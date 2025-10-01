<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Cart;
use App\Models\User;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Notifications\OrderStatusUpdateNotification;

class OrderService
{
    public function createOrderFromCart(User $user, string $paymentGateway = 'whatsapp'): Order
    {
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart || empty($cart->items)) {
            throw new \Exception('Cart is empty');
        }

        $items = $cart->items;
        $quantity = array_sum(array_map(function ($item) {
            return (int) ($item['qty'] ?? 0);
        }, $items));

        // Validate stock availability before creating order
        $this->validateStockAvailability($items);

        $subtotal = $cart->grand_total;
        $shipping = 0; // You can add shipping calculation logic here
        $grandTotal = $subtotal + $shipping;

        // Use database transaction to ensure data consistency
        return DB::transaction(function () use ($user, $items, $quantity, $subtotal, $shipping, $grandTotal, $paymentGateway, $cart) {
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'items' => $items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'grand_total' => $grandTotal,
                'quantity' => $quantity,
                'status' => Order::STATUS_UNCONFIRMED,
                'payment_gateway' => $paymentGateway,
                'shipping_address' => $user->present_address,
            ]);

            // Reduce stock for each item
            $this->reduceStock($items);

            // Clear the cart
            $cart->delete();

            // Send to Google Sheets
            $this->sendToGoogleSheets($order);

            return $order;
        });
    }

    public function sendToGoogleSheets(Order $order): void
    {
        $webhookUrl = config('services.google_sheets.webhook');
        
        if (empty($webhookUrl)) {
            return;
        }

        // Format product details for Google Sheets
        $productDetails = $this->formatProductDetailsForSheets($order->items);

        try {
            Http::timeout(10)->post($webhookUrl, [
                'Name' => $order->user->name,
                'order' => $productDetails,
                'User_id' => $order->user_id,
                'Phone number' => $order->user->phone ?? 'N/A',
                'Address' => $order->shipping_address ?? 'N/A',
                'Quantity' => $order->quantity,
                'status' => $order->status,
                'total amount' => number_format($order->grand_total, 2, '.', ''),
                'Order Number' => $order->order_number,
            ]);
        } catch (\Exception $e) {
            // Log the error but don't fail the order creation
            \Log::error('Failed to send order to Google Sheets: ' . $e->getMessage());
        }
    }

    /**
     * Format product details for Google Sheets
     */
    private function formatProductDetailsForSheets(array $items): string
    {
        $details = [];
        foreach ($items as $item) {
            // Try to get product name from different possible fields
            $productName = $item['product_name'] ?? $item['title'] ?? 'Unknown Product';
            $variant = $item['variant_name'] ?? $item['variant'] ?? '';
            $quantity = $item['qty'] ?? 1;
            $price = $item['price'] ?? 0;
            $total = $price * $quantity;
            
            $line = "{$productName}";
            if ($variant) {
                $line .= " ({$variant})";
            }
            $line .= " - Qty: {$quantity} × $" . number_format($price, 2) . " = $" . number_format($total, 2);
            
            $details[] = $line;
        }
        
        return implode(' | ', $details);
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        if (!in_array($status, [Order::STATUS_PENDING, Order::STATUS_RECEIVED, Order::STATUS_UNAVAILABLE])) {
            return false;
        }

        $oldStatus = $order->status;
        $order->update(['status' => $status]);
        
        // Send notification to user if status changed
        if ($oldStatus !== $status && $order->user) {
            $order->user->notify(new OrderStatusUpdateNotification($order, $oldStatus, $status));
        }
        
        // Update Google Sheets if status changed
        $this->updateGoogleSheetsStatus($order);
        
        return true;
    }

    private function updateGoogleSheetsStatus(Order $order): void
    {
        $webhookUrl = config('services.google_sheets.update_webhook');
        
        if (empty($webhookUrl)) {
            return;
        }

        try {
            Http::timeout(10)->post($webhookUrl, [
                'Order Number' => $order->order_number,
                'status' => $order->status,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update Google Sheets status: ' . $e->getMessage());
        }
    }

    /**
     * Validate stock availability for all items in the cart
     */
    private function validateStockAvailability(array $items): void
    {
        foreach ($items as $item) {
            $variantId = $item['variant_id'] ?? null;
            $requestedQty = (int) ($item['qty'] ?? 0);
            
            if (!$variantId || $requestedQty <= 0) {
                continue;
            }

            $variant = ProductVariant::find($variantId);
            
            if (!$variant) {
                throw new \Exception("Product variant not found for item: " . ($item['product_name'] ?? 'Unknown'));
            }

            if ($variant->stock < $requestedQty) {
                $available = $variant->stock;
                $productName = $item['product_name'] ?? $variant->product->title ?? 'Unknown Product';
                throw new \Exception("Insufficient stock for '{$productName}'. Available: {$available}, Requested: {$requestedQty}");
            }

            if (!$variant->is_active) {
                $productName = $item['product_name'] ?? $variant->product->title ?? 'Unknown Product';
                throw new \Exception("Product variant '{$productName}' is currently unavailable");
            }
        }
    }

    /**
     * Reduce stock for all items in the order
     */
    private function reduceStock(array $items): void
    {
        foreach ($items as $item) {
            $variantId = $item['variant_id'] ?? null;
            $qty = (int) ($item['qty'] ?? 0);
            
            if (!$variantId || $qty <= 0) {
                continue;
            }

            $variant = ProductVariant::find($variantId);
            
            if ($variant) {
                $variant->decrement('stock', $qty);
                
                // Log the stock reduction for admin tracking
                \Log::info("Stock reduced for variant {$variantId}: -{$qty} units. Remaining stock: {$variant->fresh()->stock}");
            }
        }
    }

    /**
     * Restore stock when an order is cancelled or failed
     */
    public function restoreStock(Order $order): void
    {
        $items = $order->items ?? [];
        
        foreach ($items as $item) {
            $variantId = $item['variant_id'] ?? null;
            $qty = (int) ($item['qty'] ?? 0);
            
            if (!$variantId || $qty <= 0) {
                continue;
            }

            $variant = ProductVariant::find($variantId);
            
            if ($variant) {
                $variant->increment('stock', $qty);
                
                // Log the stock restoration for admin tracking
                \Log::info("Stock restored for variant {$variantId}: +{$qty} units. New stock: {$variant->fresh()->stock}");
            }
        }
    }
}




<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;
use App\Models\Order;

class CheckoutController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function show()
    {
        $user = Auth::user();
        
        if ($user && $user->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders.');
        }

        // Get user's cart
        $cart = \App\Models\Cart::where('user_id', $user->id)->first();
        
        if (!$cart || empty($cart->items)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = 0;
        $items = $cart->items ?? [];
        foreach ($items as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['qty'] ?? 0);
        }
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        return view('checkout.show', compact('cart', 'subtotal', 'tax', 'total'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        
        if ($user && $user->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders.');
        }

        try {
            // Create order from cart and clear cart
            $order = $this->orderService->createOrderFromCart($user, 'cash_on_delivery');

            return redirect()->route('account.orders')->with('success', 'Order placed successfully! Order #' . $order->order_number . '. You will be contacted for confirmation.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function whatsapp(Request $request)
    {
        $user = Auth::user();
        
        if ($user && $user->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders.');
        }

        try {
            // Create order from cart and clear cart
            $order = $this->orderService->createOrderFromCart($user, 'whatsapp');

            // Prepare detailed WhatsApp message with product information
            $productDetails = $this->formatProductDetails($order->items);
            $message = "🛍️ *NEW ORDER - L'essence*\n\n" .
                "👤 *Customer:* {$user->name}\n" .
                "🆔 *User ID:* {$user->id}\n" .
                "📦 *Order #:* {$order->order_number}\n\n" .
                "🛒 *Products:*\n{$productDetails}\n" .
                "📊 *Total Quantity:* {$order->quantity} items\n" .
                "💰 *Total Amount:* $" . number_format($order->grand_total, 2) . "\n\n" .
                "📍 *Shipping Address:* " . ($order->shipping_address ?? 'Not provided') . "\n\n" .
                "Please process this order. Thank you! 🙏";
            
            // WhatsApp link with the specified phone number
            $whatsappNumber = "8801878506851";
            $whatsappLink = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);

            return redirect()->away($whatsappLink)->with('success', 'Order placed successfully! Order #' . $order->order_number);

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function facebook(Request $request)
    {
        $user = Auth::user();
        
        if ($user && $user->role === 'admin') {
            return redirect()->route('cart.index')->with('error', 'Admins cannot place orders.');
        }

        try {
            // Create order from cart and clear cart
            $order = $this->orderService->createOrderFromCart($user, 'facebook');

            // Prepare detailed Facebook Messenger message with product information
            $productDetails = $this->formatProductDetails($order->items);
            $text = rawurlencode("🛍️ *NEW ORDER - L'essence*\n\n" .
                "👤 *Customer:* {$user->name}\n" .
                "🆔 *User ID:* {$user->id}\n" .
                "📦 *Order #:* {$order->order_number}\n\n" .
                "🛒 *Products:*\n{$productDetails}\n" .
                "📊 *Total Quantity:* {$order->quantity} items\n" .
                "💰 *Total Amount:* $" . number_format($order->grand_total, 2) . "\n\n" .
                "📍 *Shipping Address:* " . ($order->shipping_address ?? 'Not provided') . "\n\n" .
                "Please process this order. Thank you! 🙏");
            
            // Facebook page link. Messenger deep link supports text param via m.me
            $fbLink = "https://m.me/lessenceperfumery?ref=order&text={$text}";

            return redirect()->away($fbLink)->with('success', 'Order placed successfully! Order #' . $order->order_number);

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    /**
     * Format product details for order messages
     */
    private function formatProductDetails(array $items): string
    {
        $details = [];
        foreach ($items as $item) {
            // Try to get product name from different possible fields
            $productName = $item['product_name'] ?? $item['title'] ?? 'Unknown Product';
            $variant = $item['variant_name'] ?? $item['variant'] ?? '';
            $quantity = $item['qty'] ?? 1;
            $price = $item['price'] ?? 0;
            $total = $price * $quantity;
            
            $line = "• {$productName}";
            if ($variant) {
                $line .= " ({$variant})";
            }
            $line .= " - Qty: {$quantity} × $" . number_format($price, 2) . " = $" . number_format($total, 2);
            
            $details[] = $line;
        }
        
        return implode("\n", $details);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use App\Notifications\OrderStatusUpdateNotification;

class OrderAdminController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with('user')->latest();
        
        // Filter by status if provided; otherwise default to non-received orders
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', Order::STATUS_RECEIVED);
        }
        
        $orders = $query->paginate(20);
        $statuses = [
            Order::STATUS_UNCONFIRMED => 'Unconfirmed',
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_RECEIVED => 'Received',
            Order::STATUS_UNAVAILABLE => 'Unavailable'
        ];
        
        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:' . Order::STATUS_UNCONFIRMED . ',' . Order::STATUS_PENDING . ',' . Order::STATUS_RECEIVED . ',' . Order::STATUS_UNAVAILABLE,
            'admin_notes' => 'nullable|string|max:1000',
            'flagged' => 'sometimes|boolean',
        ]);

        $oldStatus = $order->status;
        $success = $order->updateStatus($data['status'], $data['admin_notes'] ?? null);
        
        if (array_key_exists('flagged', $data)) {
            $order->flagged = (bool) $data['flagged'];
            $order->save();
        }

        // Send notification to user if status changed
        if ($success && $oldStatus !== $data['status'] && $order->user) {
            $order->user->notify(new OrderStatusUpdateNotification($order, $oldStatus, $data['status']));
        }

        // If order status changed to UNAVAILABLE, restore stock and send notification
        if ($success && $oldStatus !== Order::STATUS_UNAVAILABLE && $data['status'] === Order::STATUS_UNAVAILABLE) {
            $this->orderService->restoreStock($order);
            
            // Send notification to customer about unavailability
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderUnavailableNotification($order, $data['admin_notes'] ?? 'Product is currently unavailable.'));
            }
        }

        if ($success) {
            return back()->with('status', 'Order status updated to: ' . $order->status_label . ($oldStatus !== $data['status'] ? ' - User notified' : ''));
        } else {
            return back()->with('error', 'Invalid status provided');
        }
    }

    public function destroy(Request $request, Order $order)
    {
        $data = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if ($order->user) {
            $order->user->notify(new \App\Notifications\AdminMessage($data['message'], auth()->user()));
        }

        // Restore stock before deleting the order
        $this->orderService->restoreStock($order);
        
        $order->delete();
        return redirect()->route('admin.orders.index')->with('status', 'Order removed, stock restored, and user notified');
    }

    public function show(Order $order)
    {
        $order->load('user');
        return view('admin.orders.show', compact('order'));
    }

    public function history(Request $request)
    {
        $orders = Order::with('user')
            ->where('status', Order::STATUS_RECEIVED)
            ->latest()
            ->paginate(20);
        $statuses = [
            Order::STATUS_RECEIVED => 'Received',
        ];
        $isHistory = true;
        return view('admin.orders.index', compact('orders', 'statuses', 'isHistory'));
    }
}

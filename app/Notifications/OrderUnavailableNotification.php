<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderUnavailableNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $adminNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $adminNotes = null)
    {
        $this->order = $order;
        $this->adminNotes = $adminNotes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $productDetails = '';
        foreach ($this->order->items as $item) {
            // Use product_name as primary, fallback to title if not available
            $productName = $item['product_name'] ?? $item['title'] ?? 'Unknown Product';
            $productDetails .= "• {$productName}";
            
            // Use variant_name as primary, fallback to variant if not available
            $variant = $item['variant_name'] ?? $item['variant'] ?? '';
            if ($variant) {
                $productDetails .= " ({$variant})";
            }
            $productDetails .= " - Qty: {$item['qty']} × $" . number_format($item['price'], 2) . " = $" . number_format($item['price'] * $item['qty'], 2) . "\n";
        }

        return (new MailMessage)
                    ->subject('Order #' . $this->order->order_number . ' - Product Unavailable')
                    ->greeting('Dear ' . $this->order->user->name . ',')
                    ->line('We regret to inform you that your order #' . $this->order->order_number . ' cannot be fulfilled at this time due to product unavailability.')
                    ->line('Order Details:')
                    ->line($productDetails)
                    ->line('Total Amount: $' . number_format($this->order->grand_total, 2))
                    ->line('Admin Notes: ' . ($this->adminNotes ?: 'No additional notes provided.'))
                    ->line('We apologize for any inconvenience caused. Please contact us if you have any questions or would like to discuss alternative options.')
                    ->action('View Your Orders', route('account.orders'))
                    ->line('Thank you for your understanding.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'message' => 'Your order #' . $this->order->order_number . ' is unavailable',
            'admin_notes' => $this->adminNotes,
        ];
    }
}

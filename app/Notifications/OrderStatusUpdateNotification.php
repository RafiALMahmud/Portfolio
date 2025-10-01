<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusUpdateNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'unconfirmed' => 'Unconfirmed',
            'pending' => 'Pending',
            'received' => 'Received',
            'unavailable' => 'Unavailable'
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? ucfirst($this->oldStatus);
        $newStatusLabel = $statusLabels[$this->newStatus] ?? ucfirst($this->newStatus);

        return (new MailMessage)
            ->subject("Order #{$this->order->order_number} Status Update - L'essence")
            ->greeting("Hello {$notifiable->name},")
            ->line("Your order status has been updated:")
            ->line("**Order Number:** {$this->order->order_number}")
            ->line("**Previous Status:** {$oldStatusLabel}")
            ->line("**New Status:** {$newStatusLabel}")
            ->line("**Total Amount:** $" . number_format($this->order->grand_total, 2))
            ->line("**Order Date:** " . $this->order->created_at->format('M d, Y \a\t g:i A'))
            ->when($this->order->admin_notes, function ($message) {
                return $message->line("**Admin Notes:** " . $this->order->admin_notes);
            })
            ->action('View Order Details', route('account.orders'))
            ->line('Thank you for choosing L\'essence!')
            ->salutation('Best regards, The L\'essence Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'unconfirmed' => 'Unconfirmed',
            'pending' => 'Pending',
            'received' => 'Received',
            'unavailable' => 'Unavailable'
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? ucfirst($this->oldStatus);
        $newStatusLabel = $statusLabels[$this->newStatus] ?? ucfirst($this->newStatus);

        return [
            'type' => 'order_status_update',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_label' => $oldStatusLabel,
            'new_status_label' => $newStatusLabel,
            'total_amount' => $this->order->grand_total,
            'admin_notes' => $this->order->admin_notes,
            'message' => "Your order #{$this->order->order_number} status has been updated from {$oldStatusLabel} to {$newStatusLabel}.",
            'created_at' => now(),
        ];
    }
}


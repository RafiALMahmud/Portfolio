<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    const STATUS_UNCONFIRMED = 'unconfirmed';
    const STATUS_PENDING = 'pending';
    const STATUS_RECEIVED = 'received';
    const STATUS_UNAVAILABLE = 'unavailable';

    protected $fillable = [
        'user_id',
        'order_number',
        'items',
        'subtotal',
        'shipping',
        'grand_total',
        'status',
        'payment_gateway',
        'shipping_address',
        'quantity',
        'admin_notes',
        'status_updated_at',
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'quantity' => 'integer',
        'status_updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_UNCONFIRMED => 'Unconfirmed',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_RECEIVED => 'Received',
            self::STATUS_UNAVAILABLE => 'Unavailable',
            default => 'Unknown'
        };
    }

    public function isUnconfirmed(): bool
    {
        return $this->status === self::STATUS_UNCONFIRMED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isReceived(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function isUnavailable(): bool
    {
        return $this->status === self::STATUS_UNAVAILABLE;
    }

    public function isInHistory(): bool
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    public function updateStatus(string $status, string $adminNotes = null): bool
    {
        if (!in_array($status, [self::STATUS_UNCONFIRMED, self::STATUS_PENDING, self::STATUS_RECEIVED, self::STATUS_UNAVAILABLE])) {
            return false;
        }

        $this->update([
            'status' => $status,
            'admin_notes' => $adminNotes,
            'status_updated_at' => now(),
        ]);

        return true;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfumeNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'note_type',
        'note_name',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getNoteTypeLabelAttribute(): string
    {
        return match($this->note_type) {
            'top' => 'Top Notes',
            'middle' => 'Middle Notes',
            'base' => 'Base Notes',
            default => 'Unknown'
        };
    }

    public function scopeTopNotes($query)
    {
        return $query->where('note_type', 'top');
    }

    public function scopeMiddleNotes($query)
    {
        return $query->where('note_type', 'middle');
    }

    public function scopeBaseNotes($query)
    {
        return $query->where('note_type', 'base');
    }
}

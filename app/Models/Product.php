<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'title',
        'product_name',
        'slug',
        'story',
        'feature_callouts',
        'main_image',
        'gallery_images',
        'is_published',
        'discount_percentage',
    ];

    protected $casts = [
        'feature_callouts' => 'array',
        'gallery_images' => 'array',
        'is_published' => 'boolean',
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function perfumeNotes(): HasMany
    {
        return $this->hasMany(PerfumeNote::class)->orderBy('note_type')->orderBy('sort_order');
    }

    public function topNotes(): HasMany
    {
        return $this->hasMany(PerfumeNote::class)->where('note_type', 'top')->orderBy('sort_order');
    }

    public function middleNotes(): HasMany
    {
        return $this->hasMany(PerfumeNote::class)->where('note_type', 'middle')->orderBy('sort_order');
    }

    public function baseNotes(): HasMany
    {
        return $this->hasMany(PerfumeNote::class)->where('note_type', 'base')->orderBy('sort_order');
    }

    /**
     * Calculate discounted price for a given price
     */
    public function getDiscountedPrice($price)
    {
        if (!$this->discount_percentage || $this->discount_percentage <= 0) {
            return $price;
        }
        
        $discountAmount = $price * ($this->discount_percentage / 100);
        return $price - $discountAmount;
    }

    /**
     * Get formatted discounted price
     */
    public function getFormattedDiscountedPrice($price)
    {
        return number_format($this->getDiscountedPrice($price), 2);
    }

    /**
     * Check if product has discount
     */
    public function hasDiscount()
    {
        return $this->discount_percentage && $this->discount_percentage > 0;
    }
}

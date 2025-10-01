<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function home()
    {
        // First try to get discounted products
        $discountedProducts = Product::with(['variants', 'perfumeNotes'])
            ->where('is_published', true)
            ->where('discount_percentage', '>', 0)
            ->whereNotNull('discount_percentage')
            ->take(6)
            ->get();
        
        // If we have discounted products, use them; otherwise get all products
        if ($discountedProducts->count() > 0) {
            $featuredProducts = $discountedProducts;
        } else {
            $featuredProducts = Product::with(['variants', 'perfumeNotes'])
                ->where('is_published', true)
                ->take(6)
                ->get();
        }
            
        return view('welcome', compact('featuredProducts'));
    }
    
    public function index()
    {
        $products = Product::with(['variants', 'perfumeNotes'])
            ->where('is_published', true)
            ->paginate(12);
            
        return view('shop.index', compact('products'));
    }
}

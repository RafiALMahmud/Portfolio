<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['variants' => function ($q) {
            $q->where('is_active', true);
        }])->where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('product.show', compact('product'));
    }
}

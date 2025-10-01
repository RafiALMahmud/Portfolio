<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
        
        // Check if variant is active
        if (!$variant->is_active) {
            return back()->with('error', 'This product variant is currently unavailable.');
        }
        
        $cart = Cart::firstOrCreate(['user_id' => $user->id], ['items' => [], 'grand_total' => 0]);
        
        $items = $cart->items ?? [];
        $existingItem = collect($items)->firstWhere('variant_id', $data['variant_id']);
        
        // Calculate total quantity that would be in cart after adding
        $currentQty = $existingItem ? $existingItem['qty'] : 0;
        $newTotalQty = $currentQty + $data['quantity'];
        
        // Check stock availability
        if ($variant->stock < $newTotalQty) {
            $available = $variant->stock;
            $productName = $variant->product->product_name ?? $variant->product->title;
            return back()->with('error', "Insufficient stock for '{$productName}'. Available: {$available}, Requested: {$newTotalQty}");
        }
        
        if ($existingItem) {
            $existingItem['qty'] += $data['quantity'];
        } else {
            $items[] = [
                'variant_id' => $data['variant_id'],
                'product_name' => $variant->product->product_name ?? $variant->product->title,
                'variant_name' => $variant->name,
                'price' => $variant->price,
                'qty' => $data['quantity']
            ];
        }
        
        $cart->items = $items;
        $cart->grand_total = collect($items)->sum(function($item) {
            return $item['price'] * $item['qty'];
        });
        $cart->save();

        return back()->with('status', 'Item added to cart');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();
        
        if (!$cart) {
            return back()->with('error', 'Cart not found');
        }

        $items = $cart->items ?? [];
        
        if ($data['quantity'] == 0) {
            $items = collect($items)->reject(function($item) use ($data) {
                return $item['variant_id'] == $data['variant_id'];
            })->values()->toArray();
        } else {
            // Check stock availability before updating quantity
            $variant = ProductVariant::find($data['variant_id']);
            if ($variant && $variant->stock < $data['quantity']) {
                $available = $variant->stock;
                $productName = $variant->product->product_name ?? $variant->product->title;
                return back()->with('error', "Insufficient stock for '{$productName}'. Available: {$available}, Requested: {$data['quantity']}");
            }
            
            $items = collect($items)->map(function($item) use ($data) {
                if ($item['variant_id'] == $data['variant_id']) {
                    $item['qty'] = $data['quantity'];
                }
                return $item;
            })->toArray();
        }
        
        $cart->items = $items;
        $cart->grand_total = collect($items)->sum(function($item) {
            return $item['price'] * $item['qty'];
        });
        $cart->save();

        return back()->with('status', 'Cart updated');
    }

    public function clear()
    {
        $user = Auth::user();
        Cart::where('user_id', $user->id)->delete();
        
        return back()->with('status', 'Cart cleared');
    }
}

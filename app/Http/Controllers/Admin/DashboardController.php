<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        $recentOrders = Order::latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'totalOrders','pendingOrders','totalUsers','totalProducts','recentOrders'
        ));
    }
}

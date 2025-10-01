<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing products to set product_name = title where product_name is null
        \DB::table('products')
            ->whereNull('product_name')
            ->update(['product_name' => \DB::raw('title')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Set product_name to null for products where product_name equals title
        \DB::table('products')
            ->whereColumn('product_name', 'title')
            ->update(['product_name' => null]);
    }
};

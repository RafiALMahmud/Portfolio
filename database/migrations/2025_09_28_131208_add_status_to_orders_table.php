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
        Schema::table('orders', function (Blueprint $table) {
            // Drop the existing status column and recreate with new values
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['unconfirmed', 'pending', 'received', 'unavailable'])->default('unconfirmed')->after('payment_gateway');
            $table->text('admin_notes')->nullable()->after('status');
            $table->timestamp('status_updated_at')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_notes', 'status_updated_at']);
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending','paid','failed','cancelled','shipped','completed'])->default('pending');
        });
    }
};

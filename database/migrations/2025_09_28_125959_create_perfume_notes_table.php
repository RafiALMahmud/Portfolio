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
        Schema::create('perfume_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('note_type', ['top', 'middle', 'base'])->comment('Top notes, Middle notes, Base notes');
            $table->string('note_name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['product_id', 'note_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_notes');
    }
};

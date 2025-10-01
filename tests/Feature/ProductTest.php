<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PerfumeNote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_home_page()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_shop()
    {
        $response = $this->get('/shop');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_shop()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create(['is_published' => true]);
        ProductVariant::factory()->create(['product_id' => $product->id]);

        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_user_can_view_product_details()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create([
            'slug' => 'test-product',
            'is_published' => true,
        ]);
        ProductVariant::factory()->create([
            'product_id' => $product->id,
            'is_active' => true,
        ]);

        $response = $this->get('/product/test-product');
        $response->assertStatus(200);
        $response->assertViewHas('product');
    }

    public function test_product_relationships_work()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        $note = PerfumeNote::factory()->create(['product_id' => $product->id]);

        $this->assertTrue($product->variants->contains($variant));
        $this->assertTrue($product->perfumeNotes->contains($note));
    }

    public function test_product_variant_stock_validation()
    {
        $variant = ProductVariant::factory()->create(['stock' => 5]);

        $this->assertTrue($variant->stock >= 0);
        $this->assertIsInt($variant->stock);
    }

    public function test_perfume_notes_are_ordered_correctly()
    {
        $product = Product::factory()->create();
        
        PerfumeNote::factory()->create([
            'product_id' => $product->id,
            'note_type' => 'base',
            'sort_order' => 3,
        ]);
        
        PerfumeNote::factory()->create([
            'product_id' => $product->id,
            'note_type' => 'top',
            'sort_order' => 1,
        ]);
        
        PerfumeNote::factory()->create([
            'product_id' => $product->id,
            'note_type' => 'middle',
            'sort_order' => 2,
        ]);

        $notes = $product->perfumeNotes;
        $this->assertEquals('top', $notes[0]->note_type);
        $this->assertEquals('middle', $notes[1]->note_type);
        $this->assertEquals('base', $notes[2]->note_type);
    }
}


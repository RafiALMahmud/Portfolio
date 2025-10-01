<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_user_can_add_item_to_cart()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        $response = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNotNull($cart);
        $this->assertCount(1, $cart->items);
    }

    public function test_user_can_update_cart_item()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add item first
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Update quantity
        $response = $this->post('/cart/update', [
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertEquals(5, $cart->items[0]['qty']);
    }

    public function test_user_can_remove_item_from_cart()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add item first
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Remove item by setting quantity to 0
        $response = $this->post('/cart/update', [
            'variant_id' => $variant->id,
            'quantity' => 0,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertEmpty($cart->items);
    }

    public function test_user_can_clear_cart()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add item first
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        // Clear cart
        $response = $this->post('/cart/clear');

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNull($cart);
    }

    public function test_cart_validates_stock_availability()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 5,
            'is_active' => true,
        ]);

        $response = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 10, // More than available stock
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cart_validates_inactive_variants()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => false,
        ]);

        $response = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cart_calculates_totals_correctly()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 50.00,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 3,
        ]);

        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertEquals(150.00, $cart->grand_total);
    }

    public function test_admin_cannot_access_cart()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get('/cart');
        $response->assertStatus(403);
    }
}

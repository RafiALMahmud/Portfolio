<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_user_can_view_checkout_page()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add item to cart
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->get('/checkout');
        $response->assertStatus(200);
        $response->assertViewHas(['cart', 'subtotal', 'tax', 'total']);
    }

    public function test_user_cannot_checkout_empty_cart()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/checkout');
        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }

    public function test_user_can_place_order()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
            'price' => 50.00,
        ]);

        // Add item to cart
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);

        $response = $this->post('/checkout');

        $response->assertRedirect('/orders');
        $response->assertSessionHas('success');

        // Check order was created
        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(Order::STATUS_UNCONFIRMED, $order->status);
        $this->assertEquals(2, $order->quantity);
        $this->assertEquals(100.00, $order->grand_total);

        // Check cart was cleared
        $cart = Cart::where('user_id', $user->id)->first();
        $this->assertNull($cart);

        // Check stock was reduced
        $variant->refresh();
        $this->assertEquals(8, $variant->stock);
    }

    public function test_user_can_place_facebook_order()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        // Add item to cart
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 1,
        ]);

        $response = $this->get('/checkout/facebook');

        $response->assertRedirect();
        $this->assertStringContainsString('m.me/lessenceperfumery', $response->getTargetUrl());

        // Check order was created
        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('facebook', $order->payment_gateway);
    }

    public function test_admin_cannot_place_orders()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get('/checkout');
        $response->assertStatus(403);
    }

    public function test_order_creation_with_insufficient_stock_fails()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 1,
            'is_active' => true,
        ]);

        // Add more items than available stock
        $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 5,
        ]);

        $response = $this->post('/checkout');
        $response->assertRedirect('/cart');
        $response->assertSessionHas('error');
    }

    public function test_order_status_constants_work()
    {
        $order = new Order();
        
        $this->assertEquals('unconfirmed', Order::STATUS_UNCONFIRMED);
        $this->assertEquals('pending', Order::STATUS_PENDING);
        $this->assertEquals('received', Order::STATUS_RECEIVED);
        $this->assertEquals('unavailable', Order::STATUS_UNAVAILABLE);
    }

    public function test_order_status_methods_work()
    {
        $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);
        
        $this->assertTrue($order->isPending());
        $this->assertFalse($order->isUnconfirmed());
        $this->assertFalse($order->isReceived());
        $this->assertFalse($order->isUnavailable());
    }

    public function test_order_can_update_status()
    {
        $order = Order::factory()->create(['status' => Order::STATUS_UNCONFIRMED]);
        
        $result = $order->updateStatus(Order::STATUS_PENDING, 'Admin notes');
        
        $this->assertTrue($result);
        $this->assertEquals(Order::STATUS_PENDING, $order->fresh()->status);
        $this->assertEquals('Admin notes', $order->fresh()->admin_notes);
    }

    public function test_order_status_label_works()
    {
        $order = Order::factory()->create(['status' => Order::STATUS_PENDING]);
        
        $this->assertEquals('Pending', $order->status_label);
    }

    public function test_order_number_is_generated_automatically()
    {
        $order = Order::factory()->create();
        
        $this->assertStringStartsWith('ORD-', $order->order_number);
        $this->assertEquals(12, strlen($order->order_number)); // ORD- + 8 chars
    }
}

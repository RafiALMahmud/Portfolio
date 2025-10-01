<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Cart;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function test_can_create_order_from_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
            'price' => 50.00,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'items' => [
                [
                    'variant_id' => $variant->id,
                    'product_name' => $product->title,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'qty' => 2,
                ]
            ],
            'grand_total' => 100.00,
        ]);

        $order = $this->orderService->createOrderFromCart($user, 'whatsapp');

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals(100.00, $order->grand_total);
        $this->assertEquals(2, $order->quantity);
        $this->assertEquals('whatsapp', $order->payment_gateway);
        $this->assertEquals(Order::STATUS_UNCONFIRMED, $order->status);

        // Check cart was deleted
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        // Check stock was reduced
        $variant->refresh();
        $this->assertEquals(8, $variant->stock);
    }

    public function test_throws_exception_for_empty_cart()
    {
        $user = User::factory()->create();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart is empty');

        $this->orderService->createOrderFromCart($user, 'whatsapp');
    }

    public function test_throws_exception_for_insufficient_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 1,
            'is_active' => true,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'items' => [
                [
                    'variant_id' => $variant->id,
                    'product_name' => $product->title,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'qty' => 5, // More than available stock
                ]
            ],
            'grand_total' => 250.00,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient stock');

        $this->orderService->createOrderFromCart($user, 'whatsapp');
    }

    public function test_throws_exception_for_inactive_variant()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => false,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'items' => [
                [
                    'variant_id' => $variant->id,
                    'product_name' => $product->title,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'qty' => 2,
                ]
            ],
            'grand_total' => 100.00,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('currently unavailable');

        $this->orderService->createOrderFromCart($user, 'whatsapp');
    }

    public function test_can_restore_stock()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'stock' => 10,
            'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-TEST123',
            'items' => [
                [
                    'variant_id' => $variant->id,
                    'product_name' => $product->title,
                    'variant_name' => $variant->name,
                    'price' => $variant->price,
                    'qty' => 3,
                ]
            ],
            'subtotal' => 150.00,
            'shipping' => 0,
            'grand_total' => 150.00,
            'quantity' => 3,
            'status' => Order::STATUS_UNCONFIRMED,
            'payment_gateway' => 'whatsapp',
            'shipping_address' => 'Test Address',
        ]);

        // Reduce stock first
        $variant->decrement('stock', 3);
        $this->assertEquals(7, $variant->fresh()->stock);

        // Restore stock
        $this->orderService->restoreStock($order);

        $variant->refresh();
        $this->assertEquals(10, $variant->stock);
    }

    public function test_can_update_order_status()
    {
        $order = Order::factory()->create(['status' => Order::STATUS_UNCONFIRMED]);

        $result = $this->orderService->updateOrderStatus($order, Order::STATUS_PENDING);

        $this->assertTrue($result);
        $this->assertEquals(Order::STATUS_PENDING, $order->fresh()->status);
    }

    public function test_cannot_update_to_invalid_status()
    {
        $order = Order::factory()->create(['status' => Order::STATUS_UNCONFIRMED]);

        $result = $this->orderService->updateOrderStatus($order, 'invalid_status');

        $this->assertFalse($result);
        $this->assertEquals(Order::STATUS_UNCONFIRMED, $order->fresh()->status);
    }
}


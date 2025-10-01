<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\PerfumeNote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertViewHas(['totalOrders', 'pendingOrders', 'totalUsers', 'totalProducts', 'recentOrders']);
    }

    public function test_regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_orders()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $order = Order::factory()->create();

        $response = $this->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    public function test_admin_can_filter_orders_by_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        Order::factory()->create(['status' => Order::STATUS_PENDING]);
        Order::factory()->create(['status' => Order::STATUS_RECEIVED]);

        $response = $this->get('/admin/orders?status=' . Order::STATUS_PENDING);
        $response->assertStatus(200);
        
        $orders = $response->viewData('orders');
        $this->assertCount(1, $orders->items());
    }

    public function test_admin_can_update_order_status()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $order = Order::factory()->create(['status' => Order::STATUS_UNCONFIRMED]);

        $response = $this->patch("/admin/orders/{$order->id}", [
            'status' => Order::STATUS_PENDING,
            'admin_notes' => 'Order confirmed',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $order->refresh();
        $this->assertEquals(Order::STATUS_PENDING, $order->status);
        $this->assertEquals('Order confirmed', $order->admin_notes);
    }

    public function test_admin_can_delete_order()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $order = Order::factory()->create();

        $response = $this->delete("/admin/orders/{$order->id}", [
            'message' => 'Order cancelled due to unavailability',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_admin_can_view_order_details()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $order = Order::factory()->create();

        $response = $this->get("/admin/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertViewHas('order');
    }

    public function test_admin_can_view_products()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $product = Product::factory()->create();

        $response = $this->get('/admin/products');
        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_admin_can_create_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $productData = [
            'title' => 'Test Perfume',
            'product_name' => 'Test Perfume Name',
            'story' => 'A beautiful fragrance',
            'price' => 100.00,
            'quantity' => 50,
            'is_published' => true,
            'perfume_notes' => [
                [
                    'note_type' => 'top',
                    'note_name' => 'Citrus',
                    'description' => 'Fresh citrus notes',
                    'sort_order' => 1,
                ],
                [
                    'note_type' => 'middle',
                    'note_name' => 'Rose',
                    'description' => 'Floral rose notes',
                    'sort_order' => 2,
                ],
                [
                    'note_type' => 'base',
                    'note_name' => 'Sandalwood',
                    'description' => 'Warm base notes',
                    'sort_order' => 3,
                ],
            ],
        ];

        $response = $this->post('/admin/products', $productData);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('products', [
            'title' => 'Test Perfume',
            'product_name' => 'Test Perfume Name',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'price' => 100.00,
            'stock' => 50,
        ]);

        $this->assertDatabaseHas('perfume_notes', [
            'note_name' => 'Citrus',
            'note_type' => 'top',
        ]);
    }

    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $product = Product::factory()->create();

        $response = $this->patch("/admin/products/{$product->id}", [
            'is_published' => false,
            'discount_percentage' => 20,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $product->refresh();
        $this->assertFalse($product->is_published);
        $this->assertEquals(20, $product->discount_percentage);
    }

    public function test_admin_can_add_perfume_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $product = Product::factory()->create();

        $response = $this->post("/admin/products/{$product->id}/perfume-notes", [
            'note_type' => 'top',
            'note_name' => 'Bergamot',
            'description' => 'Fresh bergamot notes',
            'sort_order' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('perfume_notes', [
            'product_id' => $product->id,
            'note_name' => 'Bergamot',
            'note_type' => 'top',
        ]);
    }

    public function test_admin_can_update_perfume_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $product = Product::factory()->create();
        $note = PerfumeNote::factory()->create(['product_id' => $product->id]);

        $response = $this->patch("/admin/perfume-notes/{$note->id}", [
            'note_type' => 'middle',
            'note_name' => 'Updated Note',
            'description' => 'Updated description',
            'sort_order' => 2,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $note->refresh();
        $this->assertEquals('Updated Note', $note->note_name);
        $this->assertEquals('middle', $note->note_type);
    }

    public function test_admin_can_delete_perfume_note()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $product = Product::factory()->create();
        $note = PerfumeNote::factory()->create(['product_id' => $product->id]);

        $response = $this->delete("/admin/perfume-notes/{$note->id}");

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('perfume_notes', ['id' => $note->id]);
    }
}

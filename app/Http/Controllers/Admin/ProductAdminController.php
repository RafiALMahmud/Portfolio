<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PerfumeNote;
use Illuminate\Support\Str;

class ProductAdminController extends Controller
{
    public function index()
    {
        $products = Product::with(['variants', 'perfumeNotes'])->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'product_name' => ['nullable','string','max:255'],
            'story' => ['nullable','string'],
            'price' => ['required','numeric','min:0'],
            'quantity' => ['required','integer','min:0'],
            'main_image' => ['nullable','image','max:2048'],
            'is_published' => ['sometimes','boolean'],
            'discount_percentage' => ['nullable','numeric','min:0','max:100'],
            'perfume_notes' => ['required','array','min:1'],
            'perfume_notes.*.note_type' => ['required','in:top,middle,base'],
            'perfume_notes.*.note_name' => ['required','string','max:255'],
            'perfume_notes.*.description' => ['nullable','string'],
            'perfume_notes.*.sort_order' => ['nullable','integer','min:0'],
        ]);

        $imagePath = null;
        if ($request->hasFile('main_image')) {
            $imagePath = $request->file('main_image')->store('products', 'public');
        }

        $product = Product::create([
            'sku' => 'SKU-'.strtoupper(Str::random(8)),
            'title' => $data['title'],
            'product_name' => $data['product_name'] ?? $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::random(6),
            'story' => $data['story'] ?? null,
            'main_image' => $imagePath,
            'is_published' => (bool)($data['is_published'] ?? true),
            'feature_callouts' => null,
            'gallery_images' => null,
        ]);

        if (isset($data['discount_percentage'])) {
            $product->discount_percentage = $data['discount_percentage'];
            $product->save();
        }

        ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Default',
            'size' => null,
            'concentration' => null,
            'price' => $data['price'],
            'stock' => $data['quantity'],
            'is_active' => true,
        ]);

        // Create perfume notes (at least 1 is required)
        foreach ($data['perfume_notes'] as $noteData) {
            if (!empty($noteData['note_name'])) {
                PerfumeNote::create([
                    'product_id' => $product->id,
                    'note_type' => $noteData['note_type'],
                    'note_name' => $noteData['note_name'],
                    'description' => $noteData['description'] ?? null,
                    'sort_order' => $noteData['sort_order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('status', 'Product created');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'is_published' => 'boolean',
            'discount_percentage' => 'nullable|numeric|min:0|max:100'
        ]);

        $product->update($data);
        
        return back()->with('status', 'Product updated successfully');
    }

    public function addPerfumeNote(Request $request, Product $product)
    {
        $data = $request->validate([
            'note_type' => 'required|in:top,middle,base',
            'note_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $perfumeNote = $product->perfumeNotes()->create([
            'note_type' => $data['note_type'],
            'note_name' => $data['note_name'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return back()->with('status', 'Perfume note added successfully');
    }

    public function updatePerfumeNote(Request $request, PerfumeNote $perfumeNote)
    {
        $data = $request->validate([
            'note_type' => 'required|in:top,middle,base',
            'note_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $perfumeNote->update($data);

        return back()->with('status', 'Perfume note updated successfully');
    }

    public function deletePerfumeNote(PerfumeNote $perfumeNote)
    {
        $perfumeNote->delete();

        return back()->with('status', 'Perfume note deleted successfully');
    }
}

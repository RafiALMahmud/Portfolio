<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:1100px;margin:32px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:16px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #2a2a2a;text-align:left}
        input,select{
            width:100%;
            padding:8px;
            border-radius:6px;
            border:1px solid #2a2a2a;
            background:#0f0f0f;
            color:#fff;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #e7d2b8;
            background: #1a1a1a;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(231, 210, 184, 0.2);
        }
        input:hover, select:hover {
            border-color: #555;
            background: #1a1a1a;
        }
        .btn{
            background:#e7d2b8;
            color:#111;
            padding:8px 12px;
            border-radius:6px;
            border:none;
            cursor:pointer;
            font-weight:600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn:hover {
            background: #d4c4a8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 210, 184, 0.3);
        }
        .btn:active {
            transform: translateY(0);
        }
        .btn-sm{padding:6px 10px;font-size:14px}
        .status{display:inline-block;padding:4px 8px;border-radius:4px;font-size:12px;font-weight:600}
        .status.published{background:#1a4d1a;color:#90ee90}
        .status.unpublished{background:#4d1a1a;color:#ffb4b4}
        a{
            color:#e7d2b8;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
        }
        a:hover {
            color: #d4c4a8;
            background: rgba(231, 210, 184, 0.1);
            transform: translateY(-1px);
        }
        .success{color:#90ee90;font-size:14px;margin:8px 0}
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Dashboard
            </a>
            <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9,22 9,12 15,12 15,22"/>
                </svg>
                Home
            </a>
        </div>
        
        <h1>Products Management</h1>
        
        @if(session('status'))
            <div class="success">{{ session('status') }}</div>
        @endif

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Status</th>
                        <th>Stock</th>
                        <th>Discount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                            <strong>{{ $product->product_name ?? $product->title }}</strong><br>
                            <small style="color:#9f9f9f">{{ $product->sku }}</small>
                        </td>
                        <td>
                            <span class="status {{ $product->is_published ? 'published' : 'unpublished' }}">
                                {{ $product->is_published ? 'Published' : 'Unpublished' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $totalStock = $product->variants->sum('stock');
                            @endphp
                            @if($totalStock > 0)
                                <span style="color: #90ee90;">{{ $totalStock }} units</span>
                            @else
                                <span style="color: #ff6b6b;">Out of Stock</span>
                            @endif
                        </td>
                        <td>{{ $product->discount_percentage ?? 0 }}%</td>
                        <td>
                            <form method="POST" action="{{ route('admin.products.update', $product) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <select name="is_published" onchange="this.form.submit()">
                                    <option value="1" {{ $product->is_published ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ !$product->is_published ? 'selected' : '' }}>Unpublished</option>
                                </select>
                            </form>
                            <form method="POST" action="{{ route('admin.products.update', $product) }}" style="display:inline;margin-left:8px;">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="discount_percentage" value="{{ $product->discount_percentage ?? 0 }}" 
                                       min="0" max="100" step="0.01" style="width:80px;display:inline-block;">
                                <button type="submit" class="btn btn-sm">Set Discount</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:12px">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</body>
</html>

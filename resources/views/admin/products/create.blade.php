<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product - Admin</title>
    <style>
        body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:#0b0b0b;color:#e9e9e9;margin:0}
        .wrap{max-width:720px;margin:48px auto;padding:24px}
        h1{font-weight:700;margin:0 0 16px}
        .card{background:#141414;border:1px solid #222;border-radius:12px;padding:24px}
        label{display:block;margin:12px 0 6px;color:#cfcfcf}
        input,textarea,select{width:100%;padding:12px 14px;border-radius:8px;border:1px solid #2a2a2a;background:#0f0f0f;color:#fff;box-sizing:border-box}
        .btn{background:#e7d2b8;color:#111;padding:12px 16px;border-radius:8px;border:none;font-weight:600;margin-top:16px;cursor:pointer}
        a{color:#e7d2b8}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .error{color:#ffb4b4;font-size:14px}
        /* Hide number input spinners */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
    </style>
</head>
<body>
    <div class="wrap">
        <!-- Navigation -->
        <div style="margin-bottom: 2rem; display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('admin.products.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #e7d2b8; text-decoration: none; font-weight: 500; transition: color 0.3s;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Products
            </a>
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
        
        <h1>Create Product</h1>
        <div class="card">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title') }}" required>
                @error('title')<div class="error">{{ $message }}</div>@enderror

                <label for="product_name">Product Name (Optional)</label>
                <input id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="Leave empty to use title">
                @error('product_name')<div class="error">{{ $message }}</div>@enderror

                <label for="story">Description</label>
                <textarea id="story" name="story">{{ old('story') }}</textarea>

                <div class="row">
                    <div>
                        <label for="price">Price</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price') }}" required>
                        @error('price')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="quantity">Quantity</label>
                        <input id="quantity" name="quantity" type="number" min="0" value="{{ old('quantity') }}" required>
                        @error('quantity')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div>
                        <label for="discount_percentage">Discount %</label>
                        <input id="discount_percentage" name="discount_percentage" type="number" step="0.01" min="0" max="100" value="{{ old('discount_percentage', 0) }}">
                    </div>
                    <div>
                        <label for="is_published">Available</label>
                        <select id="is_published" name="is_published">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <label for="main_image">Image</label>
                <input id="main_image" name="main_image" type="file" accept="image/*">

                <!-- Perfume Notes Section -->
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #2a2a2a;">
                    <h3 style="color: #e7d2b8; margin-bottom: 1rem;">Perfume Notes <span style="color: #ff6b6b;">*</span></h3>
                    <p style="color: #9f9f9f; font-size: 0.9rem; margin-bottom: 1rem;">
                        Add at least one fragrance note to help customers understand the scent profile of this perfume. You can add multiple notes for different note types (Top, Middle, Base).
                    </p>
                    
                    <div id="perfume-notes-container">
                        <!-- Default note will be added here -->
                    </div>
                    
                    <button type="button" id="add-note-btn" style="background: #2a2a2a; color: #e7d2b8; padding: 0.5rem 1rem; border: 1px solid #444; border-radius: 6px; cursor: pointer; margin-top: 1rem;">
                        + Add Another Note
                    </button>
                </div>

                <button class="btn" type="submit">Create</button>
            </form>
        </div>

        <p style="margin-top:16px;color:#9f9f9f">
            <a href="{{ route('admin.products.index') }}">Back to products</a>
        </p>
    </div>

    <script>
        let noteIndex = 0;
        
        function createNoteDiv(isRequired = false) {
            const noteDiv = document.createElement('div');
            noteDiv.style.cssText = 'background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;';
            noteDiv.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                    <div>
                        <label style="color: #cfcfcf; font-size: 0.9rem;">Note Type <span style="color: #ff6b6b;">*</span></label>
                        <select name="perfume_notes[${noteIndex}][note_type]" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #2a2a2a; background: #0f0f0f; color: #fff;">
                            <option value="top">Top Notes</option>
                            <option value="middle" selected>Middle Notes</option>
                            <option value="base">Base Notes</option>
                        </select>
                    </div>
                    <div>
                        <label style="color: #cfcfcf; font-size: 0.9rem;">Note Name <span style="color: #ff6b6b;">*</span></label>
                        <input type="text" name="perfume_notes[${noteIndex}][note_name]" placeholder="e.g., Rose, Vanilla, Sandalwood" required style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #2a2a2a; background: #0f0f0f; color: #fff;">
                    </div>
                    <div>
                        <label style="color: #cfcfcf; font-size: 0.9rem;">Description (Optional)</label>
                        <input type="text" name="perfume_notes[${noteIndex}][description]" placeholder="Brief description" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #2a2a2a; background: #0f0f0f; color: #fff;">
                    </div>
                    <div>
                        ${isRequired ? 
                            '<span style="color: #9f9f9f; font-size: 0.8rem; padding: 8px 12px;">Required</span>' : 
                            '<button type="button" onclick="removeNote(this)" style="background: #dc3545; color: #fff; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer;">Remove</button>'
                        }
                    </div>
                </div>
            `;
            return noteDiv;
        }
        
        function removeNote(button) {
            const container = document.getElementById('perfume-notes-container');
            if (container.children.length > 1) {
                button.parentElement.parentElement.parentElement.remove();
            } else {
                alert('At least one perfume note is required.');
            }
        }
        
        function addNote() {
            const container = document.getElementById('perfume-notes-container');
            const noteDiv = createNoteDiv(false);
            container.appendChild(noteDiv);
            noteIndex++;
        }
        
        // Add default required note on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('perfume-notes-container');
            const defaultNote = createNoteDiv(true);
            container.appendChild(defaultNote);
            noteIndex++;
        });
        
        document.getElementById('add-note-btn').addEventListener('click', addNote);
    </script>
</body>
</html>



@extends('layouts.app')

@section('title', 'SmartCafe - Products')
@section('page_title', 'Product Catalog')

@section('content')
<!-- Header Actions Area -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h3 class="text-lg font-bold text-slate-950 dark:text-white">Active Product Inventory</h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Manage cafe beverages, snacks, toppings, and pricing</p>
    </div>
    <button onclick="openAddModal()" class="flex items-center justify-center gap-2 px-5 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer">
        <i class="fas fa-plus"></i>
        <span>Add Product</span>
    </button>
</div>

<!-- Products Table Card -->
<div class="bg-white dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50 rounded-[2rem] overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left text-sm">
            <thead>
                <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 font-semibold">
                    <th class="px-6 py-4.5 font-bold">Image</th>
                    <th class="px-6 py-4.5 font-bold">Product Name</th>
                    <th class="px-6 py-4.5 font-bold">Price</th>
                    <th class="px-6 py-4.5 font-bold">Category</th>
                    <th class="px-6 py-4.5 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                @foreach ($pro as $p)
                <tr data-id="{{ $p->id }}" class="hover:bg-slate-50/40 dark:hover:bg-slate-800/10 transition-colors duration-150">
                    <!-- Product Image -->
                    <td class="px-6 py-4">
                        <div class="relative w-12 h-12 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800/80 border border-slate-200/50 dark:border-slate-800/50">
                            <img src="{{ asset('img/' . $p->img) }}" alt="{{ $p->name }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <!-- Product Name & ID -->
                    <td class="px-6 py-4">
                        <span class="font-semibold text-slate-900 dark:text-white block">{{ $p->name }}</span>
                        <span class="text-xs text-slate-400 dark:text-slate-500">ID: #{{ $p->id }}</span>
                    </td>
                    <!-- Product Price -->
                    <td class="px-6 py-4 font-bold text-slate-900 dark:text-slate-100">
                        ${{ number_format($p->price, 2) }}
                    </td>
                    <!-- Product Category Badge -->
                    <td class="px-6 py-4">
                        @if($p->cat_id == 1)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-cyan-50 dark:bg-cyan-950/20 text-cyan-700 dark:text-cyan-400 text-xs font-semibold rounded-full border border-cyan-100 dark:border-cyan-950/30">
                                <i class="fas fa-snowflake text-[10px]"></i>
                                Ice
                            </span>
                        @elseif($p->cat_id == 2)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-full border border-amber-100 dark:border-amber-950/30">
                                <i class="fas fa-mug-hot text-[10px]"></i>
                                Hot
                            </span>
                        @elseif($p->cat_id == 3)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 dark:bg-purple-950/20 text-purple-700 dark:text-purple-400 text-xs font-semibold rounded-full border border-purple-100 dark:border-purple-950/30">
                                <i class="fas fa-blender text-[10px]"></i>
                                Frappe
                            </span>
                        @elseif($p->cat_id == 4)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 text-xs font-semibold rounded-full border border-emerald-100 dark:border-emerald-950/30">
                                <i class="fas fa-ice-cream text-[10px]"></i>
                                Topping
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 dark:bg-slate-950/20 text-slate-700 dark:text-slate-400 text-xs font-semibold rounded-full border border-slate-100 dark:border-slate-950/30">
                                Category: {{ $p->cat_id }}
                            </span>
                        @endif
                    </td>
                    <!-- Product Actions -->
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Edit Button -->
                            <button 
                                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-900/50 flex items-center justify-center transition-all cursor-pointer hover:scale-105 active:scale-95"
                                onclick="editProduct(this)"
                                data-id="{{ $p->id }}"
                                data-cat="{{ $p->cat_id }}"
                            >
                                <i class="fas fa-pen-to-square text-xs"></i>
                            </button>
                            <!-- Delete Button -->
                            <button 
                                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:border-rose-200 dark:hover:border-rose-900/50 flex items-center justify-center transition-all cursor-pointer hover:scale-105 active:scale-95"
                                onclick="deleteProduct({{ $p->id }})"
                            >
                                <i class="fas fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add Product -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-md hidden transition-opacity duration-300" id="productModal">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300">
        <!-- Title -->
        <div class="flex items-center justify-between px-8 py-6 border-b border-slate-200/50 dark:border-slate-800/50">
            <h3 class="text-xl font-bold text-slate-950 dark:text-white">Add Product</h3>
            <button onclick="closeAddModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Form -->
        <form action="{{ route('product.add') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            @csrf
            
            <!-- Custom File Upload Area -->
            <div class="space-y-1.5">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Product Image</span>
                <label class="flex flex-col items-center justify-center w-full h-40 bg-slate-50 dark:bg-slate-800/40 border-2 border-dashed border-slate-200 dark:border-slate-700/80 rounded-[1.5rem] cursor-pointer hover:bg-slate-100/50 dark:hover:bg-slate-850/50 transition-colors duration-200 group relative">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="add-upload-placeholder">
                        <i class="fas fa-cloud-arrow-up text-3xl text-slate-400 group-hover:scale-110 transition-transform duration-200 mb-2"></i>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Upload Image</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">PNG, JPG, WEBP formats</p>
                    </div>
                    <img id="add-img-preview" class="w-full h-full object-cover rounded-[1.4rem] absolute inset-0 hidden">
                    <input type="file" name="img" id="prodImage" required class="hidden" onchange="previewImage(this, 'add-img-preview', 'add-upload-placeholder')">
                </label>
            </div>

            <div class="space-y-1.5">
                <label for="add-name" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Product Name</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-mug-hot text-slate-400 ml-4"></i>
                    <input type="text" name="name" id="add-name" placeholder="Cappuccino" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="add-price" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Price (USD)</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-dollar-sign text-slate-400 ml-4"></i>
                    <input type="number" step="0.01" name="price" id="add-price" placeholder="2.50" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="add-cat" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Category</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-layer-group text-slate-400 ml-4"></i>
                    <select name="cat_id" id="add-cat" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white font-medium cursor-pointer">
                        <option value="" disabled selected class="dark:bg-slate-900 text-slate-400">Select Category</option>
                        <option value="1" class="dark:bg-slate-900">Ice</option>
                        <option value="2" class="dark:bg-slate-900">Hot</option>
                        <option value="3" class="dark:bg-slate-900">Frappe</option>
                        <option value="4" class="dark:bg-slate-900">Topping</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-2">
                Save Product
            </button>
        </form>
    </div>
</div>

<!-- Modal: Edit Product -->
<div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-md hidden transition-opacity duration-300" id="editProductModal">
    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[2.5rem] overflow-hidden shadow-2xl border border-slate-200/50 dark:border-slate-800/50 transform scale-95 transition-transform duration-300">
        <!-- Title -->
        <div class="flex items-center justify-between px-8 py-6 border-b border-slate-200/50 dark:border-slate-800/50">
            <h3 class="text-xl font-bold text-slate-950 dark:text-white">Edit Product</h3>
            <button onclick="closeEditModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"><i class="fas fa-times"></i></button>
        </div>
        
        <!-- Form -->
        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            
            <!-- Custom File Upload Area -->
            <div class="space-y-1.5">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Replace Product Image</span>
                <label class="flex flex-col items-center justify-center w-full h-40 bg-slate-50 dark:bg-slate-800/40 border-2 border-dashed border-slate-200 dark:border-slate-700/80 rounded-[1.5rem] cursor-pointer hover:bg-slate-100/50 dark:hover:bg-slate-850/50 transition-colors duration-200 group relative">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="edit-upload-placeholder">
                        <i class="fas fa-cloud-arrow-up text-3xl text-slate-400 group-hover:scale-110 transition-transform duration-200 mb-2"></i>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Upload Image</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Leave empty to keep current image</p>
                    </div>
                    <img id="edit-img-preview" class="w-full h-full object-cover rounded-[1.4rem] absolute inset-0 hidden">
                    <input type="file" name="img" id="editImg" class="hidden" onchange="previewImage(this, 'edit-img-preview', 'edit-upload-placeholder')">
                </label>
            </div>

            <div class="space-y-1.5">
                <label for="editName" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Product Name</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-mug-hot text-slate-400 ml-4"></i>
                    <input type="text" name="name" id="editName" placeholder="Product Name" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="editPrice" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Price (USD)</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-dollar-sign text-slate-400 ml-4"></i>
                    <input type="number" step="0.01" name="price" id="editPrice" placeholder="0.00" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white placeholder:text-slate-400 font-medium" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="editCat" class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Category</label>
                <div class="flex items-center bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/80 rounded-2xl focus-within:border-indigo-600 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all duration-200">
                    <i class="fas fa-layer-group text-slate-400 ml-4"></i>
                    <select name="cat_id" id="editCat" required class="w-full py-3.5 px-4 bg-transparent border-none outline-none text-slate-900 dark:text-white font-medium cursor-pointer">
                        <option value="" disabled class="dark:bg-slate-900 text-slate-400">Select Category</option>
                        <option value="1" class="dark:bg-slate-900">Ice</option>
                        <option value="2" class="dark:bg-slate-900">Hot</option>
                        <option value="3" class="dark:bg-slate-900">Frappe</option>
                        <option value="4" class="dark:bg-slate-900">Topping</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all duration-200 hover:-translate-y-0.5 active:translate-y-0 cursor-pointer mt-2">
                Update Product
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview Image when Selected
    function previewImage(input, previewId, placeholderId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    // Open Add Product Modal
    function openAddModal() {
        const modal = document.getElementById('productModal');
        const content = modal.querySelector('div');
        
        // Reset preview
        document.getElementById('add-img-preview').classList.add('hidden');
        document.getElementById('add-upload-placeholder').classList.remove('hidden');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Close Add Product Modal
    function closeAddModal() {
        const modal = document.getElementById('productModal');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }

    // Open Edit Product Modal
    function openEditModal() {
        const modal = document.getElementById('editProductModal');
        const content = modal.querySelector('div');
        
        // Reset preview (since edit image might be empty initially)
        document.getElementById('edit-img-preview').classList.add('hidden');
        document.getElementById('edit-upload-placeholder').classList.remove('hidden');
        modal.classList.remove('hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    // Close Edit Product Modal
    function closeEditModal() {
        const modal = document.getElementById('editProductModal');
        const content = modal.querySelector('div');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 150);
    }

    // Delete Product Check
    function deleteProduct(id) {
        if (confirm('Are you sure you want to delete this product?')) {
            window.location.href = '/Product/delete/' + id;
        }
    }

    // Set Edit Form action dynamically & populate inputs
    function editProduct(button) {
        const row = button.closest('tr');
        const id = button.getAttribute("data-id");
        const cat = button.getAttribute("data-cat");
        
        const name = row.children[1].querySelector('span').textContent.trim();
        // Remove dollar sign and any whitespace to extract float value
        const price = row.children[2].textContent.replace('$', '').trim();
        
        // Load original image preview
        const origImgSrc = row.children[0].querySelector('img').src;
        const preview = document.getElementById('edit-img-preview');
        const placeholder = document.getElementById('edit-upload-placeholder');
        preview.src = origImgSrc;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');

        // Bind form action
        const form = document.getElementById("editProductForm");
        form.action = `/editpro/${id}`;

        document.getElementById("editId").value = id;
        document.getElementById("editName").value = name;
        document.getElementById("editPrice").value = price;
        document.getElementById("editCat").value = cat;

        openEditModal();
    }

    // Close modals on clicking backdrop
    window.addEventListener('click', function(event) {
        const prodModal = document.getElementById('productModal');
        const editModal = document.getElementById('editProductModal');
        
        if (event.target === prodModal) {
            closeAddModal();
        }
        if (event.target === editModal) {
            closeEditModal();
        }
    });

    // Close on Escape Key
    window.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeAddModal();
            closeEditModal();
        }
    });
</script>
@endsection

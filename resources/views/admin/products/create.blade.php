@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center -mt-12">
    
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-10 border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-extrabold text-gray-800 flex items-center justify-center gap-2">
                <span class="text-3xl">☕️</span> Add New Drink or Food
            </h2>
        </div>
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            {{-- STEP 1: Select Category (Organizes the list) --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">1. Choose Category</label>
                <select id="category_filter" name="category_id" required 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none bg-white font-bold">
                    <option selected disabled>Choose a Category...</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- STEP 2: Choose Stock Source (Pulls inventory items) --}}
            <div id="ingredient_container" class="hidden">
                <label class="block text-sm font-bold text-gray-700 mb-1.5">2. Link to Inventory Stock</label>
                <select id="ingredient_selector" name="inventory_id" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none bg-white text-sm">
                    <option value="0" data-cost="0">Not a Stock Item (Service/Manual)</option>
                    @foreach($ingredients as $item)
                        {{-- Added 'data-category' and 'data-unit' for smart filtering --}}
                        <option value="{{ $item->id }}" 
                                data-category="{{ $item->category_id }}"
                                data-unit="{{ $item->unit }}"
                                data-cost="{{ $item->cost_price }}">
                            {{ $item->item_name }} ( Buy: ${{ number_format($item->cost_price, 2) }} / {{ $item->unit }} )
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NEW: Cost Reference & Profit Margin Box (Hidden until item is selected) --}}
            <div id="margin_box" class="hidden bg-gray-50 rounded-2xl p-4 border border-dashed border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">Buy-in Cost:</span>
                    <span id="display_cost" class="text-sm font-mono font-bold text-gray-700">$0.00</span>
                </div>
            </div>

            {{-- STEP 3: The Retail Specifics (Shown after Linking) --}}
            <div id="retail_specifics" class="hidden space-y-5 border-t border-gray-100 pt-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Product Name (for Menu)</label>
                    <input type="text" id="menu_name_input" name="name" required 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <p class="mt-1 text-[10px] text-gray-400">Set a custom name for the menu (e.g., "Cafe Special Roast"). It defaults to the stock item name.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Selling Price ($)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-500 border-r pr-3">$</span>
                        <input type="number" id="selling_price" name="price" step="0.01" placeholder="0.00" required 
                               class="w-full pl-12 pr-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition font-bold text-lg">
                    </div>
                </div>

                {{-- Image Field (as requested) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">Product Image (optional)</label>
                    <input type="file" name="image" 
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700">
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-4 space-y-3">
                <button type="submit" 
                        class="w-full bg-[#6f4e37] hover:bg-[#5a3d2b] text-white font-bold py-3 rounded-lg shadow-md transition transform active:scale-95">
                    Save to Menu
                </button>
                <a href="{{ route('admin.products.index') }}" class="block text-center text-gray-500 hover:text-gray-700 text-sm font-medium transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT FOR DYNAMIC WORKFLOW --}}
<script>
    const categoryFilter = document.getElementById('category_filter');
    const ingredientSelector = document.getElementById('ingredient_selector');
    const ingredientContainer = document.getElementById('ingredient_container');
    
    const marginBox = document.getElementById('margin_box');
    const displayCost = document.getElementById('display_cost');
    const sellingPriceInput = document.getElementById('selling_price');
    
    const retailSpecifics = document.getElementById('retail_specifics');
    const menuNameInput = document.getElementById('menu_name_input');

    // Action 1: When Category is selected, show and filter the Inventory List
    categoryFilter.addEventListener('change', () => {
        const selectedCategory = categoryFilter.value;
        ingredientContainer.classList.remove('hidden');
        ingredientSelector.selectedIndex = 0; // Reset
        marginBox.classList.add('hidden'); // Hide cost

        // Loop through all inventory items and hide those that don't match the category
        Array.from(ingredientSelector.options).forEach(option => {
            if (option.value === "0") {
                option.style.display = "block"; // Always show 'Manual'
            } else if (option.getAttribute('data-category') === selectedCategory) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
    });

    // Action 2: When Stock Source is selected, show cost and autofill name
    ingredientSelector.addEventListener('change', () => {
        const selectedOption = ingredientSelector.options[ingredientSelector.selectedIndex];
        const cost = parseFloat(selectedOption.getAttribute('data-cost')) || 0;
        const itemName = selectedOption.text.split(' (')[0]; // Simple way to strip the price info

        if (cost > 0) {
            marginBox.classList.remove('hidden');
            displayCost.innerText = `$${cost.toFixed(2)}`;
            retailSpecifics.classList.remove('hidden');
            
            // Set the menu name input to the stock item name as a default
            menuNameInput.value = itemName;
            
            // Set a min price to prevent selling below cost
            sellingPriceInput.min = cost.toFixed(2);
            sellingPriceInput.placeholder = `Must be > $${cost.toFixed(2)}`;
        } else {
            // Manual entry mode
            marginBox.classList.add('hidden');
            retailSpecifics.classList.remove('hidden');
            menuNameInput.value = "";
            sellingPriceInput.min = "0.01";
            sellingPriceInput.placeholder = "0.00";
        }
    });

    // Loss Prevention Check (Red outline if too low)
    sellingPriceInput.addEventListener('input', () => {
        const cost = parseFloat(displayCost.innerText.replace('$', '')) || 0;
        const price = parseFloat(sellingPriceInput.value) || 0;
        
        if (cost > 0 && price <= cost) {
            sellingPriceInput.classList.add('border-red-500', 'bg-red-50');
        } else {
            sellingPriceInput.classList.remove('border-red-500', 'bg-red-50');
        }
    });
</script>
@endsection
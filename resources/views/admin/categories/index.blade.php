@extends('layouts.admin')

@section('content')
<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-3xl font-black text-[#2d1102] uppercase tracking-tight">📁 Menu Categories</h1>
                <p class="text-amber-900/60 font-medium italic">Organize your coffee, teas, and snacks</p>
            </div>
            
            @if(auth()->user()->role == 'admin')
                <a href="{{ route('admin.categories.create') }}" 
                class="bg-[#4a2c2a] hover:bg-[#2d1102] text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-amber-900/10 transition-all transform hover:scale-105">
                + New Category
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($categories as $category)
            <div class="bg-white p-8 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-amber-100/20 text-center hover:shadow-xl hover:shadow-amber-900/5 transition-all duration-500 group">
                
                {{-- Category Image/Icon --}}
                <div class="mb-6 relative">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             class="w-28 h-28 mx-auto rounded-full object-cover border-4 border-amber-50 shadow-md group-hover:scale-105 transition-transform duration-500" 
                             alt="{{ $category->name }}">
                    @else
                        <div class="w-28 h-28 mx-auto bg-amber-50 rounded-full flex items-center justify-center text-amber-800 text-3xl font-black uppercase shadow-inner border-2 border-amber-100/50">
                            {{ substr($category->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <h3 class="text-xl font-black text-[#2d1102] tracking-tight">{{ $category->name }}</h3>
                <p class="inline-block mt-3 px-4 py-1.5 bg-amber-50 text-amber-800 text-[10px] font-black rounded-full uppercase tracking-[0.15em]">
                    {{ $category->products->count() }} Items
                </p>
                
                @if(auth()->user()->role == 'admin')
                    <div class="mt-8 pt-6 border-t border-amber-50/50 flex justify-center gap-3">
                        {{-- Edit Button --}}
                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                           class="text-amber-800 bg-amber-50 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-amber-100 transition-colors">
                            Edit
                        </a>

                        {{-- Delete Button --}}
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" 
                              onsubmit="return confirm('Deleting this category might affect products. Continue?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 bg-red-50 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-8 pt-6 border-t border-amber-50/50 text-amber-900/20 text-[10px] font-black uppercase tracking-[0.2em] italic">
                        View Only Mode
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
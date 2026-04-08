@extends('layouts.admin')

@section('content')
<div class="min-h-screen flex items-center justify-center -mt-12" style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);">
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(45,17,2,0.05)] p-12 border border-amber-100/20">
        
        <div class="text-center mb-10">
            <span class="inline-block p-4 bg-amber-50 rounded-2xl mb-4 text-2xl">✨</span>
            <h2 class="text-3xl font-black text-[#2d1102] uppercase tracking-tighter">New Category</h2>
            <p class="text-amber-900/40 text-[11px] font-bold uppercase tracking-widest mt-1">Add a new section to your menu</p>
        </div>

        {{-- Note the route name: admin.categories.store --}}
        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <div>
                <label class="block text-[10px] font-black text-amber-900/40 uppercase tracking-[0.2em] mb-3 ml-1">Category Name</label>
                <input type="text" name="name" placeholder="e.g. Seasonal Brews" required 
                       class="w-full px-5 py-4 rounded-2xl border border-amber-100 bg-amber-50/20 focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition font-bold text-[#2d1102]">
            </div>

            <div>
                <label class="block text-[10px] font-black text-amber-900/40 uppercase tracking-[0.2em] mb-3 ml-1">Category Image</label>
                <div class="bg-amber-50/30 p-4 rounded-2xl border border-dashed border-amber-200">
                    <input type="file" name="image" 
                           class="w-full text-[11px] font-bold text-amber-900/40 
                                  file:mr-4 file:py-2 file:px-6 
                                  file:rounded-xl file:border-0 
                                  file:text-[10px] file:font-black file:uppercase file:tracking-widest
                                  file:bg-[#4a2c2a] file:text-white
                                  hover:file:bg-[#2d1102] transition">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#2d1102] hover:bg-[#4a2c2a] text-white font-black uppercase text-xs tracking-[0.2em] py-5 rounded-2xl shadow-xl shadow-amber-900/10 transition transform active:scale-95">
                    Save Category
                </button>
                
                <a href="{{ route('admin.categories.index') }}" class="block text-center mt-6 text-[10px] font-black text-amber-900/30 uppercase tracking-widest hover:text-amber-700 transition">
                    ← Cancel and Go Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto my-10 px-6">
    <a href="{{ route('admin.suppliers.index') }}" class="text-[#4a2c2a] font-black mb-6 inline-flex items-center gap-2 group transition-all">
        <span class="group-hover:-translate-x-1 transition-transform">←</span> Back to Database
    </a>
    
    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(74,44,42,0.05)] border border-amber-100/50 p-12">
        <h2 class="text-3xl font-black text-[#2d1102] mb-8 tracking-tight">Edit Supplier: <span class="text-amber-600">{{ $supplier->name }}</span></h2>
        
        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-[#4a2c2a]">Supplier Name</label>
                <input type="text" name="name" value="{{ $supplier->name }}" 
                    class="w-full px-5 py-4 bg-amber-50/30 border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold text-[#2d1102]" required>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#4a2c2a]">Phone Number</label>
                    <input type="text" name="phone" value="{{ $supplier->phone }}" 
                        class="w-full px-5 py-4 bg-amber-50/30 border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold text-[#2d1102]">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-[#4a2c2a]">Email Address</label>
                    <input type="email" name="email" value="{{ $supplier->email }}" 
                        class="w-full px-5 py-4 bg-amber-50/30 border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold text-[#2d1102]">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-black uppercase tracking-widest text-[#4a2c2a]">Address</label>
                <textarea name="address" rows="3" 
                    class="w-full px-5 py-4 bg-amber-50/30 border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 transition-all font-semibold text-[#2d1102]">{{ $supplier->address }}</textarea>
            </div>

            <button type="submit" 
                class="w-full bg-[#4a2c2a] text-white py-5 rounded-2xl font-black uppercase tracking-widest shadow-[0_10px_25px_rgba(74,44,42,0.2)] hover:bg-[#2d1102] hover:shadow-[0_15px_30px_rgba(74,44,42,0.3)] transition-all active:scale-[0.98] mt-4">
                Update Supplier Details
            </button>
        </form>
    </div>
</div>
@endsection
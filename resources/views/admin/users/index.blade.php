@extends('layouts.admin')

@section('content')

<div class="p-8 min-h-screen" style="background: linear-gradient(120deg, #f9f7f0 0%, #f8f6f5 100%);">
    <div class="max-w-5xl mx-auto">
        
        <h1 class="text-2xl font-black text-[#2d1102] mb-6 uppercase tracking-tight">Staff Management</h1>

        {{-- Section 1: Create New Staff - Background changed to white for high contrast --}}
        <div class="bg-white p-8 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 mb-8">
            <h2 class="text-lg font-bold text-[#4a2c2a] mb-4">Add New Staff Member</h2>
            <form action="{{ route('admin.users.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <input type="text" name="name" placeholder="Full Name" class="w-full p-4 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none placeholder-amber-900/30" required>
                <input type="email" name="email" placeholder="Email Address" class="w-full p-4 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none placeholder-amber-900/30" required>
                <input type="password" name="password" placeholder="Temporary Password" class="w-full p-4 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none placeholder-amber-900/30" required>
                
                <select name="role" class="w-full p-4 bg-amber-50/30 border border-amber-100/50 rounded-xl focus:ring-2 focus:ring-amber-500 outline-none font-bold text-amber-800/60">
                    <option value="cashier">Cashier (Standard Access)</option>
                    <option value="admin">Admin (Full Access)</option>
                </select>
                
                <div class="md:col-span-2">
                    <button type="submit" class="w-full bg-[#4a2c2a] hover:bg-[#2d1102] text-white font-black py-4 rounded-xl transition shadow-lg shadow-amber-900/10">
                        ➕ Register Staff Member
                    </button>
                </div>
            </form>
        </div>

        {{-- Section 2: Staff Table - Background changed to white --}}
        <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-amber-100/20 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-amber-50/30 border-b border-amber-100/50">
                    <tr>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Name</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Role</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest">Email</th>
                        <th class="p-5 text-xs font-black text-amber-900/40 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-amber-50/50 last:border-none hover:bg-amber-50/20 transition">
                        <td class="p-5 font-bold text-[#2d1102]">{{ $user->name }}</td>
                        <td class="p-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-700' : 'bg-orange-50 text-orange-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-5 text-amber-900/60 font-medium">{{ $user->email }}</td>
                        <td class="p-5 text-right flex justify-end items-center gap-4">
                            {{-- Edit Button --}}
                            <button onclick="openEditModal({{ json_encode($user) }})" class="text-[#4a2c2a] hover:text-black font-black text-xs uppercase tracking-widest">
                                Manage
                            </button>

                            {{-- Delete Form --}}
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Permanently delete this staff account?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Section 3: Manage Modal (Role & Password) --}}
<div id="editModal" class="fixed inset-0 bg-[#2d1102]/60 hidden items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white p-8 rounded-[2rem] w-full max-w-md shadow-2xl border border-amber-100">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-black text-[#2d1102]">Account Settings</h2>
            <button onclick="closeModal()" class="text-amber-900/30 hover:text-[#2d1102]">✕</button>
        </div>
        
        <form id="editForm" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="space-y-6">
                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                    <label class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Managing Account</label>
                    <p id="editNameDisplay" class="text-lg font-bold text-[#4a2c2a]"></p>
                </div>

                <div>
                    <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Assign Position</label>
                    <select name="role" id="editRole" class="w-full p-4 bg-amber-50/50 rounded-xl border-none outline-none font-bold text-[#2d1102] mt-1 focus:ring-2 focus:ring-amber-500">
                        <option value="cashier">Cashier</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <hr class="border-amber-50">

                <div>
                    <label class="text-[10px] font-black text-amber-900/40 uppercase tracking-widest">Force Password Reset</label>
                    <input type="password" name="password" placeholder="Enter new password" class="w-full p-4 bg-amber-50/50 rounded-xl border-none outline-none mt-1 focus:ring-2 focus:ring-orange-400 placeholder-amber-900/20">
                    <p class="text-[10px] text-amber-900/40 mt-2 italic">Leave blank to keep current password.</p>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 bg-amber-50 text-amber-900/60 font-bold rounded-xl hover:bg-amber-100 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-4 bg-[#4a2c2a] text-white font-bold rounded-xl shadow-lg shadow-amber-900/10 hover:bg-[#2d1102] transition">Update User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('editNameDisplay').innerText = user.name;
        document.getElementById('editRole').value = user.role;
        document.getElementById('editForm').action = `/admin/users/${user.id}`;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>
@endsection
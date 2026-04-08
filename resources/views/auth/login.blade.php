<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" 
         style="background: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%) !important;">
        
        <div class="w-full sm:max-w-md mt-6 px-10 py-12 bg-white/70 backdrop-blur-md shadow-xl overflow-hidden sm:rounded-3xl border border-amber-100/50">
            
            <div class="flex flex-col items-center mb-8">
                <div class="p-3 bg-amber-100 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-amber-900 tracking-tight">Cafe Admin</h2>
                <p class="text-amber-800/60 text-sm mt-1">Restricted Staff Access</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="!text-[#3b1901] !font-black mb-1 block" />
                    <x-text-input id="email" class="block w-full !bg-white !border-2 !border-amber-200 !text-black rounded-xl py-4 px-4 focus:!border-[#3b1901] focus:!ring-0 transition-all" 
                        type="email" name="email" :value="old('email')" required autofocus placeholder="admin@cafe.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <x-input-label for="password" :value="__('Password')" class="!text-[#3b1901] !font-black mb-1 block" />
                    <x-text-input id="password" class="block w-full !bg-white !border-2 !border-amber-200 !text-black rounded-xl py-4 px-4 focus:!border-[#3b1901] focus:!ring-0 transition-all"
                        type="password" name="password" required placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-amber-400 text-[#3b1901] focus:ring-[#3b1901]" name="remember">
                        <span class="ms-2 text-sm text-[#3b1901] font-bold">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-[#3b1901] hover:underline font-black" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-8">
                    <button type="submit" 
                            style="background-color: #3b1901 !important; color: #ffffff !important;"
                            class="w-full flex justify-center items-center py-4 px-4 rounded-xl shadow-2xl text-base font-black uppercase tracking-widest hover:opacity-90 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: white !important;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        LOGIN TO SYSTEM
                    </button>
                </div>
            </form>
            
            <div class="mt-12 pt-6 border-t border-amber-100 text-center">
                <p class="text-[10px] text-[#3b1901]/60 uppercase tracking-[0.3em] font-black">Group A | CADT</p>
            </div>
        </div>
    </div>
</x-guest-layout>
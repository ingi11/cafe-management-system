<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Admin Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bg-gradient: linear-gradient(120deg, #fef9e8 0%, #fff5ea 100%);
        }
        .cafe-bg {
            background: var(--bg-gradient);
        }
    </style>
</head>
<body class="cafe-bg min-h-screen flex items-center justify-center text-gray-800 font-sans">
    <div class="text-center px-4">
        <div class="mb-6 inline-block p-5 bg-white/40 rounded-full backdrop-blur-md shadow-sm border border-white/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        
        <h1 class="text-5xl font-black tracking-tight mb-4 text-amber-900">CAFE <span class="text-amber-600">ADMIN</span></h1>
        <p class="text-xl text-amber-800/70 mb-10 max-w-md mx-auto leading-relaxed">
            Manage your inventory, track sales, and oversee staff performance in one place.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('login') }}" class="flex items-center justify-center px-8 py-4 bg-white text-amber-900 font-bold rounded-xl hover:bg-orange-50 transition-all shadow-md border border-amber-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                STAFF LOGIN
            </a>
        </div>
        
        <div class="mt-12 space-y-2">
            <p class="text-sm text-amber-900/40 uppercase tracking-widest font-bold">
                Web Application Development Project
            </p>
            <p class="text-xs text-amber-800/60 font-medium italic">
                Developed by <span class="text-amber-900">Lim Ingi</span>, 
                <span class="text-amber-900">Leng Sokeng</span>, and 
                <span class="text-amber-900">Hong Sindy</span>
            </p>
            <p class="text-xs text-amber-700/50 font-bold">Group A</p>
        </div>
    </div>
</body>
</html>
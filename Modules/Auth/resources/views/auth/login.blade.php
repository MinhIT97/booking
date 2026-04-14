<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StayNest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <svg class="w-10 h-10 text-[#ff385c]" fill="currentColor" viewBox="0 0 24 24">
                <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <span class="text-2xl font-extrabold text-gray-900">Stay<span class="text-[#ff385c]">Nest</span></span>
        </div>

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-500 mb-8">Please enter your details to sign in.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="name@example.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <a href="#" class="text-xs font-semibold text-[#ff385c] hover:underline">Forgot?</a>
                    </div>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#ff385c] focus:ring-[#ff385c]">
                    <label for="remember" class="text-sm text-gray-600 font-medium">Remember me for 30 days</label>
                </div>

                <button type="submit" 
                        class="w-full bg-[#ff385c] hover:bg-[#d90b2e] text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-[#ff385c]/20 flex items-center justify-center gap-2 mt-2">
                    Sign In
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7-7 7M3 12h18"/></svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-[#ff385c] font-bold hover:underline">Sign up for free</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>

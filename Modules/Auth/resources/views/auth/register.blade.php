<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - StayNest</title>
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
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Create Account</h1>
            <p class="text-gray-500 mb-8">Join our community of hosts and travelers.</p>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="John Doe">
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="name@example.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="Min. 8 characters">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#ff385c]/20 focus:border-[#ff385c] transition-all text-gray-900 placeholder-gray-400"
                           placeholder="Repeat password">
                </div>

                <div class="flex items-start gap-2 pt-1">
                    <input type="checkbox" id="terms" required class="mt-1 w-4 h-4 rounded border-gray-300 text-[#ff385c] focus:ring-[#ff385c]">
                    <label for="terms" class="text-xs text-gray-500 leading-normal">By creating an account, you agree to our <a href="#" class="text-[#ff385c] font-semibold hover:underline">Terms of Service</a> and <a href="#" class="text-[#ff385c] font-semibold hover:underline">Privacy Policy</a>.</label>
                </div>

                <button type="submit" 
                        class="w-full bg-[#ff385c] hover:bg-[#d90b2e] text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-[#ff385c]/20 flex items-center justify-center gap-2 mt-4">
                    Create Account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-[#ff385c] font-bold hover:underline">Log in here</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>

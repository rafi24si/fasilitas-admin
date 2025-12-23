<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Sistem Fasilitas Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* BACKGROUND GRADIENT ANIMATION */
        .bg-animate {
            background-size: 300% 300%;
            animation: gradientMove 14s ease infinite;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* FADE */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(25px); }
            to { opacity:1; transform:none; }
        }

        .fade-up {
            animation: fadeUp .8s ease-out;
        }
    </style>
</head>

<body
    class="bg-animate bg-gradient-to-br from-green-50 via-white to-emerald-100
           min-h-screen flex items-center justify-center px-4">

    <!-- CARD -->
    <div
        class="fade-up bg-white/90 backdrop-blur-xl p-8 rounded-2xl shadow-2xl
               w-full max-w-md border border-green-200">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo.jpeg') }}" class="w-40 drop-shadow-md">
        </div>

        <!-- TITLE -->
        <h2 class="text-2xl font-extrabold text-center mb-2 text-green-700">
            Daftar Akun Baru
        </h2>

        <p class="text-center text-gray-600 mb-6 text-sm">
            Registrasi akun untuk mengelola fasilitas dan peminjaman desa
        </p>

        <!-- ERROR VALIDATION -->
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm border border-red-300">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('register.process') }}"
              method="POST"
              class="space-y-4"
              onsubmit="handleRegister()">
            @csrf

            <!-- NAMA -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">
                    Nama Lengkap
                </label>
                <input type="text"
                       name="name"
                       required
                       class="w-full border border-gray-300 px-4 py-2 rounded-lg
                              focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">
                    Email
                </label>
                <input type="email"
                       name="email"
                       required
                       class="w-full border border-gray-300 px-4 py-2 rounded-lg
                              focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">
                    Password
                </label>

                <div class="relative">
                    <input type="password"
                           name="password"
                           id="password"
                           minlength="6"
                           required
                           oninput="checkStrength()"
                           class="w-full border border-gray-300 px-4 py-2 rounded-lg
                                  focus:ring-2 focus:ring-green-500 focus:outline-none">

                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-green-600">
                        👁️
                    </button>
                </div>

                <p id="strengthText" class="text-xs mt-1 text-gray-500">
                    Minimal 6 karakter
                </p>
            </div>

            <!-- ROLE -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700 text-sm">
                    Role
                </label>
                <select name="role"
                        required
                        class="w-full border border-gray-300 px-4 py-2 rounded-lg
                               focus:ring-2 focus:ring-green-500 focus:outline-none bg-white">
                    <option value="admin">Admin</option>
                    <option value="petugas" selected>Petugas</option>
                </select>
            </div>

            <!-- SUBMIT -->
            <button id="registerBtn"
                    class="w-full py-2 rounded-lg font-semibold text-white text-lg
                           bg-gradient-to-r from-green-600 via-emerald-500 to-green-400
                           hover:from-green-700 hover:to-emerald-600
                           shadow-md hover:shadow-xl transition active:scale-95">
                Daftar Akun
            </button>
        </form>

        <!-- LOGIN LINK -->
        <p class="text-center mt-6 text-gray-600 text-sm">
            Sudah punya akun?
            <a href="{{ route('login.form') }}"
               class="font-semibold text-green-600 hover:underline">
                Login
            </a>
        </p>

    </div>

    <!-- JS -->
    <script>
        function togglePassword() {
            const p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        }

        function handleRegister() {
            const btn = document.getElementById('registerBtn');
            btn.innerText = 'Memproses...';
            btn.disabled = true;
        }

        function checkStrength() {
            const p = document.getElementById('password').value;
            const t = document.getElementById('strengthText');

            if (p.length < 6) {
                t.innerText = 'Password terlalu pendek';
                t.className = 'text-xs mt-1 text-red-500';
            } else if (p.length < 9) {
                t.innerText = 'Password cukup';
                t.className = 'text-xs mt-1 text-yellow-600';
            } else {
                t.innerText = 'Password kuat';
                t.className = 'text-xs mt-1 text-green-600';
            }
        }
    </script>

</body>
</html>

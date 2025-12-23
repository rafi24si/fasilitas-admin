<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Sistem Fasilitas Desa</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        /* BACKGROUND */
        .bg-layer {
            position: fixed;
            inset: 0;
            background-size: cover;
            background-position: center;
            transition: opacity 1.2s ease-in-out;
            z-index: -1;
            filter: brightness(65%);
        }

        /* GLASS CARD */
        .glass {
            backdrop-filter: blur(18px);
            background: rgba(255,255,255,.9);
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

<body class="flex items-center justify-center min-h-screen relative">

    <!-- BACKGROUND -->
    <div id="bg1" class="bg-layer"></div>
    <div id="bg2" class="bg-layer opacity-0"></div>

    <!-- LOGIN CARD -->
    <div class="glass fade-up rounded-2xl shadow-2xl p-8 w-full max-w-md border border-green-200">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo.jpeg') }}" class="w-52 drop-shadow-lg">
        </div>

        <!-- TITLE -->
        <div class="text-center mb-6">
            <h2 class="text-xl font-extrabold text-green-700">
                Sistem Fasilitas Desa
            </h2>
            <p class="text-sm text-gray-600 mt-2">
                Login untuk mengelola fasilitas umum dan peminjaman desa
            </p>
        </div>

        <!-- ERROR -->
        @if (session('error'))
            <div class="mb-4 px-4 py-2 rounded-lg bg-red-100 text-red-700 text-sm border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('login.process') }}" method="POST" class="space-y-4" onsubmit="handleLogin(this)">
            @csrf

            <!-- EMAIL -->
            <div>
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input type="email" name="email" required
                       class="w-full mt-1 px-4 py-2 rounded-lg border border-gray-300
                              focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="text-sm font-semibold text-gray-700">Password</label>

                <div class="relative mt-1">
                    <input type="password" name="password" id="password" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300
                                  focus:ring-2 focus:ring-green-500 focus:outline-none">

                    <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-2.5 text-gray-500 hover:text-green-600">
                        👁️
                    </button>
                </div>
            </div>

            <!-- BUTTON -->
            <button id="loginBtn"
                    class="w-full py-2 rounded-lg font-semibold text-white text-lg
                           bg-gradient-to-r from-green-600 via-emerald-500 to-green-400
                           hover:from-green-700 hover:to-emerald-600
                           shadow-md hover:shadow-xl transition">
                Masuk
            </button>
        </form>

        <!-- REGISTER -->
        <p class="text-center mt-6 text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register.form') }}" class="font-semibold text-green-600 hover:underline">
                Daftar Sekarang
            </a>
        </p>

    </div>

    <!-- JS -->
    <script>
        /* PASSWORD TOGGLE */
        function togglePassword() {
            const p = document.getElementById('password');
            p.type = p.type === 'password' ? 'text' : 'password';
        }

        /* LOADING */
        function handleLogin() {
            const btn = document.getElementById('loginBtn');
            btn.innerText = 'Memproses...';
            btn.disabled = true;
        }

        /* BACKGROUND SLIDESHOW (FASILITAS & PEMINJAMAN) */
        const images = [
            "https://images.unsplash.com/photo-1581090700227-1e37b190418e", // gedung publik
            "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2", // ruang rapat
            "https://images.unsplash.com/photo-1570129477492-45c003edd2be", // kantor desa
            "https://images.unsplash.com/photo-1590650153855-d9e808231d41"  // fasilitas umum
        ];

        let i = 0;
        const bg1 = document.getElementById('bg1');
        const bg2 = document.getElementById('bg2');
        let active = bg1;

        function changeBg() {
            const next = active === bg1 ? bg2 : bg1;
            next.style.backgroundImage = `url('${images[i]}')`;
            next.style.opacity = 1;
            active.style.opacity = 0;
            active = next;
            i = (i + 1) % images.length;
        }

        bg1.style.backgroundImage = `url('${images[0]}')`;
        setInterval(changeBg, 7000);
    </script>

</body>
</html>

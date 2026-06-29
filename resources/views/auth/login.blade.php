<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Seblak Jeletet</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[60px] h-[60px] rounded-full object-cover shadow-sm border border-black/5">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">

  <div class="bg-white w-full max-w-[400px] rounded-[16px] shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-black/5 p-8">

    <div class="flex flex-col items-center justify-center text-center gap-3 mb-6">
    <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[60px] h-[60px] rounded-full object-cover shadow-sm border border-black/5">
    <h1 class="text-[24px] font-[800] text-black">Masuk Sistem</h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-[#e6f4ea] text-[#03913F] border border-[#03913F]/20 rounded-[8px] text-[13px] font-[800] text-center shadow-sm animate-fade-in">
             {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-[#FDE7E7] text-[#DC0F11] border border-[#DC0F11]/20 rounded-[8px] text-[13px] font-[800] text-center shadow-sm animate-fade-in">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
      @csrf

      <div class="mb-5">
        <label class="block text-[13px] font-[800] text-black mb-2">Email Akses</label>
        <input type="email" name="email" required placeholder="kasir@seblak.com" value="{{ old('email') }}"
               class="w-full px-4 py-3.5 rounded-[8px] bg-[#efefef] border border-transparent focus:bg-white focus:border-black outline-none transition font-[600] text-[#1A1A1A]">
      </div>

      <div class="mb-8 relative">
        <label class="block text-[13px] font-[800] text-black mb-2">Password</label>
        <div class="relative">
            <input type="password" name="password" id="password" required placeholder="••••••••"
                   class="w-full pl-4 pr-12 py-3.5 rounded-[8px] bg-[#efefef] border border-transparent focus:bg-white focus:border-black outline-none transition font-[600] text-[#1A1A1A]">

            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-[#8A8A8E] hover:text-black transition focus:outline-none">
                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>
        </div>
      </div>

      <button type="submit" class="w-full bg-[#DC0F11] hover:bg-[#BF0103] text-white font-[800] py-4 rounded-full transition shadow-lg">
        Masuk Sekarang
      </button>
    </form>

  </div>

  <script>
    function togglePassword() {
        const pwdInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
            // Ubah icon jadi mata dicoret
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
        } else {
            pwdInput.type = 'password';
            // Ubah kembali jadi mata terbuka
            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
        }
    }
  </script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Selamat Datang - Seblak Jeletet</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;700;800;900&family=Playfair+Display:wght@800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

  <style>
    body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; }
    .font-playfair { font-family: 'Playfair Display', serif; }
  </style>
</head>
<body class="min-h-screen flex items-center justify-center p-5">

  <div class="bg-white w-full max-w-[420px] rounded-[16px] shadow-[0_4px_24px_rgba(0,0,0,0.06)] border border-black/5 overflow-hidden">

    <div class="px-6 pt-8 pb-4 flex flex-col items-center border-b border-black/5">
    <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[60px] h-[60px] rounded-full object-cover shadow-sm border border-black/5">
    <h1 class="font-playfair text-[24px] font-[800] tracking-tight leading-none text-center">Seblak <span class="text-[#DC0F11]">Jeletet</span></h1>
      <p class="text-[13px] font-[700] text-[#8A8A8E] mt-1 tracking-wider uppercase">Medan</p>
    </div>

    <div class="p-6 md:p-8">
      <div class="mb-6">
        <h2 class="text-[24px] font-[800] leading-tight mb-1">Selamat datang 👋</h2>
        <p class="text-[14px] font-[500] text-[#5e5e5e]">Silakan lengkapi data untuk memulai pesanan.</p>

        @if($meja)
        <div class="inline-flex mt-3 bg-[#F3F3F3] text-[#1A1A1A] px-4 py-1.5 rounded-full text-[13px] font-[800]">
          📍 Meja No. {{ $meja }}
        </div>
        @endif
      </div>

      <form action="{{ route('start.order') }}" method="POST">
        @csrf
        <input type="hidden" name="meja" value="{{ $meja }}">

        <div class="mb-5">
          <label class="block text-[14px] font-[700] text-[#1A1A1A] mb-2">Nama Pemesan</label>
          <input type="text" name="nama_pelanggan" required placeholder=""
                 class="w-full px-4 py-3.5 rounded-[8px] bg-[#efefef] border border-transparent focus:bg-white focus:border-black outline-none transition font-[600] text-[#1A1A1A] placeholder-[#8A8A8E]">
        </div>

        <div class="mb-8">
          <label class="block text-[14px] font-[700] text-[#1A1A1A] mb-3">Tipe Pesanan</label>
          <div class="flex gap-3">
            <label class="flex-1 cursor-pointer">
              <input type="radio" name="tipe_pesanan" value="Makan Sini" class="hidden peer" checked
                     onchange="updateOrderType(this)">
              <div class="type-opt text-center p-3 rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] text-[#DC0F11] font-[800] text-[13px] transition">
                🍽️ Makan Sini
              </div>
            </label>

            <label class="flex-1 cursor-pointer">
              <input type="radio" name="tipe_pesanan" value="Bungkus" class="hidden peer"
                     onchange="updateOrderType(this)">
              <div class="type-opt text-center p-3 rounded-full border-2 border-black/5 bg-[#F3F3F3] text-[#5e5e5e] font-[600] text-[13px] transition">
                🛍️ Bungkus
              </div>
            </label>
          </div>
        </div>

        <button type="submit" class="w-full bg-[#DC0F11] hover:bg-[#BF0103] text-white font-[800] text-[16px] py-4 rounded-full transition flex justify-center items-center gap-2">
          Lihat menu pemesanan
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>

      </form>
    </div>
  </div>

  <script>
    function updateOrderType(input) {
      document.querySelectorAll('.type-opt').forEach(el => {
        el.className = 'type-opt text-center p-3 rounded-full border-2 border-black/5 bg-[#F3F3F3] text-[#5e5e5e] font-[600] text-[13px] transition';
      });

      if(input.checked) {
        let label = input.nextElementSibling;
        label.className = 'type-opt text-center p-3 rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] text-[#DC0F11] font-[800] text-[13px] transition';
      }
    }
  </script>

</body>
</html>

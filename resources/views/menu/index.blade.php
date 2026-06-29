<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seblak Jeletet Medan — Menu</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">

  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,800;1,700&display=swap" rel="stylesheet">

  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

  <style>
    body { font-family: 'Nunito', sans-serif; background-color: #FAF7F4; color: #1A1A1A; overflow-x: hidden; }
    .font-playfair { font-family: 'Playfair Display', serif; }
    .scrollbar-none::-webkit-scrollbar { display: none; }

    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slideUp 0.3s ease-out; }
    .animate-fade-in { animation: fadeIn 0.3s ease; }
  </style>
</head>
<body class="pb-28">

<nav class="fixed top-0 inset-x-0 z-[100] h-[68px] bg-white/95 backdrop-blur-md border-b border-black/5 px-5 md:px-10 flex items-center justify-between">
 <a href="#" class="flex items-center gap-2 md:gap-3 text-decoration-none">
    <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo" class="w-[40px] h-[40px] rounded-full object-cover shadow-sm border border-black/5">
    <div class="font-playfair text-[18px] md:text-[20px] font-[800] text-[#1A1A1A]">Seblak <span class="text-[#DC0F11]">Jeletet Medan</span></div>
  </a>
  <button id="cartToggle" class="relative bg-[#FDE7E7] w-[42px] h-[42px] rounded-full flex items-center justify-center text-[18px] hover:scale-105 transition">
    🛒
    <span id="cartCount" class="absolute -top-1 -right-1 bg-[#DC0F11] text-white text-[9px] font-[900] w-[18px] h-[18px] rounded-full flex items-center justify-center border-2 border-white">0</span>
  </button>
</nav>

<section class="pt-[68px] bg-gradient-to-r from-[#DC0F11] to-[#BF0103] min-h-[180px] flex items-center">
  <div class="max-w-[1200px] mx-auto px-5 w-full">
    <h1 class="font-playfair text-[32px] font-[800] text-white">Hai, {{ session('nama_pelanggan', 'Pelanggan') }}! 👋</h1>
    <p class="text-white/90 font-[600] mt-1 text-[14px]">{{ session('tipe_pesanan', 'Makan Sini') }} Meja {{ session('meja', '-') }}</p>
  </div>
</section>

<div class="sticky top-[68px] z-[80] bg-[#FAF7F4]/95 backdrop-blur-md border-b border-black/5 overflow-x-auto scrollbar-none">
  <div class="flex px-5 py-3 gap-2 w-max mx-auto max-w-[1200px]" id="tabsEl">
    @foreach($categories as $index => $cat)
      <button
        id="btn-{{ $cat->id }}"
        onclick="switchCategory('{{ $cat->id }}')"
        class="tab-btn shrink-0 rounded-full px-5 py-2.5 text-[14px] font-[700] transition cursor-pointer {{ $index === 0 ? 'bg-[#DC0F11] text-white' : 'bg-[#F3F3F3] text-[#1A1A1A]' }}"
      >
        {{ $cat->nama_kategori }}
      </button>
    @endforeach
  </div>
</div>

<div class="max-w-[1200px] mx-auto px-5 pt-6 pb-10" id="menuWrap">
    @foreach($categories as $index => $cat)
    <div class="cat-section animate-fade-in {{ $index === 0 ? 'block' : 'hidden' }}" id="sec-{{ $cat->id }}">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-5">
            @foreach($cat->menus as $menu)
            @php
                $catName = strtolower($cat->nama_kategori);
                $isSeblakOrBaso = str_contains($catName, 'seblak') || str_contains($catName, 'baso') || str_contains($catName, 'bakso');
                $isMie = str_contains($catName, 'mie') || str_contains($catName, 'wonton');
                $isPaket = str_contains($catName, 'paket');

                $menuData = [
                    'id' => $menu->id, 'name' => $menu->nama_menu, 'price' => $menu->harga,
                    'desc' => $menu->deskripsi, 'isSeblakOrBaso' => $isSeblakOrBaso,
                    'isMie' => $isMie, 'isPaket' => $isPaket, 'foto' => $menu->foto
                ];
            @endphp

            <div class="bg-white rounded-[16px] overflow-hidden shadow-sm border border-black/5 flex flex-col relative">

                <div class="w-full aspect-square bg-[#F3F3F3] relative flex items-center justify-center text-[40px] overflow-hidden shrink-0">
                    @if($menu->foto)
                        <img src="{{ asset('storage/' . $menu->foto) }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <span>🍲</span>
                    @endif
                    <div class="absolute top-2 left-2 z-10 bg-[#DC0F11] text-white text-[10px] font-[900] px-2 py-1 rounded-[8px] shadow-md">
                        Rp {{ number_format($menu->harga/1000, 0) }}K
                    </div>
                </div>

                <div class="p-3 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[13px] font-[800] leading-tight mb-1">{{ $menu->nama_menu }}</div>
                        <div class="text-[10px] text-[#8A8A8E] leading-snug line-clamp-2 mb-2">{{ $menu->deskripsi }}</div>
                    </div>

                    <div class="action-container mt-2 flex justify-end" data-id="{{ $menu->id }}">
                        <button class="btn-add-card btn-tambah w-full bg-[#DC0F11] text-white rounded-full py-2 text-[12px] font-[800] hover:bg-[#BF0103] transition shadow-sm" onclick='handleMenuClick(@json($menuData))'>
                            Tambah
                        </button>

                        <div class="plus-minus-control ui-qty-control hidden items-center justify-between gap-1 bg-[#F3F3F3] border border-black/5 p-1 rounded-full w-full">
                            <button class="btn-min w-[26px] h-[26px] bg-white text-[#DC0F11] rounded-full font-[900] text-[16px] shadow-sm flex items-center justify-center hover:bg-gray-50 transition" onclick='decreaseItemCard(@json($menuData))'>-</button>
                            <span class="qty-display card-qty-text text-[13px] font-[900] text-[#DC0F11] w-[20px] text-center">1</span>
                            <button class="btn-plus w-[26px] h-[26px] bg-[#DC0F11] text-white rounded-full font-[900] text-[16px] flex items-center justify-center hover:bg-[#BF0103] transition" onclick='handleMenuClick(@json($menuData))'>+</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>

<div class="modal-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-[200] hidden items-end md:items-center justify-center" id="orderModal" onclick="if(event.target==this) closeModal()">
  <div class="modal-box bg-white w-full max-w-[450px] rounded-t-[24px] md:rounded-[24px] p-6 animate-slide-up max-h-[90vh] overflow-y-auto">

    <div class="flex justify-between items-start mb-2">
        <h2 id="mName" class="text-[20px] font-[900] text-[#1A1A1A]"></h2>
        <button onclick="closeModal()" class="bg-[#F3F3F3] w-[32px] h-[32px] rounded-full flex items-center justify-center text-[16px] font-[700] text-[#1A1A1A]">✕</button>
    </div>
    <div id="mDesc" class="text-[12px] text-[#8A8A8E] mb-4 pb-4 border-b border-black/5"></div>

    <div id="secLevel" class="hidden mb-4">
        <label class="block font-[800] mb-2 text-[13px] text-[#1A1A1A]">Level Pedas</label>
        <div class="grid grid-cols-5 gap-2">
            @foreach([1, 2, 3, 4, 5] as $v)
                <button class="opt-btn btn-level rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A] hover:bg-[#F3F3F3] transition" onclick="selectOption('level', '{{ $v }}', this)">Lv {{ $v }}</button>
            @endforeach
        </div>
    </div>

    <div id="secKencur" class="hidden mb-4">
        <label class="block font-[800] mb-2 text-[13px] text-[#1A1A1A]">Takaran Kencur</label>
        <div class="grid grid-cols-4 gap-2">
            <button class="opt-btn btn-kencur rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A]" onclick="selectOption('kencur', 'Tidak', this)">Tidak</button>
            <button class="opt-btn btn-kencur rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A]" onclick="selectOption('kencur', 'Sedikit', this)">Sedikit</button>
            <button class="opt-btn btn-kencur rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-2 py-2 text-[12px] font-[900] text-[#DC0F11]" onclick="selectOption('kencur', 'Sedang', this)">Sedang</button>
            <button class="opt-btn btn-kencur rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A]" onclick="selectOption('kencur', 'Banyak', this)">Banyak</button>
        </div>
    </div>

    <div id="secKuah" class="hidden mb-4">
        <label class="block font-[800] mb-2 text-[13px] text-[#1A1A1A]">Tekstur Kuah</label>
        <div class="grid grid-cols-3 gap-2">
            <button class="opt-btn btn-kuah rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A]" onclick="selectOption('kuah', 'Cair', this)">Cair</button>
            <button class="opt-btn btn-kuah rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-2 py-2 text-[12px] font-[900] text-[#DC0F11]" onclick="selectOption('kuah', 'Kental', this)">Kental</button>
            <button class="opt-btn btn-kuah rounded-full border border-black/10 bg-white px-2 py-2 text-[12px] font-[800] text-[#1A1A1A]" onclick="selectOption('kuah', 'Basah', this)">Basah</button>
        </div>
    </div>

    <div class="flex justify-between items-center mt-5 pt-4 border-t border-black/5">
        <span class="font-[900] text-[14px]">Jumlah Pesan:</span>
        <div class="flex items-center gap-2 rounded-full border border-black/10 bg-[#F3F3F3] p-1">
            <button class="w-[32px] h-[32px] bg-white text-[#DC0F11] rounded-full font-[900] text-[18px] shadow-sm flex items-center justify-center hover:bg-gray-50 transition" onclick="changeModalQty(-1)">-</button>
            <span class="text-[14px] font-[900] text-[#DC0F11] w-[24px] text-center" id="modalQtyDisplay">1</span>
            <button class="w-[32px] h-[32px] bg-[#DC0F11] text-white rounded-full font-[900] text-[18px] flex items-center justify-center hover:bg-[#BF0103] transition" onclick="changeModalQty(1)">+</button>
        </div>
    </div>

    <button onclick="processAddToCart()" class="w-full bg-[#DC0F11] hover:bg-[#BF0103] text-white rounded-full py-4 mt-6 font-[900] text-[15px] transition shadow-md">SIMPAN & TAMBAH</button>
  </div>
</div>

<div class="cart-sb fixed top-0 right-[-100%] w-full max-w-[380px] h-screen bg-white z-[160] transition-all duration-300 shadow-2xl flex flex-col p-5" id="cartSb">
    <div class="flex justify-between items-center mb-4 pb-4 border-b border-black/5">
        <h3 class="font-[900] text-[18px]">🛒</h3>
        <button onclick="document.getElementById('cartSb').classList.remove('right-0'); document.getElementById('cartSb').classList.add('right-[-100%]');" class="w-[32px] h-[32px] bg-[#F3F3F3] rounded-full font-[700] text-[16px]">✕</button>
    </div>

    <div id="cartItems" class="flex-1 overflow-y-auto pr-2 space-y-4"></div>

    <div class="pt-4 border-t border-black/5 mt-auto">
        <div class="mb-4">
            <label class="block text-[13px] font-[800] text-[#1A1A1A] mb-2">Catatan Dapur (Opsional)</label>
            <textarea id="orderNotes" name="catatan" rows="2" placeholder="Contoh: Sosis gabung ke Seblak 1 ya..." class="w-full p-3 rounded-[12px] border border-black/10 bg-[#F3F3F3] text-[12px] outline-none resize-none focus:bg-white focus:border-[#DC0F11] transition"></textarea>
        </div>

        <div id="cartTotal" class="font-[900] text-[18px] flex justify-between items-center mb-4"></div>

        <form action="{{ route('cart.add') }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="cart_data" id="cartDataPayload">
            <input type="hidden" name="catatan_payload" id="catatanPayload">

            <div class="mb-5">
                <label class="block text-[13px] font-[800] text-[#1A1A1A] mb-3">Pilih Metode Bayar</label>
                <div class="flex gap-2">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="Tunai" class="hidden peer" checked>
                        <div class="opt-pay w-full text-center rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-2 py-3 text-[12px] font-[900] text-[#DC0F11] transition" onclick="selectPay('Tunai', this)">
                            Cash
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="Midtrans" class="hidden peer">
                        <div class="opt-pay w-full text-center rounded-full border-2 border-black/10 bg-[#F3F3F3] px-2 py-3 text-[12px] font-[800] text-[#8A8A8E] transition" onclick="selectPay('Midtrans', this)">
                            QRIS
                        </div>
                    </label>
                </div>
            </div>

            <button type="button" onclick="checkout()" class="w-full bg-[#DC0F11] hover:bg-[#BF0103] text-white rounded-full py-4 font-[900] text-[15px] transition shadow-lg">PESAN & BAYAR SEKARANG</button>
        </form>
    </div>
</div>

<script>
let cart = [];
let curItem = null;
let tempOptions = { level: null, kencur: 'Sedang', kuah: 'Kental' };
let modalQty = 1;

function switchCategory(id) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = 'tab-btn shrink-0 rounded-full px-5 py-2.5 text-[14px] font-[700] text-[#1A1A1A] bg-[#F3F3F3] transition cursor-pointer';
    });
    document.querySelectorAll('.cat-section').forEach(s => {
        s.classList.remove('block'); s.classList.add('hidden');
    });

    let activeBtn = document.getElementById(`btn-${id}`);
    activeBtn.className = 'tab-btn shrink-0 rounded-full px-5 py-2.5 text-[14px] font-[700] text-white bg-[#DC0F11] transition cursor-pointer';

    document.getElementById(`sec-${id}`).classList.remove('hidden');
    document.getElementById(`sec-${id}`).classList.add('block');
    activeBtn.scrollIntoView({behavior:'smooth', inline:'center'});
}

function handleMenuClick(item) {
    if (!item.isSeblakOrBaso && !item.isMie && !item.isPaket) { addToCartFast(item); return; }

    curItem = item; tempOptions = { level: null, kencur: 'Sedang', kuah: 'Kental' }; modalQty = 1;
    document.getElementById('modalQtyDisplay').textContent = modalQty;
    document.getElementById('mName').textContent = item.name;
    document.getElementById('mDesc').textContent = item.desc || '';

    document.querySelectorAll('.btn-level').forEach(b => {
        b.className = 'opt-btn btn-level rounded-full border border-black/10 bg-white px-4 py-2 text-[12px] font-[800] text-[#1A1A1A] hover:bg-[#F3F3F3] transition cursor-pointer';
    });
    document.querySelectorAll('.btn-kencur').forEach(b => {
        if(b.textContent.trim() === 'Sedang') {
            b.className = 'opt-btn btn-kencur rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-4 py-2 text-[12px] font-[900] text-[#DC0F11] transition cursor-pointer';
        } else {
            b.className = 'opt-btn btn-kencur rounded-full border border-black/10 bg-white px-4 py-2 text-[12px] font-[800] text-[#1A1A1A] hover:bg-[#F3F3F3] transition cursor-pointer';
        }
    });
    document.querySelectorAll('.btn-kuah').forEach(b => {
        if(b.textContent.trim() === 'Kental') {
            b.className = 'opt-btn btn-kuah rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-4 py-2 text-[12px] font-[900] text-[#DC0F11] transition cursor-pointer';
        } else {
            b.className = 'opt-btn btn-kuah rounded-full border border-black/10 bg-white px-4 py-2 text-[12px] font-[800] text-[#1A1A1A] hover:bg-[#F3F3F3] transition cursor-pointer';
        }
    });

    document.getElementById('secLevel').style.display = (item.isSeblakOrBaso || item.isMie) ? 'block' : 'none';
    document.getElementById('secKencur').style.display = item.isSeblakOrBaso ? 'block' : 'none';
    document.getElementById('secKuah').style.display = (item.isSeblakOrBaso || item.isMie) ? 'block' : 'none';

    document.getElementById('orderModal').classList.remove('hidden');
    document.getElementById('orderModal').classList.add('flex');
}

function changeModalQty(delta) {
    modalQty += delta; if(modalQty < 1) modalQty = 1;
    document.getElementById('modalQtyDisplay').textContent = modalQty;
}

function closeModal() {
    document.getElementById('orderModal').classList.remove('flex');
    document.getElementById('orderModal').classList.add('hidden');
}

function selectOption(type, value, btn) {
    tempOptions[type] = value;
    document.querySelectorAll('.btn-' + type).forEach(b => {
        b.className = `opt-btn btn-${type} rounded-full border border-black/10 bg-white px-4 py-2 text-[12px] font-[800] text-[#1A1A1A] hover:bg-[#F3F3F3] transition cursor-pointer`;
    });
    btn.className = `opt-btn btn-${type} rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-4 py-2 text-[12px] font-[900] text-[#DC0F11] transition cursor-pointer`;
}

function processAddToCart() {
    if(!tempOptions.level && (curItem.isSeblakOrBaso || curItem.isMie)) return alert('Level pedas wajib dipilih!');
    let itemToPush = { ...curItem, qty: modalQty, optLevel: tempOptions.level };
    if(curItem.isSeblakOrBaso || curItem.isMie) { itemToPush.optKencur = tempOptions.kencur; itemToPush.optKuah = tempOptions.kuah; }

    cart.push(itemToPush);
    recalcPromos(); updateUI(); closeModal();

    document.getElementById('cartSb').classList.remove('right-[-100%]');
    document.getElementById('cartSb').classList.add('right-0');
}

function addToCartFast(item) {
    let index = cart.findIndex(i => i.id === item.id);
    if(index !== -1) { cart[index].qty++; } else { cart.push({ ...item, qty: 1 }); }
    recalcPromos(); updateUI();
}

function decreaseItemCard(menu) {
    if(menu.isSeblakOrBaso || menu.isMie || menu.isPaket) {
        alert('Menu dengan opsi kuah/pedas, harap hapus dari dalam Keranjang langsung ya!');
        document.getElementById('cartSb').classList.remove('right-[-100%]');
        document.getElementById('cartSb').classList.add('right-0');
        return;
    }
    let index = cart.findLastIndex(item => item.id === menu.id);
    if(index !== -1) { cart[index].qty--; if(cart[index].qty === 0) cart.splice(index, 1); }
    recalcPromos(); updateUI();
}

function changeCartQty(index, delta) {
    cart[index].qty += delta;
    if(cart[index].qty <= 0) cart.splice(index, 1);
    recalcPromos(); updateUI();
}

function recalcPromos() {
    cart = cart.filter(item => !item.isPromo);
    let countPaket = cart.filter(item => item.isPaket).reduce((sum, item) => sum + item.qty, 0);
    if(countPaket > 0) {
        cart.push({ id: 'promo', name: 'Es Teh Manis (Promo Paket)', price: 0, qty: countPaket, isPromo: true });
    }
}

function updateUI() {
    let totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
    document.getElementById('cartCount').textContent = totalQty;

    let totalHarga = 0;
    document.getElementById('cartItems').innerHTML = cart.map((it, i) => {
        totalHarga += (it.price * it.qty);
        let details = [];
        if(it.optLevel) details.push(`Lv ${it.optLevel}`);
        if(it.optKencur) details.push(`Kencur: ${it.optKencur}`);
        if(it.optKuah) details.push(`Kuah: ${it.optKuah}`);

        let detailText = details.length > 0 ? `<div class="text-[10px] text-[#DC0F11] font-[800] mt-1">${details.join(' • ')}</div>` : '';

        let controlHTML = it.isPromo ?
            `<span class="text-[12px] bg-[#FECB01] text-black px-2 py-1 rounded-[6px] font-[900]">🎁 GRATIS</span>` :
            `<div class="flex items-center gap-2 rounded-full border border-black/10 bg-[#F3F3F3] p-1"><button class="w-[26px] h-[26px] flex items-center justify-center rounded-full bg-white text-[#DC0F11] font-[900]" onclick="changeCartQty(${i}, -1)">-</button><span class="w-[18px] text-center text-[13px] font-[900] text-[#DC0F11]">${it.qty}</span><button class="w-[26px] h-[26px] flex items-center justify-center rounded-full bg-[#DC0F11] text-white font-[900]" onclick="changeCartQty(${i}, 1)">+</button></div>`;

        return `<div class="mb-4 border-b border-black/5 pb-4 flex justify-between items-center"><div><b class="text-[13px] text-[#1A1A1A]">${it.name}</b>${detailText}<div class="text-[12px] font-[900] mt-1 text-[#1A1A1A]">Rp ${it.price.toLocaleString('id-ID')}</div></div>${controlHTML}</div>`;
    }).join('');

    document.getElementById('cartTotal').innerHTML = `<span class="text-[#1A1A1A]">Total Tagihan</span> <span class="text-[#DC0F11]">Rp ${totalHarga.toLocaleString('id-ID')}</span>`;
    document.getElementById('cartDataPayload').value = JSON.stringify(cart);

    document.querySelectorAll('.action-container').forEach(container => {
        let menuId = container.dataset.id;
        let qtyInCart = cart.filter(item => item.id == menuId).reduce((sum, item) => sum + item.qty, 0);
        let btnTambah = container.querySelector('.btn-tambah');
        let uiControl = container.querySelector('.ui-qty-control');
        let cardQtyText = container.querySelector('.card-qty-text');

        if(qtyInCart > 0) {
            btnTambah.style.display = 'none';
            uiControl.style.display = 'flex';
            cardQtyText.textContent = qtyInCart;
        } else {
            btnTambah.style.display = 'block';
            uiControl.style.display = 'none';
        }
    });
}

function selectPay(val, el) {
    document.querySelectorAll('.opt-pay').forEach(btn => {
        btn.className = 'opt-pay w-full text-center rounded-full border-2 border-black/10 bg-[#F3F3F3] px-2 py-3 text-[12px] font-[800] text-[#8A8A8E] transition cursor-pointer';
    });
    el.className = 'opt-pay w-full text-center rounded-full border-2 border-[#DC0F11] bg-[#FDE7E7] px-2 py-3 text-[12px] font-[900] text-[#DC0F11] transition cursor-pointer';
    document.querySelector(`input[name="metode_pembayaran"][value="${val}"]`).checked = true;
}

function checkout() {
    if(cart.length === 0) return alert('Keranjang masih kosong!');
    let notes = document.getElementById('orderNotes').value;
    document.getElementById('catatanPayload').value = notes;
    document.getElementById('checkoutForm').submit();
}

document.getElementById('cartToggle').onclick = function() {
    document.getElementById('cartSb').classList.remove('right-[-100%]');
    document.getElementById('cartSb').classList.add('right-0');
};

updateUI();
</script>
</body>
</html>

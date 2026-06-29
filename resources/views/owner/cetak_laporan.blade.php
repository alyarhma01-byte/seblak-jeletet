<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Seblak Jeletet Medan</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Nunito', sans-serif; background-color: #fff; color: #000; }
        /* Pengaturan Kertas A4 untuk PDF */
        @media print {
            @page { size: A4 portrait; margin: 1.5cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="p-8 max-w-[21cm] mx-auto bg-white" onload="window.print()">

    <div class="no-print mb-6 flex justify-between">
        <a href="{{ route('owner.laporan') }}" class="bg-gray-800 text-white px-4 py-2 rounded font-bold hover:bg-black transition">⬅ Kembali ke Laporan</a>

        <button onclick="window.print()" class="bg-[#DC0F11] text-white px-4 py-2 rounded font-bold hover:bg-red-700 transition">Simpan sebagai PDF / Cetak</button>
    </div>

    <div class="flex justify-between items-center border-b-[4px] border-black pb-4 mb-8">
        <div class="text-left">
            <h1 class="text-[28px] font-[900] text-[#DC0F11] tracking-widest uppercase m-0 leading-none">Seblak Jeletet Medan</h1>
            <p class="text-[14px] font-[700] mt-2">Jl. Tuasan Gg. Rukun No.19, Medan 20222, Indonesia</p>
            <p class="text-[14px] font-[700]">Telp. 0896-3378-7703</p>
            <p class="text-[14px] font-[700]">e-mail: seblakjeletetmarelan@gmail.com</p>
        </div>
        <div>
            <img src="{{ asset('LOGO SEBLAK.jpg') }}" alt="Logo Seblak" class="h-[100px] w-auto object-contain">
        </div>
    </div>

    <div class="text-center mb-8">
        <h2 class="text-[20px] font-[900] uppercase underline">Laporan Keuangan & Transaksi</h2>
        <p class="text-[14px] font-[700] mt-1">
            Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        </p>
    </div>

    <div class="mb-8">
        <h3 class="text-[16px] font-[900] mb-2 border-b-2 border-black/20 pb-1">A. Ringkasan Laba Bersih</h3>
        <table class="w-full text-left border-collapse border border-black">
            <tr class="bg-gray-100">
                <th class="border border-black p-2 w-1/2">Total Pemasukan (Omzet)</th>
                <td class="border border-black p-2 font-[900]">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-gray-100">
                <th class="border border-black p-2 w-1/2">Total Pengeluaran (Beban)</th>
                <td class="border border-black p-2 font-[900] text-red-600">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr class="bg-black text-white">
                <th class="border border-black p-2 uppercase">Laba Bersih</th>
                <td class="border border-black p-2 font-[900] text-[18px]">Rp {{ number_format($labaBersih, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="mb-8">
        <h3 class="text-[16px] font-[900] mb-2 border-b-2 border-black/20 pb-1">B. Rincian Pemasukan Transaksi</h3>
        <table class="w-full text-left border-collapse border border-black text-[12px]">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-black p-2 w-[120px]">Tanggal</th>
                    <th class="border border-black p-2 w-[80px]">ID INV</th>
                    <th class="border border-black p-2">Pelanggan</th>
                    <th class="border border-black p-2 w-[80px] text-center">Metode</th>
                    <th class="border border-black p-2 w-[120px] text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="border border-black p-2">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="border border-black p-2">#{{ $order->id }}</td>
                    <td class="border border-black p-2">{{ $order->nama_pelanggan }}</td>
                    <td class="border border-black p-2 text-center">{{ $order->metode_pembayaran }}</td>
                    <td class="border border-black p-2 text-right font-[700]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-black p-4 text-center italic">Tidak ada transaksi pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mb-8">
        <h3 class="text-[16px] font-[900] mb-2 border-b-2 border-black/20 pb-1">C. Rincian Pengeluaran</h3>
        <table class="w-full text-left border-collapse border border-black text-[12px]">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-black p-2 w-[120px]">Tanggal</th>
                    <th class="border border-black p-2">Keterangan / Nama Pengeluaran</th>
                    <th class="border border-black p-2 w-[120px] text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran as $out)
                <tr>
                    <td class="border border-black p-2">{{ \Carbon\Carbon::parse($out->created_at)->format('d/m/Y') }}</td>
                    <td class="border border-black p-2">{{ $out->nama_pengeluaran ?? $out->keterangan ?? '-' }}</td>
                    <td class="border border-black p-2 font-[900] text-red-600">- Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="border border-black p-4 text-center italic">Tidak ada pengeluaran pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-12 flex justify-end">
        <div class="text-center w-[200px]">
            <p class="text-[14px] font-[700] mb-16">Medan, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>Mengetahui,</p>
            <p class="text-[14px] font-[900] underline">{{ Auth::user()->name ?? 'Pemilik Warung' }}</p>
            <p class="text-[12px] font-[700]">Owner Seblak Jeletet</p>
        </div>
    </div>

</body>
</html>

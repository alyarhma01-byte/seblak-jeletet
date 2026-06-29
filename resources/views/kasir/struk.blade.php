<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - #{{ $order->id }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('LOGO SEBLAK.jpg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Courier+New&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #FAF7F4; /* Warna background sistem */
            color: #1A1A1A;
            margin: 0;
            padding: 0;
        }

        /* Ukuran kertas struk thermal standar (Font Courier) */
        .struk-container {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm;
            padding: 10px;
            margin: auto;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .garis {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; padding: 2px 0; }
        .item-name { display: block; font-weight: bold; font-size: 11px; }
        .item-note { font-size: 10px; display: block; padding-left: 8px; color: #333; }

        /* MENGATUR TAMPILAN SAAT DI-PRINT AGAR TOMBOL HILANG & KERTAS PAS */
        @media print {
            .no-print { display: none !important; }
            body { background-color: #fff !important; align-items: flex-start !important; justify-content: flex-start !important; padding: 0 !important; }
            .print-reset { border: none !important; box-shadow: none !important; padding: 0 !important; margin: 0 !important; border-radius: 0 !important; background: transparent !important; }
            .struk-container { width: 58mm !important; padding: 0 !important; margin: 0 !important; }
        }
    </style>
</head>
<body onload="window.print()" class="flex items-center justify-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-[24px] shadow-sm border border-black/5 flex flex-col items-center print-reset w-full max-w-[400px]">

        <div class="struk-container">
            <div class="text-center font-bold" style="font-size: 16px;">SEBLAK JELETET MEDAN</div>
            <div class="text-center" style="font-size: 10px; margin-top: 4px; line-height: 1.3;">
                Jl. Tuasan Gg. Rukun No.19<br>
                Medan 20222, Indonesia<br>
                Telp. 0896-3378-7703
            </div>

            <div class="garis"></div>

            <table style="font-size: 11px;">
                <tr><td>Tgl</td><td class="text-right">{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i') }}</td></tr>
                <tr><td>Kasir</td><td class="text-right">{{ Auth::user()->name ?? 'Kasir Utama' }}</td></tr>
                <tr><td>INV</td><td class="text-right">#{{ $order->id }}</td></tr>
                <tr><td>Meja</td><td class="text-right font-bold">#{{ str_pad($order->no_meja, 2, '0', STR_PAD_LEFT) }}</td></tr>
                <tr><td>Nama</td><td class="text-right">{{ $order->nama_pelanggan }}</td></tr>
            </table>

            <div class="garis"></div>

            <table>
                @foreach($order->details as $item)
                <tr>
                    <td colspan="2">
                        <span class="item-name">{{ strtoupper($item->menu_name) }}</span>
                        @if($item->catatan || $item->level !== null || $item->kencur || $item->kuah)
                            <span class="item-note">
                                @if($item->level !== null) Lv{{ $item->level }} @endif
                                @if($item->kencur) | {{ $item->kencur }} @endif
                                @if($item->kuah) | {{ $item->kuah }} @endif
                                {{ $item->catatan ? '| '.$item->catatan : '' }}
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 11px; font-weight: bold;">{{ $item->qty }} x {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="font-size: 11px;">{{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </table>

            <div class="garis"></div>

            <table style="font-size: 12px;">
                <tr>
                    <td class="font-bold" style="font-size: 14px;">TOTAL</td>
                    <td class="text-right font-bold" style="font-size: 14px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Metode</td>
                    <td class="text-right">{{ strtoupper($order->metode_pembayaran) }}</td>
                </tr>
                @if($order->metode_pembayaran == 'Tunai')
                <tr>
                    <td>Tunai</td>
                    <td class="text-right">Rp {{ number_format($order->uang_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td class="text-right">Rp {{ number_format($order->kembalian, 0, ',', '.') }}</td>
                </tr>
                @endif
            </table>

            <div class="garis"></div>

            <div class="text-center" style="font-size: 10px; margin-top: 10px; line-height: 1.3;">
                Terima Kasih Atas Kunjungan Anda<br>
                seblakjeletetmarelan@gmail.com
            </div>
            <div class="text-center" style="font-size: 10px; margin-top: 15px;">
                - - - - - - - - - - - - - -
            </div>
        </div>
        <div class="no-print flex justify-center gap-3 w-full mt-8 pt-6 border-t border-black/5">
            <button onclick="window.history.back()" class="flex-1 bg-white border-2 border-[#1A1A1A] text-[#1A1A1A] px-4 py-3 rounded-[14px] font-[900] text-[13px] hover:bg-[#F3F3F3] transition text-center tracking-wide">
                KEMBALI
            </button>
            <button onclick="window.print()" class="flex-1 bg-[#1A1A1A] border-2 border-[#1A1A1A] text-white px-4 py-3 rounded-[14px] font-[900] text-[13px] hover:bg-black transition shadow-md text-center tracking-wide">
                DOWNLOAD STRUK
            </button>
        </div>

    </div>

</body>
</html>

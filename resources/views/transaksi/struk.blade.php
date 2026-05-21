<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Parkir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .struk {
            width: 300px;
            background: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .border-top {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        
        .border-bottom {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }
        
        .border-double {
            border-top: 3px double #000;
            margin: 10px 0;
        }
        
        .table {
            width: 100%;
            margin: 10px 0;
        }
        
        .table td {
            padding: 4px 0;
        }
        
        .total {
            font-size: 14px;
            font-weight: bold;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
        }
        
        .btn-print {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-close {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .struk {
                box-shadow: none;
                margin: 0;
                padding: 10px;
            }
            .btn-print, .btn-close {
                display: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="struk">
        <div class="text-center">
            <div class="logo">PARKIR SYSTEM</div>
            <div>Jl. Parkir No. 123</div>
            <div>Telp. (021) 12345678</div>
            <div class="border-top"></div>
        </div>
        
        <table class="table">
            <tr>
                <td width="40%">No. Transaksi</td>
                <td width="10%">:</td>
                <td>{{ $transaksi->id_parkir }}</td>
            </tr>
            <tr>
                <td>Plat Nomor</td>
                <td>:</td>
                <td><strong>{{ $transaksi->plat_nomor }}</strong></td>
            </tr>
            <tr>
                <td>Jenis</td>
                <td>:</td>
                <td>{{ $transaksi->jenis_kendaraan }}</td>
            </tr>
            <tr>
                <td>Warna</td>
                <td>:</td>
                <td>{{ $transaksi->warna ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pemilik</td>
                <td>:</td>
                <td>{{ $transaksi->pemilik ?? '-' }}</td>
            </tr>
            <tr>
                <td>Area Parkir</td>
                <td>:</td>
                <td>{{ $transaksi->nama_area }}</td>
            </tr>
        </table>
        
        <div class="border-top"></div>
        
        <table class="table">
            <tr>
                <td width="40%">Waktu Masuk</td>
                <td width="10%">:</td>
                <td>{{ date('d/m/Y H:i:s', strtotime($transaksi->waktu_masuk)) }}</td>
            </tr>
            <tr>
                <td>Waktu Keluar</td>
                <td>:</td>
                <td>{{ date('d/m/Y H:i:s', strtotime($transaksi->waktu_keluar)) }}</td>
            </tr>
            <tr>
                <td>Durasi</td>
                <td>:</td>
                <td>{{ $transaksi->durasi_jam }} Jam</td>
            </tr>
            <tr>
                <td>Tarif/Jam</td>
                <td>:</td>
                <td>Rp {{ number_format($transaksi->tarif_per_jam) }}</td>
            </tr>
        </table>
        
        <div class="border-double"></div>
        
        <table class="table">
            <tr class="total">
                <td width="40%">TOTAL BAYAR</td>
                <td width="10%">:</td>
                <td class="text-right">Rp {{ number_format($transaksi->biaya_total) }}</td>
            </tr>
        </table>
        
        <div class="border-double"></div>
        
        <table class="table">
            <tr>
                <td width="40%">Petugas</td>
                <td width="10%">:</td>
                <td>{{ $transaksi->nama_lengkap }}</td>
            </tr>
        </table>
        
        <div class="border-top"></div>
        
        <div class="text-center">
            <strong>TERIMA KASIH</strong><br>
            <small>~ Selamat Jalan ~</small>
        </div>
        
        <div class="border-top"></div>
        <div class="text-center">
            <small>Simpan struk ini sebagai bukti pembayaran</small>
        </div>
        
        <div class="no-print">
            <button class="btn-print" onclick="window.print()">
                <i class="fas fa-print me-2"></i> Cetak Struk
            </button>
            <button class="btn-close" onclick="window.close()">
                <i class="fas fa-times me-2"></i> Tutup
            </button>
        </div>
    </div>
    
    <script>
        // Auto print
        setTimeout(function() {
            window.print();
        }, 500);
    </script>
</body>
</html>
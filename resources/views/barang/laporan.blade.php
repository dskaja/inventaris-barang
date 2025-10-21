<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data Barang</title>
    <style>
        @page {
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000;
        }

        /* Header Laporan */
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 12px;
            margin-top: 3px;
        }

        .header .date-print {
            font-size: 10px;
            margin-top: 8px;
        }

        /* Info Laporan */
        .info-section {
            margin-bottom: 20px;
        }

        .info-section table {
            width: 100%;
            border: none;
        }

        .info-section td {
            border: none;
            padding: 2px 0;
            font-size: 11px;
        }

        .info-section td:first-child {
            width: 150px;
            font-weight: bold;
        }

        /* Tabel Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.data-table th {
            padding: 8px 5px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #000;
            background: #f0f0f0;
        }

        table.data-table td {
            padding: 6px 5px;
            border: 1px solid #000;
            font-size: 10px;
            vertical-align: top;
        }

        /* Kolom Spesifik */
        .col-no { 
            width: 3%; 
            text-align: center;
        }

        .col-kode { 
            width: 9%;
            text-align: center;
        }

        .col-nama { 
            width: 15%;
        }

        .col-kategori { width: 10%; }
        .col-lokasi { width: 10%; }
        .col-sumber { width: 9%; }
        .col-jumlah { 
            width: 7%; 
            text-align: center;
        }
        .col-tipe { width: 8%; }
        .col-kondisi { width: 10%; }
        .col-status { width: 10%; }
        .col-tanggal { 
            width: 9%; 
            text-align: center;
        }

        /* Text Style */
        .bold-text {
            font-weight: bold;
        }

        .small-text {
            font-size: 9px;
            font-style: italic;
            display: block;
            margin-top: 2px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px;
            font-style: italic;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .footer .summary {
            text-align: right;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-box p {
            margin-bottom: 5px;
        }

        .signature-line {
            margin-top: 70px;
            border-bottom: 1px solid #000;
            display: inline-block;
            width: 200px;
            padding-top: 5px;
        }

        .signature-name {
            margin-top: 5px;
            font-weight: bold;
        }

        /* Print Optimization */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }

            table.data-table {
                page-break-inside: auto;
            }

            table.data-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            table.data-table thead {
                display: table-header-group;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Laporan Data Barang Inventaris</h1>
        <p class="subtitle">Sistem Informasi Manajemen Inventaris</p>
        <p class="date-print">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <table>
            <tr>
                <td>Periode Laporan</td>
                <td>: {{ date('F Y') }}</td>
            </tr>
            <tr>
                <td>Total Data Barang</td>
                <td>: {{ count($barangs) }} Item</td>
            </tr>
            <tr>
                <td>Dicetak Oleh</td>
                <td>: {{ Auth::user()->name ?? 'Administrator' }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-kode">Kode Barang</th>
                <th class="col-nama">Nama Barang</th>
                <th class="col-kategori">Kategori</th>
                <th class="col-lokasi">Lokasi</th>
                <th class="col-sumber">Sumber Dana</th>
                <th class="col-jumlah">Jumlah</th>
                <th class="col-tipe">Tipe</th>
                <th class="col-kondisi">Kondisi</th>
                <th class="col-status">Status</th>
                <th class="col-tanggal">Tgl Pengadaan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($barangs as $index => $barang)
            <tr>
                <td class="col-no">{{ $index + 1 }}</td>
                <td class="col-kode bold-text">{{ $barang->kode_barang }}</td>
                <td class="col-nama">{{ $barang->nama_barang }}</td>
                <td class="col-kategori">{{ $barang->kategori->nama_kategori }}</td>
                <td class="col-lokasi">{{ $barang->lokasi->nama_lokasi }}</td>
                <td class="col-sumber">{{ $barang->sumber_dana }}</td>
                <td class="col-jumlah">{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                <td class="col-tipe">{{ $barang->tipe_barang }}</td>
                <td class="col-kondisi">
                    <span class="bold-text">{{ $barang->kondisi }}</span>
                    @if($barang->kondisi === 'Rusak Ringan' && $barang->keterangan_kerusakan)
                        <span class="small-text">{{ Str::limit($barang->keterangan_kerusakan, 40) }}</span>
                    @endif
                </td>
                <td class="col-status">
                    @if($barang->isBisaDipinjam())
                        Bisa Dipinjam
                    @else
                        @if($barang->kondisi === 'Rusak Berat')
                            Rusak Berat
                        @elseif($barang->jumlah <= 0)
                            Stok Habis
                        @else
                            Tidak Tersedia
                        @endif
                    @endif
                </td>
                <td class="col-tanggal">{{ date('d-m-Y', strtotime($barang->tanggal_pengadaan)) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="empty-state">
                    Tidak ada data barang yang tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="summary">
            Total: {{ count($barangs) }} Item Barang
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Mengetahui,</p>
                <p class="bold-text">Kepala Bagian Inventaris</p>
                <div class="signature-line"></div>
                <p class="signature-name">( ................................. )</p>
            </div>

            <div class="signature-box">
                <p>{{ date('d F Y') }}</p>
                <p class="bold-text">Petugas Inventaris</p>
                <div class="signature-line"></div>
                <p class="signature-name">( ................................. )</p>
            </div>
        </div>
    </div>
</body>
</html>
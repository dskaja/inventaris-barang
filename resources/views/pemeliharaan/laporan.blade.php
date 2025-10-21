<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Pemeliharaan Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Style untuk layar */
        @media screen {
            body {
                background-color: #f5f5f5;
                padding: 20px;
            }
            .page-container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                padding: 30px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                border-radius: 8px;
            }
            .no-print {
                display: block;
            }
            .print-only {
                display: none;
            }
        }

        /* Style untuk cetak */
        @media print {
            @page {
                size: A4 landscape;
                margin: 15mm;
            }
            body {
                margin: 0;
                padding: 0;
                background: white;
            }
            .page-container {
                max-width: 100%;
                padding: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }

        /* Style umum */
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
        }

        .header h1 {
            font-size: 20pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14pt;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px 10px;
            border: none;
        }

        .info-table td:first-child {
            width: 180px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .data-table th {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }

        .data-table td {
            vertical-align: top;
        }

        .summary-box {
            border: 2px solid #000;
            padding: 15px;
            background-color: #f8f9fa;
            margin-top: 20px;
        }

        .summary-box table {
            width: 100%;
        }

        .summary-box td {
            padding: 5px 10px;
        }

        .summary-box td:first-child {
            font-weight: bold;
            width: 200px;
        }

        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: right;
            text-align: center;
            width: 250px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Filter Section (Hidden saat print) -->
        <div class="no-print mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="bi bi-file-earmark-text"></i> Laporan Perbaikan</h4>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-success">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('pemeliharaan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('pemeliharaan.laporan') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="Dalam Perbaikan" {{ request('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-search"></i> Tampilkan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Header Laporan -->
        <div class="header">
            <h1>Laporan Perbaikan Barang</h1>
            <h2>Sistem Inventaris Barang</h2>
            <p style="font-size: 11pt;">
                Periode: {{ request('tanggal_dari') ? \Carbon\Carbon::parse(request('tanggal_dari'))->format('d/m/Y') : 'Semua' }} 
                s/d {{ request('tanggal_sampai') ? \Carbon\Carbon::parse(request('tanggal_sampai'))->format('d/m/Y') : 'Sekarang' }}
            </p>
        </div>

        <!-- Info Section -->
        <table class="info-table">
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Status Filter</td>
                <td>: {{ request('status') == 'semua' || !request('status') ? 'Semua Status' : request('status') }}</td>
            </tr>
            <tr>
                <td>Total Data</td>
                <td>: {{ $pemeliharaan->count() }} item</td>
            </tr>
        </table>

        <!-- Data Table -->
        @if($pemeliharaan->isEmpty())
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Tidak ada data perbaikan untuk ditampilkan
        </div>
        @else
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="10%">Kode Perbaikan</th>
                    <th width="15%">Barang</th>
                    <th width="25%">Keterangan</th>
                    <th width="12%">Biaya</th>
                    <th width="10%">Status</th>
                    <th width="10%">Tanggal Masuk</th>
                    <th width="10%">Tanggal Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemeliharaan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->kode_pemeliharaan }}</td>
                    <td>
                        <strong>{{ $item->nama_barang }}</strong><br>
                        <small style="font-size: 10pt;">{{ $item->kode_barang }}</small>
                    </td>
                    <td style="font-size: 10pt;">{{ $item->keterangan ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->biaya_perbaikan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                    <td class="text-center">{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                    <td class="text-center">
                        {{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-box">
            <table>
                <tr>
                    <td>Total Perbaikan</td>
                    <td>: {{ $pemeliharaan->count() }} item</td>
                </tr>
                <tr>
                    <td>Dalam Perbaikan</td>
                    <td>: {{ $dalamPerbaikan }} item</td>
                </tr>
                <tr>
                    <td>Selesai</td>
                    <td>: {{ $selesai }} item</td>
                </tr>
                <tr>
                    <td><strong>Total Biaya Perbaikan</strong></td>
                    <td><strong>: Rp {{ number_format($totalBiaya, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Jakarta, {{ now()->format('d F Y') }}</p>
                <p>Mengetahui,</p>
                <div class="signature-line">
                    <strong>(...........................)</strong><br>
                    Kepala Bagian Inventaris
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
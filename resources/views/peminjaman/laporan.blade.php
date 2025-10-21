{{-- resources/views/peminjaman/laporan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #f5f7fa;
            padding: 20px;
        }

        .container-custom {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
        }

        .filter-section h3 {
            margin-bottom: 15px;
            color: #2c3e50;
            font-size: 16px;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        .report-header {
            text-align: center;
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
        }

        .report-header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .report-header .subtitle {
            font-size: 14px;
            margin-bottom: 3px;
        }

        .report-header .date-print {
            font-size: 12px;
            color: #666;
        }

        .filter-info {
            background: #f8f9fa;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            font-size: 12px;
        }

        .filter-info strong {
            font-weight: 600;
        }

        .stats-simple {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .stats-simple table {
            width: 100%;
            margin: 0;
        }

        .stats-simple td {
            padding: 5px 10px;
            font-size: 13px;
            border: none;
        }

        .stats-simple td:first-child {
            font-weight: 600;
            width: 180px;
        }

        .stats-simple td:nth-child(2) {
            width: 20px;
            text-align: center;
        }

        table.table-report {
            font-size: 11px;
            border-collapse: collapse;
            width: 100%;
        }

        table.table-report th,
        table.table-report td {
            border: 1px solid #000;
            padding: 8px 6px;
            vertical-align: middle;
        }

        table.table-report thead th {
            background: #e9ecef;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 10px;
        }

        table.table-report tbody td {
            font-size: 11px;
        }

        table.table-report tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .text-center-custom {
            text-align: center;
        }

        .badge-simple {
            padding: 3px 8px;
            font-size: 10px;
            border-radius: 3px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-dipinjam {
            background: #ffc107;
            color: #000;
        }

        .badge-terlambat {
            background: #dc3545;
            color: #fff;
        }

        .badge-dikembalikan {
            background: #28a745;
            color: #fff;
        }

        .no-data {
            padding: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            font-style: italic;
        }

        .report-footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            font-size: 11px;
            text-align: right;
            color: #666;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .filter-section,
            .btn,
            .no-print {
                display: none !important;
            }

            .container-custom {
                box-shadow: none;
                padding: 15px;
                max-width: 100%;
            }

            .report-header {
                border-bottom: 2px solid #000;
            }

            table.table-report {
                page-break-inside: auto;
            }

            table.table-report tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            table.table-report thead {
                display: table-header-group;
            }

            .report-footer {
                position: fixed;
                bottom: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <!-- Filter Section (Hidden on Print) -->
        <div class="filter-section no-print">
            <h3>Filter Laporan</h3>
            <form action="{{ route('peminjaman.laporan') }}" method="GET" id="filterForm">
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ $filters['tanggal_dari'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ $filters['tanggal_sampai'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="statusSelect">
                            <option value="">Semua Status</option>
                            <option value="Dipinjam" {{ ($filters['status'] ?? '') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Terlambat" {{ ($filters['status'] ?? '') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            <option value="Dikembalikan" {{ ($filters['status'] ?? '') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Peminjam</label>
                        <input type="text" name="peminjam" class="form-control" placeholder="Cari peminjam..." value="{{ $filters['peminjam'] ?? '' }}">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                    <button type="button" onclick="window.print()" class="btn btn-success">Cetak / PDF</button>
                    <a href="{{ route('peminjaman.laporan') }}" class="btn btn-secondary">Reset Filter</a>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>

        <!-- Report Header -->
        <div class="report-header">
            <h1>LAPORAN PEMINJAMAN BARANG</h1>
            <div class="subtitle">Sistem Inventaris Barang</div>
            <div class="date-print">Dicetak pada: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }} WIB</div>
        </div>

        <!-- Filter Info -->
        @if(($filters['tanggal_dari'] ?? '') || ($filters['tanggal_sampai'] ?? '') || ($filters['status'] ?? '') || ($filters['peminjam'] ?? ''))
        <div class="filter-info">
            <strong>Filter Laporan:</strong>
            @if($filters['tanggal_dari'] ?? '')
                Tanggal Dari: {{ \Carbon\Carbon::parse($filters['tanggal_dari'])->isoFormat('D MMMM Y') }}
            @endif
            @if($filters['tanggal_sampai'] ?? '')
                | Tanggal Sampai: {{ \Carbon\Carbon::parse($filters['tanggal_sampai'])->isoFormat('D MMMM Y') }}
            @endif
            @if($filters['status'] ?? '')
                | Status: {{ $filters['status'] }}
            @endif
            @if($filters['peminjam'] ?? '')
                | Peminjam: {{ $filters['peminjam'] }}
            @endif
        </div>
        @endif

        <!-- Statistik Sederhana -->
        <div class="stats-simple">
            <table>
                <tr>
                    <td>Total Peminjaman</td>
                    <td>:</td>
                    <td><strong>{{ $stats['total'] }}</strong> transaksi</td>
                </tr>
                <tr>
                    <td>Sedang Dipinjam</td>
                    <td>:</td>
                    <td><strong>{{ $stats['dipinjam'] }}</strong> transaksi</td>
                </tr>
                <tr>
                    <td>Terlambat</td>
                    <td>:</td>
                    <td><strong>{{ $stats['terlambat'] }}</strong> transaksi</td>
                </tr>
                <tr>
                    <td>Dikembalikan</td>
                    <td>:</td>
                    <td><strong>{{ $stats['dikembalikan'] }}</strong> transaksi</td>
                </tr>
            </table>
        </div>

        <!-- Table -->
        @if($peminjaman->count() > 0)
        <table class="table-report">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="8%">Kode</th>
                    <th width="12%">Peminjam</th>
                    <th width="15%">Barang</th>
                    <th width="10%">Kategori</th>
                    <th width="7%">Jumlah</th>
                    <th width="10%">Tgl Pinjam</th>
                    <th width="10%">Tgl Kembali<br>Rencana</th>
                    <th width="10%">Tgl Kembali<br>Aktual</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman as $index => $item)
                <tr>
                    <td class="text-center-custom">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                    <td>
                        {{ $item->nama_peminjam }}<br>
                        <small style="color: #666;">{{ $item->telepon_peminjam }}</small>
                    </td>
                    <td>
                        <strong>{{ $item->barang->nama_barang }}</strong><br>
                        <small style="color: #666;">{{ $item->barang->kode_barang }}</small>
                    </td>
                    <td>{{ $item->barang->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-center-custom">
                        <strong>{{ $item->jumlah_dipinjam }}</strong> {{ $item->barang->satuan }}
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($item->tanggal_kembali_aktual)
                            {{ \Carbon\Carbon::parse($item->tanggal_kembali_aktual)->format('d/m/Y H:i') }}
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                    <td class="text-center-custom">
                        @if($item->status == 'Dipinjam')
                            <span class="badge-simple badge-dipinjam">Dipinjam</span>
                        @elseif($item->status == 'Terlambat')
                            <span class="badge-simple badge-terlambat">Terlambat</span>
                        @else
                            <span class="badge-simple badge-dikembalikan">Dikembalikan</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="no-data">
            Tidak ada data peminjaman yang sesuai dengan filter yang dipilih.
        </div>
        @endif

        <!-- Footer -->
        <div class="report-footer">
            Laporan ini digenerate otomatis oleh Sistem Inventaris Barang
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
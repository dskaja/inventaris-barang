<x-main-layout :title-page="'Detail Peminjaman'">
    <style>
        .detail-card {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            background: white;
        }

        .card-header-formal {
            background: #2c3e50;
            color: white;
            padding: 1.2rem 1.5rem;
            border-bottom: 3px solid #34495e;
        }

        .card-header-formal h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .status-badge-formal {
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .section-title-formal {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid #e0e0e0;
        }

        .info-table-formal {
            width: 100%;
            margin-bottom: 1.5rem;
        }

        .info-table-formal tr {
            border-bottom: 1px solid #f5f5f5;
        }

        .info-table-formal tr:last-child {
            border-bottom: none;
        }

        .info-table-formal th {
            color: #555;
            font-weight: 600;
            padding: 0.8rem 0;
            width: 38%;
            font-size: 0.9rem;
            vertical-align: top;
        }

        .info-table-formal td {
            color: #333;
            padding: 0.8rem 0;
            font-size: 0.9rem;
        }

        .info-table-formal td strong {
            color: #2c3e50;
            font-weight: 600;
        }

        .data-section-formal {
            background: white;
            padding: 1.5rem;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            margin-bottom: 1.2rem;
        }

        .badge-formal {
            padding: 0.35rem 0.8rem;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-block;
        }

        .keterangan-box-formal {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            padding: 1.2rem;
            border-radius: 6px;
            margin-top: 1.5rem;
        }

        .keterangan-box-formal h6 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
        }

        .keterangan-box-formal p {
            color: #555;
            line-height: 1.6;
            margin: 0;
            font-size: 0.9rem;
        }

        .action-buttons-formal {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }

        .btn-formal {
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            font-weight: 500;
            font-size: 0.9rem;
            border: none;
        }

        .btn-primary-formal {
            background: #2c3e50;
            color: white;
        }

        .btn-primary-formal:hover {
            background: #34495e;
            color: white;
        }

        .btn-secondary-formal {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary-formal:hover {
            background: #7f8c8d;
            color: white;
        }

        .highlight-value-formal {
            background: #ecf0f1;
            padding: 0.3rem 0.7rem;
            border-radius: 4px;
            font-weight: 600;
            color: #2c3e50;
        }

        .text-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .divider-formal {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 1.5rem 0;
        }

        /* Badge colors */
        .badge-dipinjam {
            background: #f39c12;
            color: white;
        }

        .badge-terlambat {
            background: #e74c3c;
            color: white;
        }

        .badge-dikembalikan {
            background: #27ae60;
            color: white;
        }

        .badge-kategori {
            background: #3498db;
            color: white;
        }

        .badge-lokasi {
            background: #95a5a6;
            color: white;
        }

        .badge-kondisi-baik {
            background: #27ae60;
            color: white;
        }

        .badge-kondisi-rusak {
            background: #e67e22;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .info-table-formal th {
                width: 45%;
                font-size: 0.85rem;
            }
            
            .info-table-formal td {
                font-size: 0.85rem;
            }

            .data-section-formal {
                padding: 1rem;
            }
        }
    </style>

    <div class="card detail-card">
        <div class="card-header card-header-formal d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5>Detail Informasi Peminjaman</h5>
            <span class="status-badge-formal {{ $peminjaman->status == 'Dipinjam' ? 'badge-dipinjam' : ($peminjaman->status == 'Terlambat' ? 'badge-terlambat' : 'badge-dikembalikan') }}">
                {{ $peminjaman->status }}
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="data-section-formal">
                        <h6 class="section-title-formal">Data Peminjaman</h6>
                        <table class="info-table-formal">
                            <tr>
                                <th>Kode Peminjaman</th>
                                <td>: <span class="highlight-value-formal">{{ $peminjaman->kode_peminjaman }}</span></td>
                            </tr>
                            <tr>
                                <th>Tanggal Pinjam</th>
                                <td>: {{ $peminjaman->tanggal_pinjam->format('d F Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <th>Tanggal Kembali (Rencana)</th>
                                <td>: {{ $peminjaman->tanggal_kembali_rencana->format('d F Y, H:i') }} WIB</td>
                            </tr>
                            @if($peminjaman->tanggal_kembali_aktual)
                            <tr>
                                <th>Tanggal Kembali (Aktual)</th>
                                <td>: <span class="text-value">{{ $peminjaman->tanggal_kembali_aktual->format('d F Y, H:i') }} WIB</span></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Durasi Peminjaman</th>
                                <td>: <strong>{{ $peminjaman->durasi_detail }}</strong></td>
                            </tr>
                            @if($peminjaman->keterlambatan)
                            <tr>
                                <th>Keterlambatan</th>
                                <td>: <span class="badge-formal badge-terlambat">{{ $peminjaman->keterlambatan }}</span></td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    <div class="data-section-formal">
                        <h6 class="section-title-formal">Data Peminjam</h6>
                        <table class="info-table-formal">
                            <tr>
                                <th>Nama Peminjam</th>
                                <td>: <strong>{{ $peminjaman->nama_peminjam }}</strong></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>: {{ $peminjaman->email_peminjam ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>: {{ $peminjaman->telepon_peminjam ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="data-section-formal">
                        <h6 class="section-title-formal">Data Barang</h6>
                        <table class="info-table-formal">
                            <tr>
                                <th>Kode Barang</th>
                                <td>: <span class="highlight-value-formal">{{ $peminjaman->barang->kode_barang }}</span></td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>: <strong>{{ $peminjaman->barang->nama_barang ?? $peminjaman->barang->nama ?? '-' }}</strong></td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>: <span class="badge-formal badge-kategori">{{ $peminjaman->barang->kategori->nama_kategori ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>: <span class="badge-formal badge-lokasi">{{ $peminjaman->barang->lokasi->nama_lokasi ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <th>Jumlah Dipinjam</th>
                                <td>: <span class="text-value">{{ $peminjaman->jumlah_dipinjam }} Unit</span></td>
                            </tr>
                        </table>
                    </div>

                    <div class="data-section-formal">
                        <h6 class="section-title-formal">Kondisi Barang</h6>
                        <table class="info-table-formal">
                            <tr>
                                <th>Kondisi Saat Pinjam</th>
                                <td>: <span class="badge-formal badge-kategori">{{ $peminjaman->kondisi_pinjam }}</span></td>
                            </tr>
                            @if($peminjaman->kondisi_kembali)
                            <tr>
                                <th>Kondisi Saat Kembali</th>
                                <td>: 
                                    <span class="badge-formal {{ $peminjaman->kondisi_kembali == 'Baik' ? 'badge-kondisi-baik' : 'badge-kondisi-rusak' }}">
                                        {{ $peminjaman->kondisi_kembali }}
                                    </span>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            @if($peminjaman->keterangan)
            <hr class="divider-formal">
            <div class="keterangan-box-formal">
                <h6>Keterangan</h6>
                <p style="white-space: pre-line;">{{ $peminjaman->keterangan }}</p>
            </div>
            @endif

            <div class="action-buttons-formal d-flex gap-2 flex-wrap">
                @if($peminjaman->status != 'Dikembalikan')
                <a href="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" class="btn btn-formal btn-primary-formal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                    </svg>
                    Kembalikan Barang
                </a>
                @endif
                <a href="{{ route('peminjaman.index') }}" class="btn btn-formal btn-secondary-formal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 5px;">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</x-main-layout>
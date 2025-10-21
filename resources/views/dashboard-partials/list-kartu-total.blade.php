<div class="row">
    @php 
    $kartus = [
        [
            'text' => 'TOTAL BARANG',
            'total' => $jumlahBarang,
            'route' => 'barang.index',
            'icon' => 'bi-box-seam',
            'color' => 'primary',
        ],
        [
            'text' => 'TOTAL KATEGORI',
            'total' => $jumlahKategori,
            'route' => 'kategori.index',
            'icon' => 'bi-tag',
            'color' => 'secondary',
        ],
        [
            'text' => 'TOTAL LOKASI',
            'total' => $jumlahLokasi,
            'route' => 'lokasi.index',
            'icon' => 'bi-geo-alt',
            'color' => 'success',
        ],
        [
            'text' => 'TOTAL USER',
            'total' => $jumlahUser,
            'route' => 'user.index',
            'icon' => 'bi-people',
            'color' => 'danger',
            'role' => 'admin'
        ],
        [
            'text' => 'PEMINJAMAN AKTIF',
            'total' => $jumlahPeminjamanAktif,
            'route' => 'peminjaman.index',
            'icon' => 'bi-arrow-left-right',
            'color' => 'warning',
        ],
        [
            'text' => 'TERLAMBAT',
            'total' => $jumlahTerlambat,
            'route' => 'peminjaman.index',
            'icon' => 'bi-exclamation-triangle',
            'color' => 'danger',
        ],
        [
            'text' => 'PEMELIHARAAN',
            'total' => $jumlahPemeliharaan,
            'route' => 'pemeliharaan.index',
            'icon' => 'bi-tools',
            'color' => 'info',
        ],
    ];
    @endphp

    @foreach ($kartus as $kartu)
        @php 
        extract($kartu);
        @endphp
        
        @isset($role)
            @role($role)
                <x-kartu-total :text="$text" :route="$route" :total="$total" :icon="$icon" :color="$color"/>
            @endrole
        @else
            <x-kartu-total :text="$text" :route="$route" :total="$total" :icon="$icon" :color="$color"/>
        @endisset
    @endforeach
</div>
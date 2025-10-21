<x-main-layout :title-page="__('Tambah Barang')">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Form Tambah Barang</h5>
        </div>
        
        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                
                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-triangle"></i> Ada kesalahan input:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Tampilkan error dari controller --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-x-circle"></i> Error:</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Include form fields --}}
                @include('barang.partials._form')
            </div>
        </form>
    </div>
</x-main-layout>
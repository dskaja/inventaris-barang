<x-main-layout :title-page="__('Edit Barang')">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Form Edit Barang</h5>
        </div>
        
        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                @method('PUT')
                
                {{-- Tampilkan error --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong><i class="bi bi-exclamation-triangle"></i> Ada kesalahan:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Include form --}}
                @include('barang.partials._form', ['update' => true])
            </div>
        </form>
    </div>
</x-main-layout>
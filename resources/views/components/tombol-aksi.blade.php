@props(['href' => '#', 'type' => 'show'])

@if ($type === 'delete')
    <button type="button" 
            class="btn btn-danger btn-sm" 
            data-bs-toggle="modal" 
            data-bs-target="#deleteModal"
            data-url="{{ $href }}">
        <i class="bi bi-trash"></i>
    </button>
@else
    <a href="{{ $href }}" class="btn btn-sm btn-{{ $type === 'edit' ? 'warning' : 'info' }}">
        <i class="bi bi-{{ $type === 'edit' ? 'pencil' : 'eye' }}"></i>
    </a>
@endif
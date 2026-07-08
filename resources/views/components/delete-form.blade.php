@props(['action', 'label' => 'this item'])

<form
    method="POST"
    action="{{ $action }}"
    x-data
    @submit.prevent="
        Swal.fire({
            title: 'Delete {{ addslashes($label) }}?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it',
        }).then((result) => { if (result.isConfirmed) { $el.submit(); } })
    "
>
    @csrf
    @method('DELETE')
    <button type="submit" class="rounded-lg p-2 text-muted hover:bg-red-50 hover:text-danger" title="Delete">
        <i class="fa-solid fa-trash"></i>
    </button>
</form>

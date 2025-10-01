<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" method="post" action="#">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <i class="bi bi-exclamation-diamond text-danger"></i>
                    Konfirmasi Hapus
                </h1>
            </div>

            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="submit" class="btn btn-danger">
                    Ya, Hapus!
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = deleteModal.querySelector('form');
        
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const deleteUrl = button.getAttribute('data-url');
            deleteForm.setAttribute('action', deleteUrl);
        });
    });
</script>
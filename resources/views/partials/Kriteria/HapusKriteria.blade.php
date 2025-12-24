<!-- Modal Hapus Kriteria -->
<div class="modal fade" id="modalHapusKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="warning-outline"></ion-icon>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kriteria ini?</p>
                <p><strong id="hapusNama"></strong></p>
                <p class="text-warning">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <strong>Peringatan:</strong> Menghapus kriteria akan menghapus semua subkriteria dan bobot yang terkait!
                </p>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">
                    <ion-icon name="trash-outline"></ion-icon> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
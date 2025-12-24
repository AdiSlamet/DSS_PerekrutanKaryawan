<!-- Modal Edit Kriteria -->
<div class="modal fade" id="modalEditKriteria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="create-outline"></ion-icon>
                    Edit Kriteria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditKriteria">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama Kriteria *</label>
                        <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="editJenis" class="form-label">Jenis Kriteria *</label>
                        <select class="form-select" id="editJenis" name="jenis" required>
                            <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                            <option value="cost">Cost (Semakin kecil semakin baik)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnUpdateKriteria">
                    <ion-icon name="save-outline"></ion-icon> Update
                </button>
            </div>
        </div>
    </div>
</div>
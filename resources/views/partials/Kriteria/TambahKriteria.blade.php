<!-- Modal Tambah Kriteria -->
<div class="modal fade" id="modalTambahKriteria" tabindex="-1" aria-labelledby="modalTambahKriteriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKriteriaLabel">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Tambah Kriteria Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahKriteria">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               placeholder="Contoh: Pengalaman Kerja, Komunikasi, dll" required>
                        <div class="invalid-feedback">Nama kriteria harus diisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis Kriteria <span class="text-danger">*</span></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="">Pilih Jenis</option>
                            <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                            <option value="cost">Cost (Semakin kecil semakin baik)</option>
                        </select>
                        <small class="text-muted">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            Benefit: Nilai tinggi menguntungkan, Cost: Nilai rendah menguntungkan
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnSimpanKriteria">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
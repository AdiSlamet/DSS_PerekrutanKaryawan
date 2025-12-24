{{-- Modal Tambah Sub Kriteria --}}
<div class="custom-modal" id="modalTambahSubKriteria">
    <div class="modal-overlay" id="modalOverlayTambah"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Tambah Sub Kriteria Baru
                </h5>
                <button type="button" class="modal-close" id="closeTambahSub">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <form id="formTambahSubKriteria">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="tambahKriteriaId" name="kriteria_id">
                    <div class="form-group">
                        <label for="tambahNama" class="form-label">
                            Nama Sub Kriteria <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="tambahNama" name="nama" class="form-control" 
                               placeholder="Contoh: BPKB Mobil, SK Kios, dll" required>
                        <div class="invalid-feedback">Nama sub kriteria harus diisi</div>
                    </div>
                    <div class="form-group">
                        <label for="tambahNilai" class="form-label">
                            Nilai <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="tambahNilai" name="nilai" class="form-control" 
                               placeholder="Masukkan nilai (1-10)" min="1" max="10" step="0.1" required>
                        <div class="invalid-feedback">Nilai harus diisi antara 1-10</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnBatalTambahSub">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanSubKriteria">
                        <ion-icon name="save-outline"></ion-icon> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Sub Kriteria --}}
<div class="custom-modal" id="modalEditSubKriteria">
    <div class="modal-overlay" id="modalOverlayEdit"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="create-outline"></ion-icon>
                    Edit Sub Kriteria
                </h5>
                <button type="button" class="modal-close" id="closeEditSub">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <form id="formEditSubKriteria">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="editSubId" name="id">
                    <div class="form-group">
                        <label for="editSubNama" class="form-label">
                            Nama Sub Kriteria <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="editSubNama" name="nama" class="form-control" required>
                        <div class="invalid-feedback">Nama sub kriteria harus diisi</div>
                    </div>
                    <div class="form-group">
                        <label for="editSubNilai" class="form-label">
                            Nilai <span class="text-danger">*</span>
                        </label>
                        <input type="number" id="editSubNilai" name="nilai" class="form-control" 
                               min="1" max="10" step="0.1" required>
                        <div class="invalid-feedback">Nilai harus diisi antara 1-10</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btnBatalEditSub">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateSubKriteria">
                        <ion-icon name="save-outline"></ion-icon> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus Sub Kriteria --}}
<div class="custom-modal" id="modalHapusSubKriteria">
    <div class="modal-overlay" id="modalOverlayHapus"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="warning-outline"></ion-icon>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="modal-close" id="closeHapusSub">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 15px;">Apakah Anda yakin ingin menghapus sub kriteria ini?</p>
                <p style="font-weight: 500; margin-bottom: 20px;"><strong id="hapusSubNama"></strong></p>
                
                <div class="alert alert-warning">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <div class="alert-content">
                        <strong>Perhatian:</strong> Data yang dihapus tidak dapat dikembalikan.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalHapusSub">
                    Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapusSub">
                    <ion-icon name="trash-outline"></ion-icon> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
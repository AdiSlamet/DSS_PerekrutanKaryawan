<!-- Modal Tambah Kandidat -->
<link rel="stylesheet" href="css/partials/ModalTambah.css">
<div id="tambahModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Tambah Kandidat Baru</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addCandidateForm">
                @csrf
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" class="form-control" required>
                </div>

                <h3 class="section-title">Penilaian Awal</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pengalaman">Pengalaman *</label>
                        <select id="pengalaman" name="pengalaman" class="form-control" required>
                            <option value="">Pilih Pengalaman</option>
                            <option value="1">1 - Tidak Ada Pengalaman</option>
                            <option value="2">2 - Hanya Ikut Pelatihan</option>
                            <option value="3">3 - Pernah bekerja < 6 bulan</option>
                            <option value="4">4 - Pernah bekerja 6-12 bulan</option>
                            <option value="5">5 - Pernah bekerja > 1 tahun</option>
                        </select>
                        <small class="form-text">Bobot: 30%</small>
                    </div>

                    <div class="form-group">
                        <label for="jarak">Jarak Tempuh *</label>
                        <select id="jarak" name="jarak" class="form-control" required>
                            <option value="">Pilih Jarak</option>
                            <option value="1">1 - Sangat Jauh</option>
                            <option value="2">2 - Jauh</option>
                            <option value="3">3 - Cukup Dekat</option>
                            <option value="4">4 - Dekat</option>
                            <option value="5">5 - Sangat Dekat</option>
                        </select>
                        <small class="form-text">Bobot: 25%</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="komunikasi">Komunikasi *</label>
                        <select id="komunikasi" name="komunikasi" class="form-control" required>
                            <option value="">Pilih Level Komunikasi</option>
                            <option value="1">1 - Tidak Aktif</option>
                            <option value="2">2 - Kurang Aktif</option>
                            <option value="3">3 - Cukup Aktif</option>
                            <option value="4">4 - Aktif</option>
                            <option value="5">5 - Sangat Aktif</option>
                        </select>
                        <small class="form-text">Bobot: 25%</small>
                    </div>

                    <div class="form-group">
                        <label for="fleksibilitas">Fleksibilitas *</label>
                        <select id="fleksibilitas" name="fleksibilitas" class="form-control" required>
                            <option value="">Pilih Fleksibilitas</option>
                            <option value="1">1 - Tidak Fleksibel</option>
                            <option value="2">2 - Kurang Fleksibel</option>
                            <option value="3">3 - Cukup Fleksibel</option>
                            <option value="4">4 - Fleksibel</option>
                            <option value="5">5 - Sangat Fleksibel</option>
                        </select>
                        <small class="form-text">Bobot: 20%</small>
                    </div>
                </div>

                        <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="save-outline"></ion-icon> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary close-modal">
                        <ion-icon name="close-outline"></ion-icon> Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addCandidateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validasi form
    const nama = document.getElementById('nama').value;
    const pengalaman = document.getElementById('pengalaman').value;
    const jarak = document.getElementById('jarak').value;
    const komunikasi = document.getElementById('komunikasi').value;
    const fleksibilitas = document.getElementById('fleksibilitas').value;
    
    if (!nama || || !pengalaman || !jarak || !komunikasi || !fleksibilitas) {
        alert('Harap lengkapi semua field yang wajib diisi!');
        return;
    }
    
    // Simulasi penyimpanan data
    alert('Data kandidat berhasil disimpan!\n\nNama: ' + nama);
    
    // Redirect ke halaman daftar
    window.location.href = '#';
});


</script>
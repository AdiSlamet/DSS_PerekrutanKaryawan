<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Data Kandidat</h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editCandidateForm">
                <div class="form-group">
                    <label for="nama">Nama Lengkap *</label>
                    <input type="text" id="nama" name="nama" class="form-control" value="Andi Pratama" required>
                </div>

                <h3 class="section-title">Perbarui Penilaian</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="pengalaman">Pengalaman *</label>
                        <select id="pengalaman" name="pengalaman" class="form-control" required>
                            <option value="1">1 - Tidak Ada Pengalaman</option>
                            <option value="2">2 - Hanya Ikut Pelatihan</option>
                            <option value="3">3 - Pernah bekerja < 6 bulan</option>
                            <option value="4">4 - Pernah bekerja 6-12 bulan</option>
                            <option value="5" selected>5 - Pernah bekerja > 1 tahun</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jarak">Jarak Tempuh *</label>
                        <select id="jarak" name="jarak" class="form-control" required>
                            <option value="1">1 - Sangat Jauh</option>
                            <option value="2">2 - Jauh</option>
                            <option value="3">3 - Cukup Dekat</option>
                            <option value="4">4 - Dekat</option>
                            <option value="5" selected>5 - Sangat Dekat</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="komunikasi">Komunikasi *</label>
                        <select id="komunikasi" name="komunikasi" class="form-control" required>
                            <option value="1">1 - Tidak Aktif</option>
                            <option value="2">2 - Kurang Aktif</option>
                            <option value="3" selected>3 - Cukup Aktif</option>
                            <option value="4">4 - Aktif</option>
                            <option value="5">5 - Sangat Aktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fleksibilitas">Fleksibilitas *</label>
                        <select id="fleksibilitas" name="fleksibilitas" class="form-control" required>
                            <option value="1">1 - Tidak Fleksibel</option>
                            <option value="2">2 - Kurang Fleksibel</option>
                            <option value="3">3 - Cukup Fleksibel</option>
                            <option value="4" selected>4 - Fleksibel</option>
                            <option value="5">5 - Sangat Fleksibel</option>
                        </select>
                    </div>
                </div>

                <div class="current-score">
                    <h4>Skor Saat Ini: <span class="score-value">1.00</span></h4>
                    <div class="score-info">
                        <p>Pengalaman: 5 × 30% = 1.5</p>
                        <p>Jarak: 5 × 25% = 1.25</p>
                        <p>Komunikasi: 3 × 25% = 0.75</p>
                        <p>Fleksibilitas: 4 × 20% = 0.8</p>
                        <p><strong>Total: 1.00</strong></p>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="save-outline"></ion-icon> Update
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
document.getElementById('editCandidateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simulasi update data
    alert('Data kandidat berhasil diperbarui!');
    window.location.href = '#'; // Redirect ke halaman daftar
});

function deleteCandidate() {
    if (confirm('Apakah Anda yakin ingin menghapus kandidat ini?\nData yang dihapus tidak dapat dikembalikan.')) {
        alert('Kandidat berhasil dihapus!');
        window.location.href = '#'; // Redirect ke halaman daftar
    }
}

// Hitung skor otomatis saat dropdown berubah
document.querySelectorAll('#pengalaman, #jarak, #komunikasi, #fleksibilitas').forEach(select => {
    select.addEventListener('change', calculateScore);
});

function calculateScore() {
    const bobot = {
        pengalaman: 0.3,
        jarak: 0.25,
        komunikasi: 0.25,
        fleksibilitas: 0.2
    };
    
    let total = 0;
    
    // Hitung normalisasi benefit
    const pengalaman = parseInt(document.getElementById('pengalaman').value) || 0;
    const jarak = parseInt(document.getElementById('jarak').value) || 0;
    const komunikasi = parseInt(document.getElementById('komunikasi').value) || 0;
    const fleksibilitas = parseInt(document.getElementById('fleksibilitas').value) || 0;
    
    // Asumsi max value = 5
    const maxValues = { pengalaman: 5, jarak: 5, komunikasi: 5, fleksibilitas: 5 };
    
    const normalized = {
        pengalaman: pengalaman / maxValues.pengalaman,
        jarak: jarak / maxValues.jarak,
        komunikasi: komunikasi / maxValues.komunikasi,
        fleksibilitas: fleksibilitas / maxValues.fleksibilitas
    };
    
    total = (normalized.pengalaman * bobot.pengalaman) +
            (normalized.jarak * bobot.jarak) +
            (normalized.komunikasi * bobot.komunikasi) +
            (normalized.fleksibilitas * bobot.fleksibilitas);
    
    // Update tampilan skor
    document.querySelector('.score-value').textContent = total.toFixed(2);
}
</script>
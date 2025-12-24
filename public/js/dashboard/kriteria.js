class KriteriaManager {
    constructor() {
        console.log('KriteriaManager initialized');
        this.kriteriaData = [];
        
        this.initElements();
        this.bindEvents();
        this.loadKriteria();
    }

    initElements() {
        console.log('Initializing elements...');
        this.tableBody = document.getElementById('kriteriaTableBody');
        this.emptyState = document.getElementById('emptyState');
        this.loadingState = document.getElementById('loadingState');
        
        this.btnTambahKriteria = document.getElementById('btnTambahKriteria');
        this.btnRefresh = document.getElementById('btnRefresh');
        this.btnTambahPertama = document.getElementById('btnTambahPertama');
        
        this.totalKriteria = document.getElementById('totalKriteria');
        this.totalBenefit = document.getElementById('totalBenefit');
        this.totalCost = document.getElementById('totalCost');
        
        // Modal elements
        this.modalTambah = document.getElementById('modalTambahKriteria');
        this.modalEdit = document.getElementById('modalEditKriteria');
        this.modalHapus = document.getElementById('modalHapusKriteria');
        
        console.log('Elements initialized');
    }

    bindEvents() {
        console.log('Binding events...');
        
        // Event tombol utama
        this.btnTambahKriteria?.addEventListener('click', () => this.openTambahModal());
        this.btnTambahPertama?.addEventListener('click', () => this.openTambahModal());
        this.btnRefresh?.addEventListener('click', () => this.loadKriteria());
        
        // Event modal tambah
        document.getElementById('closeTambah')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('modalOverlayTambah')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('btnBatalTambah')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('btnSimpanKriteria')?.addEventListener('click', () => this.saveKriteria());
        
        // Event modal edit
        document.getElementById('closeEdit')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('modalOverlayEdit')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('btnBatalEdit')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('btnUpdateKriteria')?.addEventListener('click', () => this.updateKriteria());
        
        // Event modal hapus
        document.getElementById('closeHapus')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('modalOverlayHapus')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('btnBatalHapus')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('btnKonfirmasiHapus')?.addEventListener('click', () => this.confirmDelete());
        
        // ESC key untuk close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
        
        console.log('Events bound successfully');
    }

    async loadKriteria() {
        console.log('Loading kriteria from:', API_CONFIG.kriteriaIndex);
        this.showLoading();
        
        try {
            const response = await fetch(API_CONFIG.kriteriaIndex, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Error response:', errorText);
                throw new Error(`HTTP ${response.status}: Gagal memuat data`);
            }
            
            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.status === 'success') {
                this.kriteriaData = data.data || [];
                this.renderTable();
                this.updateStatistics();
            } else {
                throw new Error(data.message || 'Gagal memuat data');
            }
        } catch (error) {
            console.error('Error loading kriteria:', error);
            this.showToast(error.message || 'Gagal memuat data kriteria', 'error');
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'block';
        } finally {
            this.hideLoading();
        }
    }

    async saveKriteria() {
        console.log('Saving kriteria...');
        
        const nama = document.getElementById('nama')?.value.trim() || '';
        const jenis = document.getElementById('jenis')?.value || '';
        
        // Validasi form
        let isValid = true;
        
        if (!nama) {
            document.getElementById('nama').classList.add('is-invalid');
            isValid = false;
        } else {
            document.getElementById('nama').classList.remove('is-invalid');
        }
        
        if (!jenis) {
            document.getElementById('jenis').classList.add('is-invalid');
            isValid = false;
        } else {
            document.getElementById('jenis').classList.remove('is-invalid');
        }
        
        if (!isValid) {
            this.showToast('Harap isi semua field yang wajib diisi', 'error');
            return;
        }
        
        try {
            console.log('Sending POST to:', API_CONFIG.kriteriaStore);
            console.log('Data:', { nama, jenis });
            
            const response = await fetch(API_CONFIG.kriteriaStore, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                },
                body: JSON.stringify({ 
                    nama: nama,
                    jenis: jenis
                })
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (response.ok && data.status === 'success') {
                this.showToast('Kriteria berhasil ditambahkan', 'success');
                this.closeTambahModal();
                await this.loadKriteria();
            } else {
                // Handle validation errors from Laravel
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Gagal menambahkan kriteria');
            }
        } catch (error) {
            console.error('Error saving kriteria:', error);
            this.showToast(error.message || 'Gagal menambahkan kriteria', 'error');
        }
    }

async updateKriteria() {
    const id = document.getElementById('editId').value;
    const nama = document.getElementById('editNama')?.value.trim() || '';
    const jenis = document.getElementById('editJenis')?.value || '';
    
    // Validasi
    if (!nama || !jenis) {
        if (!nama) document.getElementById('editNama').classList.add('is-invalid');
        if (!jenis) document.getElementById('editJenis').classList.add('is-invalid');
        this.showToast('Harap lengkapi semua field', 'error');
        return;
    }
    
    // Reset validation
    document.getElementById('editNama').classList.remove('is-invalid');
    document.getElementById('editJenis').classList.remove('is-invalid');
    
    this.showToast('Memperbarui data...', 'info');
    
    try {
        // Coba dua cara berbeda
        
        // CARA 1: JSON dengan PUT
        console.log('Trying JSON PUT...');
        const jsonResponse = await this.tryJsonPut(id, nama, jenis);
        
        if (jsonResponse.success) {
            this.handleUpdateSuccess(id, nama, jenis, jsonResponse.data);
            return;
        }
        
        // CARA 2: FormData dengan POST _method=PUT
        console.log('JSON failed, trying FormData...');
        const formDataResponse = await this.tryFormDataPut(id, nama, jenis);
        
        if (formDataResponse.success) {
            this.handleUpdateSuccess(id, nama, jenis, formDataResponse.data);
            return;
        }
        
        // Jika keduanya gagal
        throw new Error('Semua metode update gagal');
        
    } catch (error) {
        console.error('Error updating kriteria:', error);
        this.showToast(error.message || 'Gagal memperbarui kriteria', 'error');
    }
}

// Helper methods
async tryJsonPut(id, nama, jenis) {
    try {
        const response = await fetch(`/api/kriteria/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ nama, jenis })
        });
        
        const data = await response.json();
        return { 
            success: response.ok && data.status === 'success', 
            data 
        };
    } catch (error) {
        return { success: false, error };
    }
}

async tryFormDataPut(id, nama, jenis) {
    try {
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('nama', nama);
        formData.append('jenis', jenis);
        
        const response = await fetch(`/api/kriteria/${id}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': API_CONFIG.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        return { 
            success: response.ok && data.status === 'success', 
            data 
        };
    } catch (error) {
        return { success: false, error };
    }
}

handleUpdateSuccess(id, nama, jenis, responseData) {
    console.log('Update successful:', responseData);
    
    // Update data lokal
    const index = this.kriteriaData.findIndex(k => k.id == id);
    if (index !== -1) {
        this.kriteriaData[index].nama = nama;
        this.kriteriaData[index].jenis = jenis;
        this.renderTable();
    }
    
    this.showToast('Kriteria berhasil diperbarui', 'success');
    this.closeEditModal();
    
    // Debug: cek apakah data benar-benar terupdate di database
    setTimeout(() => {
        this.debugCheckData(id);
    }, 1000);
}

// Debug method
async debugCheckData(id) {
    try {
        const response = await fetch(`/api/kriteria/${id}`);
        const data = await response.json();
        console.log('Debug check after update:', data);
    } catch (error) {
        console.error('Debug check error:', error);
    }
}

    
    async confirmDelete() {
        const id = this.modalHapus.dataset.id;
        
        try {
            const url = API_CONFIG.kriteriaDestroy(id);
            console.log('Sending DELETE to:', url);
            
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                }
            });
            
            console.log('Delete response status:', response.status);
            
            const data = await response.json();
            console.log('Delete response data:', data);
            
            if (response.ok && data.status === 'success') {
                this.showToast('Kriteria berhasil dihapus', 'success');
                this.closeHapusModal();
                await this.loadKriteria();
            } else {
                throw new Error(data.message || 'Gagal menghapus kriteria');
            }
        } catch (error) {
            console.error('Error deleting kriteria:', error);
            this.showToast(error.message || 'Gagal menghapus kriteria', 'error');
        }
    }

    // Method yang tetap sama
    openTambahModal() {
        console.log('Opening tambah modal');
        
        const form = document.getElementById('formTambahKriteria');
        if (form) {
            form.reset();
            // Reset validation states
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
            });
        }
        
        this.closeAllModals();
        this.modalTambah.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeTambahModal() {
        this.modalTambah.classList.remove('active');
        document.body.style.overflow = '';
    }

    openEditModal(kriteriaId) {
        const kriteria = this.kriteriaData.find(k => k.id == kriteriaId);
        if (!kriteria) {
            this.showToast('Kriteria tidak ditemukan', 'error');
            return;
        }
        
        document.getElementById('editId').value = kriteria.id;
        document.getElementById('editNama').value = kriteria.nama;
        document.getElementById('editJenis').value = kriteria.jenis;
        
        this.closeAllModals();
        this.modalEdit.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeEditModal() {
        this.modalEdit.classList.remove('active');
        document.body.style.overflow = '';
    }

    openHapusModal(kriteriaId) {
        const kriteria = this.kriteriaData.find(k => k.id == kriteriaId);
        if (!kriteria) {
            this.showToast('Kriteria tidak ditemukan', 'error');
            return;
        }
        
        document.getElementById('hapusNama').textContent = kriteria.nama;
        this.modalHapus.dataset.id = kriteriaId;
        
        this.closeAllModals();
        this.modalHapus.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeHapusModal() {
        this.modalHapus.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeAllModals() {
        document.querySelectorAll('.custom-modal').forEach(modal => {
            modal.classList.remove('active');
        });
        document.body.style.overflow = '';
    }

    editKriteria(id) {
        console.log('Editing kriteria:', id);
        this.openEditModal(id);
    }

    deleteKriteria(id) {
        console.log('Deleting kriteria:', id);
        this.openHapusModal(id);
    }

    renderTable() {
        console.log('Rendering kriteria:', this.kriteriaData);
        
        if (!this.kriteriaData || this.kriteriaData.length === 0) {
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'block';
            return;
        }
        
        this.emptyState.style.display = 'none';
        
        let html = '';
        
        this.kriteriaData.forEach((kriteria, index) => {
            const jenisClass = kriteria.jenis === 'benefit' ? 'jenis-benefit' : 'jenis-cost';
            const jenisText = kriteria.jenis === 'benefit' ? 'Benefit' : 'Cost';
            // Pastikan subkriteria ada atau default ke array kosong
            const subkriteria = kriteria.subkriteria || [];
            const jumlahSubkriteria = subkriteria.length;
            
            html += `
                <tr data-id="${kriteria.id}">
                    <td>${index + 1}</td>
                    <td>
                        <div class="kriteria-name">
                            <strong>${kriteria.nama || 'Tanpa Nama'}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="jenis-badge ${jenisClass}">
                            ${jenisText}
                        </span>
                    </td>
                    <td>
                        <span class="subkriteria-count">${jumlahSubkriteria}</span> subkriteria
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-edit" data-id="${kriteria.id}" title="Edit">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <button class="btn-action btn-delete" data-id="${kriteria.id}" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        this.tableBody.innerHTML = html;
        
        // Bind event untuk tombol aksi
        this.bindRowEvents();
    }

    bindRowEvents() {
        // Event untuk tombol edit
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const kriteriaId = e.currentTarget.dataset.id;
                this.editKriteria(kriteriaId);
            });
        });
        
        // Event untuk tombol delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const kriteriaId = e.currentTarget.dataset.id;
                this.deleteKriteria(kriteriaId);
            });
        });
    }

    updateStatistics() {
        const total = this.kriteriaData.length;
        const totalBenefit = this.kriteriaData.filter(k => k.jenis === 'benefit').length;
        const totalCost = this.kriteriaData.filter(k => k.jenis === 'cost').length;
        
        this.totalKriteria.textContent = total;
        this.totalBenefit.textContent = totalBenefit;
        this.totalCost.textContent = totalCost;
    }

    showLoading() {
        if (this.loadingState) this.loadingState.style.display = 'flex';
        if (this.emptyState) this.emptyState.style.display = 'none';
    }

    hideLoading() {
        if (this.loadingState) this.loadingState.style.display = 'none';
    }

    showToast(message, type = 'info') {
        console.log(`Toast [${type}]:`, message);
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1100;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s ease;
            border-left: 4px solid ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        `;
        
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <ion-icon name="${type === 'success' ? 'checkmark-circle' : type === 'error' ? 'alert-circle' : 'information-circle'}-outline" 
                         style="color: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'}; font-size: 20px;"></ion-icon>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Kriteria');
    try {
        window.kriteriaManager = new KriteriaManager();
        console.log('KriteriaManager initialized successfully');
    } catch (error) {
        console.error('Error initializing KriteriaManager:', error);
        // Tampilkan error ke user
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #f44336;
            color: white;
            padding: 15px;
            text-align: center;
            z-index: 9999;
        `;
        errorDiv.textContent = 'Terjadi kesalahan saat memuat halaman. Silakan refresh halaman.';
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }
});

    const BOBOT_CONFIG = {
        baseUrl: '{{ url("/") }}',
        bobotIndex: '/api/bobot',
        bobotStore: '/api/bobot',
        bobotUpdate: (id) => `/api/bobot/${id}`,
        bobotDestroy: (id) => `/api/bobot/${id}`,
        kriteriaList: '/api/kriteria', // Untuk dropdown pilih kriteria
        csrfToken: '{{ csrf_token() }}'
    };
    
    console.log('Bobot Config:', BOBOT_CONFIG);



class BobotManager {
    constructor() {
        console.log('BobotManager initialized');
        this.bobotData = [];
        this.kriteriaData = [];
        
        this.initElements();
        this.bindEvents();
        this.loadData();
    }

    initElements() {
        console.log('Initializing bobot elements...');
        this.tableBody = document.getElementById('bobotTableBody');
        this.emptyState = document.getElementById('emptyState');
        this.loadingState = document.getElementById('loadingState');
        
        this.btnTambahBobot = document.getElementById('btnTambahBobot');
        this.btnRefreshBobot = document.getElementById('btnRefreshBobot');
        this.btnTambahPertamaBobot = document.getElementById('btnTambahPertamaBobot');
        
        this.totalBobot = document.getElementById('totalBobot');
        this.totalBobotValue = document.getElementById('totalBobotValue');
        this.averageBobot = document.getElementById('averageBobot');
        
        // Modal elements
        this.modalTambah = document.getElementById('modalTambahBobot');
        this.modalEdit = document.getElementById('modalEditBobot');
        this.modalHapus = document.getElementById('modalHapusBobot');
        
        console.log('Bobot elements initialized');
    }

    bindEvents() {
        console.log('Binding bobot events...');
        
        // Event tombol utama
        this.btnTambahBobot?.addEventListener('click', () => this.openTambahModal());
        this.btnTambahPertamaBobot?.addEventListener('click', () => this.openTambahModal());
        this.btnRefreshBobot?.addEventListener('click', () => this.loadData());
        
        // Event modal tambah
        document.getElementById('closeTambahBobot')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('modalOverlayTambahBobot')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('btnBatalTambahBobot')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('btnSimpanBobot')?.addEventListener('click', () => this.saveBobot());
        
        // Event modal edit
        document.getElementById('closeEditBobot')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('modalOverlayEditBobot')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('btnBatalEditBobot')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('btnUpdateBobot')?.addEventListener('click', () => this.updateBobot());
        
        // Event modal hapus
        document.getElementById('closeHapusBobot')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('modalOverlayHapusBobot')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('btnBatalHapusBobot')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('btnKonfirmasiHapusBobot')?.addEventListener('click', () => this.confirmDelete());
        
        // ESC key untuk close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
        
        console.log('Bobot events bound successfully');
    }

    async loadData() {
        console.log('Loading bobot data...');
        this.showLoading();
        
        try {
            // Load data bobot dan kriteria secara bersamaan
            const [bobotResponse, kriteriaResponse] = await Promise.all([
                fetch(BOBOT_CONFIG.bobotIndex, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }),
                fetch(BOBOT_CONFIG.kriteriaList, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
            ]);
            
            console.log('Bobot response status:', bobotResponse.status);
            console.log('Kriteria response status:', kriteriaResponse.status);
            
            if (!bobotResponse.ok) throw new Error(`HTTP ${bobotResponse.status}: Gagal memuat bobot`);
            if (!kriteriaResponse.ok) throw new Error(`HTTP ${kriteriaResponse.status}: Gagal memuat kriteria`);
            
            const bobotData = await bobotResponse.json();
            const kriteriaData = await kriteriaResponse.json();
            
            console.log('Bobot data:', bobotData);
            console.log('Kriteria data:', kriteriaData);
            
            if (bobotData.status === 'success' && kriteriaData.status === 'success') {
                this.bobotData = bobotData.data || [];
                this.kriteriaData = kriteriaData.data || [];
                
                this.renderTable();
                this.updateStatistics();
            } else {
                throw new Error(bobotData.message || kriteriaData.message || 'Gagal memuat data');
            }
        } catch (error) {
            console.error('Error loading data:', error);
            this.showToast(error.message || 'Gagal memuat data', 'error');
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'block';
        } finally {
            this.hideLoading();
        }
    }

    renderTable() {
        console.log('Rendering bobot table:', this.bobotData);
        
        if (!this.bobotData || this.bobotData.length === 0) {
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'block';
            return;
        }
        
        this.emptyState.style.display = 'none';
        
        let html = '';
        
        this.bobotData.forEach((bobot, index) => {
            const bobotValue = parseFloat(bobot.bobot) || 0;
            const percentage = (bobotValue * 100).toFixed(1);
            
            // Dapatkan info kriteria
            const kriteria = bobot.kriteria || {};
            const kriteriaNama = kriteria.nama || 'Tidak diketahui';
            const kriteriaJenis = kriteria.jenis === 'benefit' ? 'Benefit' : 'Cost';
            
            // Tentukan class untuk bobot
            const bobotClass = bobotValue > 0.3 ? 'bobot-tinggi' : bobotValue < 0.1 ? 'bobot-rendah' : '';
            
            html += `
                <tr data-id="${bobot.id}">
                    <td>${index + 1}</td>
                    <td>
                        <div class="kriteria-info">
                            <span class="kriteria-name">${kriteriaNama}</span>
                            <span class="kriteria-type">${kriteriaJenis}</span>
                        </div>
                    </td>
                    <td>
                        <span class="bobot-value ${bobotClass}">${bobotValue.toFixed(2)}</span>
                    </td>
                    <td>
                        <span class="percentage-badge">${percentage}%</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-edit" data-id="${bobot.id}" title="Edit">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <button class="btn-action btn-delete" data-id="${bobot.id}" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        this.tableBody.innerHTML = html;
        this.bindRowEvents();
    }

    updateStatistics() {
        const total = this.bobotData.length;
        let totalValue = 0;
        
        this.bobotData.forEach(bobot => {
            totalValue += parseFloat(bobot.bobot) || 0;
        });
        
        const average = total > 0 ? totalValue / total : 0;
        
        this.totalBobot.textContent = total;
        this.totalBobotValue.textContent = totalValue.toFixed(2);
        this.averageBobot.textContent = average.toFixed(2);
    }

    // Modal Functions
    openTambahModal() {
        console.log('Opening tambah bobot modal');
        
        // Reset form
        const form = document.getElementById('formTambahBobot');
        if (form) {
            form.reset();
            // Reset validation
            const inputs = form.querySelectorAll('.form-control');
            inputs.forEach(input => input.classList.remove('is-invalid'));
        }
        
        // Isi dropdown kriteria
        this.populateKriteriaDropdown();
        
        this.closeAllModals();
        this.modalTambah.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    populateKriteriaDropdown() {
        const dropdown = document.getElementById('kriteria_id');
        if (!dropdown) return;
        
        // Simpan value yang dipilih sebelumnya
        const selectedValue = dropdown.value;
        
        // Kosongkan dropdown
        dropdown.innerHTML = '<option value="">Pilih Kriteria</option>';
        
        // Filter kriteria yang belum memiliki bobot
        const kriteriaWithBobot = new Set(this.bobotData.map(b => b.kriteria_id));
        const availableKriteria = this.kriteriaData.filter(k => !kriteriaWithBobot.has(k.id));
        
        if (availableKriteria.length === 0) {
            dropdown.innerHTML = '<option value="">Semua kriteria sudah memiliki bobot</option>';
            dropdown.disabled = true;
            return;
        }
        
        dropdown.disabled = false;
        
        // Tambahkan options
        availableKriteria.forEach(kriteria => {
            const option = document.createElement('option');
            option.value = kriteria.id;
            option.textContent = `${kriteria.nama} (${kriteria.jenis})`;
            dropdown.appendChild(option);
        });
        
        // Kembalikan value yang dipilih sebelumnya jika masih valid
        if (selectedValue && availableKriteria.some(k => k.id == selectedValue)) {
            dropdown.value = selectedValue;
        }
    }

    closeTambahModal() {
        this.modalTambah.classList.remove('active');
        document.body.style.overflow = '';
    }

    openEditModal(bobotId) {
        const bobot = this.bobotData.find(b => b.id == bobotId);
        if (!bobot) {
            this.showToast('Bobot tidak ditemukan', 'error');
            return;
        }
        
        // Isi form edit
        document.getElementById('editBobotId').value = bobot.id;
        document.getElementById('editKriteriaNama').value = bobot.kriteria?.nama || 'Tidak diketahui';
        document.getElementById('editBobot').value = bobot.bobot;
        
        this.closeAllModals();
        this.modalEdit.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeEditModal() {
        this.modalEdit.classList.remove('active');
        document.body.style.overflow = '';
    }

    openHapusModal(bobotId) {
        const bobot = this.bobotData.find(b => b.id == bobotId);
        if (!bobot) {
            this.showToast('Bobot tidak ditemukan', 'error');
            return;
        }
        
        const kriteriaNama = bobot.kriteria?.nama || 'Tidak diketahui';
        document.getElementById('hapusBobotInfo').textContent = 
            `Kriteria: ${kriteriaNama}, Bobot: ${bobot.bobot}`;
        
        this.modalHapus.dataset.id = bobotId;
        
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

    // CRUD Operations
    async saveBobot() {
        const kriteriaId = document.getElementById('kriteria_id')?.value;
        const bobotValue = document.getElementById('bobot')?.value;
        
        // Validasi
        let isValid = true;
        
        if (!kriteriaId) {
            document.getElementById('kriteria_id').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!bobotValue || bobotValue <= 0) {
            document.getElementById('bobot').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            this.showToast('Harap lengkapi semua field dengan benar', 'error');
            return;
        }
        
        try {
            console.log('Saving bobot...', { kriteria_id: kriteriaId, bobot: bobotValue });
            
            const response = await fetch(BOBOT_CONFIG.bobotStore, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': BOBOT_CONFIG.csrfToken
                },
                body: JSON.stringify({
                    kriteria_id: parseInt(kriteriaId),
                    bobot: parseFloat(bobotValue)
                })
            });
            
            console.log('Save response status:', response.status);
            
            const data = await response.json();
            console.log('Save response data:', data);
            
            if (response.ok && data.status === 'success') {
                this.showToast('Bobot berhasil ditambahkan', 'success');
                this.closeTambahModal();
                await this.loadData();
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Gagal menambahkan bobot');
            }
        } catch (error) {
            console.error('Error saving bobot:', error);
            this.showToast(error.message || 'Gagal menambahkan bobot', 'error');
        }
    }

    async updateBobot() {
        const id = document.getElementById('editBobotId').value;
        const bobotValue = document.getElementById('editBobot')?.value;
        
        if (!bobotValue || bobotValue <= 0) {
            document.getElementById('editBobot').classList.add('is-invalid');
            this.showToast('Nilai bobot harus diisi', 'error');
            return;
        }
        
        try {
            console.log('Updating bobot:', { id, bobot: bobotValue });
            
            // Gunakan FormData untuk kompatibilitas
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('bobot', bobotValue);
            
            const response = await fetch(BOBOT_CONFIG.bobotUpdate(id), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': BOBOT_CONFIG.csrfToken
                },
                body: formData
            });
            
            console.log('Update response status:', response.status);
            
            const data = await response.json();
            console.log('Update response data:', data);
            
            if (response.ok && data.status === 'success') {
                // Update data lokal
                const index = this.bobotData.findIndex(b => b.id == id);
                if (index !== -1) {
                    this.bobotData[index].bobot = parseFloat(bobotValue);
                    this.renderTable();
                    this.updateStatistics();
                }
                
                this.showToast('Bobot berhasil diperbarui', 'success');
                this.closeEditModal();
                
                // Auto refresh setelah 2 detik
                setTimeout(() => {
                    this.loadData();
                }, 2000);
                
            } else {
                throw new Error(data.message || 'Gagal memperbarui bobot');
            }
        } catch (error) {
            console.error('Error updating bobot:', error);
            this.showToast(error.message || 'Gagal memperbarui bobot', 'error');
        }
    }

    async confirmDelete() {
        const id = this.modalHapus.dataset.id;
        
        try {
            console.log('Deleting bobot:', id);
            
            const response = await fetch(BOBOT_CONFIG.bobotDestroy(id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': BOBOT_CONFIG.csrfToken
                }
            });
            
            console.log('Delete response status:', response.status);
            
            const data = await response.json();
            console.log('Delete response data:', data);
            
            if (response.ok && data.status === 'success') {
                this.showToast('Bobot berhasil dihapus', 'success');
                this.closeHapusModal();
                await this.loadData();
            } else {
                throw new Error(data.message || 'Gagal menghapus bobot');
            }
        } catch (error) {
            console.error('Error deleting bobot:', error);
            this.showToast(error.message || 'Gagal menghapus bobot', 'error');
        }
    }

    // Row Events
    bindRowEvents() {
        // Event untuk tombol edit
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const bobotId = e.currentTarget.dataset.id;
                this.editBobot(bobotId);
            });
        });
        
        // Event untuk tombol delete
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const bobotId = e.currentTarget.dataset.id;
                this.deleteBobot(bobotId);
            });
        });
    }

    editBobot(id) {
        console.log('Editing bobot:', id);
        this.openEditModal(id);
    }

    deleteBobot(id) {
        console.log('Deleting bobot:', id);
        this.openHapusModal(id);
    }

    // Utility Methods
    showLoading() {
        if (this.loadingState) this.loadingState.style.display = 'flex';
        if (this.emptyState) this.emptyState.style.display = 'none';
    }

    hideLoading() {
        if (this.loadingState) this.loadingState.style.display = 'none';
    }

    showToast(message, type = 'info') {
        console.log(`Toast [${type}]:`, message);
        
        // Hapus toast sebelumnya
        document.querySelectorAll('.toast').forEach(toast => toast.remove());
        
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
    console.log('DOM Content Loaded - Bobot');
    try {
        window.bobotManager = new BobotManager();
        console.log('BobotManager initialized successfully');
    } catch (error) {
        console.error('Error initializing BobotManager:', error);
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
class PenilaianManager {
    constructor() {
        console.log('PenilaianManager initialized');
        this.penilaianData = [];
        this.kandidatData = [];
        this.kriteriaData = [];
        this.subKriteriaData = [];
        this.currentStep = 1;
        this.selectedKandidat = null;
        
        this.API_CONFIG = {
            baseUrl: window.location.origin,
            penilaianIndex: '/api/penilaian',
            penilaianStore: '/api/penilaian',
            penilaianShow: (id) => `/api/penilaian/${id}`,
            penilaianDestroy: (id) => `/api/penilaian/${id}`,
            penilaianHitung: (id) => `/api/penilaian/${id}/hitung`,
            kandidatIndex: '/api/kandidat',
            kriteriaIndex: '/api/kriteria',
            subKriteriaIndex: '/api/sub-kriteria',
            subKriteriaByKriteria: (id) => `/api/kriteria/${id}/sub`,
            csrfToken: document.querySelector('meta[name="csrf-token"]').content
        };
        
        this.initElements();
        this.bindEvents();
        this.loadData();
    }

    initElements() {
        // Main elements
        this.penilaianTableBody = document.getElementById('penilaianTableBody');
        this.emptyState = document.getElementById('emptyState');
        this.loadingState = document.getElementById('loadingState');
        
        // Stats elements
        this.totalKandidat = document.getElementById('totalKandidat');
        this.totalDinilai = document.getElementById('totalDinilai');
        this.totalBelum = document.getElementById('totalBelum');
        
        // Buttons
        this.btnTambahPenilaian = document.getElementById('btnTambahPenilaian');
        this.btnRefreshPenilaian = document.getElementById('btnRefreshPenilaian');
        this.btnTambahPertama = document.getElementById('btnTambahPertama');
        this.btnApplyFilter = document.getElementById('btnApplyFilter');
        this.btnResetFilter = document.getElementById('btnResetFilter');
        
        // Modals
        this.modalTambah = document.getElementById('modalTambahPenilaian');
        this.modalDetail = document.getElementById('modalDetailPenilaian');
        this.modalHitung = document.getElementById('modalHitungSMART');
        
        // Forms
        this.formTambah = document.getElementById('formTambahPenilaian');
        this.kandidatSelect = document.getElementById('kandidat_id');
        this.formPenilaianContainer = document.getElementById('formPenilaianContainer');
        
        // Step navigation
        this.btnPrevStep = document.getElementById('btnPrevStep');
        this.btnNextStep = document.getElementById('btnNextStep');
        this.btnSimpanPenilaian = document.getElementById('btnSimpanPenilaian');
        
        // Modal close buttons
        this.btnBatalTambahPenilaian = document.getElementById('btnBatalTambahPenilaian');
        this.btnBatalDetail = document.getElementById('btnBatalDetail');
        this.btnBatalHitung = document.getElementById('btnBatalHitung');
    }

    bindEvents() {
        // Main buttons
        this.btnTambahPenilaian?.addEventListener('click', () => this.openTambahModal());
        this.btnTambahPertama?.addEventListener('click', () => this.openTambahModal());
        this.btnRefreshPenilaian?.addEventListener('click', () => this.loadData());
        
        // Filter buttons
        this.btnApplyFilter?.addEventListener('click', () => this.applyFilter());
        this.btnResetFilter?.addEventListener('click', () => this.resetFilter());
        
        // Step navigation
        this.btnPrevStep?.addEventListener('click', () => this.prevStep());
        this.btnNextStep?.addEventListener('click', () => this.nextStep());
        this.btnSimpanPenilaian?.addEventListener('click', (e) => {
            e.preventDefault();
            this.savePenilaian();
        });
        
        // Modal close events
        this.btnBatalTambahPenilaian?.addEventListener('click', () => this.closeTambahModal());
        this.btnBatalDetail?.addEventListener('click', () => this.closeDetailModal());
        this.btnBatalHitung?.addEventListener('click', () => this.closeHitungModal());
        
        // Kandidat select change
        this.kandidatSelect?.addEventListener('change', (e) => this.onKandidatSelect(e));
        
        // Form submit
        this.formTambah?.addEventListener('submit', (e) => e.preventDefault());
    }

    async loadData() {
        try {
            this.showLoading();
            
            // Load all data
            await Promise.all([
                this.loadPenilaian(),
                this.loadKandidat(),
                this.loadKriteria()
            ]);
            
            this.renderTable();
            this.updateStats();
            
        } catch (error) {
            console.error('Error loading data:', error);
            this.showToast('Gagal memuat data', 'error');
        } finally {
            this.hideLoading();
        }
    }

    async loadPenilaian() {
        const response = await fetch(this.API_CONFIG.penilaianIndex);
        if (!response.ok) throw new Error('Gagal memuat data penilaian');
        
        const data = await response.json();
        this.penilaianData = data.data || data;
    }

    async loadKandidat() {
        const response = await fetch(this.API_CONFIG.kandidatIndex);
        if (!response.ok) throw new Error('Gagal memuat data kandidat');
        
        const data = await response.json();
        this.kandidatData = data.data || data;
    }

    async loadKriteria() {
        const response = await fetch(this.API_CONFIG.kriteriaIndex);
        if (!response.ok) throw new Error('Gagal memuat data kriteria');
        
        const data = await response.json();
        this.kriteriaData = data.data || data;
        
        // Load sub kriteria for each kriteria
        this.subKriteriaData = [];
        for (const kriteria of this.kriteriaData) {
            const subResponse = await fetch(this.API_CONFIG.subKriteriaByKriteria(kriteria.id));
            if (subResponse.ok) {
                const subData = await subResponse.json();
                if (subData.data) {
                    this.subKriteriaData = [...this.subKriteriaData, ...subData.data];
                }
            }
        }
    }

    renderTable() {
        if (!this.penilaianData || this.penilaianData.length === 0) {
            this.penilaianTableBody.innerHTML = '';
            this.emptyState.style.display = 'block';
            return;
        }
        
        this.emptyState.style.display = 'none';
        
        let html = '';
        this.penilaianData.forEach((penilaian, index) => {
            const kandidat = this.kandidatData.find(k => k.id === penilaian.kandidat_id);
            const tanggal = new Date(penilaian.created_at).toLocaleDateString('id-ID');
            const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });
            
            html += `
                <tr data-id="${penilaian.id}">
                    <td>${index + 1}</td>
                    <td>
                        <strong>${kandidat?.nama || 'Unknown'}</strong>
                    </td>
                    <td>${periode}</td>
                    <td>${tanggal}</td>
                    <td>
                        <span class="status-badge sudah">Sudah Dinilai</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action view" data-id="${penilaian.id}" title="Lihat Detail">
                                <ion-icon name="eye-outline"></ion-icon>
                            </button>
                            <button class="btn-action calculate" data-id="${penilaian.id}" title="Hitung SMART">
                                <ion-icon name="calculator-outline"></ion-icon>
                            </button>
                            <button class="btn-action delete" data-id="${penilaian.id}" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        this.penilaianTableBody.innerHTML = html;
        
        // Attach event listeners to action buttons
        this.attachTableEvents();
    }

    attachTableEvents() {
        // View button
        document.querySelectorAll('.btn-action.view').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const penilaianId = e.currentTarget.dataset.id;
                this.viewDetail(penilaianId);
            });
        });
        
        // Calculate button
        document.querySelectorAll('.btn-action.calculate').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const penilaianId = e.currentTarget.dataset.id;
                this.calculateSMART(penilaianId);
            });
        });
        
        // Delete button
        document.querySelectorAll('.btn-action.delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const penilaianId = e.currentTarget.dataset.id;
                this.deletePenilaian(penilaianId);
            });
        });
    }

    updateStats() {
        const totalKandidat = this.kandidatData.length;
        const totalDinilai = this.penilaianData.length;
        const totalBelum = totalKandidat - totalDinilai;
        
        this.totalKandidat.textContent = totalKandidat;
        this.totalDinilai.textContent = totalDinilai;
        this.totalBelum.textContent = totalBelum;
    }

    // Modal Methods
    openTambahModal() {
        this.resetForm();
        this.populateKandidatSelect();
        this.currentStep = 1;
        this.updateStepUI();
        
        this.modalTambah.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeTambahModal() {
        this.modalTambah.classList.remove('active');
        document.body.style.overflow = '';
        this.resetForm();
    }

    async viewDetail(penilaianId) {
        try {
            const response = await fetch(this.API_CONFIG.penilaianShow(penilaianId));
            if (!response.ok) throw new Error('Gagal memuat detail penilaian');
            
            const data = await response.json();
            const penilaian = data.data || data;
            
            // Populate detail modal
            this.populateDetailModal(penilaian);
            
            this.modalDetail.classList.add('active');
            document.body.style.overflow = 'hidden';
            
        } catch (error) {
            console.error('Error viewing detail:', error);
            this.showToast('Gagal memuat detail penilaian', 'error');
        }
    }

    closeDetailModal() {
        this.modalDetail.classList.remove('active');
        document.body.style.overflow = '';
    }

    async calculateSMART(penilaianId) {
        try {
            const response = await fetch(this.API_CONFIG.penilaianHitung(penilaianId), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.API_CONFIG.csrfToken
                }
            });
            
            if (!response.ok) throw new Error('Gagal menghitung SMART');
            
            const data = await response.json();
            
            // Populate calculation modal
            this.populateHitungModal(data);
            
            this.modalHitung.classList.add('active');
            document.body.style.overflow = 'hidden';
            
        } catch (error) {
            console.error('Error calculating SMART:', error);
            this.showToast('Gagal menghitung SMART', 'error');
        }
    }

    closeHitungModal() {
        this.modalHitung.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Form Methods
    resetForm() {
        if (this.formTambah) this.formTambah.reset();
        this.selectedKandidat = null;
        this.formPenilaianContainer.innerHTML = '';
        
        const kandidatInfo = document.getElementById('kandidatInfo');
        if (kandidatInfo) kandidatInfo.style.display = 'none';
    }

    populateKandidatSelect() {
        if (!this.kandidatSelect) return;
        
        // Clear existing options except the first one
        while (this.kandidatSelect.options.length > 1) {
            this.kandidatSelect.remove(1);
        }
        
        // Filter kandidat yang belum dinilai
        const kandidatBelumDinilai = this.kandidatData.filter(kandidat => {
            return !this.penilaianData.some(p => p.kandidat_id === kandidat.id);
        });
        
        // Add options
        kandidatBelumDinilai.forEach(kandidat => {
            const option = document.createElement('option');
            option.value = kandidat.id;
            option.textContent = kandidat.nama;
            this.kandidatSelect.appendChild(option);
        });
    }

    onKandidatSelect(event) {
        const kandidatId = event.target.value;
        this.selectedKandidat = this.kandidatData.find(k => k.id == kandidatId);
        
        const kandidatInfo = document.getElementById('kandidatInfo');
        if (this.selectedKandidat) {
            kandidatInfo.style.display = 'block';
            document.getElementById('infoEmail').textContent = this.selectedKandidat.email || '-';
            document.getElementById('infoPhone').textContent = this.selectedKandidat.no_hp || '-';
            document.getElementById('infoCreatedAt').textContent = 
                new Date(this.selectedKandidat.created_at).toLocaleDateString('id-ID');
        } else {
            kandidatInfo.style.display = 'none';
        }
    }

    async nextStep() {
        if (this.currentStep === 1) {
            // Validate step 1
            if (!this.kandidatSelect.value) {
                this.showToast('Silakan pilih kandidat terlebih dahulu', 'error');
                return;
            }
            
            // Load penilaian form for step 2
            await this.loadPenilaianForm();
            this.currentStep = 2;
            
        } else if (this.currentStep === 2) {
            // Validate all criteria have been selected
            const allSelected = this.validatePenilaianForm();
            if (!allSelected) {
                this.showToast('Silakan pilih semua sub kriteria untuk setiap kriteria', 'error');
                return;
            }
        }
        
        this.updateStepUI();
    }

    prevStep() {
        if (this.currentStep === 2) {
            this.currentStep = 1;
            this.updateStepUI();
        }
    }

    updateStepUI() {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(step => {
            step.classList.remove('active');
        });
        
        // Show current step
        document.getElementById(`step${this.currentStep}`).classList.add('active');
        
        // Update button visibility
        if (this.currentStep === 1) {
            this.btnPrevStep.style.display = 'none';
            this.btnNextStep.style.display = 'inline-flex';
            this.btnSimpanPenilaian.style.display = 'none';
            this.btnNextStep.textContent = 'Selanjutnya';
        } else if (this.currentStep === 2) {
            this.btnPrevStep.style.display = 'inline-flex';
            this.btnNextStep.style.display = 'none';
            this.btnSimpanPenilaian.style.display = 'inline-flex';
        }
    }

    async loadPenilaianForm() {
        this.formPenilaianContainer.innerHTML = '';
        
        for (const kriteria of this.kriteriaData) {
            const subKriteriaList = this.subKriteriaData.filter(sub => sub.kriteria_id == kriteria.id);
            
            const kriteriaHTML = `
                <div class="kriteria-group" data-kriteria-id="${kriteria.id}">
                    <div class="kriteria-header">
                        <h4 class="kriteria-title">${kriteria.nama}</h4>
                        <span class="kriteria-code">${kriteria.kode || 'K-' + kriteria.id}</span>
                    </div>
                    <div class="subkriteria-options">
                        ${subKriteriaList.map(sub => `
                            <label class="radio-option">
                                <input type="radio" 
                                       name="kriteria_${kriteria.id}" 
                                       value="${sub.id}"
                                       data-kriteria-id="${kriteria.id}"
                                       data-nilai="${sub.nilai}">
                                <div class="radio-label">
                                    <strong>${sub.nama}</strong>
                                    <small>Pilih untuk kriteria ${kriteria.nama}</small>
                                </div>
                                <div class="nilai-display">${sub.nilai}</div>
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;
            
            this.formPenilaianContainer.innerHTML += kriteriaHTML;
        }
        
        // Add radio selection event listeners
        this.addRadioSelectionEvents();
    }

    addRadioSelectionEvents() {
        document.querySelectorAll('.radio-option input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const option = e.target.closest('.radio-option');
                // Remove selected class from all options in this group
                const group = option.closest('.kriteria-group');
                group.querySelectorAll('.radio-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                // Add selected class to current option
                option.classList.add('selected');
            });
        });
    }

    validatePenilaianForm() {
        const kriteriaGroups = document.querySelectorAll('.kriteria-group');
        let allValid = true;
        
        kriteriaGroups.forEach(group => {
            const selected = group.querySelector('input[type="radio"]:checked');
            if (!selected) {
                group.style.borderColor = '#ef4444';
                allValid = false;
            } else {
                group.style.borderColor = '#e5e7eb';
            }
        });
        
        return allValid;
    }

    getPenilaianData() {
        const data = {
            kandidat_id: this.kandidatSelect.value,
            sub_kriteria_id: []
        };
        
        document.querySelectorAll('.kriteria-group').forEach(group => {
            const selected = group.querySelector('input[type="radio"]:checked');
            if (selected) {
                const kriteriaId = selected.dataset.kriteriaId;
                const subKriteriaId = selected.value;
                data.sub_kriteria_id.push({
                    kriteria_id: kriteriaId,
                    sub_kriteria_id: subKriteriaId
                });
            }
        });
        
        return data;
    }

    async savePenilaian() {
        const formData = this.getPenilaianData();
        
        try {
            const response = await fetch(this.API_CONFIG.penilaianStore, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.API_CONFIG.csrfToken
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showToast('Penilaian berhasil disimpan', 'success');
                this.closeTambahModal();
                await this.loadData();
            } else {
                throw new Error(data.message || 'Gagal menyimpan penilaian');
            }
        } catch (error) {
            console.error('Error saving penilaian:', error);
            this.showToast(error.message, 'error');
        }
    }

    async deletePenilaian(penilaianId) {
        if (!confirm('Apakah Anda yakin ingin menghapus penilaian ini?')) {
            return;
        }
        
        try {
            const response = await fetch(this.API_CONFIG.penilaianDestroy(penilaianId), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.API_CONFIG.csrfToken
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showToast('Penilaian berhasil dihapus', 'success');
                await this.loadData();
            } else {
                throw new Error(data.message || 'Gagal menghapus penilaian');
            }
        } catch (error) {
            console.error('Error deleting penilaian:', error);
            this.showToast(error.message, 'error');
        }
    }

    populateDetailModal(penilaian) {
        // Populate header
        const kandidat = this.kandidatData.find(k => k.id === penilaian.kandidat_id);
        document.getElementById('detailNamaKandidat').textContent = kandidat?.nama || '-';
        document.getElementById('detailPeriode').textContent = 
            new Date(penilaian.periode).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        document.getElementById('detailTanggal').textContent = 
            new Date(penilaian.created_at).toLocaleDateString('id-ID');
        
        // Populate detail table
        const detailTableBody = document.getElementById('detailTableBody');
        detailTableBody.innerHTML = '';
        
        if (penilaian.detail_penilaian) {
            penilaian.detail_penilaian.forEach((detail, index) => {
                const row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${detail.kriteria?.nama || '-'}</td>
                        <td>${detail.sub_kriteria?.nama || '-'}</td>
                        <td>${detail.sub_kriteria?.nilai || '-'}</td>
                    </tr>
                `;
                detailTableBody.innerHTML += row;
            });
        }
    }

    populateHitungModal(data) {
        // This is a placeholder - you'll need to customize based on your SMART calculation response
        // The data structure will depend on your backend implementation
        
        const hitungPeriode = document.getElementById('hitungPeriode');
        const tableNormalisasi = document.getElementById('tableNormalisasi');
        const tableBobot = document.getElementById('tableBobot');
        const tableHasil = document.getElementById('tableHasil');
        
        // Example implementation - customize based on your actual data structure
        hitungPeriode.textContent = data.periode || '-';
        
        // Add your SMART calculation display logic here
        // This is just an example structure
        if (data.normalisasi) {
            // Populate normalization table
        }
        
        if (data.bobot) {
            // Populate weight table
        }
        
        if (data.ranking) {
            // Populate ranking table
        }
    }

    applyFilter() {
        const periode = document.getElementById('filterPeriode').value;
        const status = document.getElementById('filterStatus').value;
        
        // Apply filter logic here
        console.log('Applying filter:', { periode, status });
        // You'll need to implement the actual filtering logic based on your API
    }

    resetFilter() {
        document.getElementById('filterPeriode').value = '';
        document.getElementById('filterStatus').value = '';
        this.loadData();
    }

    // Utility Methods
    showLoading() {
        this.loadingState.style.display = 'flex';
        this.emptyState.style.display = 'none';
        this.penilaianTableBody.innerHTML = '';
    }

    hideLoading() {
        this.loadingState.style.display = 'none';
    }

    showToast(message, type = 'info') {
        // Create toast element
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
            border-left: 4px solid ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#4361ee'};
            min-width: 300px;
        `;
        
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <ion-icon name="${type === 'success' ? 'checkmark-circle' : type === 'error' ? 'alert-circle' : 'information-circle'}-outline" 
                         style="color: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#4361ee'}; font-size: 20px;"></ion-icon>
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

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.penilaianManager = new PenilaianManager();
});
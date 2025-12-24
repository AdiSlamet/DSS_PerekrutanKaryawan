class SubKriteriaManager {
    constructor() {
        this.currentKriteriaId = null;
        this.currentKriteria = null;
        this.initElements();
        this.initEvents();
        this.initSelect2();
    }

    initElements() {
        // Elements
        this.selectKriteria = document.getElementById('selectKriteria');
        this.btnTambahSubKriteria = document.getElementById('btnTambahSubKriteria');
        this.btnTambahPertama = document.getElementById('btnTambahPertama');
        this.btnRefresh = document.getElementById('btnRefreshSub');
        this.btnLihatKriteria = document.getElementById('btnLihatKriteria');
        
        // Table elements
        this.tableBody = document.getElementById('subKriteriaTableBody');
        this.loadingState = document.getElementById('loadingState');
        this.emptyStateDefault = document.getElementById('emptyStateDefault');
        this.emptyStateNoData = document.getElementById('emptyStateNoData');
        
        // Stats elements
        this.totalSubKriteria = document.getElementById('totalSubKriteria');
        this.nilaiTertinggi = document.getElementById('nilaiTertinggi');
        this.nilaiTerendah = document.getElementById('nilaiTerendah');
        
        // Info card
        this.kriteriaInfo = document.getElementById('kriteriaInfo');
        this.infoNamaKriteria = document.getElementById('infoNamaKriteria');
        this.infoJenisKriteria = document.getElementById('infoJenisKriteria');
        this.infoJumlahSubKriteria = document.getElementById('infoJumlahSubKriteria');
        
        // Modal elements
        this.initModalElements();
    }

    initModalElements() {
        // Tambah Modal
        this.modalTambah = document.getElementById('modalTambahSubKriteria');
        this.modalOverlayTambah = document.getElementById('modalOverlayTambah');
        this.closeTambah = document.getElementById('closeTambah');
        this.btnBatalTambah = document.getElementById('btnBatalTambah');
        this.btnSimpan = document.getElementById('btnSimpanSubKriteria');
        this.formTambah = document.getElementById('formTambahSubKriteria');
        
        // Edit Modal
        this.modalEdit = document.getElementById('modalEditSubKriteria');
        this.modalOverlayEdit = document.getElementById('modalOverlayEdit');
        this.closeEdit = document.getElementById('closeEdit');
        this.btnBatalEdit = document.getElementById('btnBatalEdit');
        this.btnUpdate = document.getElementById('btnUpdateSubKriteria');
        this.formEdit = document.getElementById('formEditSubKriteria');
        
        // Hapus Modal
        this.modalHapus = document.getElementById('modalHapusSubKriteria');
        this.modalOverlayHapus = document.getElementById('modalOverlayHapus');
        this.closeHapus = document.getElementById('closeHapus');
        this.btnBatalHapus = document.getElementById('btnBatalHapus');
        this.btnKonfirmasiHapus = document.getElementById('btnKonfirmasiHapus');
        this.hapusNama = document.getElementById('hapusNama');
    }

    initEvents() {
        // Kriteria selection
        this.selectKriteria.addEventListener('change', (e) => this.onKriteriaChange(e));
        
        // Tambah button
        this.btnTambahSubKriteria.addEventListener('click', () => this.openTambahModal());
        this.btnTambahPertama.addEventListener('click', () => this.openTambahModal());
        
        // Refresh button
        this.btnRefresh.addEventListener('click', () => this.loadSubKriteria());
        
        // Lihat kriteria button
        this.btnLihatKriteria.addEventListener('click', () => this.lihatKriteria());
        
        // Modal events
        this.initModalEvents();
    }

    initModalEvents() {
        // Tambah Modal
        this.closeTambah.addEventListener('click', () => this.closeModal(this.modalTambah));
        this.modalOverlayTambah.addEventListener('click', () => this.closeModal(this.modalTambah));
        this.btnBatalTambah.addEventListener('click', () => this.closeModal(this.modalTambah));
        this.btnSimpan.addEventListener('click', () => this.simpanSubKriteria());
        
        // Edit Modal
        this.closeEdit.addEventListener('click', () => this.closeModal(this.modalEdit));
        this.modalOverlayEdit.addEventListener('click', () => this.closeModal(this.modalEdit));
        this.btnBatalEdit.addEventListener('click', () => this.closeModal(this.modalEdit));
        this.btnUpdate.addEventListener('click', () => this.updateSubKriteria());
        
        // Hapus Modal
        this.closeHapus.addEventListener('click', () => this.closeModal(this.modalHapus));
        this.modalOverlayHapus.addEventListener('click', () => this.closeModal(this.modalHapus));
        this.btnBatalHapus.addEventListener('click', () => this.closeModal(this.modalHapus));
        this.btnKonfirmasiHapus.addEventListener('click', () => this.hapusSubKriteria());
        
        // Form validation
        this.initFormValidation();
    }

    initFormValidation() {
        const forms = [this.formTambah, this.formEdit];
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                input.addEventListener('invalid', (e) => {
                    e.preventDefault();
                    this.showValidationError(input);
                });
                
                input.addEventListener('input', () => {
                    if (input.checkValidity()) {
                        this.clearValidationError(input);
                    }
                });
            });
        });
    }

    initSelect2() {
        // Custom select styling
        this.selectKriteria.style.cssText = `
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            appearance: none;
            cursor: pointer;
        `;
    }

    onKriteriaChange(e) {
        const kriteriaId = e.target.value;
        if (kriteriaId) {
            this.currentKriteriaId = kriteriaId;
            this.btnTambahSubKriteria.disabled = false;
            this.loadKriteriaInfo(kriteriaId);
            this.loadSubKriteria();
            this.loadStatistics();
        } else {
            this.resetView();
        }
    }

    async loadKriteriaInfo(kriteriaId) {
        try {
            const response = await fetch(`/api/kriteria/${kriteriaId}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                this.currentKriteria = data.data;
                this.updateKriteriaInfo();
            }
        } catch (error) {
            console.error('Error loading kriteria info:', error);
        }
    }

    updateKriteriaInfo() {
        if (!this.currentKriteria) return;
        
        this.kriteriaInfo.style.display = 'block';
        this.infoNamaKriteria.textContent = this.currentKriteria.nama;
        this.infoJenisKriteria.textContent = this.currentKriteria.jenis.toUpperCase();
        this.infoJenisKriteria.className = `info-badge ${this.currentKriteria.jenis}`;
        
        // Update form dengan kriteria_id
        document.getElementById('kriteria_id').value = this.currentKriteria.id;
    }

    async loadSubKriteria() {
        if (!this.currentKriteriaId) return;
        
        this.showLoading();
        
        try {
            const response = await fetch(API_CONFIG.subKriteriaByKriteria(this.currentKriteriaId));
            const data = await response.json();
            
            if (data.status === 'success') {
                this.renderTable(data.data);
                this.updateStats(data.data);
                this.hideEmptyStates();
                
                // Show/hide no data state
                if (data.data.length === 0) {
                    this.emptyStateNoData.style.display = 'block';
                    this.tableBody.parentElement.parentElement.style.display = 'none';
                } else {
                    this.emptyStateNoData.style.display = 'none';
                    this.tableBody.parentElement.parentElement.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error loading sub kriteria:', error);
            this.showError('Gagal memuat data sub kriteria');
        } finally {
            this.hideLoading();
        }
    }

    async loadStatistics() {
        if (!this.currentKriteriaId) return;
        
        try {
            const response = await fetch(`/api/sub-kriteria/statistics/${this.currentKriteriaId}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                this.updateStatistics(data.data);
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    renderTable(subKriteria) {
        this.tableBody.innerHTML = '';
        
        subKriteria.forEach((item, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${this.escapeHtml(item.nama)}</td>
                <td>
                    <span class="nilai-badge" title="Nilai: ${item.nilai}">
                        ${item.nilai}
                    </span>
                </td>
                <td class="date-cell">
                    ${this.formatDate(item.created_at)}
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="btn-icon btn-edit" data-id="${item.id}" title="Edit">
                            <ion-icon name="create-outline"></ion-icon>
                        </button>
                        <button class="btn-icon btn-delete" data-id="${item.id}" title="Hapus">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                    </div>
                </td>
            `;
            
            // Add event listeners to action buttons
            const editBtn = row.querySelector('.btn-edit');
            const deleteBtn = row.querySelector('.btn-delete');
            
            editBtn.addEventListener('click', () => this.openEditModal(item));
            deleteBtn.addEventListener('click', () => this.openHapusModal(item));
            
            this.tableBody.appendChild(row);
        });
    }

    updateStats(subKriteria) {
        const total = subKriteria.length;
        this.infoJumlahSubKriteria.textContent = total;
        
        // Jika ada data di table, update stats
        if (total > 0) {
            this.updateStatistics({
                total: total,
                max_value: Math.max(...subKriteria.map(s => parseFloat(s.nilai))),
                min_value: Math.min(...subKriteria.map(s => parseFloat(s.nilai)))
            });
        }
    }

    updateStatistics(stats) {
        this.totalSubKriteria.textContent = stats.total;
        this.nilaiTertinggi.textContent = stats.max_value || 0;
        this.nilaiTerendah.textContent = stats.min_value || 0;
    }

    openTambahModal() {
        if (!this.currentKriteriaId) {
            this.showError('Pilih kriteria terlebih dahulu');
            return;
        }
        
        this.formTambah.reset();
        this.clearValidationErrors(this.formTambah);
        this.openModal(this.modalTambah);
    }

    openEditModal(subKriteria) {
        document.getElementById('editId').value = subKriteria.id;
        document.getElementById('editNama').value = subKriteria.nama;
        document.getElementById('editNilai').value = subKriteria.nilai;
        
        this.clearValidationErrors(this.formEdit);
        this.openModal(this.modalEdit);
    }

    openHapusModal(subKriteria) {
        this.hapusNama.textContent = subKriteria.nama;
        this.hapusId = subKriteria.id;
        this.openModal(this.modalHapus);
    }

    async simpanSubKriteria() {
        if (!this.validateForm(this.formTambah)) return;
        
        const formData = new FormData(this.formTambah);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch(API_CONFIG.subKriteriaStore, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                this.closeModal(this.modalTambah);
                this.showSuccess(result.message);
                this.loadSubKriteria();
                this.loadStatistics();
            } else {
                this.showError(result.message || 'Gagal menambah sub kriteria');
            }
        } catch (error) {
            console.error('Error saving sub kriteria:', error);
            this.showError('Terjadi kesalahan saat menyimpan data');
        }
    }

    async updateSubKriteria() {
        if (!this.validateForm(this.formEdit)) return;
        
        const id = document.getElementById('editId').value;
        const formData = new FormData(this.formEdit);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch(API_CONFIG.subKriteriaUpdate(id), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                this.closeModal(this.modalEdit);
                this.showSuccess(result.message);
                this.loadSubKriteria();
                this.loadStatistics();
            } else {
                this.showError(result.message || 'Gagal mengupdate sub kriteria');
            }
        } catch (error) {
            console.error('Error updating sub kriteria:', error);
            this.showError('Terjadi kesalahan saat mengupdate data');
        }
    }

    async hapusSubKriteria() {
        if (!this.hapusId) return;
        
        try {
            const response = await fetch(API_CONFIG.subKriteriaDestroy(this.hapusId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                }
            });
            
            const result = await response.json();
            
            if (result.status === 'success') {
                this.closeModal(this.modalHapus);
                this.showSuccess(result.message);
                this.loadSubKriteria();
                this.loadStatistics();
            } else {
                this.showError(result.message || 'Gagal menghapus sub kriteria');
            }
        } catch (error) {
            console.error('Error deleting sub kriteria:', error);
            this.showError('Terjadi kesalahan saat menghapus data');
        }
    }

    lihatKriteria() {
        if (this.currentKriteriaId) {
            window.location.href = `/dashboard/kriteria/${this.currentKriteriaId}`;
        }
    }

    // Utility Methods
    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                this.showValidationError(input);
                isValid = false;
            }
        });
        
        return isValid;
    }

    showValidationError(input) {
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'flex';
        }
    }

    clearValidationError(input) {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.style.display = 'none';
        }
    }

    clearValidationErrors(form) {
        const inputs = form.querySelectorAll('.is-invalid');
        inputs.forEach(input => this.clearValidationError(input));
    }

    openModal(modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeModal(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    showLoading() {
        this.loadingState.style.display = 'flex';
        this.emptyStateDefault.style.display = 'none';
        this.emptyStateNoData.style.display = 'none';
        this.tableBody.parentElement.parentElement.style.display = 'none';
    }

    hideLoading() {
        this.loadingState.style.display = 'none';
    }

    hideEmptyStates() {
        this.emptyStateDefault.style.display = 'none';
    }

    resetView() {
        this.currentKriteriaId = null;
        this.currentKriteria = null;
        this.btnTambahSubKriteria.disabled = true;
        this.kriteriaInfo.style.display = 'none';
        this.tableBody.innerHTML = '';
        this.emptyStateDefault.style.display = 'block';
        this.emptyStateNoData.style.display = 'none';
        this.tableBody.parentElement.parentElement.style.display = 'none';
        
        // Reset stats
        this.totalSubKriteria.textContent = '0';
        this.nilaiTertinggi.textContent = '0';
        this.nilaiTerendah.textContent = '0';
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'alert-circle'}-outline"></ion-icon>
            <span>${message}</span>
        `;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
        
        // Add notification styles if not exists
        if (!document.getElementById('notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                .notification {
                    position: fixed;
                    top: 24px;
                    right: 24px;
                    padding: 16px 24px;
                    border-radius: 12px;
                    color: white;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    z-index: 9999;
                    animation: slideInRight 0.3s ease;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
                }
                
                .notification-success {
                    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
                }
                
                .notification-error {
                    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
                }
                
                .notification-info {
                    background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
                }
                
                .fade-out {
                    animation: fadeOut 0.3s ease forwards;
                }
                
                @keyframes slideInRight {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                
                @keyframes fadeOut {
                    to {
                        opacity: 0;
                        transform: translateY(-20px);
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.subKriteriaManager = new SubKriteriaManager();
});
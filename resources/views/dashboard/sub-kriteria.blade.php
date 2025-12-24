@extends('layouts.app')

@section('title', 'Manajemen Sub Kriteria')
    
@section('content')
<div class="subkriteria-page">
    <!-- Header -->
    <header class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                </svg>
            </div>
            <div>
                <h1>Manajemen Sub Kriteria</h1>
                <p class="page-subtitle">Kelola sub kriteria untuk setiap kriteria penilaian</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnRefreshAll">
                <svg class="icon" viewBox="0 0 24 24">
                    <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                </svg>
                Refresh Data
            </button>
        </div>
    </header>

    <!-- Alert Message -->
    <div class="alert d-none" id="messageAlert"></div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/>
                        <path d="M7 12h2v5H7zm4-7h2v12h-2zm4 3h2v9h-2z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 id="totalKriteria">0</h3>
                    <p>Total Kriteria</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 id="totalSubKriteria">0</h3>
                    <p>Total Sub Kriteria</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="icon" viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 id="rataNilai">0</h3>
                    <p>Rata-rata Nilai</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Kriteria Cards Section -->
    <div class="loading-state" id="mainLoading">
        <div class="spinner"></div>
        <p>Memuat data kriteria...</p>
    </div>

    <div class="kriteria-cards" id="kriteriaCards"></div>
</div>

<!-- Include Modals Partial -->
@include('partials.subkriteria-modals')

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/dashboard/sub-kriteria.css') }}">

<!-- JavaScript -->
<script>
    class SubKriteriaManager {
    constructor() {
        console.log('SubKriteriaManager initialized');
        this.kriteriaData = [];
        this.subKriteriaData = [];
        this.kriteriaToDelete = null;
        
        this.API_CONFIG = {
            baseUrl: window.location.origin,
            kriteria: '/api/kriteria',
            subKriteria: '/api/sub-kriteria',
            subKriteriaByKriteria: (id) => `/api/kriteria/${id}/sub`
        };
        
        this.initElements();
        this.bindEvents();
        this.loadData();
    }

    initElements() {
        // Main elements
        this.kriteriaCards = document.getElementById('kriteriaCards');
        this.mainLoading = document.getElementById('mainLoading');
        this.messageAlert = document.getElementById('messageAlert');
        this.btnRefreshAll = document.getElementById('btnRefreshAll');
        
        // Stats elements
        this.totalKriteriaEl = document.getElementById('totalKriteria');
        this.totalSubKriteriaEl = document.getElementById('totalSubKriteria');
        this.rataNilaiEl = document.getElementById('rataNilai');
        
        // Form elements
        this.formTambah = document.getElementById('formTambahSubKriteria');
        this.formEdit = document.getElementById('formEditSubKriteria');
        
        // Modal elements
        this.modalTambah = document.getElementById('modalTambahSubKriteria');
        this.modalEdit = document.getElementById('modalEditSubKriteria');
        this.modalHapus = document.getElementById('modalHapusSubKriteria');
    }

    bindEvents() {
        // Refresh button
        this.btnRefreshAll?.addEventListener('click', () => this.loadData());
        
        // Modal close events
        document.getElementById('closeTambahSub')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('modalOverlayTambah')?.addEventListener('click', () => this.closeTambahModal());
        document.getElementById('btnBatalTambahSub')?.addEventListener('click', () => this.closeTambahModal());
        
        document.getElementById('closeEditSub')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('modalOverlayEdit')?.addEventListener('click', () => this.closeEditModal());
        document.getElementById('btnBatalEditSub')?.addEventListener('click', () => this.closeEditModal());
        
        document.getElementById('closeHapusSub')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('modalOverlayHapus')?.addEventListener('click', () => this.closeHapusModal());
        document.getElementById('btnBatalHapusSub')?.addEventListener('click', () => this.closeHapusModal());
        
        // Form submit events
        this.formTambah?.addEventListener('submit', (e) => this.saveSubKriteria(e));
        this.formEdit?.addEventListener('submit', (e) => this.updateSubKriteria(e));
        
        // Delete confirmation
        document.getElementById('btnKonfirmasiHapusSub')?.addEventListener('click', () => this.confirmDelete());
        
        // ESC key untuk close modal
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }

    async loadData() {
        try {
            this.showLoading();
            
            // Load kriteria data
            const kriteriaResponse = await fetch(this.API_CONFIG.kriteria);
            if (!kriteriaResponse.ok) throw new Error('Gagal memuat data kriteria');
            const kriteriaResult = await kriteriaResponse.json();
            this.kriteriaData = kriteriaResult.data || kriteriaResult;
            
            // Load subkriteria untuk setiap kriteria
            this.subKriteriaData = [];
            for (const kriteria of this.kriteriaData) {
                try {
                    const subResponse = await fetch(this.API_CONFIG.subKriteriaByKriteria(kriteria.id));
                    if (subResponse.ok) {
                        const subResult = await subResponse.json();
                        if (subResult.data) {
                            this.subKriteriaData = [...this.subKriteriaData, ...subResult.data.map(item => ({
                                ...item,
                                kriteria_id: kriteria.id,
                                kriteria_nama: kriteria.nama
                            }))];
                        }
                    }
                } catch (error) {
                    console.error(`Error loading sub kriteria for kriteria ${kriteria.id}:`, error);
                }
            }
            
            this.renderKriteriaCards();
            this.updateStats();
            
        } catch (error) {
            console.error('Error loading data:', error);
            this.showMessage('Gagal memuat data', 'error');
        } finally {
            this.hideLoading();
        }
    }

    renderKriteriaCards() {
        this.kriteriaCards.innerHTML = '';
        
        if (this.kriteriaData.length === 0) {
            this.kriteriaCards.innerHTML = `
                <div class="empty-state" style="grid-column: 1/-1;">
                    <div class="empty-icon">
                        <svg class="icon" viewBox="0 0 24 24" style="width: 64px; height: 64px;">
                            <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/>
                        </svg>
                    </div>
                    <h3>Belum Ada Data Kriteria</h3>
                    <p>Tambahkan kriteria terlebih dahulu untuk mengelola sub kriteria</p>
                </div>
            `;
            return;
        }
        
        this.kriteriaData.forEach(kriteria => {
            const subKriteria = this.subKriteriaData.filter(item => item.kriteria_id == kriteria.id);
            
            const card = document.createElement('div');
            card.className = 'kriteria-card';
            card.innerHTML = `
                <div class="kriteria-header">
                    <div>
                        <div class="kriteria-title">
                            ${kriteria.nama}
                            <span class="kriteria-code">${kriteria.kode || 'K-' + kriteria.id}</span>
                        </div>
                    </div>
                    <button class="add-sub-btn" data-kriteria-id="${kriteria.id}" data-kriteria-nama="${kriteria.nama}">
                        <svg class="icon" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                    </button>
                </div>
                <div class="kriteria-meta">
                    <div class="kriteria-info">
                        ${subKriteria.length} Sub Kriteria • Rata-rata: ${this.calculateAverage(subKriteria).toFixed(1)}
                    </div>
                    <button class="refresh-kriteria" data-kriteria-id="${kriteria.id}">
                        <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
                        </svg>
                        Refresh
                    </button>
                </div>
                <div class="subkriteria-table-container">
                    ${subKriteria.length > 0 ? `
                        <table class="subkriteria-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sub Kriteria</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${subKriteria.map((item, index) => `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.nama}</td>
                                        <td>
                                            <span class="nilai-badge">${item.nilai}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn btn-outline btn-sm btn-edit-sub" 
                                                        data-id="${item.id}"
                                                        data-nama="${item.nama}"
                                                        data-nilai="${item.nilai}">
                                                    <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                    </svg>
                                                </button>
                                                <button class="btn btn-danger btn-sm btn-delete-sub" 
                                                        data-id="${item.id}" 
                                                        data-nama="${item.nama}">
                                                    <svg class="icon" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    ` : `
                        <div class="empty-state">
                            <svg class="icon" viewBox="0 0 24 24" style="width: 48px; height: 48px;">
                                <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/>
                            </svg>
                            <h4>Belum Ada Sub Kriteria</h4>
                            <p>Klik tombol + untuk menambahkan sub kriteria pertama</p>
                        </div>
                    `}
                </div>
            `;
            
            this.kriteriaCards.appendChild(card);
        });
        
        this.attachCardEvents();
    }

    attachCardEvents() {
        // Add button
        document.querySelectorAll('.add-sub-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const kriteriaId = e.currentTarget.dataset.kriteriaId;
                const kriteriaNama = e.currentTarget.dataset.kriteriaNama;
                this.openTambahModal(kriteriaId, kriteriaNama);
            });
        });

        // Refresh button
        document.querySelectorAll('.refresh-kriteria').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const kriteriaId = e.currentTarget.dataset.kriteriaId;
                await this.refreshKriteriaData(kriteriaId);
            });
        });

        // Edit button
        document.querySelectorAll('.btn-edit-sub').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const nama = e.currentTarget.dataset.nama;
                const nilai = e.currentTarget.dataset.nilai;
                this.openEditModal(id, nama, nilai);
            });
        });

        // Delete button
        document.querySelectorAll('.btn-delete-sub').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.dataset.id;
                const nama = e.currentTarget.dataset.nama;
                this.openHapusModal(id, nama);
            });
        });
    }

    calculateAverage(subKriteria) {
        if (subKriteria.length === 0) return 0;
        const total = subKriteria.reduce((sum, item) => sum + parseFloat(item.nilai), 0);
        return total / subKriteria.length;
    }

    updateStats() {
        this.totalKriteriaEl.textContent = this.kriteriaData.length;
        this.totalSubKriteriaEl.textContent = this.subKriteriaData.length;
        
        if (this.subKriteriaData.length > 0) {
            const totalNilai = this.subKriteriaData.reduce((sum, item) => sum + parseFloat(item.nilai), 0);
            this.rataNilaiEl.textContent = (totalNilai / this.subKriteriaData.length).toFixed(1);
        } else {
            this.rataNilaiEl.textContent = '0';
        }
    }

    // Modal Methods
    openTambahModal(kriteriaId, kriteriaNama) {
        document.getElementById('tambahKriteriaId').value = kriteriaId;
        document.querySelector('#modalTambahSubKriteria .modal-title').innerHTML = `
            <ion-icon name="add-circle-outline"></ion-icon>
            Tambah Sub Kriteria - ${kriteriaNama}
        `;
        
        this.formTambah?.reset();
        this.modalTambah.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeTambahModal() {
        this.modalTambah.classList.remove('active');
        document.body.style.overflow = '';
        this.formTambah?.reset();
    }

    openEditModal(id, nama, nilai) {
        document.getElementById('editSubId').value = id;
        document.getElementById('editSubNama').value = nama;
        document.getElementById('editSubNilai').value = nilai;
        
        this.modalEdit.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeEditModal() {
        this.modalEdit.classList.remove('active');
        document.body.style.overflow = '';
        this.formEdit?.reset();
    }

    openHapusModal(id, nama) {
        this.kriteriaToDelete = { id, nama };
        document.getElementById('hapusSubNama').textContent = nama;
        this.modalHapus.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeHapusModal() {
        this.modalHapus.classList.remove('active');
        document.body.style.overflow = '';
        this.kriteriaToDelete = null;
    }

    closeAllModals() {
        this.closeTambahModal();
        this.closeEditModal();
        this.closeHapusModal();
    }

    // API Methods
    async saveSubKriteria(e) {
        e.preventDefault();
        
        const formData = {
            kriteria_id: document.getElementById('tambahKriteriaId').value,
            nama: document.getElementById('tambahNama').value.trim(),
            nilai: parseFloat(document.getElementById('tambahNilai').value)
        };
        
        // Validation
        if (!formData.nama || !formData.nilai) {
            this.showMessage('Harap isi semua field yang wajib', 'error');
            return;
        }
        
        try {
            const response = await fetch(this.API_CONFIG.subKriteria, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showMessage('Sub Kriteria berhasil ditambahkan', 'success');
                this.closeTambahModal();
                await this.refreshKriteriaData(formData.kriteria_id);
            } else {
                throw new Error(data.message || 'Gagal menambahkan data');
            }
        } catch (error) {
            console.error('Error saving sub kriteria:', error);
            this.showMessage(error.message, 'error');
        }
    }

    async updateSubKriteria(e) {
        e.preventDefault();
        
        const formData = {
            id: document.getElementById('editSubId').value,
            nama: document.getElementById('editSubNama').value.trim(),
            nilai: parseFloat(document.getElementById('editSubNilai').value)
        };
        
        try {
            const response = await fetch(`${this.API_CONFIG.subKriteria}/${formData.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    nama: formData.nama,
                    nilai: formData.nilai
                })
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showMessage('Sub Kriteria berhasil diperbarui', 'success');
                this.closeEditModal();
                await this.loadData();
            } else {
                throw new Error(data.message || 'Gagal mengupdate data');
            }
        } catch (error) {
            console.error('Error updating sub kriteria:', error);
            this.showMessage(error.message, 'error');
        }
    }

    async confirmDelete() {
        if (!this.kriteriaToDelete) return;
        
        try {
            const response = await fetch(`${this.API_CONFIG.subKriteria}/${this.kriteriaToDelete.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                this.showMessage('Sub Kriteria berhasil dihapus', 'success');
                this.closeHapusModal();
                await this.loadData();
            } else {
                throw new Error(data.message || 'Gagal menghapus data');
            }
        } catch (error) {
            console.error('Error deleting sub kriteria:', error);
            this.showMessage(error.message, 'error');
        }
    }

    async refreshKriteriaData(kriteriaId) {
        try {
            const response = await fetch(this.API_CONFIG.subKriteriaByKriteria(kriteriaId));
            if (!response.ok) throw new Error('Gagal refresh data');
            
            const result = await response.json();
            
            // Update data lokal
            this.subKriteriaData = this.subKriteriaData.filter(item => item.kriteria_id != kriteriaId);
            
            if (result.data) {
                const kriteria = this.kriteriaData.find(k => k.id == kriteriaId);
                result.data.forEach(item => {
                    this.subKriteriaData.push({
                        ...item,
                        kriteria_id: kriteriaId,
                        kriteria_nama: kriteria.nama
                    });
                });
            }
            
            this.renderKriteriaCards();
            this.updateStats();
            this.showMessage('Data berhasil diperbarui', 'success');
            
        } catch (error) {
            console.error('Error refreshing data:', error);
            this.showMessage('Gagal memperbarui data', 'error');
        }
    }

    // Utility Methods
    showMessage(message, type = 'success') {
        this.messageAlert.innerHTML = `
            <div class="alert-icon">
                <svg class="icon" viewBox="0 0 24 24">
                    ${type === 'success' ? 
                        '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>' :
                        '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>'}
                </svg>
            </div>
            <div class="alert-content">
                ${message}
            </div>
        `;
        
        this.messageAlert.className = `alert alert-${type}`;
        this.messageAlert.classList.remove('d-none');
        
        setTimeout(() => {
            this.messageAlert.classList.add('d-none');
        }, 5000);
    }

    showLoading() {
        this.mainLoading.classList.remove('d-none');
        this.kriteriaCards.classList.add('d-none');
    }

    hideLoading() {
        this.mainLoading.classList.add('d-none');
        this.kriteriaCards.classList.remove('d-none');
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.subKriteriaManager = new SubKriteriaManager();
    
    // Auto refresh setiap 30 detik
    setInterval(() => {
        window.subKriteriaManager?.loadData();
    }, 30000);
});
</script>
@endsection
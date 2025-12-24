@extends('layouts.app')

@section('title', 'Manajemen Bobot Kriteria')

@section('content')
    <link rel="stylesheet" href="css/dashboard/bobot.css">
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="scale-outline"></ion-icon>
            </div>
            <div>
                <h1>Manajemen Bobot Kriteria</h1>
                <p class="page-subtitle">Kelola bobot untuk setiap kriteria penilaian</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnTambahBobot">
                <ion-icon name="add-outline"></ion-icon> Tambah Bobot
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <ion-icon name="layers-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalBobot">0</h3>
                    <p>Total Bobot</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalBobotValue">0</h3>
                    <p>Total Nilai Bobot</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                    <ion-icon name="analytics-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="averageBobot">0</h3>
                    <p>Rata-rata Bobot</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Bobot -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="list-outline"></ion-icon> Daftar Bobot Kriteria</h2>
            <div class="section-actions">
                <button class="btn btn-outline" id="btnRefreshBobot">
                    <ion-icon name="refresh-outline"></ion-icon> Refresh
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="candidates-table" id="bobotTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th>Kriteria</th>
                        <th style="width: 150px;">Bobot</th>
                        <th style="width: 120px;">Persentase</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bobotTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <div class="spinner"></div>
            <p>Memuat data bobot...</p>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState">
            <div class="empty-icon">
                <ion-icon name="scale-outline"></ion-icon>
            </div>
            <h3>Belum Ada Data Bobot</h3>
            <p>Tambahkan bobot untuk setiap kriteria penilaian</p>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH BOBOT -->
<div class="custom-modal" id="modalTambahBobot">
    <div class="modal-overlay" id="modalOverlayTambahBobot"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Tambah Bobot Baru
                </h5>
                <button type="button" class="modal-close" id="closeTambahBobot">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahBobot">
                    @csrf
                    <div class="form-group">
                        <label for="kriteria_id" class="form-label">Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="kriteria_id" name="kriteria_id" required>
                            <option value="">Pilih Kriteria</option>
                            <!-- Options akan diisi oleh JavaScript -->
                        </select>
                        <div class="invalid-feedback">Harap pilih kriteria</div>
                    </div>
                    <div class="form-group">
                        <label for="bobot" class="form-label">Nilai Bobot <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="bobot" name="bobot" 
                               min="0" step="0.01" placeholder="Contoh: 0.25" required>
                        <small class="text-muted">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            Nilai bobot antara 0-1 (misal: 0.25 untuk 25%)
                        </small>
                        <div class="invalid-feedback">Nilai bobot harus diisi</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalTambahBobot">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnSimpanBobot">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT BOBOT -->
<div class="custom-modal" id="modalEditBobot">
    <div class="modal-overlay" id="modalOverlayEditBobot"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="create-outline"></ion-icon>
                    Edit Bobot
                </h5>
                <button type="button" class="modal-close" id="closeEditBobot">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditBobot">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editBobotId" name="id">
                    <div class="form-group">
                        <label class="form-label">Kriteria</label>
                        <input type="text" class="form-control" id="editKriteriaNama" readonly>
                        <small class="text-muted">Kriteria tidak dapat diubah</small>
                    </div>
                    <div class="form-group">
                        <label for="editBobot" class="form-label">Nilai Bobot *</label>
                        <input type="number" class="form-control" id="editBobot" name="bobot" 
                               min="0" step="0.01" required>
                        <div class="invalid-feedback">Nilai bobot harus diisi</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalEditBobot">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnUpdateBobot">
                    <ion-icon name="save-outline"></ion-icon> Update
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HAPUS BOBOT -->
<div class="custom-modal" id="modalHapusBobot">
    <div class="modal-overlay" id="modalOverlayHapusBobot"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="warning-outline"></ion-icon>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="modal-close" id="closeHapusBobot">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus bobot ini?</p>
                <p><strong id="hapusBobotInfo"></strong></p>
                <div class="alert alert-warning">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalHapusBobot">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapusBobot">
                    <ion-icon name="trash-outline"></ion-icon> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
   /* Additional styles for Kriteria */
.jenis-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    letter-spacing: 0.3px;
    text-transform: uppercase;
    font-size: 0.75rem;
}

.jenis-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.jenis-benefit {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
}

.jenis-benefit:hover {
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
}

.jenis-cost {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.jenis-cost:hover {
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
}

.subkriteria-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.875rem;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.2);
    transition: all 0.3s ease;
}

.subkriteria-count:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

/* Enhanced Table Styles */
.table-container {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #F3F4F6;
}

.candidates-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.candidates-table thead {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
}

.candidates-table th {
    padding: 20px 24px;
    font-weight: 600;
    color: #475569;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #E2E8F0;
    white-space: nowrap;
}

.candidates-table td {
    padding: 20px 24px;
    color: #1E293B;
    border-bottom: 1px solid #F1F5F9;
    font-size: 0.95rem;
    transition: background-color 0.2s ease;
}

.candidates-table tbody tr {
    transition: all 0.3s ease;
}

.candidates-table tbody tr:hover {
    background-color: #F8FAFC;
    transform: translateX(2px);
}

.candidates-table tbody tr:last-child td {
    border-bottom: none;
}

/* Enhanced Modal Styles */
.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.custom-modal.active {
    display: flex;
    opacity: 1;
    visibility: visible;
    animation: modalFadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes modalFadeIn {
    0% {
        opacity: 0;
        transform: scale(0.95);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    z-index: 1;
    opacity: 0;
    animation: overlayFadeIn 0.3s ease forwards;
}

@keyframes overlayFadeIn {
    to {
        opacity: 1;
    }
}

.modal-dialog {
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 500px;
    animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1) 0.1s both;
}

@keyframes slideUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-content {
    background: white;
    border-radius: 20px;
    box-shadow: 0 32px 64px rgba(15, 23, 42, 0.25);
    overflow: hidden;
    border: 1px solid #E2E8F0;
}

.modal-header {
    padding: 24px 32px;
    border-bottom: 1px solid #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
}

.modal-title {
    margin: 0;
    font-size: 1.375rem;
    font-weight: 700;
    color: #1E293B;
    display: flex;
    align-items: center;
    gap: 12px;
}

.modal-title ion-icon {
    font-size: 1.5rem;
    color: #4361ee;
}

.modal-close {
    background: rgba(255, 255, 255, 0.8);
    border: 1px solid #E2E8F0;
    font-size: 1.5rem;
    color: #64748B;
    cursor: pointer;
    padding: 8px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    width: 40px;
    height: 40px;
}

.modal-close:hover {
    background: white;
    border-color: #4361ee;
    color: #4361ee;
    transform: rotate(90deg);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.1);
}

.modal-body {
    padding: 32px;
    max-height: 70vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #CBD5E1 #F1F5F9;
}

.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #F1F5F9;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #CBD5E1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #94A3B8;
}

.modal-footer {
    padding: 24px 32px;
    border-top: 1px solid #F1F5F9;
    display: flex;
    justify-content: flex-end;
    gap: 16px;
    background: #F8FAFC;
}

/* Enhanced Form Styles */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #334155;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 6px;
}

.form-label .text-danger {
    margin-left: -6px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    color: #1E293B;
    font-family: inherit;
}

.form-control:hover {
    border-color: #CBD5E1;
}

.form-control:focus {
    outline: none;
    border-color: #4361ee;
    box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
    transform: translateY(-1px);
}

.form-control.is-invalid {
    border-color: #EF4444;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23EF4444' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23EF4444' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    padding-right: calc(1.5em + 0.75rem);
}

.form-control.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
}

.invalid-feedback {
    display: none;
    margin-top: 8px;
    color: #EF4444;
    font-size: 0.875rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}

.invalid-feedback ion-icon {
    font-size: 1rem;
}

.form-control.is-invalid ~ .invalid-feedback {
    display: flex;
}

.text-muted {
    color: #64748B;
    font-size: 0.875rem;
    margin-top: 10px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
    line-height: 1.5;
}

.text-muted ion-icon {
    margin-top: 2px;
    color: #94A3B8;
}

/* Enhanced Button Styles */
.btn {
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    min-width: 120px;
    position: relative;
    overflow: hidden;
}

.btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

.btn:focus:not(:active)::after {
    animation: ripple 1s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0, 0);
        opacity: 0.5;
    }
    100% {
        transform: scale(20, 20);
        opacity: 0;
    }
}

.btn-primary {
    background: linear-gradient(135deg, #4361ee 0%, #3A56D4 100%);
    color: white;
    box-shadow: 0 4px 16px rgba(67, 97, 238, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3A56D4 0%, #2E4BC4 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(67, 97, 238, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(67, 97, 238, 0.3);
}

.btn-secondary {
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 16px rgba(100, 116, 139, 0.3);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #475569 0%, #334155 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(100, 116, 139, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
}

.btn-outline-secondary {
    background: white;
    border: 2px solid #E2E8F0;
    color: #475569;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.btn-outline-secondary:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

/* Enhanced Alert Styles */
.alert {
    padding: 18px 20px;
    border-radius: 12px;
    margin: 20px 0;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    border-left: 4px solid;
}

.alert ion-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.alert-warning {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    border: 1px solid #FBBF24;
    border-left-color: #F59E0B;
    color: #92400E;
}

.alert-warning strong {
    color: #B45309;
}

.alert .alert-content {
    flex: 1;
}

.alert .alert-title {
    font-weight: 700;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Enhanced Stats Section */
.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.stat-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
    border: 1px solid #F1F5F9;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--color-start), var(--color-end));
}

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1E293B;
    margin: 0 0 8px 0;
    line-height: 1;
    background: linear-gradient(135deg, #1E293B, #475569);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-content p {
    color: #64748B;
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    letter-spacing: 0.5px;
}

/* Loading State Enhancement */
.loading-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    text-align: center;
}

.spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #F1F5F9;
    border-top-color: #4361ee;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 24px;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.loading-state p {
    color: #64748B;
    font-size: 1.1rem;
    font-weight: 500;
    margin: 0;
}

/* Empty State Enhancement */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    font-size: 5rem;
    color: #CBD5E1;
    margin-bottom: 24px;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

.empty-state h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1E293B;
    margin: 0 0 12px 0;
}

.empty-state p {
    color: #64748B;
    font-size: 1.1rem;
    max-width: 400px;
    margin: 0 auto 32px;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-dialog {
        max-width: 95%;
        margin: 10px;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .candidates-table {
        display: block;
        overflow-x: auto;
    }
    
    .candidates-table th,
    .candidates-table td {
        padding: 16px 12px;
        white-space: nowrap;
    }
    
    .btn {
        padding: 12px 20px;
        min-width: 100px;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .custom-modal .modal-content,
    .table-container,
    .stat-card {
        background: #1E293B;
        border-color: #334155;
    }
    
    .candidates-table th,
    .candidates-table td {
        color: #E2E8F0;
        border-color: #334155;
    }
    
    .candidates-table thead {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    }
    
    .form-control {
        background: #334155;
        border-color: #475569;
        color: #F1F5F9;
    }
    
    .form-control:focus {
        border-color: #4361ee;
        background: #334155;
    }
    
    .text-muted {
        color: #94A3B8;
    }
}

/* Print Styles */
@media print {
    .custom-modal,
    .modal-overlay,
    .btn {
        display: none !important;
    }
    
    .table-container {
        box-shadow: none;
        border: 1px solid #000;
    }
    
    .candidates-table {
        border-collapse: collapse;
    }
    
    .candidates-table th {
        background: #f0f0f0 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<!-- JavaScript Configuration -->



<script src="{{ asset('js/dashboard/bobot.js') }}"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

@endsection
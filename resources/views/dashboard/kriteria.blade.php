@extends('layouts.app')

@section('title', 'Manajemen Kriteria')

@section('content')
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="stats-chart-outline"></ion-icon>
            </div>
            <div>
                <h1>Manajemen Kriteria Penilaian</h1>
                <p class="page-subtitle">Kelola kriteria untuk proses penilaian kandidat</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnTambahKriteria">
                <ion-icon name="add-outline"></ion-icon> Tambah Kriteria
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
                    <h3 id="totalKriteria">0</h3>
                    <p>Total Kriteria</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                    <ion-icon name="trending-up-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalBenefit">0</h3>
                    <p>Kriteria Benefit</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                    <ion-icon name="trending-down-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalCost">0</h3>
                    <p>Kriteria Cost</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Kriteria -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="list-outline"></ion-icon> Daftar Kriteria</h2>
            <div class="section-actions">
                <button class="btn btn-outline-secondary" id="btnRefresh">
                    <ion-icon name="refresh-outline"></ion-icon> Refresh
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="candidates-table" id="kriteriaTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th>Nama Kriteria</th>
                        <th style="width: 120px;">Jenis</th>
                        <th style="width: 120px;">Jumlah Subkriteria</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="kriteriaTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <div class="spinner"></div>
            <p>Memuat data kriteria...</p>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState">
            <div class="empty-icon">
                <ion-icon name="stats-chart-outline"></ion-icon>
            </div>
            <h3>Belum Ada Data Kriteria</h3>
            <p>Tambahkan kriteria baru untuk memulai konfigurasi penilaian</p>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH KRITERIA -->
<div class="custom-modal" id="modalTambahKriteria">
    <div class="modal-overlay" id="modalOverlayTambah"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Tambah Kriteria Baru
                </h5>
                <button type="button" class="modal-close" id="closeTambah">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTambahKriteria">
                    @csrf
                    <div class="form-group">
                        <label for="nama" class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               placeholder="Contoh: Pengalaman Kerja, Komunikasi, dll" required>
                        <div class="invalid-feedback">Nama kriteria harus diisi</div>
                    </div>
                    <div class="form-group">
                        <label for="jenis" class="form-label">Jenis Kriteria <span class="text-danger">*</span></label>
                        <select class="form-control" id="jenis" name="jenis" required>
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
                <button type="button" class="btn btn-secondary" id="btnBatalTambah">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnSimpanKriteria">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL EDIT KRITERIA -->
<div class="custom-modal" id="modalEditKriteria">
    <div class="modal-overlay" id="modalOverlayEdit"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="create-outline"></ion-icon>
                    Edit Kriteria
                </h5>
                <button type="button" class="modal-close" id="closeEdit">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditKriteria">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editNama" class="form-label">Nama Kriteria *</label>
                        <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="editJenis" class="form-label">Jenis Kriteria *</label>
                        <select class="form-control" id="editJenis" name="jenis" required>
                            <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                            <option value="cost">Cost (Semakin kecil semakin baik)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalEdit">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnUpdateKriteria">
                    <ion-icon name="save-outline"></ion-icon> Update
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HAPUS KRITERIA -->
<div class="custom-modal" id="modalHapusKriteria">
    <div class="modal-overlay" id="modalOverlayHapus"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="warning-outline"></ion-icon>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="modal-close" id="closeHapus">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kriteria ini?</p>
                <p><strong id="hapusNama"></strong></p>
                <div class="alert alert-warning">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <strong>Peringatan:</strong> Menghapus kriteria akan menghapus semua subkriteria dan bobot yang terkait!
                </div>
                <p class="text-danger">Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnBatalHapus">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">
                    <ion-icon name="trash-outline"></ion-icon> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/dashboard/data_kandidat.css') }}">
<style>
    /* Additional styles for Kriteria */
    .jenis-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .jenis-benefit {
        background-color: rgba(76, 175, 80, 0.1);
        color: #2E7D32;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }
    
    .jenis-cost {
        background-color: rgba(244, 67, 54, 0.1);
        color: #c62828;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }
    
    .subkriteria-count {
        font-weight: 600;
        color: #4361ee;
    }

    /* CUSTOM MODAL STYLES */
    .custom-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .custom-modal.active {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        z-index: 1;
    }

    .modal-dialog {
        position: relative;
        z-index: 2;
        width: 90%;
        max-width: 500px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .modal-title {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6b7280;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .modal-body {
        padding: 24px;
        max-height: 60vh;
        overflow-y: auto;
    }

    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        display: none;
        margin-top: 6px;
        color: #ef4444;
        font-size: 0.875rem;
    }

    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
    }

    /* Button Styles */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: #4361ee;
        color: white;
    }

    .btn-primary:hover {
        background: #3a56d4;
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
    }

    .btn-outline-secondary {
        background: transparent;
        border: 1px solid #d1d5db;
        color: #374151;
    }

    .btn-outline-secondary:hover {
        background: #f3f4f6;
    }

    /* Alert Styles */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin: 16px 0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #92400e;
    }

    .text-muted {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .text-danger {
        color: #ef4444;
        font-weight: 500;
    }
</style>

<!-- JavaScript -->
<script>
    const API_CONFIG = {
        baseUrl: '{{ url("/") }}',
        // Route::apiResource() membuat endpoint di /api/kriteria
        kriteriaIndex: '/api/kriteria',
        kriteriaStore: '/api/kriteria',
        kriteriaUpdate: (id) => `/api/kriteria/${id}`,
        kriteriaDestroy: (id) => `/api/kriteria/${id}`,
        csrfToken: '{{ csrf_token() }}'
    };
    
    console.log('API Routes Loaded:', API_CONFIG);
</script>

<script src="{{ asset('js/dashboard/kriteria.js') }}"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

@endsection
@extends('layouts.app')

@section('title', 'Manajemen Penilaian')

@section('content')
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="clipboard-outline"></ion-icon>
            </div>
            <div>
                <h1>Penilaian Kandidat</h1>
                <p class="page-subtitle">Kelola penilaian kandidat berdasarkan kriteria yang ditentukan</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-green" onclick="openModal()">
                <ion-icon name="add-outline"></ion-icon> Tambah Penilaian
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="filter-outline"></ion-icon> Filter Periode</h2>
            <div class="section-actions">
                <button class="btn btn-outline-secondary" onclick="clearFilter()">
                    <ion-icon name="refresh-outline"></ion-icon> Reset Filter
                </button>
            </div>
        </div>
        <div class="filter-container">
            <div class="form-group">
                <label for="filterPeriode" class="form-label">Pilih Periode</label>
                <input type="month" id="filterPeriode" onchange="filterByPeriode()" 
                       class="form-control" style="max-width: 300px;">
            </div>
        </div>
    </div>

    <!-- Tabel Data Penilaian -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="list-outline"></ion-icon> Daftar Penilaian</h2>
            <div class="section-actions">
                <button class="btn btn-outline-secondary" onclick="loadPenilaians()">
                    <ion-icon name="refresh-outline"></ion-icon> Refresh
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="candidates-table" id="penilaianTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th>Kandidat</th>
                        <th style="width: 150px;">Periode</th>
                        <th style="width: 120px;">Jumlah Kriteria</th>
                        <th style="width: 120px;">Total Skor</th>
                        <th style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="penilaianTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <div class="spinner"></div>
            <p>Memuat data penilaian...</p>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState">
            <div class="empty-icon">
                <ion-icon name="clipboard-outline"></ion-icon>
            </div>
            <h3>Belum Ada Data Penilaian</h3>
            <p>Tambahkan penilaian baru untuk memulai evaluasi kandidat</p>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH PENILAIAN -->
<div class="custom-modal" id="modalTambah">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-dialog" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="add-circle-outline"></ion-icon>
                    Tambah Penilaian Baru
                </h5>
                <button type="button" class="modal-close" onclick="closeModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <form id="formPenilaian" onsubmit="submitPenilaian(event)">
                    @csrf
                    <div class="form-group">
                        <label for="kandidat_id" class="form-label">Kandidat <span class="text-danger">*</span></label>
                        <select class="form-control" id="kandidat_id" required>
                            <option value="">Pilih Kandidat</option>
                        </select>
                    </div>
                    
                    <div id="kriteriaContainer" class="kriteria-container">
                        <!-- Kriteria akan diisi oleh JavaScript -->
                    </div>
                    
                    <div class="alert alert-info">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        <strong>Informasi:</strong> Pilih sub kriteria untuk setiap kriteria yang tersedia
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="submit" form="formPenilaian" class="btn btn-primary">
                    <ion-icon name="save-outline"></ion-icon> Simpan Penilaian
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL PENILAIAN -->
<div class="custom-modal" id="modalDetail">
    <div class="modal-overlay" onclick="closeDetailModal()"></div>
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="eye-outline"></ion-icon>
                    Detail Penilaian
                </h5>
                <button type="button" class="modal-close" onclick="closeDetailModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <div id="detailContent">
                    <!-- Detail akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">
                    <ion-icon name="close-outline"></ion-icon> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL KONFIRMASI HITUNG SMART -->
<div class="custom-modal" id="modalKonfirmasiHitung">
    <div class="modal-overlay" onclick="closeKonfirmasiHitungModal()"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="calculator-outline"></ion-icon>
                    Konfirmasi Perhitungan SMART
                </h5>
                <button type="button" class="modal-close" onclick="closeKonfirmasiHitungModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <ion-icon name="warning-outline"></ion-icon>
                    <strong>Perhatian!</strong> Proses ini akan menghitung nilai SMART berdasarkan data penilaian yang ada.
                </div>
                <p>Apakah Anda yakin ingin menghitung nilai SMART untuk penilaian ini?</p>
                <p><strong id="hitungInfo"></strong></p>
                <div class="alert alert-info">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    <strong>Informasi:</strong> 
                    <ul style="margin: 5px 0 0 15px; padding-left: 5px;">
                        <li>Perhitungan menggunakan metode Simple Multi Attribute Rating Technique (SMART)</li>
                        <li>Hasil perhitungan akan disimpan dan dapat dilihat di modal hasil</li>
                        <li>Proses ini tidak dapat dibatalkan setelah dimulai</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeKonfirmasiHitungModal()">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiHitung">
                    <ion-icon name="calculator-outline"></ion-icon> Hitung SMART
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HASIL SMART -->
<div class="custom-modal" id="modalSmart">
    <div class="modal-overlay" onclick="closeSmartModal()"></div>
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="calculator-outline"></ion-icon>
                    Hasil Perhitungan SMART
                </h5>
                <button type="button" class="modal-close" onclick="closeSmartModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <div id="smartResult">
                    <!-- Hasil SMART akan diisi oleh JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeSmartModal()">
                    <ion-icon name="close-outline"></ion-icon> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HAPUS PENILAIAN -->
<div class="custom-modal" id="modalHapus">
    <div class="modal-overlay" onclick="closeHapusModal()"></div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <ion-icon name="warning-outline"></ion-icon>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="modal-close" onclick="closeHapusModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus penilaian ini?</p>
                <p><strong id="hapusInfo"></strong></p>
                <div class="alert alert-warning">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <strong>Peringatan:</strong> Data yang dihapus tidak dapat dikembalikan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeHapusModal()">
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
    /* Additional styles for Penilaian */
    .kriteria-container {
        max-height: 400px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #f9fafb;
    }
    
    .kriteria-item {
        margin-bottom: 20px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .kriteria-item:last-child {
        margin-bottom: 0;
    }
    
    .skor-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .skor-tinggi {
        background-color: rgba(76, 175, 80, 0.1);
        color: #2E7D32;
        border: 1px solid rgba(76, 175, 80, 0.2);
    }
    
    .skor-sedang {
        background-color: rgba(255, 152, 0, 0.1);
        color: #f57c00;
        border: 1px solid rgba(255, 152, 0, 0.2);
    }
    
    .skor-rendah {
        background-color: rgba(244, 67, 54, 0.1);
        color: #c62828;
        border: 1px solid rgba(244, 67, 54, 0.2);
    }
    
    .filter-container {
        padding: 20px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    
    /* Action buttons styling */
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
    }
    
    .btn-view {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .btn-view:hover {
        background: rgba(59, 130, 246, 0.2);
    }
    
    .btn-calculate {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .btn-calculate:hover {
        background: rgba(16, 185, 129, 0.2);
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.2);
    }
    
    /* Detail table styling */
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    
    .detail-table th,
    .detail-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .detail-table th {
        background: #f9fafb;
        font-weight: 600;
        color: #374151;
    }
    
    .detail-table tr:hover {
        background: #f9fafb;
    }
    
    /* SMART result styling */
    .smart-result-card {
        padding: 20px;
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: 12px;
        color: white;
        text-align: center;
        margin-bottom: 20px;
    }
    
    .smart-score {
        font-size: 3rem;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .smart-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    /* Custom styles for consistent UI */
    .candidates-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .header-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #2F4F2F 0%, #3E6B3E 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }
    
    .page-header h1 {
        margin: 0;
        font-size: 24px;
        color: #1f2937;
        font-weight: 700;
    }
    
    .page-subtitle {
        margin: 5px 0 0 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .stats-section {
        margin-bottom: 30px;
        display: none;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: transform 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
    }
    
    .stat-content h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .stat-content p {
        margin: 5px 0 0 0;
        color: #6b7280;
        font-size: 14px;
    }
    
    .data-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-header h2 {
        margin: 0;
        font-size: 18px;
        color: #1f2937;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .section-actions {
        display: flex;
        gap: 10px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .candidates-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .candidates-table thead {
        background: #2F4F2F;
    }
    
    .candidates-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        color: #f9fafb;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .candidates-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }
    
    .candidates-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .candidates-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Button Styles */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
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
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
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
    
    /* Form Styles */
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
        font-size: 14px;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .form-control.is-invalid {
        border-color: #ef4444;
    }
    
    /* Alert Styles */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin: 16px 0;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 14px;
    }
    
    .alert-info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        color: #1e40af;
    }
    
    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        border: 1px solid rgba(245, 158, 11, 0.2);
        color: #92400e;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #065f46;
    }
    
    /* Text Styles */
    .text-danger {
        color: #ef4444;
        font-weight: 500;
    }
    
    .text-muted {
        color: #6b7280;
        font-size: 13px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Loading State */
    .loading-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e5e7eb;
        border-top-color: #4361ee;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-state p {
        color: #6b7280;
        font-size: 14px;
    }
    
    /* Empty State */
    .empty-state {
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f9fafb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: #9ca3af;
        margin-bottom: 20px;
    }
    
    .empty-state h3 {
        margin: 0 0 10px 0;
        color: #1f2937;
        font-weight: 600;
        font-size: 18px;
    }
    
    .empty-state p {
        margin: 0 0 20px 0;
        color: #6b7280;
        font-size: 14px;
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
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }
    
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
    }
    
    .modal-title {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
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
        overflow-y: auto;
        flex: 1;
    }
    
    .modal-footer {
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-shrink: 0;
    }

    #modalKonfirmasiHitung .modal-dialog {
    max-width: 500px;
}

#modalKonfirmasiHitung ul {
    margin: 8px 0;
    padding-left: 20px;
}

#modalKonfirmasiHitung li {
    margin-bottom: 4px;
    font-size: 13px;
}

#hitungInfo {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 8px;
    border-left: 4px solid #4361ee;
    margin: 15px 0;
}
</style>

<!-- JavaScript -->
<script>
    const API_CONFIG = {
        baseUrl: '{{ url("/") }}',
        penilaianIndex: '/api/penilaian',
        penilaianStore: '/api/penilaian',
        penilaianShow: (id) => `/api/penilaian/${id}`,
        penilaianDestroy: (id) => `/api/penilaian/${id}`,
        penilaianHitung: (id) => `/api/penilaian/${id}/hitung`,
        kandidatIndex: '/api/kandidat',
        kriteriaIndex: '/api/kriteria',
        subKriteriaIndex: '/api/sub-kriteria',
        kriteriaSub: (id) => `/api/kriteria/${id}/sub`,
        csrfToken: '{{ csrf_token() }}'
    };
</script>

<script>
class PenilaianManager {
    constructor() {
        console.log('PenilaianManager initialized');
        this.penilaianData = [];
        this.kriteriaData = [];
        this.kandidatData = [];
        
        this.initElements();
        this.bindEvents();
        this.loadData();
    }

    initElements() {
        console.log('Initializing elements...');
        this.tableBody = document.getElementById('penilaianTableBody');
        this.emptyState = document.getElementById('emptyState');
        this.loadingState = document.getElementById('loadingState');
        
        this.totalPenilaian = document.getElementById('totalPenilaian');
        this.periodeAktif = document.getElementById('periodeAktif');
        this.rerataSkor = document.getElementById('rerataSkor');
        
        // Modal elements
        this.modalTambah = document.getElementById('modalTambah');
        this.modalDetail = document.getElementById('modalDetail');
        this.modalSmart = document.getElementById('modalSmart');
        this.modalHapus = document.getElementById('modalHapus');
        this.modalKonfirmasiHitung = document.getElementById('modalKonfirmasiHitung');
        
        // Form elements
        this.kriteriaContainer = document.getElementById('kriteriaContainer');
        this.kandidatSelect = document.getElementById('kandidat_id');
        
        console.log('Elements initialized');
    }

    bindEvents() {
        console.log('Binding events...');
        
        // Gunakan event delegation untuk tombol aksi di tabel
        document.addEventListener('click', (e) => {
            const target = e.target;
            
            // Cek apakah klik berasal dari tombol aksi atau elemen di dalamnya
            const actionBtn = target.closest('.btn-action');
            if (!actionBtn) return;
            
            const penilaianId = actionBtn.dataset.id;
            if (!penilaianId) return;
            
            // Tentukan aksi berdasarkan class tombol
            if (actionBtn.classList.contains('btn-view')) {
                this.showDetail(penilaianId);
            } else if (actionBtn.classList.contains('btn-calculate')) {
                this.hitungSMART(penilaianId);
            } else if (actionBtn.classList.contains('btn-delete')) {
                this.openDeleteModal(penilaianId);
            }
        });
        
        // Binding untuk modal buttons
        document.getElementById('btnKonfirmasiHapus')?.addEventListener('click', () => this.confirmDelete());
        document.getElementById('btnKonfirmasiHitung')?.addEventListener('click', () => this.confirmHitungSMART());
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
        
        console.log('Events bound successfully');
    }

    async loadData() {
        await Promise.all([
            this.loadPenilaians(),
            this.loadKandidats(),
            this.loadKriterias()
        ]);
    }

    async loadPenilaians(periode = '') {
        console.log('Loading penilaian...');
        this.showLoading(true);
        
        try {
            const url = periode ? `${API_CONFIG.penilaianIndex}?periode=${periode}` : API_CONFIG.penilaianIndex;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: Gagal memuat data`);
            }
            
            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.status === 'success') {
                this.penilaianData = data.data || [];
                this.renderTable();
                this.updateStatistics();
            } else {
                throw new Error(data.message || 'Gagal memuat data');
            }
        } catch (error) {
            console.error('Error loading penilaian:', error);
            this.showToast(error.message || 'Gagal memuat data penilaian', 'error');
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'flex';
        } finally {
            this.showLoading(false);
        }
    }

    async loadKandidats() {
        try {
            const response = await fetch(API_CONFIG.kandidatIndex);
            const data = await response.json();
            
            if (data.status === 'success') {
                this.kandidatData = data.data || [];
                this.populateKandidatSelect();
            }
        } catch (error) {
            console.error('Error loading kandidats:', error);
        }
    }

    async loadKriterias() {
        try {
            const response = await fetch(API_CONFIG.kriteriaIndex);
            const data = await response.json();
            
            if (data.status === 'success') {
                this.kriteriaData = data.data || [];
            }
        } catch (error) {
            console.error('Error loading kriterias:', error);
        }
    }

    populateKandidatSelect() {
        this.kandidatSelect.innerHTML = '<option value="">Pilih Kandidat</option>';
        this.kandidatData.forEach(kandidat => {
            const option = document.createElement('option');
            option.value = kandidat.id;
            option.textContent = kandidat.nama;
            this.kandidatSelect.appendChild(option);
        });
    }

    async displayKriteriaForm() {
        this.kriteriaContainer.innerHTML = '';
        
        for (const kriteria of this.kriteriaData) {
            try {
                const response = await fetch(API_CONFIG.kriteriaSub(kriteria.id));
                const result = await response.json();
                
                const subKriteria = result.data || [];
                
                const kriteriaDiv = document.createElement('div');
                kriteriaDiv.className = 'kriteria-item';
                kriteriaDiv.innerHTML = `
                    <label class="form-label">${kriteria.nama}</label>
                    <select name="sub_kriteria_${kriteria.id}" required class="form-control">
                        <option value="">Pilih Sub Kriteria</option>
                        ${subKriteria.map(sk => `
                            <option value="${sk.id}">
                                ${sk.nama} (Nilai: ${sk.nilai})
                            </option>
                        `).join('')}
                    </select>
                `;
                
                this.kriteriaContainer.appendChild(kriteriaDiv);
            } catch (error) {
                console.error(`Error loading subkriteria for kriteria ${kriteria.id}:`, error);
            }
        }
    }

    async submitPenilaian(event) {
        event.preventDefault();
        console.log('Submitting penilaian...');
        
        const kandidatId = this.kandidatSelect.value;
        
        if (!kandidatId) {
            this.showToast('Pilih kandidat terlebih dahulu', 'error');
            return;
        }
        
        const subKriteriaArray = [];
        let isValid = true;
        
        const selects = this.kriteriaContainer.querySelectorAll('select');
        selects.forEach(select => {
            if (!select.value) {
                select.classList.add('is-invalid');
                isValid = false;
            } else {
                select.classList.remove('is-invalid');
                
                const kriteriaId = select.name.replace('sub_kriteria_', '');
                subKriteriaArray.push({
                    kriteria_id: parseInt(kriteriaId),
                    sub_kriteria_id: parseInt(select.value)
                });
            }
        });
        
        if (!isValid) {
            this.showToast('Pilih semua sub kriteria yang tersedia', 'error');
            return;
        }
        
        try {
            console.log('Sending POST to:', API_CONFIG.penilaianStore);
            
            const response = await fetch(API_CONFIG.penilaianStore, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                },
                body: JSON.stringify({
                    kandidat_id: parseInt(kandidatId),
                    sub_kriteria_id: subKriteriaArray
                })
            });
            
            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);
            
            if (response.ok && data.status === 'success') {
                this.showToast('Penilaian berhasil ditambahkan', 'success');
                this.closeModal();
                await this.loadPenilaians(); // Ini akan merender ulang tabel dengan data baru
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    throw new Error(errorMessages);
                }
                throw new Error(data.message || 'Gagal menambahkan penilaian');
            }
        } catch (error) {
            console.error('Error saving penilaian:', error);
            this.showToast(error.message || 'Gagal menambahkan penilaian', 'error');
        }
    }

    async showDetail(id) {
        try {
            const response = await fetch(API_CONFIG.penilaianShow(id));
            const data = await response.json();
            
            if (data.status === 'success') {
                const penilaian = data.data;
                const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                
                let detailHTML = `
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">Kandidat</p>
                            <p class="text-lg font-semibold">${penilaian.kandidat?.nama || '-'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Periode</p>
                            <p class="text-lg font-semibold">${periode}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Skor</p>
                            <p class="text-lg font-semibold">${penilaian.total_skor || '0'}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Detail Penilaian</p>
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Kriteria</th>
                                        <th>Sub Kriteria</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                (penilaian.detail_penilaian || []).forEach(detail => {
                    detailHTML += `
                        <tr>
                            <td>${detail.kriteria?.nama || '-'}</td>
                            <td>${detail.sub_kriteria?.nama || '-'}</td>
                            <td><span class="skor-badge ${this.getSkorClass(detail.sub_kriteria?.nilai)}">
                                ${detail.sub_kriteria?.nilai || '0'}
                            </span></td>
                        </tr>
                    `;
                });
                
                detailHTML += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
                
                document.getElementById('detailContent').innerHTML = detailHTML;
                this.openDetailModal();
            }
        } catch (error) {
            console.error('Error showing detail:', error);
            this.showToast('Gagal memuat detail penilaian', 'error');
        }
    }

    openKonfirmasiHitungModal(id) {
        const penilaian = this.penilaianData.find(p => p.id == id);
        if (!penilaian) {
            this.showToast('Penilaian tidak ditemukan', 'error');
            return;
        }
        
        const kandidatNama = penilaian.kandidat?.nama || 'Unknown';
        const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { 
            month: 'long', 
            year: 'numeric' 
        });
        
        document.getElementById('hitungInfo').innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span><strong>Kandidat:</strong> ${kandidatNama}</span>
                <span><strong>Periode:</strong> ${periode}</span>
                <span><strong>Total Kriteria:</strong> ${(penilaian.detail_penilaian || []).length} kriteria</span>
            </div>
        `;
        
        this.modalKonfirmasiHitung.dataset.id = id;
        this.closeAllModals();
        this.modalKonfirmasiHitung.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeKonfirmasiHitungModal() {
        this.modalKonfirmasiHitung.classList.remove('active');
        document.body.style.overflow = '';
        delete this.modalKonfirmasiHitung.dataset.id;
    }

    async confirmHitungSMART() {
        const id = this.modalKonfirmasiHitung.dataset.id;
        
        if (!id) {
            this.showToast('ID penilaian tidak valid', 'error');
            return;
        }
        
        // Tampilkan loading di modal
        const modalBody = this.modalKonfirmasiHitung.querySelector('.modal-body');
        const originalContent = modalBody.innerHTML;
        
        modalBody.innerHTML = `
            <div class="text-center">
                <div class="spinner" style="margin: 0 auto 15px auto;"></div>
                <p>Sedang menghitung nilai SMART...</p>
                <p class="text-muted">Harap tunggu sebentar</p>
            </div>
        `;
        
        try {
            const response = await fetch(API_CONFIG.penilaianHitung(id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                this.closeKonfirmasiHitungModal();
                this.showSmartResult(data.data);
                this.showToast('Perhitungan SMART berhasil', 'success');
                
                // Refresh data setelah perhitungan
                setTimeout(() => {
                    this.loadPenilaians();
                }, 1000);
            } else {
                throw new Error(data.message || 'Gagal menghitung nilai SMART');
            }
        } catch (error) {
            console.error('Error calculating SMART:', error);
            modalBody.innerHTML = originalContent;
            this.showToast(error.message || 'Gagal menghitung nilai SMART', 'error');
        }
    }

    async hitungSMART(id) {
        this.openKonfirmasiHitungModal(id);
    }

    showSmartResult(data) {
        const smartResult = document.getElementById('smartResult');
        smartResult.innerHTML = `
            <div class="smart-result-card">
                <div class="smart-score">${data.total_skor || '0'}</div>
                <div class="smart-label">Total Skor SMART</div>
            </div>
            <div class="alert alert-success">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <strong>Berhasil!</strong> Perhitungan SMART telah selesai.
            </div>
            <p>Perhitungan SMART menggunakan metode Simple Multi Attribute Rating Technique telah berhasil dilakukan.</p>
        `;
        
        this.closeAllModals();
        this.modalSmart.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    openDeleteModal(id) {
        const penilaian = this.penilaianData.find(p => p.id == id);
        if (!penilaian) {
            this.showToast('Penilaian tidak ditemukan', 'error');
            return;
        }
        
        const kandidatNama = penilaian.kandidat?.nama || 'Unknown';
        const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        
        document.getElementById('hapusInfo').textContent = 
            `${kandidatNama} - Periode ${periode}`;
        
        this.modalHapus.dataset.id = id;
        this.closeAllModals();
        this.modalHapus.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    async confirmDelete() {
        const id = this.modalHapus.dataset.id;
        
        try {
            const response = await fetch(API_CONFIG.penilaianDestroy(id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                }
            });
            
            const data = await response.json();
            
            if (response.ok && data.status === 'success') {
                this.showToast('Penilaian berhasil dihapus', 'success');
                this.closeHapusModal();
                await this.loadPenilaians();
            } else {
                throw new Error(data.message || 'Gagal menghapus penilaian');
            }
        } catch (error) {
            console.error('Error deleting penilaian:', error);
            this.showToast(error.message || 'Gagal menghapus penilaian', 'error');
        }
    }

    renderTable() {
        console.log('Rendering penilaian:', this.penilaianData);
        
        if (!this.penilaianData || this.penilaianData.length === 0) {
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'flex';
            return;
        }
        
        this.emptyState.style.display = 'none';
        
        let html = '';
        
        this.penilaianData.forEach((penilaian, index) => {
            const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
            const detailPenilaian = penilaian.detail_penilaian || [];
            const totalKriteria = detailPenilaian.length;
            const totalSkor = penilaian.total_skor || '0';
            
            html += `
                <tr data-id="${penilaian.id}">
                    <td>${index + 1}</td>
                    <td>
                        <div class="kandidat-name">
                            <strong>${penilaian.kandidat?.nama || 'Unknown'}</strong>
                        </div>
                    </td>
                    <td>${periode}</td>
                    <td>
                        <span class="subkriteria-count">${totalKriteria}</span> kriteria
                    </td>
                    <td>
                        <span class="skor-badge ${this.getSkorClass(totalSkor)}">
                            ${totalSkor}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-action btn-view" data-id="${penilaian.id}" title="Detail">
                                <ion-icon name="eye-outline"></ion-icon>
                            </button>
                            <button class="btn-action btn-calculate" data-id="${penilaian.id}" title="Hitung SMART">
                                <ion-icon name="calculator-outline"></ion-icon>
                            </button>
                            <button class="btn-action btn-delete" data-id="${penilaian.id}" title="Hapus">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        this.tableBody.innerHTML = html;
        
        // HAPUS pemanggilan bindRowEvents() di sini karena kita sudah pakai event delegation
        console.log('Table rendered with', this.penilaianData.length, 'rows');
    }

    updateStatistics() {
        const total = this.penilaianData.length;
        const periodes = new Set(
            this.penilaianData.map(p => {
                const date = new Date(p.periode);
                return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}`;
            })
        );
        const totalSkor = this.penilaianData.reduce((sum, p) => sum + (parseFloat(p.total_skor) || 0), 0);
        const averageSkor = total > 0 ? (totalSkor / total).toFixed(2) : '0';
        
        console.log('Statistik:', { total, periodes: periodes.size, averageSkor });
        
        window.penilaianStats = {
            total,
            periodeAktif: periodes.size,
            rerataSkor: averageSkor
        };
    }

    getSkorClass(skor) {
        const nilai = parseFloat(skor) || 0;
        if (nilai >= 80) return 'skor-tinggi';
        if (nilai >= 60) return 'skor-sedang';
        return 'skor-rendah';
    }

    // Modal methods
    openModal() {
        console.log('Opening modal...');
        this.displayKriteriaForm();
        this.closeAllModals();
        this.modalTambah.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeModal() {
        this.modalTambah.classList.remove('active');
        document.body.style.overflow = '';
        
        const form = document.getElementById('formPenilaian');
        if (form) {
            form.reset();
        }
    }

    openDetailModal() {
        this.closeAllModals();
        this.modalDetail.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeDetailModal() {
        this.modalDetail.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeSmartModal() {
        this.modalSmart.classList.remove('active');
        document.body.style.overflow = '';
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

    // Filter methods
    filterByPeriode() {
        const periode = document.getElementById('filterPeriode').value;
        this.loadPenilaians(periode);
    }

    clearFilter() {
        document.getElementById('filterPeriode').value = '';
        this.loadPenilaians();
    }

    // Utility methods
    showLoading(show) {
        if (this.loadingState) this.loadingState.style.display = show ? 'flex' : 'none';
        if (this.tableBody) this.tableBody.parentElement.parentElement.style.display = show ? 'none' : 'block';
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

// Global functions
function closeKonfirmasiHitungModal() {
    if (window.penilaianManager) {
        window.penilaianManager.closeKonfirmasiHitungModal();
    }
}

function openModal() {
    if (window.penilaianManager) {
        window.penilaianManager.openModal();
    }
}

function closeModal() {
    if (window.penilaianManager) {
        window.penilaianManager.closeModal();
    }
}

function closeDetailModal() {
    if (window.penilaianManager) {
        window.penilaianManager.closeDetailModal();
    }
}

function closeSmartModal() {
    if (window.penilaianManager) {
        window.penilaianManager.closeSmartModal();
    }
}

function closeHapusModal() {
    if (window.penilaianManager) {
        window.penilaianManager.closeHapusModal();
    }
}

function filterByPeriode() {
    if (window.penilaianManager) {
        window.penilaianManager.filterByPeriode();
    }
}

function clearFilter() {
    if (window.penilaianManager) {
        window.penilaianManager.clearFilter();
    }
}

function loadPenilaians() {
    if (window.penilaianManager) {
        window.penilaianManager.loadPenilaians();
    }
}

function submitPenilaian(event) {
    if (window.penilaianManager) {
        window.penilaianManager.submitPenilaian(event);
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Penilaian');
    try {
        window.penilaianManager = new PenilaianManager();
        console.log('PenilaianManager initialized successfully');
    } catch (error) {
        console.error('Error initializing PenilaianManager:', error);
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
</script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
@endsection
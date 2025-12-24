@extends('layouts.app')

@section('title', 'Data Kandidat')

@section('content')
<link rel="stylesheet" href="css/dashboard/data_kandidat.css">
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="people-outline"></ion-icon>
            </div>
            <div>
                <h1>Manajemen Data Kandidat</h1>
                <p class="page-subtitle">Kelola data kandidat untuk proses seleksi</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline-primary" id="btnImport">
                <ion-icon name="cloud-upload-outline"></ion-icon> Import
            </button>
            <button class="btn btn-primary" id="btnTambahBaru" >
                <ion-icon name="add-outline"></ion-icon> Tambah Kandidat
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalKandidat">0</h3>
                    <p>Total Kandidat</p>
                </div>
                <div class="stat-trend">
                    <ion-icon name="trending-up-outline"></ion-icon>
                    <span>+2 hari ini</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                    <ion-icon name="star-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="topRated">0</h3>
                    <p>Top Rated</p>
                </div>
                <div class="stat-trend">
                    <ion-icon name="trophy-outline"></ion-icon>
                    <span>Skor ≥ 4.5</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="pendingReview">0</h3>
                    <p>Perlu Review</p>
                </div>
                <div class="stat-trend">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    <span>Skor ≤ 3.0</span>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);">
                    <ion-icon name="calendar-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="activePeriod">-</h3>
                    <p>Periode Aktif</p>
                </div>
                <div class="stat-trend">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span>Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kontrol dan Filter -->
    <div class="controls-section">
        <div class="controls-grid">
            <!-- Periode Selector -->
            <div class="control-group">
                <label>Periode Seleksi</label>
                <div class="periode-selector">
                    <select id="periodeSelect" class="periode-select">
                        <option value="">Semua Periode</option>
                        <!-- Options akan diisi otomatis oleh JavaScript dari data -->
                    </select>
                    <div class="periode-info">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <span>Periode: <strong id="currentPeriode">Semua Periode</strong></span>
                    </div>
                </div>
            </div>
            
            <!-- Search Box -->
            <div class="control-group">
                <label>Cari Kandidat</label>
                <div class="search-box">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" id="searchInput" placeholder="Nama, ID, atau kriteria...">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Kandidat -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="list-outline"></ion-icon> Daftar Kandidat</h2>
            <div class="section-actions">
            </div>
        </div>
        
        <div class="table-container">
            <table class="candidates-table" id="candidatesTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th>Nama Kandidat</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="candidatesTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState">
            <div class="empty-icon">
                <ion-icon name="people-outline"></ion-icon>
            </div>
            <h3>Belum Ada Data Kandidat</h3>
            <p>Tambahkan kandidat baru untuk memulai proses seleksi</p>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container" id="paginationContainer" style="display: none;">
            <div class="pagination-info">
                Menampilkan <span id="pageStart">0</span>-<span id="pageEnd">0</span> dari <span id="totalItems">0</span> kandidat
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" id="btnPrev">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </button>
                <div class="pagination-numbers" id="paginationNumbers"></div>
                <button class="pagination-btn" id="btnNext">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </button>
            </div>
            <div class="items-per-page">
                <span>Per halaman:</span>
                <select id="itemsPerPage" class="page-select">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Batch Actions -->
    <div class="batch-actions" id="batchActions" style="display: none;">
        <div class="batch-info">
            <ion-icon name="checkbox-outline"></ion-icon>
            <span id="selectedCount">0</span> kandidat terpilih
        </div>
        <div class="batch-buttons">
            <button class="btn btn-outline-danger" id="btnBatchDelete">
                <ion-icon name="trash-outline"></ion-icon> Hapus Terpilih
            </button>
            <button class="btn btn-outline-primary" id="btnBatchExport">
                <ion-icon name="download-outline"></ion-icon> Export Terpilih
            </button>
            <button class="btn btn-primary" id="btnBatchReview">
                <ion-icon name="eye-outline"></ion-icon> Tinjau Terpilih
            </button>
        </div>
    </div>

    <!-- Footer Halaman -->
    <div class="page-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="info-item">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    <div>
                        <span>Tips Pengelolaan</span>
                        <strong>Simpan perubahan secara berkala</strong>
                    </div>
                </div>
                <div class="info-item">
                    <ion-icon name="time-outline"></ion-icon>
                    <div>
                        <span>Update Terakhir</span>
                        <strong id="lastUpdate">-</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('partials.DataKandidat.TambahKandidat')
@include('partials.DataKandidat.EditDaftarKandidat')
@include('partials.DataKandidat.HapusDataKandidat')
@include('partials.DataKandidat.DetailKandidat')
{{-- @include('partials.DataKandidat.BatchDeleteModal') --}}

<script src="{{ asset('js/dashboard/data_kandidat.js') }}"></script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

@endsection
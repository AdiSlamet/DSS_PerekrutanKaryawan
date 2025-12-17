@extends('layouts.app')

@section('title', 'Hasil Perhitungan & Ranking')
    
@section('content')
<link rel="stylesheet" href="css/dashboard/perhitungan.css">
<div class="results-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="trophy-outline"></ion-icon>
            </div>
            <div>
                <h1>Hasil Perhitungan & Ranking</h1>
                <p class="page-subtitle">Analisis lengkap hasil seleksi kandidat berdasarkan bobot kriteria</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline-primary" id="btnRefresh">
                <ion-icon name="refresh-outline"></ion-icon> Refresh Data
            </button>
            <button class="btn btn-primary" id="btnSimpanSemua">
                <ion-icon name="save-outline"></ion-icon> Simpan Semua
            </button>
        </div>
    </div>

    <!-- Navigasi Halaman -->
    <div class="page-navigation">
        <div class="nav-tabs">
            <a href="#overview" class="nav-tab active">
                <ion-icon name="stats-chart-outline"></ion-icon>
                Ringkasan
            </a>
            <a href="#ranking" class="nav-tab">
                <ion-icon name="podium-outline"></ion-icon>
                Ranking
            </a>
            <a href="#analysis" class="nav-tab">
                <ion-icon name="analytics-outline"></ion-icon>
                Analisis Detail
            </a>
            <a href="#comparison" class="nav-tab">
                <ion-icon name="git-compare-outline"></ion-icon>
                Perbandingan
            </a>
        </div>
        
        <div class="quick-actions">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown">
                    <ion-icon name="download-outline"></ion-icon>
                    Export
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" id="exportPDF">
                        <ion-icon name="document-outline"></ion-icon> PDF
                    </a>
                    <a class="dropdown-item" href="#" id="exportExcel">
                        <ion-icon name="document-text-outline"></ion-icon> Excel
                    </a>
                    <a class="dropdown-item" href="#" id="exportJSON">
                        <ion-icon name="code-outline"></ion-icon> JSON
                    </a>
                </div>
            </div>
            
            <button class="btn btn-outline-secondary" id="btnPrint">
                <ion-icon name="print-outline"></ion-icon> Cetak
            </button>
        </div>
    </div>

    <!-- Konten Utama -->
    <div class="page-content">
        <!-- Section 1: Ringkasan Statistik -->
        <section id="overview" class="page-section active">
            <div class="section-header">
                <h2><ion-icon name="stats-chart-outline"></ion-icon> Ringkasan Statistik</h2>
                <p class="section-subtitle">Gambaran umum hasil perhitungan kandidat</p>
            </div>
            
            <!-- Statistik Cards -->
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
                        <span>Semua data</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                        <ion-icon name="trophy-outline"></ion-icon>
                    </div>
                    <div class="stat-content">
                        <h3 id="skorTertinggi">0.00</h3>
                        <p>Skor Tertinggi</p>
                    </div>
                    <div class="stat-trend">
                        <ion-icon name="trending-up-outline"></ion-icon>
                        <span id="topCandidateName">-</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                        <ion-icon name="trending-down-outline"></ion-icon>
                    </div>
                    <div class="stat-content">
                        <h3 id="skorTerendah">0.00</h3>
                        <p>Skor Terendah</p>
                    </div>
                    <div class="stat-trend">
                        <ion-icon name="trending-down-outline"></ion-icon>
                        <span id="bottomCandidateName">-</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);">
                        <ion-icon name="calculator-outline"></ion-icon>
                    </div>
                    <div class="stat-content">
                        <h3 id="rataRata">0.00</h3>
                        <p>Rata-rata Skor</p>
                    </div>
                    <div class="stat-trend">
                        <ion-icon name="stats-chart-outline"></ion-icon>
                        <span>Seluruh kandidat</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                    <div class="stat-content">
                        <h3 id="direkomendasikan">0</h3>
                        <p>Direkomendasikan</p>
                    </div>
                    <div class="stat-trend">
                        <ion-icon name="arrow-up-outline"></ion-icon>
                        <span>≥ 4.0 skor</span>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #E91E63 0%, #C2185B 100%);">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                    </div>
                    <div class="stat-content">
                        <h3 id="perluPertimbangan">0</h3>
                        <p>Perlu Pertimbangan</p>
                    </div>
                    <div class="stat-trend">
                        <ion-icon name="warning-outline"></ion-icon>
                        <span>&lt; 3.0 skor</span>
                    </div>
                </div>
            </div>

            <!-- Bobot Kriteria -->
            <div class="weight-section">
                <div class="section-header">
                    <h3><ion-icon name="scale-outline"></ion-icon> Bobot Kriteria yang Digunakan</h3>
                    <p class="section-subtitle">Konfigurasi bobot untuk perhitungan</p>
                </div>
                
                <div class="weight-grid">
                    <div class="weight-item">
                        <div class="weight-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div>
                                <h4>Pengalaman</h4>
                                <p>Pengalaman kerja relevan</p>
                            </div>
                        </div>
                        <div class="weight-value" id="weightPengalaman">30%</div>
                        <div class="weight-range">
                            <div class="range-bar">
                                <div class="range-fill" style="width: 30%;"></div>
                            </div>
                            <div class="range-labels">
                                <span>10%</span>
                                <span>30%</span>
                                <span>50%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="weight-item">
                        <div class="weight-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <div>
                                <h4>Jarak</h4>
                                <p>Jarak tempat tinggal</p>
                            </div>
                        </div>
                        <div class="weight-value" id="weightJarak">25%</div>
                        <div class="weight-range">
                            <div class="range-bar">
                                <div class="range-fill" style="width: 25%;"></div>
                            </div>
                            <div class="range-labels">
                                <span>10%</span>
                                <span>25%</span>
                                <span>40%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="weight-item">
                        <div class="weight-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div>
                                <h4>Komunikasi</h4>
                                <p>Kemampuan komunikasi</p>
                            </div>
                        </div>
                        <div class="weight-value" id="weightKomunikasi">25%</div>
                        <div class="weight-range">
                            <div class="range-bar">
                                <div class="range-fill" style="width: 25%;"></div>
                            </div>
                            <div class="range-labels">
                                <span>10%</span>
                                <span>25%</span>
                                <span>40%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="weight-item">
                        <div class="weight-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                            <div>
                                <h4>Fleksibilitas</h4>
                                <p>Fleksibilitas kerja</p>
                            </div>
                        </div>
                        <div class="weight-value" id="weightFleksibilitas">20%</div>
                        <div class="weight-range">
                            <div class="range-bar">
                                <div class="range-fill" style="width: 20%;"></div>
                            </div>
                            <div class="range-labels">
                                <span>10%</span>
                                <span>20%</span>
                                <span>40%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="weight-summary">
                    <div class="total-weight">
                        <span>Total Bobot:</span>
                        <span class="total-value" id="totalWeight">100%</span>
                    </div>
                    <div class="weight-status">
                        <span class="status-badge valid">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                            Valid
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chart Distribusi -->
            <div class="chart-section">
                <div class="section-header">
                    <h3><ion-icon name="pie-chart-outline"></ion-icon> Distribusi Skor Kandidat</h3>
                    <p class="section-subtitle">Persebaran skor akhir kandidat</p>
                </div>
                
                <div class="chart-container">
                    <div class="chart-wrapper">
                        <canvas id="scoreDistributionChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background: #4CAF50;"></span>
                            <span>Sangat Baik (4.0-5.0)</span>
                            <span class="legend-count" id="countExcellent">0</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #FF9800;"></span>
                            <span>Baik (3.0-3.9)</span>
                            <span class="legend-count" id="countGood">0</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #F44336;"></span>
                            <span>Cukup (2.0-2.9)</span>
                            <span class="legend-count" id="countFair">0</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background: #9E9E9E;"></span>
                            <span>Kurang (&lt; 2.0)</span>
                            <span class="legend-count" id="countPoor">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Ranking -->
        <section id="ranking" class="page-section">
            <div class="section-header">
                <h2><ion-icon name="podium-outline"></ion-icon> Ranking Kandidat</h2>
                <p class="section-subtitle">Urutan kandidat berdasarkan skor akhir terbobot</p>
            </div>
            
            <!-- Filter dan Pencarian -->
            <div class="ranking-controls">
                <div class="search-box">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" id="searchCandidate" placeholder="Cari kandidat...">
                </div>
                
                <div class="filter-group">
                    <select id="filterStatus" class="filter-select">
                        <option value="all">Semua Status</option>
                        <option value="recommended">Direkomendasikan</option>
                        <option value="qualified">Memenuhi Syarat</option>
                        <option value="needs-review">Perlu Pertimbangan</option>
                    </select>
                    
                    <select id="filterRank" class="filter-select">
                        <option value="all">Semua Ranking</option>
                        <option value="top3">Top 3</option>
                        <option value="top10">Top 10</option>
                        <option value="top50%">Top 50%</option>
                    </select>
                    
                    <button class="btn btn-outline-secondary" id="btnResetFilter">
                        <ion-icon name="refresh-outline"></ion-icon> Reset
                    </button>
                </div>
            </div>

            <!-- Tabel Ranking -->
            <div class="ranking-table-container">
                <table class="ranking-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Rank</th>
                            <th>Kandidat</th>
                            <th style="width: 120px;">Skor Akhir</th>
                            <th style="width: 150px;">Detail Nilai</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="rankingBody">
                        <!-- Data akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
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

            <!-- Top 3 Highlight -->
            <div class="top-three-section">
                <div class="section-header">
                    <h3><ion-icon name="medal-outline"></ion-icon> Top 3 Kandidat</h3>
                    <p class="section-subtitle">Kandidat dengan skor tertinggi</p>
                </div>
                
                <div class="top-three-cards">
                    <!-- Top 1 -->
                    <div class="top-card top-1">
                        <div class="top-badge">
                            <ion-icon name="trophy-outline"></ion-icon>
                            <span>#1</span>
                        </div>
                        <div class="top-avatar" id="top1Avatar">
                            <span>--</span>
                        </div>
                        <div class="top-info">
                            <h4 id="top1Name">-</h4>
                            <p id="top1Score">Skor: 0.00</p>
                        </div>
                        <div class="top-details">
                            <div class="detail-item">
                                <span>Pengalaman</span>
                                <span id="top1Exp">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Jarak</span>
                                <span id="top1Distance">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Komunikasi</span>
                                <span id="top1Comm">0/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top 2 -->
                    <div class="top-card top-2">
                        <div class="top-badge">
                            <ion-icon name="medal-outline"></ion-icon>
                            <span>#2</span>
                        </div>
                        <div class="top-avatar" id="top2Avatar">
                            <span>--</span>
                        </div>
                        <div class="top-info">
                            <h4 id="top2Name">-</h4>
                            <p id="top2Score">Skor: 0.00</p>
                        </div>
                        <div class="top-details">
                            <div class="detail-item">
                                <span>Pengalaman</span>
                                <span id="top2Exp">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Jarak</span>
                                <span id="top2Distance">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Komunikasi</span>
                                <span id="top2Comm">0/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top 3 -->
                    <div class="top-card top-3">
                        <div class="top-badge">
                            <ion-icon name="ribbon-outline"></ion-icon>
                            <span>#3</span>
                        </div>
                        <div class="top-avatar" id="top3Avatar">
                            <span>--</span>
                        </div>
                        <div class="top-info">
                            <h4 id="top3Name">-</h4>
                            <p id="top3Score">Skor: 0.00</p>
                        </div>
                        <div class="top-details">
                            <div class="detail-item">
                                <span>Pengalaman</span>
                                <span id="top3Exp">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Jarak</span>
                                <span id="top3Distance">0/5</span>
                            </div>
                            <div class="detail-item">
                                <span>Komunikasi</span>
                                <span id="top3Comm">0/5</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 3: Analisis Detail -->
        <section id="analysis" class="page-section">
            <div class="section-header">
                <h2><ion-icon name="analytics-outline"></ion-icon> Analisis Detail</h2>
                <p class="section-subtitle">Analisis mendalam terhadap setiap kandidat</p>
            </div>
            
            <!-- Pilih Kandidat untuk Analisis -->
            <div class="analysis-controls">
                <div class="candidate-selector">
                    <label>Pilih Kandidat:</label>
                    <select id="selectCandidate" class="candidate-select">
                        <option value="">-- Pilih Kandidat --</option>
                        <!-- Options akan diisi oleh JavaScript -->
                    </select>
                </div>
                
                <div class="analysis-actions">
                    <button class="btn btn-outline-primary" id="btnCompare">
                        <ion-icon name="git-compare-outline"></ion-icon> Bandingkan
                    </button>
                    <button class="btn btn-outline-secondary" id="btnViewDetails">
                        <ion-icon name="document-text-outline"></ion-icon> Lihat Detail
                    </button>
                </div>
            </div>

            <!-- Card Analisis Kandidat -->
            <div class="analysis-card" id="analysisCard">
                <div class="card-placeholder">
                    <ion-icon name="person-circle-outline"></ion-icon>
                    <h3>Pilih kandidat untuk melihat analisis</h3>
                    <p>Pilih kandidat dari dropdown di atas untuk menampilkan analisis detail</p>
                </div>
            </div>

            <!-- Grafik Perbandingan Kriteria -->
            <div class="comparison-chart-section">
                <div class="section-header">
                    <h3><ion-icon name="bar-chart-outline"></ion-icon> Perbandingan Kriteria</h3>
                    <p class="section-subtitle">Performa kandidat pada setiap kriteria</p>
                </div>
                
                <div class="chart-container">
                    <canvas id="criteriaComparisonChart"></canvas>
                </div>
            </div>

            <!-- Analisis Kekuatan dan Kelemahan -->
            <div class="strength-analysis">
                <div class="strength-grid">
                    <div class="strength-card">
                        <div class="strength-header">
                            <ion-icon name="thumbs-up-outline" style="color: #4CAF50;"></ion-icon>
                            <h3>Kekuatan Utama</h3>
                        </div>
                        <div class="strength-content" id="strengthsList">
                            <p>Pilih kandidat untuk melihat analisis kekuatan</p>
                        </div>
                    </div>
                    
                    <div class="strength-card">
                        <div class="strength-header">
                            <ion-icon name="thumbs-down-outline" style="color: #F44336;"></ion-icon>
                            <h3>Area Perbaikan</h3>
                        </div>
                        <div class="strength-content" id="weaknessesList">
                            <p>Pilih kandidat untuk melihat area perbaikan</p>
                        </div>
                    </div>
                    
                    <div class="strength-card">
                        <div class="strength-header">
                            <ion-icon name="bulb-outline" style="color: #FF9800;"></ion-icon>
                            <h3>Rekomendasi</h3>
                        </div>
                        <div class="strength-content" id="recommendationsList">
                            <p>Pilih kandidat untuk melihat rekomendasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Perbandingan -->
        <section id="comparison" class="page-section">
            <div class="section-header">
                <h2><ion-icon name="git-compare-outline"></ion-icon> Perbandingan Kandidat</h2>
                <p class="section-subtitle">Bandingkan beberapa kandidat secara side-by-side</p>
            </div>
            
            <!-- Pilih Kandidat untuk Perbandingan -->
            <div class="comparison-controls">
                <div class="comparison-selectors">
                    <div class="selector-group">
                        <label>Kandidat 1:</label>
                        <select id="compareCandidate1" class="comparison-select">
                            <option value="">-- Pilih Kandidat 1 --</option>
                        </select>
                    </div>
                    
                    <div class="selector-group">
                        <label>Kandidat 2:</label>
                        <select id="compareCandidate2" class="comparison-select">
                            <option value="">-- Pilih Kandidat 2 --</option>
                        </select>
                    </div>
                    
                    <div class="selector-group">
                        <label>Kandidat 3:</label>
                        <select id="compareCandidate3" class="comparison-select">
                            <option value="">-- Pilih Kandidat 3 --</option>
                        </select>
                    </div>
                </div>
                
                <button class="btn btn-primary" id="btnCompareSelected">
                    <ion-icon name="git-compare-outline"></ion-icon> Bandingkan
                </button>
            </div>

            <!-- Hasil Perbandingan -->
            <div class="comparison-results" id="comparisonResults">
                <div class="comparison-placeholder">
                    <ion-icon name="people-outline"></ion-icon>
                    <h3>Pilih kandidat untuk perbandingan</h3>
                    <p>Pilih 2-3 kandidat dari dropdown di atas untuk memulai perbandingan</p>
                </div>
            </div>

            <!-- Tabel Perbandingan -->
            <div class="comparison-table-container" id="comparisonTableContainer" style="display: none;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th>Kriteria</th>
                            <th id="compareHeader1">Kandidat 1</th>
                            <th id="compareHeader2">Kandidat 2</th>
                            <th id="compareHeader3">Kandidat 3</th>
                        </tr>
                    </thead>
                    <tbody id="comparisonTableBody">
                        <!-- Data perbandingan akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Grafik Perbandingan -->
            <div class="comparison-chart-container" id="comparisonChartContainer" style="display: none;">
                <div class="section-header">
                    <h3><ion-icon name="stats-chart-outline"></ion-icon> Visualisasi Perbandingan</h3>
                </div>
                <div class="chart-container">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Halaman -->
    <div class="page-footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="info-item">
                    <ion-icon name="time-outline"></ion-icon>
                    <div>
                        <span>Waktu Perhitungan</span>
                        <strong id="calculationTime">-</strong>
                    </div>
                </div>
                <div class="info-item">
                    <ion-icon name="settings-outline"></ion-icon>
                    <div>
                        <span>Konfigurasi Bobot</span>
                        <strong id="weightConfig">Default</strong>
                    </div>
                </div>
                <div class="info-item">
                    <ion-icon name="document-outline"></ion-icon>
                    <div>
                        <span>Versi Laporan</span>
                        <strong id="reportVersion">1.0</strong>
                    </div>
                </div>
            </div>
            
            <div class="footer-actions">
                <button class="btn btn-outline-secondary" id="btnBack">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </button>
                <button class="btn btn-success" id="btnFinalize">
                    <ion-icon name="checkmark-done-outline"></ion-icon> Finalisasi Hasil
                </button>
            </div>
        </div>
    </div>
</div>

<style>

</style>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('js/dashboard/perhitungan.js') }}"></script>
@endsection
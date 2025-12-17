@extends('layouts.app')

@section('title', 'Penilaian - Proses Perhitungan SAW')
    
@section('content')
<link rel="stylesheet" href="css/dashboard/penilaian.css">
<div class="calculation-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="calculator-outline"></ion-icon>
            </div>
            <div>
                <h1>Penilaian & Perhitungan SAW</h1>
                <p class="page-subtitle">Proses detail perhitungan Simple Additive Weighting</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-outline-primary" id="btnRefresh">
                <ion-icon name="refresh-outline"></ion-icon> Refresh Data
            </button>
            <button class="btn btn-primary" id="btnProses">
                <ion-icon name="play-outline"></ion-icon> Proses Perhitungan
            </button>
        </div>
    </div>

    {{-- fitur pencarian --}}
    <div class="filter-container">
        <div class="filter-section">
            <div class="filter-header">
                <h3><ion-icon name="search-outline"></ion-icon> Pencarian & Filter</h3>
            </div>
            
            <div class="filter-grid">
                <!-- Pencarian -->
                <div class="filter-group">
                    <label for="searchInput"><ion-icon name="search"></ion-icon> Cari Kandidat:</label>
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Cari berdasarkan nama, ID, atau posisi...">
                        <button class="search-btn" id="btnSearch">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <!-- Filter Periode -->
                <div class="filter-group">
                    <label for="periodeFilter"><ion-icon name="calendar-outline"></ion-icon> Periode:</label>
                    <select id="periodeFilter" class="filter-select">
                        <option value="all">Semua Periode</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="custom">Periode Kustom</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="filter-group">
                    <label for="statusFilter"><ion-icon name="funnel-outline"></ion-icon> Status:</label>
                    <select id="statusFilter" class="filter-select">
                        <option value="all">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Non-aktif</option>
                        <option value="selected">Terpilih</option>
                    </select>
                </div>

                <!-- Periode Kustom (Tampil hanya saat dipilih) -->
                <div class="filter-group custom-date-range" id="customDateRange" style="display: none;">
                    <label><ion-icon name="calendar"></ion-icon> Rentang Tanggal:</label>
                    <div class="date-range-inputs">
                        <div class="date-input">
                            <label for="startDate">Dari:</label>
                            <input type="date" id="startDate" class="date-picker">
                        </div>
                        <div class="date-input">
                            <label for="endDate">Sampai:</label>
                            <input type="date" id="endDate" class="date-picker">
                        </div>
                        <button class="btn-apply" id="btnApplyDateRange">
                            <ion-icon name="checkmark-outline"></ion-icon>
                        </button>
                    </div>
                </div>
            </div>
        </div>

            <!-- Navigasi Tahapan -->
        <div class="steps-container">
            <div class="step active" data-step="1">
                <div class="step-number">1</div>
                <div class="step-label">
                    <span>Data Alternatif</span>
                    <small>Data kandidat</small>
                </div>
            </div>
            <div class="step" data-step="2">
                <div class="step-number">2</div>
                <div class="step-label">
                    <span>Matriks Keputusan</span>
                    <small>X = (Xij)</small>
                </div>
            </div>
            <div class="step" data-step="3">
                <div class="step-number">3</div>
                <div class="step-label">
                    <span>Bobot Kriteria</span>
                    <small>W = (Wj)</small>
                </div>
            </div>
            <div class="step" data-step="4">
                <div class="step-number">4</div>
                <div class="step-label">
                    <span>Normalisasi</span>
                    <small>R = (Rij)</small>
                </div>
            </div>
            <div class="step" data-step="5">
                <div class="step-number">5</div>
                <div class="step-label">
                    <span>Nilai Utility</span>
                    <small>V = (Vij)</small>
                </div>
            </div>
            <div class="step" data-step="6">
                <div class="step-number">6</div>
                <div class="step-label">
                    <span>Hasil Akhir</span>
                    <small>Ranking</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Konten Utama -->
    <div class="page-content">
        <!-- Step 1: Data Alternatif -->
        <section id="step1" class="calculation-step active">
            <div class="step-header">
                <h2><ion-icon name="people-outline"></ion-icon> Data Alternatif (Kandidat)</h2>
                <p class="step-description">Data kandidat yang akan dinilai berdasarkan kriteria</p>
            </div>
            
            <!-- Tabel Data Alternatif -->
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">No</th>
                            <th>Nama Kandidat</th>
                            <th style="width: 120px;">ID</th>
                            <th>Posisi</th>
                            <th style="width: 150px;">Tanggal Daftar</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="alternativesTable">
                        <!-- Data alternatif akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="data-summary">
                <div class="summary-item">
                    <span class="label">Total Alternatif:</span>
                    <span class="value" id="totalAlternatives">0</span>
                </div>
                <div class="summary-item">
                    <span class="label">Alternatif Aktif:</span>
                    <span class="value" id="activeAlternatives">0</span>
                </div>
                <div class="summary-item">
                    <span class="label">Alternatif Dipilih:</span>
                    <span class="value" id="selectedAlternatives">0</span>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn btn-outline-secondary" id="btnSelectAll">
                    <ion-icon name="checkbox-outline"></ion-icon> Pilih Semua
                </button>
                <button class="btn btn-secondary" id="btnNextStep1">
                    Lanjut ke Matriks Keputusan <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </div>
        </section>

        <!-- Step 2: Matriks Keputusan -->
        <section id="step2" class="calculation-step">
            <div class="step-header">
                <h2><ion-icon name="grid-outline"></ion-icon> Matriks Keputusan (X)</h2>
                <p class="step-description">Matriks nilai alternatif terhadap setiap kriteria</p>
            </div>
            
            <!-- Filter dan Kontrol -->
            <div class="matrix-controls">
                <div class="control-group">
                    <label>Tampilkan:</label>
                    <select id="showCriteria" class="control-select">
                        <option value="all">Semua Kriteria</option>
                        <option value="cost">Kriteria Cost</option>
                        <option value="benefit">Kriteria Benefit</option>
                    </select>
                </div>
                <div class="control-group">
                    <label>Format Nilai:</label>
                    <select id="valueFormat" class="control-select">
                        <option value="decimal">Desimal (1-5)</option>
                        <option value="percentage">Persentase</option>
                        <option value="scale">Skala 0-100</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Matriks Keputusan -->
            <div class="matrix-container">
                <table class="matrix-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 80px;">Alt</th>
                            <th rowspan="2" style="width: 150px;">Nama</th>
                            <th colspan="4"  style="color: #000;" class="criteria-header">Kriteria</th>
                            <th rowspan="2" style="width: 120px;">Tipe</th>
                        </tr>
                        <tr>
                            <th style="width: 100px;">C1 (Pengalaman)</th>
                            <th style="width: 100px;">C2 (Jarak)</th>
                            <th style="width: 100px;">C3 (Komunikasi)</th>
                            <th style="width: 100px;">C4 (Fleksibilitas)</th>
                        </tr>
                    </thead>
                    <tbody id="matrixTableBody">
                        <!-- Data matriks akan diisi oleh JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr class="matrix-summary">
                            <td colspan="2"><strong>Min (Xmin)</strong></td>
                            <td id="minC1">-</td>
                            <td id="minC2">-</td>
                            <td id="minC3">-</td>
                            <td id="minC4">-</td>
                            <td>-</td>
                        </tr>
                        <tr class="matrix-summary">
                            <td colspan="2"><strong>Max (Xmax)</strong></td>
                            <td id="maxC1">-</td>
                            <td id="maxC2">-</td>
                            <td id="maxC3">-</td>
                            <td id="maxC4">-</td>
                            <td>-</td>
                        </tr>
                        <tr class="matrix-summary">
                            <td colspan="2"><strong>Tipe Kriteria</strong></td>
                            <td><span class="badge benefit">Benefit</span></td>
                            <td><span class="badge cost">Cost</span></td>
                            <td><span class="badge benefit">Benefit</span></td>
                            <td><span class="badge benefit">Benefit</span></td>
                            <td>-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Rumus dan Keterangan -->
            <div class="formula-section">
                <h3><ion-icon name="information-circle-outline"></ion-icon> Keterangan Matriks Keputusan</h3>
                <div class="formula-content">
                    <p><strong>Matriks Keputusan (X):</strong> Menyimpan nilai alternatif terhadap kriteria</p>
                    <div class="formula-box">
                        <code>X = [x<sub>ij</sub>]<sub>m×n</sub></code>
                        <p>Dimana:</p>
                        <ul>
                            <li>x<sub>ij</sub> = Nilai alternatif ke-i pada kriteria ke-j</li>
                            <li>m = Jumlah alternatif (kandidat)</li>
                            <li>n = Jumlah kriteria</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn btn-outline-secondary" id="btnBackStep2">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </button>
                <button class="btn btn-secondary" id="btnNextStep2">
                    Lanjut ke Bobot Kriteria <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </div>
        </section>

        <!-- Step 3: Bobot Kriteria -->
        <section id="step3" class="calculation-step">
            <div class="step-header">
                <h2><ion-icon name="scale-outline"></ion-icon> Bobot Kriteria (W)</h2>
                <p class="step-description">Pemberian bobot untuk setiap kriteria berdasarkan tingkat kepentingan</p>
            </div>

            <!-- Bobot Kriteria -->
            <div class="weight-container">
                <div class="weight-cards">
                    <div class="weight-card">
                        <div class="weight-card-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div>
                                <h3>C1: Pengalaman Kerja</h3>
                                <p>Pengalaman kerja relevan (tahun)</p>
                            </div>
                        </div>
                        <div class="weight-input-group">
                            <label>Bobot (W<sub>1</sub>):</label>
                            <div class="input-with-unit">
                                <input type="number" id="weightC1" class="weight-input" value="30" min="0" max="100">
                                <span class="input-unit">%</span>
                            </div>
                        </div>
                        <div class="weight-info">
                            <span>Tipe: <span class="badge benefit">Benefit</span></span>
                            <span>Range: 1-5</span>
                        </div>
                    </div>

                    <div class="weight-card">
                        <div class="weight-card-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <div>
                                <h3>C2: Jarak Tempuh</h3>
                                <p>Jarak tempat tinggal ke kantor (km)</p>
                            </div>
                        </div>
                        <div class="weight-input-group">
                            <label>Bobot (W<sub>2</sub>):</label>
                            <div class="input-with-unit">
                                <input type="number" id="weightC2" class="weight-input" value="25" min="0" max="100">
                                <span class="input-unit">%</span>
                            </div>
                        </div>
                        <div class="weight-info">
                            <span>Tipe: <span class="badge cost">Cost</span></span>
                            <span>Range: 1-5</span>
                        </div>
                    </div>

                    <div class="weight-card">
                        <div class="weight-card-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div>
                                <h3>C3: Komunikasi</h3>
                                <p>Kemampuan komunikasi dan presentasi</p>
                            </div>
                        </div>
                        <div class="weight-input-group">
                            <label>Bobot (W<sub>3</sub>):</label>
                            <div class="input-with-unit">
                                <input type="number" id="weightC3" class="weight-input" value="25" min="0" max="100">
                                <span class="input-unit">%</span>
                            </div>
                        </div>
                        <div class="weight-info">
                            <span>Tipe: <span class="badge benefit">Benefit</span></span>
                            <span>Range: 1-5</span>
                        </div>
                    </div>

                    <div class="weight-card">
                        <div class="weight-card-header">
                            <div class="weight-icon" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                            <div>
                                <h3>C4: Fleksibilitas</h3>
                                <p>Fleksibilitas waktu kerja</p>
                            </div>
                        </div>
                        <div class="weight-input-group">
                            <label>Bobot (W<sub>4</sub>):</label>
                            <div class="input-with-unit">
                                <input type="number" id="weightC4" class="weight-input" value="20" min="0" max="100">
                                <span class="input-unit">%</span>
                            </div>
                        </div>
                        <div class="weight-info">
                            <span>Tipe: <span class="badge benefit">Benefit</span></span>
                            <span>Range: 1-5</span>
                        </div>
                    </div>
                </div>

                <!-- Total Bobot -->
                <div class="weight-total">
                    <div class="total-card">
                        <h3>Total Bobot</h3>
                        <div class="total-value">
                            <span id="totalWeightValue">100</span>
                            <span class="total-unit">%</span>
                        </div>
                        <div class="total-status" id="weightStatus">
                            <span class="status-badge valid">
                                <ion-icon name="checkmark-circle-outline"></ion-icon>
                                Valid
                            </span>
                        </div>
                        <div class="total-info">
                            <p>Total bobot harus tepat 100%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rumus Bobot -->
            <div class="formula-section">
                <h3><ion-icon name="calculator-outline"></ion-icon> Rumus Bobot Kriteria</h3>
                <div class="formula-content">
                    <p><strong>Vektor Bobot (W):</strong> Menyimpan bobot untuk setiap kriteria</p>
                    <div class="formula-box">
                        <code>W = [w<sub>1</sub>, w<sub>2</sub>, w<sub>3</sub>, w<sub>4</sub>]</code>
                        <p>Dimana:</p>
                        <ul>
                            <li>w<sub>j</sub> = Bobot kriteria ke-j</li>
                            <li>∑w<sub>j</sub> = 1 (100%)</li>
                            <li>0 ≤ w<sub>j</sub> ≤ 1</li>
                        </ul>
                        <p>Normalisasi bobot: w<sub>j</sub>' = w<sub>j</sub> / ∑w<sub>j</sub></p>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn btn-outline-secondary" id="btnBackStep3">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </button>
                <button class="btn btn-secondary" id="btnNextStep3">
                    Lanjut ke Normalisasi <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </div>
        </section>

        <!-- Step 4: Normalisasi Matriks -->
        <section id="step4" class="calculation-step">
            <div class="step-header">
                <h2><ion-icon name="stats-chart-outline"></ion-icon> Normalisasi Matriks (R)</h2>
                <p class="step-description">Proses normalisasi matriks keputusan untuk menyamakan skala</p>
            </div>

            <!-- Tabel Normalisasi -->
            <div class="normalization-container">
                <table class="normalization-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 80px;">Alt</th>
                            <th rowspan="2" style="width: 150px;">Nama</th>
                            <th colspan="4" class="criteria-header">Kriteria Ternormalisasi (R<sub>ij</sub>)</th>
                            <th rowspan="2" style="width: 120px;">Proses</th>
                        </tr>
                        <tr>
                            <th style="width: 150px;">C1 (Pengalaman)</th>
                            <th style="width: 150px;">C2 (Jarak)</th>
                            <th style="width: 150px;">C3 (Komunikasi)</th>
                            <th style="width: 150px;">C4 (Fleksibilitas)</th>
                        </tr>
                    </thead>
                    <tbody id="normalizationTableBody">
                        <!-- Data normalisasi akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Rumus Normalisasi -->
            <div class="formula-section">
                <h3><ion-icon name="calculator-outline"></ion-icon> Rumus Normalisasi</h3>
                <div class="formula-content">
                    <div class="formula-grid">
                        <div class="formula-card">
                            <h4>Kriteria Benefit</h4>
                            <div class="formula-box">
                                <code>r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>j</sub>)</code>
                                <p>Untuk kriteria yang ingin dimaksimalkan</p>
                                <ul>
                                    <li>Pengalaman (C1)</li>
                                    <li>Komunikasi (C3)</li>
                                    <li>Fleksibilitas (C4)</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="formula-card">
                            <h4>Kriteria Cost</h4>
                            <div class="formula-box">
                                <code>r<sub>ij</sub> = min(x<sub>j</sub>) / x<sub>ij</sub></code>
                                <p>Untuk kriteria yang ingin diminimalkan</p>
                                <ul>
                                    <li>Jarak (C2)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn btn-outline-secondary" id="btnBackStep4">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </button>
                <button class="btn btn-secondary" id="btnNextStep4">
                    Lanjut ke Nilai Utility <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </div>
        </section>

        <!-- Step 5: Nilai Utility -->
        <section id="step5" class="calculation-step">
            <div class="step-header">
                <h2><ion-icon name="flash-outline"></ion-icon> Nilai Utility (V)</h2>
                <p class="step-description">Perhitungan nilai utility dengan mengalikan matriks ternormalisasi dengan bobot</p>
            </div>

            <!-- Tabel Nilai Utility -->
            <div class="utility-container">
                <table class="utility-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 80px;">Alt</th>
                            <th rowspan="2" style="width: 150px;">Nama</th>
                            <th colspan="4" class="criteria-header">Nilai Utility (V<sub>ij</sub> = r<sub>ij</sub> × w<sub>j</sub>)</th>
                            <th rowspan="2" style="width: 120px;">Total (V<sub>i</sub>)</th>
                        </tr>
                        <tr>
                            <th style="width: 150px;">C1 × W1</th>
                            <th style="width: 150px;">C2 × W2</th>
                            <th style="width: 150px;">C3 × W3</th>
                            <th style="width: 150px;">C4 × W4</th>
                        </tr>
                    </thead>
                    <tbody id="utilityTableBody">
                        <!-- Data utility akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Rumus Utility -->
            <div class="formula-section">
                <h3><ion-icon name="calculator-outline"></ion-icon> Rumus Nilai Utility</h3>
                <div class="formula-content">
                    <div class="formula-box">
                        <code>V<sub>ij</sub> = r<sub>ij</sub> × w<sub>j</sub></code>
                        <p>Dimana:</p>
                        <ul>
                            <li>V<sub>ij</sub> = Nilai utility alternatif i pada kriteria j</li>
                            <li>r<sub>ij</sub> = Nilai ternormalisasi</li>
                            <li>w<sub>j</sub> = Bobot kriteria j</li>
                        </ul>
                        
                        <p><strong>Total Nilai Utility:</strong></p>
                        <code>V<sub>i</sub> = ∑(V<sub>ij</sub>) untuk j = 1 sampai n</code>
                        <p>V<sub>i</sub> = V<sub>i1</sub> + V<sub>i2</sub> + V<sub>i3</sub> + V<sub>i4</sub></p>
                    </div>
                </div>
            </div>

            <div class="step-actions">
                <button class="btn btn-outline-secondary" id="btnBackStep5">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali
                </button>
                <button class="btn btn-secondary" id="btnNextStep5">
                    Lihat Hasil Akhir <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </div>
        </section>

        <!-- Step 6: Hasil Akhir -->
        <section id="step6" class="calculation-step">
            <div class="step-header">
                <h2><ion-icon name="trophy-outline"></ion-icon> Hasil Akhir & Ranking</h2>
                <p class="step-description">Rangking alternatif berdasarkan total nilai utility</p>
            </div>

            <!-- Tabel Hasil Akhir -->
            <div class="results-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Rank</th>
                            <th>Nama Kandidat</th>
                            <th style="width: 120px;">Total Utility (V<sub>i</sub>)</th>
                            <th style="width: 150px;">Detail Perhitungan</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="resultsTableBody">
                        <!-- Data hasil akan diisi oleh JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Visualisasi Hasil -->
            <div class="visualization-section">
                <div class="chart-container">
                    <div class="chart-card">
                        <h3><ion-icon name="bar-chart-outline"></ion-icon> Perbandingan Total Utility</h3>
                        <div class="chart-wrapper">
                            <canvas id="utilityChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="summary-card">
                        <h3><ion-icon name="information-circle-outline"></ion-icon> Ringkasan Hasil</h3>
                        <div class="summary-content">
                            <div class="summary-item">
                                <span class="label">Total Alternatif:</span>
                                <span class="value" id="finalTotalAlternatives">0</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Nilai Tertinggi:</span>
                                <span class="value" id="finalHighestScore">0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Nilai Terendah:</span>
                                <span class="value" id="finalLowestScore">0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Rata-rata:</span>
                                <span class="value" id="finalAverageScore">0.00</span>
                            </div>
                            <div class="summary-item">
                                <span class="label">Direkomendasikan:</span>
                                <span class="value" id="finalRecommended">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi Akhir -->
            <div class="final-actions">
                <button class="btn btn-outline-secondary" id="btnBackStep6">
                    <ion-icon name="arrow-back-outline"></ion-icon> Kembali ke Utility
                </button>
                <button class="btn btn-primary" id="btnSaveResults">
                    <ion-icon name="save-outline"></ion-icon> Simpan Hasil
                </button>
                <button class="btn btn-success" id="btnExportResults">
                    <ion-icon name="download-outline"></ion-icon> Export Hasil
                </button>
            </div>
        </section>
    </div>

    <!-- Modal Detail Perhitungan -->
    <div class="modal" id="calculationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Detail Perhitungan</h3>
                <button class="modal-close" id="modalClose">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Detail perhitungan akan diisi -->
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let alternatives = [];
    let selectedAlternatives = [];
    let criteria = {
        C1: { name: 'Pengalaman', type: 'benefit', weight: 30, min: null, max: null },
        C2: { name: 'Jarak', type: 'cost', weight: 25, min: null, max: null },
        C3: { name: 'Komunikasi', type: 'benefit', weight: 25, min: null, max: null },
        C4: { name: 'Fleksibilitas', type: 'benefit', weight: 20, min: null, max: null }
    };
    let normalizedMatrix = [];
    let utilityMatrix = [];
    let results = [];
    let currentStep = 1;

    // Initialize
    loadAlternatives();
    initializeEventListeners();
    setupCharts();

    // Load alternatives data
    function loadAlternatives() {
        // Try to load from localStorage or generate sample data
        const savedAlternatives = localStorage.getItem('alternativesData');
        
        if (savedAlternatives) {
            try {
                const data = JSON.parse(savedAlternatives);
                alternatives = data;
            } catch (e) {
                console.error('Error loading alternatives:', e);
                generateSampleAlternatives();
            }
        } else {
            generateSampleAlternatives();
        }
        
        selectedAlternatives = [...alternatives.filter(alt => alt.status === 'active')];
        updateStep1();
    }

    function generateSampleAlternatives() {
        const sampleData = [
            { id: 'K001', name: 'Ahmad Santoso', position: 'Software Developer', date: '2024-01-15', status: 'active', scores: { C1: 4, C2: 3, C3: 5, C4: 4 } },
            { id: 'K002', name: 'Budi Setiawan', position: 'Data Analyst', date: '2024-01-16', status: 'active', scores: { C1: 3, C2: 4, C3: 4, C4: 3 } },
            { id: 'K003', name: 'Citra Lestari', position: 'Project Manager', date: '2024-01-17', status: 'active', scores: { C1: 5, C2: 2, C3: 3, C4: 5 } },
            { id: 'K004', name: 'Dewi Anggraini', position: 'UI/UX Designer', date: '2024-01-18', status: 'active', scores: { C1: 4, C2: 5, C3: 4, C4: 3 } },
            { id: 'K005', name: 'Eko Prasetyo', position: 'Backend Developer', date: '2024-01-19', status: 'active', scores: { C1: 3, C2: 3, C3: 3, C4: 4 } },
            { id: 'K006', name: 'Fitriani Sari', position: 'Frontend Developer', date: '2024-01-20', status: 'inactive', scores: { C1: 5, C2: 4, C3: 5, C4: 4 } },
            { id: 'K007', name: 'Gunawan Wijaya', position: 'System Analyst', date: '2024-01-21', status: 'active', scores: { C1: 4, C2: 3, C3: 4, C4: 3 } },
            { id: 'K008', name: 'Hana Putri', position: 'QA Engineer', date: '2024-01-22', status: 'active', scores: { C1: 3, C2: 2, C3: 5, C4: 4 } }
        ];
        
        alternatives = sampleData;
    }

    // Update Step 1: Data Alternatif
    function updateStep1() {
        const tableBody = document.getElementById('alternativesTable');
        tableBody.innerHTML = '';
        
        alternatives.forEach((alt, index) => {
            const isSelected = selectedAlternatives.some(selected => selected.id === alt.id);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>
                    <div class="candidate-info">
                        <div class="candidate-name">${alt.name}</div>
                        <small class="candidate-id">${alt.id}</small>
                    </div>
                </td>
                <td>${alt.id}</td>
                <td>${alt.position}</td>
                <td>${formatDate(alt.date)}</td>
                <td>
                    <span class="status-badge ${alt.status === 'active' ? 'active' : 'inactive'}">
                        ${alt.status === 'active' ? 'Aktif' : 'Non-aktif'}
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn select-btn ${isSelected ? 'selected' : ''}" 
                                data-id="${alt.id}" 
                                ${alt.status !== 'active' ? 'disabled' : ''}>
                            <ion-icon name="${isSelected ? 'checkbox' : 'square-outline'}"></ion-icon>
                        </button>
                        <button class="action-btn view-btn" data-id="${alt.id}">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update summary
        document.getElementById('totalAlternatives').textContent = alternatives.length;
        document.getElementById('activeAlternatives').textContent = alternatives.filter(a => a.status === 'active').length;
        document.getElementById('selectedAlternatives').textContent = selectedAlternatives.length;
        
        // Add event listeners
        tableBody.querySelectorAll('.select-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const altId = this.getAttribute('data-id');
                toggleSelectAlternative(altId);
            });
        });
        
        tableBody.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const altId = this.getAttribute('data-id');
                viewAlternativeDetails(altId);
            });
        });
    }

    function toggleSelectAlternative(altId) {
        const alt = alternatives.find(a => a.id === altId);
        if (!alt || alt.status !== 'active') return;
        
        const index = selectedAlternatives.findIndex(a => a.id === altId);
        if (index > -1) {
            // Remove from selection
            selectedAlternatives.splice(index, 1);
        } else {
            // Add to selection
            selectedAlternatives.push(alt);
        }
        
        updateStep1();
    }

    // Step navigation
    function showStep(stepNumber) {
        // Hide all steps
        document.querySelectorAll('.calculation-step').forEach(step => {
            step.classList.remove('active');
        });
        
        // Remove active class from all steps
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active');
        });
        
        // Show selected step
        document.getElementById(`step${stepNumber}`).classList.add('active');
        document.querySelector(`.step[data-step="${stepNumber}"]`).classList.add('active');
        
        currentStep = stepNumber;
        
        // Update step-specific content
        switch(stepNumber) {
            case 2:
                updateStep2();
                break;
            case 3:
                updateStep3();
                break;
            case 4:
                updateStep4();
                break;
            case 5:
                updateStep5();
                break;
            case 6:
                updateStep6();
                break;
        }
    }

    // Step 2: Matriks Keputusan
    function updateStep2() {
        if (selectedAlternatives.length === 0) {
            alert('Pilih minimal 1 alternatif terlebih dahulu!');
            showStep(1);
            return;
        }
        
        const tableBody = document.getElementById('matrixTableBody');
        tableBody.innerHTML = '';
        
        // Reset min/max values
        Object.keys(criteria).forEach(key => {
            criteria[key].min = null;
            criteria[key].max = null;
        });
        
        // Calculate min and max for each criteria
        selectedAlternatives.forEach(alt => {
            Object.keys(criteria).forEach(key => {
                const value = alt.scores[key];
                if (criteria[key].min === null || value < criteria[key].min) {
                    criteria[key].min = value;
                }
                if (criteria[key].max === null || value > criteria[key].max) {
                    criteria[key].max = value;
                }
            });
        });
        
        // Populate table
        selectedAlternatives.forEach((alt, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>A${index + 1}</td>
                <td>${alt.name}</td>
                <td class="score-cell">${alt.scores.C1}</td>
                <td class="score-cell">${alt.scores.C2}</td>
                <td class="score-cell">${alt.scores.C3}</td>
                <td class="score-cell">${alt.scores.C4}</td>
                <td>Alternatif</td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update min/max display
        document.getElementById('minC1').textContent = criteria.C1.min;
        document.getElementById('minC2').textContent = criteria.C2.min;
        document.getElementById('minC3').textContent = criteria.C3.min;
        document.getElementById('minC4').textContent = criteria.C4.min;
        
        document.getElementById('maxC1').textContent = criteria.C1.max;
        document.getElementById('maxC2').textContent = criteria.C2.max;
        document.getElementById('maxC3').textContent = criteria.C3.max;
        document.getElementById('maxC4').textContent = criteria.C4.max;
    }

    // Step 3: Bobot Kriteria
    function updateStep3() {
        // Update weight inputs with current values
        document.getElementById('weightC1').value = criteria.C1.weight;
        document.getElementById('weightC2').value = criteria.C2.weight;
        document.getElementById('weightC3').value = criteria.C3.weight;
        document.getElementById('weightC4').value = criteria.C4.weight;
        
        updateWeightTotal();
    }

    function updateWeightTotal() {
        const total = criteria.C1.weight + criteria.C2.weight + criteria.C3.weight + criteria.C4.weight;
        document.getElementById('totalWeightValue').textContent = total;
        
        const statusElement = document.getElementById('weightStatus');
        if (total === 100) {
            statusElement.innerHTML = `
                <span class="status-badge valid">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    Valid
                </span>
            `;
        } else {
            statusElement.innerHTML = `
                <span class="status-badge invalid">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                    Perlu Penyesuaian
                </span>
            `;
        }
    }

    // Step 4: Normalisasi Matriks
    function updateStep4() {
        calculateNormalization();
        const tableBody = document.getElementById('normalizationTableBody');
        tableBody.innerHTML = '';
        
        selectedAlternatives.forEach((alt, index) => {
            const norm = normalizedMatrix[index];
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>A${index + 1}</td>
                <td>${alt.name}</td>
                <td>
                    <div class="normalized-value">
                        <div class="value-display">${norm.C1.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${alt.scores.C1} / ${criteria.C1.max}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="normalized-value">
                        <div class="value-display">${norm.C2.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${criteria.C2.min} / ${alt.scores.C2}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="normalized-value">
                        <div class="value-display">${norm.C3.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${alt.scores.C3} / ${criteria.C3.max}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="normalized-value">
                        <div class="value-display">${norm.C4.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${alt.scores.C4} / ${criteria.C4.max}
                        </div>
                    </div>
                </td>
                <td>
                    <button class="btn-detail" data-index="${index}">
                        <ion-icon name="information-circle-outline"></ion-icon>
                        Detail
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Add event listeners for detail buttons
        tableBody.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                showNormalizationDetail(index);
            });
        });
    }

    function calculateNormalization() {
        normalizedMatrix = [];
        
        selectedAlternatives.forEach(alt => {
            const norm = {
                C1: alt.scores.C1 / criteria.C1.max,  // Benefit
                C2: criteria.C2.min / alt.scores.C2,  // Cost
                C3: alt.scores.C3 / criteria.C3.max,  // Benefit
                C4: alt.scores.C4 / criteria.C4.max   // Benefit
            };
            normalizedMatrix.push(norm);
        });
    }

    // Step 5: Nilai Utility
    function updateStep5() {
        calculateUtility();
        const tableBody = document.getElementById('utilityTableBody');
        tableBody.innerHTML = '';
        
        selectedAlternatives.forEach((alt, index) => {
            const utility = utilityMatrix[index];
            const total = utility.total;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>A${index + 1}</td>
                <td>${alt.name}</td>
                <td>
                    <div class="utility-value">
                        <div class="value-display">${utility.C1.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${normalizedMatrix[index].C1.toFixed(4)} × ${(criteria.C1.weight/100).toFixed(2)}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="utility-value">
                        <div class="value-display">${utility.C2.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${normalizedMatrix[index].C2.toFixed(4)} × ${(criteria.C2.weight/100).toFixed(2)}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="utility-value">
                        <div class="value-display">${utility.C3.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${normalizedMatrix[index].C3.toFixed(4)} × ${(criteria.C3.weight/100).toFixed(2)}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="utility-value">
                        <div class="value-display">${utility.C4.toFixed(4)}</div>
                        <div class="formula-preview">
                            ${normalizedMatrix[index].C4.toFixed(4)} × ${(criteria.C4.weight/100).toFixed(2)}
                        </div>
                    </div>
                </td>
                <td class="total-utility">
                    <div class="total-value">${total.toFixed(4)}</div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function calculateUtility() {
        utilityMatrix = [];
        
        normalizedMatrix.forEach((norm, index) => {
            const utility = {
                C1: norm.C1 * (criteria.C1.weight / 100),
                C2: norm.C2 * (criteria.C2.weight / 100),
                C3: norm.C3 * (criteria.C3.weight / 100),
                C4: norm.C4 * (criteria.C4.weight / 100)
            };
            utility.total = utility.C1 + utility.C2 + utility.C3 + utility.C4;
            utilityMatrix.push(utility);
        });
    }

    // Step 6: Hasil Akhir
    function updateStep6() {
        calculateResults();
        updateResultsTable();
        updateVisualization();
    }

    function calculateResults() {
        results = selectedAlternatives.map((alt, index) => {
            const utility = utilityMatrix[index];
            return {
                ...alt,
                utility: utility,
                totalUtility: utility.total,
                ranking: 0
            };
        });
        
        // Sort by total utility (descending)
        results.sort((a, b) => b.totalUtility - a.totalUtility);
        
        // Assign ranking
        results.forEach((result, index) => {
            result.ranking = index + 1;
        });
    }

    function updateResultsTable() {
        const tableBody = document.getElementById('resultsTableBody');
        tableBody.innerHTML = '';
        
        results.forEach((result, index) => {
            const row = document.createElement('tr');
            row.className = `rank-${result.ranking}`;
            
            row.innerHTML = `
                <td>
                    <div class="rank-badge rank-${result.ranking}">${result.ranking}</div>
                </td>
                <td>
                    <div class="result-candidate">
                        <strong>${result.name}</strong>
                        <small>${result.id} - ${result.position}</small>
                    </div>
                </td>
                <td class="total-score">
                    <div class="score-value">${result.totalUtility.toFixed(4)}</div>
                    <div class="score-percentage">${(result.totalUtility * 100).toFixed(2)}%</div>
                </td>
                <td>
                    <div class="calculation-preview">
                        <small>V = ${result.utility.C1.toFixed(4)} + ${result.utility.C2.toFixed(4)} + ${result.utility.C3.toFixed(4)} + ${result.utility.C4.toFixed(4)}</small>
                    </div>
                </td>
                <td>
                    <span class="status-badge ${result.totalUtility >= 0.7 ? 'recommended' : result.totalUtility >= 0.5 ? 'qualified' : 'needs-review'}">
                        ${getResultStatus(result.totalUtility)}
                    </span>
                </td>
                <td>
                    <button class="btn-detail" data-id="${result.id}">
                        <ion-icon name="calculator-outline"></ion-icon>
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Add event listeners
        tableBody.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                const resultId = this.getAttribute('data-id');
                showResultDetails(resultId);
            });
        });
        
        // Update summary
        const total = results.length;
        const highest = Math.max(...results.map(r => r.totalUtility));
        const lowest = Math.min(...results.map(r => r.totalUtility));
        const average = results.reduce((sum, r) => sum + r.totalUtility, 0) / total;
        const recommended = results.filter(r => r.totalUtility >= 0.7).length;
        
        document.getElementById('finalTotalAlternatives').textContent = total;
        document.getElementById('finalHighestScore').textContent = highest.toFixed(4);
        document.getElementById('finalLowestScore').textContent = lowest.toFixed(4);
        document.getElementById('finalAverageScore').textContent = average.toFixed(4);
        document.getElementById('finalRecommended').textContent = recommended;
    }

    function updateVisualization() {
        const ctx = document.getElementById('utilityChart').getContext('2d');
        
        if (window.utilityChartInstance) {
            window.utilityChartInstance.destroy();
        }
        
        window.utilityChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: results.map(r => r.name),
                datasets: [{
                    label: 'Total Utility',
                    data: results.map(r => r.totalUtility),
                    backgroundColor: results.map((r, i) => {
                        if (i === 0) return '#FFD700';
                        if (i === 1) return '#C0C0C0';
                        if (i === 2) return '#CD7F32';
                        return '#007bff';
                    }),
                    borderColor: results.map((r, i) => {
                        if (i === 0) return '#FFA500';
                        if (i === 1) return '#A9A9A9';
                        if (i === 2) return '#8B4513';
                        return '#0056b3';
                    }),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 1,
                        title: {
                            display: true,
                            text: 'Nilai Utility'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Kandidat'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Utility: ${context.parsed.y.toFixed(4)}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Helper functions
    function formatDate(dateString) {
        const options = { day: 'numeric', month: 'short', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function getResultStatus(score) {
        if (score >= 0.7) return 'Direkomendasikan';
        if (score >= 0.5) return 'Memenuhi Syarat';
        return 'Perlu Pertimbangan';
    }

    // Modal functions
    function showNormalizationDetail(index) {
        const alt = selectedAlternatives[index];
        const norm = normalizedMatrix[index];
        
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <h4>Detail Normalisasi: ${alt.name}</h4>
            
            <div class="detail-grid">
                <div class="detail-card">
                    <h5>Pengalaman (C1) - Benefit</h5>
                    <div class="formula-detail">
                        <code>r<sub>11</sub> = x<sub>11</sub> / max(x<sub>1</sub>)</code>
                        <p>= ${alt.scores.C1} / ${criteria.C1.max}</p>
                        <p>= ${norm.C1.toFixed(4)}</p>
                    </div>
                </div>
                
                <div class="detail-card">
                    <h5>Jarak (C2) - Cost</h5>
                    <div class="formula-detail">
                        <code>r<sub>12</sub> = min(x<sub>2</sub>) / x<sub>12</sub></code>
                        <p>= ${criteria.C2.min} / ${alt.scores.C2}</p>
                        <p>= ${norm.C2.toFixed(4)}</p>
                    </div>
                </div>
                
                <div class="detail-card">
                    <h5>Komunikasi (C3) - Benefit</h5>
                    <div class="formula-detail">
                        <code>r<sub>13</sub> = x<sub>13</sub> / max(x<sub>3</sub>)</code>
                        <p>= ${alt.scores.C3} / ${criteria.C3.max}</p>
                        <p>= ${norm.C3.toFixed(4)}</p>
                    </div>
                </div>
                
                <div class="detail-card">
                    <h5>Fleksibilitas (C4) - Benefit</h5>
                    <div class="formula-detail">
                        <code>r<sub>14</sub> = x<sub>14</sub> / max(x<sub>4</sub>)</code>
                        <p>= ${alt.scores.C4} / ${criteria.C4.max}</p>
                        <p>= ${norm.C4.toFixed(4)}</p>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modalTitle').textContent = `Normalisasi: ${alt.name}`;
        document.getElementById('calculationModal').style.display = 'block';
    }

    function showResultDetails(resultId) {
        const result = results.find(r => r.id === resultId);
        if (!result) return;
        
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <h4>Detail Perhitungan: ${result.name}</h4>
            <div class="result-summary">
                <div class="summary-card">
                    <h5>Ranking: #${result.ranking}</h5>
                    <div class="total-score-large">${result.totalUtility.toFixed(4)}</div>
                    <p>${(result.totalUtility * 100).toFixed(2)}% dari nilai maksimal</p>
                </div>
            </div>
            
            <h5>Detail Perhitungan:</h5>
            <div class="calculation-detail">
                <p><strong>Nilai Utility per Kriteria:</strong></p>
                <ul>
                    <li>Pengalaman: ${normalizedMatrix[results.indexOf(result)].C1.toFixed(4)} × ${(criteria.C1.weight/100).toFixed(2)} = ${result.utility.C1.toFixed(4)}</li>
                    <li>Jarak: ${normalizedMatrix[results.indexOf(result)].C2.toFixed(4)} × ${(criteria.C2.weight/100).toFixed(2)} = ${result.utility.C2.toFixed(4)}</li>
                    <li>Komunikasi: ${normalizedMatrix[results.indexOf(result)].C3.toFixed(4)} × ${(criteria.C3.weight/100).toFixed(2)} = ${result.utility.C3.toFixed(4)}</li>
                    <li>Fleksibilitas: ${normalizedMatrix[results.indexOf(result)].C4.toFixed(4)} × ${(criteria.C4.weight/100).toFixed(2)} = ${result.utility.C4.toFixed(4)}</li>
                </ul>
                
                <p><strong>Total Utility:</strong></p>
                <div class="formula-box">
                    <code>V = ${result.utility.C1.toFixed(4)} + ${result.utility.C2.toFixed(4)} + ${result.utility.C3.toFixed(4)} + ${result.utility.C4.toFixed(4)} = ${result.totalUtility.toFixed(4)}</code>
                </div>
            </div>
        `;
        
        document.getElementById('modalTitle').textContent = `Perhitungan: ${result.name}`;
        document.getElementById('calculationModal').style.display = 'block';
    }

    function viewAlternativeDetails(altId) {
        const alt = alternatives.find(a => a.id === altId);
        if (!alt) return;
        
        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <h4>Detail Alternatif: ${alt.name}</h4>
            
            <div class="alternative-details">
                <div class="detail-row">
                    <span class="label">ID:</span>
                    <span class="value">${alt.id}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Nama:</span>
                    <span class="value">${alt.name}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Posisi:</span>
                    <span class="value">${alt.position}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Tanggal Daftar:</span>
                    <span class="value">${formatDate(alt.date)}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="status-badge ${alt.status === 'active' ? 'active' : 'inactive'}">
                            ${alt.status === 'active' ? 'Aktif' : 'Non-aktif'}
                        </span>
                    </span>
                </div>
            </div>
            
            <h5>Nilai Kriteria:</h5>
            <div class="scores-grid">
                <div class="score-card">
                    <span class="score-label">Pengalaman</span>
                    <div class="score-value">${alt.scores.C1}/5</div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${(alt.scores.C1/5)*100}%"></div>
                    </div>
                </div>
                <div class="score-card">
                    <span class="score-label">Jarak</span>
                    <div class="score-value">${alt.scores.C2}/5</div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${(alt.scores.C2/5)*100}%"></div>
                    </div>
                </div>
                <div class="score-card">
                    <span class="score-label">Komunikasi</span>
                    <div class="score-value">${alt.scores.C3}/5</div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${(alt.scores.C3/5)*100}%"></div>
                    </div>
                </div>
                <div class="score-card">
                    <span class="score-label">Fleksibilitas</span>
                    <div class="score-value">${alt.scores.C4}/5</div>
                    <div class="score-bar">
                        <div class="score-fill" style="width: ${(alt.scores.C4/5)*100}%"></div>
                    </div>
                </div>
            </div>
        `;
        
        document.getElementById('modalTitle').textContent = `Detail Alternatif`;
        document.getElementById('calculationModal').style.display = 'block';
    }

    // Initialize event listeners
    function initializeEventListeners() {
        // Step navigation buttons
        document.getElementById('btnNextStep1').addEventListener('click', () => showStep(2));
        document.getElementById('btnNextStep2').addEventListener('click', () => showStep(3));
        document.getElementById('btnNextStep3').addEventListener('click', () => showStep(4));
        document.getElementById('btnNextStep4').addEventListener('click', () => showStep(5));
        document.getElementById('btnNextStep5').addEventListener('click', () => showStep(6));
        
        document.getElementById('btnBackStep2').addEventListener('click', () => showStep(1));
        document.getElementById('btnBackStep3').addEventListener('click', () => showStep(2));
        document.getElementById('btnBackStep4').addEventListener('click', () => showStep(3));
        document.getElementById('btnBackStep5').addEventListener('click', () => showStep(4));
        document.getElementById('btnBackStep6').addEventListener('click', () => showStep(5));
        
        // Select all button
        document.getElementById('btnSelectAll').addEventListener('click', function() {
            if (selectedAlternatives.length === alternatives.filter(a => a.status === 'active').length) {
                // Deselect all
                selectedAlternatives = [];
            } else {
                // Select all active
                selectedAlternatives = alternatives.filter(a => a.status === 'active');
            }
            updateStep1();
        });
        
        // Weight inputs
        document.querySelectorAll('.weight-input').forEach(input => {
            input.addEventListener('input', function() {
                const criteriaId = this.id.replace('weight', '');
                criteria[criteriaId].weight = parseInt(this.value) || 0;
                updateWeightTotal();
            });
        });
        
        // Process button
        document.getElementById('btnProses').addEventListener('click', function() {
            // Validate weight total
            const totalWeight = criteria.C1.weight + criteria.C2.weight + criteria.C3.weight + criteria.C4.weight;
            if (totalWeight !== 100) {
                alert('Total bobot harus tepat 100%! Silakan sesuaikan bobot kriteria.');
                showStep(3);
                return;
            }
            
            if (selectedAlternatives.length === 0) {
                alert('Pilih minimal 1 alternatif terlebih dahulu!');
                showStep(1);
                return;
            }
            
            // Start from step 2
            showStep(2);
        });
        
        // Save results
        document.getElementById('btnSaveResults').addEventListener('click', function() {
            const data = {
                timestamp: new Date().toISOString(),
                criteria: criteria,
                alternatives: selectedAlternatives,
                normalizedMatrix: normalizedMatrix,
                utilityMatrix: utilityMatrix,
                results: results
            };
            
            localStorage.setItem('calculationResults', JSON.stringify(data));
            alert('Hasil perhitungan berhasil disimpan!');
        });
        
        // Export results
        document.getElementById('btnExportResults').addEventListener('click', function() {
            exportToExcel();
        });
        
        // Refresh button
        document.getElementById('btnRefresh').addEventListener('click', function() {
            loadAlternatives();
            showStep(1);
        });
        
        // Modal close
        document.getElementById('modalClose').addEventListener('click', function() {
            document.getElementById('calculationModal').style.display = 'none';
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('calculationModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    function setupCharts() {
        // Chart setup for step 6 is handled in updateVisualization()
    }

    function exportToExcel() {
        const data = [
            ['Ranking', 'ID', 'Nama', 'Posisi', 'Pengalaman', 'Jarak', 'Komunikasi', 'Fleksibilitas', 'Total Utility', 'Status']
        ];
        
        results.forEach(result => {
            const status = getResultStatus(result.totalUtility);
            data.push([
                result.ranking,
                result.id,
                result.name,
                result.position,
                result.scores.C1,
                result.scores.C2,
                result.scores.C3,
                result.scores.C4,
                result.totalUtility.toFixed(4),
                status
            ]);
        });
        
        const csvContent = data.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `hasil-perhitungan-saw-${new Date().toISOString().slice(0, 10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        alert('Data berhasil diexport ke CSV/Excel!');
    }
});
</script>

<style>
/* Base Styles */
.calculation-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 20px;
}

.page-header {
    background: white;
    border-radius: 15px;
    padding: 25px 30px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
}

.header-content h1 {
    margin: 0;
    font-size: 28px;
    color: #333;
    font-weight: 700;
}

.page-subtitle {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 16px;
}

.header-actions {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

/* Calculation Steps Navigation */
.calculation-steps {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.steps-container {
    display: flex;
    justify-content: space-between;
    position: relative;
}

.steps-container::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 50px;
    right: 50px;
    height: 3px;
    background: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    opacity: 0.5;
    transition: all 0.3s ease;
}

.step.active {
    opacity: 1;
}

.step.active .step-number {
    background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: bold;
    color: #666;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.step-label {
    text-align: center;
}

.step-label span {
    display: block;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.step-label small {
    color: #666;
    font-size: 12px;
}

/* Calculation Steps Content */
.calculation-step {
    display: none;
    animation: fadeIn 0.5s ease-out;
}

.calculation-step.active {
    display: block;
}

.step-header {
    margin-bottom: 25px;
}

.step-header h2 {
    margin: 0 0 8px 0;
    color: #333;
    font-size: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.step-description {
    margin: 0;
    color: #666;
    font-size: 15px;
}

/* Data Table Styles */
.data-table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.data-table thead {
    background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);
}

.data-table th {
    padding: 18px 20px;
    color: white;
    font-weight: 600;
    text-align: left;
    font-size: 14px;
    white-space: nowrap;
}

.data-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

.data-table tbody tr:last-child {
    border-bottom: none;
}

.data-table td {
    padding: 18px 20px;
    vertical-align: middle;
}

.candidate-info {
    display: flex;
    flex-direction: column;
}

.candidate-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.candidate-id {
    color: #666;
    font-size: 12px;
}

/* Status Badges */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
    text-align: center;
    min-width: 80px;
}

.status-badge.active {
    background: #e7f9ed;
    color: #1b9d3f;
    border: 1px solid #c3e6cb;
}

.status-badge.inactive {
    background: #ffebee;
    color: #f44336;
    border: 1px solid #f5c6cb;
}

.status-badge.valid {
    background: #e7f9ed;
    color: #1b9d3f;
    border: 1px solid #c3e6cb;
}

.status-badge.invalid {
    background: #ffebee;
    color: #f44336;
    border: 1px solid #f5c6cb;
}

.status-badge.recommended {
    background: #e7f9ed;
    color: #1b9d3f;
    border: 1px solid #c3e6cb;
}

.status-badge.qualified {
    background: #fff4e5;
    color: #ff9800;
    border: 1px solid #ffeeba;
}

.status-badge.needs-review {
    background: #ffebee;
    color: #f44336;
    border: 1px solid #f5c6cb;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 2px solid #e9ecef;
    background: white;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-2px);
}

.action-btn.selected {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.action-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Data Summary */
.data-summary {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    min-width: 150px;
}

.summary-item .label {
    font-size: 14px;
    color: #666;
    margin-bottom: 8px;
}

.summary-item .value {
    font-size: 32px;
    font-weight: bold;
    color: #2196F3;
}

/* Step Actions */
.step-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

/* Matrix Controls */
.matrix-controls {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.control-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.control-group label {
    font-weight: 600;
    color: #333;
    white-space: nowrap;
}

.control-select {
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: white;
    color: #333;
    font-size: 14px;
    min-width: 150px;
    outline: none;
    cursor: pointer;
}

.control-select:focus {
    border-color: #007bff;
}

/* Matrix Table */
.matrix-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.matrix-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 800px;
}

.matrix-table th {
    padding: 15px;
    background: #f8f9fa;
    color: #333;
    font-weight: 600;
    text-align: center;
    border: 1px solid #e9ecef;
}

.matrix-table td {
    padding: 15px;
    text-align: center;
    border: 1px solid #e9ecef;
}

.criteria-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #333 !important; /* Warna hitam */
}

.score-cell {
    font-weight: bold;
    font-size: 18px;
    color: #333;
}

.matrix-summary td {
    font-weight: 600;
    background: #f8f9fa;
}

/* Badges for Criteria Type */
.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge.benefit {
    background: #e7f9ed;
    color: #1b9d3f;
}

.badge.cost {
    background: #ffebee;
    color: #f44336;
}

/* Formula Section */
.formula-section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.formula-section h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.formula-content {
    line-height: 1.6;
}

.formula-box {
    background: #f8f9fa;
    border-left: 4px solid #2196F3;
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
    overflow-x: auto;
}

.formula-box code {
    display: block;
    font-family: 'Courier New', monospace;
    font-size: 18px;
    color: #333;
    margin-bottom: 15px;
    background: white;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.formula-box p {
    margin: 10px 0;
    color: #666;
}

.formula-box ul {
    margin: 10px 0;
    padding-left: 20px;
    color: #666;
}

.formula-box li {
    margin-bottom: 5px;
}

/* Weight Cards */
.weight-container {
    margin-bottom: 30px;
}

.weight-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.weight-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.weight-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #2196F3;
}

.weight-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 20px;
}

.weight-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.weight-card-header h3 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 16px;
}

.weight-card-header p {
    margin: 0;
    color: #666;
    font-size: 13px;
}

.weight-input-group {
    margin-bottom: 15px;
}

.weight-input-group label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 14px;
}

.input-with-unit {
    display: flex;
    align-items: center;
    gap: 10px;
}

.weight-input {
    flex: 1;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
    color: #2196F3;
    outline: none;
    transition: all 0.3s ease;
}

.weight-input:focus {
    border-color: #2196F3;
    box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.1);
}

.input-unit {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    min-width: 30px;
}

.weight-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
    font-size: 12px;
    color: #666;
}

/* Weight Total */
.weight-total {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.total-card {
    text-align: center;
    max-width: 300px;
    margin: 0 auto;
}

.total-card h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
}

.total-value {
    font-size: 64px;
    font-weight: bold;
    color: #2196F3;
    margin-bottom: 20px;
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 5px;
}

.total-unit {
    font-size: 32px;
    color: #666;
}

.total-status {
    margin-bottom: 15px;
}

.total-info p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

/* Normalization & Utility Tables */
.normalization-container,
.utility-container,
.results-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    overflow-x: auto;
}

.normalization-table,
.utility-table,
.results-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.normalization-table th,
.utility-table th,
.results-table th {
    padding: 15px;
    background: #f8f9fa;
    color: #333;
    font-weight: 600;
    text-align: center;
    border: 1px solid #e9ecef;
}

.normalization-table td,
.utility-table td,
.results-table td {
    padding: 15px;
    text-align: center;
    border: 1px solid #e9ecef;
    vertical-align: middle;
}

/* Normalized and Utility Values */
.normalized-value,
.utility-value {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: center;
}

.value-display {
    font-weight: bold;
    font-size: 16px;
    color: #2196F3;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    min-width: 100px;
}

.formula-preview {
    font-size: 12px;
    color: #666;
    background: #f0f0f0;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
}

.total-utility {
    font-weight: bold;
}

.total-value {
    font-size: 20px;
    font-weight: bold;
    color: #2196F3;
}

/* Detail Buttons */
.btn-detail {
    padding: 8px 16px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    color: #666;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-detail:hover {
    background: #2196F3;
    color: white;
    border-color: #2196F3;
    transform: translateY(-2px);
}

/* Formula Grid */
.formula-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.formula-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid;
}

.formula-card:nth-child(1) {
    border-left-color: #4CAF50;
}

.formula-card:nth-child(2) {
    border-left-color: #F44336;
}

.formula-card h4 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 16px;
}

/* Results Table */
.rank-badge {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 16px;
    margin: 0 auto;
}

.rank-1 .rank-badge {
    background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
    box-shadow: 0 4px 12px rgba(255, 165, 0, 0.3);
}

.rank-2 .rank-badge {
    background: linear-gradient(135deg, #C0C0C0 0%, #A9A9A9 100%);
    box-shadow: 0 4px 12px rgba(169, 169, 169, 0.3);
}

.rank-3 .rank-badge {
    background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%);
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
}

.rank-other .rank-badge {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.result-candidate {
    text-align: left;
}

.result-candidate strong {
    display: block;
    margin-bottom: 4px;
    color: #333;
}

.result-candidate small {
    color: #666;
    font-size: 12px;
}

.total-score {
    text-align: center;
}

.score-value {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 4px;
}

.score-percentage {
    font-size: 12px;
    color: #666;
}

.calculation-preview {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    color: #666;
    background: #f8f9fa;
    padding: 8px;
    border-radius: 6px;
}

/* Visualization Section */
.visualization-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.chart-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

@media (max-width: 992px) {
    .chart-container {
        grid-template-columns: 1fr;
    }
}

.chart-card,
.summary-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
}

.chart-card h3,
.summary-card h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chart-wrapper {
    height: 300px;
    position: relative;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.summary-content .summary-item {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    min-width: auto;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-content .summary-item:last-child {
    border-bottom: none;
}

.summary-content .summary-item .label {
    margin-bottom: 0;
    font-size: 14px;
    color: #666;
}

.summary-content .summary-item .value {
    font-size: 18px;
    font-weight: bold;
    color: #2196F3;
}

/* Final Actions */
.final-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #e9ecef;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    overflow-y: auto;
    animation: fadeIn 0.3s ease-out;
}

.modal-content {
    background: white;
    border-radius: 15px;
    margin: 50px auto;
    max-width: 800px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: slideUp 0.3s ease-out;
}

.modal-header {
    padding: 25px 30px;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 20px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #333;
}

.modal-body {
    padding: 30px;
    max-height: 70vh;
    overflow-y: auto;
}

/* Detail Grid in Modal */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.detail-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #2196F3;
}

.detail-card h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 14px;
}

.formula-detail {
    font-family: 'Courier New', monospace;
    color: #666;
}

.formula-detail code {
    display: block;
    margin-bottom: 10px;
    font-size: 14px;
    background: white;
    padding: 8px;
    border-radius: 4px;
}

.formula-detail p {
    margin: 5px 0;
    font-size: 13px;
}

/* Alternative Details */
.alternative-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
}

.detail-row {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-row .label {
    font-size: 12px;
    color: #666;
}

.detail-row .value {
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.scores-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.score-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
}

.score-label {
    display: block;
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
}

.score-card .score-value {
    font-size: 24px;
    font-weight: bold;
    color: #2196F3;
    margin-bottom: 10px;
}

.score-bar {
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.score-fill {
    height: 100%;
    background: linear-gradient(90deg, #2196F3, #0D47A1);
    border-radius: 4px;
    transition: width 0.3s ease;
}

/* Result Summary in Modal */
.result-summary {
    margin-bottom: 30px;
}

.summary-card {
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    margin-bottom: 20px;
}

.summary-card h5 {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 16px;
}

.total-score-large {
    font-size: 48px;
    font-weight: bold;
    color: #2196F3;
    margin-bottom: 10px;
}

.calculation-detail {
    line-height: 1.6;
}

.calculation-detail ul {
    margin: 10px 0;
    padding-left: 20px;
    color: #666;
}

.calculation-detail li {
    margin-bottom: 8px;
    font-size: 14px;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .steps-container {
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }
    
    .steps-container::before {
        display: none;
    }
    
    .step {
        flex: 0 0 calc(33.333% - 20px);
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .step-actions,
    .final-actions {
        flex-direction: column;
        gap: 15px;
    }
    
    .step-actions button,
    .final-actions button {
        width: 100%;
    }
    
    .data-summary {
        flex-direction: column;
        gap: 15px;
    }
    
    .summary-item {
        min-width: auto;
    }
    
    .chart-container {
        grid-template-columns: 1fr;
    }
    
    .weight-cards {
        grid-template-columns: 1fr;
    }
    
    .formula-grid {
        grid-template-columns: 1fr;
    }
    
    .matrix-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .control-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .control-select {
        width: 100%;
    }
}

/* pencarian */
.filter-container {
    background: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.filter-header {
    margin-bottom: 20px;
}

.filter-header h3 {
    margin: 0;
    color: #333;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-weight: 600;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Search Box */
.search-box {
    display: flex;
    gap: 0;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.search-box:focus-within {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.search-box input {
    flex: 1;
    padding: 12px 15px;
    border: none;
    outline: none;
    font-size: 14px;
    color: #333;
}

.search-btn {
    padding: 0 20px;
    background: #007bff;
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #0056b3;
}

/* Filter Select */
.filter-select {
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    background: white;
    color: #333;
    font-size: 14px;
    outline: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

/* Date Range Inputs */
.date-range-inputs {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 10px;
    align-items: end;
}

.date-input {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.date-input label {
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.date-picker {
    padding: 10px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 14px;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
}

.date-picker:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
}

.btn-apply {
    padding: 10px 15px;
    background: #28a745;
    border: none;
    border-radius: 6px;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-apply:hover {
    background: #218838;
}

/* Active Filters */
.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.active-filter {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: #e7f9ed;
    border: 1px solid #c3e6cb;
    border-radius: 20px;
    font-size: 13px;
    color: #1b9d3f;
}

.active-filter strong {
    font-weight: 600;
}

.clear-filter {
    background: none;
    border: none;
    color: #1b9d3f;
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.clear-filter:hover {
    color: #f44336;
}

/* Filter Info in Step 1 */
.filter-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    padding: 15px 20px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.filter-info-content {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.filter-info-content ion-icon {
    font-size: 20px;
}

.btn-clear-filters {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 6px;
    padding: 8px 16px;
    color: white;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-clear-filters:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Date Display in Table */
.date-display {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 14px;
}

.date-display ion-icon {
    color: #007bff;
}
</style>
@endsection
@extends('layouts.app')

@section('title', 'Pengaturan Bobot Kriteria')
    
@section('content')
<link rel="stylesheet" href="css/dashboard/bobot.css">
<div class="bobot-settings-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="options-outline"></ion-icon>
            </div>
            <div>
                <h1>Pengaturan Bobot Kriteria</h1>
                <p class="page-subtitle">Atur bobot penilaian untuk sistem seleksi karyawan</p>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="btnSave">
                <ion-icon name="save-outline"></ion-icon> Simpan Perubahan
            </button>
        </div>
    </div>

    <!-- Navigasi Halaman -->
    {{-- <div class="page-navigation">
        <div class="nav-tabs">
            <a href="#pengaturan" class="nav-tab active">
                <ion-icon name="settings-outline"></ion-icon>
                Pengaturan Bobot
            </a>
            <a href="#riwayat" class="nav-tab">
                <ion-icon name="time-outline"></ion-icon>
                Riwayat Perubahan
            </a>
            <a href="#analisis" class="nav-tab">
                <ion-icon name="analytics-outline"></ion-icon>
                Analisis Dampak
            </a>
        </div>
        
        <div class="quick-actions">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="presetDropdown">
                    <ion-icon name="color-palette-outline"></ion-icon>
                    Preset
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-preset="default">
                        <ion-icon name="refresh-outline"></ion-icon> Default
                    </a>
                    <a class="dropdown-item" href="#" data-preset="experience-heavy">
                        <ion-icon name="trending-up-outline"></ion-icon> Fokus Pengalaman
                    </a>
                    <a class="dropdown-item" href="#" data-preset="balanced">
                        <ion-icon name="balance-outline"></ion-icon> Seimbang
                    </a>
                    <a class="dropdown-item" href="#" data-preset="communication-heavy">
                        <ion-icon name="chatbubble-outline"></ion-icon> Fokus Komunikasi
                    </a>
                </div>
            </div>
            
            <button class="btn btn-outline-secondary" id="btnResetAll">
                <ion-icon name="trash-outline"></ion-icon> Reset Semua
            </button>
        </div>
    </div> --}}

    <!-- Konten Utama -->
    <div class="page-content">
        <!-- Panel Kiri: Kontrol Bobot -->
        <div class="left-panel">
            <!-- Informasi Penting -->
            <div class="info-card">
                <div class="info-icon">
                    <ion-icon name="information-circle-outline"></ion-icon>
                </div>
                <div class="info-content">
                    <h3>Panduan Pengaturan Bobot</h3>
                    <p>Total bobot semua kriteria harus 100%. Sistem akan menyesuaikan bobot kriteria lain secara otomatis saat Anda melakukan perubahan.</p>
                </div>
            </div>

            <!-- Form Pengaturan Bobot -->
            <form id="formBobot" class="bobot-form">
                <div class="bobot-sections">
                    <!-- Kriteria 1: Pengalaman -->
                    <section class="bobot-section active" id="section-pengalaman">
                        <div class="section-header">
                            <div class="criteria-badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div class="section-title">
                                <h2>Pengalaman Kerja</h2>
                                <p class="section-description">Penilaian berdasarkan pengalaman kerja yang relevan</p>
                            </div>
                            <div class="section-indicator">
                                <span class="priority-badge">
                                    <ion-icon name="trophy-outline"></ion-icon>
                                    Prioritas Tertinggi
                                </span>
                                <span class="current-value" id="currentPengalaman">30%</span>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="control-group">
                                <label for="sliderPengalaman">
                                    <span>Atur Bobot</span>
                                    <span class="range-info">(10% - 50%)</span>
                                </label>
                                <div class="slider-container">
                                    <input type="range" 
                                           class="bobot-slider" 
                                           id="sliderPengalaman" 
                                           min="10" 
                                           max="50" 
                                           value="30" 
                                           step="1"
                                           data-criteria="pengalaman">
                                    <div class="slider-ticks">
                                        <span>10%</span>
                                        <span>30%</span>
                                        <span>50%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label>Input Manual</label>
                                <div class="input-controls">
                                    <button type="button" class="btn-decrement" data-criteria="pengalaman">
                                        <ion-icon name="remove-outline"></ion-icon>
                                    </button>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               id="inputPengalaman" 
                                               class="bobot-input" 
                                               min="10" 
                                               max="50" 
                                               value="30"
                                               data-criteria="pengalaman">
                                        <span class="input-suffix">%</span>
                                    </div>
                                    <button type="button" class="btn-increment" data-criteria="pengalaman">
                                        <ion-icon name="add-outline"></ion-icon>
                                    </button>
                                    <button type="button" class="btn-reset" data-criteria="pengalaman">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="criteria-details">
                                <h4><ion-icon name="information-circle-outline"></ion-icon> Detail Kriteria</h4>
                                <ul>
                                    <li>Diukur dalam tahun pengalaman kerja</li>
                                    <li>Relevansi dengan posisi yang dilamar</li>
                                    <li>Pengalaman di bidang yang sama lebih diutamakan</li>
                                    <li>Memiliki rentang bobot terluas (10-50%)</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Kriteria 2: Jarak -->
                    <section class="bobot-section" id="section-jarak">
                        <div class="section-header">
                            <div class="criteria-badge" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                                <ion-icon name="location-outline"></ion-icon>
                            </div>
                            <div class="section-title">
                                <h2>Jarak Tempat Tinggal</h2>
                                <p class="section-description">Penilaian berdasarkan jarak lokasi tinggal dengan kantor</p>
                            </div>
                            <div class="section-indicator">
                                <span class="current-value" id="currentJarak">25%</span>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="control-group">
                                <label for="sliderJarak">
                                    <span>Atur Bobot</span>
                                    <span class="range-info">(10% - 40%)</span>
                                </label>
                                <div class="slider-container">
                                    <input type="range" 
                                           class="bobot-slider" 
                                           id="sliderJarak" 
                                           min="10" 
                                           max="40" 
                                           value="25" 
                                           step="1"
                                           data-criteria="jarak">
                                    <div class="slider-ticks">
                                        <span>10%</span>
                                        <span>25%</span>
                                        <span>40%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label>Input Manual</label>
                                <div class="input-controls">
                                    <button type="button" class="btn-decrement" data-criteria="jarak">
                                        <ion-icon name="remove-outline"></ion-icon>
                                    </button>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               id="inputJarak" 
                                               class="bobot-input" 
                                               min="10" 
                                               max="40" 
                                               value="25"
                                               data-criteria="jarak">
                                        <span class="input-suffix">%</span>
                                    </div>
                                    <button type="button" class="btn-increment" data-criteria="jarak">
                                        <ion-icon name="add-outline"></ion-icon>
                                    </button>
                                    <button type="button" class="btn-reset" data-criteria="jarak">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="criteria-details">
                                <h4><ion-icon name="information-circle-outline"></ion-icon> Detail Kriteria</h4>
                                <ul>
                                    <li>Diukur dalam kilometer</li>
                                    <li>Semakin dekat jarak, nilai semakin tinggi</li>
                                    <li>Mempertimbangkan kemacetan dan akses transportasi</li>
                                    <li>Dapat mempengaruhi ketepatan waktu kerja</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Kriteria 3: Komunikasi -->
                    <section class="bobot-section" id="section-komunikasi">
                        <div class="section-header">
                            <div class="criteria-badge" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div class="section-title">
                                <h2>Kemampuan Komunikasi</h2>
                                <p class="section-description">Penilaian kemampuan komunikasi verbal dan non-verbal</p>
                            </div>
                            <div class="section-indicator">
                                <span class="current-value" id="currentKomunikasi">25%</span>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="control-group">
                                <label for="sliderKomunikasi">
                                    <span>Atur Bobot</span>
                                    <span class="range-info">(10% - 40%)</span>
                                </label>
                                <div class="slider-container">
                                    <input type="range" 
                                           class="bobot-slider" 
                                           id="sliderKomunikasi" 
                                           min="10" 
                                           max="40" 
                                           value="25" 
                                           step="1"
                                           data-criteria="komunikasi">
                                    <div class="slider-ticks">
                                        <span>10%</span>
                                        <span>25%</span>
                                        <span>40%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label>Input Manual</label>
                                <div class="input-controls">
                                    <button type="button" class="btn-decrement" data-criteria="komunikasi">
                                        <ion-icon name="remove-outline"></ion-icon>
                                    </button>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               id="inputKomunikasi" 
                                               class="bobot-input" 
                                               min="10" 
                                               max="40" 
                                               value="25"
                                               data-criteria="komunikasi">
                                        <span class="input-suffix">%</span>
                                    </div>
                                    <button type="button" class="btn-increment" data-criteria="komunikasi">
                                        <ion-icon name="add-outline"></ion-icon>
                                    </button>
                                    <button type="button" class="btn-reset" data-criteria="komunikasi">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="criteria-details">
                                <h4><ion-icon name="information-circle-outline"></ion-icon> Detail Kriteria</h4>
                                <ul>
                                    <li>Dinilai melalui tes komunikasi dan wawancara</li>
                                    <li>Meliputi kemampuan presentasi dan negosiasi</li>
                                    <li>Kemampuan mendengarkan dan memahami instruksi</li>
                                    <li>Komunikasi tertulis dan lisan</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Kriteria 4: Fleksibilitas -->
                    <section class="bobot-section" id="section-fleksibilitas">
                        <div class="section-header">
                            <div class="criteria-badge" style="background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                            <div class="section-title">
                                <h2>Fleksibilitas Kerja</h2>
                                <p class="section-description">Penilaian kemampuan adaptasi dan fleksibilitas waktu kerja</p>
                            </div>
                            <div class="section-indicator">
                                <span class="current-value" id="currentFleksibilitas">20%</span>
                            </div>
                        </div>
                        
                        <div class="section-body">
                            <div class="control-group">
                                <label for="sliderFleksibilitas">
                                    <span>Atur Bobot</span>
                                    <span class="range-info">(10% - 40%)</span>
                                </label>
                                <div class="slider-container">
                                    <input type="range" 
                                           class="bobot-slider" 
                                           id="sliderFleksibilitas" 
                                           min="10" 
                                           max="40" 
                                           value="20" 
                                           step="1"
                                           data-criteria="fleksibilitas">
                                    <div class="slider-ticks">
                                        <span>10%</span>
                                        <span>20%</span>
                                        <span>40%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label>Input Manual</label>
                                <div class="input-controls">
                                    <button type="button" class="btn-decrement" data-criteria="fleksibilitas">
                                        <ion-icon name="remove-outline"></ion-icon>
                                    </button>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               id="inputFleksibilitas" 
                                               class="bobot-input" 
                                               min="10" 
                                               max="40" 
                                               value="20"
                                               data-criteria="fleksibilitas">
                                        <span class="input-suffix">%</span>
                                    </div>
                                    <button type="button" class="btn-increment" data-criteria="fleksibilitas">
                                        <ion-icon name="add-outline"></ion-icon>
                                    </button>
                                    <button type="button" class="btn-reset" data-criteria="fleksibilitas">
                                        <ion-icon name="refresh-outline"></ion-icon>
                                        Reset
                                    </button>
                                </div>
                            </div>
                            
                            <div class="criteria-details">
                                <h4><ion-icon name="information-circle-outline"></ion-icon> Detail Kriteria</h4>
                                <ul>
                                    <li>Kesiapan kerja shift atau lembur</li>
                                    <li>Kemampuan adaptasi terhadap perubahan</li>
                                    <li>Fleksibilitas waktu dan tempat kerja</li>
                                    <li>Respon terhadap tekanan dan deadline</li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        </div>

        <!-- Panel Kanan: Ringkasan dan Visualisasi -->
        <div class="right-panel">
            <!-- Ringkasan Total -->
            <div class="summary-card">
                <div class="summary-header">
                    <h3><ion-icon name="calculator-outline"></ion-icon> Ringkasan Bobot</h3>
                    <div class="summary-status" id="totalStatus">
                        <span class="status-valid">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                            Valid
                        </span>
                    </div>
                </div>
                
                <div class="summary-content">
                    <div class="total-display">
                        <div class="total-label">Total Bobot</div>
                        <div class="total-value" id="totalBobot">100%</div>
                    </div>
                    
                    <div class="progress-summary">
                        <div class="progress-bar-large">
                            <div class="progress-fill" id="progressFill" style="width: 100%;"></div>
                        </div>
                        <div class="progress-labels">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    </div>
                    
                    <div class="distribution-info">
                        <h4>Distribusi Bobot</h4>
                        <div class="distribution-bars">
                            <div class="dist-bar" style="width: 30%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <span class="dist-label">Pengalaman</span>
                                <span class="dist-value">30%</span>
                            </div>
                            <div class="dist-bar" style="width: 25%; background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                                <span class="dist-label">Jarak</span>
                                <span class="dist-value">25%</span>
                            </div>
                            <div class="dist-bar" style="width: 25%; background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                                <span class="dist-label">Komunikasi</span>
                                <span class="dist-value">25%</span>
                            </div>
                            <div class="dist-bar" style="width: 20%; background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);">
                                <span class="dist-label">Fleksibilitas</span>
                                <span class="dist-value">20%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analisis Dampak -->
            <div class="impact-card">
                <div class="impact-header">
                    <h3><ion-icon name="analytics-outline"></ion-icon> Analisis Dampak</h3>
                    <span class="impact-update">Real-time</span>
                </div>
                
                <div class="impact-content">
                    <div class="impact-item">
                        <div class="impact-info">
                            <h4>Prioritas Tertinggi</h4>
                            <p>Kriteria dengan bobot tertinggi</p>
                        </div>
                        <div class="impact-value" id="highestCriteria">
                            <span class="criteria-tag" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                Pengalaman (30%)
                            </span>
                        </div>
                    </div>
                    
                    <div class="impact-item">
                        <div class="impact-info">
                            <h4>Pengaruh pada Seleksi</h4>
                            <p>Kriteria akan lebih menentukan</p>
                        </div>
                        <div class="impact-value">
                            <span id="influenceLevel" class="level-high">Tinggi</span>
                        </div>
                    </div>
                    
                    <div class="impact-item">
                        <div class="impact-info">
                            <h4>Keseimbangan</h4>
                            <p>Distribusi bobot antar kriteria</p>
                        </div>
                        <div class="impact-value">
                            <span id="balanceScore" class="score-good">Baik</span>
                        </div>
                    </div>
                </div>
                
                <div class="impact-footer">
                    <p><ion-icon name="information-circle-outline"></ion-icon> Bobot yang seimbang menghasilkan seleksi yang lebih objektif</p>
                </div>
            </div>

            <!-- Panduan Cepat -->
            <div class="guide-card">
                <div class="guide-header">
                    <h3><ion-icon name="bulb-outline"></ion-icon> Panduan Cepat</h3>
                </div>
                
                <div class="guide-content">
                    <div class="guide-item">
                        <div class="guide-icon">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                        </div>
                        <div class="guide-text">
                            <h5>Total 100%</h5>
                            <p>Pastikan total bobot selalu 100%</p>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <div class="guide-icon">
                            <ion-icon name="sync-outline"></ion-icon>
                        </div>
                        <div class="guide-text">
                            <h5>Auto-adjust</h5>
                            <p>Sistem otomatis menyesuaikan bobot lainnya</p>
                        </div>
                    </div>
                    
                    <div class="guide-item">
                        <div class="guide-icon">
                            <ion-icon name="save-outline"></ion-icon>
                        </div>
                        <div class="guide-text">
                            <h5>Simpan Perubahan</h5>
                            <p>Klik tombol Simpan untuk menerapkan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Halaman -->
    <div class="page-footer">
        <div class="footer-content">
            <div class="footer-info">
                <ion-icon name="shield-checkmark-outline"></ion-icon>
                <div>
                    <h4>Pengaturan Aman</h4>
                    <p>Perubahan akan diterapkan pada sistem seleksi berikutnya</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/dashboard/bobot.js') }}"></script>
@endsection
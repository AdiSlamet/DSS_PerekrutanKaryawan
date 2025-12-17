@extends('layouts.app')

@section('title', 'Dashboard')
    
@section('content')
<link rel="stylesheet" href="css/dashboard/home.css">
<div class="dashboard-page">
    <!-- Header Dashboard -->
    <div class="dashboard-header">
        <div class="welcome-section">
            <h1>Selamat Datang di Sistem SPK</h1>
            <p class="welcome-subtitle">Sistem Pendukung Keputusan Seleksi Kandidat</p>
        </div>
        <div class="date-section">
            <div class="current-date">
                <ion-icon name="calendar-outline"></ion-icon>
                <span id="currentDate">-</span>
            </div>
            <div class="last-update">
                <ion-icon name="time-outline"></ion-icon>
                <span>Update Terakhir: <span id="lastUpdateTime">-</span></span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="card-content">
                <div class="stat-numbers">
                    <h3 id="totalKandidat">0</h3>
                    <p>Total Kandidat</p>
                </div>
                <div class="stat-icon">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
            </div>
            <div class="card-footer">
                <ion-icon name="trending-up-outline"></ion-icon>
                <span>+2 dari bulan lalu</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-content">
                <div class="stat-numbers">
                    <h3 id="sudahDinilai">0</h3>
                    <p>Sudah Dinilai</p>
                </div>
                <div class="stat-icon">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                </div>
            </div>
            <div class="card-footer">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <span>100% lengkap</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-content">
                <div class="stat-numbers">
                    <h3 id="belumDinilai">0</h3>
                    <p>Belum Dinilai</p>
                </div>
                <div class="stat-icon">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
            </div>
            <div class="card-footer">
                <ion-icon name="alert-circle-outline"></ion-icon>
                <span>Perlu penilaian</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-content">
                <div class="stat-numbers">
                    <h3 id="kandidatTerbaik">-</h3>
                    <p>Kandidat Terbaik</p>
                </div>
                <div class="stat-icon">
                    <ion-icon name="trophy-outline"></ion-icon>
                </div>
            </div>
            <div class="card-footer">
                <ion-icon name="star-outline"></ion-icon>
                <span>Skor tertinggi</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="card-content">
                <div class="stat-numbers">
                    <h3 id="averageScore">0.00</h3>
                    <p>Rata-rata Skor</p>
                </div>
                <div class="stat-icon">
                    <ion-icon name="stats-chart-outline"></ion-icon>
                </div>
            </div>
            <div class="card-footer">
                <ion-icon name="analytics-outline"></ion-icon>
                <span>Analisis statistik</span>
            </div>
        </div>
    </div>


    <!-- Main Content -->
    <div class="dashboard-content">
        <!-- Left Column: Recent Activities & Top Candidates -->
        <div class="left-column">
            <!-- Top 5 Candidates -->
            <div class="top-candidates">
                <div class="section-header">
                    <h2><ion-icon name="trophy-outline"></ion-icon> Top 5 Kandidat</h2>
                    <div class="ranking-period">
                        <select id="rankingPeriod" class="period-select">
                            <option value="current">Bulan Ini</option>
                            <option value="last_month">Bulan Lalu</option>
                            <option value="all_time">Semua Waktu</option>
                        </select>
                    </div>
                </div>
                
                <div class="candidates-list" id="topCandidatesList">
                    <!-- Top candidates will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Right Column: Charts & Statistics -->
        <div class="right-column">
            <!-- Score Distribution Chart -->
            <div class="chart-section">
                <div class="section-header">
                    <h2><ion-icon name="pie-chart-outline"></ion-icon> Distribusi Skor</h2>
                    <div class="chart-legend" id="scoreLegend"></div>
                </div>
                
                <div class="chart-container">
                    <canvas id="scoreDistributionChart"></canvas>
                </div>
            </div>

            <!-- Performance by Criteria -->
            <div class="criteria-performance">
                <div class="section-header">
                    <h2><ion-icon name="bar-chart-outline"></ion-icon> Performa per Kriteria</h2>
                    <div class="criteria-selector">
                        <select id="criteriaSelect" class="criteria-select">
                            <option value="all">Semua Kriteria</option>
                            <option value="pengalaman">Pengalaman</option>
                            <option value="jarak">Jarak</option>
                            <option value="komunikasi">Komunikasi</option>
                            <option value="fleksibilitas">Fleksibilitas</option>
                        </select>
                    </div>
                </div>
                
                <div class="performance-chart">
                    <canvas id="criteriaPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Updates -->
    <div class="recent-updates">
        <div class="section-header">
            <h2><ion-icon name="megaphone-outline"></ion-icon> Update Terbaru</h2>
            <button class="btn btn-outline-primary" id="btnMarkAllRead">
                Tandai Semua Dibaca
            </button>
        </div>
        
        <div class="updates-list" id="updatesList">
            <!-- Updates will be populated by JavaScript -->
        </div>
    </div>
</div>

<style>

</style>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="{{ asset('js/dashboard/home.js') }}"></script>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

@endsection
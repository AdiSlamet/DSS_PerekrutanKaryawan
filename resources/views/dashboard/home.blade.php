@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="home-outline"></ion-icon>
            </div>
            <div>
                <h1>Dashboard</h1>
                <p class="page-subtitle">Sistem Pendukung Keputusan Penilaian Kandidat dengan Metode SMART</p>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="totalKandidat">0</h3>
                    <p>Total Kandidat</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                    <ion-icon name="checkmark-done-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="sudahDinilai">0</h3>
                    <p>Sudah Dinilai</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                    <ion-icon name="time-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="belumDinilai">0</h3>
                    <p>Belum Dinilai</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);">
                    <ion-icon name="star-outline"></ion-icon>
                </div>
                <div class="stat-content">
                    <h3 id="rataSkor">0.00</h3>
                    <p>Rata-rata Skor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="filter-outline"></ion-icon> Filter Periode</h2>
        </div>
        <div class="filter-container">
            <div class="form-group" style="max-width: 300px;">
                <label for="filterPeriode" class="form-label">
                    <ion-icon name="calendar-outline"></ion-icon> Pilih Periode
                </label>
                <input type="month" id="filterPeriode" class="form-control">
            </div>
            <div class="filter-actions">
                <button class="btn btn-primary" onclick="applyFilter()">
                    <ion-icon name="checkmark-outline"></ion-icon> Terapkan
                </button>
                <button class="btn btn-outline-secondary" onclick="clearFilter()">
                    <ion-icon name="refresh-outline"></ion-icon> Reset
                </button>
            </div>
        </div>
        <div class="periode-info">
            <ion-icon name="information-circle-outline"></ion-icon>
            <span id="periodeInfo">Menampilkan semua data</span>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="stats-chart-outline"></ion-icon> Progress Penilaian</h2>
        </div>
        <div class="progress-container">
            <div class="progress-item">
                <div class="progress-header">
                    <span class="progress-label">Sudah Dinilai</span>
                    <span class="progress-percent" id="persenSudahDinilai">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressSudahDinilai" style="width: 0%; background: linear-gradient(90deg, #4CAF50 0%, #2E7D32 100%);"></div>
                </div>
                <div class="progress-count" id="countSudahDinilai">0 dari 0</div>
            </div>
            
            <div class="progress-item">
                <div class="progress-header">
                    <span class="progress-label">Belum Dinilai</span>
                    <span class="progress-percent" id="persenBelumDinilai">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" id="progressBelumDinilai" style="width: 0%; background: linear-gradient(90deg, #FF9800 0%, #F57C00 100%);"></div>
                </div>
                <div class="progress-count" id="countBelumDinilai">0 dari 0</div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="analytics-outline"></ion-icon> Analisis Dashboard</h2>
        </div>
        <div class="charts-grid">
            <div class="chart-container" style="position: relative; height: 300px;">
                <h3>
                    <ion-icon name="pie-chart-outline"></ion-icon>
                    Status Penilaian
                </h3>
                <canvas id="statusChart"></canvas>
            </div>
            <div class="chart-container" style="position: relative; height: 300px;">
                <h3>
                    <ion-icon name="bar-chart-outline"></ion-icon>
                    Progress Penilaian
                </h3>
                <canvas id="progressChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="flash-outline"></ion-icon> Aksi Cepat</h2>
        </div>
        <div class="quick-actions-grid">
            <a href="/penilaian" class="quick-action-card">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);">
                    <ion-icon name="add-circle-outline"></ion-icon>
                </div>
                <div class="quick-action-content">
                    <h4>Tambah Penilaian</h4>
                    <p>Input penilaian kandidat baru</p>
                </div>
            </a>

            <a href="/hasil" class="quick-action-card">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                    <ion-icon name="trophy-outline"></ion-icon>
                </div>
                <div class="quick-action-content">
                    <h4>Lihat Hasil</h4>
                    <p>Ranking dan statistik lengkap</p>
                </div>
            </a>

            <a href="/penilaian" class="quick-action-card">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #9c27b0 0%, #6a1b9a 100%);">
                    <ion-icon name="settings-outline"></ion-icon>
                </div>
                <div class="quick-action-content">
                    <h4>Kelola Data</h4>
                    <p>Kelola kandidat dan kriteria</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Loading State -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <p>Memuat data dashboard...</p>
    </div>
</div>

<!-- CSRF Token untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/dashboard/data_kandidat.css') }}">
<style>
    /* Additional styles for Dashboard */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .chart-container h3 {
        margin: 0 0 20px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    
    .quick-action-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e5e7eb;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: #4361ee;
    }
    
    .quick-action-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
    }
    
    .quick-action-content h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .quick-action-content p {
        margin: 0;
        font-size: 14px;
        color: #6b7280;
    }
    
    .periode-info {
        padding: 12px 20px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6b7280;
    }
    
    .periode-info ion-icon {
        font-size: 16px;
        color: #4361ee;
    }
    
    #periodeInfo strong {
        color: #1f2937;
    }
    
    .progress-container {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .progress-item {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .progress-label {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }
    
    .progress-percent {
        font-weight: 700;
        font-size: 16px;
        color: #4361ee;
    }
    
    .progress-bar {
        height: 10px;
        background: #f3f4f6;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 8px;
    }
    
    .progress-fill {
        height: 100%;
        border-radius: 5px;
        transition: width 1s ease-in-out;
    }
    
    .progress-count {
        font-size: 13px;
        color: #6b7280;
    }
    
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .loading-content {
        background: white;
        border-radius: 12px;
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    
    .loading-content .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e5e7eb;
        border-top-color: #4361ee;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .loading-content p {
        margin: 0;
        color: #374151;
        font-weight: 500;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- JavaScript -->
<script>
    const API_CONFIG = {
        dashboard: '/api/dashboard',
    };
    
    let statusChart = null;
    let progressChart = null;
    let currentPeriode = '';
</script>

<script>
class DashboardManager {
    constructor() {
        console.log('DashboardManager initialized');
        this.dashboardData = {};
        
        this.initElements();
        this.bindEvents();
        this.loadDashboardData();
    }

    initElements() {
        console.log('Initializing elements...');
        this.loadingOverlay = document.getElementById('loadingOverlay');
        console.log('Elements initialized');
    }

    bindEvents() {
        console.log('Binding events...');
        console.log('Events bound successfully');
    }

    async loadDashboardData() {
        this.showLoading(true);
        
        try {
            const url = currentPeriode 
                ? `${API_CONFIG.dashboard}?periode=${currentPeriode}` 
                : API_CONFIG.dashboard;
            
            const response = await fetch(url);
            const result = await response.json();

            if (result.status === 'success') {
                this.dashboardData = result.data;
                this.updateCards();
                this.updateCharts();
                this.updatePeriodeInfo();
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
            this.showToast('Gagal memuat data dashboard', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    updateCards() {
        const data = this.dashboardData;
        
        // Update card values
        document.getElementById('totalKandidat').textContent = data.total_kandidat || 0;
        document.getElementById('sudahDinilai').textContent = data.sudah_dinilai || 0;
        document.getElementById('belumDinilai').textContent = data.belum_dinilai || 0;
        document.getElementById('rataSkor').textContent = data.rata_skor || '0.00';

        // Update progress bars
        const total = data.total_kandidat || 1;
        const sudah = data.sudah_dinilai || 0;
        const belum = data.belum_dinilai || 0;
        
        const persenSudah = Math.round((sudah / total) * 100);
        const persenBelum = Math.round((belum / total) * 100);

        document.getElementById('progressSudahDinilai').style.width = persenSudah + '%';
        document.getElementById('progressBelumDinilai').style.width = persenBelum + '%';
        document.getElementById('persenSudahDinilai').textContent = persenSudah + '%';
        document.getElementById('persenBelumDinilai').textContent = persenBelum + '%';
        document.getElementById('countSudahDinilai').textContent = `${sudah} dari ${total}`;
        document.getElementById('countBelumDinilai').textContent = `${belum} dari ${total}`;
    }

    updateCharts() {
        const data = this.dashboardData;
        
        // Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart');
        
        if (statusChart) {
            statusChart.destroy();
        }

        statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Dinilai', 'Belum Dinilai'],
                datasets: [{
                    data: [data.sudah_dinilai || 0, data.belum_dinilai || 0],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(249, 115, 22)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 12
                            },
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Progress Chart (Bar)
        const ctxProgress = document.getElementById('progressChart');
        
        if (progressChart) {
            progressChart.destroy();
        }

        progressChart = new Chart(ctxProgress, {
            type: 'bar',
            data: {
                labels: ['Total', 'Sudah Dinilai', 'Belum Dinilai'],
                datasets: [{
                    label: 'Jumlah Kandidat',
                    data: [
                        data.total_kandidat || 0, 
                        data.sudah_dinilai || 0, 
                        data.belum_dinilai || 0
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)'
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(249, 115, 22)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    updatePeriodeInfo() {
        const infoEl = document.getElementById('periodeInfo');
        const periode = this.dashboardData.periode;
        
        if (periode) {
            const [year, month] = periode.split('-');
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const monthName = monthNames[parseInt(month) - 1] || 'Unknown';
            infoEl.innerHTML = `Menampilkan data untuk periode: <strong>${monthName} ${year}</strong>`;
        } else {
            infoEl.innerHTML = `Menampilkan <strong>semua data</strong>`;
        }
    }

    // Filter methods
    applyFilter() {
        currentPeriode = document.getElementById('filterPeriode').value;
        this.loadDashboardData();
    }

    clearFilter() {
        document.getElementById('filterPeriode').value = '';
        currentPeriode = '';
        this.loadDashboardData();
    }

    // Utility methods
    showLoading(show) {
        if (this.loadingOverlay) {
            this.loadingOverlay.style.display = show ? 'flex' : 'none';
        }
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
function applyFilter() {
    if (window.dashboardManager) {
        window.dashboardManager.applyFilter();
    }
}

function clearFilter() {
    if (window.dashboardManager) {
        window.dashboardManager.clearFilter();
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Dashboard');
    try {
        window.dashboardManager = new DashboardManager();
        console.log('DashboardManager initialized successfully');
    } catch (error) {
        console.error('Error initializing DashboardManager:', error);
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
@extends('layouts.app')

@section('title', 'Hasil Perhitungan & Ranking')
    
@section('content')
<div class="candidates-page">
    <!-- Header Halaman -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-icon">
                <ion-icon name="trophy-outline"></ion-icon>
            </div>
            <div>
                <h1>Hasil Perhitungan & Ranking</h1>
                <p class="page-subtitle">Dashboard dan analisis hasil penilaian dengan metode SMART</p>
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
                <label for="filterPeriode" class="form-label">Pilih Periode</label>
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
    </div>

    <!-- Charts Section -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="bar-chart-outline"></ion-icon> Analisis Hasil</h2>
        </div>
        <div class="charts-grid">
            <div class="chart-container">
                <h3>Distribusi Skor Kandidat</h3>
                <canvas id="distribusiChart" style="height: 250px;"></canvas>
            </div>
            <div class="chart-container">
                <h3>Top 5 Kandidat</h3>
                <ul id="top5List" class="top5-list"></ul>
            </div>
        </div>
    </div>

    <!-- Ranking Table -->
    <div class="data-section">
        <div class="section-header">
            <h2><ion-icon name="list-outline"></ion-icon> Ranking Kandidat</h2>
            <div class="section-actions">
                <button class="btn btn-outline-secondary" onclick="loadAllData()">
                    <ion-icon name="refresh-outline"></ion-icon> Refresh
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="candidates-table" id="rankingTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">Ranking</th>
                        <th>Nama Kandidat</th>
                        <th style="width: 150px;">Total Skor</th>
                        <th style="width: 180px;">Klasifikasi</th>
                    </tr>
                </thead>
                <tbody id="rankingTableBody">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Loading State -->
        <div class="loading-state" id="loadingState">
            <div class="spinner"></div>
            <p>Memuat data hasil penilaian...</p>
        </div>
        
        <!-- Empty State -->
        <div class="empty-state" id="emptyState">
            <div class="empty-icon">
                <ion-icon name="trophy-outline"></ion-icon>
            </div>
            <h3>Belum Ada Data Hasil</h3>
            <p>Tidak ada data hasil penilaian untuk ditampilkan</p>
        </div>
    </div>
</div>

<!-- CSRF Token untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('css/dashboard/data_kandidat.css') }}">
<style>
    /* Additional styles for Hasil Penilaian */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    
    .chart-container {
        position: relative;
        height: 400px; /* Fixed height */
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
    }
    
    .top5-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .top5-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .top5-item:last-child {
        margin-bottom: 0;
    }
    
    .rank-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .rank-1 {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #7c2d12;
    }
    
    .rank-2 {
        background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%);
        color: #374151;
    }
    
    .rank-3 {
        background: linear-gradient(135deg, #CD7F32 0%, #A0522D 100%);
        color: white;
    }
    
    .rank-other {
        background: #e5e7eb;
        color: #6b7280;
    }
    
    .top5-name {
        flex: 1;
        margin-left: 12px;
        font-weight: 500;
        color: #1f2937;
    }
    
    .top5-score {
        font-weight: 600;
        color: #4361ee;
    }
    
    .klasifikasi-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .klasifikasi-direkomendasikan {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    
    .klasifikasi-memenuhi {
        background-color: rgba(251, 191, 36, 0.1);
        color: #d97706;
        border: 1px solid rgba(251, 191, 36, 0.2);
    }
    
    .klasifikasi-dipertimbangkan {
        background-color: rgba(249, 115, 22, 0.1);
        color: #ea580c;
        border: 1px solid rgba(249, 115, 22, 0.2);
    }
    
    .filter-container {
        padding: 20px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-bottom: 0;
        display: flex;
        gap: 20px;
        align-items: flex-end;
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 5px;
    }
</style>

<!-- JavaScript -->
<script>
    const API_CONFIG = {
        hasilStatistik: '/api/hasil/statistik',
        hasilDistribusi: '/api/hasil/distribusi',
        hasilRanking: '/api/hasil/ranking',
    };
    
    let distribusiChart = null;
    let currentPeriode = '';
</script>

<script>
class HasilManager {
    constructor() {
        console.log('HasilManager initialized');
        this.rankingData = [];
        this.statistikData = {};
        
        this.initElements();
        this.bindEvents();
        this.loadAllData();
    }

    initElements() {
        console.log('Initializing elements...');
        this.tableBody = document.getElementById('rankingTableBody');
        this.emptyState = document.getElementById('emptyState');
        this.loadingState = document.getElementById('loadingState');
        this.top5List = document.getElementById('top5List');
        
        console.log('Elements initialized');
    }

    bindEvents() {
        console.log('Binding events...');
        console.log('Events bound successfully');
    }

    async loadAllData() {
        this.showLoading(true);
        await Promise.all([
            this.loadStatistik(),
            this.loadDistribusi(),
            this.loadRanking()
        ]);
        this.showLoading(false);
    }

    async loadStatistik() {
        try {
            const url = currentPeriode 
                ? `${API_CONFIG.hasilStatistik}?periode=${currentPeriode}` 
                : API_CONFIG.hasilStatistik;
            
            const response = await fetch(url);
            const data = await response.json();

            if (data.status === 'success') {
                this.statistikData = data.data;
                // Data statistik tersedia di this.statistikData
                console.log('Statistik loaded:', this.statistikData);
            }
        } catch (error) {
            console.error('Error loading statistik:', error);
        }
    }

    async loadDistribusi() {
        try {
            const url = currentPeriode 
                ? `${API_CONFIG.hasilDistribusi}?periode=${currentPeriode}` 
                : API_CONFIG.hasilDistribusi;
            
            const response = await fetch(url);
            const data = await response.json();

            if (data.status === 'success') {
                this.updateDistribusiChart(data.data);
            }
        } catch (error) {
            console.error('Error loading distribusi:', error);
        }
    }

    async loadRanking() {
        try {
            const url = currentPeriode 
                ? `${API_CONFIG.hasilRanking}?periode=${currentPeriode}` 
                : API_CONFIG.hasilRanking;

            const response = await fetch(url);
            const data = await response.json();

            if (data.status === 'success') {
                this.rankingData = data.data;
                this.renderRankingTable();
                this.renderTop5List();
            }
        } catch (error) {
            console.error('Error loading ranking:', error);
        }
    }

    updateDistribusiChart(data) {
        const ctx = document.getElementById('distribusiChart');
        
        if (distribusiChart) {
            distribusiChart.destroy();
        }

        distribusiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sangat Baik (4.0-5.0)', 'Baik (3.0-3.9)', 'Cukup (2.0-2.9)', 'Kurang (<2.0)'],
                datasets: [{
                    label: 'Jumlah Kandidat',
                    data: [data.sangat_baik || 0, data.baik || 0, data.cukup || 0, data.kurang || 0],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(251, 191, 36)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    renderTop5List() {
        if (!this.rankingData || this.rankingData.length === 0) {
            this.top5List.innerHTML = `
                <li class="top5-item">
                    <span class="text-gray-500 text-sm">Belum ada data kandidat</span>
                </li>
            `;
            return;
        }

        let html = '';
        
        this.rankingData.slice(0, 5).forEach((item, index) => {
            const rankClass = index === 0 ? 'rank-1' : 
                             index === 1 ? 'rank-2' : 
                             index === 2 ? 'rank-3' : 'rank-other';
            
            html += `
                <li class="top5-item">
                    <div class="rank-badge ${rankClass}">${index + 1}</div>
                    <div class="top5-name">${item.nama_kandidat || 'Unknown'}</div>
                    <div class="top5-score">${item.total_skor || '0'}</div>
                </li>
            `;
        });
        
        this.top5List.innerHTML = html;
    }

    renderRankingTable() {
        console.log('Rendering ranking:', this.rankingData);
        
        if (!this.rankingData || this.rankingData.length === 0) {
            this.tableBody.innerHTML = '';
            this.emptyState.style.display = 'flex';
            return;
        }
        
        this.emptyState.style.display = 'none';
        
        let html = '';
        
        this.rankingData.forEach((item, index) => {
            const klasifikasiBadge = this.getKlasifikasiBadge(item.klasifikasi);
            const rankBadge = this.getRankBadge(index + 1);
            
            html += `
                <tr data-id="${item.id || index}">
                    <td>
                        ${rankBadge}
                    </td>
                    <td>
                        <div class="kandidat-name">
                            <strong>${item.nama_kandidat || 'Unknown'}</strong>
                        </div>
                    </td>
                    <td>
                        <span class="skor-badge ${this.getSkorClass(item.total_skor)}">
                            ${item.total_skor || '0'}
                        </span>
                    </td>
                    <td>
                        ${klasifikasiBadge}
                    </td>
                </tr>
            `;
        });
        
        this.tableBody.innerHTML = html;
    }

    getRankBadge(rank) {
        if (rank === 1) {
            return `<span class="rank-badge rank-1">${rank}</span>`;
        } else if (rank === 2) {
            return `<span class="rank-badge rank-2">${rank}</span>`;
        } else if (rank === 3) {
            return `<span class="rank-badge rank-3">${rank}</span>`;
        } else {
            return `<span class="rank-badge rank-other">${rank}</span>`;
        }
    }

    getKlasifikasiBadge(klasifikasi) {
        const klasifikasiMap = {
            'direkomendasikan': {
                class: 'klasifikasi-direkomendasikan',
                text: 'Direkomendasikan'
            },
            'memenuhi syarat': {
                class: 'klasifikasi-memenuhi',
                text: 'Memenuhi Syarat'
            },
            'perlu dipertimbangkan': {
                class: 'klasifikasi-dipertimbangkan',
                text: 'Perlu Dipertimbangkan'
            }
        };
        
        const klas = klasifikasiMap[klasifikasi] || {
            class: 'klasifikasi-memenuhi',
            text: klasifikasi || 'belum di hitung'
        };
        
        return `<span class="klasifikasi-badge ${klas.class}">${klas.text}</span>`;
    }

    getSkorClass(skor) {
        const nilai = parseFloat(skor) || 0;
        if (nilai >= 4.0) return 'skor-tinggi';
        if (nilai >= 3.0) return 'skor-sedang';
        return 'skor-rendah';
    }

    // Filter methods
    applyFilter() {
        currentPeriode = document.getElementById('filterPeriode').value;
        this.loadAllData();
    }

    clearFilter() {
        document.getElementById('filterPeriode').value = '';
        currentPeriode = '';
        this.loadAllData();
    }

    // Utility methods
    showLoading(show) {
        if (this.loadingState) this.loadingState.style.display = show ? 'flex' : 'none';
        if (this.tableBody) this.tableBody.parentElement.parentElement.style.display = show ? 'none' : 'block';
        if (this.top5List) this.top5List.parentElement.style.display = show ? 'none' : 'block';
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
    if (window.hasilManager) {
        window.hasilManager.applyFilter();
    }
}

function clearFilter() {
    if (window.hasilManager) {
        window.hasilManager.clearFilter();
    }
}

function loadAllData() {
    if (window.hasilManager) {
        window.hasilManager.loadAllData();
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Hasil');
    try {
        window.hasilManager = new HasilManager();
        console.log('HasilManager initialized successfully');
    } catch (error) {
        console.error('Error initializing HasilManager:', error);
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
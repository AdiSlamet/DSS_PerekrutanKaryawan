@extends('layouts.app')

@section('title', 'Hasil Perhitungan & Ranking')
    
@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Hasil Penilaian Kandidat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Hasil Penilaian Kandidat</h1>
            <p class="text-gray-600">Dashboard dan analisis hasil penilaian dengan metode SMART</p>
        </div>

        <!-- Filter Periode -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Periode</label>
                    <input type="month" id="filterPeriode" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button onclick="applyFilter()" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Terapkan
                </button>
                <button onclick="clearFilter()" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Reset
                </button>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Kandidat</p>
                        <p id="totalKandidat" class="text-3xl font-bold text-gray-800">0</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Skor Tertinggi</p>
                        <p id="skorTertinggi" class="text-3xl font-bold text-green-600">0</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Skor Terendah</p>
                        <p id="skorTerendah" class="text-3xl font-bold text-red-600">0</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Rata-rata Skor</p>
                        <p id="rataSkor" class="text-3xl font-bold text-purple-600">0</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Klasifikasi Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Direkomendasikan</p>
                <p id="direkomendasikan" class="text-4xl font-bold mb-2">0</p>
                <p class="text-sm opacity-75">Kandidat</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Memenuhi Syarat</p>
                <p id="memenuhiSyarat" class="text-4xl font-bold mb-2">0</p>
                <p class="text-sm opacity-75">Kandidat</p>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
                <p class="text-sm opacity-90 mb-1">Perlu Dipertimbangkan</p>
                <p id="perluDipertimbangkan" class="text-4xl font-bold mb-2">0</p>
                <p class="text-sm opacity-75">Kandidat</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Distribusi Skor Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Distribusi Skor Kandidat</h3>
                <canvas id="distribusiChart"></canvas>
            </div>

            <!-- Top 5 Kandidat Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Top 5 Kandidat</h3>
                <ul id="top5List" class="space-y-3"></ul>
            </div>
        </div>

        <!-- Ranking Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Ranking Kandidat</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ranking</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kandidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Skor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klasifikasi</th>
                        </tr>
                    </thead>
                    <tbody id="rankingTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data akan diisi dengan JavaScript -->
                    </tbody>
                </table>
            </div>

            <div id="loadingState" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Memuat data...</p>
            </div>

            <div id="emptyState" class="text-center py-8 hidden">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-gray-500">Tidak ada data hasil penilaian</p>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api';
        let distribusiChart = null;
        let top5Chart = null;
        let currentPeriode = '';

        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
        });

        async function loadAllData() {
            showLoading(true);
            await Promise.all([
                loadStatistik(),
                loadDistribusi(),
                loadRanking()
            ]);
            showLoading(false);
        }

        async function loadStatistik() {
            try {
                const url = currentPeriode 
                    ? `${API_URL}/hasil/statistik?periode=${currentPeriode}` 
                    : `${API_URL}/hasil/statistik`;
                
                const response = await fetch(url);
                const data = await response.json();

                if (data.status === 'success') {
                    const stats = data.data;
                    document.getElementById('totalKandidat').textContent = stats.total_kandidat;
                    document.getElementById('skorTertinggi').textContent = stats.skor_tertinggi;
                    document.getElementById('skorTerendah').textContent = stats.skor_terendah;
                    document.getElementById('rataSkor').textContent = stats.rata_skor;
                    document.getElementById('direkomendasikan').textContent = stats.direkomendasikan;
                    document.getElementById('memenuhiSyarat').textContent = stats.memenuhi_syarat;
                    document.getElementById('perluDipertimbangkan').textContent = stats.perlu_dipertimbangkan;
                }
            } catch (error) {
                console.error('Error loading statistik:', error);
            }
        }

        async function loadDistribusi() {
            try {
                const url = currentPeriode 
                    ? `${API_URL}/hasil/distribusi?periode=${currentPeriode}` 
                    : `${API_URL}/hasil/distribusi`;
                
                const response = await fetch(url);
                const data = await response.json();

                if (data.status === 'success') {
                    const dist = data.data;
                    updateDistribusiChart(dist);
                }
            } catch (error) {
                console.error('Error loading distribusi:', error);
            }
        }

        async function loadRanking() {
            try {
                const url = currentPeriode 
                    ? `${API_URL}/hasil/ranking?periode=${currentPeriode}` 
                    : `${API_URL}/hasil/ranking`;

                const response = await fetch(url);
                const data = await response.json();

                if (data.status === 'success') {
                    displayRanking(data.data);
                    renderTop5List(data.data);
                }
            } catch (error) {
                console.error('Error loading ranking:', error);
            }
        }

        function updateDistribusiChart(data) {
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
                        data: [data.sangat_baik, data.baik, data.cukup, data.kurang],
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
                    maintainAspectRatio: true,
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

    function renderTop5List(data) {
        const list = document.getElementById('top5List');

        if (!data || data.length === 0) {
            list.innerHTML = `
                <li class="text-gray-500 text-sm">Belum ada data kandidat</li>
            `;
            return;
        }

        list.innerHTML = '';

        data.slice(0, 5).forEach((item, index) => {
            list.innerHTML += `
                <li class="flex items-center justify-between bg-gray-50 px-4 py-3 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-lg font-bold text-blue-600">#${index + 1}</span>
                        <span class="font-medium text-gray-800">
                            ${item.nama_kandidat}
                        </span>
                    </div>
                </li>
            `;
        });
    }

        function displayRanking(ranking) {
            const tbody = document.getElementById('rankingTableBody');
            const emptyState = document.getElementById('emptyState');

            if (ranking.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            tbody.innerHTML = '';

            ranking.forEach(item => {
                const klasifikasiBadge = getKlasifikasiBadge(item.klasifikasi);
                const rankBadge = getRankBadge(item.rank);
                
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${rankBadge}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${item.nama_kandidat}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-blue-600">${item.total_skor}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${klasifikasiBadge}
                        </td>
                    </tr>
                `;
            });
        }

        function getRankBadge(rank) {
            if (rank === 1) {
                return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-800 font-bold">1</span>`;
            } else if (rank === 2) {
                return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-800 font-bold">2</span>`;
            } else if (rank === 3) {
                return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-800 font-bold">3</span>`;
            } else {
                return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-semibold">${rank}</span>`;
            }
        }

        function getKlasifikasiBadge(klasifikasi) {
            const badges = {
                'direkomendasikan': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Direkomendasikan</span>',
                'memenuhi syarat': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Memenuhi Syarat</span>',
                'perlu dipertimbangkan': '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Perlu Dipertimbangkan</span>'
            };
            return badges[klasifikasi] || '<span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">-</span>';
        }

        function applyFilter() {
            const periode = document.getElementById('filterPeriode').value;
            currentPeriode = periode;
            loadAllData();
        }

        function clearFilter() {
            document.getElementById('filterPeriode').value = '';
            currentPeriode = '';
            loadAllData();
        }

        function showLoading(show) {
            const loading = document.getElementById('loadingState');
            const table = document.querySelector('table');
            
            if (show) {
                loading.classList.remove('hidden');
                if (table) table.classList.add('hidden');
            } else {
                loading.classList.add('hidden');
                if (table) table.classList.remove('hidden');
            }
        }

        
    </script>
</body>
</html>
@endsection
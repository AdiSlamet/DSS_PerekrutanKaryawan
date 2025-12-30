class Dashboard {
    constructor() {
        this.statusChart = null;
        this.progressChart = null;
        this.currentPeriod = '';
        
        this.init();
    }

    init() {
        this.updateCurrentDateTime();
        this.loadDashboardData();
        this.setupEventListeners();
    }

    updateCurrentDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
        document.getElementById('lastUpdateTime').textContent = now.toLocaleTimeString('id-ID');
    }

    async loadDashboardData() {
        this.showLoading(true);
        
        try {
            const url = this.currentPeriod 
                ? `/api/dashboard?periode=${this.currentPeriod}` 
                : '/api/dashboard';
            
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                const data = result.data;
                this.updateDashboard(data);
                this.updateCharts(data);
                this.updatePeriodeInfo(data);
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
            this.showNotification('Gagal memuat data dashboard', 'error');
        } finally {
            this.showLoading(false);
        }
    }

    updateDashboard(data) {
        // Update card values
        document.getElementById('totalKandidat').textContent = data.total_kandidat || 0;
        document.getElementById('sudahDinilai').textContent = data.sudah_dinilai || 0;
        document.getElementById('belumDinilai').textContent = data.belum_dinilai || 0;
        document.getElementById('rataSkor').textContent = data.rata_skor?.toFixed(2) || '0.00';

        // Calculate percentages
        const total = data.total_kandidat || 1;
        const persenSudah = Math.round((data.sudah_dinilai / total) * 100);
        const persenBelum = Math.round((data.belum_dinilai / total) * 100);

        // Update progress bars
        document.getElementById('progressSudahDinilai').style.width = persenSudah + '%';
        document.getElementById('progressBelumDinilai').style.width = persenBelum + '%';
        document.getElementById('persenSudahDinilai').textContent = persenSudah + '%';
        document.getElementById('persenBelumDinilai').textContent = persenBelum + '%';
    }

    updateCharts(data) {
        // Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart');
        if (this.statusChart) {
            this.statusChart.destroy();
        }

        this.statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Sudah Dinilai', 'Belum Dinilai'],
                datasets: [{
                    data: [data.sudah_dinilai || 0, data.belum_dinilai || 0],
                    backgroundColor: [
                        'rgba(52, 199, 89, 0.8)',
                        'rgba(255, 149, 0, 0.8)'
                    ],
                    borderColor: [
                        'rgb(52, 199, 89)',
                        'rgb(255, 149, 0)'
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
                            }
                        }
                    }
                }
            }
        });

        // Progress Chart (Bar)
        const ctxProgress = document.getElementById('progressChart');
        if (this.progressChart) {
            this.progressChart.destroy();
        }

        this.progressChart = new Chart(ctxProgress, {
            type: 'bar',
            data: {
                labels: ['Total', 'Sudah Dinilai', 'Belum Dinilai'],
                datasets: [{
                    label: 'Jumlah Kandidat',
                    data: [data.total_kandidat || 0, data.sudah_dinilai || 0, data.belum_dinilai || 0],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(52, 199, 89, 0.8)',
                        'rgba(255, 149, 0, 0.8)'
                    ],
                    borderColor: [
                        'rgb(102, 126, 234)',
                        'rgb(52, 199, 89)',
                        'rgb(255, 149, 0)'
                    ],
                    borderWidth: 2,
                    borderRadius: 4
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
                            stepSize: 1
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    updatePeriodeInfo(data) {
        const infoEl = document.getElementById('periodeInfo');
        const activePeriodEl = document.getElementById('activePeriod');
        
        if (this.currentPeriod) {
            const [year, month] = this.currentPeriod.split('-');
            const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const monthName = monthNames[parseInt(month) - 1];
            
            infoEl.innerHTML = `<ion-icon name="information-circle-outline"></ion-icon> Menampilkan data untuk periode: <strong>${monthName} ${year}</strong>`;
            activePeriodEl.textContent = `${monthName} ${year}`;
        } else {
            infoEl.innerHTML = `<ion-icon name="information-circle-outline"></ion-icon> Menampilkan <strong>semua data</strong>`;
            activePeriodEl.textContent = 'Semua';
        }

        // Update latest data info
        if (data.latest_update) {
            document.getElementById('latestData').textContent = new Date(data.latest_update).toLocaleDateString('id-ID');
        }
    }

    setupEventListeners() {
        // Apply filter button
        document.getElementById('btnApplyFilter').addEventListener('click', () => {
            this.currentPeriod = document.getElementById('periodeSelect').value;
            this.loadDashboardData();
        });

        // Clear filter button
        document.getElementById('btnClearFilter').addEventListener('click', () => {
            document.getElementById('periodeSelect').value = '';
            this.currentPeriod = '';
            this.loadDashboardData();
        });

        // Period select change
        document.getElementById('periodeSelect').addEventListener('change', (e) => {
            this.currentPeriod = e.target.value;
        });

        // Auto-refresh every 5 minutes
        setInterval(() => {
            this.loadDashboardData();
            this.updateCurrentDateTime();
        }, 5 * 60 * 1000);
    }

    showLoading(show) {
        // Create loading overlay if it doesn't exist
        let overlay = document.querySelector('.loading-overlay');
        
        if (show) {
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.className = 'loading-overlay';
                overlay.innerHTML = `
                    <div class="loading-spinner"></div>
                    <p>Memuat data dashboard...</p>
                `;
                document.body.appendChild(overlay);
            }
            overlay.style.display = 'flex';
        } else if (overlay) {
            overlay.style.display = 'none';
        }
    }

    showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <ion-icon name="${type === 'success' ? 'checkmark-circle' : 
                            type === 'error' ? 'close-circle' : 'information-circle'}"></ion-icon>
            <span>${message}</span>
        `;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Dashboard();
});
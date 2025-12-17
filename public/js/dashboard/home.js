document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let scoreDistributionChart = null;
    let criteriaPerformanceChart = null;
    let candidatesData = [];
    
    // Initialize
    updateCurrentDateTime();
    loadDashboardData();
    setupEventListeners();
    
    // Update current date and time
    function updateCurrentDateTime() {
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
    
    // Load dashboard data
    function loadDashboardData() {
        // Load candidates data from localStorage
        const savedCandidates = localStorage.getItem('candidatesData');
        const savedPeriode = localStorage.getItem('currentPeriode') || '2024-01';
        
        if (savedCandidates) {
            try {
                const allData = JSON.parse(savedCandidates);
                if (allData[savedPeriode]) {
                    candidatesData = allData[savedPeriode];
                }
            } catch (e) {
                console.error('Error loading candidates data:', e);
                candidatesData = getSampleCandidates();
            }
        } else {
            candidatesData = getSampleCandidates();
        }
        
        updateDashboardStats();
        updateRecentActivities();
        updateTopCandidates();
        updateRecentUpdates();
        setupCharts();
    }
    
    function getSampleCandidates() {
        return [
            {
                id: 1,
                name: 'Andi Pratama',
                scores: { pengalaman: 5, jarak: 5, komunikasi: 3, fleksibilitas: 4 },
                createdAt: '2024-01-15',
                status: 'recommended'
            },
            {
                id: 2,
                name: 'Budi Santoso',
                scores: { pengalaman: 4, jarak: 4, komunikasi: 4, fleksibilitas: 3 },
                createdAt: '2024-01-16',
                status: 'qualified'
            },
            {
                id: 3,
                name: 'Cindy Wijaya',
                scores: { pengalaman: 3, jarak: 3, komunikasi: 3, fleksibilitas: 3 },
                createdAt: '2024-01-17',
                status: 'qualified'
            },
            {
                id: 4,
                name: 'Dian Permata',
                scores: { pengalaman: 2, jarak: 4, komunikasi: 2, fleksibilitas: 2 },
                createdAt: '2024-01-18',
                status: 'needs-review'
            },
            {
                id: 5,
                name: 'Eko Putra',
                scores: { pengalaman: 5, jarak: 3, komunikasi: 5, fleksibilitas: 4 },
                createdAt: '2024-01-19',
                status: 'recommended'
            },
            {
                id: 6,
                name: 'Fitriani Sari',
                scores: { pengalaman: 4, jarak: 5, komunikasi: 4, fleksibilitas: 5 },
                createdAt: '2024-01-20',
                status: 'recommended'
            },
            {
                id: 7,
                name: 'Gunawan Wijaya',
                scores: { pengalaman: 3, jarak: 4, komunikasi: 2, fleksibilitas: 3 },
                createdAt: '2024-01-21',
                status: 'needs-review'
            },
            {
                id: 8,
                name: 'Hana Putri',
                scores: { pengalaman: 5, jarak: 2, komunikasi: 4, fleksibilitas: 5 },
                createdAt: '2024-01-22',
                status: 'qualified'
            },
            {
                id: 9,
                name: 'Irfan Maulana',
                scores: { pengalaman: 4, jarak: 4, komunikasi: 5, fleksibilitas: 4 },
                createdAt: '2024-01-23',
                status: 'recommended'
            },
            {
                id: 10,
                name: 'Jihan Aulia',
                scores: { pengalaman: 2, jarak: 3, komunikasi: 2, fleksibilitas: 2 },
                createdAt: '2024-01-24',
                status: 'needs-review'
            }
        ];
    }
    
    function calculateFinalScore(candidate) {
        const scores = candidate.scores;
        const average = (scores.pengalaman + scores.jarak + scores.komunikasi + scores.fleksibilitas) / 4;
        return parseFloat(average.toFixed(2));
    }
    
    function updateDashboardStats() {
        const totalKandidat = candidatesData.length;
        const sudahDinilai = candidatesData.filter(c => c.status !== 'pending').length;
        const belumDinilai = candidatesData.filter(c => c.status === 'pending').length;
        
        // Find best candidate
        let bestCandidate = null;
        let bestScore = 0;
        
        candidatesData.forEach(candidate => {
            const score = calculateFinalScore(candidate);
            if (score > bestScore) {
                bestScore = score;
                bestCandidate = candidate;
            }
        });
        
        // Calculate average score
        const totalScore = candidatesData.reduce((sum, candidate) => {
            return sum + calculateFinalScore(candidate);
        }, 0);
        
        const averageScore = totalKandidat > 0 ? (totalScore / totalKandidat).toFixed(2) : '0.00';
        
        // Update DOM elements
        document.getElementById('totalKandidat').textContent = totalKandidat;
        document.getElementById('sudahDinilai').textContent = sudahDinilai;
        document.getElementById('belumDinilai').textContent = belumDinilai;
        document.getElementById('kandidatTerbaik').textContent = bestCandidate ? bestCandidate.name.split(' ')[0] : '-';
        document.getElementById('averageScore').textContent = averageScore;
    }
    
    function updateRecentActivities() {
        const activities = [
            {
                icon: 'person-add-outline',
                title: 'Kandidat Baru Ditambahkan',
                description: 'Irfan Maulana ditambahkan ke sistem',
                time: '10 menit yang lalu',
                color: '#007bff'
            },
            {
                icon: 'star-outline',
                title: 'Penilaian Diselesaikan',
                description: '5 kandidat berhasil dinilai',
                time: '1 jam yang lalu',
                color: '#4CAF50'
            },
            {
                icon: 'calculator-outline',
                title: 'Perhitungan Ranking',
                description: 'Proses perhitungan ranking selesai',
                time: '2 jam yang lalu',
                color: '#FF9800'
            },
            {
                icon: 'download-outline',
                title: 'Export Data',
                description: 'Laporan hasil seleksi diexport',
                time: '3 jam yang lalu',
                color: '#9C27B0'
            },
            {
                icon: 'settings-outline',
                title: 'Pengaturan Diperbarui',
                description: 'Bobot kriteria diperbarui',
                time: '5 jam yang lalu',
                color: '#2196F3'
            }
        ];
        
        const activitiesList = document.getElementById('activitiesList');
        let activitiesHTML = '';
        
        activities.forEach(activity => {
            activitiesHTML += `
                <div class="activity-item">
                    <div class="activity-icon" style="background: ${activity.color}20; color: ${activity.color};">
                        <ion-icon name="${activity.icon}"></ion-icon>
                    </div>
                    <div class="activity-content">
                        <h4>${activity.title}</h4>
                        <p>${activity.description}</p>
                        <div class="activity-time">${activity.time}</div>
                    </div>
                </div>
            `;
        });
        
        activitiesList.innerHTML = activitiesHTML;
    }
    
    function updateTopCandidates() {
        // Calculate scores and sort
        const rankedCandidates = candidatesData
            .map(candidate => ({
                ...candidate,
                finalScore: calculateFinalScore(candidate)
            }))
            .sort((a, b) => b.finalScore - a.finalScore)
            .slice(0, 5);
        
        const topCandidatesList = document.getElementById('topCandidatesList');
        let candidatesHTML = '';
        
        rankedCandidates.forEach((candidate, index) => {
            const initials = candidate.name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2);
            const rankClass = `rank-${index + 1}`;
            
            candidatesHTML += `
                <div class="candidate-item ${rankClass}">
                    <div class="candidate-avatar">
                        ${initials}
                    </div>
                    <div class="candidate-info">
                        <h4>${candidate.name}</h4>
                        <p>ID: ${candidate.id}</p>
                    </div>
                    <div class="candidate-score">
                        <div class="score-value">${candidate.finalScore.toFixed(2)}</div>
                        <div class="score-label">Ranking #${index + 1}</div>
                    </div>
                </div>
            `;
        });
        
        topCandidatesList.innerHTML = candidatesHTML;
    }
    
    function updateRecentUpdates() {
        const updates = [
            {
                icon: 'information-circle-outline',
                type: 'info',
                title: 'Versi Sistem 2.1',
                description: 'Update terbaru sistem SPK telah tersedia',
                time: 'Hari ini',
                badge: 'New'
            },
            {
                icon: 'warning-outline',
                type: 'warning',
                title: 'Maintenance Schedule',
                description: 'Jadwal maintenance sistem minggu depan',
                time: 'Kemarin',
                badge: null
            },
            {
                icon: 'checkmark-circle-outline',
                type: 'success',
                title: 'Data Backup Berhasil',
                description: 'Backup data otomatis berhasil dilakukan',
                time: '2 hari yang lalu',
                badge: null
            }
        ];
        
        const updatesList = document.getElementById('updatesList');
        let updatesHTML = '';
        
        updates.forEach((update, index) => {
            const unreadClass = index === 0 ? 'unread' : '';
            
            updatesHTML += `
                <div class="update-item ${unreadClass}">
                    <div class="update-icon ${update.type}">
                        <ion-icon name="${update.icon}"></ion-icon>
                    </div>
                    <div class="update-content">
                        <h4>${update.title}</h4>
                        <p>${update.description}</p>
                        <div class="update-time">${update.time}</div>
                    </div>
                    ${update.badge ? `<span class="update-badge">${update.badge}</span>` : ''}
                </div>
            `;
        });
        
        updatesList.innerHTML = updatesHTML;
    }
    
    function setupCharts() {
        setupScoreDistributionChart();
        setupCriteriaPerformanceChart();
    }
    
    function setupScoreDistributionChart() {
        const ctx = document.getElementById('scoreDistributionChart').getContext('2d');
        
        // Calculate score distribution
        const scores = candidatesData.map(candidate => calculateFinalScore(candidate));
        
        const excellent = scores.filter(score => score >= 4.0).length;
        const good = scores.filter(score => score >= 3.0 && score < 4.0).length;
        const fair = scores.filter(score => score >= 2.0 && score < 3.0).length;
        const poor = scores.filter(score => score < 2.0).length;
        
        // Update legend
        const legend = document.getElementById('scoreLegend');
        legend.innerHTML = `
            <div class="legend-item">
                <span class="legend-color" style="background: #4CAF50;"></span>
                <span>Sangat Baik (${excellent})</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background: #FF9800;"></span>
                <span>Baik (${good})</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background: #F44336;"></span>
                <span>Cukup (${fair})</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background: #9E9E9E;"></span>
                <span>Kurang (${poor})</span>
            </div>
        `;
        
        if (scoreDistributionChart) {
            scoreDistributionChart.destroy();
        }
        
        scoreDistributionChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sangat Baik (≥4.0)', 'Baik (3.0-3.9)', 'Cukup (2.0-2.9)', 'Kurang (<2.0)'],
                datasets: [{
                    data: [excellent, good, fair, poor],
                    backgroundColor: ['#4CAF50', '#FF9800', '#F44336', '#9E9E9E'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} kandidat`;
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }
    
    function setupCriteriaPerformanceChart() {
        const ctx = document.getElementById('criteriaPerformanceChart').getContext('2d');
        
        // Calculate average scores per criteria
        const pengalamanAvg = candidatesData.reduce((sum, c) => sum + c.scores.pengalaman, 0) / candidatesData.length;
        const jarakAvg = candidatesData.reduce((sum, c) => sum + c.scores.jarak, 0) / candidatesData.length;
        const komunikasiAvg = candidatesData.reduce((sum, c) => sum + c.scores.komunikasi, 0) / candidatesData.length;
        const fleksibilitasAvg = candidatesData.reduce((sum, c) => sum + c.scores.fleksibilitas, 0) / candidatesData.length;
        
        if (criteriaPerformanceChart) {
            criteriaPerformanceChart.destroy();
        }
        
        criteriaPerformanceChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pengalaman', 'Jarak', 'Komunikasi', 'Fleksibilitas'],
                datasets: [{
                    label: 'Rata-rata Skor',
                    data: [pengalamanAvg, jarakAvg, komunikasiAvg, fleksibilitasAvg],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(76, 175, 80, 0.8)',
                        'rgba(255, 152, 0, 0.8)',
                        'rgba(156, 39, 176, 0.8)'
                    ],
                    borderColor: [
                        'rgb(102, 126, 234)',
                        'rgb(76, 175, 80)',
                        'rgb(255, 152, 0)',
                        'rgb(156, 39, 176)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        title: {
                            display: true,
                            text: 'Skor (1-5)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Kriteria'
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
                                return `Skor: ${context.raw.toFixed(2)}/5`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    function setupEventListeners() {
        // Refresh dashboard button
        document.getElementById('btnRefreshDashboard').addEventListener('click', function() {
            loadDashboardData();
            updateCurrentDateTime();
            showNotification('Dashboard berhasil diperbarui!', 'success');
        });
        
        // View all activities button
        document.getElementById('btnViewAllActivities').addEventListener('click', function() {
            window.location.href = '/aktivitas';
        });
        
        // Mark all as read button
        document.getElementById('btnMarkAllRead').addEventListener('click', function() {
            document.querySelectorAll('.update-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            showNotification('Semua update ditandai sebagai dibaca', 'info');
        });
        
        // Ranking period select
        document.getElementById('rankingPeriod').addEventListener('change', function() {
            updateTopCandidates();
        });
        
        // Criteria select
        document.getElementById('criteriaSelect').addEventListener('change', function() {
            setupCriteriaPerformanceChart();
        });
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'alert-circle'}"></ion-icon>
            <span>${message}</span>
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${type === 'success' ? '#d4edda' : '#f8d7da'};
            color: ${type === 'success' ? '#155724' : '#721c24'};
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
            border-left: 4px solid ${type === 'success' ? '#28a745' : '#dc3545'};
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Add CSS for notifications
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
    // Add function to open bobot modal (if exists)
    window.openBobotModal = function() {
        const bobotModal = document.getElementById('bobotModal');
        if (bobotModal) {
            bobotModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            alert('Modal pengaturan bobot belum tersedia. Pastikan komponen modal sudah ditambahkan.');
        }
    };
    
    // Add function to open hasil modal (if exists)
    window.openHasilModal = function() {
        const hasilModal = document.getElementById('hasilModal');
        if (hasilModal) {
            hasilModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            alert('Modal hasil perhitungan belum tersedia. Pastikan komponen modal sudah ditambahkan.');
        }
    };
});
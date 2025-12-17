document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let candidates = [];
    let currentBobot = {};
    let currentPage = 1;
    let itemsPerPage = 10;
    let filteredCandidates = [];
    let scoreDistributionChart = null;
    let criteriaComparisonChart = null;
    let comparisonChart = null;
    
    // Initialize
    loadData();
    initializeEventListeners();
    setupCharts();
    
    // Load data from localStorage or main table
    function loadData() {
        // Try to load from localStorage first
        const savedResults = localStorage.getItem('hasilPerhitungan');
        const savedBobot = localStorage.getItem('bobotSettings');
        
        if (savedBobot) {
            try {
                currentBobot = JSON.parse(savedBobot);
            } catch (e) {
                console.error('Error loading bobot settings:', e);
                currentBobot = getDefaultBobot();
            }
        } else {
            currentBobot = getDefaultBobot();
        }
        
        if (savedResults) {
            try {
                const data = JSON.parse(savedResults);
                if (data.candidates && data.candidates.length > 0) {
                    candidates = data.candidates;
                    filteredCandidates = [...candidates];
                    calculateAllScores();
                } else {
                    collectDataFromMainTable();
                }
            } catch (e) {
                console.error('Error loading saved results:', e);
                collectDataFromMainTable();
            }
        } else {
            collectDataFromMainTable();
        }
        
        updateUI();
        updateCharts();
    }
    
    function getDefaultBobot() {
        return {
            pengalaman: 30,
            jarak: 25,
            komunikasi: 25,
            fleksibilitas: 20
        };
    }
    
    function collectDataFromMainTable() {
        candidates = [];
        // This should be populated from your main table
        // For now, we'll use dummy data
        generateSampleData();
        filteredCandidates = [...candidates];
        calculateAllScores();
    }
    
    function generateSampleData() {
        const sampleNames = [
            'Ahmad Santoso', 'Budi Setiawan', 'Citra Lestari', 'Dewi Anggraini',
            'Eko Prasetyo', 'Fitriani Sari', 'Gunawan Wijaya', 'Hana Putri',
            'Irfan Maulana', 'Jihan Aulia'
        ];
        
        for (let i = 0; i < 10; i++) {
            candidates.push({
                id: `K${String(i+1).padStart(3, '0')}`,
                name: sampleNames[i],
                scores: {
                    pengalaman: Math.floor(Math.random() * 5) + 1,
                    jarak: Math.floor(Math.random() * 5) + 1,
                    komunikasi: Math.floor(Math.random() * 5) + 1,
                    fleksibilitas: Math.floor(Math.random() * 5) + 1
                },
                finalScore: 0,
                ranking: 0
            });
        }
    }
    
    function calculateAllScores() {
        candidates.forEach(candidate => {
            const result = calculateWeightedScore(candidate);
            candidate.finalScore = result.finalScore;
            candidate.weightedScores = result.weightedScores;
        });
        
        // Sort by final score
        candidates.sort((a, b) => b.finalScore - a.finalScore);
        
        // Assign ranking
        candidates.forEach((candidate, index) => {
            candidate.ranking = index + 1;
        });
        
        filteredCandidates = [...candidates];
    }
    
    function calculateWeightedScore(candidate) {
        const scores = candidate.scores;
        
        const weightedScores = {
            pengalaman: (scores.pengalaman / 5) * (currentBobot.pengalaman / 100),
            jarak: (scores.jarak / 5) * (currentBobot.jarak / 100),
            komunikasi: (scores.komunikasi / 5) * (currentBobot.komunikasi / 100),
            fleksibilitas: (scores.fleksibilitas / 5) * (currentBobot.fleksibilitas / 100)
        };
        
        const totalWeightedScore = Object.values(weightedScores).reduce((sum, score) => sum + score, 0);
        const finalScore = totalWeightedScore * 5;
        
        return {
            weightedScores: weightedScores,
            finalScore: parseFloat(finalScore.toFixed(2))
        };
    }
    
    function updateUI() {
        updateSummaryCards();
        updateWeightDisplay();
        updateRankingTable();
        updatePagination();
        updateTopThree();
        populateCandidateSelectors();
        updateFooterInfo();
    }
    
    function updateSummaryCards() {
        if (candidates.length === 0) return;
        
        const total = candidates.length;
        const highestScore = Math.max(...candidates.map(c => c.finalScore));
        const lowestScore = Math.min(...candidates.map(c => c.finalScore));
        const averageScore = candidates.reduce((sum, c) => sum + c.finalScore, 0) / total;
        const topCandidate = candidates.find(c => c.finalScore === highestScore);
        const bottomCandidate = candidates.find(c => c.finalScore === lowestScore);
        const recommendedCount = candidates.filter(c => c.finalScore >= 4.0).length;
        const needsReviewCount = candidates.filter(c => c.finalScore < 3.0).length;
        
        document.getElementById('totalKandidat').textContent = total;
        document.getElementById('skorTertinggi').textContent = highestScore.toFixed(2);
        document.getElementById('skorTerendah').textContent = lowestScore.toFixed(2);
        document.getElementById('rataRata').textContent = averageScore.toFixed(2);
        document.getElementById('direkomendasikan').textContent = recommendedCount;
        document.getElementById('perluPertimbangan').textContent = needsReviewCount;
        
        if (topCandidate) {
            document.getElementById('topCandidateName').textContent = topCandidate.name.split(' ')[0];
        }
        
        if (bottomCandidate) {
            document.getElementById('bottomCandidateName').textContent = bottomCandidate.name.split(' ')[0];
        }
    }
    
    function updateWeightDisplay() {
        document.getElementById('weightPengalaman').textContent = `${currentBobot.pengalaman}%`;
        document.getElementById('weightJarak').textContent = `${currentBobot.jarak}%`;
        document.getElementById('weightKomunikasi').textContent = `${currentBobot.komunikasi}%`;
        document.getElementById('weightFleksibilitas').textContent = `${currentBobot.fleksibilitas}%`;
        document.getElementById('totalWeight').textContent = '100%';
        
        // Update range fills
        document.querySelectorAll('.range-fill').forEach((fill, index) => {
            const weights = [currentBobot.pengalaman, currentBobot.jarak, currentBobot.komunikasi, currentBobot.fleksibilitas];
            const maxValues = [50, 40, 40, 40];
            const percentage = (weights[index] / maxValues[index]) * 100;
            fill.style.width = `${percentage}%`;
        });
    }
    
    function updateRankingTable() {
        const tbody = document.getElementById('rankingBody');
        tbody.innerHTML = '';
        
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, filteredCandidates.length);
        const pageCandidates = filteredCandidates.slice(startIndex, endIndex);
        
        pageCandidates.forEach(candidate => {
            const status = getStatusFromScore(candidate.finalScore);
            const initials = candidate.name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2);
            
            const row = document.createElement('tr');
            row.className = `rank-${candidate.ranking <= 3 ? candidate.ranking : 'other'}`;
            
            row.innerHTML = `
                <td class="rank-cell">
                    <div class="rank-badge">${candidate.ranking}</div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px;">
                            ${initials}
                        </div>
                        <div>
                            <strong>${candidate.name}</strong><br>
                            <small style="color: #666;">ID: ${candidate.id}</small>
                        </div>
                    </div>
                </td>
                <td class="score-cell">
                    <div class="score-value">${candidate.finalScore.toFixed(2)}</div>
                    <div class="score-rating">${getRatingText(candidate.finalScore)}</div>
                    <div class="score-progress">
                        <div class="score-progress-fill" style="width: ${(candidate.finalScore / 5) * 100}%; background: ${getScoreColor(candidate.finalScore)}"></div>
                    </div>
                </td>
                <td>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px;">
                        <small>Pengalaman: <strong>${candidate.scores.pengalaman}/5</strong></small>
                        <small>Jarak: <strong>${candidate.scores.jarak}/5</strong></small>
                        <small>Komunikasi: <strong>${candidate.scores.komunikasi}/5</strong></small>
                        <small>Fleksibilitas: <strong>${candidate.scores.fleksibilitas}/5</strong></small>
                    </div>
                </td>
                <td>
                    <span class="status-badge ${status.class}">${status.text}</span>
                </td>
                <td>
                    <div class="table-actions">
                        <button class="action-btn view-btn" data-id="${candidate.id}">
                            <ion-icon name="eye-outline"></ion-icon>
                        </button>
                        <button class="action-btn compare-btn" data-id="${candidate.id}">
                            <ion-icon name="git-compare-outline"></ion-icon>
                        </button>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
        
        // Add event listeners to action buttons
        tbody.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                viewCandidateDetails(candidateId);
            });
        });
        
        tbody.querySelectorAll('.compare-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                addCandidateToComparison(candidateId);
            });
        });
    }
    
    function updatePagination() {
        const totalItems = filteredCandidates.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage + 1;
        const endIndex = Math.min(startIndex + itemsPerPage - 1, totalItems);
        
        document.getElementById('pageStart').textContent = startIndex;
        document.getElementById('pageEnd').textContent = endIndex;
        document.getElementById('totalItems').textContent = totalItems;
        
        // Update pagination numbers
        const paginationNumbers = document.getElementById('paginationNumbers');
        paginationNumbers.innerHTML = '';
        
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
        
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-number ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.addEventListener('click', () => {
                currentPage = i;
                updateRankingTable();
                updatePagination();
            });
            paginationNumbers.appendChild(pageBtn);
        }
        
        // Update prev/next buttons
        document.getElementById('btnPrev').disabled = currentPage === 1;
        document.getElementById('btnNext').disabled = currentPage === totalPages;
    }
    
    function updateTopThree() {
        if (candidates.length < 3) return;
        
        const topCandidates = candidates.slice(0, 3);
        
        topCandidates.forEach((candidate, index) => {
            const num = index + 1;
            const initials = candidate.name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2);
            
            document.getElementById(`top${num}Avatar`).innerHTML = `<span>${initials}</span>`;
            document.getElementById(`top${num}Name`).textContent = candidate.name;
            document.getElementById(`top${num}Score`).textContent = `Skor: ${candidate.finalScore.toFixed(2)}`;
            document.getElementById(`top${num}Exp`).textContent = `${candidate.scores.pengalaman}/5`;
            document.getElementById(`top${num}Distance`).textContent = `${candidate.scores.jarak}/5`;
            document.getElementById(`top${num}Comm`).textContent = `${candidate.scores.komunikasi}/5`;
        });
    }
    
    function populateCandidateSelectors() {
        const selectors = [
            document.getElementById('selectCandidate'),
            document.getElementById('compareCandidate1'),
            document.getElementById('compareCandidate2'),
            document.getElementById('compareCandidate3')
        ];
        
        selectors.forEach(selector => {
            if (!selector) return;
            
            selector.innerHTML = '<option value="">-- Pilih Kandidat --</option>';
            candidates.forEach(candidate => {
                const option = document.createElement('option');
                option.value = candidate.id;
                option.textContent = `${candidate.name} (${candidate.id}) - Skor: ${candidate.finalScore.toFixed(2)}`;
                selector.appendChild(option);
            });
        });
    }
    
    function updateFooterInfo() {
        const now = new Date();
        document.getElementById('calculationTime').textContent = now.toLocaleTimeString('id-ID');
        document.getElementById('weightConfig').textContent = 'Custom';
        document.getElementById('reportVersion').textContent = '1.0';
    }
    
    function setupCharts() {
        // Setup score distribution chart
        const scoreCtx = document.getElementById('scoreDistributionChart').getContext('2d');
        scoreDistributionChart = new Chart(scoreCtx, {
            type: 'pie',
            data: {
                labels: ['Sangat Baik (4.0-5.0)', 'Baik (3.0-3.9)', 'Cukup (2.0-2.9)', 'Kurang (< 2.0)'],
                datasets: [{
                    data: [0, 0, 0, 0],
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
                }
            }
        });
        
        // Setup criteria comparison chart
        const criteriaCtx = document.getElementById('criteriaComparisonChart').getContext('2d');
        criteriaComparisonChart = new Chart(criteriaCtx, {
            type: 'radar',
            data: {
                labels: ['Pengalaman', 'Jarak', 'Komunikasi', 'Fleksibilitas'],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
        
        // Setup comparison chart
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        comparisonChart = new Chart(comparisonCtx, {
            type: 'bar',
            data: {
                labels: ['Pengalaman', 'Jarak', 'Komunikasi', 'Fleksibilitas', 'Skor Akhir'],
                datasets: []
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }
    
    function updateCharts() {
        if (!scoreDistributionChart || !criteriaComparisonChart) return;
        
        // Update score distribution chart
        const excellentCount = candidates.filter(c => c.finalScore >= 4.0).length;
        const goodCount = candidates.filter(c => c.finalScore >= 3.0 && c.finalScore < 4.0).length;
        const fairCount = candidates.filter(c => c.finalScore >= 2.0 && c.finalScore < 3.0).length;
        const poorCount = candidates.filter(c => c.finalScore < 2.0).length;
        
        scoreDistributionChart.data.datasets[0].data = [excellentCount, goodCount, fairCount, poorCount];
        scoreDistributionChart.update();
        
        // Update legend counts
        document.getElementById('countExcellent').textContent = excellentCount;
        document.getElementById('countGood').textContent = goodCount;
        document.getElementById('countFair').textContent = fairCount;
        document.getElementById('countPoor').textContent = poorCount;
    }
    
    function updateCriteriaComparisonChart(candidateId) {
        const candidate = candidates.find(c => c.id === candidateId);
        if (!candidate) return;
        
        criteriaComparisonChart.data.datasets = [{
            label: candidate.name,
            data: [
                candidate.scores.pengalaman,
                candidate.scores.jarak,
                candidate.scores.komunikasi,
                candidate.scores.fleksibilitas
            ],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(54, 162, 235, 1)'
        }];
        criteriaComparisonChart.update();
    }
    
    function initializeEventListeners() {
        // Navigation tabs
        document.querySelectorAll('.nav-tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update active tab
                document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Show corresponding section
                const target = this.getAttribute('href').substring(1);
                document.querySelectorAll('.page-section').forEach(section => {
                    section.classList.remove('active');
                });
                document.getElementById(target).classList.add('active');
            });
        });
        
        // Search
        document.getElementById('searchCandidate').addEventListener('input', function() {
            filterCandidates();
        });
        
        // Filters
        document.getElementById('filterStatus').addEventListener('change', function() {
            filterCandidates();
        });
        
        document.getElementById('filterRank').addEventListener('change', function() {
            filterCandidates();
        });
        
        document.getElementById('btnResetFilter').addEventListener('click', function() {
            document.getElementById('searchCandidate').value = '';
            document.getElementById('filterStatus').value = 'all';
            document.getElementById('filterRank').value = 'all';
            filterCandidates();
        });
        
        // Items per page
        document.getElementById('itemsPerPage').addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            updateRankingTable();
            updatePagination();
        });
        
        // Pagination buttons
        document.getElementById('btnPrev').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateRankingTable();
                updatePagination();
            }
        });
        
        document.getElementById('btnNext').addEventListener('click', function() {
            const totalPages = Math.ceil(filteredCandidates.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateRankingTable();
                updatePagination();
            }
        });
        
        // Analysis section
        document.getElementById('selectCandidate').addEventListener('change', function() {
            if (this.value) {
                showCandidateAnalysis(this.value);
            } else {
                hideCandidateAnalysis();
            }
        });
        
        document.getElementById('btnViewDetails').addEventListener('click', function() {
            const selectedId = document.getElementById('selectCandidate').value;
            if (selectedId) {
                viewCandidateDetails(selectedId);
            }
        });
        
        // Comparison section
        document.getElementById('btnCompareSelected').addEventListener('click', function() {
            compareSelectedCandidates();
        });
        
        // Export buttons
        document.getElementById('exportPDF').addEventListener('click', function(e) {
            e.preventDefault();
            exportToPDF();
        });
        
        document.getElementById('exportExcel').addEventListener('click', function(e) {
            e.preventDefault();
            exportToExcel();
        });
        
        document.getElementById('exportJSON').addEventListener('click', function(e) {
            e.preventDefault();
            exportToJSON();
        });
        
        // Other buttons
        document.getElementById('btnPrint').addEventListener('click', function() {
            window.print();
        });
        
        document.getElementById('btnRefresh').addEventListener('click', function() {
            loadData();
        });
        
        document.getElementById('btnSimpanSemua').addEventListener('click', function() {
            saveAllResults();
        });
        
        document.getElementById('btnBack').addEventListener('click', function() {
            window.history.back();
        });
        
        document.getElementById('btnFinalize').addEventListener('click', function() {
            finalizeResults();
        });
    }
    
    function filterCandidates() {
        const searchTerm = document.getElementById('searchCandidate').value.toLowerCase();
        const statusFilter = document.getElementById('filterStatus').value;
        const rankFilter = document.getElementById('filterRank').value;
        
        filteredCandidates = candidates.filter(candidate => {
            // Search filter
            const matchesSearch = candidate.name.toLowerCase().includes(searchTerm) || 
                                 candidate.id.toLowerCase().includes(searchTerm);
            
            // Status filter
            let matchesStatus = true;
            if (statusFilter !== 'all') {
                const status = getStatusFromScore(candidate.finalScore);
                matchesStatus = status.class.includes(statusFilter);
            }
            
            // Rank filter
            let matchesRank = true;
            if (rankFilter !== 'all') {
                const totalCandidates = candidates.length;
                switch (rankFilter) {
                    case 'top3':
                        matchesRank = candidate.ranking <= 3;
                        break;
                    case 'top10':
                        matchesRank = candidate.ranking <= 10;
                        break;
                    case 'top50%':
                        matchesRank = candidate.ranking <= Math.ceil(totalCandidates * 0.5);
                        break;
                }
            }
            
            return matchesSearch && matchesStatus && matchesRank;
        });
        
        currentPage = 1;
        updateRankingTable();
        updatePagination();
    }
    
    function showCandidateAnalysis(candidateId) {
        const candidate = candidates.find(c => c.id === candidateId);
        if (!candidate) return;
        
        const analysisCard = document.getElementById('analysisCard');
        analysisCard.innerHTML = `
            <div style="display: flex; gap: 30px; align-items: flex-start;">
                <div style="flex-shrink: 0;">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; font-weight: bold; margin-bottom: 20px;">
                        ${candidate.name.split(' ').map(word => word[0]).join('').toUpperCase().substring(0, 2)}
                    </div>
                    <div style="text-align: center;">
                        <div class="rank-badge" style="margin: 0 auto 10px auto;">${candidate.ranking}</div>
                        <span class="status-badge ${getStatusFromScore(candidate.finalScore).class}" style="display: inline-block;">
                            ${getStatusFromScore(candidate.finalScore).text}
                        </span>
                    </div>
                </div>
                
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 10px 0; color: #333; font-size: 24px;">${candidate.name}</h3>
                    <p style="color: #666; margin-bottom: 20px;">ID: ${candidate.id} | Skor Akhir: <strong style="color: #007bff; font-size: 20px;">${candidate.finalScore.toFixed(2)}</strong></p>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px;">
                        <div class="score-item">
                            <span style="font-size: 14px; color: #666;">Pengalaman</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                    <div style="width: ${(candidate.scores.pengalaman / 5) * 100}%; height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);"></div>
                                </div>
                                <span style="font-weight: bold; color: #333;">${candidate.scores.pengalaman}/5</span>
                            </div>
                        </div>
                        
                        <div class="score-item">
                            <span style="font-size: 14px; color: #666;">Jarak</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                    <div style="width: ${(candidate.scores.jarak / 5) * 100}%; height: 100%; background: linear-gradient(90deg, #4CAF50 0%, #2E7D32 100%);"></div>
                                </div>
                                <span style="font-weight: bold; color: #333;">${candidate.scores.jarak}/5</span>
                            </div>
                        </div>
                        
                        <div class="score-item">
                            <span style="font-size: 14px; color: #666;">Komunikasi</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                    <div style="width: ${(candidate.scores.komunikasi / 5) * 100}%; height: 100%; background: linear-gradient(90deg, #FF9800 0%, #F57C00 100%);"></div>
                                </div>
                                <span style="font-weight: bold; color: #333;">${candidate.scores.komunikasi}/5</span>
                            </div>
                        </div>
                        
                        <div class="score-item">
                            <span style="font-size: 14px; color: #666;">Fleksibilitas</span>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                    <div style="width: ${(candidate.scores.fleksibilitas / 5) * 100}%; height: 100%; background: linear-gradient(90deg, #9C27B0 0%, #7B1FA2 100%);"></div>
                                </div>
                                <span style="font-weight: bold; color: #333;">${candidate.scores.fleksibilitas}/5</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; margin-top: 20px;">
                        <h4 style="margin: 0 0 10px 0; color: #333; font-size: 16px;">Detail Perhitungan</h4>
                        <p style="margin: 0; color: #666; font-size: 14px;">
                            Skor akhir dihitung dengan rumus:<br>
                            (${candidate.scores.pengalaman}/5 × ${currentBobot.pengalaman}%) + 
                            (${candidate.scores.jarak}/5 × ${currentBobot.jarak}%) + 
                            (${candidate.scores.komunikasi}/5 × ${currentBobot.komunikasi}%) + 
                            (${candidate.scores.fleksibilitas}/5 × ${currentBobot.fleksibilitas}%) × 5
                            = ${candidate.finalScore.toFixed(2)}
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        // Update criteria comparison chart
        updateCriteriaComparisonChart(candidateId);
        
        // Update strengths and weaknesses
        updateCandidateAnalysis(candidate);
    }
    
    function hideCandidateAnalysis() {
        const analysisCard = document.getElementById('analysisCard');
        analysisCard.innerHTML = `
            <div class="card-placeholder">
                <ion-icon name="person-circle-outline"></ion-icon>
                <h3>Pilih kandidat untuk melihat analisis</h3>
                <p>Pilih kandidat dari dropdown di atas untuk menampilkan analisis detail</p>
            </div>
        `;
        
        // Clear criteria comparison chart
        criteriaComparisonChart.data.datasets = [];
        criteriaComparisonChart.update();
        
        // Clear analysis lists
        document.getElementById('strengthsList').innerHTML = '<p>Pilih kandidat untuk melihat analisis kekuatan</p>';
        document.getElementById('weaknessesList').innerHTML = '<p>Pilih kandidat untuk melihat area perbaikan</p>';
        document.getElementById('recommendationsList').innerHTML = '<p>Pilih kandidat untuk melihat rekomendasi</p>';
    }
    
    function updateCandidateAnalysis(candidate) {
        // Update strengths
        const strengths = getCandidateStrengths(candidate);
        const strengthsList = document.getElementById('strengthsList');
        strengthsList.innerHTML = strengths.length > 0 ? 
            '<ul>' + strengths.map(strength => `<li>${strength}</li>`).join('') + '</ul>' :
            '<p>Tidak ada kelebihan khusus yang teridentifikasi</p>';
        
        // Update weaknesses
        const weaknesses = getCandidateWeaknesses(candidate);
        const weaknessesList = document.getElementById('weaknessesList');
        weaknessesList.innerHTML = weaknesses.length > 0 ? 
            '<ul>' + weaknesses.map(weakness => `<li>${weakness}</li>`).join('') + '</ul>' :
            '<p>Tidak ada kelemahan signifikan yang teridentifikasi</p>';
        
        // Update recommendations
        const recommendations = getCandidateRecommendations(candidate);
        const recommendationsList = document.getElementById('recommendationsList');
        recommendationsList.innerHTML = recommendations.length > 0 ? 
            '<ul>' + recommendations.map(rec => `<li>${rec}</li>`).join('') + '</ul>' :
            '<p>Tidak ada rekomendasi khusus</p>';
    }
    
    function compareSelectedCandidates() {
        const candidate1Id = document.getElementById('compareCandidate1').value;
        const candidate2Id = document.getElementById('compareCandidate2').value;
        const candidate3Id = document.getElementById('compareCandidate3').value;
        
        const selectedIds = [candidate1Id, candidate2Id, candidate3Id].filter(id => id);
        
        if (selectedIds.length < 2) {
            alert('Pilih minimal 2 kandidat untuk perbandingan');
            return;
        }
        
        const selectedCandidates = selectedIds.map(id => candidates.find(c => c.id === id)).filter(c => c);
        
        // Update comparison table
        updateComparisonTable(selectedCandidates);
        
        // Update comparison chart
        updateComparisonChart(selectedCandidates);
        
        // Show comparison results
        document.getElementById('comparisonResults').style.display = 'none';
        document.getElementById('comparisonTableContainer').style.display = 'block';
        document.getElementById('comparisonChartContainer').style.display = 'block';
    }
    
    function updateComparisonTable(candidates) {
        const tableBody = document.getElementById('comparisonTableBody');
        tableBody.innerHTML = '';
        
        // Update headers
        candidates.forEach((candidate, index) => {
            document.getElementById(`compareHeader${index + 1}`).textContent = candidate.name;
        });
        
        // Hide empty headers
        for (let i = candidates.length + 1; i <= 3; i++) {
            document.getElementById(`compareHeader${i}`).style.display = 'none';
        }
        
        // Add rows for each criteria
        const criteria = [
            { name: 'Pengalaman', key: 'pengalaman', color: '#667eea' },
            { name: 'Jarak', key: 'jarak', color: '#4CAF50' },
            { name: 'Komunikasi', key: 'komunikasi', color: '#FF9800' },
            { name: 'Fleksibilitas', key: 'fleksibilitas', color: '#9C27B0' },
            { name: 'Skor Akhir', key: 'finalScore', color: '#007bff', isFinal: true }
        ];
        
        criteria.forEach(criterion => {
            const row = document.createElement('tr');
            const criterionCell = document.createElement('td');
            criterionCell.innerHTML = `<strong>${criterion.name}</strong>`;
            row.appendChild(criterionCell);
            
            candidates.forEach(candidate => {
                const cell = document.createElement('td');
                if (criterion.isFinal) {
                    cell.innerHTML = `
                        <div style="font-size: 20px; font-weight: bold; color: ${criterion.color};">${candidate[criterion.key].toFixed(2)}</div>
                        <div style="font-size: 12px; color: #666;">Ranking: ${candidate.ranking}</div>
                    `;
                } else {
                    const score = candidate.scores[criterion.key];
                    cell.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                                <div style="width: ${(score / 5) * 100}%; height: 100%; background: ${criterion.color};"></div>
                            </div>
                            <span style="font-weight: bold; color: #333;">${score}/5</span>
                        </div>
                    `;
                }
                row.appendChild(cell);
            });
            
            // Add empty cells for unused columns
            for (let i = candidates.length; i < 3; i++) {
                const cell = document.createElement('td');
                cell.innerHTML = '-';
                row.appendChild(cell);
            }
            
            tableBody.appendChild(row);
        });
    }
    
    function updateComparisonChart(candidates) {
        const datasets = candidates.map((candidate, index) => {
            const colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
            return {
                label: candidate.name,
                data: [
                    candidate.scores.pengalaman,
                    candidate.scores.jarak,
                    candidate.scores.komunikasi,
                    candidate.scores.fleksibilitas,
                    candidate.finalScore
                ],
                backgroundColor: colors[index],
                borderColor: colors[index],
                borderWidth: 2
            };
        });
        
        comparisonChart.data.datasets = datasets;
        comparisonChart.update();
    }
    
    function viewCandidateDetails(candidateId) {
        const candidate = candidates.find(c => c.id === candidateId);
        if (!candidate) return;
        
        // In a real application, this would open a detailed modal
        alert(`Detail Kandidat:\n\nNama: ${candidate.name}\nID: ${candidate.id}\nRanking: ${candidate.ranking}\nSkor: ${candidate.finalScore.toFixed(2)}\n\nPengalaman: ${candidate.scores.pengalaman}/5\nJarak: ${candidate.scores.jarak}/5\nKomunikasi: ${candidate.scores.komunikasi}/5\nFleksibilitas: ${candidate.scores.fleksibilitas}/5`);
    }
    
    function addCandidateToComparison(candidateId) {
        // Find an empty comparison selector and set it
        const selectors = [
            document.getElementById('compareCandidate1'),
            document.getElementById('compareCandidate2'),
            document.getElementById('compareCandidate3')
        ];
        
        for (const selector of selectors) {
            if (!selector.value) {
                selector.value = candidateId;
                break;
            }
        }
    }
    
    // Helper functions
    function getStatusFromScore(score) {
        if (score >= 4.0) {
            return { class: 'status-recommended', text: 'Direkomendasikan' };
        } else if (score >= 3.0) {
            return { class: 'status-qualified', text: 'Memenuhi Syarat' };
        } else {
            return { class: 'status-needs-review', text: 'Perlu Pertimbangan' };
        }
    }
    
    function getRatingText(score) {
        if (score >= 4.5) return 'Sangat Baik';
        if (score >= 4.0) return 'Baik';
        if (score >= 3.0) return 'Cukup';
        if (score >= 2.0) return 'Kurang';
        return 'Sangat Kurang';
    }
    
    function getScoreColor(score) {
        if (score >= 4.0) return '#4CAF50';
        if (score >= 3.0) return '#FF9800';
        if (score >= 2.0) return '#F44336';
        return '#9E9E9E';
    }
    
    function getCandidateStrengths(candidate) {
        const strengths = [];
        if (candidate.scores.pengalaman >= 4) strengths.push('Pengalaman kerja memadai');
        if (candidate.scores.jarak >= 4) strengths.push('Lokasi tempat tinggal strategis');
        if (candidate.scores.komunikasi >= 4) strengths.push('Kemampuan komunikasi sangat baik');
        if (candidate.scores.fleksibilitas >= 4) strengths.push('Sangat fleksibel dalam bekerja');
        if (candidate.finalScore >= 4.0) strengths.push('Skor keseluruhan sangat baik');
        return strengths;
    }
    
    function getCandidateWeaknesses(candidate) {
        const weaknesses = [];
        if (candidate.scores.pengalaman <= 2) weaknesses.push('Pengalaman kerja terbatas');
        if (candidate.scores.jarak <= 2) weaknesses.push('Jarak tempat tinggal kurang ideal');
        if (candidate.scores.komunikasi <= 2) weaknesses.push('Kemampuan komunikasi perlu ditingkatkan');
        if (candidate.scores.fleksibilitas <= 2) weaknesses.push('Kurang fleksibel dalam bekerja');
        return weaknesses;
    }
    
    function getCandidateRecommendations(candidate) {
        const recommendations = [];
        if (candidate.finalScore >= 4.0) {
            recommendations.push('Direkomendasikan untuk tahap selanjutnya');
            recommendations.push('Pertimbangkan untuk wawancara lanjutan');
        } else if (candidate.finalScore >= 3.0) {
            recommendations.push('Pertimbangkan jika ada kuota tersisa');
            recommendations.push('Mungkin memerlukan pelatihan tambahan');
        } else {
            recommendations.push('Pertimbangkan ulang untuk posisi ini');
            recommendations.push('Mungkin lebih cocok untuk posisi lain');
        }
        return recommendations;
    }
    
    // Export functions
    function exportToPDF() {
        alert('Fitur export PDF akan segera tersedia');
        // In a real application, you would use a library like jsPDF
    }
    
    function exportToExcel() {
        const data = [
            ['Ranking', 'ID', 'Nama', 'Pengalaman', 'Jarak', 'Komunikasi', 'Fleksibilitas', 'Skor Akhir', 'Status']
        ];
        
        candidates.forEach(candidate => {
            const status = getStatusFromScore(candidate.finalScore);
            data.push([
                candidate.ranking,
                candidate.id,
                candidate.name,
                candidate.scores.pengalaman,
                candidate.scores.jarak,
                candidate.scores.komunikasi,
                candidate.scores.fleksibilitas,
                candidate.finalScore.toFixed(2),
                status.text
            ]);
        });
        
        const csvContent = data.map(row => row.join(',')).join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `hasil-perhitungan-${new Date().toISOString().slice(0, 10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        alert('Data berhasil diexport ke CSV/Excel!');
    }
    
    function exportToJSON() {
        const data = {
            timestamp: new Date().toISOString(),
            bobot: currentBobot,
            candidates: candidates,
            summary: {
                total: candidates.length,
                highest: Math.max(...candidates.map(c => c.finalScore)),
                lowest: Math.min(...candidates.map(c => c.finalScore)),
                average: candidates.reduce((sum, c) => sum + c.finalScore, 0) / candidates.length
            }
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `hasil-perhitungan-${new Date().toISOString().slice(0, 10)}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        alert('Data berhasil diexport ke JSON!');
    }
    
    function saveAllResults() {
        const data = {
            timestamp: new Date().toISOString(),
            bobot: currentBobot,
            candidates: candidates
        };
        
        localStorage.setItem('hasilPerhitungan', JSON.stringify(data));
        
        // Show success notification
        showNotification('Semua hasil berhasil disimpan!', 'success');
    }
    
    function finalizeResults() {
        if (confirm('Finalisasi hasil perhitungan? Hasil akan dianggap final dan tidak dapat diubah.')) {
            // In a real application, this would save to the server
            showNotification('Hasil telah difinalisasi!', 'success');
        }
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
});
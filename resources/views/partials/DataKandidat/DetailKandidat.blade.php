<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal Detail Kandidat</title>
    <style>
        /* Modal Styles - SAMA DENGAN MODAL LAINNYA */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 0;
            border-radius: 10px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease-out;
            display: flex;
            flex-direction: column;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -60%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #e5e5e5;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            flex-shrink: 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-modal-detail {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.3s;
            background: none;
            border: none;
            color: white;
            padding: 0;
            width: 100px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            line-height: 1;
        }

        /* .close-modal:hover {
            transform: rotate(90deg);
            background: rgba(255, 255, 255, 0.2);
        } */

        .modal-body {
            padding: 30px;
            overflow-y: auto;
            flex-grow: 1;
            max-height: calc(90vh - 150px);
        }

        /* Scrollbar */
        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Modal Footer */
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #e5e5e5;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #f8f9fa;
            border-radius: 0 0 10px 10px;
        }

        /* =============== DETAIL CONTENT STYLES =============== */
        
        /* Candidate Header */
        .candidate-header {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            color: white;
            margin-bottom: 25px;
        }

        .candidate-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            flex-shrink: 0;
        }

        .candidate-main-info {
            flex: 1;
        }

        .candidate-main-info h3 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 600;
        }

        .candidate-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 5px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 12px;
            border-radius: 6px;
        }

        .meta-item ion-icon {
            font-size: 16px;
        }

        /* Score Highlight */
        .score-highlight {
            text-align: center;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            min-width: 120px;
        }

        .score-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .score-big {
            font-size: 32px;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 5px;
        }

        .score-rating {
            font-size: 13px;
            opacity: 0.9;
        }

        /* Info Grid - SAMA DENGAN DASHBOARD */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .info-card-title {
            font-size: 13px;
            color: #666;
            margin: 0;
        }

        .info-card-value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        /* Scores Section */
        .scores-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 600;
            color: #333;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e5e5;
        }

        .section-title ion-icon {
            font-size: 20px;
            color: #667eea;
        }

        /* Score Items */
        .score-item {
            margin-bottom: 20px;
        }

        .score-item:last-child {
            margin-bottom: 0;
        }

        .score-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .score-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .score-value-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
        }

        .score-bar {
            height: 10px;
            background: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .score-bar-fill {
            height: 100%;
            border-radius: 5px;
            transition: width 0.5s ease;
        }

        /* Statistics */
        .stats-mini-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-mini-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .stat-mini-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        .stat-mini-icon {
            font-size: 28px;
            margin-bottom: 10px;
            color: #667eea;
        }

        .stat-mini-value {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-mini-label {
            font-size: 13px;
            color: #666;
        }

        /* Timeline */
        .timeline-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .timeline-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 20px;
            color: white;
            position: relative;
            z-index: 1;
        }

        .timeline-content {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .timeline-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .timeline-date {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .timeline-description {
            font-size: 13px;
            color: #555;
        }

        /* Status Badges - SAMA DENGAN TABLE */
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status-recommended {
            background: #e7f9ed;
            color: #1b9d3f;
            border: 1px solid #c3e6cb;
        }

        .status-qualified {
            background: #fff4e5;
            color: #ff9800;
            border: 1px solid #ffeeba;
        }

        .status-needs-review {
            background: #ffebee;
            color: #f44336;
            border: 1px solid #f5c6cb;
        }

        .status-pending {
            background: #e7f1ff;
            color: #007bff;
            border: 1px solid #b3d7ff;
        }

        /* Buttons - SAMA DENGAN MODAL LAIN */
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn ion-icon {
            font-size: 18px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #333;
            border: 1px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        /* Score colors - SAMA DENGAN TABLE */
        .score-1 { background: linear-gradient(90deg, #dc3545, #c82333) !important; }
        .score-2 { background: linear-gradient(90deg, #fd7e14, #e65c00) !important; }
        .score-3 { background: linear-gradient(90deg, #ffc107, #e6a800) !important; color: #212529 !important; }
        .score-4 { background: linear-gradient(90deg, #28a745, #1e7e34) !important; }
        .score-5 { background: linear-gradient(90deg, #20c997, #17a589) !important; }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .modal-body {
                padding: 20px;
                max-height: calc(95vh - 140px);
            }

            .candidate-header {
                flex-direction: column;
                text-align: center;
                padding: 15px;
            }

            .candidate-avatar {
                margin: 0 auto;
            }

            .candidate-meta {
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-mini-grid {
                grid-template-columns: 1fr;
            }

            .score-highlight {
                margin-top: 15px;
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .score-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .score-value-badge {
                align-self: flex-start;
            }
        }

        @media (max-height: 700px) {
            .modal-content {
                max-height: 85vh;
            }
            
            .modal-body {
                max-height: calc(85vh - 150px);
            }
        }
    </style>
</head>
<body>

<!-- Modal Detail Kandidat -->
<div class="modal" id="detailModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                Detail Kandidat
            </h2>
            <button class="close-modal-detail">&times;</button>
        </div>
        
        <div class="modal-body">
            <!-- Header dengan Avatar dan Info Utama -->
            <div class="candidate-header">
                <div class="candidate-avatar">
                    <ion-icon name="person-circle-outline"></ion-icon>
                </div>
                <div class="candidate-main-info">
                    <h3 id="detailNama">-</h3>
                    <div class="candidate-meta">
                        <span class="meta-item">
                            <ion-icon name="pricetag-outline"></ion-icon>
                            ID: <strong id="detailId">-</strong>
                        </span>
                        <span class="meta-item">
                            <ion-icon name="calendar-outline"></ion-icon>
                            <span id="detailTanggal">-</span>
                        </span>
                        <span id="detailStatusBadge" class="status-badge">-</span>
                    </div>
                </div>
                <div class="score-highlight">
                    <div class="score-label">Skor Akhir</div>
                    <div class="score-big" id="detailScoreFinal">0.0</div>
                    <div class="score-rating" id="detailScoreRating">-</div>
                </div>
            </div>

            <!-- Info Cards Grid -->
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <ion-icon name="briefcase-outline"></ion-icon>
                        </div>
                        <p class="info-card-title">Pengalaman Kerja</p>
                    </div>
                    <h3 class="info-card-value" id="detailPengalamanValue">-</h3>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>
                        <p class="info-card-title">Jarak Tempat Tinggal</p>
                    </div>
                    <h3 class="info-card-value" id="detailJarakValue">-</h3>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                            <ion-icon name="chatbubbles-outline"></ion-icon>
                        </div>
                        <p class="info-card-title">Kemampuan Komunikasi</p>
                    </div>
                    <h3 class="info-card-value" id="detailKomunikasiValue">-</h3>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon" style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);">
                            <ion-icon name="time-outline"></ion-icon>
                        </div>
                        <p class="info-card-title">Fleksibilitas Waktu</p>
                    </div>
                    <h3 class="info-card-value" id="detailFleksibilitasValue">-</h3>
                </div>
            </div>

            <!-- Scores Detail Section -->
            <div class="scores-section">
                <div class="section-title">
                    <ion-icon name="bar-chart-outline"></ion-icon>
                    Detail Penilaian Kriteria
                </div>

                <div class="score-item">
                    <div class="score-header">
                        <span class="score-name">
                            <ion-icon name="briefcase-outline"></ion-icon> Pengalaman Kerja
                        </span>
                        <span class="score-value-badge" id="badgePengalaman">
                            <ion-icon name="star"></ion-icon> -
                        </span>
                    </div>
                    <div class="score-bar">
                        <div class="score-bar-fill" id="barPengalaman"></div>
                    </div>
                </div>

                <div class="score-item">
                    <div class="score-header">
                        <span class="score-name">
                            <ion-icon name="location-outline"></ion-icon> Jarak Tempat Tinggal
                        </span>
                        <span class="score-value-badge" id="badgeJarak">
                            <ion-icon name="star"></ion-icon> -
                        </span>
                    </div>
                    <div class="score-bar">
                        <div class="score-bar-fill" id="barJarak"></div>
                    </div>
                </div>

                <div class="score-item">
                    <div class="score-header">
                        <span class="score-name">
                            <ion-icon name="chatbubbles-outline"></ion-icon> Kemampuan Komunikasi
                        </span>
                        <span class="score-value-badge" id="badgeKomunikasi">
                            <ion-icon name="star"></ion-icon> -
                        </span>
                    </div>
                    <div class="score-bar">
                        <div class="score-bar-fill" id="barKomunikasi"></div>
                    </div>
                </div>

                <div class="score-item">
                    <div class="score-header">
                        <span class="score-name">
                            <ion-icon name="time-outline"></ion-icon> Fleksibilitas Waktu
                        </span>
                        <span class="score-value-badge" id="badgeFleksibilitas">
                            <ion-icon name="star"></ion-icon> -
                        </span>
                    </div>
                    <div class="score-bar">
                        <div class="score-bar-fill" id="barFleksibilitas"></div>
                    </div>
                </div>
            </div>

            <!-- Mini Statistics -->
            <div class="stats-mini-grid">
                <div class="stat-mini-card">
                    <ion-icon name="calculator-outline" class="stat-mini-icon"></ion-icon>
                    <div class="stat-mini-value" id="detailRataRata">0.0</div>
                    <div class="stat-mini-label">Rata-rata Skor</div>
                </div>
                <div class="stat-mini-card">
                    <ion-icon name="trending-up-outline" class="stat-mini-icon"></ion-icon>
                    <div class="stat-mini-value" id="detailPersentase">0%</div>
                    <div class="stat-mini-label">Persentase Kelayakan</div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-section">
                <div class="section-title">
                    <ion-icon name="git-commit-outline"></ion-icon>
                    Riwayat Status
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot" style="background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);">
                        <ion-icon name="checkmark-circle"></ion-icon>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Kandidat Terdaftar</div>
                        <div class="timeline-date" id="timelineCreated">-</div>
                    </div>
                </div>

                <div class="timeline-item" id="timelineAssessment">
                    <div class="timeline-dot" style="background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);">
                        <ion-icon name="create"></ion-icon>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Penilaian Dilakukan</div>
                        <div class="timeline-date">Hari ini</div>
                    </div>
                </div>

                <div class="timeline-item" id="timelineStatus">
                    <div class="timeline-dot" style="background: linear-gradient(135deg, #2196F3 0%, #0D47A1 100%);">
                        <ion-icon name="information-circle"></ion-icon>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-title">Status Saat Ini</div>
                        <div class="timeline-date">Sekarang</div>
                        <div class="timeline-description" id="timelineStatusDesc">-</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal-detail">
                <ion-icon name="close-outline"></ion-icon> Tutup
            </button>
        </div>
    </div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
// Function untuk menampilkan detail kandidat
function showDetailModal(candidateData) {
    const modal = document.getElementById('detailModal');
    
    // Populate data
    document.getElementById('detailNama').textContent = candidateData.name;
    document.getElementById('detailId').textContent = candidateData.id;
    document.getElementById('detailTanggal').textContent = formatDate(candidateData.createdAt);
    
    // Status badge
    const statusBadge = document.getElementById('detailStatusBadge');
    statusBadge.textContent = getStatusText(candidateData.status);
    statusBadge.className = `status-badge ${getStatusClass(candidateData.status)}`;
    
    // Skor final
    const finalScore = calculateFinalScore(candidateData);
    document.getElementById('detailScoreFinal').textContent = finalScore.toFixed(2);
    document.getElementById('detailScoreRating').textContent = getRatingText(finalScore);
    
    // Info cards
    document.getElementById('detailPengalamanValue').textContent = getScoreText(candidateData.scores.pengalaman);
    document.getElementById('detailJarakValue').textContent = getScoreText(candidateData.scores.jarak);
    document.getElementById('detailKomunikasiValue').textContent = getScoreText(candidateData.scores.komunikasi);
    document.getElementById('detailFleksibilitasValue').textContent = getScoreText(candidateData.scores.fleksibilitas);
    
    // Score bars dan badges
    updateScoreBar('Pengalaman', candidateData.scores.pengalaman);
    updateScoreBar('Jarak', candidateData.scores.jarak);
    updateScoreBar('Komunikasi', candidateData.scores.komunikasi);
    updateScoreBar('Fleksibilitas', candidateData.scores.fleksibilitas);
    
    // Statistics
    document.getElementById('detailRataRata').textContent = finalScore.toFixed(2);
    document.getElementById('detailPersentase').textContent = Math.round((finalScore / 5) * 100) + '%';
    
    // Timeline
    document.getElementById('timelineCreated').textContent = formatDate(candidateData.createdAt);
    document.getElementById('timelineStatusDesc').textContent = getStatusDescription(candidateData.status);
    
    // Show modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function updateScoreBar(criterion, score) {
    const bar = document.getElementById(`bar${criterion}`);
    const badge = document.getElementById(`badge${criterion}`);
    
    const percentage = (score / 5) * 100;
    bar.style.width = `${percentage}%`;
    bar.className = 'score-bar-fill';
    bar.classList.add(`score-${score}`);
    
    badge.textContent = `${getScoreText(score)} (${score}/5)`;
    badge.className = 'score-value-badge';
    badge.classList.add(`score-${score}`);
}

function calculateFinalScore(candidate) {
    const scores = candidate.scores;
    const average = (scores.pengalaman + scores.jarak + scores.komunikasi + scores.fleksibilitas) / 4;
    return parseFloat(average.toFixed(2));
}

function getScoreText(score) {
    if (score >= 4.5) return 'Sangat Baik';
    if (score >= 3.5) return 'Baik';
    if (score >= 2.5) return 'Cukup';
    return 'Kurang';
}

function getRatingText(score) {
    if (score >= 4.0) return 'Direkomendasikan';
    if (score >= 3.0) return 'Memenuhi Syarat';
    return 'Perlu Review';
}

function getStatusClass(status) {
    switch (status) {
        case 'recommended': return 'status-recommended';
        case 'qualified': return 'status-qualified';
        case 'needs-review': return 'status-needs-review';
        default: return 'status-pending';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'recommended': return 'Direkomendasikan';
        case 'qualified': return 'Memenuhi Syarat';
        case 'needs-review': return 'Perlu Ditinjau';
        default: return 'Pending';
    }
}

function getStatusDescription(status) {
    switch (status) {
        case 'recommended': return 'Kandidat direkomendasikan untuk posisi ini.';
        case 'qualified': return 'Kandidat memenuhi syarat untuk posisi ini.';
        case 'needs-review': return 'Kandidat perlu ditinjau lebih lanjut.';
        default: return 'Kandidat masih dalam proses penilaian.';
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Close modal functionality
document.querySelectorAll('.close-modal-detail').forEach(button => {
    button.addEventListener('click', () => {
        const modal = document.getElementById('detailModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
});

// Close modal dengan klik di luar content
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Close modal dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('detailModal');
        if (modal.style.display === 'flex') {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
});
</script>
</body>
</html>
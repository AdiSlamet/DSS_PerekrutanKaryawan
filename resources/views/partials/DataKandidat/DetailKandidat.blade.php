<!-- Modal Detail Kandidat -->
<div id="modalDetailKandidat" class="modal-overlay" style="display: none;">
    <div class="modal-container modal-detail">
        <div class="modal-header">
            <h2>
                <ion-icon name="information-circle-outline"></ion-icon>
                Detail Kandidat
            </h2>
            <button class="modal-close" onclick="closeModal('modalDetailKandidat')">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="detail-card">
                <div class="detail-avatar">
                    <div class="avatar-placeholder" id="detailAvatar">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                </div>
                
                <div class="detail-info">
                    <h3 id="detailNama">-</h3>
                    <span class="detail-subtitle">Kandidat ID: <strong id="detailId">-</strong></span>
                </div>
            </div>
            
            <div class="detail-sections">
                <div class="detail-section">
                    <div class="section-title">
                        <ion-icon name="information-outline"></ion-icon>
                        Informasi Umum
                    </div>
                    <div class="section-content">
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge" id="detailStatusBadge">
                                    <span id="detailStatus">-</span>
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tanggal Daftar</span>
                            <span class="info-value" id="detailTanggal">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeModal('modalDetailKandidat')">
                <ion-icon name="close-outline"></ion-icon>
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
.modal-detail .modal-container {
    max-width: 550px;
}

.detail-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    margin-bottom: 24px;
}

.detail-avatar {
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.avatar-placeholder ion-icon {
    font-size: 40px;
    color: white;
}

.detail-info {
    flex: 1;
    color: white;
}

.detail-info h3 {
    margin: 0 0 6px 0;
    font-size: 24px;
    font-weight: 700;
}

.detail-subtitle {
    font-size: 14px;
    opacity: 0.9;
}

.detail-subtitle strong {
    font-weight: 600;
}

.detail-sections {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-section {
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.section-title ion-icon {
    font-size: 18px;
    color: #6366f1;
}

.section-content {
    padding: 16px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    font-size: 14px;
    color: #6b7280;
    font-weight: 500;
}

.info-value {
    font-size: 14px;
    color: #1f2937;
    font-weight: 600;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.status-lolos {
    background: #dcfce7;
    color: #16a34a;
}

.status-badge.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.status-tidak-lolos {
    background: #fee2e2;
    color: #dc2626;
}
</style>

<script>
// Update status badge styling based on status
function updateDetailStatus(status) {
    const badge = document.getElementById('detailStatusBadge');
    const statusLower = (status || 'pending').toLowerCase();
    badge.className = 'status-badge status-' + statusLower;
}

// Listen for modal show event to update status styling
const detailModal = document.getElementById('modalDetailKandidat');
if (detailModal) {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'style') {
                if (detailModal.style.display !== 'none') {
                    const status = document.getElementById('detailStatus').textContent;
                    updateDetailStatus(status);
                }
            }
        });
    });
    
    observer.observe(detailModal, { attributes: true });
}
</script>
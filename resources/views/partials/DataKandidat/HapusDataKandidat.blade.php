<!-- Modal Hapus Kandidat -->
<div id="modalHapusKandidat" class="modal-overlay" style="display: none;">
    <div class="modal-container modal-delete">
        <div class="modal-header">
            <h2>
                <ion-icon name="warning-outline"></ion-icon>
                Konfirmasi Hapus
            </h2>
            <button class="modal-close" onclick="closeModalHapus()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        
        <div class="modal-body">
            <input type="hidden" id="deleteKandidatId">
            
            <div class="delete-warning">
                <div class="warning-icon">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                </div>
                <div class="warning-content">
                    <h3>Apakah Anda yakin?</h3>
                    <p>
                        Anda akan menghapus kandidat <strong id="deleteKandidatNama"></strong>.
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>
            
            <div class="delete-info">
                <ion-icon name="information-circle-outline"></ion-icon>
                <span>Data yang dihapus akan hilang permanen dari sistem</span>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="button" class="btn btn-outline" onclick="closeModalHapus()">
                <ion-icon name="close-outline"></ion-icon>
                Batal
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmDeleteKandidat()">
                <ion-icon name="trash-outline"></ion-icon>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<style>
/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.modal-container {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

.modal-delete .modal-container {
    max-width: 450px;
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px;
    border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
    font-size: 20px;
    color: #1f2937;
}

.modal-header ion-icon {
    font-size: 24px;
    color: #ef4444;
}

.modal-close {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    color: #6b7280;
    transition: all 0.2s;
    border-radius: 8px;
}

.modal-close:hover {
    background: #f3f4f6;
    color: #1f2937;
}

.modal-close ion-icon {
    font-size: 24px;
}

.modal-body {
    padding: 24px;
}

.delete-warning {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
}

.warning-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-radius: 12px;
}

.warning-icon ion-icon {
    font-size: 28px;
    color: #ef4444;
}

.warning-content h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    color: #1f2937;
}

.warning-content p {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
    line-height: 1.6;
}

.warning-content strong {
    color: #ef4444;
}

.delete-info {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    background: #f9fafb;
    border-radius: 8px;
    border-left: 3px solid #6366f1;
}

.delete-info ion-icon {
    font-size: 18px;
    color: #6366f1;
    flex-shrink: 0;
}

.delete-info span {
    font-size: 13px;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding: 24px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.btn-outline {
    background: white;
    border: 2px solid #e5e7eb;
    color: #6b7280;
}

.btn-outline:hover {
    background: #f9fafb;
    border-color: #d1d5db;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Close modal function
function closeModalHapus() {
    const modal = document.getElementById('modalHapusKandidat');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Confirm delete function
async function confirmDeleteKandidat() {
    const id = document.getElementById('deleteKandidatId').value;
    
    if (!id) {
        console.error('No kandidat ID found');
        return;
    }
    
    try {
        const response = await fetch(`/api/kandidat/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Gagal menghapus kandidat');
        }
        
        const result = await response.json();
        
        // Show success notification
        if (typeof showNotification === 'function') {
            showNotification(result.message || 'Kandidat berhasil dihapus', 'success');
        }
        
        // Close modal
        closeModalHapus();
        
        // Reload data
        if (typeof loadKandidatData === 'function') {
            const currentPeriode = document.getElementById('periodeSelect')?.value || null;
            loadKandidatData(currentPeriode);
        }
    } catch (error) {
        console.error('Error:', error);
        if (typeof showNotification === 'function') {
            showNotification('Gagal menghapus kandidat', 'error');
        }
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalHapusKandidat');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModalHapus();
            }
        });
    }
});
</script>
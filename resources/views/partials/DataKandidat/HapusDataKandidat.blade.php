<!-- Modal Hapus -->
<div id="hapusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>
                <ion-icon name="warning-outline"></ion-icon>
                Konfirmasi Hapus
            </h2>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="delete-confirmation">
                <div class="warning-icon">
                    <ion-icon name="alert-circle-outline"></ion-icon>
                </div>
                <p class="confirmation-text">
                    Apakah Anda yakin ingin menghapus data kandidat ini?
                </p>
                <div class="data-preview">
                    <p><strong>Nama:</strong> <span id="deleteNama">-</span></p>
                    <p><strong>Posisi:</strong> <span id="deletePosisi">-</span></p>
                    <p><strong>Skor:</strong> <span id="deleteSkor">-</span></p>
                </div>
                <p class="warning-note">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Data yang telah dihapus tidak dapat dikembalikan.
                </p>
            </div>
            
            <div class="modal-actions">
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <ion-icon name="trash-outline"></ion-icon> Ya, Hapus
                </button>
                <button type="button" class="btn btn-secondary close-modal">
                    <ion-icon name="close-outline"></ion-icon> Batal
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===================== DELETE MODAL STYLES ===================== */
.delete-confirmation {
    text-align: center;
    padding: 20px 0;
}

.warning-icon {
    font-size: 60px;
    color: #dc3545;
    margin-bottom: 20px;
}

.warning-icon ion-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.confirmation-text {
    font-size: 18px;
    font-weight: 500;
    color: var(--black1);
    margin-bottom: 25px;
    line-height: 1.5;
}

.data-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin: 20px 0;
    text-align: left;
    border-left: 4px solid #dc3545;
}

.data-preview p {
    margin: 8px 0;
    font-size: 14px;
}

.data-preview strong {
    color: var(--black1);
    min-width: 70px;
    display: inline-block;
}

.data-preview span {
    color: var(--black2);
}

.warning-note {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 12px;
    font-size: 13px;
    color: #856404;
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.warning-note ion-icon {
    font-size: 18px;
    flex-shrink: 0;
}

/* Danger Button */
.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Modal actions khusus untuk delete */
#hapusModal .modal-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e5e5e5;
}

#hapusModal .modal-actions .btn {
    min-width: 130px;
}

/* Responsive */
@media (max-width: 768px) {
    .warning-icon {
        font-size: 50px;
    }
    
    .confirmation-text {
        font-size: 16px;
    }
    
    .data-preview {
        padding: 12px;
    }
}
</style>
<!-- Modal Edit Kandidat -->
<div id="modalEditKandidat" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h2>
                <ion-icon name="create-outline"></ion-icon>
                Edit Data Kandidat
            </h2>
            <button class="modal-close" onclick="closeModalEdit()">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        
        <form id="formEditKandidat" class="modal-form">
            <input type="hidden" id="editKandidatId">
            
            <div class="form-group">
                <label for="editNama">
                    Nama Lengkap <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="editNama" 
                    name="nama" 
                    class="form-control" 
                    placeholder="Masukkan nama lengkap kandidat"
                    required
                >
                <small class="form-text">Perbarui nama kandidat sesuai kebutuhan</small>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeModalEdit()">
                    <ion-icon name="close-outline"></ion-icon>
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <ion-icon name="checkmark-outline"></ion-icon>
                    Update Data
                </button>
            </div>
        </form>
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
    color: #6366f1;
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

.modal-form {
    padding: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.required {
    color: #ef4444;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-text {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 24px;
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

.btn-primary {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
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
function closeModalEdit() {
    const modal = document.getElementById('modalEditKandidat');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Form submit handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditKandidat');
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('editKandidatId').value;
            const formData = {
                nama: document.getElementById('editNama').value
            };
            
            if (typeof updateKandidat === 'function') {
                await updateKandidat(id, formData);
            }
        });
    }
});

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalEditKandidat');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModalEdit();
            }
        });
    }
});
</script>
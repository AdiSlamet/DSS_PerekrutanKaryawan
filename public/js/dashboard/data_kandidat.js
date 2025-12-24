// Configuration
const API_BASE_URL = '/api/kandidat';
let currentPage = 1;
let itemsPerPage = 10;
let totalItems = 0;
let allKandidatData = [];
let selectedKandidatIds = [];
let currentPeriode = '';
let availablePeriodes = [];

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    loadKandidatData();
});

// Event Listeners
function initializeEventListeners() {
    // Periode selector
    document.getElementById('periodeSelect').addEventListener('change', handlePeriodeChange);
    
    // Search input
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    
    // Buttons
    document.getElementById('btnTambahBaru').addEventListener('click', openTambahModal);
    document.getElementById('btnImport').addEventListener('click', handleImport);
    
    // Pagination
    document.getElementById('itemsPerPage').addEventListener('change', handleItemsPerPageChange);
    document.getElementById('btnPrev').addEventListener('click', () => changePage(currentPage - 1));
    document.getElementById('btnNext').addEventListener('click', () => changePage(currentPage + 1));
    
    
    // Batch actions
    document.getElementById('btnBatchDelete').addEventListener('click', handleBatchDelete);
    document.getElementById('btnBatchExport').addEventListener('click', handleBatchExport);
    document.getElementById('btnBatchReview').addEventListener('click', handleBatchReview);
}

// Load Data from API
async function loadKandidatData(periode = null) {
    try {
        showLoading();
        
        const url = periode ? `${API_BASE_URL}?periode=${periode}` : API_BASE_URL;
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error('Gagal memuat data');
        }
        
        const result = await response.json();
        allKandidatData = result.data || [];
        
        // Extract dan populate periode dari data
        extractAndPopulatePeriodes();
        
        updateStatistics();
        renderTable();
        updateLastUpdate();
        
        hideLoading();
    } catch (error) {
        console.error('Error loading data:', error);
        showNotification('Gagal memuat data kandidat', 'error');
        hideLoading();
    }
}

// Extract dan Populate Periode Selector
function extractAndPopulatePeriodes() {
    // Extract unique periode dari data
    const periodes = new Set();
    
    allKandidatData.forEach(kandidat => {
        if (kandidat.created_at) {
            const date = new Date(kandidat.created_at);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const periodeValue = `${year}-${month}`;
            periodes.add(periodeValue);
        }
    });
    
    // Convert ke array dan sort descending (terbaru di atas)
    availablePeriodes = Array.from(periodes).sort((a, b) => b.localeCompare(a));
    
    // Populate select options
    const periodeSelect = document.getElementById('periodeSelect');
    periodeSelect.innerHTML = '<option value="">Semua Periode</option>';
    
    availablePeriodes.forEach(periode => {
        const [year, month] = periode.split('-');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        const monthName = monthNames[parseInt(month) - 1];
        const displayText = `${monthName} ${year}`;
        
        const option = document.createElement('option');
        option.value = periode;
        option.textContent = displayText;
        periodeSelect.appendChild(option);
    });
    
    // Set current periode jika belum di-set atau jika currentPeriode tidak ada di list
    if (!currentPeriode || !availablePeriodes.includes(currentPeriode)) {
        if (availablePeriodes.length > 0) {
            currentPeriode = availablePeriodes[0]; // Set ke periode terbaru
            periodeSelect.value = currentPeriode;
            updatePeriodeDisplay(currentPeriode);
        } else {
            currentPeriode = '';
            periodeSelect.value = '';
            updatePeriodeDisplay('Belum Ada Data');
        }
    }
}

// Update Periode Display
function updatePeriodeDisplay(periodeValue) {
    if (!periodeValue || periodeValue === 'Belum Ada Data') {
        document.getElementById('currentPeriode').textContent = 'Belum Ada Data';
        document.getElementById('activePeriod').textContent = '-';
        return;
    }
    
    const [year, month] = periodeValue.split('-');
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const monthName = monthNames[parseInt(month) - 1];
    const displayText = `${monthName} ${year}`;
    
    document.getElementById('currentPeriode').textContent = displayText;
    document.getElementById('activePeriod').textContent = `${monthName.substring(0, 3)} ${year}`;
}

// Update Statistics
function updateStatistics() {
    const total = allKandidatData.length;
    const topRated = allKandidatData.filter(k => k.status === 'Lolos').length;
    const needReview = allKandidatData.filter(k => k.status === 'Pending' || !k.status).length;
    
    document.getElementById('totalKandidat').textContent = total;
    document.getElementById('topRated').textContent = topRated;
    document.getElementById('pendingReview').textContent = needReview;
    
    totalItems = total;
}

// Render Table
function renderTable() {
    const tbody = document.getElementById('candidatesTableBody');
    const emptyState = document.getElementById('emptyState');
    const paginationContainer = document.getElementById('paginationContainer');
    
    if (allKandidatData.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'flex';
        paginationContainer.style.display = 'none';
        return;
    }
    
    emptyState.style.display = 'none';
    paginationContainer.style.display = 'flex';
    
    // Pagination calculation
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, allKandidatData.length);
    const pageData = allKandidatData.slice(startIndex, endIndex);
    
    // Render rows
    tbody.innerHTML = pageData.map((kandidat, index) => `
        <tr data-id="${kandidat.id}">
            <td>${startIndex + index + 1}</td>
            <td>
                <div class="candidate-info">
                    <div class="candidate-avatar">
                        ${kandidat.nama.charAt(0).toUpperCase()}
                    </div>
                    <div class="candidate-details">
                        <strong>${kandidat.nama}</strong>
                        <span class="candidate-id">ID: ${kandidat.id}</span>
                    </div>
                </div>
            </td>
            <td>
                <span class="status-badge status-${(kandidat.status || 'pending').toLowerCase()}">
                    ${kandidat.status || 'Pending'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-view" onclick="viewKandidat(${kandidat.id})" title="Lihat Detail">
                        <ion-icon name="eye-outline"></ion-icon>
                    </button>
                    <button class="btn-action btn-edit" onclick="editKandidat(${kandidat.id})" title="Edit">
                        <ion-icon name="create-outline"></ion-icon>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteKandidat(${kandidat.id})" title="Hapus">
                        <ion-icon name="trash-outline"></ion-icon>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    // Add event listeners to checkboxes
    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', handleRowCheckboxChange);
    });
    
    updatePagination();
}

// Update Pagination
function updatePagination() {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const startItem = (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);
    
    document.getElementById('pageStart').textContent = startItem;
    document.getElementById('pageEnd').textContent = endItem;
    document.getElementById('totalItems').textContent = totalItems;
    
    document.getElementById('btnPrev').disabled = currentPage === 1;
    document.getElementById('btnNext').disabled = currentPage === totalPages;
    
    // Render page numbers
    const paginationNumbers = document.getElementById('paginationNumbers');
    let pagesHtml = '';
    
    for (let i = 1; i <= Math.min(totalPages, 5); i++) {
        pagesHtml += `
            <button class="pagination-number ${i === currentPage ? 'active' : ''}" 
                onclick="changePage(${i})">
                ${i}
            </button>
        `;
    }
    
    paginationNumbers.innerHTML = pagesHtml;
}

// Change Page
function changePage(page) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    
    currentPage = page;
    renderTable();
}

// Handle Items Per Page Change
function handleItemsPerPageChange(e) {
    itemsPerPage = parseInt(e.target.value);
    currentPage = 1;
    renderTable();
}

// Handle Periode Change
function handlePeriodeChange(e) {
    currentPeriode = e.target.value;
    
    if (currentPeriode) {
        updatePeriodeDisplay(currentPeriode);
    } else {
        document.getElementById('currentPeriode').textContent = 'Semua Periode';
        document.getElementById('activePeriod').textContent = 'Semua';
    }
    
    loadKandidatData(currentPeriode);
}

// Handle Search
function handleSearch(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    if (!searchTerm) {
        renderTable();
        return;
    }
    
    const filteredData = allKandidatData.filter(kandidat => 
        kandidat.nama.toLowerCase().includes(searchTerm) ||
        kandidat.id.toString().includes(searchTerm)
    );
    
    const tempData = allKandidatData;
    allKandidatData = filteredData;
    totalItems = filteredData.length;
    currentPage = 1;
    renderTable();
    allKandidatData = tempData;
}

// Open Tambah Modal
function openTambahModal() {
    const modal = document.getElementById('modalTambahKandidat');
    if (modal) {
        modal.style.display = 'flex';
        document.getElementById('formTambahKandidat').reset();
    }
}

// Tambah Kandidat
async function tambahKandidat(formData) {
    try {
        const response = await fetch(API_BASE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            throw new Error('Gagal menambah kandidat');
        }
        
        const result = await response.json();
        showNotification(result.massage || 'Kandidat berhasil ditambahkan', 'success');
        closeModal('modalTambahKandidat');
        
        // Reload data tanpa filter periode untuk mendapatkan semua data terbaru
        await loadKandidatData();
        
        // Set periode ke yang baru saja ditambahkan jika berbeda
        if (formData.created_at) {
            const date = new Date(formData.created_at);
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const newPeriode = `${year}-${month}`;
            
            if (newPeriode !== currentPeriode) {
                currentPeriode = newPeriode;
                document.getElementById('periodeSelect').value = newPeriode;
                updatePeriodeDisplay(newPeriode);
                loadKandidatData(currentPeriode);
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menambah kandidat', 'error');
    }
}

// View Kandidat
async function viewKandidat(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/${id}`);
        
        if (!response.ok) {
            throw new Error('Gagal memuat detail kandidat');
        }
        
        const result = await response.json();
        const kandidat = result.data;
        
        // Populate detail modal
        document.getElementById('detailNama').textContent = kandidat.nama;
        document.getElementById('detailId').textContent = kandidat.id;
        document.getElementById('detailStatus').textContent = kandidat.status || 'Pending';
        document.getElementById('detailTanggal').textContent = new Date(kandidat.created_at).toLocaleDateString('id-ID');
        
        // Show modal
        const modal = document.getElementById('modalDetailKandidat');
        if (modal) {
            modal.style.display = 'flex';
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat detail kandidat', 'error');
    }
}

// Edit Kandidat
async function editKandidat(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/${id}`);
        
        if (!response.ok) {
            throw new Error('Gagal memuat data kandidat');
        }
        
        const result = await response.json();
        const kandidat = result.data;
        
        // Populate edit form
        document.getElementById('editKandidatId').value = kandidat.id;
        document.getElementById('editNama').value = kandidat.nama;
        
        // Show modal
        const modal = document.getElementById('modalEditKandidat');
        if (modal) {
            modal.style.display = 'flex';
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memuat data kandidat', 'error');
    }
}

// Update Kandidat
async function updateKandidat(id, formData) {
    try {
        const response = await fetch(`${API_BASE_URL}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify(formData)
        });
        
        if (!response.ok) {
            throw new Error('Gagal memperbarui kandidat');
        }
        
        const result = await response.json();
        showNotification(result.message || 'Kandidat berhasil diperbarui', 'success');
        closeModal('modalEditKandidat');
        loadKandidatData(currentPeriode);
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal memperbarui kandidat', 'error');
    }
}

// Delete Kandidat
async function deleteKandidat(id) {
    // Show confirmation modal
    document.getElementById('deleteKandidatId').value = id;
    const kandidat = allKandidatData.find(k => k.id === id);
    document.getElementById('deleteKandidatNama').textContent = kandidat?.nama || 'kandidat ini';
    
    const modal = document.getElementById('modalHapusKandidat');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Confirm Delete
async function confirmDelete() {
    const id = document.getElementById('deleteKandidatId').value;
    
    try {
        const response = await fetch(`${API_BASE_URL}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        });
        
        if (!response.ok) {
            throw new Error('Gagal menghapus kandidat');
        }
        
        const result = await response.json();
        showNotification(result.message || 'Kandidat berhasil dihapus', 'success');
        closeModal('modalHapusKandidat');
        
        // Reload untuk update periode yang tersedia
        await loadKandidatData();
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menghapus kandidat', 'error');
    }
}

// Row Checkbox Handler
function handleRowCheckboxChange() {
    updateSelectedIds();
}

// Update Selected IDs
function updateSelectedIds() {
    selectedKandidatIds = Array.from(document.querySelectorAll('.row-checkbox:checked'))
        .map(cb => parseInt(cb.value));
    
    const batchActions = document.getElementById('batchActions');
    const selectedCount = document.getElementById('selectedCount');
    
    if (selectedKandidatIds.length > 0) {
        batchActions.style.display = 'flex';
        selectedCount.textContent = selectedKandidatIds.length;
    } else {
        batchActions.style.display = 'none';
    }
}

// Batch Delete
async function handleBatchDelete() {
    if (selectedKandidatIds.length === 0) return;
    
    if (!confirm(`Hapus ${selectedKandidatIds.length} kandidat terpilih?`)) return;
    
    try {
        const deletePromises = selectedKandidatIds.map(id => 
            fetch(`${API_BASE_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
        );
        
        await Promise.all(deletePromises);
        showNotification(`${selectedKandidatIds.length} kandidat berhasil dihapus`, 'success');
        selectedKandidatIds = [];
        
        // Reload untuk update periode yang tersedia
        await loadKandidatData();
    } catch (error) {
        console.error('Error:', error);
        showNotification('Gagal menghapus kandidat', 'error');
    }
}

// Batch Export
function handleBatchExport() {
    if (selectedKandidatIds.length === 0) return;
    
    const selectedData = allKandidatData.filter(k => selectedKandidatIds.includes(k.id));
    const csv = convertToCSV(selectedData);
    downloadCSV(csv, 'kandidat_export.csv');
    showNotification('Data berhasil diekspor', 'success');
}

// Batch Review
function handleBatchReview() {
    if (selectedKandidatIds.length === 0) return;
    showNotification(`Review ${selectedKandidatIds.length} kandidat`, 'info');
}

// Import Handler
function handleImport() {
    showNotification('Fitur import akan segera tersedia', 'info');
}

// Utility Functions
function convertToCSV(data) {
    const headers = ['ID', 'Nama', 'Status', 'Tanggal'];
    const rows = data.map(k => [k.id, k.nama, k.status || 'Pending', k.created_at]);
    
    return [headers, ...rows].map(row => row.join(',')).join('\n');
}

function downloadCSV(csv, filename) {
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

function showLoading() {
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <ion-icon name="${type === 'success' ? 'checkmark-circle' : type === 'error' ? 'close-circle' : 'information-circle'}-outline"></ion-icon>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function updateLastUpdate() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
    document.getElementById('lastUpdate').textContent = timeString;
}

// Make functions globally accessible
window.viewKandidat = viewKandidat;
window.editKandidat = editKandidat;
window.deleteKandidat = deleteKandidat;
window.confirmDelete = confirmDelete;
window.changePage = changePage;
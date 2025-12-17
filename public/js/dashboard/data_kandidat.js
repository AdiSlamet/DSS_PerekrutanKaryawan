document.addEventListener('DOMContentLoaded', function() {
    // Global variables
    let candidates = [];
    let filteredCandidates = [];
    let selectedCandidates = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentPeriode = '2024-01';
    
    // Initialize
    loadCandidates();
    initializeEventListeners();
    
    // Load candidates data
    function loadCandidates() {
        // Try to load from localStorage first
        const savedCandidates = localStorage.getItem('candidatesData');
        const savedPeriode = localStorage.getItem('currentPeriode');
        
        if (savedPeriode) {
            currentPeriode = savedPeriode;
            document.getElementById('periodeSelect').value = currentPeriode;
            updatePeriodeInfo();
        }
        
        if (savedCandidates) {
            try {
                const data = JSON.parse(savedCandidates);
                if (data[currentPeriode]) {
                    candidates = data[currentPeriode];
                } else {
                    candidates = getSampleCandidates();
                }
            } catch (e) {
                console.error('Error loading candidates data:', e);
                candidates = getSampleCandidates();
            }
        } else {
            candidates = getSampleCandidates();
        }
        
        updateUI();
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
    
    function updateUI() {
        updateStats();
        updateTable();
        updatePagination();
        updateBatchActions();
        updateLastUpdate();
    }
    
    function updateStats() {
        const total = candidates.length;
        const topRated = candidates.filter(c => calculateFinalScore(c) >= 4.5).length;
        const pendingReview = candidates.filter(c => calculateFinalScore(c) <= 3.0).length;
        
        document.getElementById('totalKandidat').textContent = total;
        document.getElementById('topRated').textContent = topRated;
        document.getElementById('pendingReview').textContent = pendingReview;
        document.getElementById('activePeriod').textContent = getPeriodeText(currentPeriode);
    }
    
    function updateTable() {
        const tbody = document.getElementById('candidatesTableBody');
        const emptyState = document.getElementById('emptyState');
        
        // Apply filters
        filterCandidates();
        
        if (filteredCandidates.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.add('visible');
            document.getElementById('paginationContainer').style.display = 'none';
            return;
        }
        
        emptyState.classList.remove('visible');
        document.getElementById('paginationContainer').style.display = 'flex';
        
        // Get current page data
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, filteredCandidates.length);
        const pageCandidates = filteredCandidates.slice(startIndex, endIndex);
        
        // Build table rows
        let tableHTML = '';
        pageCandidates.forEach((candidate, index) => {
            const finalScore = calculateFinalScore(candidate);
            const isSelected = selectedCandidates.includes(candidate.id);
            
            const getRatingClass = (score) => {
                if (score >= 4.5) return 'rating-excellent';
                if (score >= 3.5) return 'rating-good';
                if (score >= 2.5) return 'rating-fair';
                return 'rating-poor';
            };
            
            const getRatingText = (score) => {
                if (score >= 4.5) return 'Sangat Baik';
                if (score >= 3.5) return 'Baik';
                if (score >= 2.5) return 'Cukup';
                return 'Kurang';
            };
            
            const getStatusClass = (status) => {
                switch (status) {
                    case 'recommended': return 'status-recommended';
                    case 'qualified': return 'status-qualified';
                    case 'needs-review': return 'status-needs-review';
                    default: return 'status-pending';
                }
            };
            
            const getStatusText = (status) => {
                switch (status) {
                    case 'recommended': return 'Sudah di hitung';
                    case 'qualified': return 'Sedang di hitung';
                    case 'needs-review': return 'Belum di hitung';
                    default: return 'Pending';
                }
            };
            
            const getScoreColor = (score) => {
                if (score >= 4.0) return '#4CAF50';
                if (score >= 3.0) return '#FF9800';
                if (score >= 2.0) return '#F44336';
                return '#9E9E9E';
            };
            
            tableHTML += `
                <tr class="${isSelected ? 'selected' : ''}">
                    <td>
                        <input type="checkbox" class="candidate-checkbox" data-id="${candidate.id}" ${isSelected ? 'checked' : ''}>
                    </td>
                    <td>${startIndex + index + 1}</td>
                    <td>
                        <strong>${candidate.name}</strong><br>
                        <small style="color: #666;">ID: ${candidate.id}</small>
                    </td>
                    <td>
                        <span class="rating-badge ${getRatingClass(candidate.scores.pengalaman)}">
                            ${getRatingText(candidate.scores.pengalaman)} (${candidate.scores.pengalaman})
                        </span>
                    </td>
                    <td>
                        <span class="rating-badge ${getRatingClass(candidate.scores.jarak)}">
                            ${getRatingText(candidate.scores.jarak)} (${candidate.scores.jarak})
                        </span>
                    </td>
                    <td>
                        <span class="rating-badge ${getRatingClass(candidate.scores.komunikasi)}">
                            ${getRatingText(candidate.scores.komunikasi)} (${candidate.scores.komunikasi})
                        </span>
                    </td>
                    <td>
                        <span class="rating-badge ${getRatingClass(candidate.scores.fleksibilitas)}">
                            ${getRatingText(candidate.scores.fleksibilitas)} (${candidate.scores.fleksibilitas})
                        </span>
                    </td>
                    <td class="score-cell">
                        <div class="score-value">${finalScore.toFixed(2)}</div>
                        <div class="score-progress">
                            <div class="score-progress-fill" style="width: ${(finalScore / 5) * 100}%; background: ${getScoreColor(finalScore)}"></div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge ${getStatusClass(candidate.status)}">${getStatusText(candidate.status)}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" data-id="${candidate.id}">
                                <ion-icon name="eye-outline"></ion-icon>
                            </button>
                            <button class="action-btn edit" data-id="${candidate.id}">
                                <ion-icon name="create-outline"></ion-icon>
                            </button>
                            <button class="action-btn delete" data-id="${candidate.id}">
                                <ion-icon name="trash-outline"></ion-icon>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tbody.innerHTML = tableHTML;
        
        // Add event listeners to checkboxes and action buttons
        addTableEventListeners();
    }
    
    function filterCandidates() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const filterPengalaman = document.getElementById('filterPengalaman').value;
        const filterStatus = document.getElementById('filterStatus').value;
        
        filteredCandidates = candidates.filter(candidate => {
            // Search filter
            const matchesSearch = candidate.name.toLowerCase().includes(searchTerm) || 
                                 candidate.id.toString().includes(searchTerm);
            
            // Pengalaman filter
            let matchesPengalaman = true;
            if (filterPengalaman) {
                matchesPengalaman = candidate.scores.pengalaman.toString() === filterPengalaman;
            }
            
            // Status filter
            let matchesStatus = true;
            if (filterStatus) {
                matchesStatus = candidate.status === filterStatus;
            }
            
            return matchesSearch && matchesPengalaman && matchesStatus;
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
                updateTable();
                updatePagination();
            });
            paginationNumbers.appendChild(pageBtn);
        }
        
        // Update prev/next buttons
        document.getElementById('btnPrev').disabled = currentPage === 1;
        document.getElementById('btnNext').disabled = currentPage === totalPages;
    }
    
    function updateBatchActions() {
        const batchActions = document.getElementById('batchActions');
        const selectedCount = selectedCandidates.length;
        
        if (selectedCount > 0) {
            batchActions.style.display = 'flex';
            document.getElementById('selectedCount').textContent = selectedCount;
        } else {
            batchActions.style.display = 'none';
        }
    }
    
    function updateLastUpdate() {
        const now = new Date();
        document.getElementById('lastUpdate').textContent = now.toLocaleTimeString('id-ID');
    }
    
    function updatePeriodeInfo() {
        const periodeText = getPeriodeText(currentPeriode);
        document.getElementById('currentPeriode').textContent = periodeText;
    }
    
    function getPeriodeText(periode) {
        const [year, month] = periode.split('-');
        const monthNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return `${monthNames[parseInt(month) - 1]} ${year}`;
    }
    
    function addTableEventListeners() {
        // Checkboxes
        document.querySelectorAll('.candidate-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const candidateId = parseInt(this.getAttribute('data-id'));
                const row = this.closest('tr');
                
                if (this.checked) {
                    selectedCandidates.push(candidateId);
                    row.classList.add('selected');
                } else {
                    selectedCandidates = selectedCandidates.filter(id => id !== candidateId);
                    row.classList.remove('selected');
                    
                    // Uncheck select all if any checkbox is unchecked
                    const selectAll = document.getElementById('selectAll');
                    if (selectAll) {
                        selectAll.checked = false;
                        selectAll.indeterminate = false;
                    }
                }
                
                updateBatchActions();
            });
        });
        
        // Action buttons
        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                viewCandidate(candidateId);
            });
        });
        
        document.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                editCandidate(candidateId);
            });
        });
        
        document.querySelectorAll('.action-btn.rate').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                rateCandidate(candidateId);
            });
        });
        
        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const candidateId = this.getAttribute('data-id');
                deleteCandidate(candidateId);
            });
        });
    }
    
    function initializeEventListeners() {
        // Select All checkbox
        const selectAll = document.getElementById('selectAll');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.candidate-checkbox');
                const startIndex = (currentPage - 1) * itemsPerPage;
                const endIndex = Math.min(startIndex + itemsPerPage, filteredCandidates.length);
                const pageCandidates = filteredCandidates.slice(startIndex, endIndex);
                
                if (this.checked) {
                    // Select all on current page
                    pageCandidates.forEach(candidate => {
                        if (!selectedCandidates.includes(candidate.id)) {
                            selectedCandidates.push(candidate.id);
                        }
                    });
                    checkboxes.forEach(cb => cb.checked = true);
                    document.querySelectorAll('#candidatesTableBody tr').forEach(row => {
                        row.classList.add('selected');
                    });
                } else {
                    // Deselect all on current page
                    pageCandidates.forEach(candidate => {
                        selectedCandidates = selectedCandidates.filter(id => id !== candidate.id);
                    });
                    checkboxes.forEach(cb => cb.checked = false);
                    document.querySelectorAll('#candidatesTableBody tr').forEach(row => {
                        row.classList.remove('selected');
                    });
                }
                
                this.indeterminate = false;
                updateBatchActions();
            });
        }
        
        // Search input
        document.getElementById('searchInput').addEventListener('input', function() {
            currentPage = 1;
            updateTable();
            updatePagination();
        });
        
        // Filter selects
        document.getElementById('filterPengalaman').addEventListener('change', function() {
            currentPage = 1;
            updateTable();
            updatePagination();
        });
        
        document.getElementById('filterStatus').addEventListener('change', function() {
            currentPage = 1;
            updateTable();
            updatePagination();
        });
        
        // Reset filter button
        document.getElementById('btnResetFilter').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterPengalaman').value = '';
            document.getElementById('filterStatus').value = '';
            currentPage = 1;
            updateTable();
            updatePagination();
        });
        
        // Periode select
        document.getElementById('periodeSelect').addEventListener('change', function() {
            if (this.value) {
                // Save current candidates data
                saveCandidatesData();
                
                // Change periode
                currentPeriode = this.value;
                localStorage.setItem('currentPeriode', currentPeriode);
                
                // Load candidates for new periode
                loadCandidates();
                updatePeriodeInfo();
                
                // Reset filters
                document.getElementById('searchInput').value = '';
                document.getElementById('filterPengalaman').value = '';
                document.getElementById('filterStatus').value = '';
                selectedCandidates = [];
            }
        });
        
        // Items per page
        document.getElementById('itemsPerPage').addEventListener('change', function() {
            itemsPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
            updatePagination();
        });
        
        // Pagination buttons
        document.getElementById('btnPrev').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
                updatePagination();
            }
        });
        
        document.getElementById('btnNext').addEventListener('click', function() {
            const totalPages = Math.ceil(filteredCandidates.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
                updatePagination();
            }
        });
        
        // Tambah kandidat buttons
        document.getElementById('btnTambahBaru').addEventListener('click', showTambahModal);
        document.getElementById('btnTambahPertama').addEventListener('click', showTambahModal);
        
        // Import button
        document.getElementById('btnImport').addEventListener('click', function() {
            alert('Fitur import akan segera tersedia');
        });
        
        // Export buttons
        document.getElementById('btnExportData').addEventListener('click', exportAllData);
        document.getElementById('btnBatchExport').addEventListener('click', exportSelectedData);
        
        // Hitung ranking button
        document.getElementById('btnHitungRanking').addEventListener('click', function() {
            // Check if there are enough candidates
            if (candidates.length === 0) {
                alert('Belum ada data kandidat untuk dihitung rankingnya.');
                return;
            }
            
            // Save data first
            saveCandidatesData();
            
            // Open ranking page
            window.location.href = '/hasil-perhitungan';
        });
        
        // Batch delete button
        document.getElementById('btnBatchDelete').addEventListener('click', function() {
            if (selectedCandidates.length === 0) {
                alert('Pilih kandidat terlebih dahulu.');
                return;
            }
            
            // Show batch delete modal
            const batchDeleteModal = document.getElementById('batchDeleteModal');
            if (batchDeleteModal) {
                document.getElementById('batchDeleteCount').textContent = selectedCandidates.length;
                batchDeleteModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else {
                if (confirm(`Hapus ${selectedCandidates.length} kandidat terpilih?`)) {
                    deleteSelectedCandidates();
                }
            }
        });
        
        // Batch review button
        document.getElementById('btnBatchReview').addEventListener('click', function() {
            if (selectedCandidates.length === 0) {
                alert('Pilih kandidat terlebih dahulu.');
                return;
            }
            
            // Show batch review interface
            alert(`Membuka review untuk ${selectedCandidates.length} kandidat terpilih`);
        });
        
        // Refresh button
        document.getElementById('btnRefreshData').addEventListener('click', function() {
            loadCandidates();
        });
        
        // Simpan perubahan button
        document.getElementById('btnSimpanPerubahan').addEventListener('click', function() {
            saveCandidatesData();
            alert('Perubahan berhasil disimpan!');
        });
    }
    
    function showTambahModal() {
        const tambahModal = document.getElementById('tambahModal');
        if (tambahModal) {
            tambahModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            alert('Modal tambah kandidat belum tersedia. Silakan tambahkan komponen modal terlebih dahulu.');
        }
    }
    
    function viewCandidate(candidateId) {
        const candidate = candidates.find(c => c.id === parseInt(candidateId));
        if (!candidate) return;
        
        showDetailModal(candidate);
    }
    
    function editCandidate(candidateId) {
        const candidate = candidates.find(c => c.id === parseInt(candidateId));
        if (!candidate) return;
        
        // Show edit modal
        const editModal = document.getElementById('editModal');
        if (editModal) {
            // Populate form with candidate data
            const editNamaInput = editModal.querySelector('#editNama');
            const editPengalamanSelect = editModal.querySelector('#editPengalaman');
            const editJarakSelect = editModal.querySelector('#editJarak');
            const editKomunikasiSelect = editModal.querySelector('#editKomunikasi');
            const editFleksibilitasSelect = editModal.querySelector('#editFleksibilitas');
            
            if (editNamaInput) editNamaInput.value = candidate.name;
            if (editPengalamanSelect) editPengalamanSelect.value = candidate.scores.pengalaman;
            if (editJarakSelect) editJarakSelect.value = candidate.scores.jarak;
            if (editKomunikasiSelect) editKomunikasiSelect.value = candidate.scores.komunikasi;
            if (editFleksibilitasSelect) editFleksibilitasSelect.value = candidate.scores.fleksibilitas;
            
            // Store candidate ID for update
            editModal.setAttribute('data-candidate-id', candidateId);
            
            editModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            alert('Modal edit kandidat belum tersedia. Silakan tambahkan komponen modal terlebih dahulu.');
        }
    }
    
    // function rateCandidate(candidateId) {
    //     const candidate = candidates.find(c => c.id === parseInt(candidateId));
    //     if (!candidate) return;
        
    //     // Show rating modal or interface
    //     alert(`Berikan rating untuk ${candidate.name}:\n\nCurrent Scores:\nPengalaman: ${candidate.scores.pengalaman}/5\nJarak: ${candidate.scores.jarak}/5\nKomunikasi: ${candidate.scores.komunikasi}/5\nFleksibilitas: ${candidate.scores.fleksibilitas}/5\n\nSkor Akhir: ${calculateFinalScore(candidate).toFixed(2)}`);
    // }
    
    function deleteCandidate(candidateId) {
        const candidate = candidates.find(c => c.id === parseInt(candidateId));
        if (!candidate) return;
        
        // Show delete modal
        const hapusModal = document.getElementById('hapusModal');
        if (hapusModal) {
            // Populate modal with candidate data
            const deleteNama = document.getElementById('deleteNama');
            const deletePosisi = document.getElementById('deletePosisi');
            const deleteSkor = document.getElementById('deleteSkor');
            
            if (deleteNama) deleteNama.textContent = candidate.name;
            if (deletePosisi) {
                const scoresText = `Pengalaman: ${candidate.scores.pengalaman}/5, Jarak: ${candidate.scores.jarak}/5, Komunikasi: ${candidate.scores.komunikasi}/5, Fleksibilitas: ${candidate.scores.fleksibilitas}/5`;
                deletePosisi.textContent = scoresText;
            }
            if (deleteSkor) deleteSkor.textContent = calculateFinalScore(candidate).toFixed(2);
            
            // Store candidate ID for deletion
            hapusModal.setAttribute('data-candidate-id', candidateId);
            
            hapusModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        } else {
            if (confirm(`Hapus kandidat "${candidate.name}"?`)) {
                performDeleteCandidate(candidateId);
            }
        }
    }
    
    function performDeleteCandidate(candidateId) {
        candidates = candidates.filter(c => c.id !== parseInt(candidateId));
        
        // Remove from selected candidates if present
        selectedCandidates = selectedCandidates.filter(id => id !== parseInt(candidateId));
        
        // Update UI
        updateUI();
        saveCandidatesData();
    }
    
    function deleteSelectedCandidates() {
        candidates = candidates.filter(c => !selectedCandidates.includes(c.id));
        selectedCandidates = [];
        
        // Update UI
        updateUI();
        saveCandidatesData();
        
        // Close modal if open
        const batchDeleteModal = document.getElementById('batchDeleteModal');
        if (batchDeleteModal) {
            batchDeleteModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        alert('Kandidat terpilih berhasil dihapus!');
    }
    
    function exportAllData() {
        const data = candidates.map(candidate => ({
            ID: candidate.id,
            Nama: candidate.name,
            Pengalaman: candidate.scores.pengalaman,
            Jarak: candidate.scores.jarak,
            Komunikasi: candidate.scores.komunikasi,
            Fleksibilitas: candidate.scores.fleksibilitas,
            Skor_Akhir: calculateFinalScore(candidate).toFixed(2),
            Status: candidate.status,
            Tanggal_Daftar: candidate.createdAt
        }));
        
        exportToCSV(data, `data-kandidat-${currentPeriode}`);
    }
    
    function exportSelectedData() {
        const selectedData = candidates
            .filter(c => selectedCandidates.includes(c.id))
            .map(candidate => ({
                ID: candidate.id,
                Nama: candidate.name,
                Pengalaman: candidate.scores.pengalaman,
                Jarak: candidate.scores.jarak,
                Komunikasi: candidate.scores.komunikasi,
                Fleksibilitas: candidate.scores.fleksibilitas,
                Skor_Akhir: calculateFinalScore(candidate).toFixed(2),
                Status: candidate.status,
                Tanggal_Daftar: candidate.createdAt
            }));
        
        if (selectedData.length === 0) {
            alert('Tidak ada kandidat yang dipilih untuk diexport.');
            return;
        }
        
        exportToCSV(selectedData, `kandidat-terpilih-${currentPeriode}`);
    }
    
    function exportToCSV(data, filename) {
        const headers = Object.keys(data[0] || {});
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header]}"`).join(','))
        ].join('\n');
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${filename}-${new Date().toISOString().slice(0, 10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        alert('Data berhasil diexport!');
    }
    
    function saveCandidatesData() {
        const allData = JSON.parse(localStorage.getItem('candidatesData') || '{}');
        allData[currentPeriode] = candidates;
        localStorage.setItem('candidatesData', JSON.stringify(allData));
    }
    
    // Function untuk menambahkan kandidat baru dari modal
    window.addNewCandidate = function(candidateData) {
        // Generate new ID
        const newId = candidates.length > 0 ? Math.max(...candidates.map(c => c.id)) + 1 : 1;
        
        const newCandidate = {
            id: newId,
            name: candidateData.nama,
            scores: {
                pengalaman: parseInt(candidateData.pengalaman),
                jarak: parseInt(candidateData.jarak),
                komunikasi: parseInt(candidateData.komunikasi),
                fleksibilitas: parseInt(candidateData.fleksibilitas)
            },
            createdAt: new Date().toISOString().split('T')[0],
            status: 'pending'
        };
        
        // Calculate status based on score
        const finalScore = calculateFinalScore(newCandidate);
        if (finalScore >= 4.0) {
            newCandidate.status = 'recommended';
        } else if (finalScore >= 3.0) {
            newCandidate.status = 'qualified';
        } else {
            newCandidate.status = 'needs-review';
        }
        
        candidates.push(newCandidate);
        
        // Update UI
        updateUI();
        saveCandidatesData();
        
        // Close modal
        const tambahModal = document.getElementById('tambahModal');
        if (tambahModal) {
            tambahModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        alert('Kandidat baru berhasil ditambahkan!');
    };
    
    // Function untuk update kandidat dari modal edit
    window.updateCandidate = function(candidateData) {
        const candidateId = parseInt(document.getElementById('editModal').getAttribute('data-candidate-id'));
        const candidateIndex = candidates.findIndex(c => c.id === candidateId);
        
        if (candidateIndex !== -1) {
            candidates[candidateIndex] = {
                ...candidates[candidateIndex],
                name: candidateData.nama,
                scores: {
                    pengalaman: parseInt(candidateData.pengalaman),
                    jarak: parseInt(candidateData.jarak),
                    komunikasi: parseInt(candidateData.komunikasi),
                    fleksibilitas: parseInt(candidateData.fleksibilitas)
                }
            };
            
            // Update status based on new score
            const finalScore = calculateFinalScore(candidates[candidateIndex]);
            if (finalScore >= 4.0) {
                candidates[candidateIndex].status = 'recommended';
            } else if (finalScore >= 3.0) {
                candidates[candidateIndex].status = 'qualified';
            } else {
                candidates[candidateIndex].status = 'needs-review';
            }
            
            // Update UI
            updateUI();
            saveCandidatesData();
            
            // Close modal
            const editModal = document.getElementById('editModal');
            if (editModal) {
                editModal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            alert('Data kandidat berhasil diperbarui!');
        }
    };
    
    // Function untuk konfirmasi hapus kandidat dari modal
    window.confirmDelete = function() {
        const candidateId = document.getElementById('hapusModal').getAttribute('data-candidate-id');
        performDeleteCandidate(candidateId);
        
        // Close modal
        const hapusModal = document.getElementById('hapusModal');
        if (hapusModal) {
            hapusModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        alert('Kandidat berhasil dihapus!');
    };
    
    // Function untuk konfirmasi batch delete
    window.confirmBatchDelete = function() {
        deleteSelectedCandidates();
    };
});

    // close modal
    document.addEventListener('DOMContentLoaded', function() {
    
    // Function untuk close modal
    function closeModal(modal) {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    // Function untuk open modal
    function openModal(modal) {
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    // 1. Event listener untuk semua tombol close (X button)
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const modal = this.closest('.modal');
            closeModal(modal);
        });
    });
    
    // 2. Event listener untuk click di luar modal (overlay)
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            // Hanya close jika click langsung di modal (overlay), bukan di modal-content
            if (e.target === this) {
                closeModal(this);
            }
        });
    });
    
    // 3. Prevent close ketika click di dalam modal-content
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    // 4. Close modal dengan tombol ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' || e.keyCode === 27) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (modal.style.display === 'flex') {
                    closeModal(modal);
                }
            });
        }
    });
    
    // 5. Event listener untuk tombol Batal di setiap modal
    document.querySelectorAll('.btn-secondary').forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.classList.contains('close-modal') || 
                this.textContent.includes('Batal')) {
                e.preventDefault();
                const modal = this.closest('.modal');
                closeModal(modal);
            }
        });
    });
    
    // 6. Specific handlers untuk setiap modal
    
    // Tambah Modal
    const btnTambahBaru = document.getElementById('btnTambahBaru');
    const btnTambahPertama = document.getElementById('btnTambahPertama');
    const tambahModal = document.getElementById('tambahModal');
    const btnCloseTambah = document.getElementById('btnCloseTambah');
    
    if (btnTambahBaru) {
        btnTambahBaru.addEventListener('click', () => openModal(tambahModal));
    }
    if (btnTambahPertama) {
        btnTambahPertama.addEventListener('click', () => openModal(tambahModal));
    }
    if (btnCloseTambah) {
        btnCloseTambah.addEventListener('click', () => closeModal(tambahModal));
    }
    
    // Edit Modal
    const editModal = document.getElementById('editModal');
    const btnCloseEdit = document.getElementById('btnCloseEdit');
    
    if (btnCloseEdit) {
        btnCloseEdit.addEventListener('click', () => closeModal(editModal));
    }
    
    // Hapus Modal
    const hapusModal = document.getElementById('hapusModal');
    const btnCloseHapus = document.getElementById('btnCloseHapus');
    const btnCancelHapus = document.getElementById('btnCancelHapus');
    
    if (btnCloseHapus) {
        btnCloseHapus.addEventListener('click', () => closeModal(hapusModal));
    }
    if (btnCancelHapus) {
        btnCancelHapus.addEventListener('click', () => closeModal(hapusModal));
    }
    
    // 7. Handler untuk form submit - close modal setelah berhasil
    const addCandidateForm = document.getElementById('addCandidateForm');
    if (addCandidateForm) {
        addCandidateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validasi form
            const nama = document.getElementById('nama').value;
            const pengalaman = document.getElementById('pengalaman').value;
            const jarak = document.getElementById('jarak').value;
            const komunikasi = document.getElementById('komunikasi').value;
            const fleksibilitas = document.getElementById('fleksibilitas').value;
            
            if (!nama || !pengalaman || !jarak || !komunikasi || !fleksibilitas) {
                alert('Harap lengkapi semua field yang wajib diisi!');
                return;
            }
            
            // Panggil fungsi addNewCandidate (sudah ada di script utama)
            if (typeof window.addNewCandidate === 'function') {
                window.addNewCandidate({
                    nama: nama,
                    pengalaman: pengalaman,
                    jarak: jarak,
                    komunikasi: komunikasi,
                    fleksibilitas: fleksibilitas
                });
            }
            
            // Reset form dan close modal
            this.reset();
            closeModal(tambahModal);
        });
    }
    
    // Edit Form Submit
    const editCandidateForm = document.getElementById('editCandidateForm');
    if (editCandidateForm) {
        editCandidateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Panggil fungsi updateCandidate (sudah ada di script utama)
            if (typeof window.updateCandidate === 'function') {
                const editNama = document.querySelector('#editModal #nama');
                const editPengalaman = document.querySelector('#editModal #pengalaman');
                const editJarak = document.querySelector('#editModal #jarak');
                const editKomunikasi = document.querySelector('#editModal #komunikasi');
                const editFleksibilitas = document.querySelector('#editModal #fleksibilitas');
                
                window.updateCandidate({
                    nama: editNama.value,
                    pengalaman: editPengalaman.value,
                    jarak: editJarak.value,
                    komunikasi: editKomunikasi.value,
                    fleksibilitas: editFleksibilitas.value
                });
            }
            
            closeModal(editModal);
        });
    }
    
    // Confirm Delete Button
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (typeof window.confirmDelete === 'function') {
                window.confirmDelete();
            }
            closeModal(hapusModal);
        });
    }
    
    console.log('✅ Modal close functionality initialized');
});
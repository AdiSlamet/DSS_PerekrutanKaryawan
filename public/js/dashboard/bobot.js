document.addEventListener('DOMContentLoaded', function() {
    // Data dan konfigurasi
    const defaultBobot = {
        pengalaman: 30,
        jarak: 25,
        komunikasi: 25,
        fleksibilitas: 20
    };
    
    const presets = {
        default: { pengalaman: 30, jarak: 25, komunikasi: 25, fleksibilitas: 20 },
        'experience-heavy': { pengalaman: 45, jarak: 20, komunikasi: 20, fleksibilitas: 15 },
        balanced: { pengalaman: 25, jarak: 25, komunikasi: 25, fleksibilitas: 25 },
        'communication-heavy': { pengalaman: 25, jarak: 20, komunikasi: 35, fleksibilitas: 20 }
    };
    
    const constraints = {
        pengalaman: { min: 10, max: 50 },
        jarak: { min: 10, max: 40 },
        komunikasi: { min: 10, max: 40 },
        fleksibilitas: { min: 10, max: 40 }
    };
    
    let currentBobot = { ...defaultBobot };
    
    // Inisialisasi
    loadSavedSettings();
    updateAllDisplays();
    updateCharts();
    updateAnalysis();
    
    // Event Listeners untuk Sliders
    document.querySelectorAll('.bobot-slider').forEach(slider => {
        slider.addEventListener('input', function() {
            const criteria = this.getAttribute('data-criteria');
            const newValue = parseInt(this.value);
            
            currentBobot[criteria] = newValue;
            adjustOtherCriteria(criteria, newValue);
            updateAllDisplays();
            updateCharts();
            updateAnalysis();
        });
    });
    
    // Event Listeners untuk Inputs
    document.querySelectorAll('.bobot-input').forEach(input => {
        input.addEventListener('input', function() {
            const criteria = this.getAttribute('data-criteria');
            let newValue = parseInt(this.value) || constraints[criteria].min;
            
            newValue = Math.max(constraints[criteria].min, 
                              Math.min(constraints[criteria].max, newValue));
            
            currentBobot[criteria] = newValue;
            adjustOtherCriteria(criteria, newValue);
            updateAllDisplays();
            updateCharts();
            updateAnalysis();
        });
        
        input.addEventListener('blur', function() {
            const criteria = this.getAttribute('data-criteria');
            let value = parseInt(this.value) || constraints[criteria].min;
            
            if (value < constraints[criteria].min) {
                this.value = constraints[criteria].min;
                this.dispatchEvent(new Event('input'));
            } else if (value > constraints[criteria].max) {
                this.value = constraints[criteria].max;
                this.dispatchEvent(new Event('input'));
            }
        });
    });
    
    // Tombol Increment/Decrement
    document.querySelectorAll('.btn-increment').forEach(btn => {
        btn.addEventListener('click', function() {
            const criteria = this.getAttribute('data-criteria');
            changeValue(criteria, 1);
        });
    });
    
    document.querySelectorAll('.btn-decrement').forEach(btn => {
        btn.addEventListener('click', function() {
            const criteria = this.getAttribute('data-criteria');
            changeValue(criteria, -1);
        });
    });
    
    // Tombol Reset per Kriteria
    document.querySelectorAll('.btn-reset').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const criteria = this.getAttribute('data-criteria');
            currentBobot[criteria] = defaultBobot[criteria];
            adjustOtherCriteria(criteria, defaultBobot[criteria]);
            updateAllDisplays();
            updateCharts();
            updateAnalysis();
            
            showNotification(`Bobot ${criteria} direset ke ${defaultBobot[criteria]}%`, 'info');
        });
    });
    
    // Preset
    document.querySelectorAll('[data-preset]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const preset = this.getAttribute('data-preset');
            if (presets[preset]) {
                if (confirm(`Gunakan preset ${this.textContent.trim()}?`)) {
                    currentBobot = { ...presets[preset] };
                    updateAllDisplays();
                    updateCharts();
                    updateAnalysis();
                    showNotification(`Preset "${this.textContent.trim()}" diterapkan`, 'success');
                }
            }
        });
    });
    
    // Reset All
    document.getElementById('btnResetAll').addEventListener('click', function() {
        if (confirm('Reset semua bobot ke nilai default?')) {
            currentBobot = { ...defaultBobot };
            updateAllDisplays();
            updateCharts();
            updateAnalysis();
            showNotification('Semua bobot telah direset ke nilai default', 'success');
        }
    });
    
    // Simpan Perubahan
    document.getElementById('btnSave').addEventListener('click', function() {
        saveSettings();
    });
    
    // Terapkan Sekarang
    document.getElementById('btnApply').addEventListener('click', function() {
        if (calculateTotal() !== 100) {
            showNotification('Total bobot harus 100% sebelum diterapkan', 'error');
            return;
        }
        
        if (confirm('Terapkan pengaturan bobot ini sekarang?')) {
            saveSettings();
            applyToSystem();
        }
    });
    
    // Batal
    document.getElementById('btnCancel').addEventListener('click', function() {
        if (hasUnsavedChanges()) {
            if (confirm('Ada perubahan yang belum disimpan. Yakin ingin keluar?')) {
                window.history.back();
            }
        } else {
            window.history.back();
        }
    });
    
    // Panduan
    document.getElementById('btnHelp').addEventListener('click', function() {
        showHelpModal();
    });
    
    // Navigasi Tab
    document.querySelectorAll('.nav-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active tab
            document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding section
            const target = this.getAttribute('href').substring(1);
            showSection(target);
        });
    });
    
    // Fungsi Helper
    function calculateTotal() {
        return Object.values(currentBobot).reduce((sum, val) => sum + val, 0);
    }
    
    function adjustOtherCriteria(changedCriteria, newValue) {
        const oldValue = currentBobot[changedCriteria];
        const difference = newValue - oldValue;
        
        if (difference === 0) return;
        
        const otherCriteria = Object.keys(currentBobot).filter(c => c !== changedCriteria);
        const adjustPerCriteria = Math.round(difference / otherCriteria.length * -1);
        
        otherCriteria.forEach(criteria => {
            let newBobot = currentBobot[criteria] + adjustPerCriteria;
            newBobot = Math.max(constraints[criteria].min, 
                              Math.min(constraints[criteria].max, newBobot));
            currentBobot[criteria] = newBobot;
        });
        
        // Ensure total is 100
        let total = calculateTotal();
        let attempts = 0;
        
        while (total !== 100 && attempts < 10) {
            const diff = 100 - total;
            const criteriaToAdjust = otherCriteria[attempts % otherCriteria.length];
            
            let newBobot = currentBobot[criteriaToAdjust] + diff;
            newBobot = Math.max(constraints[criteriaToAdjust].min, 
                              Math.min(constraints[criteriaToAdjust].max, newBobot));
            
            currentBobot[criteriaToAdjust] = newBobot;
            total = calculateTotal();
            attempts++;
        }
    }
    
    function changeValue(criteria, delta) {
        let newValue = currentBobot[criteria] + delta;
        newValue = Math.max(constraints[criteria].min, 
                          Math.min(constraints[criteria].max, newValue));
        
        currentBobot[criteria] = newValue;
        adjustOtherCriteria(criteria, newValue);
        updateAllDisplays();
        updateCharts();
        updateAnalysis();
    }
    
    function updateAllDisplays() {
        // Update sliders and inputs
        Object.keys(currentBobot).forEach(criteria => {
            const slider = document.getElementById(`slider${capitalizeFirst(criteria)}`);
            const input = document.getElementById(`input${capitalizeFirst(criteria)}`);
            const currentValue = document.getElementById(`current${capitalizeFirst(criteria)}`);
            
            if (slider) slider.value = currentBobot[criteria];
            if (input) input.value = currentBobot[criteria];
            if (currentValue) currentValue.textContent = `${currentBobot[criteria]}%`;
        });
        
        // Update total
        const total = calculateTotal();
        document.getElementById('totalBobot').textContent = `${total}%`;
        document.getElementById('progressFill').style.width = `${total}%`;
        
        // Update status
        const statusElement = document.getElementById('totalStatus');
        if (total === 100) {
            statusElement.innerHTML = `<span class="status-valid">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                Valid
            </span>`;
        } else {
            statusElement.innerHTML = `<span class="status-invalid">
                <ion-icon name="alert-circle-outline"></ion-icon>
                ${total}%
            </span>`;
        }
    }
    
    function updateCharts() {
        // Update distribution bars
        Object.keys(currentBobot).forEach(criteria => {
            const bar = document.querySelector(`.dist-bar:nth-child(${getCriteriaIndex(criteria)})`);
            if (bar) {
                bar.style.width = `${currentBobot[criteria]}%`;
                bar.querySelector('.dist-value').textContent = `${currentBobot[criteria]}%`;
            }
        });
    }
    
    function updateAnalysis() {
        const total = calculateTotal();
        
        // Find highest criteria
        const highestCriteria = Object.keys(currentBobot).reduce((a, b) => 
            currentBobot[a] > currentBobot[b] ? a : b
        );
        
        const highestValue = currentBobot[highestCriteria];
        document.getElementById('highestCriteria').innerHTML = 
            `<span class="criteria-tag" style="${getCriteriaStyle(highestCriteria)}">
                ${capitalizeFirst(highestCriteria)} (${highestValue}%)
            </span>`;
        
        // Update influence level
        const influenceElement = document.getElementById('influenceLevel');
        if (highestValue >= 40) {
            influenceElement.textContent = 'Sangat Tinggi';
            influenceElement.className = 'level-very-high';
        } else if (highestValue >= 30) {
            influenceElement.textContent = 'Tinggi';
            influenceElement.className = 'level-high';
        } else {
            influenceElement.textContent = 'Sedang';
            influenceElement.className = 'level-medium';
        }
        
        // Update balance score
        const balanceElement = document.getElementById('balanceScore');
        const values = Object.values(currentBobot);
        const maxDiff = Math.max(...values) - Math.min(...values);
        
        if (maxDiff <= 10) {
            balanceElement.textContent = 'Sangat Baik';
            balanceElement.className = 'score-excellent';
        } else if (maxDiff <= 20) {
            balanceElement.textContent = 'Baik';
            balanceElement.className = 'score-good';
        } else {
            balanceElement.textContent = 'Kurang';
            balanceElement.className = 'score-poor';
        }
    }
    
    function getCriteriaStyle(criteria) {
        const styles = {
            pengalaman: 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);',
            jarak: 'background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);',
            komunikasi: 'background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);',
            fleksibilitas: 'background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);'
        };
        return styles[criteria] || '';
    }
    
    function getCriteriaIndex(criteria) {
        const order = ['pengalaman', 'jarak', 'komunikasi', 'fleksibilitas'];
        return order.indexOf(criteria) + 1;
    }
    
    function capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }
    
    function saveSettings() {
        const total = calculateTotal();
        
        if (total !== 100) {
            showNotification(`Total bobot harus 100%. Saat ini: ${total}%`, 'error');
            return;
        }
        
        // Save to localStorage
        localStorage.setItem('bobotSettings', JSON.stringify(currentBobot));
        
        // Simulate API call
        setTimeout(() => {
            showNotification('Pengaturan bobot berhasil disimpan!', 'success');
        }, 500);
    }
    
    function applyToSystem() {
        // Simulate API call
        showNotification('Menerapkan pengaturan ke sistem...', 'info');
        
        setTimeout(() => {
            showNotification('Pengaturan bobot berhasil diterapkan!', 'success');
        }, 1500);
    }
    
    function loadSavedSettings() {
        const saved = localStorage.getItem('bobotSettings');
        if (saved) {
            try {
                const savedBobot = JSON.parse(saved);
                const total = Object.values(savedBobot).reduce((a, b) => a + b, 0);
                
                if (total === 100) {
                    currentBobot = savedBobot;
                }
            } catch (e) {
                console.error('Error loading saved settings:', e);
            }
        }
    }
    
    function hasUnsavedChanges() {
        const saved = localStorage.getItem('bobotSettings');
        if (!saved) return JSON.stringify(defaultBobot) !== JSON.stringify(currentBobot);
        
        try {
            const savedBobot = JSON.parse(saved);
            return JSON.stringify(savedBobot) !== JSON.stringify(currentBobot);
        } catch (e) {
            return true;
        }
    }
    
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.page-content > div').forEach(div => {
            div.style.display = 'none';
        });
        
        // Show selected section
        if (sectionId === 'pengaturan') {
            document.querySelector('.page-content').style.display = 'grid';
        } else {
            document.querySelector('.page-content').innerHTML = 
                `<div class="coming-soon">
                    <h3><ion-icon name="construct-outline"></ion-icon> Fitur ${sectionId} dalam Pengembangan</h3>
                    <p>Fitur ini akan segera hadir dalam pembaruan berikutnya.</p>
                </div>`;
        }
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <ion-icon name="${type === 'success' ? 'checkmark-circle' : 
                             type === 'error' ? 'alert-circle' : 
                             type === 'info' ? 'information-circle' : 'alert-circle'}"></ion-icon>
            <span>${message}</span>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d4edda' : 
                         type === 'error' ? '#f8d7da' : 
                         type === 'info' ? '#d1ecf1' : '#fff3cd'};
            color: ${type === 'success' ? '#155724' : 
                    type === 'error' ? '#721c24' : 
                    type === 'info' ? '#0c5460' : '#856404'};
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            animation: slideInRight 0.3s ease-out;
            border-left: 4px solid ${type === 'success' ? '#28a745' : 
                                 type === 'error' ? '#dc3545' : 
                                 type === 'info' ? '#17a2b8' : '#ffc107'};
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    function showHelpModal() {
        const helpModal = document.createElement('div');
        helpModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            padding: 20px;
        `;
        
        helpModal.innerHTML = `
            <div style="background: white; border-radius: 15px; padding: 30px; max-width: 600px; width: 100%; max-height: 80vh; overflow-y: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                        <ion-icon name="help-circle-outline" style="color: #007bff;"></ion-icon>
                        Panduan Pengaturan Bobot
                    </h2>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            style="background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">
                        &times;
                    </button>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <h3 style="color: #333; margin-bottom: 10px;">Total Bobot Harus 100%</h3>
                        <p style="color: #666; line-height: 1.6;">
                            Sistem akan otomatis menyesuaikan bobot kriteria lainnya saat Anda mengubah satu kriteria. 
                            Pastikan total selalu 100% sebelum menyimpan.
                        </p>
                    </div>
                    
                    <div>
                        <h3 style="color: #333; margin-bottom: 10px;">Rentang Bobot</h3>
                        <ul style="color: #666; line-height: 1.6; padding-left: 20px;">
                            <li><strong>Pengalaman Kerja:</strong> 10% - 50%</li>
                            <li><strong>Jarak Tempat Tinggal:</strong> 10% - 40%</li>
                            <li><strong>Kemampuan Komunikasi:</strong> 10% - 40%</li>
                            <li><strong>Fleksibilitas Kerja:</strong> 10% - 40%</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 style="color: #333; margin-bottom: 10px;">Tips Pengaturan</h3>
                        <ul style="color: #666; line-height: 1.6; padding-left: 20px;">
                            <li>Gunakan preset untuk konfigurasi cepat</li>
                            <li>Prioritaskan kriteria yang paling penting untuk posisi</li>
                            <li>Distribusi yang seimbang menghasilkan seleksi yang objektif</li>
                            <li>Simpan pengaturan setelah selesai</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 style="color: #333; margin-bottom: 10px;">Analisis Dampak</h3>
                        <p style="color: #666; line-height: 1.6;">
                            Panel analisis akan menampilkan dampak dari pengaturan bobot Anda terhadap proses seleksi.
                        </p>
                    </div>
                </div>
                
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="margin-top: 25px; width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Mengerti
                </button>
            </div>
        `;
        
        document.body.appendChild(helpModal);
    }
    
    // Add CSS for animations
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
        .status-invalid {
            color: #dc3545 !important;
            background: #f8d7da !important;
            padding: 8px 15px !important;
            border-radius: 20px !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
        }
        .level-very-high {
            color: #721c24 !important;
            background: #f8d7da !important;
        }
        .level-medium {
            color: #856404 !important;
            background: #fff3cd !important;
        }
        .score-excellent {
            color: #155724 !important;
            background: #d4edda !important;
        }
        .score-poor {
            color: #721c24 !important;
            background: #f8d7da !important;
        }
        .coming-soon {
            grid-column: 1 / -1;
            text-align: center;
            padding: 100px 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }
        .coming-soon h3 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .coming-soon p {
            color: #666;
            font-size: 16px;
        }
    `;
    document.head.appendChild(style);
});
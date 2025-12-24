@extends('layouts.app')

@section('title', 'Manajemen Kriteria')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penilaian Kandidat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Penilaian Kandidat</h1>
                <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Penilaian
                </button>
            </div>

            <!-- Filter -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Periode</label>
                <input type="month" id="filterPeriode" onchange="filterByPeriode()" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button onclick="clearFilter()" class="ml-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
                    Clear
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kandidat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Kriteria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data akan diisi dengan JavaScript -->
                    </tbody>
                </table>
            </div>

            <div id="loadingState" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Memuat data...</p>
            </div>

            <div id="emptyState" class="text-center py-8 hidden">
                <p class="text-gray-500">Tidak ada data penilaian</p>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Penilaian -->
    <div id="modalTambah" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Tambah Penilaian</h3>
                <button onclick="closeModal()" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="formPenilaian" onsubmit="submitPenilaian(event)">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kandidat</label>
                    <select id="kandidat_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kandidat</option>
                    </select>
                </div>

                <div id="kriteriaContainer" class="space-y-4 mb-4">
                    <!-- Kriteria akan diisi dengan JavaScript -->
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="modalDetail" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Detail Penilaian</h3>
                <button onclick="closeDetailModal()" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div id="detailContent">
                <!-- Detail akan diisi dengan JavaScript -->
            </div>

            <div class="mt-4 flex justify-end">
                <button onclick="closeDetailModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api';
        let kriteriaData = [];
        // let subKriteriaData = [];

        // Setup CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Fetch data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            loadPenilaians();
            loadKandidats();
            loadKriterias();
        });

        

        async function loadPenilaians(periode = '') {
            showLoading(true);
            try {
                const url = periode ? `${API_URL}/penilaian?periode=${periode}` : `${API_URL}/penilaian`;
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.status === 'success') {
                    displayPenilaians(data.data);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat data penilaian');
            }
            showLoading(false);
        }

        async function loadKandidats() {
            try {
                const response = await fetch(`${API_URL}/kandidat`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    const select = document.getElementById('kandidat_id');
                    select.innerHTML = '<option value="">Pilih Kandidat</option>';
                    data.data.forEach(kandidat => {
                        select.innerHTML += `<option value="${kandidat.id}">${kandidat.nama}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function loadKriterias() {
            try {
                const response = await fetch(`${API_URL}/kriteria`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    kriteriaData = data.data;
                    await loadAllSubKriterias();
                    displayKriteriaForm();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function loadAllSubKriterias() {
            try {
                const response = await fetch(`${API_URL}/sub-kriteria`);
                const data = await response.json();
                
                if (data.status === 'success') {
                    subKriteriaData = data.data;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function displayKriteriaForm() {
            const container = document.getElementById('kriteriaContainer');
            container.innerHTML = '';

            for (const kriteria of kriteriaData) {
                const response = await fetch(`${API_URL}/kriteria/${kriteria.id}/sub`);
                const result = await response.json();

                const subKrits = result.data;

                container.innerHTML += `
                    <div class="border border-gray-200 rounded-lg p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ${kriteria.nama}
                        </label>
                        <select name="sub_kriteria_${kriteria.id}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Sub Kriteria</option>
                            ${subKrits.map(sk => `
                                <option value="${sk.id}">
                                    ${sk.nama} (Nilai: ${sk.nilai})
                                </option>
                            `).join('')}
                        </select>
                    </div>
                `;
            }
        }
        function displayPenilaians(penilaians) {
            const tbody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');

            if (penilaians.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            tbody.innerHTML = '';

            penilaians.forEach((penilaian, index) => {
                const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
                const totalKriteria = penilaian.detail_penilaian ? penilaian.detail_penilaian.length : 0;

                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${penilaian.kandidat ? penilaian.kandidat.nama : '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${periode}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${totalKriteria}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="showDetail(${penilaian.id})" 
                                class="text-blue-600 hover:text-blue-900">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button onclick="hitungSMART(${penilaian.id})" 
                                class="text-green-600 hover:text-green-900">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="deletePenilaian(${penilaian.id})" 
                                class="text-red-600 hover:text-red-900">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            });
        }

        async function submitPenilaian(event) {
            event.preventDefault();

            const kandidatId = document.getElementById('kandidat_id').value;
            const subKriteriaArray = [];

            kriteriaData.forEach(kriteria => {
                const select = document.querySelector(`select[name="sub_kriteria_${kriteria.id}"]`);
                if (select && select.value) {
                    subKriteriaArray.push({
                        kriteria_id: kriteria.id,
                        sub_kriteria_id: parseInt(select.value)
                    });
                }
            });

            try {
                const response = await fetch(`${API_URL}/penilaian`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kandidat_id: parseInt(kandidatId),
                        sub_kriteria_id: subKriteriaArray
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    alert('Penilaian berhasil ditambahkan');
                    closeModal();
                    loadPenilaians();
                } else {
                    alert(data.message || 'Gagal menambahkan penilaian');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            }
        }

        async function showDetail(id) {
            try {
                const response = await fetch(`${API_URL}/penilaian/${id}`);
                const data = await response.json();

                if (data.status === 'success') {
                    const penilaian = data.data;
                    const periode = new Date(penilaian.periode).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });

                    let detailHTML = `
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Kandidat</p>
                                <p class="text-lg font-semibold">${penilaian.kandidat.nama}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Periode</p>
                                <p class="text-lg font-semibold">${periode}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-2">Detail Penilaian</p>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sub Kriteria</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                    `;

                    penilaian.detail_penilaian.forEach(detail => {
                        detailHTML += `
                            <tr>
                                <td class="px-4 py-2 text-sm">${detail.kriteria.nama}</td>
                                <td class="px-4 py-2 text-sm">${detail.sub_kriteria.nama}</td>
                                <td class="px-4 py-2 text-sm font-semibold">${detail.sub_kriteria.nilai}</td>
                            </tr>
                        `;
                    });

                    detailHTML += `
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                    document.getElementById('detailContent').innerHTML = detailHTML;
                    document.getElementById('modalDetail').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal memuat detail penilaian');
            }
        }

        async function hitungSMART(id) {
            if (!confirm('Apakah Anda yakin ingin menghitung nilai SMART untuk penilaian ini?')) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}/penilaian/${id}/hitung`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    alert(`Perhitungan SMART berhasil!\nNilai Akhir: ${data.data.total_skor}`);
                    loadPenilaians();
                } else {
                    alert(data.message || 'Gagal menghitung nilai SMART');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghitung nilai SMART');
            }
        }

        async function deletePenilaian(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus penilaian ini?')) {
                return;
            }

            try {
                const response = await fetch(`${API_URL}/penilaian/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    alert('Penilaian berhasil dihapus');
                    loadPenilaians();
                } else {
                    alert('Gagal menghapus penilaian');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus data');
            }
        }

        function filterByPeriode() {
            const periode = document.getElementById('filterPeriode').value;
            loadPenilaians(periode);
        }

        function clearFilter() {
            document.getElementById('filterPeriode').value = '';
            loadPenilaians();
        }

        function openModal() {
            document.getElementById('modalTambah').classList.remove('hidden');
            document.getElementById('formPenilaian').reset();
        }

        function closeModal() {
            document.getElementById('modalTambah').classList.add('hidden');
        }

        function closeDetailModal() {
            document.getElementById('modalDetail').classList.add('hidden');
        }

        function showLoading(show) {
            const loading = document.getElementById('loadingState');
            const table = document.querySelector('table');
            
            if (show) {
                loading.classList.remove('hidden');
                table.classList.add('hidden');
            } else {
                loading.classList.add('hidden');
                table.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
@endsection
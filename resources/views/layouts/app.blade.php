<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M - Coffie</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="css/style.css">
     {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        {{--  --}}
        @include('layouts.sidebar')


        <!-- ========================= Main ==================== -->
        <div class="main">
            @include('layouts.navbar')
            @yield('content')
        </div>
    </div>

    <style>
        .main {
            position: absolute;
            width: calc(100% - 300px);
            left: 300px;
            min-height: 100vh;
            background: var(--white);
            transition: 0.5s;
            margin-top: 60px; /* GANTI PADDING DENGAN MARGIN */
        }

        .main.active {
            width: calc(100% - 80px);
            left: 80px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .topbar {
                left: 0;
                width: 100%;
            }
            
            .main {
                width: 100%;
                left: 0;
                margin-top: 60px;
            }
            
            .main.active {
                left: 0;
                width: 100%;
            }
        }

        /* kandidat */
        /* ===================== ACTION BUTTONS SIMPLE ===================== */

        .details .recentCustomers .cardHeader {
            margin-bottom: 20px; /* Menambah jarak antara judul dan tabel */
            padding-bottom: 15px;
            border-bottom: 1px solid #eee; /* Opsional: tambah garis pemisah */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-action ion-icon {
            font-size: 18px;
        }

        /* Edit Button - sama dengan primary */
        .btn-action.edit {
            background-color: var(--blue);
            color: var(--white);
        }

        .btn-action.edit:hover {
            background-color: #241d6c;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(42, 33, 133, 0.2);
        }

        /* Delete Button - sama dengan danger */
        .btn-action.delete {
            background-color: #F44336;
            color: white;
        }

        .btn-action.delete:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(244, 67, 54, 0.3);
        }

        /* Rate Button - sama dengan warning */
        .btn-action.rate {
            background-color: #FF9800;
            color: white;
        }

        .btn-action.rate:hover {
            background-color: #F57C00;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(255, 152, 0, 0.3);
        }

        /* Active state untuk rate */
        .btn-action.rate.active {
            background-color: #ffc107;
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                gap: 8px;
            }
            
            .btn-action {
                width: 36px;
                height: 36px;
            }
            
            .btn-action ion-icon {
                font-size: 16px;
            }
        }

        /* style status atau reting */
        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
            text-align: center;
            min-width: 100px;
        }

        .status.delivered {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status.pending {
            background: #fff3e0;
            color: #ef6c00;
        }

        .status.inProgress {
            background: #e3f2fd;
            color: #1565c0;
        }

        .status.return {
            background: #ffebee;
            color: #c62828;
        }

        /* Make it consistent in table */
        .details .recentOrders table tr td {
            padding: 12px 8px !important;
        }

        td .status {
            margin: 2px 0;
        }

        /* Empty State Styles */
        .empty-state {
            text-align: center;
            padding: 60px 20px !important;
            color: #999;
        }

        .empty-state ion-icon {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 16px;
            display: block;
        }

        .empty-state h3 {
            margin: 0 0 8px 0;
            color: #666;
            font-size: 18px;
            font-weight: 500;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
            color: #999;
        }

        /* Override hover effect for empty state */
        .details .recentOrders table tr.empty-state-row:hover {
            background: var(--white) !important;
            color: #999 !important;
        }

        .details .recentCustomers table tr.empty-state-row:hover {
            background: var(--white) !important;
            color: #999 !important;
        }

        /* Avatar styling for Top 5 */
        .recentCustomers .imgBx .avatar {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--blue);
            color: var(--white);
            font-weight: 600;
            font-size: 18px;
        }
        /* kandidat end */

        /* button */
    /* Button Base Styles */
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

/* Primary Button */
.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

/* Outline Primary Button */
.btn-outline-primary {
    background: white;
    color: #007bff;
    border: 2px solid #007bff;
}

.btn-outline-primary:hover {
    background: #007bff;
    color: white;
}

/* Outline Secondary Button */
.btn-outline-secondary {
    background: white;
    color: #6c757d;
    border: 2px solid #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
}

/* Outline Danger Button */
.btn-outline-danger {
    background: white;
    color: #dc3545;
    border: 2px solid #dc3545;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
}

/* Success Button */
.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #1e7e34;
}

/* Disabled State */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
    /* button end */
    </style>


    <!-- =========== Scripts =========  -->
    <script>
        // Konfigurasi API
        const API_BASE = '{{ url("/api") }}';
        
        // Setup Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.defaults.headers.common['Content-Type'] = 'application/json';
        
        // Interceptor untuk handling errors
        axios.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 419) {
                    alert('Session expired. Please refresh the page.');
                    window.location.reload();
                }
                return Promise.reject(error);
            }
        );
    </script>
    <script src="js/main.js"></script>
    <script src=js></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
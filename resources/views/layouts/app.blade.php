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
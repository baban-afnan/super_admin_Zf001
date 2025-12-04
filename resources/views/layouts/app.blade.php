<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="SmartHR - Advanced Bootstrap 5 admin dashboard template">
    <meta name="keywords" content="HR dashboard, CRM admin template, workforce management, employee management">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="icon" href="{{ asset('assets/img/logo/logo.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo/logo.png') }}" type="image/x-icon" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icons/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-lite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bokanturai.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        /* Loader styles */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }
        .page-loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Hide page content until loader completes */
        #pageWrapper { opacity: 0; transition: opacity 0.3s ease; }
        #pageWrapper.loaded { opacity: 1; }
    </style>
</head>

<body>
    <!-- Tap to top -->
    <div class="tap-top"><i class="iconly-Arrow-Up icli"></i></div>

    <!-- Loader -->
    <div id="global-loader">
        <div class="page-loader"></div>
    </div>

    <!-- Page Wrapper -->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        @include('layouts.partials.header')
        <div class="page-body-wrapper">
            @include('layouts.partials.sidebar')
            <div class="page-body">
                <div class="container-fluid">
                    @isset($header)
                        <div class="page-title">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h2>{{ $header }}</h2>
                                </div>
                            </div>
                        </div>
                    @endisset

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer bg-primary text-light py-3 mt-auto">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 small">© <span id="currentYear"></span> <strong class="text-dark">Arewa Smart Idea</strong>. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="d-inline-flex align-items-center gap-3">
                        <a href="#" target="_blank" class="text-light text-decoration-none footer-social"><i class="ti ti-brand-facebook fs-18"></i></a>
                        <a href="#" target="_blank" class="text-light text-decoration-none footer-social"><i class="ti ti-brand-twitter fs-18"></i></a>
                        <a href="#" target="_blank" class="text-light text-decoration-none footer-social"><i class="ti ti-brand-whatsapp fs-18"></i></a>
                        <a href="#" class="text-light text-decoration-none footer-social"><i class="ti ti-mail fs-18"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Auto year
        document.getElementById("currentYear").textContent = new Date().getFullYear();

        // Hide loader after page load
        window.addEventListener('load', function () {
            const loader = document.getElementById('global-loader');
            const page = document.getElementById('pageWrapper');
            loader.classList.add('hidden');
            page.classList.add('loaded');
        });
    </script>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart-data.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/@simonwep/pickr/pickr.es5.min.js') }}"></script>
    <script src="{{ asset('assets/js/todo.js') }}"></script>
    <script src="{{ asset('assets/js/theme-colorpicker.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/bokanturai.js') }}"></script>
    <script src="{{ asset('assets/js/data.js') }}"></script>
    <script src="{{ asset('assets/js/airtime.js') }}"></script>
    <script src="{{ asset('assets/js/pin.js') }}"></script>
    <script src="{{ asset('assets/js/bvnservices.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    @stack('scripts')
</body>
</html>

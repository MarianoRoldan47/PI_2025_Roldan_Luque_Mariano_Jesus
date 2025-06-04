<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-..." crossorigin="anonymous">


    <script src="https://kit.fontawesome.com/3896197f07.js" crossorigin="anonymous"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">



    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        body {
            background-color: #121212;
        }

        .login-container {
            background-color: #2a324f;
            border-radius: 1rem;
        }

        .auth-logo {
            width: 250px;
            height: auto;
        }

        .modal.fade .modal-dialog {
            transform: scale(0.8);
            transition: transform 0.3s ease-in-out;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        .modal-icon {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounceIn 0.6s;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
    @livewireStyles
</head>

<body>
    @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalTitle">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="text-white modal-content bg-dark">
                    <div class="border-white modal-header border-bottom">
                        <h5 class="text-white modal-title" id="successModalTitle">
                            <i class="fas fa-check-circle text-success me-2"></i>¡Éxito!
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 text-white modal-icon bg-success rounded-circle me-3">
                                <i class="fas fa-check fa-2x"></i>
                            </div>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div class="border-white modal-footer border-top">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                            id="successModalButton">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-4 min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="mb-4">
            <a href="/" wire:navigate>
                <img src="{{ asset('img/banner_sidebar.png') }}" class="auth-logo rounded-2" alt="CyberStock WMS">
            </a>
        </div>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-7">
                    <div class="p-4 shadow-lg login-container p-sm-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-..." crossorigin="anonymous">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/3896197f07.js" crossorigin="anonymous"></script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
            opacity: 1;
        }

        .form-control:-ms-input-placeholder,
        .form-control::-ms-input-placeholder {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        .product-image {
            height: 100px;
        }

        @media (min-width: 576px) {
            .product-image {
                height: 200px;
            }
        }

        #sidebar {
            width: 260px;
            transition: all 0.3s ease;
        }

        @media (max-width: 576px) {
            #sidebar {
                width: 60px;
            }

            #sidebar .nav-link {
                padding: 0.5rem;
                justify-content: center;
            }

            #sidebar .dropdown-toggle::after {
                display: none;
            }

            .main-content {
                margin-left: 60px;
            }
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

        /* Estilos para la paginación */
        .pagination {
            justify-content: center;
            margin-bottom: 0;
        }

        .page-item:not(:first-child) .page-link {
            margin-left: -1px;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #fff;
            background-color: #212529;
            border: 1px solid #373b3e;
        }

        .page-link:hover {
            color: #fff;
            background-color: #2c3034;
            border-color: #373b3e;
        }

        /* Ajustes específicos para el contenedor de paginación */
        .pagination-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        body {
            background-color: white;
        }
    </style>

    @stack('styles')

    @livewireStyles
</head>

<body class="d-flex vh-100">

    <livewire:layout.navigation />

    @if (session('success'))
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalTitle">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-bottom border-white">
                        <h5 class="modal-title text-white" id="successModalTitle">
                            <i class="fas fa-check-circle text-success me-2"></i>¡Éxito!
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <div class="modal-icon bg-success text-white rounded-circle p-3 me-3">
                                <i class="fas fa-check fa-2x"></i>
                            </div>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                            id="successModalButton">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session('status') && session('status-type') === 'danger')
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalTitle">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-bottom border-white">
                        <h5 class="modal-title text-white" id="errorModalTitle">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>¡Error!
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <div class="modal-icon bg-danger text-white rounded-circle p-3 me-3">
                                <i class="fas fa-times fa-2x"></i>
                            </div>
                            <p class="mb-0">{{ session('status') }}</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-white">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                            id="errorModalButton">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <main class="flex-grow-1 overflow-auto p-4">
        {{ $slot }}
    </main>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Verificar si existe el modal
            const successModal = document.getElementById('successModal');
            if (successModal) {
                try {
                    // Crear instancia del modal
                    const modal = new bootstrap.Modal(successModal);

                    // Mostrar el modal
                    modal.show();

                    // Evento cuando el modal se oculta
                    successModal.addEventListener('hidden.bs.modal', function() {
                        modal.dispose(); // Limpiar recursos
                    });
                } catch (error) {
                    console.error('Error al inicializar el modal:', error);
                }
            }

            const errorModal = document.getElementById('errorModal');
            if (errorModal) {
                try {
                    const modal = new bootstrap.Modal(errorModal);
                    modal.show();
                    errorModal.addEventListener('hidden.bs.modal', function() {
                        modal.dispose();
                    });
                } catch (error) {
                    console.error('Error al inicializar el modal de error:', error);
                }
            }
        });
    </script>

    @stack('scripts')

    @livewireScripts
</body>

</html>

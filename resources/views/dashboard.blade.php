<x-app-layout>
    @vite('resources/css/views/dashboard.css')

    <div class="p-4 container-fluid h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="fs-3">PANEL DE CONTROL</h1>
                <p class="text-muted">Resumen de actividad del almacén</p>
            </div>
            <div class="gap-2 col text-end d-flex justify-content-end align-items-center">
                <a href="{{ route('productos.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                </a>

                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMovimientosBtn"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Movimiento
                    </button>
                    <ul class="shadow-sm dropdown-menu dropdown-menu-dark dropdown-menu-end"
                        aria-labelledby="dropdownMovimientosBtn">
                        <li>
                            <a class="py-2 dropdown-item"
                                href="{{ route('movimientos.create', ['tipo' => 'entrada']) }}">
                                <i class="fas fa-arrow-right text-success me-2"></i> Entrada
                            </a>
                        </li>
                        <li>
                            <a class="py-2 dropdown-item"
                                href="{{ route('movimientos.create', ['tipo' => 'traslado']) }}">
                                <i class="fas fa-exchange-alt text-info me-2"></i> Traslado
                            </a>
                        </li>
                        <li>
                            <a class="py-2 dropdown-item"
                                href="{{ route('movimientos.create', ['tipo' => 'salida']) }}">
                                <i class="fas fa-arrow-left text-danger me-2"></i> Salida
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row g-4 informativos">
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('productos.index') }}" class="text-decoration-none">
                    <div class="text-white border-0 shadow-sm card bg-dark h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-box-seam fs-2 text-warning me-3"></i>
                            <div>
                                <h5 class="mb-1 card-title">Productos</h5>
                                <p class="card-text fs-4">{{ $productoscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('users.index') }}" class="text-decoration-none">
                    <div class="text-white border-0 shadow-sm card bg-dark h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-person-lines-fill fs-2 text-cyan me-3"></i>
                            <div>
                                <h5 class="mb-1 card-title">Usuarios</h5>
                                <p class="card-text fs-4">{{ $usuarioscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('movimientos.index') }}" class="text-decoration-none">
                    <div class="text-white border-0 shadow-sm card bg-dark h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-graph-up fs-2 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-1 card-title">Movimientos</h5>
                                <p class="card-text fs-4">{{ $movimientoscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('alertas.index') }}" class="text-decoration-none">
                    <div class="text-white border-0 shadow-sm card bg-dark h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle fs-2 text-orange me-3"></i>
                            <div>
                                <h5 class="mb-1 card-title">Alertas de Stock</h5>
                                <p class="card-text fs-4">{{ $alertascount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="overflow-auto row flex-grow-1 d-flex align-items-center">
            <div class="col-12 col-lg-6">
                <div class="mt-4 text-white card bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Movimientos semanales</h5>
                        <canvas id="movimientosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="mt-4 text-white card bg-dark">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Ranking de Usuarios Mensual</h5>
                        </div>
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.dashboardMovimientosData = {
                labels: @json($labels),
                data: @json($data),
            };

            window.dashboardRankingData = @json($usuarios);
        </script>
        @vite('resources/js/charts/dashboard.js')
    @endpush
</x-app-layout>

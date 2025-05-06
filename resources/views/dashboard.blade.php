<x-app-layout>
    @vite('resources/css/views/dashboard.css')

    <div class="container-fluid p-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fs-3">PANEL DE CONTROL</h1>
                <p class="text-muted">Resumen de actividad del almacén</p>
            </div>
            <div class="col text-end">
                <a href="" class="text-decoration-none">
                    <button class="btn btn-info text-white">+ Nuevo Movimiento</button>
                </a>
            </div>
        </div>

        <div class="row g-4 informativos">
            <div class="col-12 col-md-6 col-xl-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark border-0 shadow-sm text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-box-seam fs-2 text-warning me-3"></i>
                            <div>
                                <h5 class="card-title mb-1">Productos</h5>
                                <p class="card-text fs-4">{{ $productoscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark border-0 shadow-sm text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-person-lines-fill fs-2 text-cyan me-3"></i>
                            <div>
                                <h5 class="card-title mb-1">Usuarios</h5>
                                <p class="card-text fs-4">{{ $usuarioscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark border-0 shadow-sm text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-graph-up fs-2 text-primary me-3"></i>
                            <div>
                                <h5 class="card-title mb-1">Movimientos</h5>
                                <p class="card-text fs-4">{{ $movimientoscount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="#" class="text-decoration-none">
                    <div class="card bg-dark border-0 shadow-sm text-white h-100">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle fs-2 text-orange me-3"></i>
                            <div>
                                <h5 class="card-title mb-1">Alertas de Stock</h5>
                                <p class="card-text fs-4">{{ $alertascount }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="card bg-dark text-white mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Movimientos semanales</h5>
                        <canvas id="movimientosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="card bg-dark text-white mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Ranking de Usuarios</h5>
                        <canvas id="rankingChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.dashboardMovimientosData = {
                labels: @json($labels),
                data: @json($data),
            };

            window.dashboardRankingData = @json($usuarios);
        </script>
    </div>

    @vite('resources/js/charts/dashboard.js')
</x-app-layout>

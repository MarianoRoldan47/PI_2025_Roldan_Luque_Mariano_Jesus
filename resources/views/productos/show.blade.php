<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <!-- Header mejorado -->
        <div class="row g-2 mb-2 mb-sm-4">
            <div class="col-12 col-md">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h1 class="h3">{{ $producto->nombre }}</h1>
                        <p>Detalles del producto</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center gap-2">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                @endif
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <!-- Contenido -->
        <div class="row g-2 g-sm-4">
            <!-- Tarjeta de información -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-lg h-100">
                    <!-- Imagen con overlay gradiente -->
                    <div class="position-relative" style="height: 200px;">
                        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                            class="w-100 h-100 object-fit-cover" alt="{{ $producto->nombre }}">
                        <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 p-2">
                            <h5 class="card-title text-white mb-0 fs-6">
                                <i class="fas fa-info-circle me-2"></i>Información General
                            </h5>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="card-body p-3">
                        <div class="list-group list-group-flush bg-transparent">
                            <!-- Código -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Código</span>
                                    <span class="badge bg-secondary">{{ $producto->codigo_producto }}</span>
                                </div>
                            </div>

                            <!-- Categoría -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Categoría</span>
                                    <span class="badge bg-info">{{ $producto->categoria->nombre }}</span>
                                </div>
                            </div>

                            <!-- Tipo -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Tipo</span>
                                    <span class="badge bg-primary">{{ ucfirst($producto->tipo) }}</span>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Stock Total</span>
                                    <span class="badge bg-light text-dark">{{ $producto->stock_total }}</span>
                                </div>
                            </div>

                            <!-- Stock Mínimo -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Stock Mínimo</span>
                                    <span class="badge bg-light text-dark">{{ $producto->stock_minimo_alerta }}</span>
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="list-group-item bg-transparent text-white border-secondary py-2 px-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Estado</span>
                                    <span
                                        class="badge {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'bg-danger' : 'bg-success' }}">
                                        {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'Stock Bajo' : 'Stock OK' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-8">
                <!-- Descripción -->
                <div class="card bg-dark border-secondary shadow-sm mb-2 mb-sm-4">
                    <div class="card-body p-3">
                        <h5 class="d-flex align-items-center text-white mb-3">
                            <i class="fas fa-align-left me-2 text-info"></i>
                            <span class="fs-6">Descripción</span>
                        </h5>
                        <p class="text-white mb-0">{{ $producto->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                </div>

                <!-- Ubicaciones -->
                <div class="card bg-dark border-secondary shadow-sm mb-2 mb-sm-4">
                    <div class="card-body p-3">
                        <h5 class="d-flex align-items-center text-info mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="fs-6">Ubicaciones</span>
                        </h5>
                        @if ($producto->estanterias->isEmpty() || $producto->estanterias->sum('pivot.cantidad') == 0)
                            <div class="alert alert-dark bg-dark-subtle border-secondary mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Este producto no está almacenado en ninguna estantería.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover small mb-0">
                                    <thead>
                                        <tr>
                                            <th>Estantería</th>
                                            <th>Zona</th>
                                            <th class="text-end">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->estanterias as $estanteria)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-archive text-primary me-1"></i>
                                                    <span class="text-white">Estanteria
                                                        {{ $estanteria->nombre }}</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-signs text-info me-1"></i>
                                                    {{ $estanteria->zona->nombre }}
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-secondary">
                                                        {{ $estanteria->pivot->cantidad }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Movimientos -->
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body p-3">
                        <h5 class="d-flex align-items-center text-info mb-3">
                            <i class="fas fa-exchange-alt me-2"></i>
                            <span class="fs-6">Últimos Movimientos</span>
                        </h5>
                        @if ($producto->movimientos->isEmpty())
                            <div class="alert alert-dark bg-dark-subtle border-secondary mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay movimientos registrados para este producto.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover small mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th class="text-end">Cantidad</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->movimientos as $movimiento)
                                            <tr class="cursor-pointer hover-bg-opacity-75"
                                                onclick="window.location='{{ route('movimientos.show', $movimiento) }}'">
                                                <td>
                                                    <i class="fas fa-calendar-alt text-info me-1"></i>
                                                    {{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }}">
                                                        <i
                                                            class="fas fa-{{ $movimiento->tipo === 'entrada' ? 'arrow-right' : ($movimiento->tipo === 'salida' ? 'arrow-left' : 'exchange-alt') }} me-1"></i>
                                                        {{ ucfirst($movimiento->tipo) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge bg-secondary">
                                                        {{ $movimiento->cantidad }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-user text-info me-1"></i>
                                                    {{ $movimiento->usuario->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .fas {
                opacity: 0.9;
            }

            .bg-gradient-dark {
                background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.8) 100%);
            }

            .cursor-pointer {
                cursor: pointer;
            }

            .hover-bg-opacity-75:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
            }
        </style>
    @endpush
</x-app-layout>

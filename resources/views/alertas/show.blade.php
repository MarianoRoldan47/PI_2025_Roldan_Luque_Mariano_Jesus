<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">DETALLES DE ALERTA</h1>
                <p class="text-muted">Producto: {{ $alerta->producto->nombre }}</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center gap-2">
                <a href="{{ route('movimientos.create', ['tipo' => 'entrada', 'producto_id' => $alerta->producto->id]) }}"
                    class="btn btn-success btn-sm">
                    <i class="fas fa-arrow-right me-1"></i> Registrar Entrada
                </a>
                @if (Auth::user()->rol === 'Administrador')
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar Alerta
                    </button>
                @endif
                <a href="{{ route('alertas.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Información de la Alerta
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-white small mb-1">Fecha de Alerta:</label>
                            <p class="mb-0 d-flex align-items-center text-white">
                                <i class="fas fa-calendar-alt text-info me-2"></i>
                                {{ $alerta->fecha_alerta->format('d/m/Y H:i:s') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="text-white small mb-1">Estado:</label>
                            @php
                                $porcentaje = ($alerta->stock_actual / $alerta->producto->stock_minimo_alerta) * 100;
                            @endphp

                            @if ($alerta->stock_actual == 0)
                                <span class="badge text-bg-danger">Sin Stock</span>
                            @elseif ($porcentaje <= 30)
                                <span class="badge text-bg-danger">Crítico</span>
                            @elseif ($porcentaje <= 70)
                                <span class="badge text-bg-warning">Bajo</span>
                            @else
                                <span class="badge text-bg-warning">Cercano al mínimo</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="text-white small mb-1">Stock Actual:</label>
                            <h4 class="mb-0 text-danger">{{ $alerta->stock_actual }}</h4>
                        </div>

                        <div class="mb-3">
                            <label class="text-white small mb-1">Stock Mínimo Alerta:</label>
                            <h4 class="mb-0 text-warning">{{ $alerta->producto->stock_minimo_alerta }}</h4>
                        </div>

                        <div>
                            <label class="text-white small mb-1">Estado del Stock:</label>
                            <div class="progress bg-dark-subtle" style="height: 10px;">
                                <div class="progress-bar {{ $porcentaje <= 30 ? 'bg-danger' : 'bg-warning' }}"
                                    style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <div class="text-end small text-white mt-1">
                                {{ number_format($porcentaje, 0) }}% del stock mínimo
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-box text-primary me-2"></i>
                            Detalles del Producto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-12 col-sm-4 col-lg-3 text-center mb-3 mb-sm-0">
                                <img src="{{ $alerta->producto->imagen ? asset('storage/' . $alerta->producto->imagen) : asset('img/default-product.png') }}"
                                    class="img-fluid rounded mb-2" alt="{{ $alerta->producto->nombre }}"
                                    style="max-height: 150px; object-fit: contain;">
                                <div>
                                    <a href="{{ route('productos.show', $alerta->producto) }}"
                                        class="btn btn-primary btn-sm d-block mt-2">
                                        <i class="fas fa-eye me-2"></i>Ver Producto
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-sm-8 col-lg-9">
                                <h4 class="mb-2 text-white">{{ $alerta->producto->nombre }}</h4>

                                <div class="mb-2">
                                    <span class="badge text-bg-dark border border-secondary">
                                        <i class="fas fa-barcode text-white me-1"></i>
                                        {{ $alerta->producto->codigo_producto }}
                                    </span>

                                    <span class="badge text-bg-info">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $alerta->producto->categoria->nombre }}
                                    </span>
                                </div>

                                <p class="mb-3 small text-white">
                                    {{ $alerta->producto->descripcion ? $alerta->producto->descripcion : 'Sin descripción' }}
                                </p>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card bg-dark-subtle border-secondary">
                                            <div class="card-body p-3">
                                                <h6 class="card-title d-flex align-items-center">
                                                    <i class="fas fa-warehouse text-black me-2"></i>
                                                    Ubicación
                                                </h6>
                                                @if ($alerta->producto->estanterias->isNotEmpty())
                                                    <div class="small">
                                                        @foreach ($alerta->producto->estanterias as $estanteria)
                                                            <div
                                                                class="d-flex justify-content-between align-items-center mb-1">
                                                                <span>
                                                                    <i
                                                                        class="fas fa-map-marker-alt text-danger me-1"></i>
                                                                    {{ $estanteria->nombre }}
                                                                    ({{ $estanteria->zona->nombre }})
                                                                    :
                                                                </span>
                                                                <span class="badge text-bg-secondary">
                                                                    {{ $estanteria->pivot->cantidad }} uds
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <p class="small mb-0">No asignado a estanterías</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark shadow-sm mt-4">
                    <div class="card-header bg-dark border-secondary d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-history text-primary me-2"></i>
                            Últimos Movimientos
                        </h5>
                        <a href="{{ route('movimientos.index', ['producto_id' => $alerta->producto->id]) }}"
                            class="btn btn-primary btn-sm">
                            Ver todos
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($alerta->producto->movimientos->isEmpty())
                            <div class="alert alert-dark m-3 mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay movimientos registrados para este producto.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover align-middle small mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th class="text-end">Cantidad</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alerta->producto->movimientos->take(5) as $movimiento)
                                            <tr class="cursor-pointer hover-bg-opacity-75"
                                                onclick="window.location='{{ route('movimientos.show', $movimiento) }}'">
                                                <td>
                                                    <i class="fas fa-calendar-alt text-info me-1"></i>
                                                    {{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}
                                                </td>
                                                <td>
                                                    @if ($movimiento->tipo == 'entrada')
                                                        <span class="badge text-bg-success">
                                                            <i class="fas fa-arrow-right me-1"></i>Entrada
                                                        </span>
                                                    @elseif ($movimiento->tipo == 'salida')
                                                        <span class="badge text-bg-danger">
                                                            <i class="fas fa-arrow-left me-1"></i>Salida
                                                        </span>
                                                    @else
                                                        <span class="badge text-bg-secondary">
                                                            <i class="fas fa-exchange-alt me-1"></i>Traslado
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold">
                                                    {{ $movimiento->cantidad }}
                                                </td>
                                                <td>
                                                    @if ($movimiento->usuario)
                                                        <span class="d-inline-flex align-items-center">
                                                            <i class="fas fa-user text-white me-1"></i>
                                                            {{ $movimiento->usuario->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-white">—</span>
                                                    @endif
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

    @if (Auth::user()->rol === 'Administrador')
        <!-- Modal de confirmación para eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-danger">
                    <div class="modal-header border-danger">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar esta alerta de stock bajo?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>Esto solo eliminará la notificación, no afectará al stock real del producto.</span>
                        </div>
                        <div class="alert alert-secondary small">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Si el stock sigue por debajo del mínimo, la alerta se generará nuevamente en el
                                próximo ciclo de verificación.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('alertas.destroy', $alerta) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

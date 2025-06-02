<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">

        <div class="mb-2 row g-2 mb-sm-4">
            <div class="col-12 col-md">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h1 class="h3">{{ $producto->nombre }}</h1>
                        <p>Detalles del producto</p>
                    </div>
                </div>
            </div>
            <div class="gap-2 col-12 col-md d-flex justify-content-end align-items-center">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                @endif
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>


        <div class="row g-2 g-sm-4">

            <div class="col-md-4">
                <div class="shadow-lg card bg-dark border-secondary h-100">

                    <div class="position-relative" style="height: 200px;">
                        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                            class="w-100 h-100 object-fit-cover" alt="{{ $producto->nombre }}">
                        <div class="bottom-0 p-2 bg-opacity-75 position-absolute start-0 w-100 bg-dark">
                            <h5 class="mb-0 text-white card-title fs-6">
                                <i class="fas fa-info-circle me-2"></i>Información General
                            </h5>
                        </div>
                    </div>


                    <div class="p-3 card-body">
                        <div class="bg-transparent list-group list-group-flush">

                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Código</span>
                                    <span class="badge bg-secondary">{{ $producto->codigo_producto }}</span>
                                </div>
                            </div>


                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Categoría</span>
                                    <span class="badge bg-info">{{ $producto->categoria->nombre }}</span>
                                </div>
                            </div>


                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Tipo</span>
                                    <span class="badge bg-primary">{{ ucfirst($producto->tipo) }}</span>
                                </div>
                            </div>


                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Stock Total</span>
                                    <span class="badge bg-light text-dark">{{ $producto->stock_total }}</span>
                                </div>
                            </div>


                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-white">Stock Mínimo</span>
                                    <span class="badge bg-light text-dark">{{ $producto->stock_minimo_alerta }}</span>
                                </div>
                            </div>


                            <div class="px-0 py-2 text-white bg-transparent list-group-item border-secondary">
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

            <div class="col-md-8">
                <div class="mb-2 shadow-sm card bg-dark border-secondary mb-sm-4">
                    <div class="p-3 card-body">
                        <h5 class="mb-3 text-white d-flex align-items-center">
                            <i class="fas fa-align-left me-2 text-info"></i>
                            <span class="fs-6">Descripción</span>
                        </h5>
                        <p class="mb-0 text-white">{{ $producto->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                </div>

                <div class="mb-2 shadow-sm card bg-dark border-secondary mb-sm-4">
                    <div class="p-3 card-body">
                        <h5 class="mb-3 d-flex align-items-center text-info">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span class="fs-6">Ubicaciones</span>
                        </h5>
                        @if ($producto->estanterias->isEmpty() || $producto->estanterias->sum('pivot.cantidad') == 0)
                            <div class="mb-0 alert alert-dark bg-dark-subtle border-secondary">
                                <i class="fas fa-info-circle me-2"></i>
                                Este producto no está almacenado en ninguna estantería.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 table-dark table-hover small">
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

                <div class="shadow-sm card bg-dark border-secondary">
                    <div class="p-3 card-body">
                        <h5 class="mb-3 d-flex align-items-center text-info">
                            <i class="fas fa-exchange-alt me-2"></i>
                            <span class="fs-6">Últimos Movimientos</span>
                        </h5>
                        @if ($producto->movimientos->isEmpty())
                            <div class="mb-0 alert alert-dark bg-dark-subtle border-secondary">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay movimientos registrados para este producto.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 table-dark table-hover small">
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

    @if (Auth::user()->rol === 'Administrador')
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark border-danger">
                    <div class="text-white modal-header border-danger">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="text-white modal-body">
                        <p>¿Estás seguro de que deseas eliminar este producto?</p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atención:</strong> Esta acción eliminará el producto y todos sus datos relacionados.
                            Esta acción no se puede deshacer.
                        </div>
                        <div class="p-3 border rounded d-flex align-items-center border-secondary">
                            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                class="rounded me-3" alt="{{ $producto->nombre }}"
                                style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-0">{{ $producto->nombre }}</h6>
                                <small class="text-muted">{{ $producto->codigo_producto }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash-alt me-1"></i> Eliminar producto
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

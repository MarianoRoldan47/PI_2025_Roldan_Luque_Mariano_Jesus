<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-3 py-sm-4">
        <!-- Header simple -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Movimiento #{{ $movimiento->id }}</h1>

                <span
                    class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }} text-white">
                    {{ ucfirst($movimiento->tipo) }}
                </span>
            </div>

            <div class="d-flex gap-2 mt-3 mt-md-0">
                @if (Auth::user()->rol === 'Administrador' || Auth::user()->id === $movimiento->user_id)
                    <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                @endif
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="row g-4">
            <!-- Tarjeta de información -->
            <div class="col-12">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Detalles básicos -->
                            <div class="col-lg-6">
                                <h5 class=" mb-4 fw-bold text-white">
                                    <i class="fas fa-info-circle me-2 text-primary"></i> Información básica
                                </h5>

                                <div class="d-flex mb-4 align-items-center text-white">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="far fa-calendar-alt text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-light fw-bold">Fecha y hora</div>
                                        <div class="text-white">{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-4 align-items-center text-white">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-light fw-bold">Usuario</div>
                                        <div>
                                            @if ($movimiento->usuario)
                                                <a href="{{ route('users.show', $movimiento->usuario) }}"
                                                    class="text-white text-decoration-none">
                                                    {{ $movimiento->usuario->name }}
                                                </a>
                                            @else
                                                <span class="text-light">No asignado</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-4 align-items-center text-white">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-box text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-light fw-bold">Producto</div>
                                        <div>
                                            @if ($movimiento->producto)
                                                <div>
                                                    <a href="{{ route('productos.show', $movimiento->producto) }}"
                                                        class="text-white text-decoration-none">
                                                        {{ $movimiento->producto->nombre }}
                                                    </a>
                                                    @if ($movimiento->producto->trashed())
                                                        <span class="badge bg-danger ms-2">Eliminado</span>
                                                    @endif
                                                </div>
                                                @if ($movimiento->producto->referencia)
                                                    <small
                                                        class="text-light">{{ $movimiento->producto->referencia }}</small>
                                                @endif
                                            @else
                                                <span class="text-light">No especificado</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex mb-4 align-items-center text-white">
                                    <div class="bg-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-hashtag text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-light fw-bold">Cantidad</div>
                                        <div class="text-white">{{ $movimiento->cantidad }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ubicaciones -->
                            <div class="col-lg-6">
                                <h5 class=" text-white mb-4 fw-bold">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i> Ubicaciones
                                </h5>

                                <div class="card bg-secondary bg-opacity-25 border-secondary mb-4">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-danger rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-sign-out-alt text-white"></i>
                                            </div>
                                            <h6 class="mb-0 text-white">Origen</h6>
                                        </div>
                                        <div class="ps-5 text-white">
                                            @if ($movimiento->origen)
                                                <div class="mb-1">
                                                    <a href="{{ route('estanterias.show', $movimiento->origen) }}"
                                                        class="text-white text-decoration-none fw-bold">
                                                        Estantería {{ $movimiento->origen->nombre }}
                                                    </a>
                                                </div>
                                                @if ($movimiento->origen->zona)
                                                    <div class="small">
                                                        <i class="fas fa-map-signs text-light me-1"></i>
                                                        <a href="{{ route('zonas.show', $movimiento->origen->zona) }}"
                                                            class="text-light text-decoration-none">
                                                            {{ $movimiento->origen->zona->nombre }}
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-white">{{ $movimiento->origen_tipo }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-4">
                                    <i class="fas fa-arrow-down text-primary fa-2x"></i>
                                </div>

                                <div class="card bg-secondary bg-opacity-25 border-secondary">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-success rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-sign-in-alt text-white"></i>
                                            </div>
                                            <h6 class="mb-0 text-white">Destino</h6>
                                        </div>
                                        <div class="ps-5 text-white">
                                            @if ($movimiento->destino)
                                                <div class="mb-1">
                                                    <a href="{{ route('estanterias.show', $movimiento->destino) }}"
                                                        class="text-white text-decoration-none fw-bold">
                                                        Estantería {{ $movimiento->destino->nombre }}
                                                    </a>
                                                </div>
                                                @if ($movimiento->destino->zona)
                                                    <div class="small">
                                                        <i class="fas fa-map-signs text-light me-1"></i>
                                                        <a href="{{ route('zonas.show', $movimiento->destino->zona) }}"
                                                            class="text-light text-decoration-none">
                                                            {{ $movimiento->destino->zona->nombre }}
                                                        </a>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-white">{{ $movimiento->destino_tipo }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal simple para eliminar -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border border-danger">
                <div class="modal-header border-bottom border-danger">
                    <h5 class="modal-title text-white">Eliminar movimiento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="text-danger mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x"></i>
                    </div>
                    <h5 class="mb-3 text-white">¿Deseas eliminar este movimiento?</h5>
                    <p class="text-light">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer border-top border-danger">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

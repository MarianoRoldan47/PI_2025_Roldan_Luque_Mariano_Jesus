<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">DETALLE DEL MOVIMIENTO</h1>
                <p>Información detallada del movimiento</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm me-2" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                @endif
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Información del Movimiento</h5>

                        <div class="table-responsive mt-3">
                            <table class="table table-dark">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px">Fecha y Hora:</th>
                                        <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipo:</th>
                                        <td>
                                            <span
                                                class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }}">
                                                <i
                                                    class="fas fa-{{ $movimiento->tipo === 'entrada' ? 'arrow-right' : ($movimiento->tipo === 'salida' ? 'arrow-left' : 'exchange-alt') }} me-1"></i>
                                                {{ ucfirst($movimiento->tipo) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Usuario:</th>
                                        <td>{{ $movimiento->usuario?->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Producto:</th>
                                        <td>
                                            {{ $movimiento->producto?->nombre ?? '—' }}
                                            @if ($movimiento->producto?->trashed())
                                                <i class="fas fa-trash-alt text-danger ms-2"
                                                    title="Producto eliminado"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Cantidad:</th>
                                        <td>{{ $movimiento->cantidad }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ubicación Origen:</th>
                                        <td>
                                            {{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre . ' - ' . $movimiento->origen->zona->nombre : $movimiento->origen_tipo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ubicación Destino:</th>
                                        <td>
                                            {{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre . ' - ' . $movimiento->destino->zona->nombre : $movimiento->destino_tipo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Creación:</th>
                                        <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Última Actualización:</th>
                                        <td>{{ $movimiento->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar movimiento -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-danger text-white">
                <div class="modal-header border-danger">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white">¿Estás seguro de que deseas eliminar este movimiento?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Esta acción no se puede deshacer y puede afectar a los registros de
                        inventario.
                    </div>

                    <div class="mt-3 p-3 border border-secondary rounded text-white">
                        <h6 class="text-white">Detalles del movimiento:</h6>
                        <ul class="list-unstyled mb-0">
                            <li><strong class="text-white">Fecha:</strong> <span
                                    class="text-white">{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</span>
                            </li>
                            <li><strong class="text-white">Tipo:</strong> <span
                                    class="text-white">{{ ucfirst($movimiento->tipo) }}</span></li>
                            <li><strong class="text-white">Producto:</strong> <span
                                    class="text-white">{{ $movimiento->producto?->nombre ?? '—' }}</span></li>
                            <li><strong class="text-white">Cantidad:</strong> <span
                                    class="text-white">{{ $movimiento->cantidad }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer border-danger">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Eliminar movimiento
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

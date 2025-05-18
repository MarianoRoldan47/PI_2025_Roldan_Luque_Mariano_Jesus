<x-app-layout>
    <div class="container-fluid p-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fs-3">MOVIMIENTOS</h1>
                <p class="text-white">Historial de entradas y salidas de productos</p>
            </div>
            <div class="col text-end">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMovimientosBtn"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Movimiento
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end shadow-sm"
                        aria-labelledby="dropdownMovimientosBtn">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('movimientos.create', ['tipo' => 'entrada']) }}">
                                <i class="fas fa-arrow-right text-success me-2"></i> Entrada
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('movimientos.create', ['tipo' => 'traslado']) }}">
                                <i class="fas fa-exchange-alt text-info me-2"></i> Traslado
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('movimientos.create', ['tipo' => 'salida']) }}">
                                <i class="fas fa-arrow-left text-danger me-2"></i> Salida
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Listado de Movimientos</h5>

                        @if ($movimientos->isEmpty())
                            <p class="text-white my-3">No hay movimientos registrados.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mt-3">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Usuario</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Origen</th>
                                            <th>Destino</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movimientos as $movimiento)
                                            <tr>
                                                <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }}">
                                                        {{ ucfirst($movimiento->tipo) }}
                                                    </span>
                                                </td>
                                                <td>{{ $movimiento->usuario?->name ?? '—' }}</td>
                                                <td>
                                                    {{ $movimiento->producto->nombre }}
                                                    @if ($movimiento->producto->trashed())
                                                        <i class="fas fa-trash-alt text-danger ms-2" title="Producto eliminado"></i>
                                                    @endif
                                                </td>
                                                <td>{{ $movimiento->cantidad }}</td>
                                                <td>{{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre . ' - ' . $movimiento->origen->zona->nombre : $movimiento->origen_tipo }}</td>
                                                <td>{{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre . ' - ' . $movimiento->destino->zona->nombre : $movimiento->destino_tipo }}</td>
                                                <td>
                                                    <a href="{{ route('movimientos.show', $movimiento) }}" class="btn btn-sm btn-primary"><i class="fa-regular fa-eye"></i></a>
                                                    <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $movimiento->id }}">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $movimientos->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal de confirmación de borrado --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-bottom border-danger">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            Confirmar eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar este movimiento? Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer border-top border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');

            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const movimientoId = button.getAttribute('data-id');
                const url = "{{ route('movimientos.destroy', ':id') }}".replace(':id', movimientoId);
                deleteForm.setAttribute('action', url);
            });
        </script>
    @endpush
</x-app-layout>

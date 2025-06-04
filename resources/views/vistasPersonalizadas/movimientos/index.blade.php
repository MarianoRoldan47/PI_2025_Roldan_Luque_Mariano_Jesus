<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">MOVIMIENTOS</h1>
                <p>Historial de entradas y salidas de productos</p>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="dropdown w-100">
                    <button class="btn btn-primary dropdown-toggle w-100" type="button" id="dropdownMovimientosBtn"
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

        <div class="row g-4">
            <div class="col-12">
                <div class="text-white shadow-sm card bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">Listado de Movimientos</h5>
                        @livewire('tabla-movimientos')
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="text-white modal-content bg-dark">
                    <div class="modal-header border-bottom border-danger">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            Confirmar eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
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

            deleteModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const movimientoId = button.getAttribute('data-id');
                const url = "{{ route('movimientos.destroy', ':id') }}".replace(':id', movimientoId);
                deleteForm.setAttribute('action', url);
            });
        </script>
    @endpush
</x-app-layout>

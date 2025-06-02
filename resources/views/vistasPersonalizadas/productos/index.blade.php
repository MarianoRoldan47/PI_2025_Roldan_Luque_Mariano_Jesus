<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">PRODUCTOS</h1>
                <p>Catálogo de productos</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                <a href="{{ route('productos.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                </a>
            </div>
        </div>

        <div class="text-white shadow-sm card bg-dark">
            <div class="p-2 card-body p-sm-3">
                @livewire('tabla-productos')
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
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <div class="p-3 text-white modal-icon bg-danger rounded-circle me-3">
                                <i class="fas fa-trash fa-2x"></i>
                            </div>
                            <p class="mb-0">¿Estás seguro de que deseas eliminar este producto? Esta acción no se
                                puede deshacer.</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="" method="POST" class="d-inline">
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
            document.addEventListener('DOMContentLoaded', function() {

                const deleteButtons = document.querySelectorAll('.delete-btn');
                const deleteForm = document.getElementById('deleteForm');


                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const productoId = this.getAttribute('data-producto-id');
                        const url = '{{ route('productos.destroy', ':id') }}'.replace(':id',
                            productoId);
                        deleteForm.action = url;
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

<x-app-layout>
    <div class="container-fluid p-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fs-3">PRODUCTOS</h1>
                <p class="text-muted">Gestión del catálogo de productos</p>
            </div>
            <div class="col text-end">
                <a href="{{ route('productos.create') }}" class="btn btn-info text-white">
                    <i class="fas fa-plus"></i> Nuevo Producto
                </a>
            </div>
        </div>

        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body">
                @if ($productos->isEmpty())
                    <p class="text-muted">No hay productos registrados.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Stock Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr>
                                        <td class="text-center">
                                            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                                alt="{{ $producto->nombre }}" class="img-thumbnail"
                                                style="max-height: 100px; width: auto;">
                                        </td>
                                        <td>{{ $producto->codigo_producto }}</td>
                                        <td>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->categoria->nombre }}</td>
                                        <td>{{ $producto->stock_total }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $producto->stock_total < $producto->stock_minimo_alerta ? 'danger' : 'success' }}">
                                                {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'Stock Bajo' : 'Stock OK' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-start">
                                                <a href="{{ route('productos.show', $producto) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('productos.edit', $producto) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                    data-producto-id="{{ $producto->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $productos->links() }}
                    </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
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
                            <div class="modal-icon bg-danger text-white rounded-circle p-3 me-3">
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
                // Obtener todos los botones de eliminar
                const deleteButtons = document.querySelectorAll('.delete-btn');
                const deleteForm = document.getElementById('deleteForm');

                // Agregar evento a cada botón
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

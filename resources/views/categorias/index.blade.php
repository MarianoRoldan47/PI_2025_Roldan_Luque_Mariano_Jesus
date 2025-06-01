{{-- filepath: resources/views/categorias/index.blade.php --}}
<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-3 py-sm-4">
        <!-- Header -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Categorías</h1>
                <p class="text-muted">Gestiona las categorías de productos del sistema</p>
            </div>

            <div class="d-flex gap-2 mt-3 mt-md-0">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaCategoriaModal">
                    <i class="fas fa-plus me-1"></i> Nueva categoría
                </button>
            </div>
        </div>

        <!-- Contenedor de tarjetas -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class=" mb-0">
                    <i class="fas fa-tags me-2 text-primary"></i> Listado de categorías
                </h5>
                <span class="badge bg-primary">{{ $categorias->count() }} categorías</span>
            </div>

            @if ($categorias->isEmpty())
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="text-info mb-3">
                            <i class="fas fa-folder-open fa-4x"></i>
                        </div>
                        <h5 class="text-white">No hay categorías registradas</h5>
                        <p class="text-white">Crea una nueva categoría para comenzar</p>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#nuevaCategoriaModal">
                            <i class="fas fa-plus me-1"></i> Nueva categoría
                        </button>
                    </div>
                </div>
            @else
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                    @foreach ($categorias as $categoria)
                        <div class="col">
                            <div class="card h-100 bg-dark border-secondary shadow-sm position-relative">
                                <!-- Badge de cantidad - Sin cambios -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    @if ($categoria->productos_count > 0)
                                        <span class="badge bg-primary">
                                            {{ $categoria->productos_count }} productos
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Sin productos
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-circle bg-primary bg-opacity-25 p-2 me-3">
                                            <i class="fas fa-tag text-primary"></i>
                                        </div>
                                        <h5 class="card-title mb-0 text-truncate text-white">{{ $categoria->nombre }}
                                        </h5>
                                    </div>

                                    <!-- Botones para todos los usuarios (eliminada la condición de rol) -->
                                    <div class="mt-auto pt-2">
                                        <div class="btn-group d-flex">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-warning btn-editar-categoria flex-fill"
                                                data-id="{{ $categoria->id }}" data-nombre="{{ $categoria->nombre }}"
                                                data-bs-toggle="modal" data-bs-target="#editarCategoriaModal">
                                                <i class="fas fa-edit me-1"></i> Editar
                                            </button>
                                            <button type="button"
                                                class="btn btn-sm btn-danger btn-eliminar-categoria flex-fill"
                                                data-id="{{ $categoria->id }}" data-nombre="{{ $categoria->nombre }}"
                                                data-productos="{{ $categoria->productos_count }}"
                                                data-bs-toggle="modal" data-bs-target="#eliminarCategoriaModal">
                                                <i class="fas fa-trash me-1"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer para ver productos - Sin cambios -->
                                @if ($categoria->productos_count > 0)
                                    <div class="card-footer bg-dark border-secondary p-0">
                                        <a href="{{ route('productos.index', ['categoria_id' => $categoria->id]) }}"
                                            class="btn btn-link btn-sm text-primary w-100 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-search me-1"></i> Ver productos
                                            ({{ $categoria->productos_count }})
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para nueva categoría -->
    <div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2 text-primary"></i> Nueva Categoría</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la categoría <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" class="form-control bg-dark text-white border-secondary"
                                    id="nombre" name="nombre" required placeholder="Ej: Electrónicos, Ropa, etc."
                                    maxlength="255">
                            </div>
                            <div class="form-text text-white">El nombre debe ser único.</div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar categoría -->
    <div class="modal fade" id="editarCategoriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title"><i class="fas fa-edit me-2 text-warning"></i> Editar Categoría</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="editarCategoriaForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editar_nombre" class="form-label">Nombre de la categoría <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" class="form-control bg-dark text-white border-secondary"
                                    id="editar_nombre" name="nombre" required maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar categoría -->
    <div class="modal fade" id="eliminarCategoriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-white"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                        Eliminar
                        Categoría</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="text-danger mb-4">
                        <i class="fas fa-trash-alt fa-4x"></i>
                    </div>
                    <h5 class="mb-3">¿Estás seguro de eliminar esta categoría?</h5>
                    <p>Estás a punto de eliminar la categoría: <strong id="eliminar_categoria_nombre"></strong></p>

                    <div id="sin-productos-alerta" class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Esta acción no se puede deshacer.
                    </div>

                    <div id="con-productos-alerta" class="alert alert-danger d-none">
                        <i class="fas fa-ban me-2"></i>
                        <strong>No se puede eliminar esta categoría</strong><br>
                        Esta categoría tiene productos asociados. Primero debes reasignar o eliminar estos productos.
                    </div>
                </div>
                <div class="modal-footer border-danger">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form id="eliminarCategoriaForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="btn-confirmar-eliminar" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-1"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <style>
            /* Mejora el contraste de los botones outline sobre fondo oscuro */
            .btn-outline-danger {
                border-width: 2px;
                font-weight: 500;
                color: #ff6b6b !important;
                border-color: #ff6b6b !important;
            }

            .btn-outline-danger:hover {
                color: #fff !important;
                background-color: #ff6b6b !important;
            }

            /* También mejoramos el botón de editar para mantener la consistencia */
            .btn-outline-warning {
                border-width: 2px;
                font-weight: 500;
                color: #ffb84d !important;
                border-color: #ffb84d !important;
            }

            .btn-outline-warning:hover {
                color: #212529 !important;
                background-color: #ffb84d !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Manejar el modal de edición
                document.querySelectorAll('.btn-editar-categoria').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const nombre = this.dataset.nombre;
                        document.getElementById('editar_nombre').value = nombre;
                        document.getElementById('editarCategoriaForm').action = `/categorias/${id}`;
                    });
                });

                // Manejar el modal de eliminación
                document.querySelectorAll('.btn-eliminar-categoria').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const id = this.dataset.id;
                        const nombre = this.dataset.nombre;
                        const productosCount = parseInt(this.dataset.productos || '0');

                        document.getElementById('eliminar_categoria_nombre').textContent = nombre;
                        document.getElementById('eliminarCategoriaForm').action = `/categorias/${id}`;

                        // Mostrar el mensaje adecuado según si tiene productos o no
                        const sinProductosAlerta = document.getElementById('sin-productos-alerta');
                        const conProductosAlerta = document.getElementById('con-productos-alerta');
                        const btnConfirmarEliminar = document.getElementById('btn-confirmar-eliminar');

                        if (productosCount > 0) {
                            sinProductosAlerta.classList.add('d-none');
                            conProductosAlerta.classList.remove('d-none');
                            btnConfirmarEliminar.disabled = true;
                            btnConfirmarEliminar.classList.add('opacity-50');
                        } else {
                            sinProductosAlerta.classList.remove('d-none');
                            conProductosAlerta.classList.add('d-none');
                            btnConfirmarEliminar.disabled = false;
                            btnConfirmarEliminar.classList.remove('opacity-50');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

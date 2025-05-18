<x-app-layout>
    <div class="container-fluid p-4 h-100 d-flex flex-column">
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-plus-circle"></i> Nuevo Producto
                </h5>

                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="codigo_producto" class="form-label">Código *</label>
                            <input type="text"
                                class="form-control bg-dark text-white @error('codigo_producto') is-invalid @enderror"
                                id="codigo_producto" name="codigo_producto" value="{{ old('codigo_producto') }}"
                                required>
                            @error('codigo_producto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text"
                                class="form-control bg-dark text-white @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control bg-dark text-white @error('descripcion') is-invalid @enderror" id="descripcion"
                            name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select bg-dark text-white @error('tipo') is-invalid @enderror"
                                id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="materia_prima" {{ old('tipo') == 'materia_prima' ? 'selected' : '' }}>
                                    Materia
                                    Prima</option>
                                <option value="producto_terminado"
                                    {{ old('tipo') == 'producto_terminado' ? 'selected' : '' }}>
                                    Producto Terminado</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <div class="input-group">
                                <select
                                    class="form-select bg-dark text-white @error('categoria_id') is-invalid @enderror"
                                    id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#nuevaCategoriaModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="stock_minimo_alerta" class="form-label">Stock Mínimo de Alerta *</label>
                            <input type="number"
                                class="form-control bg-dark text-white @error('stock_minimo_alerta') is-invalid @enderror"
                                id="stock_minimo_alerta" name="stock_minimo_alerta"
                                value="{{ old('stock_minimo_alerta') }}" required>
                            @error('stock_minimo_alerta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file"
                                class="form-control bg-dark text-white @error('imagen') is-invalid @enderror"
                                id="imagen" name="imagen" accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>

                <div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header border-bottom border-info">
                                <h5 class="modal-title">
                                    <i class="fas fa-folder-plus text-info me-2"></i>
                                    Nueva Categoría
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nombreCategoria" class="form-label">Nombre de la categoría *</label>
                                    <input type="text" class="form-control bg-dark text-white"
                                        id="nombreCategoria" name="nombre" required>
                                    <div class="invalid-feedback">
                                        El nombre de la categoría es obligatorio
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-top border-info">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-info" id="guardarCategoria">
                                    <i class="fas fa-save"></i> Guardar Categoría
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const guardarCategoriaBtn = document.getElementById('guardarCategoria');
                const categoriaSelect = document.getElementById('categoria_id');
                const modalElement = document.getElementById('nuevaCategoriaModal');

                // Verificamos que window.bootstrap y Modal estén disponibles
                const Modal = window.bootstrap?.Modal;
                if (!Modal) {
                    console.error(
                        'Bootstrap Modal no está disponible. Asegúrate de importar correctamente Bootstrap JS.');
                    return;
                }

                const modal = new Modal(modalElement);

                guardarCategoriaBtn.addEventListener('click', async function() {
                    const nombre = document.getElementById('nombreCategoria').value;

                    if (!nombre) {
                        alert('El nombre de la categoría es obligatorio');
                        return;
                    }

                    try {
                        const formData = new FormData();
                        formData.append('nombre', nombre);
                        formData.append('_token', '{{ csrf_token() }}');

                        const response = await fetch('{{ route('categorias.store') }}', {
                            method: 'POST',
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Error al crear la categoría');
                        }

                        const data = await response.json();

                        // Añadir la nueva categoría al select y seleccionarla
                        const option = new Option(data.nombre, data.id, true, true);
                        categoriaSelect.append(option);

                        // Limpiar campos
                        document.getElementById('nombreCategoria').value = '';

                        // Cerrar el modal correctamente
                        modal.hide();

                    } catch (error) {
                        console.error('Error detallado:', error);
                        alert('Error al crear la categoría: ' + error.message);
                    }
                });

                // Limpiar al cerrar modal
                modalElement.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('nombreCategoria').value = '';
                });
            });
        </script>
    @endpush

</x-app-layout>

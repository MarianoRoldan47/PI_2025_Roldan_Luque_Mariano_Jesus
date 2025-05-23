<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <!-- Header -->
        <div class="row g-2 mb-2 mb-sm-4">
            <div class="col-12 col-md">
                <h1 class="h3">EDITAR PRODUCTO</h1>
                <p>{{ $producto->nombre }}</p>
            </div>
            <div class="col-12 col-md text-md-end mt-2 mt-md-0">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>

        <!-- Formulario -->
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body p-2 p-sm-3">
                <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-2 g-sm-3">
                        <!-- Código y Nombre -->
                        <div class="col-12 col-md-6 mb-2">
                            <label for="codigo_producto" class="form-label small">Código *</label>
                            <input type="text"
                                class="form-control form-control-sm bg-dark text-white @error('codigo_producto') is-invalid @enderror"
                                id="codigo_producto" name="codigo_producto"
                                value="{{ old('codigo_producto', $producto->codigo_producto) }}" required>
                            @error('codigo_producto')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label for="nombre" class="form-label small">Nombre *</label>
                            <input type="text"
                                class="form-control form-control-sm bg-dark text-white @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-2">
                            <label for="descripcion" class="form-label small">Descripción</label>
                            <textarea class="form-control form-control-sm bg-dark text-white @error('descripcion') is-invalid @enderror"
                                id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tipo y Categoría -->
                        <div class="col-12 col-md-6 mb-2">
                            <label for="tipo" class="form-label small">Tipo *</label>
                            <select
                                class="form-select form-select-sm bg-dark text-white @error('tipo') is-invalid @enderror"
                                id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="materia_prima"
                                    {{ old('tipo', $producto->tipo) == 'materia_prima' ? 'selected' : '' }}>
                                    Materia Prima
                                </option>
                                <option value="producto_terminado"
                                    {{ old('tipo', $producto->tipo) == 'producto_terminado' ? 'selected' : '' }}>
                                    Producto Terminado
                                </option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label for="categoria_id" class="form-label small">Categoría *</label>
                            <div class="input-group input-group-sm">
                                <select
                                    class="form-select form-select-sm bg-dark text-white @error('categoria_id') is-invalid @enderror"
                                    id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#nuevaCategoriaModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            @error('categoria_id')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Mínimo e Imagen -->
                        <div class="col-12 col-md-6 mb-2">
                            <label for="stock_minimo_alerta" class="form-label small">Stock Mínimo de Alerta *</label>
                            <input type="number"
                                class="form-control form-control-sm bg-dark text-white @error('stock_minimo_alerta') is-invalid @enderror"
                                id="stock_minimo_alerta" name="stock_minimo_alerta"
                                value="{{ old('stock_minimo_alerta', $producto->stock_minimo_alerta) }}" required>
                            @error('stock_minimo_alerta')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6 mb-2">
                            <label for="imagen" class="form-label small">Imagen</label>
                            <div class="image-upload-container">
                                <!-- Previsualización de imagen -->
                                <div class="image-preview mb-2 d-flex justify-content-center align-items-center bg-dark-subtle rounded"
                                    style="height: 150px; border: 2px dashed #6c757d;">
                                    <img id="preview"
                                        src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                        class="img-fluid" style="max-height: 100%; object-fit: contain;"
                                        alt="Vista previa">
                                </div>

                                <!-- Input file personalizado -->
                                <div class="input-group input-group-sm">
                                    <input type="file"
                                        class="form-control form-control-sm bg-dark text-white @error('imagen') is-invalid @enderror"
                                        id="imagen" name="imagen" accept="image/*">
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="removeImage"
                                        style="display: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @error('imagen')
                                    <div class="invalid-feedback small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="mt-3 mt-sm-4">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i>Guardar Cambios
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm ms-1">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Previsualización de imagen
            document.getElementById('imagen').addEventListener('change', function(e) {
                const preview = document.getElementById('preview');
                const removeButton = document.getElementById('removeImage');

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        removeButton.style.display = 'block';
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Botón para eliminar imagen
            document.getElementById('removeImage').addEventListener('click', function() {
                const input = document.getElementById('imagen');
                const preview = document.getElementById('preview');

                input.value = '';
                preview.src =
                    '{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}';
                this.style.display = 'none';
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .image-upload-container {
                transition: all 0.3s ease;
            }

            .image-preview {
                transition: all 0.3s ease;
                background-color: rgba(255, 255, 255, 0.05);
            }

            .image-preview:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            .form-control[type="file"] {
                padding: 0.25rem 0.5rem;
            }

            .image-preview.dragover {
                border-color: #0dcaf0 !important;
                background-color: rgba(13, 202, 240, 0.1);
            }

            @media (max-width: 576px) {
                .image-preview {
                    height: 100px !important;
                }
            }
        </style>
    @endpush
</x-app-layout>

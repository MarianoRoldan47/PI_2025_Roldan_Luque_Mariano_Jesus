<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">

        <div class="mb-2 row g-2 mb-sm-4">
            <div class="col-12 col-md">
                <h1 class="h3">NUEVO PRODUCTO</h1>
                <p>Introduce los datos del nuevo producto</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>


        <div class="text-white shadow-sm card bg-dark">
            <div class="p-2 card-body p-sm-3">
                <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-2 g-sm-3">

                        <div class="mb-2 col-12 col-md-6">
                            <label for="codigo_producto" class="form-label small">Código *</label>
                            <input type="text"
                                class="form-control form-control-sm bg-dark text-white @error('codigo_producto') is-invalid @enderror"
                                id="codigo_producto" name="codigo_producto" value="{{ old('codigo_producto') }}"
                                required>
                            @error('codigo_producto')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2 col-12 col-md-6">
                            <label for="nombre" class="form-label small">Nombre *</label>
                            <input type="text"
                                class="form-control form-control-sm bg-dark text-white @error('nombre') is-invalid @enderror"
                                id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-2 col-12">
                            <label for="descripcion" class="form-label small">Descripción</label>
                            <textarea class="form-control form-control-sm bg-dark text-white @error('descripcion') is-invalid @enderror"
                                id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-2 col-12 col-md-6">
                            <label for="tipo" class="form-label small">Tipo *</label>
                            <select
                                class="form-select form-select-sm bg-dark text-white @error('tipo') is-invalid @enderror"
                                id="tipo" name="tipo" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="materia_prima" {{ old('tipo') == 'materia_prima' ? 'selected' : '' }}>
                                    Materia Prima
                                </option>
                                <option value="producto_terminado"
                                    {{ old('tipo') == 'producto_terminado' ? 'selected' : '' }}>
                                    Producto Terminado
                                </option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2 col-12 col-md-6">
                            <label for="categoria_id" class="form-label small">Categoría *</label>
                            <div class="input-group input-group-sm">
                                <select
                                    class="form-select form-select-sm bg-dark text-white @error('categoria_id') is-invalid @enderror"
                                    id="categoria_id" name="categoria_id" required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
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


                        <div class="mb-2 col-12 col-md-6">
                            <label for="stock_minimo_alerta" class="form-label small">Stock Mínimo de Alerta *</label>
                            <input type="number"
                                class="form-control form-control-sm bg-dark text-white @error('stock_minimo_alerta') is-invalid @enderror"
                                id="stock_minimo_alerta" name="stock_minimo_alerta"
                                value="{{ old('stock_minimo_alerta') }}" required>
                            @error('stock_minimo_alerta')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2 col-12 col-md-6">
                            <label for="imagen" class="form-label small">Imagen</label>
                            <div class="image-upload-container">

                                <div class="mb-2 rounded image-preview d-flex justify-content-center align-items-center bg-dark-subtle"
                                    style="height: 150px; border: 2px dashed #6c757d;">
                                    <img id="preview" src="{{ asset('img/default-product.png') }}" class="img-fluid"
                                        style="max-height: 100%; object-fit: contain;" alt="Vista previa">
                                </div>


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


                    <div class="mt-3 mt-sm-4">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i>Guardar
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm ms-1">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>


        <div class="modal fade" id="nuevaCategoriaModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="text-white modal-content bg-dark">
                    <div class="p-2 modal-header border-bottom border-info p-sm-3">
                        <h5 class="modal-title fs-6">
                            <i class="fas fa-folder-plus text-info me-2"></i>Nueva Categoría
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="p-2 modal-body p-sm-3">
                        <div class="mb-2">
                            <label for="nombreCategoria" class="form-label small">Nombre de la categoría *</label>
                            <input type="text" class="text-white form-control form-control-sm bg-dark"
                                id="nombreCategoria" name="nombre" required>
                            <div class="invalid-feedback small">
                                El nombre de la categoría es obligatorio
                            </div>
                        </div>
                    </div>
                    <div class="p-2 modal-footer border-top border-info p-sm-3">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-info btn-sm" id="guardarCategoria">
                            <i class="fas fa-save me-1"></i>Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>

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


            document.getElementById('removeImage').addEventListener('click', function() {
                const input = document.getElementById('imagen');
                const preview = document.getElementById('preview');

                input.value = '';
                preview.src = '{{ asset('img/default-product.png') }}';
                this.style.display = 'none';
            });

            document.addEventListener('DOMContentLoaded', function() {
                const guardarCategoriaBtn = document.getElementById('guardarCategoria');
                const categoriaSelect = document.getElementById('categoria_id');
                const modalElement = document.getElementById('nuevaCategoriaModal');
                const modal = new bootstrap.Modal(modalElement);

                guardarCategoriaBtn.addEventListener('click', async function() {
                    try {
                        const nombre = document.getElementById('nombreCategoria').value.trim();

                        if (!nombre) {
                            document.getElementById('nombreCategoria').classList.add('is-invalid');
                            return;
                        }

                        document.getElementById('nombreCategoria').classList.remove('is-invalid');

                        const response = await fetch('{{ route('categorias.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                nombre: nombre
                            })
                        });

                        const data = await response.json();

                        if (data.success) {

                            const option = new Option(data.categoria.nombre, data.categoria.id, true, true);
                            categoriaSelect.appendChild(option);


                            document.getElementById('nombreCategoria').value = '';
                            modal.hide();


                            const successModal = document.createElement('div');
                            successModal.className = 'modal';
                            successModal.id = 'successModal';
                            successModal.setAttribute('tabindex', '-1');
                            successModal.setAttribute('role', 'dialog');
                            successModal.setAttribute('aria-labelledby', 'successModalTitle');
                            successModal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="text-white modal-content bg-dark">
                <div class="border-white modal-header border-bottom">
                    <h5 class="text-white modal-title" id="successModalTitle">
                        <i class="fas fa-check-circle text-success me-2"></i>¡Éxito!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center">
                        <div class="p-3 text-white modal-icon bg-success rounded-circle me-3">
                            <i class="fas fa-check fa-2x"></i>
                        </div>
                        <p class="mb-0">${data.message}</p>
                    </div>
                </div>
                <div class="border-white modal-footer border-top">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                        tabindex="0">Aceptar</button>
                </div>
            </div>
        </div>
        `;

                            document.body.appendChild(successModal);

                            const successModalInstance = new bootstrap.Modal(successModal, {
                                keyboard: true,
                                focus: true
                            });


                            successModal.addEventListener('shown.bs.modal', function() {
                                successModal.querySelector('.btn-success').focus();
                            });

                            successModalInstance.show();


                            successModal.addEventListener('hidden.bs.modal', function() {
                                document.body.removeChild(successModal);

                                categoriaSelect.focus();
                            });
                        } else {
                            throw new Error(data.message || 'Error al crear la categoría');
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        document.getElementById('nombreCategoria').classList.add('is-invalid');
                    }
                });


                document.getElementById('nombreCategoria').addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });


                modalElement.addEventListener('hidden.bs.modal', function() {
                    document.getElementById('nombreCategoria').value = '';
                    document.getElementById('nombreCategoria').classList.remove('is-invalid');
                });
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
        </style>
    @endpush
</x-app-layout>

<x-app-layout>
    <div class="container-fluid p-4 h-100 d-flex flex-column">
        <div class="card bg-dark text-white shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="fas fa-edit"></i> Editar Producto
                </h5>

                <form action="{{ route('productos.update', $producto) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="codigo_producto" class="form-label">Código *</label>
                            <input type="text"
                                   class="form-control bg-dark text-white @error('codigo_producto') is-invalid @enderror"
                                   id="codigo_producto"
                                   name="codigo_producto"
                                   value="{{ old('codigo_producto', $producto->codigo_producto) }}"
                                   required>
                            @error('codigo_producto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text"
                                   class="form-control bg-dark text-white @error('nombre') is-invalid @enderror"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre', $producto->nombre) }}"
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control bg-dark text-white @error('descripcion') is-invalid @enderror"
                                  id="descripcion"
                                  name="descripcion"
                                  rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select class="form-select bg-dark text-white @error('tipo') is-invalid @enderror"
                                    id="tipo"
                                    name="tipo"
                                    required>
                                <option value="">Selecciona un tipo</option>
                                <option value="materia_prima" {{ old('tipo', $producto->tipo) == 'materia_prima' ? 'selected' : '' }}>Materia Prima</option>
                                <option value="producto_terminado" {{ old('tipo', $producto->tipo) == 'producto_terminado' ? 'selected' : '' }}>Producto Terminado</option>
                            </select>
                            @error('tipo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select bg-dark text-white @error('categoria_id') is-invalid @enderror"
                                    id="categoria_id"
                                    name="categoria_id"
                                    required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                            {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="stock_minimo_alerta" class="form-label">Stock Mínimo de Alerta *</label>
                            <input type="number"
                                   class="form-control bg-dark text-white @error('stock_minimo_alerta') is-invalid @enderror"
                                   id="stock_minimo_alerta"
                                   name="stock_minimo_alerta"
                                   value="{{ old('stock_minimo_alerta', $producto->stock_minimo_alerta) }}"
                                   required>
                            @error('stock_minimo_alerta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file"
                                   class="form-control bg-dark text-white @error('imagen') is-invalid @enderror"
                                   id="imagen"
                                   name="imagen"
                                   accept="image/*">
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

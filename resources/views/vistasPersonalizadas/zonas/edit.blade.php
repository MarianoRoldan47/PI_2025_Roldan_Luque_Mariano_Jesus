<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">EDITAR ZONA</h1>
                <p class="text-muted">Modificar datos de zona de almacenamiento</p>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="{{ route('almacen.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto">
                <div class="card bg-dark shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('zonas.update', $zona) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nombre" class="form-label text-white">Nombre de la zona <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark border-secondary">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                    <input type="text" name="nombre" id="nombre"
                                           class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                                           value="{{ old('nombre', $zona->nombre) }}" required>
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="descripcion" class="form-label text-white">Descripción</label>
                                <textarea name="descripcion" id="descripcion" rows="3"
                                          class="form-control bg-dark text-white border-secondary @error('descripcion') is-invalid @enderror">{{ old('descripcion', $zona->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('almacen.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

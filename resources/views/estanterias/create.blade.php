<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">NUEVA ESTANTERÍA</h1>
                <p class="text-muted">Crear una nueva estantería en el almacén</p>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="{{ route('almacen.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Información de la Estantería
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('estanterias.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nombre" class="form-label text-white">Nombre <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-warehouse text-primary"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                                        id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text text-white small">
                                    Introduce un nombre descriptivo para la estantería.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="zona_id" class="form-label text-white">Zona <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-map-marker-alt text-info"></i>
                                    </span>
                                    <select
                                        class="form-select bg-dark text-white border-secondary @error('zona_id') is-invalid @enderror"
                                        id="zona_id" name="zona_id" required>
                                        <option value="" disabled
                                            {{ old('zona_id') === null && !request()->has('zona_id') ? 'selected' : '' }}>
                                            Selecciona una zona...</option>
                                        @foreach ($zonas as $zona)
                                            <option value="{{ $zona->id }}"
                                                {{ old('zona_id') == $zona->id || (request()->zona_id == $zona->id && old('zona_id') === null) ? 'selected' : '' }}>
                                                {{ $zona->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('zona_id')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="capacidad_maxima" class="form-label text-white">Capacidad Máxima <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-cubes text-warning"></i>
                                    </span>
                                    <input type="number"
                                        class="form-control bg-dark text-white border-secondary @error('capacidad_maxima') is-invalid @enderror"
                                        id="capacidad_maxima" name="capacidad_maxima"
                                        value="{{ old('capacidad_maxima') }}" min="1" required>
                                </div>
                                @error('capacidad_maxima')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text text-white small">
                                    Número máximo de productos que puede contener la estantería.
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Crear Estantería
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

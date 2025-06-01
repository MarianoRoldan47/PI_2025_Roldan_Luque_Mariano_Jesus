<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">EDITAR ESTANTERÍA</h1>
                <p class="text-muted">{{ $estanteria->nombre }} - {{ $estanteria->zona->nombre }}</p>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="{{ route('estanterias.show', $estanteria) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-edit text-warning me-2"></i>
                            Información de la Estantería
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('estanterias.update', $estanteria) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nombre" class="form-label text-white">Nombre *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-warehouse text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary @error('nombre') is-invalid @enderror"
                                           id="nombre" name="nombre" value="{{ old('nombre', $estanteria->nombre) }}" required>
                                </div>
                                @error('nombre')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="zona_id" class="form-label text-white">Zona *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-map-marker-alt text-info"></i>
                                    </span>
                                    <select class="form-select bg-dark text-white border-secondary @error('zona_id') is-invalid @enderror"
                                            id="zona_id" name="zona_id" required>
                                        @foreach ($zonas as $zona)
                                            <option value="{{ $zona->id }}" {{ (old('zona_id', $estanteria->zona_id) == $zona->id) ? 'selected' : '' }}>
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
                                <label for="capacidad_maxima" class="form-label text-white">Capacidad Máxima *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-dark text-white border-secondary">
                                        <i class="fas fa-cubes text-warning"></i>
                                    </span>
                                    <input type="number" class="form-control bg-dark text-white border-secondary @error('capacidad_maxima') is-invalid @enderror"
                                           id="capacidad_maxima" name="capacidad_maxima"
                                           value="{{ old('capacidad_maxima', $estanteria->capacidad_maxima) }}"
                                           min="{{ $estanteria->capacidad_maxima - $estanteria->capacidad_libre }}" required>
                                </div>
                                @error('capacidad_maxima')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text text-white small">
                                    El valor mínimo es {{ $estanteria->capacidad_maxima - $estanteria->capacidad_libre }} (productos actualmente almacenados).
                                </div>
                            </div>

                            <!-- Información de ocupación actual -->
                            <div class="alert alert-dark border border-secondary mb-4">
                                <h6 class="alert-heading d-flex align-items-center">
                                    <i class="fas fa-info-circle text-info me-2"></i>
                                    Estado Actual de Ocupación
                                </h6>
                                <div class="mb-2 small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Capacidad máxima: <strong>{{ $estanteria->capacidad_maxima }}</strong></span>
                                        <span>Capacidad libre: <strong>{{ $estanteria->capacidad_libre }}</strong></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Productos almacenados: <strong>{{ $estanteria->capacidad_maxima - $estanteria->capacidad_libre }}</strong></span>
                                        <span>Ocupación: <strong>{{ round((($estanteria->capacidad_maxima - $estanteria->capacidad_libre) / $estanteria->capacidad_maxima) * 100) }}%</strong></span>
                                    </div>
                                </div>
                                <div class="progress bg-dark-subtle" style="height: 8px;">
                                    @php
                                        $porcentajeOcupado = ($estanteria->capacidad_maxima - $estanteria->capacidad_libre) / $estanteria->capacidad_maxima * 100;
                                    @endphp
                                    <div class="progress-bar {{ $porcentajeOcupado >= 90 ? 'bg-danger' : ($porcentajeOcupado >= 70 ? 'bg-warning' : 'bg-success') }}"
                                         style="width: {{ $porcentajeOcupado }}%"></div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i>Actualizar Estantería
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">ZONA: {{ $zona->nombre }}</h1>
                <p class="text-muted">Detalles y gestión de estanterías</p>
            </div>
            <div class="col-auto d-flex align-items-center gap-2">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('zonas.edit', $zona) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                @endif
                <a href="{{ route('almacen.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-{{ session('status-type', 'info') }} alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card bg-dark shadow-sm h-100">
                    <div class="card-header bg-dark-subtle d-flex align-items-center">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        <span class="fw-bold">Información de la Zona</span>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-4">
                            <div class="zone-icon rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px; background-color: #2a3444;">
                                <i class="fas fa-map-marker-alt fa-2x text-info"></i>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush bg-transparent">
                            <li
                                class="list-group-item bg-transparent border-secondary py-3 d-flex justify-content-between">
                                <span class="text-white">ID:</span>
                                <span class="fw-bold text-white">{{ $zona->id }}</span>
                            </li>
                            <li
                                class="list-group-item bg-transparent border-secondary py-3 d-flex justify-content-between">
                                <span class="text-white">Nombre:</span>
                                <span class="fw-bold text-white">{{ $zona->nombre }}</span>
                            </li>
                            <li
                                class="list-group-item bg-transparent border-secondary py-3 d-flex justify-content-between">
                                <span class="text-white">Fecha de creación:</span>
                                <span class="text-white">{{ $zona->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li
                                class="list-group-item bg-transparent border-secondary py-3 d-flex justify-content-between">
                                <span class="text-white">Estanterías:</span>
                                <span class="badge bg-secondary">{{ $zona->estanterias->count() }}</span>
                            </li>
                        </ul>

                        @if ($zona->descripcion)
                            <div class="mt-3">
                                <h6 class="text-white mb-2">Descripción:</h6>
                                <p class="mb-0 text-white">{{ $zona->descripcion }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark-subtle d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-th-large me-2 text-primary"></i>
                            <span class="fw-bold">Estanterías en esta Zona</span>
                        </div>
                        @if (Auth::user()->rol === 'Administrador')
                            <a href="{{ route('estanterias.create', ['zona_id' => $zona->id]) }}"
                                class="btn btn-success btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Nueva Estantería
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        @if ($zona->estanterias->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-th-large fa-3x mb-3 text-secondary"></i>
                                <p class="mb-0 text-white">No hay estanterías en esta zona</p>
                                @if (Auth::user()->rol === 'Administrador')
                                    <a href="{{ route('estanterias.create', ['zona_id' => $zona->id]) }}"
                                        class="btn btn-primary btn-sm mt-3">
                                        <i class="fas fa-plus-circle me-1"></i> Crear primera estantería
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th class="text-center">Ocupación</th>
                                            <th class="text-center">Capacidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zona->estanterias as $estanteria)
                                            <tr class="estanteria-row"
                                                data-href="{{ route('estanterias.show', $estanteria) }}"
                                                style="cursor: pointer;">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="shelf-icon rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px; background-color: #2a3444;">
                                                            <i class="fas fa-th text-success"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $estanteria->nombre }}</h6>
                                                            <small class="text-white">ID: {{ $estanteria->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $porcentajeOcupacion =
                                                            $estanteria->capacidad_maxima > 0
                                                                ? (($estanteria->capacidad_maxima -
                                                                        $estanteria->capacidad_libre) /
                                                                        $estanteria->capacidad_maxima) *
                                                                    100
                                                                : 0;

                                                        if ($porcentajeOcupacion < 50) {
                                                            $colorClase = 'bg-success';
                                                        } elseif ($porcentajeOcupacion < 80) {
                                                            $colorClase = 'bg-warning';
                                                        } else {
                                                            $colorClase = 'bg-danger';
                                                        }
                                                    @endphp

                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar {{ $colorClase }}"
                                                            role="progressbar"
                                                            style="width: {{ $porcentajeOcupacion }}%;"
                                                            aria-valuenow="{{ $porcentajeOcupacion }}"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="text-white mt-1 d-block">
                                                        {{ round($porcentajeOcupacion) }}% ocupado
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="d-block">{{ $estanteria->capacidad_maxima - $estanteria->capacidad_libre }}
                                                        / {{ $estanteria->capacidad_maxima }}</span>
                                                    <small class="text-white">{{ $estanteria->capacidad_libre }}
                                                        disponibles</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar Zona -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-danger text-white">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white">¿Estás seguro de que deseas eliminar la zona <strong
                            class="text-white">{{ $zona->nombre }}</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Esta acción eliminará todas las estanterías asociadas a esta zona.
                    </div>
                </div>
                <div class="modal-footer border-danger">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </button>
                    <form action="{{ route('zonas.destroy', $zona) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Eliminar zona
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hacer que las filas de estanterías sean clickeables
                const estanteriaRows = document.querySelectorAll('.estanteria-row');
                estanteriaRows.forEach(row => {
                    row.addEventListener('click', function() {
                        window.location.href = this.getAttribute('data-href');
                    });

                    // Efecto visual al pasar el mouse
                    row.addEventListener('mouseenter', function() {
                        this.classList.add('table-active');
                    });

                    row.addEventListener('mouseleave', function() {
                        this.classList.remove('table-active');
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>

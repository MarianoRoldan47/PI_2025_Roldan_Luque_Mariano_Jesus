<x-app-layout>
    @push('styles')
        <style>
            .zona-header {
                transition: background-color 0.2s ease, border-color 0.2s ease;
            }

            .zona-header:hover,
            .zona-header:focus {
                background-color: #212529 !important;
                outline: none;
            }

            .estanteria-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            }

            .estanteria-card:hover,
            .estanteria-card:focus {
                transform: translateY(-3px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
                outline: none;
            }

            .estanteria-card.border-success:hover {
                border-color: #198754 !important;
            }

            .estanteria-card.border-warning:hover {
                border-color: #fd7e14 !important;
            }

            .estanteria-card.border-danger:hover {
                border-color: #dc3545 !important;
            }

            .estanteria-card.border-secondary:hover {
                border-color: #adb5bd !important;
            }

            @media (max-width: 767.98px) {
                .zona-header-content {
                    flex-direction: column;
                    align-items: flex-start !important;
                }

                .zona-badge-container {
                    margin-top: 0.75rem;
                    align-self: flex-start;
                }

                .zona-descripcion {
                    padding-left: 0 !important;
                    margin-top: 0.5rem !important;
                }

                .zona-icon-title {
                    width: 100%;
                }
            }
        </style>
    @endpush
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">ALMACÉN</h1>
                <p class="text-muted">Gestión de zonas y estanterías</p>
            </div>
            @if (Auth::user()->rol === 'Administrador')
                <div class="col-12 col-md-auto ms-md-auto">
                    <div class="gap-2 d-flex flex-column">
                        <a href="{{ route('zonas.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Zona
                        </a>
                        <a href="{{ route('estanterias.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Estantería
                        </a>
                    </div>
                </div>
            @endif
        </div>

        @if ($estanterias->isEmpty())
            <div class="shadow-sm card bg-dark">
                <div class="py-5 text-center card-body">
                    <i class="mb-3 fas fa-warehouse fa-3x text-secondary"></i>
                    <p class="mb-3 text-white">No hay zonas ni estanterías registradas en el sistema.</p>
                    @if (Auth::user()->rol === 'Administrador')
                        <div class="gap-2 d-flex justify-content-center">
                            <a href="{{ route('zonas.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Crear primera zona
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            @php
                $estanteriasPorZona = $estanterias->groupBy('zona_id');
                $zonas = App\Models\Zona::orderBy('nombre')->get();
            @endphp

            @foreach ($zonas as $zona)
                <div class="mb-4 shadow-sm card bg-dark">
                    <a href="{{ route('zonas.show', $zona) }}"
                        class="py-3 card-header bg-dark border-secondary text-decoration-none zona-header">
                        <div class="d-flex align-items-center justify-content-between zona-header-content">
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex align-items-center zona-icon-title">
                                    <div class="zone-icon rounded-circle me-3 d-flex align-items-center justify-content-center"
                                        style="min-width: 40px; height: 40px; background-color: #2a3444;">
                                        <i class="fas fa-map-marker-alt text-info"></i>
                                    </div>
                                    <h5 class="mb-0 text-white card-title text-truncate">{{ $zona->nombre }}</h5>
                                </div>

                                @if ($zona->descripcion)
                                    <p class="mt-2 mb-0 text-white small ps-5 zona-descripcion">
                                        {{ Str::limit($zona->descripcion, 120) }}
                                    </p>
                                @endif
                            </div>

                            <div class="gap-3 d-flex align-items-center zona-badge-container">
                                <span class="badge bg-secondary">
                                    {{ $zona->estanterias->count() }}
                                    <span class="d-inline">estanterías</span>
                                </span>
                            </div>
                        </div>
                    </a>

                    <div class="p-4 card-body">
                        @if (!isset($estanteriasPorZona[$zona->id]) || $estanteriasPorZona[$zona->id]->isEmpty())
                            <div class="py-4 text-center">
                                <p class="mb-3 text-white">Esta zona no tiene estanterías.</p>
                                @if (Auth::user()->rol === 'Administrador')
                                    <a href="{{ route('estanterias.create') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i> Añadir estantería
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                                @foreach ($estanteriasPorZona[$zona->id] as $estanteria)
                                    @php
                                        $porcentajeOcupado =
                                            (($estanteria->capacidad_maxima - $estanteria->capacidad_libre) /
                                                $estanteria->capacidad_maxima) *
                                            100;

                                        if ($estanteria->capacidad_libre == 0) {
                                            $estadoTexto = 'Lleno';
                                            $estadoClase = 'text-bg-danger';
                                            $borderClass = 'border-danger';
                                        } elseif ($porcentajeOcupado >= 90) {
                                            $estadoTexto = 'Casi lleno';
                                            $estadoClase = 'text-bg-danger';
                                            $borderClass = 'border-danger';
                                        } elseif ($porcentajeOcupado >= 70) {
                                            $estadoTexto = 'Ocupación alta';
                                            $estadoClase = 'text-bg-warning';
                                            $borderClass = 'border-warning';
                                        } elseif ($porcentajeOcupado > 0) {
                                            $estadoTexto = 'Disponible';
                                            $estadoClase = 'text-bg-success';
                                            $borderClass = 'border-success';
                                        } else {
                                            $estadoTexto = 'Vacío';
                                            $estadoClase = 'text-bg-secondary';
                                            $borderClass = 'border-secondary';
                                        }
                                    @endphp

                                    <div class="col">
                                        <!-- CAMBIO: Usando enlace en lugar de JavaScript para estanterías -->
                                        <a href="{{ route('estanterias.show', $estanteria) }}"
                                            class="card bg-dark-subtle h-100 estanteria-card text-decoration-none {{ $borderClass }}"
                                            style="border-width: 2px;">
                                            <div class="card-body">
                                                <div class="mb-3 d-flex align-items-center">
                                                    <div class="shelf-icon rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px; background-color: #e9ecef;">
                                                        <i class="fas fa-warehouse text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-0 card-title text-dark">{{ $estanteria->nombre }}
                                                        </h5>
                                                    </div>
                                                    <span
                                                        class="badge {{ $estadoClase }} ms-auto">{{ $estadoTexto }}</span>
                                                </div>

                                                <div class="mb-2">
                                                    <div class="mb-1 d-flex justify-content-between align-items-center">
                                                        <div class="text-dark">
                                                            <span class="text-primary">Disponible:</span>
                                                            <strong
                                                                class="text-success fw-bold">{{ $estanteria->capacidad_libre }}</strong>
                                                        </div>
                                                        <div class="text-dark">
                                                            <span class="text-primary">Total:</span>
                                                            <strong
                                                                class="text-dark">{{ $estanteria->capacidad_maxima }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="bg-opacity-25 progress bg-secondary"
                                                        style="height: 8px;">
                                                        <div class="progress-bar {{ $estanteria->capacidad_libre / $estanteria->capacidad_maxima < 0.2 ? 'bg-danger' : 'bg-success' }}"
                                                            style="width: {{ $porcentajeOcupado }}%">
                                                        </div>
                                                    </div>
                                                    <div class="mt-1 text-end">
                                                        <small
                                                            class="text-secondary">{{ number_format($porcentajeOcupado, 0) }}%
                                                            ocupado</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>

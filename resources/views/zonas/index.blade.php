<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">ZONAS DEL ALMACÉN</h1>
                <p class="text-muted">Gestión de zonas de almacenamiento</p>
            </div>
            <div class="col-12 col-md-auto d-flex justify-content-end align-items-center">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('zonas.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Nueva Zona
                    </a>
                @endif
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-{{ session('status-type', 'info') }} alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-12">
                <div class="shadow-sm card bg-dark">
                    <div class="p-0 card-body">
                        @if ($zonas->isEmpty())
                            <div class="py-5 text-center">
                                <i class="mb-3 fas fa-warehouse fa-3x text-secondary"></i>
                                <p class="mb-0 text-muted">No hay zonas registradas</p>
                                @if (Auth::user()->rol === 'Administrador')
                                    <a href="{{ route('zonas.create') }}" class="mt-3 btn btn-primary btn-sm">
                                        <i class="fas fa-plus-circle me-1"></i> Crear primera zona
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th class="text-center">Estanterías</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($zonas as $zona)
                                            <tr class="zona-row" data-href="{{ route('zonas.show', $zona) }}"
                                                style="cursor: pointer;">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="zone-icon rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px; background-color: #2a3444;">
                                                            <i class="fas fa-map-marker-alt text-info"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $zona->nombre }}</h6>
                                                            <small class="text-muted">ID: {{ $zona->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ Str::limit($zona->descripcion ?? 'Sin descripción', 50) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">{{ $zona->estanterias_count }}
                                                        estanterías</span>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const zonaRows = document.querySelectorAll('.zona-row');
                zonaRows.forEach(row => {
                    row.addEventListener('click', function() {
                        window.location.href = this.getAttribute('data-href');
                    });


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

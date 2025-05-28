<div>
    <!-- Filtros y búsqueda con mejor diseño -->
    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-dark text-white border-secondary">
                    <i class="fas fa-search"></i>
                </span>
                <input wire:model.live="search" type="text" class="form-control bg-dark text-white border-secondary"
                    placeholder="Buscar por nombre de producto...">
                @if ($search)
                    <button wire:click="$set('search', '')" class="btn btn-dark border-secondary" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-dark border-secondary dropdown-toggle d-flex align-items-center gap-2"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter"></i>
                    Filtros
                    @if (count($tiposFiltrados) > 0)
                        <span class="badge bg-primary">{{ count($tiposFiltrados) }}</span>
                    @endif
                </button>
                <div class="dropdown-menu dropdown-menu-dark p-3" style="min-width: 250px">
                    <h6 class="dropdown-header border-bottom pb-2 mb-2">Tipo de Movimiento</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados" value="entrada"
                            id="checkEntrada">
                        <label class="form-check-label d-flex align-items-center gap-2" for="checkEntrada">
                            <span class="badge bg-success">
                                <i class="fas fa-arrow-right me-1"></i> Entrada
                            </span>
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados" value="salida"
                            id="checkSalida">
                        <label class="form-check-label d-flex align-items-center gap-2" for="checkSalida">
                            <span class="badge bg-danger">
                                <i class="fas fa-arrow-left me-1"></i> Salida
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados"
                            value="traslado" id="checkTraslado">
                        <label class="form-check-label d-flex align-items-center gap-2" for="checkTraslado">
                            <span class="badge bg-secondary">
                                <i class="fas fa-exchange-alt me-1"></i> Traslado
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="badge bg-dark text-white border border-secondary py-2 px-3">
                <i class="fas fa-list me-1"></i> {{ $movimientos->total() }} movimientos
            </div>
        </div>
    </div>

    <!-- Tabla con diseño mejorado -->
    <div class="card bg-dark shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr class="bg-dark-subtle text-white border-bottom border-secondary">
                        <th wire:click="sortBy('fecha_movimiento')" class="sortable-header">
                            <div class="d-flex align-items-center">
                                <span>Fecha</span>
                                <div class="sort-icon ms-2">
                                    @if ($sortField === 'fecha_movimiento')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-secondary"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th>Tipo</th>
                        <th wire:click="sortBy('usuario')" class="sortable-header">
                            <div class="d-flex align-items-center">
                                <span>Usuario</span>
                                <div class="sort-icon ms-2">
                                    @if ($sortField === 'usuario')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-secondary"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th wire:click="sortBy('producto')" class="sortable-header">
                            <div class="d-flex align-items-center">
                                <span>Producto</span>
                                <div class="sort-icon ms-2">
                                    @if ($sortField === 'producto')
                                        <i
                                            class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-primary"></i>
                                    @else
                                        <i class="fas fa-sort text-secondary"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <th>Cantidad</th>
                        <th>Origen</th>
                        <th>Destino</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($movimientos->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-white">
                                    <i class="fas fa-search fa-3x mb-3 text-secondary d-block"></i>
                                    <p class="mb-0 text-secondary">No se encontraron movimientos que coincidan con la
                                        búsqueda</p>
                                    @if ($search || count($tiposFiltrados) > 0)
                                        <button wire:click="limpiarFiltros"
                                            class="btn btn-outline-secondary btn-sm mt-3">
                                            <i class="fas fa-times me-1"></i> Limpiar filtros
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach ($movimientos as $movimiento)
                            <tr class="movimiento-row position-relative"
                                data-href="{{ route('movimientos.show', $movimiento) }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="date-icon rounded-circle bg-dark-subtle bg-opacity-50 d-flex align-items-center justify-content-center me-2 border border-info"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-calendar text-black"></i>
                                        </div>
                                        <div>
                                            <div class="text-white">
                                                {{ $movimiento->fecha_movimiento->format('d/m/Y') }}</div>
                                            <small
                                                class="text-secondary">{{ $movimiento->fecha_movimiento->format('H:i:s') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }} py-2 px-3 d-inline-flex align-items-center">
                                        <i
                                            class="fas fa-{{ $movimiento->tipo === 'entrada' ? 'arrow-right' : ($movimiento->tipo === 'salida' ? 'arrow-left' : 'exchange-alt') }} me-2"></i>
                                        {{ ucfirst($movimiento->tipo) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-user text-black"></i>
                                        </div>
                                        <span>{{ $movimiento->usuario?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="product-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-box text-black"></i>
                                        </div>
                                        <div>
                                            <span>{{ $movimiento->producto->nombre }}</span>
                                            @if ($movimiento->producto->trashed())
                                                <i class="fas fa-trash-alt text-danger ms-2"
                                                    title="Producto eliminado"></i>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-white px-3 py-2 fw-bold">
                                        {{ $movimiento->cantidad }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="location-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-map-marker-alt text-black"></i>
                                        </div>
                                        <div class="text-truncate" style="max-width: 200px;"
                                            title="{{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre . ' - ' . $movimiento->origen->zona->nombre : $movimiento->origen_tipo }}">
                                            {{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre . ' - ' . $movimiento->origen->zona->nombre : $movimiento->origen_tipo }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="location-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-map-marker-alt text-black"></i>
                                        </div>
                                        <div class="text-truncate" style="max-width: 200px;"
                                            title="{{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre . ' - ' . $movimiento->destino->zona->nombre : $movimiento->destino_tipo }}">
                                            {{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre . ' - ' . $movimiento->destino->zona->nombre : $movimiento->destino_tipo }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $movimientos->links() }}
    </div>

    <!-- Estilos y scripts -->
    <style>
        .pagination-results {
            color: white !important;
        }

        /* Estas reglas aseguran que todos los textos de la paginación estén en blanco */
        div.pagination-results p,
        div.pagination-results span,
        nav[aria-label="Pagination Navigation"] div>p,
        nav[aria-label="Pagination Navigation"] div>span,
        nav[aria-label="Pagination Navigation"] p.text-sm.text-gray-700,
        nav[aria-label="Pagination Navigation"] p.leading-5,
        .leading-5 {
            color: white !important;
        }

        .sortable-header {
            cursor: pointer;
        }

        .sortable-header:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }

        .movimiento-row {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .movimiento-row:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            transform: translateY(-1px);
        }

        .date-icon,
        .user-icon,
        .product-icon,
        .location-icon {
            transition: all 0.2s ease;
        }

        .movimiento-row:hover .date-icon,
        .movimiento-row:hover .user-icon,
        .movimiento-row:hover .product-icon,
        .movimiento-row:hover .location-icon {
            transform: scale(1.1);
        }

        .table-responsive {
            scrollbar-width: thin;
            scrollbar-color: #6c757d #212529;
        }

        .table-responsive::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #212529;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background-color: #6c757d;
            border-radius: 20px;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Hacer que las filas sean clickeables
                const movimientoRows = document.querySelectorAll('.movimiento-row');
                movimientoRows.forEach(row => {
                    row.addEventListener('click', function() {
                        window.location.href = this.getAttribute('data-href');
                    });
                });

                // Cambiar el color del texto de paginación a blanco
                function setPaginationTextWhite() {
                    const paginationTexts = document.querySelectorAll(
                        'nav[aria-label="Pagination Navigation"] div p, nav[aria-label="Pagination Navigation"] p, .leading-5'
                        );
                    paginationTexts.forEach(text => {
                        text.style.color = 'white';
                    });
                }

                // Ejecutar inmediatamente
                setPaginationTextWhite();

                // Y también después de cualquier actualización de Livewire
                document.addEventListener('livewire:load', function() {
                    Livewire.hook('message.processed', () => {
                        setPaginationTextWhite();
                    });
                });
            });
        </script>
    @endpush
</div>

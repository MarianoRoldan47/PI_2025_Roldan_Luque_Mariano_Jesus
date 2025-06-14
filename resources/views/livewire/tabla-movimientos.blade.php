<div>

    <div class="mb-4 row g-3">
        <div class="col-md-4">
            <div class="shadow-sm input-group">
                <span class="text-white input-group-text bg-dark border-secondary">
                    <i class="fas fa-search"></i>
                </span>
                <input wire:model.live="search" type="text" class="text-white form-control bg-dark border-secondary"
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
                <button class="gap-2 btn btn-dark border-secondary dropdown-toggle d-flex align-items-center"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-filter"></i>
                    Filtros
                    @if (count($tiposFiltrados) > 0)
                        <span class="badge bg-primary">{{ count($tiposFiltrados) }}</span>
                    @endif
                </button>
                <div class="p-3 dropdown-menu dropdown-menu-dark" style="min-width: 250px">
                    <h6 class="pb-2 mb-2 dropdown-header border-bottom">Tipo de Movimiento</h6>
                    <div class="mb-2 form-check">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados" value="entrada"
                            id="checkEntrada">
                        <label class="gap-2 form-check-label d-flex align-items-center" for="checkEntrada">
                            <span class="badge bg-success">
                                <i class="fas fa-arrow-right me-1"></i> Entrada
                            </span>
                        </label>
                    </div>
                    <div class="mb-2 form-check">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados" value="salida"
                            id="checkSalida">
                        <label class="gap-2 form-check-label d-flex align-items-center" for="checkSalida">
                            <span class="badge bg-danger">
                                <i class="fas fa-arrow-left me-1"></i> Salida
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" wire:model.live="tiposFiltrados"
                            value="traslado" id="checkTraslado">
                        <label class="gap-2 form-check-label d-flex align-items-center" for="checkTraslado">
                            <span class="badge bg-secondary">
                                <i class="fas fa-exchange-alt me-1"></i> Traslado
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="px-3 py-2 text-white border badge bg-dark border-secondary">
                <i class="fas fa-list me-1"></i> {{ $movimientos->total() }} movimientos
            </div>
        </div>
    </div>


    <div class="overflow-hidden border-0 shadow-sm card bg-dark">
        <div class="table-responsive">
            <table class="table mb-0 table-dark table-hover">
                <thead>
                    <tr class="text-white bg-dark-subtle border-bottom border-secondary">
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
                            <td colspan="7" class="py-5 text-center">
                                <div class="text-white">
                                    <i class="mb-3 fas fa-search fa-3x text-secondary d-block"></i>
                                    <p class="mb-0 text-secondary">No se encontraron movimientos que coincidan con la
                                        búsqueda</p>
                                    @if ($search || count($tiposFiltrados) > 0)
                                        <button wire:click="limpiarFiltros"
                                            class="mt-3 btn btn-outline-secondary btn-sm">
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
                                        <div class="bg-opacity-50 border date-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2 border-info"
                                            style="width: 35px; height: 35px;">
                                            <i class="text-black fas fa-calendar"></i>
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
                                            <i class="text-black fas fa-user"></i>
                                        </div>
                                        <span>{{ $movimiento->usuario?->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="product-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="text-black fas fa-box"></i>
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
                                    <span class="px-3 py-2 text-white fw-bold">
                                        {{ $movimiento->cantidad }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="location-icon rounded-circle bg-dark-subtle d-flex align-items-center justify-content-center me-2"
                                            style="width: 35px; height: 35px;">
                                            <i class="text-black fas fa-map-marker-alt"></i>
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
                                            <i class="text-black fas fa-map-marker-alt"></i>
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


    <style>
        .pagination-results {
            color: white !important;
        }


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

                const movimientoRows = document.querySelectorAll('.movimiento-row');
                movimientoRows.forEach(row => {
                    row.addEventListener('click', function() {
                        window.location.href = this.getAttribute('data-href');
                    });
                });


                function setPaginationTextWhite() {
                    const paginationTexts = document.querySelectorAll(
                        'nav[aria-label="Pagination Navigation"] div p, nav[aria-label="Pagination Navigation"] p, .leading-5'
                        );
                    paginationTexts.forEach(text => {
                        text.style.color = 'white';
                    });
                }


                setPaginationTextWhite();

                
                document.addEventListener('livewire:load', function() {
                    Livewire.hook('message.processed', () => {
                        setPaginationTextWhite();
                    });
                });
            });
        </script>
    @endpush
</div>

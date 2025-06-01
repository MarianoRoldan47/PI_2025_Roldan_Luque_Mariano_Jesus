<div>
    <!-- Buscador y Filtros -->
    <div class="row g-2 mb-3">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-white border-secondary py-1">
                    <i class="fas fa-search small"></i>
                </span>
                <input wire:model.live="search" type="text"
                    class="form-control form-control-sm bg-dark text-white border-secondary"
                    placeholder="Buscar productos...">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-white border-secondary">
                    <i class="fas fa-tag small"></i>
                </span>
                <select wire:model.live="categoriaSeleccionada"
                    class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-white border-secondary">
                    <i class="fas fa-boxes small"></i>
                </span>
                <select wire:model.live="stockFilter"
                    class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="todos">Todos los productos</option>
                    <option value="disponibles">Con stock</option>
                    <option value="agotados">Sin stock</option>
                    <option value="bajoMinimo">Stock bajo mínimo</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-md-2">
            <button class="btn btn-sm btn-outline-secondary w-100" wire:click="clearFilters">
                <i class="fas fa-filter-circle-xmark me-1"></i> Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Indicador de filtros activos -->
    @if ($search || $categoriaSeleccionada || $stockFilter != 'todos')
        <div class="d-flex flex-wrap gap-2 mb-3">
            <div class="small text-light">Filtros activos:</div>

            @if ($search)
                <span class="badge bg-primary d-flex align-items-center">
                    <i class="fas fa-search me-1"></i> "{{ $search }}"
                </span>
            @endif

            @if ($categoriaSeleccionada)
                <span class="badge bg-info d-flex align-items-center">
                    <i class="fas fa-tag me-1"></i>
                    Categoría: {{ $categorias->firstWhere('id', $categoriaSeleccionada)->nombre }}
                </span>
            @endif

            @if ($stockFilter != 'todos')
                <span
                    class="badge bg-{{ $stockFilter == 'bajoMinimo' ? 'warning' : ($stockFilter == 'agotados' ? 'danger' : 'success') }} d-flex align-items-center">
                    <i class="fas fa-boxes me-1"></i>
                    @if ($stockFilter == 'disponibles')
                        Con stock
                    @elseif($stockFilter == 'agotados')
                        Sin stock
                    @elseif($stockFilter == 'bajoMinimo')
                        Stock bajo mínimo
                    @endif
                </span>
            @endif
        </div>
    @endif

    @if ($productos->isEmpty())
        <div class="card bg-dark border-secondary text-center py-5">
            <div class="card-body">
                <i class="fas fa-search fa-3x mb-3 text-secondary"></i>
                <h5 class="text-white">No se encontraron productos</h5>
                <p class="text-white-50">Prueba a cambiar los filtros de búsqueda</p>
                @if ($search || $categoriaSeleccionada || $stockFilter != 'todos')
                    <button class="btn btn-outline-secondary mt-2" wire:click="clearFilters">
                        <i class="fas fa-filter-circle-xmark me-1"></i> Limpiar filtros
                    </button>
                @endif
            </div>
        </div>
    @else
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-2">
            @foreach ($productos as $producto)
                <div class="col">
                    <div class="card h-100 bg-dark border-secondary product-card d-flex flex-column"
                        style="background: linear-gradient(180deg, #2a2a2a 0%, #1a1a1a 100%) !important;">
                        <a href="{{ route('productos.show', $producto) }}" class="text-decoration-none flex-grow-1">
                            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                class="card-img-top object-fit-cover product-image" alt="{{ $producto->nombre }}">

                            <div class="card-body p-2">
                                <div class="d-flex flex-wrap gap-1 justify-content-between align-items-start mb-1">
                                    <span class="badge text-bg-secondary d-inline-block text-truncate"
                                        style="max-width: 100%; overflow: hidden;">
                                        {{ $producto->codigo_producto }}
                                    </span>
                                    <span
                                        class="badge {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'text-bg-danger' : 'text-bg-success' }}">
                                        {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'Stock Bajo' : 'Stock OK' }}
                                    </span>
                                </div>

                                <h6 class="card-title text-white mb-1 small fw-bold d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->nombre }}">
                                    {{ $producto->nombre }}
                                </h6>

                                <p class="card-text small text-info mb-1 d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->categoria->nombre }}">
                                    <i class="fas fa-tag me-1 small"></i>
                                    {{ $producto->categoria->nombre }}
                                </p>

                                <p class="card-text small mb-0">
                                    <span class="text-light opacity-75">Stock:</span>
                                    <span class="text-white fw-bold ms-1">{{ $producto->stock_total }}</span>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @push('styles')
        <style>
            .product-card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .product-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }

            .product-card a {
                color: inherit;
            }

            .product-card a:hover {
                text-decoration: none;
            }

            .product-card .btn {
                position: relative;
                z-index: 2;
            }

            .product-image {
                height: 150px;
                object-fit: cover;
            }
        </style>
    @endpush
</div>

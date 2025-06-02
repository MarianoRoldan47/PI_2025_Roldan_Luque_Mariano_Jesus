<div>

    <div class="mb-3 row g-2">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="py-1 text-white input-group-text bg-dark border-secondary">
                    <i class="fas fa-search small"></i>
                </span>
                <input wire:model.live="search" type="text"
                    class="text-white form-control form-control-sm bg-dark border-secondary"
                    placeholder="Buscar productos...">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="input-group input-group-sm">
                <span class="text-white input-group-text bg-dark border-secondary">
                    <i class="fas fa-tag small"></i>
                </span>
                <select wire:model.live="categoriaSeleccionada"
                    class="text-white form-select form-select-sm bg-dark border-secondary">
                    <option value="">Todas las categorías</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="input-group input-group-sm">
                <span class="text-white input-group-text bg-dark border-secondary">
                    <i class="fas fa-boxes small"></i>
                </span>
                <select wire:model.live="stockFilter"
                    class="text-white form-select form-select-sm bg-dark border-secondary">
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


    @if ($search || $categoriaSeleccionada || $stockFilter != 'todos')
        <div class="flex-wrap gap-2 mb-3 d-flex">
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
        <div class="py-5 text-center card bg-dark border-secondary">
            <div class="card-body">
                <i class="mb-3 fas fa-search fa-3x text-secondary"></i>
                <h5 class="text-white">No se encontraron productos</h5>
                <p class="text-white-50">Prueba a cambiar los filtros de búsqueda</p>
                @if ($search || $categoriaSeleccionada || $stockFilter != 'todos')
                    <button class="mt-2 btn btn-outline-secondary" wire:click="clearFilters">
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

                            <div class="p-2 card-body">
                                <div class="flex-wrap gap-1 mb-1 d-flex justify-content-between align-items-start">
                                    <span class="badge text-bg-secondary d-inline-block text-truncate"
                                        style="max-width: 100%; overflow: hidden;">
                                        {{ $producto->codigo_producto }}
                                    </span>
                                    <span
                                        class="badge {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'text-bg-danger' : 'text-bg-success' }}">
                                        {{ $producto->stock_total < $producto->stock_minimo_alerta ? 'Stock Bajo' : 'Stock OK' }}
                                    </span>
                                </div>

                                <h6 class="mb-1 text-white card-title small fw-bold d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->nombre }}">
                                    {{ $producto->nombre }}
                                </h6>

                                <p class="mb-1 card-text small text-info d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->categoria->nombre }}">
                                    <i class="fas fa-tag me-1 small"></i>
                                    {{ $producto->categoria->nombre }}
                                </p>

                                <p class="mb-0 card-text small">
                                    <span class="opacity-75 text-light">Stock:</span>
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

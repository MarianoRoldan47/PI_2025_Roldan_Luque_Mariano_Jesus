<div>
    <!-- Buscador -->
    <div class="row g-2 mb-2 mb-sm-3">
        <div class="col-12 col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-dark text-white border-secondary py-1">
                    <i class="fas fa-search small"></i>
                </span>
                <input wire:model.live="search" type="text"
                    class="form-control form-control-sm bg-dark text-white border-secondary"
                    placeholder="Buscar productos...">
            </div>
        </div>
    </div>

    <!-- Grid de Productos -->
    @if ($productos->isEmpty())
        <div class="text-center py-2 py-sm-4">
            <i class="fas fa-search fa-2x mb-2 text-secondary"></i>
            <p class="text-white-50 small mb-0">No se encontraron productos</p>
        </div>
    @else
        <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-2">
            @foreach ($productos as $producto)
                <div class="col">
                    <!-- Eliminado div col anidado que causaba el problema -->
                    <div class="card h-100 bg-dark border-secondary product-card d-flex flex-column"
                        style="background: linear-gradient(180deg, #2a2a2a 0%, #1a1a1a 100%) !important;">
                        <a href="{{ route('productos.show', $producto) }}" class="text-decoration-none flex-grow-1">
                            <!-- Imagen -->
                            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                class="card-img-top object-fit-cover product-image" alt="{{ $producto->nombre }}">

                            <div class="card-body p-2">
                                <!-- Badges con overflow-wrap -->
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

                                <!-- Nombre con truncado -->
                                <h6 class="card-title text-white mb-1 small fw-bold d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->nombre }}">
                                    {{ $producto->nombre }}
                                </h6>

                                <!-- Categoría con truncado -->
                                <p class="card-text small text-info mb-1 d-block text-truncate"
                                    style="overflow: hidden;" title="{{ $producto->categoria->nombre }}">
                                    <i class="fas fa-tag me-1 small"></i>
                                    {{ $producto->categoria->nombre }}
                                </p>

                                <!-- Stock -->
                                <p class="card-text small mb-0">
                                    <span class="text-light opacity-75">Stock:</span>
                                    <span class="text-white fw-bold ms-1">{{ $producto->stock_total }}</span>
                                </p>
                            </div>
                        </a>

                        @if (Auth::user()->rol === 'Administrador')
                            <div class="card-footer border-secondary p-0 mt-auto">
                                <div class="row g-0">
                                    <div class="col-6">
                                        <a href="{{ route('productos.edit', $producto) }}"
                                            class="btn btn-warning w-100 rounded-0 border-end border-secondary d-flex align-items-center justify-content-center py-1">
                                            <i class="fa-solid fa-pen-to-square small"></i>
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="button"
                                            class="btn btn-danger w-100 rounded-0 d-flex align-items-center justify-content-center py-1"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            data-producto-id="{{ $producto->id }}">
                                            <i class="fas fa-trash small"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
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

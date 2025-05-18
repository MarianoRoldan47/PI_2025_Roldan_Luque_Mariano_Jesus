<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <!-- Header -->
        <div class="row g-2 mb-2 mb-sm-4">
            <div class="col-12 col-md">
                <h1 class="fs-5 fs-sm-3 mb-0">{{ $producto->nombre }}</h1>
                <p class="text-muted small mb-0">Detalles del producto</p>
            </div>
            <div class="col-12 col-md text-md-end mt-2 mt-md-0">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                @endif
                <a href="{{ route('productos.index') }}" class="btn btn-secondary btn-sm ms-1">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- Contenido -->
        <div class="row g-2 g-sm-4">
            <!-- Tarjeta de información -->
            <div class="col-md-4">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="d-flex justify-content-center p-2 p-sm-3" style="height: 200px; height: sm-300px;">
                        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                            class="h-100" alt="{{ $producto->nombre }}"
                            style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <div class="card-body p-2 p-sm-3">
                        <h5 class="card-title text-center mb-2 mb-sm-3 fs-6">Información General</h5>
                        <ul class="list-unstyled small">
                            <li class="mb-1"><strong>Código:</strong> {{ $producto->codigo_producto }}</li>
                            <li class="mb-1"><strong>Categoría:</strong> {{ $producto->categoria->nombre }}</li>
                            <li class="mb-1"><strong>Tipo:</strong> {{ ucfirst($producto->tipo) }}</li>
                            <li class="mb-1"><strong>Stock Total:</strong> {{ $producto->stock_total }}</li>
                            <li class="mb-1"><strong>Stock Mínimo:</strong> {{ $producto->stock_minimo_alerta }}</li>
                            <li>
                                <strong>Estado:</strong>
                                <span class="badge bg-{{ $producto->necesita_reposicion ? 'danger' : 'success' }}">
                                    {{ $producto->necesita_reposicion ? 'Stock Bajo' : 'Stock OK' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-8">
                <!-- Descripción -->
                <div class="card bg-dark text-white shadow-sm mb-2 mb-sm-4">
                    <div class="card-body p-2 p-sm-3">
                        <h5 class="card-title fs-6 mb-2">Descripción</h5>
                        <p class="small mb-0">{{ $producto->descripcion ?: 'Sin descripción' }}</p>
                    </div>
                </div>

                <!-- Ubicaciones -->
                <div class="card bg-dark text-white shadow-sm mb-2 mb-sm-4">
                    <div class="card-body p-2 p-sm-3">
                        <h5 class="card-title fs-6 mb-2">Ubicaciones</h5>
                        @if ($producto->estanterias->isEmpty() || $producto->estanterias->sum('pivot.cantidad') == 0)
                            <p class="text-white small mb-0">Este producto no está almacenado en ninguna estantería.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover small mb-0">
                                    <thead>
                                        <tr>
                                            <th>Estantería</th>
                                            <th>Zona</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->estanterias as $estanteria)
                                            <tr>
                                                <td>Estancetia {{ $estanteria->nombre }}</td>
                                                <td>{{ $estanteria->zona->nombre }}</td>
                                                <td class="text-center">{{ $estanteria->pivot->cantidad }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Movimientos -->
                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body p-2 p-sm-3">
                        <h5 class="card-title fs-6 mb-2">Últimos Movimientos</h5>
                        @if ($producto->movimientos->isEmpty())
                            <p class="text-white small mb-0">No hay movimientos registrados para este producto.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-dark table-hover small mb-0">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($producto->movimientos as $movimiento)
                                            <tr style="cursor: pointer"
                                                onclick="window.location='{{ route('movimientos.show', $movimiento) }}'">
                                                <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($movimiento->tipo) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">{{ $movimiento->cantidad }}</td>
                                                <td>{{ $movimiento->usuario->name }}</td>
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
</x-app-layout>

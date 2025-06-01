<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">EDITAR MOVIMIENTO</h1>
                <p>Modifica los datos del movimiento</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('movimientos.update', $movimiento) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="producto_id" class="form-label">Producto</label>
                                    <select class="form-select bg-dark text-white" id="producto_id" name="producto_id"
                                        required>
                                        <option value="">Selecciona un producto</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}"
                                                {{ $movimiento->producto_id == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="user_id" class="form-label">Usuario</label>
                                    <select class="form-select bg-dark text-white" id="user_id" name="user_id"
                                        required>
                                        <option value="">Selecciona un usuario</option>
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}"
                                                {{ $movimiento->user_id == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="tipo" class="form-label">Tipo de Movimiento</label>
                                    <select class="form-select bg-dark text-white" id="tipo" name="tipo"
                                        required>
                                        <option value="entrada" {{ $movimiento->tipo == 'entrada' ? 'selected' : '' }}>
                                            Entrada</option>
                                        <option value="salida" {{ $movimiento->tipo == 'salida' ? 'selected' : '' }}>
                                            Salida</option>
                                        <option value="traslado"
                                            {{ $movimiento->tipo == 'traslado' ? 'selected' : '' }}>Traslado</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="cantidad" class="form-label">Cantidad</label>
                                    <input type="number" class="form-control bg-dark text-white" id="cantidad"
                                        name="cantidad" value="{{ $movimiento->cantidad }}" required min="1">
                                </div>

                                <div class="col-md-4">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select bg-dark text-white" id="estado" name="estado"
                                        required>
                                        <option value="pendiente"
                                            {{ $movimiento->estado == 'pendiente' ? 'selected' : '' }}>Pendiente
                                        </option>
                                        <option value="confirmado"
                                            {{ $movimiento->estado == 'confirmado' ? 'selected' : '' }}>Confirmado
                                        </option>
                                        <option value="cancelado"
                                            {{ $movimiento->estado == 'cancelado' ? 'selected' : '' }}>Cancelado
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="origen_tipo" class="form-label">Tipo de Origen</label>
                                    <select
                                        class="form-select bg-dark text-white @error('origen_tipo') is-invalid @enderror"
                                        id="origen_tipo" name="origen_tipo" required>
                                        <option value="">Selecciona un tipo de origen</option>
                                        <option value="estanteria"
                                            {{ $movimiento->origen_tipo == 'estanteria' ? 'selected' : '' }}>
                                            Estantería
                                        </option>
                                        <option value="proveedor"
                                            {{ $movimiento->origen_tipo == 'proveedor' ? 'selected' : '' }}>
                                            Proveedor
                                        </option>
                                    </select>
                                    @error('origen_tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6" id="origen_estanteria_container"
                                    style="{{ $movimiento->origen_tipo != 'estanteria' ? 'display: none;' : '' }}">
                                    <label for="ubicacion_origen_id" class="form-label">Ubicación Origen</label>
                                    <select class="form-select bg-dark text-white" id="ubicacion_origen_id"
                                        name="ubicacion_origen_id">
                                        <option value="">Selecciona una estantería</option>
                                        @foreach ($estanterias as $estanteria)
                                            <option value="{{ $estanteria['id'] }}"
                                                {{ $movimiento->ubicacion_origen_id == $estanteria['id'] ? 'selected' : '' }}>
                                                {{ $estanteria['nombre'] }} (Stock:
                                                {{ $estanteria['stock_producto'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="destino_tipo" class="form-label">Tipo de Destino</label>
                                    <select
                                        class="form-select bg-dark text-white @error('destino_tipo') is-invalid @enderror"
                                        id="destino_tipo" name="destino_tipo" required>
                                        <option value="">Selecciona un tipo de destino</option>
                                        <option value="estanteria"
                                            {{ $movimiento->destino_tipo == 'estanteria' ? 'selected' : '' }}>
                                            Estantería
                                        </option>
                                        <option value="cliente"
                                            {{ $movimiento->destino_tipo == 'cliente' ? 'selected' : '' }}>
                                            Cliente
                                        </option>
                                    </select>
                                    @error('destino_tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6" id="destino_estanteria_container"
                                    style="{{ $movimiento->destino_tipo != 'estanteria' ? 'display: none;' : '' }}">
                                    <label for="ubicacion_destino_id" class="form-label">Ubicación Destino</label>
                                    <select class="form-select bg-dark text-white" id="ubicacion_destino_id"
                                        name="ubicacion_destino_id">
                                        <option value="">Selecciona una estantería</option>
                                        @foreach ($estanterias as $estanteria)
                                            <option value="{{ $estanteria['id'] }}"
                                                {{ $movimiento->ubicacion_destino_id == $estanteria['id'] ? 'selected' : '' }}>
                                                {{ $estanteria['nombre'] }} (Espacio:
                                                {{ $estanteria['espacio_disponible'] }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha_movimiento" class="form-label">Fecha del Movimiento</label>
                                    <input type="datetime-local" class="form-control bg-dark text-white"
                                        id="fecha_movimiento" name="fecha_movimiento"
                                        value="{{ $movimiento->fecha_movimiento->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tipoMovimiento = document.getElementById('tipo');
                const cantidad = document.getElementById('cantidad');
                const origenTipo = document.getElementById('origen_tipo');
                const destinoTipo = document.getElementById('destino_tipo');
                const origenEstanteriaContainer = document.getElementById('origen_estanteria_container');
                const destinoEstanteriaContainer = document.getElementById('destino_estanteria_container');
                const ubicacionOrigenId = document.getElementById('ubicacion_origen_id');
                const ubicacionDestinoId = document.getElementById('ubicacion_destino_id');

                // Datos de las estanterías (los pasamos desde PHP)
                const estanterias = @json($estanterias);

                function actualizarTiposSegunMovimiento() {
                    const tipo = tipoMovimiento.value;
                    const cantidadValue = parseInt(cantidad.value) || 0;

                    // Establecer valores por defecto según el tipo de movimiento
                    switch (tipo) {
                        case 'entrada':
                            origenTipo.value = 'proveedor';
                            destinoTipo.value = 'estanteria';
                            break;
                        case 'salida':
                            origenTipo.value = 'estanteria';
                            destinoTipo.value = 'cliente';
                            break;
                        case 'traslado':
                            origenTipo.value = 'estanteria';
                            destinoTipo.value = 'estanteria';
                            break;
                        default:
                            // Si no hay tipo seleccionado, usar los valores actuales del movimiento
                            origenTipo.value = '{{ $movimiento->origen_tipo }}' || 'estanteria';
                            destinoTipo.value = '{{ $movimiento->destino_tipo }}' || 'estanteria';
                    }

                    // Deshabilitar los selects según el tipo
                    origenTipo.readOnly  = true;
                    destinoTipo.readOnly  = true;

                    // Actualizar visibilidad y contenido de los contenedores
                    if (origenTipo.value === 'estanteria') {
                        origenEstanteriaContainer.style.display = 'block';
                        actualizarEstanteriasOrigen(cantidadValue);
                    } else {
                        origenEstanteriaContainer.style.display = 'none';
                    }

                    if (destinoTipo.value === 'estanteria') {
                        destinoEstanteriaContainer.style.display = 'block';
                        actualizarEstanteriasDestino(cantidadValue);
                    } else {
                        destinoEstanteriaContainer.style.display = 'none';
                    }
                }

                function actualizarEstanteriasOrigen(cantidadRequerida) {
                    ubicacionOrigenId.innerHTML = '<option value="">Selecciona una estantería</option>';

                    estanterias.forEach(estanteria => {
                        // Incluir si tiene stock suficiente O si es la estantería origen actual
                        if (parseInt(estanteria.stock_producto) >= parseInt(cantidadRequerida) ||
                            estanteria.id == {{ $movimiento->ubicacion_origen_id ?? 'null' }}) {
                            const option = new Option(
                                `Estanteria: ${estanteria.nombre} - ${estanteria.zona} (Stock disponible: ${estanteria.stock_producto})`,
                                estanteria.id,
                                false,
                                estanteria.id == {{ $movimiento->ubicacion_origen_id ?? 'null' }}
                            );
                            ubicacionOrigenId.add(option);
                        }
                    });

                    if (ubicacionOrigenId.options.length === 1) {
                        const option = new Option('No hay estanterías con stock suficiente', '', false, false);
                        option.readOnly  = true;
                        ubicacionOrigenId.add(option);
                    }
                }

                function actualizarEstanteriasDestino(cantidadRequerida) {
                    ubicacionDestinoId.innerHTML = '<option value="">Selecciona una estantería</option>';

                    estanterias.forEach(estanteria => {
                        // Incluir si tiene espacio suficiente O si es la estantería destino actual
                        if (parseInt(estanteria.espacio_disponible) >= parseInt(cantidadRequerida) ||
                            estanteria.id == {{ $movimiento->ubicacion_destino_id ?? 'null' }}) {
                            const option = new Option(
                                `Estanteria: ${estanteria.nombre} - ${estanteria.zona} (Libre: ${estanteria.espacio_disponible}/${estanteria.capacidad_maxima})`,
                                estanteria.id,
                                false,
                                estanteria.id == {{ $movimiento->ubicacion_destino_id ?? 'null' }}
                            );
                            ubicacionDestinoId.add(option);
                        }
                    });

                    if (ubicacionDestinoId.options.length === 1) {
                        const option = new Option('No hay estanterías con espacio libre suficiente', '', false, false);
                        option.readOnly  = true;
                        ubicacionDestinoId.add(option);
                    }
                }

                // Event listeners
                tipoMovimiento.addEventListener('change', actualizarTiposSegunMovimiento);
                cantidad.addEventListener('change', actualizarTiposSegunMovimiento);

                // Inicialización
                actualizarTiposSegunMovimiento();
            });
        </script>
    </div>
</x-app-layout>

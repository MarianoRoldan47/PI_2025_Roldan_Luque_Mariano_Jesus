<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col-12 col-md">
                <h1 class="h3">NUEVO MOVIMIENTO - {{ ucfirst($tipo) }}</h1>
                <p>Introduce los datos del nuevo movimiento</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
        @if ($tipo === 'traslado')
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                El sistema gestionará automáticamente los movimientos necesarios para realizar el traslado.
            </div>
        @elseif ($tipo === 'salida')
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                El sistema gestionará automáticamente de qué ubicaciones extraer el producto.
            </div>
        @endif

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
                <div class="text-white shadow-sm card bg-dark">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i
                                class="fas fa-{{ $tipo === 'entrada' ? 'arrow-right' : ($tipo === 'salida' ? 'arrow-left' : 'exchange-alt') }} me-2"></i>
                            Formulario de {{ ucfirst($tipo) }}
                        </h5>

                        <form action="{{ route('movimientos.store') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $tipo }}">

                            <div class="mb-3 row">
                                <div class="col-md-6">
                                    <label for="producto_id" class="form-label">Producto *</label>
                                    <select class="text-white form-select bg-dark" id="producto_id" name="producto_id"
                                        required>
                                        <option value="">Selecciona un producto</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}"
                                                {{ old('producto_id', request()->producto_id) == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }} (Stock: {{ $producto->stock_total }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="cantidad" class="form-label">Cantidad *</label>
                                    <div class="cantidad-container">
                                        <input type="number" class="text-white form-control bg-dark" id="cantidad"
                                            name="cantidad" value="{{ old('cantidad') }}" required min="1">
                                    </div>
                                </div>
                            </div>

                            @if ($tipo === 'entrada')

                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <label for="origen_tipo" class="form-label">Origen *</label>
                                        <select class="text-white form-select bg-dark" id="origen_tipo"
                                            name="origen_tipo" required readonly>
                                            <option value="proveedor" selected>Proveedor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="contenedor-estanterias">
                                        <label class="form-label">Ubicaciones Destino *</label>
                                        <div id="estanterias-seleccionadas" class="mb-2">

                                        </div>
                                        <div class="input-group">
                                            <select class="text-white form-select bg-dark"
                                                id="ubicacion_destino_selector">
                                                <option value="">Selecciona una estantería</option>
                                                @foreach ($estanterias as $estanteria)
                                                    <option value="{{ $estanteria['id'] }}"
                                                        data-capacidad="{{ $estanteria['capacidad_libre'] }}">
                                                        {{ $estanteria['nombre'] }} {{ $estanteria['zona'] }}
                                                        (Espacio: {{ $estanteria['capacidad_libre'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-info" id="agregar-estanteria">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @elseif($tipo === 'salida')

                                <div class="mb-3 row">
                                    <div class="col-md-12">
                                        <label for="destino_tipo" class="form-label">Destino *</label>
                                        <select class="text-white form-select bg-dark" id="destino_tipo"
                                            name="destino_tipo" required readonly>
                                            <option value="cliente" selected>Cliente</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <label class="form-label">Ubicaciones Origen *</label>
                                        <div id="estanterias-origen-seleccionadas" class="mb-2"></div>
                                        <div class="input-group">
                                            <select class="text-white form-select bg-dark"
                                                id="ubicacion_origen_selector">
                                                <option value="">Selecciona una estantería</option>
                                                
                                            </select>
                                            <button type="button" class="btn btn-info" id="agregar-estanteria-origen">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ubicaciones Destino *</label>
                                        <div id="estanterias-destino-seleccionadas" class="mb-2"></div>
                                        <div class="input-group">
                                            <select class="text-white form-select bg-dark"
                                                id="ubicacion_destino_selector">
                                                <option value="">Selecciona una estantería</option>
                                                @foreach ($estanteriasDestino as $estanteria)
                                                    <option value="{{ $estanteria['id'] }}"
                                                        data-capacidad="{{ $estanteria['capacidad_libre'] }}">
                                                        {{ $estanteria['nombre'] }} {{ $estanteria['zona'] }}
                                                        (Espacio: {{ $estanteria['capacidad_libre'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-info"
                                                id="agregar-estanteria-destino">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar
                                </button>
                                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const tipo = '{{ $tipo }}';
                    const cantidadInput = document.getElementById('cantidad');
                    const productoSelect = document.getElementById('producto_id');
                    let cantidadRequerida = parseInt(cantidadInput.value) || 0;



                    const estanteriasEntradaSeleccionadas = new Map();
                    const estanteriasOrigenSeleccionadas = new Map();
                    const estanteriasDestinoSeleccionadas = new Map();

                    if (tipo === 'traslado') {
                        let isUpdating = false;

                        cantidadInput.addEventListener('change', function() {
                            if (isUpdating) return;
                            isUpdating = true;

                            const cantidad = parseInt(this.value) || 0;
                            const destinoSelect = document.getElementById('ubicacion_destino_selector');


                            const capacidadTotal = Array.from(destinoSelect.options)
                                .filter(opt => opt.value)
                                .reduce((sum, opt) => sum + (parseInt(opt.dataset.capacidad) || 0), 0);


                            const errorExistente = document.getElementById('error-cantidad');
                            if (errorExistente) {
                                errorExistente.remove();
                            }

                            if (cantidad > capacidadTotal) {

                                const errorDiv = document.createElement('div');
                                errorDiv.id = 'error-cantidad';
                                errorDiv.className = 'alert alert-danger mt-2';
                                errorDiv.innerHTML = `
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No hay suficiente espacio disponible en las estanterías para realizar el traslado. Espacio total disponible para trasladar: ${capacidadTotal}
                                `;

                                this.parentNode.appendChild(errorDiv);
                                this.value = capacidadTotal;
                                cantidadRequerida = capacidadTotal;
                            }


                            if (productoSelect.value && !isUpdating) {
                                productoSelect.dispatchEvent(new Event('change'));
                            }

                            isUpdating = false;
                        });

                        productoSelect.addEventListener('change', async function() {
                            if (isUpdating) return;
                            isUpdating = true;

                            const productoId = this.value;
                            const origenSelect = document.getElementById('ubicacion_origen_selector');
                            const destinoSelect = document.getElementById('ubicacion_destino_selector');
                            const cantidad = parseInt(cantidadInput.value) || 0;


                            origenSelect.innerHTML = '<option value="">Selecciona una estantería</option>';
                            destinoSelect.innerHTML = '<option value="">Selecciona una estantería</option>';

                            if (!productoId) {
                                isUpdating = false;
                                return;
                            }

                            try {
                                const response = await fetch(
                                    `{{ route('movimientos.get-estanterias') }}?producto_id=${productoId}`, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest',
                                            'Accept': 'application/json'
                                        }
                                    });

                                if (!response.ok) throw new Error('Error en la respuesta del servidor');

                                const data = await response.json();


                                if (data.estanteriasOrigen && data.estanteriasOrigen.length > 0) {
                                    data.estanteriasOrigen.forEach(estanteria => {
                                        const option = document.createElement('option');
                                        option.value = estanteria.id;
                                        option.textContent =
                                            `${estanteria.nombre} ${estanteria.zona} (Stock: ${estanteria.stock_producto})`;
                                        option.dataset.stock = estanteria.stock_producto;
                                        origenSelect.appendChild(option);
                                    });
                                }


                                if (data.estanteriasDestino && data.estanteriasDestino.length > 0) {
                                    data.estanteriasDestino.forEach(estanteria => {
                                        const option = document.createElement('option');
                                        option.value = estanteria.id;
                                        option.textContent =
                                            `${estanteria.nombre} ${estanteria.zona} (Espacio: ${estanteria.capacidad_libre})`;
                                        option.dataset.capacidad = estanteria.capacidad_libre;
                                        destinoSelect.appendChild(option);
                                    });
                                }


                                if (cantidad > 0) {
                                    cantidadInput.dispatchEvent(new Event('change'));
                                }

                            } catch (error) {
                                console.error('Error:', error);
                            } finally {
                                isUpdating = false;
                            }
                        });
                    }
                    if (tipo === 'entrada') {
                        cantidadInput.addEventListener('change', function() {
                            const cantidad = parseInt(this.value) || 0;
                            const capacidadTotal = Array.from(document.querySelectorAll(
                                    '#ubicacion_destino_selector option'))
                                .reduce((sum, option) => {
                                    return sum + (parseInt(option.dataset.capacidad) || 0);
                                }, 0);


                            const errorExistente = document.getElementById('error-cantidad');
                            if (errorExistente) {
                                errorExistente.remove();
                            }

                            if (cantidad > capacidadTotal) {

                                const errorDiv = document.createElement('div');
                                errorDiv.id = 'error-cantidad';
                                errorDiv.className = 'alert alert-danger mt-2';
                                errorDiv.innerHTML = `
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    No hay suficiente espacio disponible en las estanterías. Espacio total disponible: ${capacidadTotal}
                                `;


                                this.parentNode.appendChild(errorDiv);


                                this.value = capacidadTotal;
                            }
                        });
                    }

                    if (tipo === 'salida') {
                        productoSelect.addEventListener('change', function() {
                            const producto = this.options[this.selectedIndex];
                            const stockTotal = parseInt(producto.textContent.match(/Stock: (\d+)/)[1]);


                            cantidadInput.max = stockTotal;

                            if (cantidadInput.value > stockTotal) {
                                cantidadInput.value = stockTotal;
                                cantidadRequerida = stockTotal;
                            }


                            if (stockTotal === 0) {
                                alert('Este producto no tiene stock disponible');
                                cantidadInput.disabled = true;
                            } else {
                                cantidadInput.disabled = false;
                            }
                        });


                        cantidadInput.addEventListener('change', function() {
                            const producto = productoSelect.options[productoSelect.selectedIndex];
                            const stockTotal = parseInt(producto.textContent.match(/Stock: (\d+)/)[1]);

                            if (this.value > stockTotal) {
                                alert(`No hay suficiente stock. Stock disponible: ${stockTotal}`);
                                this.value = stockTotal;
                                cantidadRequerida = stockTotal;
                            }
                        });


                        document.querySelector('form').addEventListener('submit', function(e) {
                            const producto = productoSelect.options[productoSelect.selectedIndex];
                            if (!producto.value) {
                                e.preventDefault();
                                alert('Debes seleccionar un producto');
                                return;
                            }

                            const stockTotal = parseInt(producto.textContent.match(/Stock: (\d+)/)[1]);
                            const cantidad = parseInt(cantidadInput.value);

                            if (cantidad > stockTotal) {
                                e.preventDefault();
                                alert(`No hay suficiente stock. Stock disponible: ${stockTotal}`);
                                return;
                            }

                            if (cantidad <= 0) {
                                e.preventDefault();
                                alert('La cantidad debe ser mayor que 0');
                                return;
                            }
                        });
                    }


                    function actualizarVistaEstanterias(tipo, contenedorId, selectorId, estanteriasMap, dataAttribute) {
                        const contenedor = document.getElementById(contenedorId);
                        const selector = document.getElementById(selectorId);
                        contenedor.innerHTML = '';


                        Array.from(selector.options).forEach(option => {
                            if (option.value && estanteriasMap.has(option.value)) {
                                option.style.display = 'none';
                            } else {
                                option.style.display = '';
                            }
                        });


                        estanteriasMap.forEach((cantidad, estanteriaId) => {
                            const estanteria = selector.querySelector(`option[value="${estanteriaId}"]`);
                            const nombreCompleto = estanteria.textContent.split('(')[0].trim();
                            const maxValue = parseInt(estanteria.dataset[dataAttribute]);

                            const div = document.createElement('div');
                            div.className =
                                'bg-dark text-white p-2 mb-2 rounded d-flex justify-content-between align-items-center';
                            div.innerHTML =
                                `<div class="d-flex align-items-center">
                                    <span class="me-3">${nombreCompleto}</span>
                                    <div class="input-group" style="width: 150px;">
                                        <input type="number"
                                            class="text-white form-control form-control-sm bg-dark"
                                            value="${cantidad}"
                                            min="1"
                                            max="${maxValue}"
                                            onchange="actualizarCantidad('${tipo}', '${estanteriaId}', this.value)"
                                            oninput="this.value = Math.min(this.value, ${maxValue})">

                                        ${tipo === 'entrada' ?
                                            `<input type="hidden" name="ubicaciones[${estanteriaId}]" value="${cantidad}">` :
                                            tipo === 'origen' ?
                                                `<input type="hidden" name="ubicaciones_origen[${estanteriaId}]" value="${cantidad}">` :
                                                `<input type="hidden" name="ubicaciones_destino[${estanteriaId}]" value="${cantidad}">`
                                        }
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="eliminarEstanteria('${tipo}', '${estanteriaId}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            `;
                            contenedor.appendChild(div);
                        });


                        const cantidadTotal = Array.from(estanteriasMap.values()).reduce((a, b) => a + parseInt(b), 0);
                        const faltante = cantidadRequerida - cantidadTotal;

                        const infoDiv = document.createElement('div');
                        infoDiv.className =
                            `alert ${faltante > 0 ? 'alert-warning' : faltante < 0 ? 'alert-danger' : 'alert-success'} mt-2 mb-0`;

                        if (faltante > 0) {
                            infoDiv.innerHTML =
                                `<i class="fas fa-exclamation-triangle me-2"></i>Faltan ${faltante} unidades por asignar`;
                        } else if (faltante < 0) {
                            infoDiv.innerHTML =
                                `<i class="fas fa-exclamation-circle me-2"></i>Se han asignado ${Math.abs(faltante)} unidades de más`;
                        } else {
                            infoDiv.innerHTML =
                                `<i class="fas fa-check-circle me-2"></i>Cantidades correctamente distribuidas`;
                        }

                        contenedor.appendChild(infoDiv);
                    }


                    window.actualizarCantidad = function(tipo, estanteriaId, nuevaCantidad) {
                        const mapa = tipo === 'entrada' ? estanteriasEntradaSeleccionadas :
                            tipo === 'origen' ? estanteriasOrigenSeleccionadas :
                            estanteriasDestinoSeleccionadas;

                        const selector = document.getElementById(tipo === 'entrada' ? 'ubicacion_destino_selector' :
                            tipo === 'origen' ? 'ubicacion_origen_selector' :
                            'ubicacion_destino_selector');

                        const estanteria = selector.querySelector(`option[value="${estanteriaId}"]`);
                        const maxValue = parseInt(estanteria.dataset[tipo === 'origen' ? 'stock' : 'capacidad']);
                        nuevaCantidad = parseInt(nuevaCantidad);

                        if (nuevaCantidad > maxValue) {
                            alert(
                                `La cantidad no puede superar ${tipo === 'origen' ? 'el stock disponible' : 'la capacidad máxima'} de ${maxValue}`
                            );
                            nuevaCantidad = Math.min(cantidadRequerida, maxValue);
                        }

                        if (nuevaCantidad < 1) {
                            alert('La cantidad debe ser mayor que 0');
                            nuevaCantidad = 1;
                        }

                        mapa.set(estanteriaId, nuevaCantidad);
                        actualizarVistaEstanterias(
                            tipo,
                            tipo === 'entrada' ? 'estanterias-seleccionadas' :
                            tipo === 'origen' ? 'estanterias-origen-seleccionadas' :
                            'estanterias-destino-seleccionadas',
                            tipo === 'entrada' ? 'ubicacion_destino_selector' :
                            tipo === 'origen' ? 'ubicacion_origen_selector' :
                            'ubicacion_destino_selector',
                            mapa,
                            tipo === 'origen' ? 'stock' : 'capacidad'
                        );
                    };

                    window.eliminarEstanteria = function(tipo, estanteriaId) {
                        const mapa = tipo === 'entrada' ? estanteriasEntradaSeleccionadas :
                            tipo === 'origen' ? estanteriasOrigenSeleccionadas :
                            estanteriasDestinoSeleccionadas;

                        mapa.delete(estanteriaId);

                        const selector = document.getElementById(tipo === 'entrada' ? 'ubicacion_destino_selector' :
                            tipo === 'origen' ? 'ubicacion_origen_selector' :
                            'ubicacion_destino_selector');

                        const option = selector.querySelector(`option[value="${estanteriaId}"]`);
                        if (option) {
                            option.style.display = '';
                        }

                        actualizarVistaEstanterias(
                            tipo,
                            tipo === 'entrada' ? 'estanterias-seleccionadas' :
                            tipo === 'origen' ? 'estanterias-origen-seleccionadas' :
                            'estanterias-destino-seleccionadas',
                            tipo === 'entrada' ? 'ubicacion_destino_selector' :
                            tipo === 'origen' ? 'ubicacion_origen_selector' :
                            'ubicacion_destino_selector',
                            mapa,
                            tipo === 'origen' ? 'stock' : 'capacidad'
                        );
                    };


                    const botonesAgregar = {
                        'entrada': document.getElementById('agregar-estanteria'),
                        'origen': document.getElementById('agregar-estanteria-origen'),
                        'destino': document.getElementById('agregar-estanteria-destino')
                    };

                    Object.entries(botonesAgregar).forEach(([tipo, boton]) => {
                        if (boton) {
                            boton.addEventListener('click', function() {
                                const selector = document.getElementById(tipo === 'entrada' ?
                                    'ubicacion_destino_selector' :
                                    tipo === 'origen' ? 'ubicacion_origen_selector' :
                                    'ubicacion_destino_selector');
                                const estanteriaId = selector.value;

                                if (!estanteriaId) return;

                                const mapa = tipo === 'entrada' ? estanteriasEntradaSeleccionadas :
                                    tipo === 'origen' ? estanteriasOrigenSeleccionadas :
                                    estanteriasDestinoSeleccionadas;

                                const maxValue = parseInt(selector.selectedOptions[0].dataset[tipo ===
                                    'origen' ? 'stock' : 'capacidad']);
                                const cantidadPendiente = cantidadRequerida - Array.from(mapa.values())
                                    .reduce((a, b) => a + parseInt(b), 0);

                                if (cantidadPendiente <= 0) {
                                    alert('Ya se ha asignado toda la cantidad requerida');
                                    return;
                                }

                                const cantidadSugerida = Math.min(maxValue, cantidadPendiente);
                                mapa.set(estanteriaId, cantidadSugerida);

                                actualizarVistaEstanterias(
                                    tipo,
                                    tipo === 'entrada' ? 'estanterias-seleccionadas' :
                                    tipo === 'origen' ? 'estanterias-origen-seleccionadas' :
                                    'estanterias-destino-seleccionadas',
                                    selector.id,
                                    mapa,
                                    tipo === 'origen' ? 'stock' : 'capacidad'
                                );

                                selector.value = '';
                            });
                        }
                    });


                    document.querySelector('form').addEventListener('submit', function(e) {
                        if (tipo === 'entrada') {
                            const totalEntrada = Array.from(estanteriasEntradaSeleccionadas.values())
                                .reduce((a, b) => a + parseInt(b), 0);
                            if (totalEntrada !== cantidadRequerida) {
                                e.preventDefault();
                                alert('La cantidad total asignada debe ser igual a la cantidad requerida');
                            }
                        } else if (tipo === 'traslado') {
                            e.preventDefault();


                            if (estanteriasOrigenSeleccionadas.size === 0 || estanteriasDestinoSeleccionadas
                                .size === 0) {
                                alert('Debes seleccionar al menos una ubicación de origen y una de destino');
                                return;
                            }


                            const totalOrigen = Array.from(estanteriasOrigenSeleccionadas.values())
                                .reduce((a, b) => a + parseInt(b), 0);
                            const totalDestino = Array.from(estanteriasDestinoSeleccionadas.values())
                                .reduce((a, b) => a + parseInt(b), 0);

                            if (totalOrigen !== cantidadRequerida || totalDestino !== cantidadRequerida) {
                                alert(
                                    'Las cantidades en origen y destino deben ser iguales a la cantidad total del movimiento'
                                );
                                return;
                            }


                            this.submit();
                        }
                    });


                    cantidadInput.addEventListener('change', function() {
                        cantidadRequerida = parseInt(this.value) || 0;
                        estanteriasEntradaSeleccionadas.clear();
                        estanteriasOrigenSeleccionadas.clear();
                        estanteriasDestinoSeleccionadas.clear();
                        if (tipo === 'entrada') {
                            actualizarVistaEstanterias('entrada', 'estanterias-seleccionadas',
                                'ubicacion_destino_selector', estanteriasEntradaSeleccionadas, 'capacidad');
                        } else if (tipo === 'traslado') {
                            actualizarVistaEstanterias('origen', 'estanterias-origen-seleccionadas',
                                'ubicacion_origen_selector', estanteriasOrigenSeleccionadas, 'stock');
                            actualizarVistaEstanterias('destino', 'estanterias-destino-seleccionadas',
                                'ubicacion_destino_selector', estanteriasDestinoSeleccionadas, 'capacidad');
                        }
                    });
                });
            </script>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const cantidad = document.getElementById('cantidad');
                    const ubicacionOrigenId = document.getElementById('ubicacion_origen_id');
                    const ubicacionDestinoId = document.getElementById('ubicacion_destino_id');


                    const estanteriasOrigen = @json($estanteriasOrigen ?? []);
                    const estanteriasDestino = @json($estanteriasDestino ?? []);

                    function actualizarEstanterias() {
                        const cantidadValue = parseInt(cantidad.value) || 0;

                        @if ($tipo !== 'entrada')
                            actualizarEstanteriasOrigen(cantidadValue);
                        @endif

                        @if ($tipo !== 'salida')
                            actualizarEstanteriasDestino(cantidadValue);
                        @endif
                    }

                    function actualizarEstanteriasOrigen(cantidadRequerida) {
                        if (!ubicacionOrigenId) return;

                        ubicacionOrigenId.innerHTML = '<option value="">Selecciona una estantería</option>';
                        estanteriasOrigen.forEach(estanteria => {
                            if (parseInt(estanteria.stock_producto) >= cantidadRequerida) {
                                const option = new Option(
                                    `Estanteria ${estanteria.nombre} - ${estanteria.zona} (Stock: ${estanteria.stock_producto})`,
                                    estanteria.id
                                );
                                ubicacionOrigenId.add(option);
                            }
                        });
                    }

                    function actualizarEstanteriasDestino(cantidadRequerida) {
                        if (!ubicacionDestinoId) return;

                        ubicacionDestinoId.innerHTML = '<option value="">Selecciona una estantería</option>';
                        estanteriasDestino.forEach(estanteria => {
                            if (parseInt(estanteria.capacidad_libre) >= cantidadRequerida) {
                                const option = new Option(
                                    `Estanteria ${estanteria.nombre} - ${estanteria.zona} (Espacio: ${estanteria.capacidad_libre})`,
                                    estanteria.id
                                );
                                ubicacionDestinoId.add(option);
                            }
                        });
                    }

                    cantidad.addEventListener('change', actualizarEstanterias);
                    actualizarEstanterias();
                });
            </script>
        @endpush
    </div>
</x-app-layout>

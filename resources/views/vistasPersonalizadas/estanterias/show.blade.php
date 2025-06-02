<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">DETALLES DE ESTANTERÍA</h1>
                <p>{{ $estanteria->nombre }} - {{ $estanteria->zona->nombre }}</p>
            </div>
            <div class="gap-2 col-12 col-md d-flex justify-content-end align-items-center">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('estanterias.edit', $estanteria) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal" data-id="{{ $estanteria->id }}"
                        data-nombre="{{ $estanteria->nombre }}">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                @endif
                <a href="{{ route('almacen.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-5 col-xl-4">
                <div class="shadow-sm card bg-dark">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="mb-0 text-white card-title d-flex align-items-center">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Información de la Estantería
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="mb-1 text-info small">Nombre:</label>
                            <p class="mb-0 text-white fs-5">{{ $estanteria->nombre }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="mb-1 text-info small">Zona:</label>
                            <p class="mb-0">
                                <span class="p-2 border badge text-bg-dark border-secondary">
                                    <i class="fas fa-map-marker-alt text-info me-1"></i>
                                    {{ $estanteria->zona->nombre }}
                                </span>
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="mb-1 text-info small">Capacidad:</label>
                            <div class="mb-1 d-flex justify-content-between align-items-center">
                                <span class="text-light">Disponible: <strong
                                        class="text-success fw-bold">{{ $estanteria->capacidad_libre }}</strong></span>
                                <span class="text-light">Total: <strong
                                        class="text-white">{{ $estanteria->capacidad_maxima }}</strong></span>
                            </div>
                            <div class="progress bg-secondary" style="height: 10px;">
                                @php
                                    $porcentajeOcupado =
                                        (($estanteria->capacidad_maxima - $estanteria->capacidad_libre) /
                                            $estanteria->capacidad_maxima) *
                                        100;
                                    $colorBarra =
                                        $porcentajeOcupado >= 90
                                            ? 'bg-danger'
                                            : ($porcentajeOcupado >= 70
                                                ? 'bg-warning'
                                                : 'bg-success');
                                @endphp
                                <div class="progress-bar {{ $colorBarra }}"
                                    style="width: {{ $porcentajeOcupado }}%">
                                </div>
                            </div>
                            <div class="mt-1 text-end small text-light">
                                {{ number_format($porcentajeOcupado, 0) }}% ocupado
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 text-info small">Estado:</label>
                            @if ($estanteria->capacidad_libre == 0)
                                <span class="badge text-bg-danger">Lleno</span>
                            @elseif ($porcentajeOcupado >= 90)
                                <span class="badge text-bg-danger">Casi lleno</span>
                            @elseif ($porcentajeOcupado >= 70)
                                <span class="badge text-bg-warning">Ocupación alta</span>
                            @elseif ($porcentajeOcupado > 0)
                                <span class="badge text-bg-success">Disponible</span>
                            @else
                                <span class="badge text-bg-secondary">Vacío</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7 col-xl-8">
                <div class="shadow-sm card bg-dark">
                    <div class="card-header bg-dark border-secondary d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white card-title d-flex align-items-center">
                            <i class="fas fa-boxes text-warning me-2"></i>
                            Productos en esta Estantería
                        </h5>
                        <span class="badge text-bg-primary">{{ $estanteria->productos->count() }} productos</span>
                    </div>
                    <div class="p-0 card-body">
                        @if ($estanteria->productos->isEmpty())
                            <div class="m-3 mb-3 alert alert-dark">
                                <i class="fas fa-info-circle me-2"></i>
                                <span class="text-light">No hay productos almacenados en esta estantería.</span>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-white">Producto</th>
                                            <th class="text-center text-white">Código</th>
                                            <th class="text-center text-white">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($estanteria->productos as $producto)
                                            <tr class="producto-row"
                                                data-href="{{ route('productos.show', $producto) }}"
                                                style="cursor: pointer;">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : asset('img/default-product.png') }}"
                                                                class="rounded" alt="{{ $producto->nombre }}"
                                                                width="40" height="40"
                                                                style="object-fit: cover;">
                                                        </div>
                                                        <div>
                                                            <div class="text-white fw-medium">{{ $producto->nombre }}
                                                            </div>
                                                            <div class="text-light small">
                                                                {{ $producto->categoria->nombre }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="border badge text-bg-dark border-secondary text-light">
                                                        {{ $producto->codigo_producto }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge text-bg-success">
                                                        {{ $producto->pivot->cantidad }} unidades
                                                    </span>
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

    @if (Auth::user()->rol === 'Administrador')

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="text-white modal-content bg-dark border-danger">
                    <div class="modal-header border-danger">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar la estantería <strong id="nombreEstanteria"></strong>?
                        </p>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span>Esta acción solo es posible si la estantería está vacía.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                
                const deleteModal = document.getElementById('deleteModal');
                if (deleteModal) {
                    deleteModal.addEventListener('show.bs.modal', function(event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        const nombre = button.getAttribute('data-nombre');

                        document.getElementById('nombreEstanteria').textContent = nombre;
                        document.getElementById('deleteForm').action = `{{ url('estanterias') }}/${id}`;
                    });
                }


                const productoRows = document.querySelectorAll('.producto-row');
                productoRows.forEach(row => {
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

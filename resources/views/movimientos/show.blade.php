<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">DETALLE DEL MOVIMIENTO</h1>
                <p>Información detallada del movimiento</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center">
                <a href="{{ route('movimientos.edit', $movimiento) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <a href="{{ route('movimientos.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-dark text-white shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Información del Movimiento</h5>

                        <div class="table-responsive mt-3">
                            <table class="table table-dark">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px">Fecha y Hora:</th>
                                        <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tipo:</th>
                                        <td>
                                            <span class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : 'danger' }}">
                                                {{ ucfirst($movimiento->tipo) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Usuario:</th>
                                        <td>{{ $movimiento->usuario?->name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Producto:</th>
                                        <td>{{ $movimiento->producto?->nombre ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Cantidad:</th>
                                        <td>{{ $movimiento->cantidad }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ubicación Origen:</th>
                                        <td>
                                            {{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre : $movimiento->origen_tipo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Ubicación Destino:</th>
                                        <td>
                                            {{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre : $movimiento->destino_tipo }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Creación:</th>
                                        <td>{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Última Actualización:</th>
                                        <td>{{ $movimiento->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('movimientos.destroy', $movimiento) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este movimiento?')">
                                    <i class="fas fa-trash"></i> Eliminar Movimiento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

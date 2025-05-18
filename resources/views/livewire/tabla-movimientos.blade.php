<div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-dark text-white border-secondary">
                    <i class="fas fa-search"></i>
                </span>
                <input wire:model.live="search" type="text" class="form-control bg-dark text-white border-secondary"
                    placeholder="Buscar por nombre de producto...">
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
                    <div class="dropdown-divider my-3"></div>
                    <div class="d-flex justify-content-between">
                        <button wire:click="limpiarFiltros" class="btn btn-sm btn-link text-white text-decoration-none">
                            <i class="fas fa-times me-1"></i>Limpiar
                        </button>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="dropdown">
                            <i class="fas fa-check me-1"></i>Aplicar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th wire:click="sortBy('fecha_movimiento')" style="cursor: pointer">
                        Fecha
                        @if ($sortField === 'fecha_movimiento')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </th>
                    <th>Tipo</th>
                    <th wire:click="sortBy('usuario')" style="cursor: pointer">
                        Usuario
                        @if ($sortField === 'usuario')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </th>
                    <th wire:click="sortBy('producto')" style="cursor: pointer">
                        Producto
                        @if ($sortField === 'producto')
                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                        @else
                            <i class="fas fa-sort"></i>
                        @endif
                    </th>
                    <th>Cantidad</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($movimientos->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-white">
                                <i class="fas fa-search fa-2x mb-3 d-block"></i>
                                No se encontraron movimientos que coincidan con la busqueda
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($movimientos as $movimiento)
                        <tr>
                            <td>{{ $movimiento->fecha_movimiento->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <span
                                    class="badge bg-{{ $movimiento->tipo === 'entrada' ? 'success' : ($movimiento->tipo === 'salida' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($movimiento->tipo) }}
                                </span>
                            </td>
                            <td>{{ $movimiento->usuario?->name ?? '—' }}</td>
                            <td>
                                {{ $movimiento->producto->nombre }}
                                @if ($movimiento->producto->trashed())
                                    <i class="fas fa-trash-alt text-danger ms-2" title="Producto eliminado"></i>
                                @endif
                            </td>
                            <td>{{ $movimiento->cantidad }}</td>
                            <td>{{ $movimiento->origen ? 'Estantería ' . $movimiento->origen->nombre . ' - ' . $movimiento->origen->zona->nombre : $movimiento->origen_tipo }}
                            </td>
                            <td>{{ $movimiento->destino ? 'Estantería ' . $movimiento->destino->nombre . ' - ' . $movimiento->destino->zona->nombre : $movimiento->destino_tipo }}
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('movimientos.show', $movimiento) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fa-regular fa-eye"></i>
                                    </a>
                                    <a href="{{ route('movimientos.edit', $movimiento) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-id="{{ $movimiento->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $movimientos->links() }}
    </div>
</div>

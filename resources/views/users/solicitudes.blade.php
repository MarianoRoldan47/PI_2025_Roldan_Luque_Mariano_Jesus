<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">SOLICITUDES DE ACCESO</h1>
                <p class="text-muted">Usuarios pendientes de aprobación</p>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="card bg-dark shadow-sm">
            @if ($pendingUsers->isEmpty())
                <div class="card-body text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                        <h4 class="text-white">No hay solicitudes pendientes</h4>
                        <p class="text-light mb-4">
                            Todas las solicitudes de acceso han sido procesadas.
                        </p>
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            <i class="fas fa-users me-1"></i> Ver todos los usuarios
                        </a>
                    </div>
                </div>
            @else
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th class="text-center">Teléfono</th>
                                    <th class="text-center">Fecha de Registro</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingUsers as $user)
                                    <tr class="user-row" data-href="{{ route('users.show', $user) }}"
                                        style="cursor: pointer;">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->imagen ? asset('storage/' . $user->imagen) : asset('img/default-profile.png') }}"
                                                    class="rounded-circle me-2" width="40" height="40" alt="{{ $user->name }}">
                                                <div>
                                                    <div class="fw-medium text-light">{{ $user->name }} {{ $user->apellido1 }} {{ $user->apellido2 }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-dark border border-secondary">
                                                {{ $user->dni }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-light">{{ $user->email }}</span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-phone text-info me-1"></i>
                                            <span class="text-light">{{ $user->telefono }}</span>
                                        </td>
                                        <td class="text-center">
                                            <i class="fas fa-calendar-alt text-info me-1"></i>
                                            <span class="text-light">{{ $user->created_at->format('d/m/Y H:i:s') }}</span>
                                        </td>
                                        <td class="text-end" onclick="event.stopPropagation();">
                                            <div class="d-flex justify-content-end gap-1">
                                                <form action="{{ route('users.aprobar', $user) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Aprobar usuario">
                                                        <i class="fas fa-check me-1"></i> Aprobar
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal" data-id="{{ $user->id }}"
                                                        data-name="{{ $user->name }}" title="Rechazar usuario">
                                                    <i class="fas fa-times me-1"></i> Rechazar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de confirmación para rechazar -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-white" id="rejectModalLabel">Confirmar rechazo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white">¿Estás seguro de que deseas rechazar la solicitud de <strong id="userName"></strong>?</p>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span class="fw-bold">Esta acción eliminará la solicitud de acceso y no se podrá deshacer.</span>
                    </div>
                </div>
                <div class="modal-footer border-danger">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Rechazar solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Manejo del modal de rechazo
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal) {
                rejectModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');

                    document.getElementById('userName').textContent = name;
                    document.getElementById('rejectForm').action = `{{ url('users') }}/${id}/rechazar`;
                });
            }

            // Hacer que las filas sean clickeables
            const userRows = document.querySelectorAll('.user-row');
            userRows.forEach(row => {
                row.addEventListener('click', function() {
                    window.location.href = this.getAttribute('data-href');
                });

                // Efecto visual al pasar el mouse
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

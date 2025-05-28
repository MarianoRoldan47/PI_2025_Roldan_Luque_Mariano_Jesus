<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">USUARIOS</h1>
                <p class="text-muted">Gestión de usuarios del sistema</p>
            </div>
            <div class="col-12 col-md-auto d-flex justify-content-end align-items-center gap-2">
                @if (Auth::user()->rol === 'Administrador')
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                    </a>

                    <a href="{{ route('users.solicitudes') }}" class="btn btn-info text-white">
                        <i class="fas fa-user-check me-2"></i>Solicitudes
                        @if ($usersPendientesAprobar > 0)
                            <span class="badge bg-danger ms-2">{{ $usersPendientesAprobar }}</span>
                        @endif
                    </a>
                @endif
            </div>
        </div>

        <div class="card bg-dark shadow-sm">
            <div class="card-body p-0">
                @if ($users->isEmpty())
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-users text-primary fa-4x mb-3"></i>
                            <h4 class="text-white">No hay usuarios registrados</h4>
                            <p class="text-light mb-4">
                                No se encontraron usuarios en el sistema.
                            </p>
                            @if (Auth::user()->rol === 'Administrador')
                                <a href="{{ route('users.create') }}" class="btn btn-primary">
                                    <i class="fas fa-user-plus me-2"></i> Crear nuevo usuario
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>DNI</th>
                                    <th>Email</th>
                                    <th class="text-center">Rol</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="user-row" data-href="{{ route('users.show', $user) }}"
                                        style="cursor: pointer;">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $user->imagen ? asset('storage/' . $user->imagen) : asset('img/default-profile.png') }}"
                                                    class="rounded-circle me-2" width="40" height="40" alt="{{ $user->name }}">
                                                <div>
                                                    <div class="fw-medium text-light">{{ $user->name }} {{ $user->apellido1 }} {{ $user->apellido2 }}</div>
                                                    <div class=" small"><i class="fas fa-phone me-2"></i>{{ $user->telefono }}</div>
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
                                            @if ($user->rol === 'Administrador')
                                                <span class="badge text-bg-danger">
                                                    <i class="fas fa-shield-alt me-1"></i>Administrador
                                                </span>
                                            @else
                                                <span class="badge text-bg-primary">
                                                    <i class="fas fa-user me-1"></i>Usuario
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($user->is_approved || $user->rol === 'Administrador')
                                                <span class="badge text-bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Aprobado
                                                </span>
                                            @else
                                                <span class="badge text-bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i>Pendiente
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm">
                                                @if (Auth::user()->rol === 'Administrador')
                                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if (Auth::id() !== $user->id)
                                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" data-id="{{ $user->id }}"
                                                                data-name="{{ $user->name }} {{ $user->apellido1 }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
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

    @if (Auth::user()->rol === 'Administrador')
        <!-- Modal de confirmación para eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white">
                    <div class="modal-header border-bottom border-danger">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            Confirmar eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center">
                            <div class="modal-icon bg-danger text-white rounded-circle p-3 me-3">
                                <i class="fas fa-trash fa-2x"></i>
                            </div>
                            <p class="mb-0">
                                ¿Estás seguro de que deseas eliminar al usuario <strong id="userName"></strong>?
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Manejo del modal de eliminación
                const deleteModal = document.getElementById('deleteModal');
                if (deleteModal) {
                    deleteModal.addEventListener('show.bs.modal', function (event) {
                        const button = event.relatedTarget;
                        const id = button.getAttribute('data-id');
                        const name = button.getAttribute('data-name');

                        document.getElementById('userName').textContent = name;
                        document.getElementById('deleteForm').action = `{{ url('users') }}/${id}`;
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
    @endif
</x-app-layout>

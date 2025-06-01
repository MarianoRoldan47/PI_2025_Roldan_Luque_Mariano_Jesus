<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">PERFIL DE USUARIO</h1>
                <p class="text-muted">Detalles de cuenta</p>
            </div>
            <div class="col-12 col-md d-flex justify-content-end align-items-center gap-2">
                @if (Auth::user()->rol === 'Administrador' || Auth::id() === $user->id)
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Editar
                    </a>
                @endif

                @if (Auth::user()->rol === 'Administrador' && Auth::id() !== $user->id)
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                        data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                @endif

                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Columna de información personal -->
            <div class="col-12 col-lg-4">
                <div class="card bg-dark shadow-sm">
                    <div class="card-body text-center">
                        <div class="position-relative mx-auto mb-3" style="width: 150px; height: 150px;">
                            <img src="{{ $user->imagen ? asset('storage/' . $user->imagen) : asset('img/default-profile.png') }}"
                                class="rounded-circle img-thumbnail bg-dark border-secondary"
                                style="width: 100%; height: 100%; object-fit: cover;" alt="{{ $user->name }}">

                            @if ($user->rol === 'Administrador')
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <i class="fas fa-star"></i>
                                </span>
                            @endif
                        </div>

                        <h4 class="mb-1 text-white">{{ $user->name }} {{ $user->apellido1 }} {{ $user->apellido2 }}
                        </h4>

                        <div class="mb-3">
                            @if ($user->rol === 'Administrador')
                                <span class="badge text-bg-danger">
                                    <i class="fas fa-shield-alt me-1"></i>Administrador
                                </span>
                            @else
                                <span class="badge text-bg-primary">
                                    <i class="fas fa-user me-1"></i>Usuario
                                </span>
                            @endif

                            @if ($user->is_approved)
                                <span class="badge text-bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Aprobado
                                </span>
                            @else
                                <span class="badge text-bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>Pendiente
                                </span>
                            @endif
                        </div>

                        <div class="alert alert-secondary border border-secondary text-start p-3 mb-0">
                            <div class="mb-2">
                                <i class="fas fa-id-card text-black me-2"></i>
                                <span>DNI:</span>
                                <span class="ms-1 fw-bold">{{ $user->dni }}</span>
                            </div>

                            <div class="mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <span>Email:</span>
                                <span class="ms-1 fw-bold">{{ $user->email }}</span>
                            </div>

                            <div class="mb-2">
                                <i class="fas fa-phone me-2"></i>
                                <span>Teléfono:</span>
                                <span class="ms-1 fw-bold">{{ $user->telefono }}</span>
                            </div>

                            <div class="mb-0">
                                <i class="fas fa-birthday-cake me-2"></i>
                                <span>Fecha de nacimiento:</span>
                                <span
                                    class="ms-1 fw-bold">{{ Date::parse($user->fecha_nacimiento)->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna de detalles de contacto y actividad -->
            <div class="col-12 col-lg-8">
                <div class="card bg-dark shadow-sm mb-4">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            Información de Contacto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-info small">Dirección</label>
                                <p class="mb-1 text-white">{{ $user->direccion }}</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-info small">Código Postal</label>
                                <p class="mb-1 text-white">{{ $user->codigo_postal }}</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-info small">Localidad</label>
                                <p class="mb-1 text-white">{{ $user->localidad }}</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-info small">Provincia</label>
                                <p class="mb-1 text-white">{{ $user->provincia }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card bg-dark shadow-sm">
                    <div class="card-header bg-dark border-secondary">
                        <h5 class="card-title mb-0 d-flex align-items-center text-white">
                            <i class="fas fa-history text-primary me-2"></i>
                            Información del Sistema
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-info small">Fecha de Registro</label>
                                <p class="mb-1 text-white">{{ $user->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-info small">Última Actualización</label>
                                <p class="mb-1 text-white">{{ $user->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>

                            @if ($user->is_approved)
                                <div class="col-md-6">
                                    <label class="form-label text-info small">Fecha de Aprobación</label>
                                    <p class="mb-1 text-white">
                                        {{ $user->approved_at ? $user->approved_at->format('d/m/Y H:i:s') : 'No disponible' }}
                                    </p>
                                </div>
                            @else
                                <div class="col-md-6">
                                    <label class="form-label text-info small">Estado de Aprobación</label>
                                    <p class="mb-1">
                                        <span class="badge text-bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>Pendiente de aprobación
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if (Auth::user()->rol === 'Administrador' && !$user->is_approved)
                    <div class="card bg-warning text-dark mt-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-bold">Usuario pendiente de aprobación</h5>
                                    <p class="mb-0 small">Este usuario no podrá acceder al sistema hasta que sea
                                        aprobado.</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('users.rechazar', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-times me-1"></i> Rechazar
                                        </button>
                                    </form>
                                    <form action="{{ route('users.aprobar', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-check me-1"></i> Aprobar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (Auth::user()->rol === 'Administrador' && Auth::id() !== $user->id)
        <!-- Modal de confirmación para eliminar -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-danger">
                    <div class="modal-header border-danger">
                        <h5 class="modal-title text-white" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-white">¿Estás seguro de que deseas eliminar al usuario
                            <strong>{{ $user->name }} {{ $user->apellido1 }}</strong>?</p>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="fw-bold">Esta acción eliminará permanentemente al usuario y todos sus datos
                                asociados.</span>
                        </div>
                    </div>
                    <div class="modal-footer border-danger">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <form action="{{ route('users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>

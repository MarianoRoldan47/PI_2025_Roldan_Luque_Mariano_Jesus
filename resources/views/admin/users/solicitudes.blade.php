<x-app-layout>
    <div class="container-fluid py-4">
        <h2 class="mb-4">Usuarios Pendientes de Aprobación</h2>

        <div class="card bg-dark border-0 shadow-sm">
            <div class="card-body">
                @if ($pendingUsers->isEmpty())
                    <p class="text-white-50">No hay usuarios pendientes de aprobación.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle">
                            <thead>
                                <tr class="text-center">
                                    <th>Imagen</th>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingUsers as $user)
                                    <tr>
                                        <td class="text-center align-middle">
                                            <img src="{{ $user->imagen ? asset('storage/' . $user->imagen) : asset('img/default-profile.png') }}"
                                                alt="imagen usuario" width="50" height="50" class="rounded-circle"/>
                                        </td>
                                        <td class="text-center align-middle">{{ $user->dni }}</td>
                                        <td class="text-center align-middle">{{ $user->name }}</td>
                                        <td class="text-center align-middle">{{ $user->email }}</td>
                                        <td class="text-center align-middle">{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td class="text-center align-middle">
                                            <form action="{{ route('admin.users.aprobar', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check me-1"></i>Aprobar
                                                </button>
                                            </form>
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
</x-app-layout>

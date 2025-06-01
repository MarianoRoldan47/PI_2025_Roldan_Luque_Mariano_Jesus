<x-app-layout>
    <div class="container-fluid px-2 px-sm-4 py-2 py-sm-4 h-100 d-flex flex-column">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3">NUEVO USUARIO</h1>
                <p class="text-muted">Crear un nuevo usuario en el sistema</p>
            </div>
            <div class="col-auto d-flex align-items-center">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="card bg-dark shadow-sm">
            <div class="card-header bg-dark border-secondary">
                <h5 class="card-title mb-0 d-flex align-items-center text-white">
                    <i class="fas fa-user-plus text-success me-2"></i>
                    Información del Usuario
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12 col-md-3 text-center">
                            <div class="mb-3">
                                <img id="preview-imagen" src="{{ asset('img/default-profile.png') }}"
                                    class="img-fluid rounded-circle border border-secondary mb-2" alt="Vista previa"
                                    style="width: 180px; height: 180px; object-fit: cover;">

                                <label for="imagen" class="form-label d-block text-white">Imagen de Perfil</label>
                                <input type="file"
                                    class="form-control bg-dark text-white border-secondary @error('imagen') is-invalid @enderror"
                                    id="imagen" name="imagen" accept="image/*">
                                <div class="form-text text-info small">
                                    Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB
                                </div>
                                @error('imagen')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-9">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h5 class="border-bottom border-secondary pb-2 text-info">Información Personal</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="dni" class="form-label text-white">DNI *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-white border-secondary">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-dark text-white border-secondary @error('dni') is-invalid @enderror"
                                            id="dni" name="dni" value="{{ old('dni') }}" required>
                                    </div>
                                    @error('dni')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label text-white">Nombre *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-white border-secondary">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-dark text-white border-secondary @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="apellido1" class="form-label text-white">Primer Apellido *</label>
                                    <input type="text"
                                        class="form-control bg-dark text-white border-secondary @error('apellido1') is-invalid @enderror"
                                        id="apellido1" name="apellido1" value="{{ old('apellido1') }}" required>
                                    @error('apellido1')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="apellido2" class="form-label text-white">Segundo Apellido *</label>
                                    <input type="text"
                                        class="form-control bg-dark text-white border-secondary @error('apellido2') is-invalid @enderror"
                                        id="apellido2" name="apellido2" value="{{ old('apellido2') }}" required>
                                    @error('apellido2')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha_nacimiento" class="form-label text-white">Fecha de Nacimiento
                                        *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-white border-secondary">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                        <input type="date"
                                            class="form-control bg-dark text-white border-secondary @error('fecha_nacimiento') is-invalid @enderror"
                                            id="fecha_nacimiento" name="fecha_nacimiento"
                                            value="{{ old('fecha_nacimiento') }}" required>
                                    </div>
                                    @error('fecha_nacimiento')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="telefono" class="form-label text-white">Teléfono *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-dark text-white border-secondary">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <input type="text"
                                            class="form-control bg-dark text-white border-secondary @error('telefono') is-invalid @enderror"
                                            id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                                    </div>
                                    @error('telefono')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h5 class="border-bottom border-secondary pb-2 mt-3 text-info">Dirección</h5>
                        </div>

                        <div class="col-12">
                            <label for="direccion" class="form-label text-white">Dirección *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-home"></i>
                                </span>
                                <input type="text"
                                    class="form-control bg-dark text-white border-secondary @error('direccion') is-invalid @enderror"
                                    id="direccion" name="direccion" value="{{ old('direccion') }}" required>
                            </div>
                            @error('direccion')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="codigo_postal" class="form-label text-white">Código Postal *</label>
                            <input type="text"
                                class="form-control bg-dark text-white border-secondary @error('codigo_postal') is-invalid @enderror"
                                id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal') }}" required>
                            @error('codigo_postal')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="localidad" class="form-label text-white">Localidad *</label>
                            <input type="text"
                                class="form-control bg-dark text-white border-secondary @error('localidad') is-invalid @enderror"
                                id="localidad" name="localidad" value="{{ old('localidad') }}" required>
                            @error('localidad')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="provincia" class="form-label text-white">Provincia *</label>
                            <input type="text"
                                class="form-control bg-dark text-white border-secondary @error('provincia') is-invalid @enderror"
                                id="provincia" name="provincia" value="{{ old('provincia') }}" required>
                            @error('provincia')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <h5 class="border-bottom border-secondary pb-2 mt-3 text-info">Información de Cuenta</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label text-white">Correo Electrónico *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email"
                                    class="form-control bg-dark text-white border-secondary @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="rol" class="form-label text-white">Rol *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-user-tag"></i>
                                </span>
                                <select
                                    class="form-select bg-dark text-white border-secondary @error('rol') is-invalid @enderror"
                                    id="rol" name="rol" required>
                                    <option value="Usuario" {{ old('rol') == 'Usuario' ? 'selected' : '' }}>Usuario
                                    </option>
                                    <option value="Administrador"
                                        {{ old('rol') == 'Administrador' ? 'selected' : '' }}>
                                        Administrador</option>
                                </select>
                            </div>
                            @error('rol')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label text-white">Contraseña *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password"
                                    class="form-control bg-dark text-white border-secondary @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label text-white">Confirmar
                                contraseña *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark text-white border-secondary">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control bg-dark text-white border-secondary"
                                    id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                    value="1" {{ old('is_approved', true) ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="is_approved">
                                    Usuario aprobado
                                </label>
                            </div>
                            <div class="form-text text-info small">
                                Un usuario no aprobado no podrá acceder al sistema.
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i>Crear Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Vista previa de imagen
                const inputImagen = document.getElementById('imagen');
                const previewImagen = document.getElementById('preview-imagen');

                if (inputImagen && previewImagen) {
                    inputImagen.addEventListener('change', function(e) {
                        if (e.target.files.length > 0) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                previewImagen.src = e.target.result;
                            };
                            reader.readAsDataURL(e.target.files[0]);
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>

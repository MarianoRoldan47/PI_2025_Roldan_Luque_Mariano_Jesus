<x-app-layout>
    <div class="py-4 py-sm-5">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Información del perfil -->
                    <div class="mb-4">
                        <livewire:profile.update-profile-information-form />
                    </div>

                    <!-- Actualizar contraseña -->
                    <div class="mb-4">
                        <livewire:profile.update-password-form />
                    </div>

                    <!-- Eliminar cuenta -->
                    <div class="mb-4">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

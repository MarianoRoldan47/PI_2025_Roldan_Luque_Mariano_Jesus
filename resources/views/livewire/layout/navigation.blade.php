<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
};
?>

<aside class="d-flex flex-column flex-shrink-0 bg-dark text-white" id="sidebar">
    <!-- Logo -->
    <a href="/" class="d-flex align-items-center justify-content-center mb-2 text-white text-decoration-none p-2">
        <div class="d-none d-sm-flex w-100">
            <img src="{{ asset('img/banner_sidebar.png') }}" class="img-fluid rounded-2" alt="CyberStock WMS">
        </div>
        <x-application-logo class="d-sm-none" style="width: 40px" />
    </a>

    <hr class="border-secondary my-2">

    <!-- Navegación -->
    <ul class="nav nav-pills flex-column mb-auto px-2">
        <li class="nav-item mb-1">
            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-house fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Home</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('movimientos.index') }}"
                class="nav-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-exchange-alt fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Movimientos</span>
            </a>
        </li>
        <li class="nav-item mb-1">
            <a href="{{ route('productos.index') }}"
                class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-tag fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Productos</span>
            </a>
        </li>
    </ul>

    <hr class="border-secondary my-2">

    <!-- Dropdown usuario -->
    <div class="dropdown px-2 mb-2">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()->imagen ? asset('storage/' . Auth::user()->imagen) : asset('img/default-profile.png') }}"
                alt="Usuario" width="32" height="32" class="rounded-circle me-2" />
            <strong class="d-none d-sm-block">{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item py-2" href="{{ route('perfil') }}">Perfil</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <button wire:click="logout" class="dropdown-item py-2 bg-danger d-flex align-items-center">
                    <span class="me-2">Cerrar Sesión</span>
                    <i class="fa-solid fa-right-from-bracket ms-auto"></i>
                </button>
            </li>
        </ul>
    </div>
</aside>

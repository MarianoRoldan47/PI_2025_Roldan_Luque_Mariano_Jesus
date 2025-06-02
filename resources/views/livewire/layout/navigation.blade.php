<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use App\Models\AlertaStock;
use App\Models\User;

new class extends Component {
    public $alertasCount = 0;
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }

    public function mount()
    {
        $this->alertasCount = AlertaStock::count();
    }
};
?>

<aside class="flex-shrink-0 text-white d-flex flex-column bg-dark" id="sidebar">

    <a href="/" class="p-2 mb-2 text-white d-flex align-items-center justify-content-center text-decoration-none">
        <div class="d-none d-sm-flex w-100">
            <img src="{{ asset('img/banner_sidebar.png') }}" class="img-fluid rounded-2" alt="CyberStock WMS">
        </div>
        <x-application-logo class="d-sm-none" style="width: 40px" />
    </a>

    <hr class="my-2 border-secondary">


    <ul class="px-2 mb-auto nav nav-pills flex-column">
        <li class="mb-1 nav-item">
            <a href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-house fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Home</span>
            </a>
        </li>


        <li class="mb-1 nav-item position-relative">
            <a href="{{ route('alertas.index') }}"
                class="nav-link {{ request()->routeIs('alertas.*') ? 'active' : '' }} text-white d-flex align-items-center py-2 alert-link">
                <div class="position-relative">
                    <i
                        class="fa-solid fa-bell fa-fw {{ $alertasCount > 0 ? 'text-warning animate__animated animate__headShake animate__infinite animate__slower' : '' }}"></i>
                    @if ($alertasCount > 0)
                        <span
                            class="top-0 position-absolute start-100 translate-middle badge rounded-pill bg-danger animate__animated animate__pulse animate__infinite">
                            {{ $alertasCount }}
                            <span class="visually-hidden">alertas de stock</span>
                        </span>
                    @endif
                </div>
                <span class="d-none d-sm-block ms-3">Alertas Stock</span>
            </a>
        </li>

        <li class="mb-1 nav-item">
            <a href="{{ route('movimientos.index') }}"
                class="nav-link {{ request()->routeIs('movimientos.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-exchange-alt fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Movimientos</span>
            </a>
        </li>

        <li class="mb-1 nav-item">
            <a href="{{ route('productos.index') }}"
                class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-tag fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Productos</span>
            </a>
        </li>

        <li class="mb-1 nav-item">
            <a href="{{ route('almacen.index') }}"
                class="nav-link {{ request()->routeIs('almacen.*') || request()->routeIs('estanterias.*') || request()->routeIs('zonas.*') ? 'active' : '' }}  text-white d-flex align-items-center py-2">
                <i class="fas fa-warehouse fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Almacén</span>
            </a>
        </li>
        <li class="mb-1 nav-item">
            <a href="{{ route('categorias.index') }}"
                class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                <i class="fa-solid fa-sitemap fa-fw"></i>
                <span class="d-none d-sm-block ms-3">Categorías</span>
            </a>
        </li>

        @if (Auth::user()->rol === 'Administrador')
            <li class="px-3 mt-3 mb-2">
                <span class="text-primary text-uppercase small fw-bold d-none d-sm-block">Administración</span>
                <span class="text-primary small fw-bold d-sm-none">
                    <i class="fa-solid fa-shield-alt fa-fw"></i>
                </span>
            </li>

            <li class="mb-1 nav-item">
                <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }} text-white d-flex align-items-center py-2">
                    <i class="fa-solid fa-users fa-fw"></i>
                    <span class="d-none d-sm-block ms-3">Usuarios</span>

                    @php
                        $pendingUsers = User::where('is_approved', false)->where('rol', 'Usuario')->count();
                    @endphp

                    @if ($pendingUsers > 0)
                        <span class="badge bg-danger rounded-pill ms-auto d-none d-sm-inline">
                            {{ $pendingUsers }}
                        </span>
                        <span
                            class="top-0 position-absolute start-100 translate-middle badge rounded-pill bg-danger d-sm-none">
                            {{ $pendingUsers }}
                        </span>
                    @endif
                </a>
            </li>
        @endif
    </ul>

    <hr class="my-2 border-secondary">


    <div class="px-2 mb-2 dropdown">
        <a href="#" class="text-white d-flex align-items-center text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{ Auth::user()->imagen ? asset('storage/' . Auth::user()->imagen) : asset('img/default-profile.png') }}"
                alt="Usuario" width="32" height="32" class="rounded-circle me-2" />
            <strong class="d-none d-sm-block">{{ Auth::user()->name }}</strong>
        </a>
        <ul class="shadow dropdown-menu dropdown-menu-dark text-small">
            <li><a class="py-2 dropdown-item" href="{{ route('perfil') }}">Perfil</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <button wire:click="logout" class="py-2 dropdown-item bg-danger d-flex align-items-center">
                    <span class="me-2">Cerrar Sesión</span>
                    <i class="fa-solid fa-right-from-bracket ms-auto"></i>
                </button>
            </li>
        </ul>
    </div>
</aside>


@push('styles')
    <style>
        @media (max-width: 576px) {
            #sidebar {
                width: 4.5rem;
            }

            .content-wrapper {
                margin-left: 4.5rem;
            }
        }

        @media (min-width: 577px) {
            #sidebar {
                width: 240px;
            }

            .content-wrapper {
                margin-left: 240px;
            }
        }
    </style>
@endpush

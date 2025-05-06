<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<!-- Sidebar -->
<aside class="d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" id="sidebar">
    <!-- Logo / Título -->
    <a href="/" class="d-flex align-items-center justify-content-center mb-2 text-white text-decoration-none">
        <div class="d-none d-sm-flex">
            <img src="{{ asset('img/banner_sidebar.png') }}" class="rounded-2" style="width: 250px" alt="CyberStock WMS">
        </div>
        <x-application-logo class="rounded-2 d-sm-none" style="width: 50px" />

    </a>

    <hr class="border-secondary">

    <!-- Navegación -->
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link active text-white d-flex align-items-center justify-content-sm-start" aria-current="page">
                <div class="d-flex justify-content-center align-items-center" style="width: 20px;">
                    <i class="fa-solid fa-house"></i>
                </div>
                <p class="d-sm-block d-none m-0 ms-3">Home</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center justify-content-sm-start" aria-current="page">
                <div class="d-flex justify-content-center align-items-center" style="width: 20px;">
                    <i class="fa-solid fa-exchange-alt"></i>
                </div>
                <p class="d-sm-block d-none m-0 ms-3">Movimientos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center justify-content-sm-start" aria-current="page">
                <div class="d-flex justify-content-center align-items-center" style="width: 20px;">
                    <i class="fa-solid fa-tag"></i>
                </div>
                <p class="d-sm-block d-none m-0 ms-3">Productos</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center justify-content-sm-start" aria-current="page">
                <div class="d-flex justify-content-center align-items-center" style="width: 20px;">
                    <i class="fa-solid fa-map-marker-alt"></i>
                </div>
                <p class="d-sm-block d-none m-0 ms-3">Zonas</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white d-flex align-items-center justify-content-sm-start" aria-current="page">
                <div class="d-flex justify-content-center align-items-center" style="width: 20px;">
                    <i class="fa-solid fa-users"></i>
                </div>
                <p class="d-sm-block d-none m-0 ms-3">Usuarios</p>
            </a>
        </li>
    </ul>

    <hr class="border-secondary">

    <!-- Dropdown usuario -->
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32"
                class="rounded-circle me-2" />
            <strong class="d-sm-block d-none">Mariano Roldan Luque</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</aside>

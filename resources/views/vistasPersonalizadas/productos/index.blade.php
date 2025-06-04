<x-app-layout>
    <div class="px-2 py-2 container-fluid px-sm-4 py-sm-4 h-100 d-flex flex-column">
        <div class="mb-4 row">
            <div class="col">
                <h1 class="h3">PRODUCTOS</h1>
                <p>Catálogo de productos</p>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="gap-2 d-flex flex-column">
                    <a href="{{ route('productos.pdf.inventario') }}" target="_blank" class="btn btn-info w-100">
                        <i class="fas fa-file-pdf me-2"></i>Generar PDF
                    </a>
                    <a href="{{ route('productos.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus-circle me-2"></i>Nuevo Producto
                    </a>
                </div>
            </div>
        </div>

        <div class="text-white shadow-sm card bg-dark">
            <div class="p-2 card-body p-sm-3">
                @livewire('tabla-productos')
            </div>
        </div>
    </div>
</x-app-layout>

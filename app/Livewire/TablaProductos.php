<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Categoria;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Route;

class TablaProductos extends Component
{
    use WithPagination;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $search = '';
    public $categoriaSeleccionada = null;
    public $stockFilter = 'todos';
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (request()->has('categoria_id')) {
            $this->categoriaSeleccionada = request()->categoria_id;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoriaSeleccionada()
    {
        $this->resetPage();
    }

    public function updatingStockFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['categoriaSeleccionada', 'stockFilter', 'search']);
    }

    public function render()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        $productosQuery = Producto::with(['categoria', 'estanterias'])
            ->when($this->sortField === 'categoria', function ($query) {
                $query->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                    ->orderBy('categorias.nombre', $this->sortDirection)
                    ->select('productos.*');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('productos.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('productos.codigo_producto', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->categoriaSeleccionada, function ($query) {
                $query->where('categoria_id', $this->categoriaSeleccionada);
            })
            ->when($this->stockFilter !== 'todos', function ($query) {
                if ($this->stockFilter === 'disponibles') {
                    $query->where('stock_total', '>', 0);
                } elseif ($this->stockFilter === 'agotados') {
                    $query->where('stock_total', '=', 0);
                } elseif ($this->stockFilter === 'bajoMinimo') {
                    $query->whereRaw('stock_total < stock_minimo_alerta AND stock_total > 0');
                }
            })
            ->when($this->sortField !== 'categoria', function ($query) {
                $query->orderBy($this->sortField, $this->sortDirection);
            });

        return view('livewire.tabla-productos', [
            'productos' => $productosQuery->get(),
            'categorias' => $categorias
        ]);
    }
}
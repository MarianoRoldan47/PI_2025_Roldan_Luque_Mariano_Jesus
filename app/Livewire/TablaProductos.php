<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Producto;
use Livewire\WithPagination;

class TablaProductos extends Component
{
    use WithPagination;

    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
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

    public function render()
    {
        return view('livewire.tabla-productos', [
            'productos' => Producto::with(['categoria', 'estanterias'])
                ->when($this->sortField === 'categoria', function ($query) {
                    $query->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
                        ->orderBy('categorias.nombre', $this->sortDirection)
                        ->select('productos.*');
                })
                ->where(function ($query) {
                    $query->where('productos.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('productos.codigo_producto', 'like', '%' . $this->search . '%');
                })
                ->when($this->sortField !== 'categoria', function ($query) {
                    $query->orderBy($this->sortField, $this->sortDirection);
                })->get()
        ]);
    }
}

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Movimiento;
use Livewire\WithPagination;

class TablaMovimientos extends Component
{
    use WithPagination;

    public $sortField = 'fecha_movimiento';
    public $sortDirection = 'desc';
    public $search = '';
    public $tiposFiltrados = [];
    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTipoFiltro()
    {
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->tiposFiltrados = [];
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
        return view('livewire.tabla-movimientos', [
            'movimientos' => Movimiento::with(['producto', 'usuario', 'origen', 'destino'])
                ->when($this->search, function ($query) {
                    $query->whereHas('producto', function ($q) {
                        $q->where('nombre', 'like', '%' . $this->search . '%');
                    });
                })
                ->when(count($this->tiposFiltrados) > 0, function ($query) {
                    $query->whereIn('movimientos.tipo', $this->tiposFiltrados); // Especificamos la tabla
                })
                ->when($this->sortField === 'producto', function ($query) {
                    $query->join('productos', 'movimientos.producto_id', '=', 'productos.id')
                        ->orderBy('productos.nombre', $this->sortDirection)
                        ->select('movimientos.*');
                })
                ->when($this->sortField === 'usuario', function ($query) {
                    $query->join('users', 'movimientos.user_id', '=', 'users.id')
                        ->orderBy('users.name', $this->sortDirection)
                        ->select('movimientos.*');
                })
                ->when($this->sortField === 'fecha_movimiento', function ($query) {
                    $query->orderBy('movimientos.fecha_movimiento', $this->sortDirection); // También especificamos aquí la tabla
                })
                ->paginate(10)
        ]);
    }
}

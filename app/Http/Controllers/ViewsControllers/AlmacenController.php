<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Estanteria;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estanterias = Estanteria::with('zona')->get();
        return view('vistasPersonalizadas.almacen.index', compact('estanterias'));
    }
}
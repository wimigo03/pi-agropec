<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paises;
use App\Models\PiCliente;

class PiClienteController extends Controller
{
    public function index()
    {
        $paises = Paises::where('estado','1')->pluck('nombre','id');
        $estados = PiCliente::ESTADOS;
        $clientes = PiCliente::orderBy('id','desc')->paginate(10);
        return view('clientes.index', compact('paises','estados','clientes'));
    }

    public function search(Request $request)
    {
        $paises = Paises::where('estado','1')->pluck('nombre','id');
        $estados = PiCliente::ESTADOS;
        $clientes = PiCliente::query()
                                ->byCodigo($request->codigo)
                                ->byPais($request->pais_id)
                                ->byFecha($request->fecha)
                                ->byRazonSocial($request->razon_social)
                                ->byNombre($request->nombre)
                                ->byNit($request->nit)
                                ->byEstado($request->estado)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('clientes.index', compact('paises','estados','clientes'));
    }

    public function create()
    {
        dd("create");
    }

    public function store(Request $request)
    {
        dd("store");
    }
}

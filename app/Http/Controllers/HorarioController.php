<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use Auth;

class HorarioController extends Controller
{
    public function index()
    {
        //$cargos = Cargo::where('empresa_id',Auth::user()->empresa_id)->get();
        //$personal = Personal::paginate(10);
        //return view('personal.index', compact('personal'));
    }

    public function detalleHorario($horario_id)
    {
        try {
            $datos = HorarioDetalle::where('horario_id', $horario_id)->get();
            return response()->json($datos);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

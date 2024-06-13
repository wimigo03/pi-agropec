<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Centro;
use App\Models\SubCentro;
use Auth;

class CentroController extends Controller
{
    const ICONO = 'fas fa-donate fa-fw';
    const INDEX = 'CENTROS CONTABLES';
    const CREATE = 'REGISTRAR CENTRO CONTABLE';
    const EDITAR = 'MODIFICAR CENTRO CONTABLE';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('centro.contable.index',['empresa_id' => Auth::user()->empresa_id]);
        }
        return view('centro_contable.indexAfter', compact('empresas'));
    }

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $centros = Centro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->select('nombre')
                                ->groupBy('nombre')
                                ->pluck('nombre','nombre');
        $_subcentros = SubCentro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->select('nombre')
                                ->groupBy('nombre')
                                ->pluck('nombre','nombre');
        $tipos = SubCentro::TIPOS;
        $estados = SubCentro::ESTADOS;
        $sub_centros = SubCentro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->orderBy('id', 'desc')
                                ->paginate(10);
        return view('centros.index', compact('icono','header','empresas','centros','_subcentros','tipos','estados','sub_centros'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $centros = Centro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->select('nombre')
                                ->groupBy('nombre')
                                ->pluck('nombre','nombre');
        $_subcentros = SubCentro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->select('nombre')
                                ->groupBy('nombre')
                                ->pluck('nombre','nombre');
        $tipos = SubCentro::TIPOS;
        $estados = SubCentro::ESTADOS;
        $sub_centros = SubCentro::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->byCentroText($request->centro)
                                ->bySubCentroText($request->sub_centro)
                                ->byCodigo($request->codigo)
                                ->byTipo($request->tipo)
                                ->byCreacion($request->fecha)
                                ->byEstado($request->estado)
                                ->orderBy('id', 'desc')
                                ->paginate(10);
        return view('centros.index', compact('icono','header','empresas','centros','_subcentros','tipos','estados','sub_centros'));
    }

    public function create()
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        return view('centros.create', compact('icono','header','empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:centros,nombre,null,id,empresa_id,' . $request->empresa_id
        ]);
        try{
            $_centro = [
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'nombre' => $request->nombre,
                'abreviatura' => $request->abreviatura,
                '_create' => date('Y-m-d'),
                'estado' => '1'
            ];

            $centro = Centro::create($_centro);

            $_sub_centro = [
                'centro_id' => $centro->id,
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'nombre' => 'GASTO GENERAL',
                'abreviatura' => 'GG',
                '_create' => date('Y-m-d'),
                'tipo' => '1',
                'estado' => '1'
            ];

            $sub_centro = SubCentro::create($_sub_centro);

            return redirect()->route('centros.index')->with('success_message', 'Centro registrado exitosamente.');
        } catch (ValidationException $e) {
            return redirect()->route('centros.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

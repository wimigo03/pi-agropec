<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Sucursal;
use Auth;

class SucursalController extends Controller
{
    const ICONO = 'fa-solid fa-house-damage fa-fw';
    const INDEX = 'SUCURSAL';
    const CREATE = 'REGISTRAR SUCURSAL';
    const EDITAR = 'MODIFICAR SUCURSAL';
    const SHOW = 'DETALLE SUCURSAL';

    public function indexAfter()
    {
        $empresas_info = Empresa::where('pi_cliente_id',Auth::user()->pi_cliente_id)->get();
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('sucursal.index',Auth::user()->empresa_id);
        }
        return view('sucursal.indexAfter', compact('empresas_info','empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $estados = Sucursal::ESTADOS;
        $sucursales = Sucursal::query()
                                ->byEmpresa($empresa_id)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('sucursal.index', compact('icono','header','empresa','estados','sucursales'));
    }

    public function search(Request $request)
    {
        $empresa = Empresa::find($request->empresa_id);
        $estados = Sucursal::ESTADOS;
        $sucursales = Sucursal::query()
                                ->byEmpresa($request->empresa_id)
                                ->bySucursalId($request->sucursal_id)
                                ->bySucursal($request->sucursal)
                                ->byDireccion($request->direccion)
                                ->byTelefono($request->telefono)
                                ->byEstado($request->estado)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('sucursal.index', compact('empresa','estados','sucursales'));
    }

    public function create($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($id);
        return view('sucursal.create', compact('icono','header','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required',
            'nombre' => 'required|unique:sucursales,nombre,null,id,empresa_id,' . $request->empresa_id,
            'ciudad' => 'required',
            'direccion' => 'required',
            'foto' => 'nullable|file|mimes:png|max:2048',
        ]);
        try{
            $sucursal = Sucursal::create([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'nombre' => $request->nombre,
                'ciudad' => $request->ciudad,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'estado' => '1'
            ]);

            return redirect()->route('sucursal.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó una sucursal en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('sucursal.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $sucursal = Sucursal::find($id);
        $empresa = Empresa::find($sucursal->empresa_id);
        return view('sucursal.editar', compact('icono','header','sucursal','empresa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required',
            'nombre' => 'required|unique:sucursales,nombre,' . $request->sucursal_id . ',id,empresa_id,' . $request->empresa_id,
            'ciudad' => 'required',
            'direccion' => 'required',
            'foto' => 'nullable|file|mimes:png|max:2048',
        ]);
        try{
            $sucursal = Sucursal::find($request->sucursal_id);
            $sucursal->update([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'nombre' => $request->nombre,
                'ciudad' => $request->ciudad,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'estado' => $request->estado
            ]);

            return redirect()->route('sucursal.search',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó una sucursal en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('sucursal.editar',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

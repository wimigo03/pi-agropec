<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Zona;
use App\Models\Sucursal;
use Auth;

class ZonaController extends Controller
{
    const ICONO = 'fa-solid fa-house-laptop fa-fw';
    const INDEX = 'ZONA';
    const REGISTRAR = 'REGISTRAR ZONA';
    const EDITAR = 'MODIFICAR ZONA';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('zonas.indexEmpresa',Auth::user()->empresa_id);
        }
        return view('zonas.indexAfter', compact('empresas'));
    }

    public function indexEmpresa($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = Zona::ESTADOS;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $empresa = Empresa::find($empresa_id);
        $zonas = Zona::where('empresa_id',$empresa_id)->paginate(10);
        return view('zonas.indexEmpresa', compact('icono','header','estados','sucursales','empresa','zonas'));
    }

    public function searchByEmpresa(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = Zona::ESTADOS;
        $sucursales = Sucursal::where('empresa_id',$request->empresa_id)->pluck('nombre','id');
        $empresa = Empresa::find($request->empresa_id);
        $zonas = Zona::query()
                        ->byEmpresa($request->empresa_id)
                        ->bySucursal($request->sucursal_id)
                        ->byCodigo($request->codigo)
                        ->byNombre($request->nombre)
                        ->byCantidadMesas($request->mesas)
                        ->byDetalle($request->detalle)
                        ->byEstado($request->estado)
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('zonas.indexEmpresa', compact('icono','header','estados','sucursales','empresa','zonas'));
    }

    public function index($sucursal_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $sucursal = Sucursal::find($sucursal_id);
        $estados = Zona::ESTADOS;
        $empresa = Empresa::find($sucursal->empresa_id);
        $zonas = Zona::where('sucursal_id',$sucursal_id)->paginate(10);
        return view('zonas.index', compact('icono','header','sucursal','estados','empresa','zonas'));
    }

    public function search(Request $request){
        $icono = self::ICONO;
        $header = self::INDEX;
        $sucursal = Sucursal::find($request->sucursal_id);
        $estados = Zona::ESTADOS;
        $empresa = Empresa::find($sucursal->empresa_id);
        $zonas = Zona::query()
                        ->byEmpresa($request->empresa_id)
                        ->bySucursal($request->sucursal_id)
                        ->byCodigo($request->codigo)
                        ->byNombre($request->nombre)
                        ->byCantidadMesas($request->mesas)
                        ->byDetalle($request->detalle)
                        ->byEstado($request->estado)
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('zonas.index', compact('icono','header','sucursal','estados','empresa','zonas'));
    }

    public function create($sucursal_id)
    {
        $icono = self::ICONO;
        $header = self::REGISTRAR;
        $sucursal = Sucursal::where('id',$sucursal_id)->first();
        $empresa = Empresa::find($sucursal->empresa_id);
        return view('zonas.create', compact('icono','header','sucursal','empresa'));
    }

    public function createBySucursal($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::REGISTRAR;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $empresa = Empresa::find($empresa_id);
        return view('zonas.createBySucursal', compact('icono','header','sucursales','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:zonas,nombre,null,id,sucursal_id,' . $request->sucursal_id,
            'codigo' => 'required|min:2|max:3'
        ]);
        $sucursal = Sucursal::find($request->sucursal_id);
        try{
            $datos = [
                'sucursal_id' => $request->sucursal_id,
                'empresa_id' => $sucursal->empresa_id,
                'pi_cliente_id' => $sucursal->pi_cliente_id,
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'mesas_disponibles' => 0,
                'detalle' => $request->detalle,
                'filas' => $request->filas,
                'columnas' => $request->columnas,
                'estado' => '1'
            ];

            $zona = Zona::create($datos);

            return redirect()->route('zonas.index', ['sucursal_id' => $request->sucursal_id])->with('success_message', 'Se agregó una <b>[ZONA]</b> en la sucursal seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.create', $request->sucursal_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function storeBySucursal(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required',
            'nombre' => 'required|unique:zonas,nombre,null,id,sucursal_id,' . $request->sucursal_id,
            'codigo' => 'required|min:2|max:3'
        ]);
        $sucursal = Sucursal::find($request->sucursal_id);
        try{
            $datos = [
                'sucursal_id' => $request->sucursal_id,
                'empresa_id' => $sucursal->empresa_id,
                'pi_cliente_id' => $sucursal->pi_cliente_id,
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'mesas_disponibles' => 0,
                'detalle' => $request->detalle,
                'filas' => $request->filas,
                'columnas' => $request->columnas,
                'estado' => '1'
            ];

            $zona = Zona::create($datos);

            return redirect()->route('zonas.indexEmpresa', $sucursal->empresa_id)->with('success_message', 'Se agregó una <b>[ZONA]</b> en la sucursal seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.create', $request->sucursal_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $zona = Zona::find($id);
        $sucursal = Sucursal::where('id',$zona->sucursal_id)->first();
        $empresa = Empresa::find($sucursal->empresa_id);
        return view('zonas.editar', compact('icono','header','zona','sucursal','empresa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:zonas,nombre,' . $request->zona_id . ',id,sucursal_id,' . $request->sucursal_id,
            'codigo' => 'required|min:2|max:3'
        ]);
        try{
            $zona = Zona::find($request->zona_id);
            $datos = [
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'detalle' => $request->detalle
            ];

            $zona->update($datos);

            return redirect()->route('zonas.index', ['sucursal_id' => $request->sucursal_id])->with('info_message', 'Se modifico la [ZONA] en la sucursal seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.editar', $request->zona_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id)
    {
        try{
            $zona = Zona::find($id);
            $zona->update([
                'estado' => '1'
            ]);
            return redirect()->route('zonas.index',$zona->sucursal_id)->with('info_message', 'Se Habilito el [ZONA] seleccionada...');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.index',$zona->sucursal_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id)
    {
        try{
            $zona = Zona::find($id);
            $zona->update([
                'estado' => '2'
            ]);
            return redirect()->route('zonas.index',$zona->sucursal_id)->with('info_message', 'Se Deshabilito el [ZONA] seleccionada...');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.index',$zona->sucursal_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitarBySucursal($id)
    {
        try{
            $zona = Zona::find($id);
            $zona->update([
                'estado' => '1'
            ]);
            return redirect()->route('zonas.indexEmpresa',$zona->empresa_id)->with('info_message', 'Se Habilito el [ZONA] seleccionada...');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.indexEmpresa',$zona->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitarBySucursal($id)
    {
        try{
            $zona = Zona::find($id);
            $zona->update([
                'estado' => '2'
            ]);
            return redirect()->route('zonas.indexEmpresa',$zona->empresa_id)->with('info_message', 'Se Deshabilito el [ZONA] seleccionada...');
        } catch (ValidationException $e) {
            return redirect()->route('zonas.indexEmpresa',$zona->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function getDatosBySucursal(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $zonas = Zona::where('sucursal_id', $id)->where('estado','1')->get()->toJson();
            if($zonas){
                return response()->json([
                    'zonas' => $zonas
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

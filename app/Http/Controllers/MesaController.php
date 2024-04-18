<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Zona;
use App\Models\Sucursal;
use App\Models\Mesa;
use Illuminate\Validation\Rule;
use DataTables;
use Auth;
use DB;

class MesaController extends Controller
{
    const ICONO = 'fas fa-utensils fa-fw';
    const INDEX = 'MESAS';
    const REGISTRAR = 'REGISTRAR MESA';
    const EDITAR = 'MODIFICAR MESA';
    const SETTING = 'CONFIGURAR MESAS';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('mesas.index',Auth::user()->empresa_id);
        }
        return view('mesas.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $estados = Zona::ESTADOS;
        $empresa = Empresa::find($empresa_id);
        $mesas = Mesa::query()
                        ->byEmpresa($empresa_id)
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('mesas.index', compact('icono','header','sucursales','estados','empresa','mesas'));
    }

    public function indexAjax(Request $request){
        $mesas = Mesa::query()
                        ->byEmpresa($request->empresa_id)
                        ->with(['sucursal','zona'])
                        ->get();
        $mesas = $mesas->map(function ($mesa) {
            $mesa->estado_mesa = $mesa->status;
            return $mesa;
        });
        return DataTables::of($mesas)
                            ->addColumn('nombre_sucursal', function ($mesa) {
                                return $mesa->sucursal ? $mesa->sucursal->nombre : '-';
                            })
                            ->addColumn('nombre_zona', function ($mesa) {
                                return $mesa->zona ? $mesa->zona->nombre : '-';
                            })
                            ->toJson();
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $sucursales = Sucursal::where('empresa_id',$request->empresa_id)->pluck('nombre','id');
        $estados = Zona::ESTADOS;
        $empresa = Empresa::find($request->empresa_id);
        $mesas = Mesa::query()
                        ->byEmpresa($request->empresa_id)
                        ->bySucursal($request->sucursal_id)
                        ->byZona($request->zona_id)
                        ->byNumero($request->numero)
                        ->bySillas($request->sillas)
                        ->byDetalle($request->detalle)
                        ->byEstado($request->estado)
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('mesas.index', compact('icono','header','sucursales','estados','empresa','mesas'));
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::REGISTRAR;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->where('estado','1')->pluck('nombre','id');
        $empresa = Empresa::find($empresa_id);
        return view('mesas.create', compact('icono','header','sucursales','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required',
            'zona_id' => 'required',
            'numero' => [
                'required',
                Rule::unique('mesas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id)
                                 ->where('zona_id', $request->zona_id);
                }),
            ],
            'sillas' => 'required'
        ]);
        try{
            $datos = [
                'zona_id' => $request->zona_id,
                'sucursal_id' => $request->sucursal_id,
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $request->cliente_id,
                'numero' => $request->numero,
                'sillas' => $request->sillas,
                'detalle' => $request->detalle,
                'estado' => '1'
            ];

            $zona = Mesa::create($datos);

            $zona = Zona::find($request->zona_id);
            $zona->update([
                'mesas_disponibles' => $zona->mesas_disponibles + 1
            ]);

            return redirect()->route('mesas.index', ['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó una [MESA] en la sucursal seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('mesas.create', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $mesa = Mesa::find($id);
        $icono = self::ICONO;
        $header = self::EDITAR;
        $sucursales = Sucursal::where('empresa_id',$mesa->empresa_id)->where('estado','1')->get();
        $empresa = Empresa::find($mesa->empresa_id);
        return view('mesas.editar', compact('mesa','icono','header','sucursales','empresa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required',
            'zona_id' => 'required',
            'numero' => [
                'required',
                Rule::unique('mesas')->where(function ($query) use ($request) {
                    return $query->where('sucursal_id', $request->sucursal_id)
                                 ->where('zona_id', $request->zona_id)
                                 ->whereNotIn('id', [$request->mesa_id]);;
                }),
            ],
            'sillas' => 'required'
        ]);
        try{
            $mesa = Mesa::find($request->mesa_id);
            $datos = [
                'zona_id' => $request->zona_id,
                'sucursal_id' => $request->sucursal_id,
                'numero' => $request->numero,
                'sillas' => $request->sillas,
                'detalle' => $request->detalle
            ];

            $mesa->update($datos);

            return redirect()->route('mesas.index', ['empresa_id' => $request->empresa_id])->with('success_message', 'Se Modificó una [MESA] en la sucursal seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('mesas.editar', $request->mesa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id)
    {
        try{
            $mesa = Mesa::find($id);
            $mesa->update([
                'estado' => '1'
            ]);

            $zona = Zona::find($mesa->zona_id);
            $zona->update([
                'mesas_disponibles' => $zona->mesas_disponibles + 1
            ]);
            return redirect()->back()->with('info_message', 'Se Habilito una [MESA] seleccionada...')->withInput();
        } catch (ValidationException $e) {
            return redirect()->route('mesas.index',$mesa->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id)
    {
        try{
            $mesa = Mesa::find($id);
            $mesa->update([
                'estado' => '2'
            ]);

            $zona = Zona::find($mesa->zona_id);
            $zona->update([
                'mesas_disponibles' => $zona->mesas_disponibles - 1
            ]);
            return redirect()->back()->with('info_message', 'Se Deshabilito una [MESA] seleccionada...')->withInput();
        } catch (ValidationException $e) {
            return redirect()->route('mesas.index',$mesa->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function setting($sucursal_id)
    {
        $icono = self::ICONO;
        $header = self::SETTING;
        $sucursal = Sucursal::find($sucursal_id);
        $zonas = Zona::where('sucursal_id',$sucursal_id)->where('estado','1')->get();
        $empresa = Empresa::find($sucursal->empresa_id);
        return view('mesas.set  ting', compact('icono','header','zonas','empresa'));
    }

    public function getMesasByZona(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $mesas = Mesa::select(DB::raw("concat('MESA N° ',numero,' (',sillas,' SILLAS)') as numero_sillas"),'id')
                            ->where('zona_id', $id)
                            ->where('estado','1')
                            ->get()
                            ->toJson();
            if($mesas){
                return response()->json([
                    'mesas' => $mesas
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeCargarMesa(Request $request)
    {
        try{
            $mesa = Mesa::find($request->mesa_id);
            $mesa->update([
                'fila' => $request->fila,
                'columna' => $request->columna,
                'estado' => 3
            ]);

            return redirect()->route('mesas.setting', ['sucursal_id' => $mesa->sucursal_id])->with('success_message', '[MESA AGREGADA]');
        } catch (ValidationException $e) {
            return redirect()->route('mesas.setting', $mesa->sucursal_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

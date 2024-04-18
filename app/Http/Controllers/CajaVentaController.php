<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CajaVenta;
use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Cargo;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;

class CajaVentaController extends Controller
{
    const ICONO = 'fa-solid fa-file-invoice-dollar fa-fw';
    const INDEX = 'CAJAS VENTAS';
    const CREATE = 'REGISTRAR CAJA DE VENTA';
    const EDITAR = 'MODIFICAR CAJA DE VENTA';
    const SHOW = 'DETALLE CAJA DE VENTA';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('caja.venta.index',Auth::user()->empresa_id);
        }
        return view('caja_venta.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $users = User::select(DB::raw('concat(username," - ",name) as usuario'),'id')
                                        ->where('estado','1')
                                        ->where('empresa_id',$empresa_id)
                                        ->pluck('usuario','id');
        $estados = CajaVenta::ESTADOS;
        $cajas_ventas = CajaVenta::query()
                                ->byEmpresa($empresa_id)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('caja_venta.index', compact('icono','header','empresa','sucursales','users','estados','cajas_ventas'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $sucursales = Sucursal::where('empresa_id',$request->empresa_id)->pluck('nombre','id');
        $users = User::select(DB::raw('concat(username," - ",name) as usuario'),'id')
                                        ->where('estado','1')
                                        ->where('empresa_id',$request->empresa_id)
                                        ->pluck('usuario','id');
        $estados = CajaVenta::ESTADOS;
        $cajas_ventas = CajaVenta::query()
                                    ->byEmpresa($request->empresa_id)
                                    ->bySucursal($request->sucursal_id)
                                    ->byFecha($request->fecha)
                                    ->byCodigo($request->codigo)
                                    ->byUser($request->user_id)
                                    ->byUserAsignado($request->cargo_id)
                                    ->byMonto($request->monto)
                                    ->byEstado($request->estado)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('caja_venta.index', compact('icono','header','empresa','sucursales','users','estados','cajas_ventas'));
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $users = User::select(DB::raw('concat(username," - ",name) as usuario'),'id')
                                        ->where('estado','1')
                                        ->where('empresa_id',$empresa_id)
                                        ->pluck('usuario','id');
        return view('caja_venta.create', compact('icono','header','empresa','sucursales','users'));
    }

    public function store(Request $request)
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $caja_venta = DB::transaction(function () use ($request) {
                $date = date('Y-m-d');
                $empresa = Empresa::find($request->empresa_id);
                $user = User::find($request->user_id);
                $cajas_ventas = CajaVenta::where('empresa_id',$request->empresa_id)->get()->count();
                $gestion = substr(date('Y', strtotime($date)), -2);
                $numero = $cajas_ventas + 1;
                $codigo = $empresa->alias . '-' . $gestion . '-' . (str_pad($numero,3,"0",STR_PAD_LEFT));
                $datos = [
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $empresa->cliente_id,
                    'sucursal_id' => $request->sucursal_id,
                    'user_id' => $user->id,
                    'cargo_id' => $user != null ? $user->id : null,
                    'codigo' => $codigo,
                    'fecha_registro' => $date,
                    'monto_apertura' => floatval(str_replace(",", "", $request->monto)),
                    'observaciones' => $request->observaciones,
                    'estado' => '1',
                ];
                $caja_venta = CajaVenta::create($datos);

                return $caja_venta;
            });
            Log::channel('cajas_ventas')->info(
                "\n" .
                "Mensaje: Caja creada con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('caja.venta.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se creo un registro con exito.');
        } catch (\Exception $e) {
            Log::channel('cajas_ventas')->info(
                "\n" .
                "Mensaje: Error al crear registro de caja: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al crear la caja]')->withInput();
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function editar($asiento_automatico_id)
    {
    }

    public function update(Request $request)
    {
    }

    public function show($caja_venta_id)
    {
        $icono = self::ICONO;
        $header = self::SHOW;
        $caja_venta = CajaVenta::find($caja_venta_id);
        $empresa = Empresa::find($caja_venta->empresa_id);
        return view('caja_venta.show', compact('icono','header','caja_venta','empresa'));
    }

    public function aprobar($caja_venta_id)
    {
        try{
            $caja_venta = DB::transaction(function () use ($caja_venta_id) {
                $caja_venta = CajaVenta::find($caja_venta_id);
                $caja_venta->update([
                    'estado' => '2',
                ]);
                return $caja_venta;
            });
            Log::channel('cajas_ventas')->info(
                "\n" .
                "Mensaje: Caja Aprobada con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('caja.venta.index',['empresa_id' => $caja_venta->empresa_id])->with('success_message', 'Se Aprobo un registro con exito.');
        } catch (\Exception $e) {
            Log::channel('cajas_ventas')->info(
                "\n" .
                "Mensaje: Error al aprobar una caja de venta: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al aprobar una caja de venta]')->withInput();
        }
    }

    public function rechazar($caja_venta_id)
    {
        {
            try{
                $caja_venta = DB::transaction(function () use ($caja_venta_id) {
                    $caja_venta = CajaVenta::find($caja_venta_id);
                    $caja_venta->update([
                        'estado' => '3',
                    ]);
                    return $caja_venta;
                });
                Log::channel('cajas_ventas')->info(
                    "\n" .
                    "Mensaje: Caja Rechazada con éxito" . "\n" .
                    "Usuario: " . Auth::user()->id . "\n"
                );
                return redirect()->route('caja.venta.index',['empresa_id' => $caja_venta->empresa_id])->with('success_message', 'Se Rechazo un registro con exito.');
            } catch (\Exception $e) {
                Log::channel('cajas_ventas')->info(
                    "\n" .
                    "Mensaje: Error al rechazar una caja de venta: " . "\n" .
                    "Usuario: " . Auth::user()->id . "\n" .
                    "Error: " . $e->getMessage() . "\n"
                );
                return redirect()->back()->with('error_message','[Ocurrio un Error al rechazar una caja de venta]')->withInput();
            }
        }
    }
}

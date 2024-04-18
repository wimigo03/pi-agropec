<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsientoAutomatico;
use App\Models\AsientoAutomaticoDetalle;
use App\Models\Empresa;
use App\Models\PlanCuenta;
use App\Models\Moneda;
use App\Models\Modulo;
use DB;
use Auth;
use Illuminate\Support\Facades\Log;

class AsientoAutomaticoController extends Controller
{
    const ICONO = 'fa-solid fa-file-invoice-dollar fa-fw';
    const INDEX = 'ASIENTOS AUTOMATICOS';
    const CREATE = 'REGISTRAR ASIENTO AUTOMATICO';
    const EDITAR = 'MODIFICAR ASIENTO AUTOMATICO';
    const SHOW = 'DETALLE ASIENTO AUTOMATICO';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('asiento.automatico.index',Auth::user()->empresa_id);
        }
        return view('asientos_automaticos.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $modulos = Modulo::where('id','!=',3)->pluck('nombre','id');
        /*$plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                    ->where('detalle','1')
                                    ->where('estado','1')
                                    ->where('empresa_id',$empresa_id)
                                    ->pluck('cuenta_contable','id');*/
        $estados = AsientoAutomatico::ESTADOS;
        /*$tipos = AsientoAutomatico::TIPOS;*/
        $asientos_automaticos = AsientoAutomatico::query()
                                                ->byEmpresa($empresa_id)
                                                ->orderBy('id','desc')
                                                ->paginate(10);
        return view('asientos_automaticos.index', compact('icono','header','empresa','modulos','estados','asientos_automaticos'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $modulos = Modulo::where('id','!=',3)->pluck('nombre','id');
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                    ->where('detalle','1')
                                    ->where('estado','1')
                                    ->where('empresa_id',$request->empresa_id)
                                    ->pluck('cuenta_contable','id');
        $estados = AsientoAutomatico::ESTADOS;
        $tipos = AsientoAutomatico::TIPOS;
        $asientos_automaticos = AsientoAutomatico::query()
                                                ->byEmpresa($request->empresa_id)
                                                ->byModulo($request->modulo_id)
                                                ->byPlanCuenta($request->plan_cuenta_id)
                                                ->byConcepto($request->concepto)
                                                ->byTipo($request->tipo)
                                                ->byGlosa($request->glosa)
                                                ->byEstado($request->estado)
                                                ->orderBy('id','desc')
                                                ->paginate(10);
        return view('asientos_automaticos.index', compact('icono','header','empresa','modulos','plan_cuentas','estados','tipos','asientos_automaticos'));
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$empresa_id)
                                        ->pluck('cuenta_contable','id');
        $modulos = Modulo::where('estado','1')->where('id','!=','3')->pluck('nombre','id');
        $tipos = AsientoAutomaticoDetalle::TIPOS;
        return view('asientos_automaticos.create', compact('icono','header','empresa','plan_cuentas','modulos','tipos'));
    }

    public function store(Request $request)
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $asiento_automatico = DB::transaction(function () use ($request) {
                $empresa = Empresa::find($request->empresa_id);
                $datos = [
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $empresa->cliente_id,
                    'modulo_id' => $request->modulo_id,
                    'nombre' => $request->nombre,
                    'estado' => '1',
                ];
                $asiento_automatico = AsientoAutomatico::create($datos);

                $cont = 0;
                while($cont < count($request->plan_cuenta_id))
                {
                    $plan_cuenta = PlanCuenta::find($request->plan_cuenta_id[$cont]);
                    $datos_detalle = [
                        'asiento_automatico_id' => $asiento_automatico->id,
                        'empresa_id' => $empresa->id,
                        'cliente_id' => $empresa->cliente_id,
                        'modulo_id' => $request->modulo_id,
                        'plan_cuenta_id' => $plan_cuenta->id,
                        'moneda_id' => $plan_cuenta->moneda_id,
                        'pais_id' => $plan_cuenta->pais_id,
                        'tipo' => $request->tipo[$cont],
                        'glosa' => $request->glosa[$cont],
                        'estado' => '1',
                    ];

                    $asiento_automatico_detalle = AsientoAutomaticoDetalle::create($datos_detalle);

                    $cont++;
                }

                return $asiento_automatico;
            });
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Asiento automatico creado con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('asiento.automatico.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se creo un asiento automatico con exito.');
        } catch (\Exception $e) {
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Error al crear asiento automatico: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al crear el asiento automatico]')->withInput();
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function editar($asiento_automatico_id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $asiento_automatico = AsientoAutomatico::find($asiento_automatico_id);
        $empresa = Empresa::find($asiento_automatico->empresa_id);
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$asiento_automatico->empresa_id)
                                        ->pluck('cuenta_contable','id');
        $modulos = Modulo::where('estado','1')->where('id','!=','3')->get();
        $tipos = AsientoAutomaticoDetalle::TIPOS;
        $asientos_automaticos_detalles = AsientoAutomaticoDetalle::where('asiento_automatico_id',$asiento_automatico_id)->where('estado','1')->get();
        return view('asientos_automaticos.editar', compact('icono','header','asiento_automatico','empresa','plan_cuentas','modulos','tipos','asientos_automaticos_detalles'));
    }

    public function eliminarRegistro($id)
    {
        $asiento_automatico_detalle = AsientoAutomaticoDetalle::find($id);
        if($asiento_automatico_detalle != null){
            $asiento_automatico_detalle->update([
                'estado' => '2'
            ]);
            return response()->json([
                'Eliminado' => 'Eliminado'
            ]);
        }

        return response()->json(['error'=>'[ERROR]']);
    }

    public function update(Request $request)
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $asiento_automatico = DB::transaction(function () use ($request) {
                $asiento_automatico = AsientoAutomatico::find($request->asiento_automatico_id);
                $empresa = Empresa::find($asiento_automatico->empresa_id);
                $datos = [
                    'modulo_id' => $request->modulo_id
                ];
                $asiento_automatico->update($datos);

                $cont = 0;
                while($cont < count($request->plan_cuenta_id))
                {
                    $plan_cuenta = PlanCuenta::find($request->plan_cuenta_id[$cont]);
                    $datos_detalle = [
                        'asiento_automatico_id' => $asiento_automatico->id,
                        'empresa_id' => $empresa->id,
                        'cliente_id' => $empresa->cliente_id,
                        'modulo_id' => $request->modulo_id,
                        'plan_cuenta_id' => $plan_cuenta->id,
                        'moneda_id' => $plan_cuenta->moneda_id,
                        'pais_id' => $plan_cuenta->pais_id,
                        'tipo' => $request->tipo[$cont],
                        'glosa' => $request->glosa[$cont],
                        'estado' => '1',
                    ];

                    $asiento_automatico_detalle = AsientoAutomaticoDetalle::create($datos_detalle);

                    $cont++;
                }

                return $asiento_automatico;
            });
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Asiento automatico creado con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('asiento.automatico.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se modifico un asiento automatico con exito.');
        } catch (\Exception $e) {
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Error al modificar asiento automatico: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al modificar el asiento automatico]')->withInput();
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function habilitar($asiento_automatico_id)
    {
        try{
            $asiento_automatico = AsientoAutomatico::find($asiento_automatico_id);
            $asiento_automatico->update([
                'estado' => '1'
            ]);

            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Asiento automatico habilitado con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('asiento.automatico.index',['empresa_id' => $asiento_automatico->empresa_id])->with('success_message', 'Se habilito un asiento automatico con exito.');
        } catch (\Exception $e) {
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Error al habilitar asiento automatico: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al habilitar el asiento automatico]')->withInput();
        }
    }

    public function deshabilitar($asiento_automatico_id)
    {
        try{
            $asiento_automatico = AsientoAutomatico::find($asiento_automatico_id);
            $asiento_automatico->update([
                'estado' => '2'
            ]);

            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Asiento automatico deshabilitado con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('asiento.automatico.index',['empresa_id' => $asiento_automatico->empresa_id])->with('success_message', 'Se deshabilito un asiento automatico con exito.');
        } catch (\Exception $e) {
            Log::channel('asientos_automaticos')->info(
                "\n" .
                "Mensaje: Error al deshabilitar asiento automatico: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al deshabilitar el asiento automatico]')->withInput();
        }
    }

    public function show($asiento_automatico_id)
    {
        $icono = self::ICONO;
        $header = self::SHOW;
        $asiento_automatico = AsientoAutomatico::find($asiento_automatico_id);
        $empresa = Empresa::find($asiento_automatico->empresa_id);
        $asientos_automaticos_detalles = AsientoAutomaticoDetalle::where('asiento_automatico_id',$asiento_automatico_id)->where('estado','1')->get();
        return view('asientos_automaticos.show', compact('icono','header','asiento_automatico','empresa','asientos_automaticos_detalles'));
    }
}

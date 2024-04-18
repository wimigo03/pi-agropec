<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ComprobanteFController;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\ComprobanteF;
use App\Models\ComprobanteFDetalle;
use App\Models\TipoCambio;
use App\Models\Moneda;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\PlanCuenta;
use App\Models\PlanCuentaAuxiliar;
use Auth;
use PDF;
use Luecano\NumeroALetras\NumeroALetras;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;

class ComprobanteController extends Controller
{
    const ICONO = 'fa-solid fa-file-invoice-dollar fa-fw';
    const INDEX = 'COMPROBANTES';
    const CREATE = 'REGISTRAR COMPROBANTE';
    const SHOW = 'DETALLE DEL COMPROBANTE';
    const EDITAR = 'MODIFICAR COMPROBANTE';
    const INICIOMESFISCAL = 'INICIO DE MES FISCAL';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('comprobante.index',Auth::user()->empresa_id);
        }
        return view('comprobantes.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $estados = Comprobante::ESTADOS;
        $tipos = Comprobante::TIPOS;
        $comprobantes = Comprobante::query()
                                    ->byEmpresa($empresa_id)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('comprobantes.index', compact('icono','header','empresa','estados','tipos','comprobantes'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $estados = Comprobante::ESTADOS;
        $tipos = Comprobante::TIPOS;
        $comprobantes = Comprobante::query()
                                    ->byEmpresa($request->empresa_id)
                                    ->byEntreFechas($request->fecha_i, $request->fecha_f)
                                    ->byNroComprobante($request->nro_comprobante)
                                    ->byConcepto($request->concepto)
                                    ->byTipo($request->tipo)
                                    ->byEstado($request->estado)
                                    ->byMonto($request->monto)
                                    ->byCopia($request->copia)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('comprobantes.index', compact('icono','header','empresa','estados','tipos','comprobantes'));

    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $tipo_cambio = TipoCambio::where('fecha',date('Y-m-d'))->where('estado','1')->first();
        if($tipo_cambio == null){
            return redirect()->route('tipo.cambio.index',$empresa_id)->with('info_message', 'Antes de continuar se debe registrar un Tipo de Cambio para la fecha actual.');
        }
        $monedas = Moneda::where('estado','1')->orderBy('id','desc')->pluck('nombre','id');
        $tipos = Comprobante::TIPOS;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$empresa_id)
                                        ->pluck('cuenta_contable','id');
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::where('estado','1')->pluck('nombre','id');
        return view('comprobantes.create', compact('icono','header','empresa','tipo_cambio','monedas','tipos','sucursales','plan_cuentas','plan_cuentas_auxiliares'));
    }

    public function store(Request $request)
    {
        $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha)));
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $comprobante = DB::transaction(function () use ($request, $fecha, $tipo_cambio) {
                $empresa = Empresa::find($request->empresa_id);
                $moneda = Moneda::where('id',$request->moneda_id)->first();
                $user = User::where('id',Auth::user()->id)->first();
                $ultimo_comprobante = $this->ultimoComprobante($request->tipo, $request->empresa_id, $fecha);
                $copia = isset($request->copia) ? '1' : '2';
                $datos = [
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $empresa->cliente_id,
                    'tipo_cambio_id' => $tipo_cambio->id,
                    'user_id' => $user != null ? $user->id : 1,
                    'cargo_id' => $user != null ? $user->cargo_id : null,
                    'moneda_id' => $moneda->id,
                    'pais_id' => $moneda->pais_id,
                    'nro' => $ultimo_comprobante['numero'],
                    'nro_comprobante' => $ultimo_comprobante['nro_comprobante'],
                    'tipo_cambio' => $tipo_cambio->dolar_oficial,
                    'ufv' => $tipo_cambio->ufv,
                    'tipo' => $request->tipo,
                    'entregado_recibido' => $request->entregado_recibido,
                    'fecha' => $fecha,
                    'concepto' => $request->concepto,
                    'monto' => $request->monto_total,
                    'moneda' => $moneda->alias,
                    'copia' => $copia,
                    'estado' => '1',
                ];
                $comprobante = Comprobante::create($datos);

                $cont = 0;
                while($cont < count($request->sucursal_id)){
                    $datos_detalle = [
                        'comprobante_id' => $comprobante->id,
                        'empresa_id' => $empresa->id,
                        'cliente_id' => $empresa->cliente_id,
                        'tipo_cambio_id' => $tipo_cambio->id,
                        'user_id' => $user != null ? $user->id : 1,
                        'cargo_id' => $user != null ? $user->cargo_id : null,
                        'moneda_id' => $moneda->id,
                        'pais_id' => $moneda->pais_id,
                        'plan_cuenta_id' => $request->plan_cuenta_id[$cont],
                        'sucursal_id' => $request->sucursal_id[$cont],
                        'plan_cuenta_auxiliar_id' => $request->auxiliar_id[$cont],
                        'glosa' => $request->glosa[$cont],
                        'debe' => floatval(str_replace(",", "", $request->debe[$cont])),
                        'haber' => floatval(str_replace(",", "", $request->haber[$cont])),
                        'estado' => '1'
                    ];

                    $comprobante_detalle = ComprobanteDetalle::create($datos_detalle);

                    $cont++;
                }

                if($copia == '1'){
                    $comprobante_controller = new ComprobanteFController;
                    $comprobantef = $comprobante_controller->copiar_comprobante($comprobante);
                }
                return $comprobante;
            });
            Log::channel('comprobantes')->info(
                "Comprobante: " . $comprobante->nro_comprobante . " Creado con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('comprobante.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó el comprobante Nro, ' . $comprobante->nro_comprobante . '...');
        } catch (\Exception $e) {
            Log::channel('comprobantes')->info(
                "Error al crear comprobante: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al crear comprobante]')->withInput();
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function crearEncabezadoComprobante($datos_comprobante)
    {
        $fecha_comprobante = date('Y-m-d', strtotime(str_replace('/', '-', $datos_comprobante['fecha'])));
        $ultimo_comprobante = $this->ultimoComprobante($datos_comprobante['tipo'], $datos_comprobante['empresa_id'], $fecha_comprobante);
        $datos = [
            'empresa_id' => $datos_comprobante['empresa_id'],
            'cliente_id' => $datos_comprobante['cliente_id'],
            'tipo_cambio_id' => $datos_comprobante['tipo_cambio_id'],
            'user_id' => $datos_comprobante['user_id'],
            'cargo_id' => $datos_comprobante['cargo_id'],
            'moneda_id' => $datos_comprobante['moneda_id'],
            'pais_id' => $datos_comprobante['pais_id'],
            'nro' => $ultimo_comprobante['numero'],
            'nro_comprobante' => $ultimo_comprobante['nro_comprobante'],
            'tipo_cambio' => $datos_comprobante['tipo_cambio'],
            'ufv' => $datos_comprobante['ufv'],
            'tipo' => $datos_comprobante['tipo'],
            'entregado_recibido' => $datos_comprobante['entregado_recibido'],
            'fecha' => $fecha_comprobante,
            'concepto' => $datos_comprobante['concepto'],
            'monto' => $datos_comprobante['monto'],
            'moneda' => $datos_comprobante['moneda'],
            'copia' => $datos_comprobante['copia'],
            'estado' => $datos_comprobante['estado']
        ];
        $comprobante = Comprobante::create($datos);
        return $comprobante;
    }

    public function ultimoComprobante($tipo,$empresa_id,$fecha)
    {
        $ultimoComprobante = Comprobante::where('tipo',$tipo)
                                        ->where('empresa_id',$empresa_id)
                                        ->whereMonth('fecha', date('m', strtotime($fecha)))
                                        ->whereYear('fecha', date('Y', strtotime($fecha)))
                                        ->orderBy('nro','desc')
                                        ->first();
        $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro : 0) + 1;
        $codigo = Comprobante::TIPOS_ALIAS[$tipo];
        $date = substr(date("Y", strtotime($fecha)),2) . date("m", strtotime($fecha));
        $nro_comprobante = $codigo . '-' . $date . '-' . (str_pad($numero,3,"0",STR_PAD_LEFT));
         return [
            'numero' => $numero,
            'nro_comprobante' => $nro_comprobante
         ];
    }

    public function primerComprobanteMes($tipo,$empresa_id,$fecha)
    {
        $primer_comprobante = Comprobante::where('tipo',$tipo)
                                        ->where('empresa_id',$empresa_id)
                                        ->whereMonth('fecha', date('m', strtotime($fecha)))
                                        ->whereYear('fecha', date('Y', strtotime($fecha)))
                                        ->where('nro',1)
                                        ->orderBy('nro','desc')
                                        ->first();
        return $primer_comprobante;
    }

    public function tieneAuxiliar($plan_cuenta_id)
    {
        $plan_cuenta = PlanCuenta::find($plan_cuenta_id);
        if($plan_cuenta != null){
            return response()->json([
                'auxiliar' => $plan_cuenta->auxiliar
            ]);
        }

        return response()->json(['error'=>'[ERROR]']);
    }

    public function show($id)
    {
        $icono = self::ICONO;
        $header = self::SHOW;
        $comprobante = Comprobante::find($id);
        $comprobante_detalles = ComprobanteDetalle::where('comprobante_id',$id)->where('estado','1')->get();
        $comprobantef = ComprobanteF::where('comprobante_id',$comprobante->id)->first();
        $total_debe = $comprobante_detalles->sum('debe');
        $total_haber = $comprobante_detalles->sum('haber');
        $empresa = Empresa::find($comprobante->empresa_id);
        return view('comprobantes.show', compact('icono','header','comprobante','comprobante_detalles','comprobantef','total_debe','total_haber','empresa'));
    }

    public function aprobar($comprobante_id){
        try{
            $comprobante = Comprobante::find($comprobante_id);
            $comprobante->update([
                'estado' => '2'
            ]);
            return redirect()->route('comprobante.index',['empresa_id' => $comprobante->empresa_id])->with('success_message', 'COMPROBANTE, ' . $comprobante->nro_comprobante . ' APROBADO CON EXITO...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobante.index',$comprobante->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function anular($comprobante_id){
        try{
            $comprobante = Comprobante::find($comprobante_id);
            $comprobante->update([
                'estado' => '3'
            ]);
            $comprobantef = ComprobanteF::select('id')->where('comprobante_id',$comprobante_id)->whereIn('estado',['1','2'])->first();
            if($comprobantef != null){
                $comprobante_fiscal = ComprobanteF::find($comprobantef->id);
                $comprobante_fiscal->update([
                    'estado' => '3'
                ]);
            }
            return redirect()->route('comprobante.index',['empresa_id' => $comprobante->empresa_id])->with('success_message', 'COMPROBANTE, ' . $comprobante->nro_comprobante . ' ANULADO CON EXITO...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobante.index',$comprobante->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function pdf($comprobante_id)
    {
        $comprobante = Comprobante::find($comprobante_id);
        $comprobante_detalles = ComprobanteDetalle::where('comprobante_id',$comprobante_id)
                                                    ->where('estado','1')
                                                    ->orderBy('debe','desc')
                                                    ->orderBy('plan_cuenta_id','asc')
                                                    ->get();
        $total_debe = $comprobante_detalles->sum('debe');
        $total_haber = $comprobante_detalles->sum('haber');
        $numero_letras = new NumeroALetras();
        $total_en_letras = $numero_letras->toInvoice($comprobante->monto, 2, $comprobante->datos_moneda->nombre);
        $pdf = PDF::loadView('comprobantes.pdf',compact(['comprobante','comprobante_detalles','total_debe','total_haber','total_en_letras']));
        $pdf->setPaper('LETTER', 'portrait');
        return $pdf->stream();
    }

    public function editar($comprobante_id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $comprobante = Comprobante::find($comprobante_id);
        $comprobante_detalles = ComprobanteDetalle::where('comprobante_id',$comprobante_id)->where('estado','1')->orderBy('id','desc')->get();
        $total_debe = $comprobante_detalles->sum('debe');
        $total_haber = $comprobante_detalles->sum('haber');
        $empresa = Empresa::find($comprobante->empresa_id);
        $sucursales = Sucursal::where('empresa_id',$comprobante->empresa_id)->pluck('nombre','id');
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$comprobante->empresa_id)
                                        ->pluck('cuenta_contable','id');
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::where('estado','1')->pluck('nombre','id');
        return view('comprobantes.editar', compact('icono','header','comprobante','comprobante_detalles','total_debe','total_haber','empresa','sucursales','plan_cuentas','plan_cuentas_auxiliares'));
    }

    public function eliminarRegistro($id)
    {
        $comprobante_detalle = ComprobanteDetalle::find($id);
        if($comprobante_detalle != null){
            $comprobante_detalle->update([
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
            $cont = 0;
            $comprobante = Comprobante::find($request->comprobante_id);
            $user = User::where('id',Auth::user()->id)->first();
            $empresa = Empresa::where('id',$comprobante->empresa_id)->first();
            $tipo_cambio = TipoCambio::where('id',$comprobante->tipo_cambio_id)->first();
            if($tipo_cambio == null){
                return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
            }
            $moneda = Moneda::where('id',$comprobante->moneda_id)->first();
            while($cont < count($request->sucursal_id)){
                $datos_detalle = [
                    'comprobante_id' => $comprobante->id,
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $empresa->cliente_id,
                    'tipo_cambio_id' => $tipo_cambio->id,
                    'user_id' => $user != null ? $user->id : 1,
                    'cargo_id' => $user != null ? $user->cargo_id : null,
                    'moneda_id' => $moneda->id,
                    'pais_id' => $moneda->pais_id,
                    'plan_cuenta_id' => $request->plan_cuenta_id[$cont],
                    'sucursal_id' => $request->sucursal_id[$cont],
                    'plan_cuenta_auxiliar_id' => $request->auxiliar_id[$cont],
                    'glosa' => $request->glosa[$cont],
                    'debe' => floatval(str_replace(",", "", $request->debe[$cont])),
                    'haber' => floatval(str_replace(",", "", $request->haber[$cont])),
                    'estado' => '1'
                ];

                $comprobante_detalle = ComprobanteDetalle::create($datos_detalle);

                $cont++;
            }

            $monto_total = ComprobanteDetalle::select('debe')->where('comprobante_id',$comprobante->id)->where('estado','1')->get()->sum('debe');

            $datos = [
                'user_id' => $user != null ? $user->id : 1,
                'cargo_id' => $user != null ? $user->cargo_id : null,
                'entregado_recibido' => $request->entregado_recibido,
                'concepto' => $request->concepto,
                'monto' => $monto_total,
            ];
            $comprobante->update($datos);

            return redirect()->route('comprobante.index',['empresa_id' => $comprobante->empresa_id])->with('info_message', 'Se actualizo el comprobante Nro, ' . $comprobante->nro_comprobante . '...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobante.index',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

<?php

namespace App\Http\Controllers;

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

class ComprobanteFController extends Controller
{
    const ICONO = 'fa-solid fa-file-invoice-dollar fa-fw';
    const INDEX = 'COMPROBANTES';
    const CREATE = 'REGISTRAR COMPROBANTE';
    const SHOW = 'DETALLE DEL COMPROBANTE';
    const EDITAR = 'MODIFICAR COMPROBANTE';

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $estados = ComprobanteF::ESTADOS;
        $tipos = ComprobanteF::TIPOS;
        $comprobantes = ComprobanteF::query()
                                    ->byEmpresa($empresa_id)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('comprobantesf.index', compact('icono','header','empresa','estados','tipos','comprobantes'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $estados = ComprobanteF::ESTADOS;
        $tipos = ComprobanteF::TIPOS;
        $comprobantes = ComprobanteF::query()
                                    ->byEmpresa($request->empresa_id)
                                    ->byEntreFechas($request->fecha_i, $request->fecha_f)
                                    ->byNroComprobante($request->nro_comprobante)
                                    ->byConcepto($request->concepto)
                                    ->byTipo($request->tipo)
                                    ->byEstado($request->estado)
                                    ->byMonto($request->monto)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('comprobantesf.index', compact('icono','header','empresa','estados','tipos','comprobantes'));

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
        $tipos = ComprobanteF::TIPOS;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$empresa_id)
                                        ->pluck('cuenta_contable','id');
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::where('estado','1')->pluck('nombre','id');
        return view('comprobantesf.create', compact('icono','header','empresa','tipo_cambio','monedas','tipos','sucursales','plan_cuentas','plan_cuentas_auxiliares'));
    }

    public function store(Request $request)
    {
        $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha)));
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        try{
            $empresa = Empresa::find($request->empresa_id);
            $moneda = Moneda::where('id',$request->moneda_id)->first();
            $user = User::where('id',Auth::user()->id)->first();
            $ultimoComprobante = ComprobanteF::where('tipo',$request->tipo)
                                            ->where('empresa_id',$request->empresa_id)
                                            ->whereMonth('fecha', date('m', strtotime($fecha)))
                                            ->whereYear('fecha', date('Y', strtotime($fecha)))
                                            ->orderBy('nro','desc')
                                            ->first();
            $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro : 0) + 1;
            $codigo = ComprobanteF::TIPOS_ALIAS[$request->tipo];
            $date = substr(date("Y", strtotime($fecha)),2) . date("m", strtotime($fecha));
            $nro_comprobante = $codigo . '-' . $date . '-' . (str_pad($numero,3,"0",STR_PAD_LEFT));
            $datos = [
                'empresa_id' => $empresa->id,
                'cliente_id' => $empresa->cliente_id,
                'tipo_cambio_id' => $tipo_cambio->id,
                'user_id' => $user != null ? $user->id : 1,
                'cargo_id' => $user != null ? $user->cargo_id : null,
                'moneda_id' => $moneda->id,
                'pais_id' => $moneda->pais_id,
                'nro' => $numero,
                'nro_comprobante' => $nro_comprobante,
                'tipo_cambio' => $tipo_cambio->dolar_oficial,
                'ufv' => $tipo_cambio->ufv,
                'tipo' => $request->tipo,
                'entregado_recibido' => $request->entregado_recibido,
                'fecha' => $fecha,
                'concepto' => $request->concepto,
                'monto' => $request->monto_total,
                'moneda' => $moneda->alias,
                'estado' => '1',
            ];
            $comprobante = ComprobanteF::create($datos);

            $cont = 0;
            while($cont < count($request->sucursal_id)){
                $datos_detalle = [
                    'comprobantef_id' => $comprobante->id,
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

                $comprobante_detalle = ComprobanteFDetalle::create($datos_detalle);

                $cont++;
            }

            return redirect()->route('comprobantef.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó el comprobante Nro, ' . $comprobante->nro_comprobante . '...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobantef.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function primerComprobanteMes($tipo,$empresa_id,$fecha)
    {
        $primer_comprobante = ComprobanteF::where('tipo',$tipo)
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
        $comprobante = ComprobanteF::find($id);
        $comprobante_detalles = ComprobanteFDetalle::where('comprobantef_id',$id)->where('estado','1')->get();
        $comprobantei = Comprobante::where('id',$comprobante->comprobante_id)->first();
        $total_debe = $comprobante_detalles->sum('debe');
        $total_haber = $comprobante_detalles->sum('haber');
        $empresa = Empresa::find($comprobante->empresa_id);
        return view('comprobantesf.show', compact('icono','header','comprobante','comprobante_detalles','comprobantei','total_debe','total_haber','empresa'));
    }

    public function aprobar($comprobante_id){
        try{
            $comprobante = ComprobanteF::find($comprobante_id);
            $comprobante->update([
                'estado' => '2'
            ]);
            return redirect()->route('comprobantef.index',['empresa_id' => $comprobante->empresa_id])->with('success_message', 'COMPROBANTE, ' . $comprobante->nro_comprobante . ' APROBADO CON EXITO...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobantef.index',$comprobante->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function anular($comprobante_id){
        try{
            $comprobante = ComprobanteF::find($comprobante_id);
            $comprobante->update([
                'estado' => '3'
            ]);
            return redirect()->route('comprobantef.index',['empresa_id' => $comprobante->empresa_id])->with('success_message', 'COMPROBANTE, ' . $comprobante->nro_comprobante . ' ANULADO CON EXITO...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobantef.index',$comprobante->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function pdf($comprobante_id)
    {
        $comprobante = ComprobanteF::find($comprobante_id);
        $comprobante_detalles = ComprobanteFDetalle::where('comprobantef_id',$comprobante_id)->where('estado','1')->orderBy('id','desc')->get();
        $total_debe = $comprobante_detalles->sum('debe');
        $total_haber = $comprobante_detalles->sum('haber');
        $numero_letras = new NumeroALetras();
        $total_en_letras = $numero_letras->toInvoice($comprobante->monto, 2, $comprobante->datos_moneda->nombre);
        $pdf = PDF::loadView('comprobantesf.pdf',compact(['comprobante','comprobante_detalles','total_debe','total_haber','total_en_letras']));
        $pdf->setPaper('LETTER', 'portrait');
        return $pdf->stream();
    }

    public function editar($comprobante_id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $comprobante = ComprobanteF::find($comprobante_id);
        $comprobante_detalles = ComprobanteFDetalle::where('comprobantef_id',$comprobante_id)->where('estado','1')->orderBy('id','desc')->get();
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
        return view('comprobantesf.editar', compact('icono','header','comprobante','comprobante_detalles','total_debe','total_haber','empresa','sucursales','plan_cuentas','plan_cuentas_auxiliares'));
    }

    public function eliminarRegistro($id)
    {
        $comprobante_detalle = ComprobanteFDetalle::find($id);
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
            $comprobante = ComprobanteF::find($request->comprobante_id);
            $user = User::where('id',Auth::user()->id)->first();
            $empresa = Empresa::where('id',$comprobante->empresa_id)->first();
            $tipo_cambio = TipoCambio::where('id',$comprobante->tipo_cambio_id)->first();
            if($tipo_cambio == null){
                return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
            }
            $moneda = Moneda::where('id',$comprobante->moneda_id)->first();
            while($cont < count($request->sucursal_id)){
                $datos_detalle = [
                    'comprobantef_id' => $comprobante->id,
                    'comprobante_id' => $comprobante->comprobante_id,
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

                $comprobante_detalle = ComprobanteFDetalle::create($datos_detalle);

                $cont++;
            }

            $monto_total = ComprobanteFDetalle::select('debe')->where('comprobantef_id',$comprobante->id)->where('estado','1')->get()->sum('debe');

            $datos = [
                'user_id' => $user != null ? $user->id : 1,
                'cargo_id' => $user != null ? $user->cargo_id : null,
                'entregado_recibido' => $request->entregado_recibido,
                'concepto' => $request->concepto,
                'monto' => $monto_total,
            ];
            $comprobante->update($datos);

            return redirect()->route('comprobantef.index',['empresa_id' => $comprobante->empresa_id])->with('info_message', 'Se actualizo el comprobante Nro, ' . $comprobante->nro_comprobante . '...');
        } catch (ValidationException $e) {
            return redirect()->route('comprobantef.index',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function copiar_comprobante($comprobante)
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $ultimoComprobante = ComprobanteF::where('tipo',$comprobante->tipo)
                                                    ->where('empresa_id',$comprobante->empresa_id)
                                                    ->whereMonth('fecha', date('m', strtotime($comprobante->fecha)))
                                                    ->whereYear('fecha', date('Y', strtotime($comprobante->fecha)))
                                                    ->orderBy('nro','desc')
                                                    ->first();
                $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro : 0) + 1;
                $codigo = ComprobanteF::TIPOS_ALIAS[$comprobante->tipo];
                $date = substr(date("Y", strtotime($comprobante->fecha)),2) . date("m", strtotime($comprobante->fecha));
                $nro_comprobante = $codigo . '-' . $date . '-' . (str_pad($numero,3,"0",STR_PAD_LEFT));
                $datos = [
                    'comprobante_id' => $comprobante->id,
                    'empresa_id' => $comprobante->empresa_id,
                    'cliente_id' => $comprobante->cliente_id,
                    'tipo_cambio_id' => $comprobante->tipo_cambio_id,
                    'user_id' => $comprobante->user_id,
                    'cargo_id' => $comprobante->cargo_id,
                    'moneda_id' => $comprobante->moneda_id,
                    'pais_id' => $comprobante->pais_id,
                    'nro' => $numero,
                    'nro_comprobante' => $nro_comprobante,
                    'tipo_cambio' => $comprobante->tipo_cambio,
                    'ufv' => $comprobante->ufv,
                    'tipo' => $comprobante->tipo,
                    'entregado_recibido' => $comprobante->entregado_recibido,
                    'fecha' => $comprobante->fecha,
                    'concepto' => $comprobante->concepto,
                    'monto' => $comprobante->monto,
                    'moneda' => $comprobante->moneda,
                    'estado' => '1'
                ];
                $comprobantef = ComprobanteF::create($datos);

                $comprobantes_detalles = ComprobanteDetalle::where('comprobante_id',$comprobante->id)->where('estado','1')->get();
                foreach($comprobantes_detalles as $comprobante_detalle){
                    $datos_detalle = [
                        'comprobantef_id' => $comprobantef->id,
                        'comprobante_id' => $comprobante->id,
                        'empresa_id' => $comprobante_detalle->empresa_id,
                        'cliente_id' => $comprobante_detalle->cliente_id,
                        'tipo_cambio_id' => $comprobante_detalle->tipo_cambio_id,
                        'user_id' => $comprobante_detalle->user_id,
                        'cargo_id' => $comprobante_detalle->cargo_id,
                        'moneda_id' => $comprobante_detalle->moneda_id,
                        'pais_id' => $comprobante_detalle->pais_id,
                        'sucursal_id' => $comprobante_detalle->sucursal_id,
                        'plan_cuenta_id' => $comprobante_detalle->plan_cuenta_id,
                        'plan_cuenta_auxiliar_id' => $comprobante_detalle->plan_cuenta_auxiliar_id,
                        'glosa' => $comprobante_detalle->glosa,
                        'debe' => $comprobante_detalle->debe,
                        'haber' => $comprobante_detalle->haber,
                        'tipo_transaccion' => $comprobante_detalle->tipo_transaccion,
                        'nro_cheque' => $comprobante_detalle->nro_cheque,
                        'orden_cheque' => $comprobante_detalle->orden_cheque,
                        'fecha_cheque' => $comprobante_detalle->fecha_cheque,
                        'estado' => $comprobante_detalle->estado
                    ];

                    $comprobantef_detalle = ComprobanteFDetalle::create($datos_detalle);
                }
                return $comprobantef;
        } catch (ValidationException $e) {
            return redirect()->route('precio.productos.index', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function crearEncabezadoComprobante($datos_comprobante)
    {
        $fecha_comprobante = date('Y-m-d', strtotime(str_replace('/', '-', $datos_comprobante['fecha'])));
        $ultimo_comprobante = $this->ultimoComprobante($datos_comprobante['tipo'], $datos_comprobante['empresa_id'], $fecha_comprobante);
        $datos = [
            'comprobante_id' => $datos_comprobante['comprobante_id'],
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
            'estado' => $datos_comprobante['estado']
        ];
        $comprobante = ComprobanteF::create($datos);
        return $comprobante;
    }

    public function ultimoComprobante($tipo,$empresa_id,$fecha)
    {
        $ultimoComprobante = ComprobanteF::where('tipo',$tipo)
                                        ->where('empresa_id',$empresa_id)
                                        ->whereMonth('fecha', date('m', strtotime($fecha)))
                                        ->whereYear('fecha', date('Y', strtotime($fecha)))
                                        ->orderBy('nro','desc')
                                        ->first();
        $numero = intval($ultimoComprobante != null ? $ultimoComprobante->nro : 0) + 1;
        $codigo = ComprobanteF::TIPOS_ALIAS[$tipo];
        $date = substr(date("Y", strtotime($fecha)),2) . date("m", strtotime($fecha));
        $nro_comprobante = $codigo . '-' . $date . '-' . (str_pad($numero,3,"0",STR_PAD_LEFT));
         return [
            'numero' => $numero,
            'nro_comprobante' => $nro_comprobante
         ];
    }
}

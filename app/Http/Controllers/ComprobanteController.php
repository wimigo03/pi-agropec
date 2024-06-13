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
use App\Models\Centro;
use App\Models\SubCentro;
use App\Models\PlanCuenta;
use App\Models\PlanCuentaAuxiliar;
use Auth;
use PDF;
use Luecano\NumeroALetras\NumeroALetras;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\ComprobantesExcel;
use Carbon\Carbon;
use DB;
use DataTables;
use Illuminate\Support\Facades\Log;

class ComprobanteController extends Controller
{
    const ICONO = 'fa-solid fa-file-invoice-dollar fa-fw';
    const INDEX = 'COMPROBANTES';
    const CREATE = 'REGISTRAR COMPROBANTE';
    const SHOW = 'DETALLE DEL COMPROBANTE';
    const EDITAR = 'MODIFICAR COMPROBANTE';
    const INICIOMESFISCAL = 'INICIO DE MES FISCAL';

    public function index(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados = Comprobante::ESTADOS;
        $tipos = Comprobante::TIPOS;

        if ($request->ajax()) {
            $data = DB::table('comprobantes as a')
                    ->join('empresas as b','b.id','a.empresa_id')
                    ->join('users as c','c.id','a.user_id')
                    ->select(
                        'a.id as comprobante_id',
                        'b.alias as empresa',
                        DB::raw("CASE
                            WHEN a.tipo = 1 THEN 'INGRESO'
                            WHEN a.tipo = 2 THEN 'EGRESO'
                            WHEN a.tipo = 3 THEN 'TRASPASO'
                            ELSE '#'
                        END AS tipos"),
                        'a.fecha as fecha',
                        'a.nro_comprobante',
                        'a.creado as creado',
                        'a.concepto',
                        'a.monto',
                        'a.estado',
                        DB::raw("CASE
                            WHEN a.estado = 1 THEN 'PENDIENTE'
                            WHEN a.estado = 2 THEN 'APROBADO'
                            WHEN a.estado = 3 THEN 'ANULADO'
                            WHEN a.estado = 4 THEN 'ELIMINADO'
                            ELSE '#'
                        END AS status"),
                        DB::raw("UPPER(c.username) as username"),
                        'a.copia'
                    );

            return Datatables::of($data)
                ->filterColumn('tipos', function($query, $keyword) {
                    $sql = "CASE
                        WHEN a.tipo = 1 THEN 'INGRESO'
                        WHEN a.tipo = 2 THEN 'EGRESO'
                        WHEN a.tipo = 3 THEN 'TRASPASO'
                        ELSE '#'
                    END like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('fecha', function($query, $keyword) {
                    $sql = "DATE_FORMAT(fecha, '%d-%m-%y') like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filterColumn('creado', function($query, $keyword) {
                    $sql = "DATE_FORMAT(creado, '%d-%m-%y') like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->editColumn('monto', function($row) {
                    return number_format($row->monto, 2, '.', ',');
                })
                ->filterColumn('status', function($query, $keyword) {
                    $sql = "CASE
                        WHEN a.estado = 1 THEN 'PENDIENTE'
                        WHEN a.estado = 2 THEN 'APROBADO'
                        WHEN a.estado = 3 THEN 'ANULADO'
                        WHEN a.estado = 4 THEN 'ELIMINADO'
                        ELSE '#'
                    END like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('copia', 'comprobantes.partials.copia')
                ->addColumn('bars', 'comprobantes.partials.bars')
                ->rawColumns(['copia','bars'])
                ->make(true);
        }

        return view('comprobantes.index', compact('icono','header','empresas','estados','tipos'));
    }

    public function getUsuarios(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $usuarios = User::select('name','id')
                            ->where('empresa_id',$id)
                            ->orderBy('id','desc')
                            ->get()
                            ->toJson();
            if($usuarios){
                return response()->json([
                    'usuarios' => $usuarios
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados = Comprobante::ESTADOS;
        $tipos = Comprobante::TIPOS;
        $comprobantes = Comprobante::query()
                        ->byPiCliente(Auth::user()->pi_cliente_id)
                        ->byEmpresa($request->empresa_id)
                        ->byTipo($request->tipo)
                        ->byEntreFechas($request->fecha_i, $request->fecha_f)
                        ->byNroComprobante($request->nro_comprobante)
                        ->byConcepto($request->concepto)
                        ->byMonto($request->monto)
                        ->byEstado($request->estado)
                        ->byCreadoPor($request->user_id)
                        ->byCopia($request->copia)
                        ->orderBy('fecha','desc')
                        ->paginate(10);
        return view('comprobantes.index', compact('icono','header','empresas','estados','tipos','comprobantes'));
    }

    public function excel(Request $request)
    {
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $comprobantes = Comprobante::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->byEmpresa($request->empresa_id)
                                ->byTipo($request->tipo)
                                ->byEntreFechas($request->fecha_i, $request->fecha_f)
                                ->byNroComprobante($request->nro_comprobante)
                                ->byConcepto($request->concepto)
                                ->byMonto($request->monto)
                                ->byEstado($request->estado)
                                ->byCreadoPor($request->user_id)
                                ->byCopia($request->copia)
                                ->orderBy('fecha','desc')
                                ->get();
            return Excel::download(new ComprobantesExcel($comprobantes),'Comprobantes.xlsx');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function create()
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        $tipo_cambio = TipoCambio::where('pi_cliente_id',Auth::user()->pi_cliente_id)->where('fecha',date('Y-m-d'))->where('estado','1')->first();
        if($tipo_cambio == null){
            return redirect()->route('tipo.cambio.index')->with('info_message', 'Antes de continuar se debe registrar un Tipo de Cambio para la fecha actual.');
        }
        $monedas = Moneda::where('estado','1')->orderBy('id','desc')->pluck('nombre','id');
        $tipos = Comprobante::TIPOS;
        return view('comprobantes.create', compact('icono','header','empresas','tipo_cambio','monedas','tipos'));
    }

    public function getCentros(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $centros = DB::table('centros')
                            ->where('empresa_id',$id)
                            ->where('estado','1')
                            ->select('nombre','id')
                            ->get()
                            ->toJson();
            if($centros){
                return response()->json([
                    'centros' => $centros
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPlanCuentas(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$id)
                                        ->get()
                                        ->toJson();
            if($plan_cuentas){
                return response()->json([
                    'plan_cuentas' => $plan_cuentas
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPlanCuentasAuxiliares(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $plan_cuentas_auxiliares = PlanCuentaAuxiliar::select('nombre','id')
                                                            ->where('estado','1')
                                                            ->where('empresa_id',$id)
                                                            ->get()
                                                            ->toJson();
            if($plan_cuentas_auxiliares){
                return response()->json([
                    'plan_cuentas_auxiliares' => $plan_cuentas_auxiliares
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSubCentros(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $subcentros = DB::table('sub_centros')
                            ->where('centro_id',$id)
                            ->where('estado','1')
                            ->select('nombre','id')
                            ->get()
                            ->toJson();
            if($subcentros){
                return response()->json([
                    'subcentros' => $subcentros
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $fecha = date('Y-m-d', strtotime($request->fecha));
        $tipo_cambio = TipoCambio::where('pi_cliente_id',Auth::user()->pi_cliente_id)->where('fecha',$fecha)->first();
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
                    'pi_cliente_id' => $empresa->pi_cliente_id,
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
                    'creado' => date('Y-m-d')
                ];
                $comprobante = Comprobante::create($datos);

                $cont = 0;
                while($cont < count($request->centro_id)){
                    $datos_detalle = [
                        'comprobante_id' => $comprobante->id,
                        'empresa_id' => $empresa->id,
                        'pi_cliente_id' => $empresa->pi_cliente_id,
                        'tipo_cambio_id' => $tipo_cambio->id,
                        'user_id' => $user != null ? $user->id : 1,
                        'cargo_id' => $user != null ? $user->cargo_id : null,
                        'moneda_id' => $moneda->id,
                        'pais_id' => $moneda->pais_id,
                        'plan_cuenta_id' => $request->plan_cuenta_id[$cont],
                        'centro_id' => $request->centro_id[$cont],
                        'sub_centro_id' => $request->sub_centro_id[$cont],
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
            return redirect()->route('comprobante.index')->with('success_message', 'Se agregó el comprobante Nro, ' . $comprobante->nro_comprobante . '...');
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
        $fecha_comprobante = date('Y-m-d', strtotime($datos_comprobante['fecha']));
        $ultimo_comprobante = $this->ultimoComprobante($datos_comprobante['tipo'], $datos_comprobante['empresa_id'], $fecha_comprobante);
        $datos = [
            'empresa_id' => $datos_comprobante['empresa_id'],
            'pi_cliente_id' => $datos_comprobante['pi_cliente_id'],
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
            'estado' => $datos_comprobante['estado'],
            'creado' => date('Y-m-d')
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
        $centros = Centro::where('empresa_id',$comprobante->empresa_id)->pluck('nombre','id');
        $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$comprobante->empresa_id)
                                        ->pluck('cuenta_contable','id');
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::query()
                                                        ->byEmpresa($comprobante->empresa_id)
                                                        ->where('estado','1')
                                                        ->pluck('nombre','id');
        return view('comprobantes.editar', compact('icono','header','comprobante','comprobante_detalles','total_debe','total_haber','centros','plan_cuentas','plan_cuentas_auxiliares'));
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
    {dd($request->all());
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
            if(isset($request->centro_id)){
                while($cont < count($request->centro_id)){
                    $datos_detalle = [
                        'comprobante_id' => $comprobante->id,
                        'empresa_id' => $empresa->id,
                        'pi_cliente_id' => $empresa->pi_cliente_id,
                        'tipo_cambio_id' => $tipo_cambio->id,
                        'user_id' => $user != null ? $user->id : 1,
                        'cargo_id' => $user != null ? $user->cargo_id : null,
                        'moneda_id' => $moneda->id,
                        'pais_id' => $moneda->pais_id,
                        'plan_cuenta_id' => $request->plan_cuenta_id[$cont],
                        'centro_id' => $request->centro_id[$cont],
                        'sub_centro_id' => $request->sub_centro_id[$cont],
                        'plan_cuenta_auxiliar_id' => $request->auxiliar_id[$cont],
                        'glosa' => $request->glosa[$cont],
                        'debe' => floatval(str_replace(",", "", $request->debe[$cont])),
                        'haber' => floatval(str_replace(",", "", $request->haber[$cont])),
                        'estado' => '1'
                    ];

                    $comprobante_detalle = ComprobanteDetalle::create($datos_detalle);

                    $cont++;
                }
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

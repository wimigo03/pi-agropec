<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\PlanCuenta;
use App\Models\User;
use App\Models\InicioMesFiscal;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\LibroMayorCuenta199Excel;
use DB;
use PDF;

class LibroMayorCuenta199Controller extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'LIBRO MAYOR POR CUENTA 1 A 99';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados_comprobantes = Comprobante::ESTADOS_SEARCH;
        return view('libro_mayor_cuenta_199.index', compact('icono','header','empresas','estados_comprobantes'));
    }

    public function getPlanCuentas(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $plan_cuentas = PlanCuenta::select(DB::raw('concat(codigo," ",nombre) as cuenta_contable'),'id')
                                        ->where('detalle','1')
                                        ->where('estado','1')
                                        ->where('empresa_id',$id)
                                        ->orderBy('codigo','asc')
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

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $plan_cuenta1 = PlanCuenta::find($request->plan_cuenta_id1);
        $plan_cuenta2 = PlanCuenta::find($request->plan_cuenta_id2);
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_mayor199 = $this->obtener_datos_mayor199($plan_cuenta1, $plan_cuenta2, $fecha_i, $fecha_f, $estados);
                if($datos_mayor199['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_mayor199['comprobantes'];
                $saldos_cuentas = $datos_mayor199['saldos_cuentas'];
                $total_debe = $datos_mayor199['total_debe'];
                $total_haber = $datos_mayor199['total_haber'];
                return view('libro_mayor_cuenta_199.index', compact('icono','header','empresa','fecha_i','fecha_f','plan_cuenta1','plan_cuenta2','comprobantes','saldos_cuentas','total_debe','total_haber'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function obtener_datos_mayor199($plan_cuenta1, $plan_cuenta2, $fecha_i, $fecha_f, $estados){
        try {
            $cuentas = PlanCuenta::whereBetween('codigo',[$plan_cuenta1->codigo,$plan_cuenta2->codigo])
                                        ->where('empresa_id',$plan_cuenta1->empresa_id)
                                        ->where('detalle','1')
                                        ->select('id','codigo')
                                        ->get()
                                        ->pluck('id')
                                        ->toArray();
            if(count($cuentas) == 0){
                throw new ValidationException('Error al procesar los datos');
            }
            $saldos_cuentas = [];
            foreach ($cuentas as $key => $value) {
                $saldos_cuentas[$value] = $this->obtener_saldo_por_cuenta($fecha_i, $value, $plan_cuenta1->empresa_id,$estados);
            }
            $comprobantes = DB::table('comprobante_detalles as a')
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->join('centros as c','c.id','a.centro_id')
                                ->join('sub_centros as d','d.id','a.sub_centro_id')
                                ->join('plan_cuentas as e','e.id','a.plan_cuenta_id')
                                ->leftjoin('plan_cuentas_auxiliares as f','f.id','a.plan_cuenta_auxiliar_id')
                                ->whereIn('a.plan_cuenta_id',$cuentas)
                                ->whereBetween('b.fecha',[$fecha_i,$fecha_f])
                                ->where('b.empresa_id',$plan_cuenta1->empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
                                ->orderBy('e.codigo','asc')
                                ->orderBy('b.fecha','asc')
                                ->select(
                                        'a.id',
                                        DB::raw("DATE_FORMAT(b.fecha,'%d-%m-%y') as fecha"),
                                        'b.nro_comprobante',
                                        DB::raw("CASE
                                            WHEN b.estado = 1 THEN 'BORRADOR'
                                            WHEN b.estado = 2 THEN 'APROBADO'
                                            ELSE 'N/A'
                                        END AS estado"),
                                        DB::raw("CASE
                                            WHEN b.estado = 1 THEN 'B'
                                            WHEN b.estado = 2 THEN 'A'
                                            ELSE 'N/A'
                                        END AS estado_abreviado"),
                                        'c.nombre as centro',
                                        'd.nombre as subcentro',
                                        'c.abreviatura as ab_centro',
                                        'd.abreviatura as ab_subcentro',
                                        'e.codigo as codigo_contable',
                                        'e.nombre as cuenta_contable',
                                        DB::raw("if(isnull(a.plan_cuenta_auxiliar_id),'',f.nombre) as auxiliar"),
                                        'a.nro_cheque',
                                        'a.glosa',
                                        'a.debe',
                                        'a.haber',
                                        'a.plan_cuenta_id')
                                ->get();
            $total_debe = $comprobantes->sum('debe');
            $total_haber = $comprobantes->sum('haber');
            return [
                'statusResult' => 200,
                'comprobantes' => $comprobantes,
                'saldos_cuentas' => $saldos_cuentas,
                'total_debe' => $total_debe,
                'total_haber' => $total_haber
            ];
        } catch (ValidationException $th) {
            return [
                'statusResult' => 500,
                'mensaje' => $th->getMessage()
            ];
        } catch (\Throwable $th) {
            return [
                'statusResult' => 500,
                'mensaje' => $th->getMessage()
            ];
        }
    }


    public function obtener_saldo_por_cuenta($fecha_i, $plan_cuenta_id, $empresa_id, $estados){
        $saldo = 0;
        $inicio_mes_fiscal = InicioMesFiscal::select('mes')->where('empresa_id',$empresa_id)->where('estado','1')->first();
        $anho = date("Y", strtotime($fecha_i));
        $mes = $inicio_mes_fiscal->mes;
        $dia = '01';
        $inicio_gestion = $anho . '-' . $mes .'-'. $dia . ' 00:00:00';
        $fecha_final = date('Y-m-d 23:59:59', strtotime($fecha_i) - 86400);
        if($fecha_final <= $inicio_gestion){
            $fecha_inicial = ($anho - 1) . '-' . $mes . '-' . $dia . ' 00:00:00';
        }else{
            $fecha_inicial = $anho . '-' . $mes . '-' . $dia . ' 00:00:00';
        }

        $sumar_restar = DB::table('comprobante_detalles as a')
                                ->join('comprobantes as b', 'b.id', 'a.comprobante_id')
                                ->where('a.plan_cuenta_id', $plan_cuenta_id)
                                ->whereBetween('b.fecha', [$fecha_inicial,$fecha_final])
                                ->where('b.empresa_id', $empresa_id)
                                ->where('a.estado', '1')
                                ->whereIn('b.estado',$estados)
                                ->orderBy('b.fecha', 'asc')
                                ->select('a.debe','a.haber')
                                ->get();
        $saldo = $sumar_restar->sum('debe') - $sumar_restar->sum('haber');
        return $saldo;
    }

    public function excel(Request $request)
    {
        $empresa = Empresa::find($request->empresa_id);
        $plan_cuenta1 = PlanCuenta::find($request->plan_cuenta_id1);
        $plan_cuenta2 = PlanCuenta::find($request->plan_cuenta_id2);
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_mayor199 = $this->obtener_datos_mayor199($plan_cuenta1, $plan_cuenta2, $fecha_i, $fecha_f, $estados);
                if($datos_mayor199['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_mayor199['comprobantes'];
                $saldos_cuentas = $datos_mayor199['saldos_cuentas'];
                $total_debe = $datos_mayor199['total_debe'];
                $total_haber = $datos_mayor199['total_haber'];
            return Excel::download(new LibroMayorCuenta199Excel($empresa,$fecha_i,$fecha_f,$plan_cuenta1,$plan_cuenta2,$comprobantes,$saldos_cuentas,$total_debe,$total_haber),'Libro_Mayor_Cuenta_1A99.xlsx');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function pdf(Request $request)
    {
        $empresa = Empresa::find($request->empresa_id);
        $plan_cuenta1 = PlanCuenta::find($request->plan_cuenta_id1);
        $plan_cuenta2 = PlanCuenta::find($request->plan_cuenta_id2);
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_mayor199 = $this->obtener_datos_mayor199($plan_cuenta1, $plan_cuenta2, $fecha_i, $fecha_f, $estados);
                if($datos_mayor199['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_mayor199['comprobantes'];
                $saldos_cuentas = $datos_mayor199['saldos_cuentas'];
                $total_debe = $datos_mayor199['total_debe'];
                $total_haber = $datos_mayor199['total_haber'];
                $pdf = PDF::loadView('libro_mayor_cuenta_199.pdf',compact(['empresa','fecha_i','fecha_f','plan_cuenta1','plan_cuenta2','comprobantes','saldos_cuentas','total_debe','total_haber']));
                $pdf->setPaper('LETTER', 'landscape');
                return $pdf->download('Libro_Mayor_Cuenta_1A99.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

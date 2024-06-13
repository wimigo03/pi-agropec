<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\PlanCuenta;
use App\Models\PlanCuentaAuxiliar;
use App\Models\User;
use App\Models\InicioMesFiscal;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\LibroMayorCuentaYAuxiliarExcel;
use DB;
use PDF;

class LibroMayorCuentaYAuxiliarController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'LIBRO MAYOR POR CUENTA Y AUXILIAR';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados_comprobantes = Comprobante::ESTADOS_SEARCH;
        return view('libro_mayor_cuenta_general_y_auxiliar.index', compact('icono','header','empresas','estados_comprobantes'));
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

    public function getPlanCuentasAuxiliares(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $plan_cuentas_auxiliares = PlanCuentaAuxiliar::select('nombre','id')
                                                ->where('estado','1')
                                                ->where('empresa_id',$id)
                                                ->orderBy('nombre','asc')
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

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa_id = $request->empresa_id;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $plan_cuenta_id = $request->plan_cuenta_id;
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_cuenta_y_auxiliar = $this->procesarLibroMayorCuentaYAuxiliar($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_id,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_cuenta_y_auxiliar['comprobantes'];
                $saldo_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_cuenta'];
                $saldo_final_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_final_cuenta'];
                $empresa = $libro_mayor_cuenta_y_auxiliar['empresa'];
                $plan_cuenta = $libro_mayor_cuenta_y_auxiliar['plan_cuenta'];
                $total_debe_cuenta = $libro_mayor_cuenta_y_auxiliar['total_debe_cuenta'];
                $total_haber_cuenta = $libro_mayor_cuenta_y_auxiliar['total_haber_cuenta'];
                $saldo_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_auxiliar'];
                $saldo_final_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_final_auxiliar'];
                $plan_cuenta_auxiliar = $libro_mayor_cuenta_y_auxiliar['plan_cuenta_auxiliar'];
                $total_debe_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_debe_auxiliar'];
                $total_haber_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_haber_auxiliar'];
                return view('libro_mayor_cuenta_general_y_auxiliar.index', compact('icono','header','empresa','fecha_i','fecha_f','plan_cuenta','comprobantes','saldo_cuenta','saldo_final_cuenta','total_debe_cuenta','total_haber_cuenta','saldo_auxiliar','saldo_final_auxiliar','plan_cuenta_auxiliar','total_debe_auxiliar','total_haber_auxiliar'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function procesarLibroMayorCuentaYAuxiliar($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_id,$plan_cuenta_auxiliar_id,$estados){
        $comprobantes = DB::table('comprobante_detalles as a')
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->join('centros as c','c.id','a.centro_id')
                                ->join('sub_centros as d','d.id','a.sub_centro_id')
                                ->where('a.plan_cuenta_id',$plan_cuenta_id)
                                ->where('a.plan_cuenta_auxiliar_id',$plan_cuenta_auxiliar_id)
                                ->whereBetween('b.fecha',[$fecha_i,$fecha_f])
                                ->where('b.empresa_id',$empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
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
                                        'a.nro_cheque',
                                        'a.glosa',
                                        'a.debe',
                                        'a.haber')
                                ->get();

        $plan_cuenta = PlanCuenta::find($plan_cuenta_id);
        $empresa = Empresa::find($empresa_id);
        $saldo_cuenta = $this->obtenerSaldoPorCuenta($fecha_i,$plan_cuenta_id,$empresa_id,$estados);
        $saldo_final_cuenta = $saldo_cuenta;
        $total_debe_cuenta = $comprobantes->sum('debe');
        $total_haber_cuenta = $comprobantes->sum('haber');
        $saldo_final_cuenta += $total_debe_cuenta - $total_haber_cuenta;

        $plan_cuenta_auxiliar = PlanCuentaAuxiliar::find($plan_cuenta_auxiliar_id);
        $saldo_auxiliar = $this->obtenerSaldoPorAuxiliar($fecha_i,$plan_cuenta_auxiliar_id,$empresa_id,$estados);
        $saldo_final_auxiliar = $saldo_auxiliar;
        $total_debe_auxiliar = $comprobantes->sum('debe');
        $total_haber_auxiliar = $comprobantes->sum('haber');
        $saldo_final_auxiliar += $total_debe_auxiliar - $total_haber_auxiliar;

        return [
            'comprobantes' => $comprobantes,
            'saldo_cuenta' => $saldo_cuenta,
            'saldo_final_cuenta' => $saldo_final_cuenta,
            'empresa' => $empresa,
            'plan_cuenta' => $plan_cuenta,
            'total_debe_cuenta' => $total_debe_cuenta,
            'total_haber_cuenta' => $total_haber_cuenta,
            'plan_cuenta_auxiliar' => $plan_cuenta_auxiliar,
            'saldo_auxiliar' => $saldo_auxiliar,
            'saldo_final_auxiliar' => $saldo_final_auxiliar,
            'total_debe_auxiliar' => $total_debe_auxiliar,
            'total_haber_auxiliar' => $total_haber_auxiliar
        ];
    }

    public function obtenerSaldoPorCuenta($fecha_i,$plan_cuenta_id,$empresa_id,$estados){
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
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->where('a.plan_cuenta_id',$plan_cuenta_id)
                                ->whereBetween('b.fecha',[$fecha_inicial,$fecha_final])
                                ->where('b.empresa_id',$empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
                                ->select('a.debe','a.haber')
                                ->get();
        $saldo = $sumar_restar->sum('debe') - $sumar_restar->sum('haber');
        return $saldo;
    }

    public function obtenerSaldoPorAuxiliar($fecha_i,$plan_cuenta_auxiliar_id,$empresa_id,$estados){
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
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->where('a.plan_cuenta_auxiliar_id',$plan_cuenta_auxiliar_id)
                                ->whereBetween('b.fecha',[$fecha_inicial,$fecha_final])
                                ->where('b.empresa_id',$empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
                                ->select('a.debe','a.haber')
                                ->get();
        $saldo = $sumar_restar->sum('debe') - $sumar_restar->sum('haber');

        return $saldo;
    }

    public function excel(Request $request)
    {
        $empresa_id = $request->empresa_id;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $plan_cuenta_id = $request->plan_cuenta_id;
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_cuenta_y_auxiliar = $this->procesarLibroMayorCuentaYAuxiliar($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_id,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_cuenta_y_auxiliar['comprobantes'];
                $saldo_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_cuenta'];
                $saldo_final_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_final_cuenta'];
                $empresa = $libro_mayor_cuenta_y_auxiliar['empresa'];
                $plan_cuenta = $libro_mayor_cuenta_y_auxiliar['plan_cuenta'];
                $total_debe_cuenta = $libro_mayor_cuenta_y_auxiliar['total_debe_cuenta'];
                $total_haber_cuenta = $libro_mayor_cuenta_y_auxiliar['total_haber_cuenta'];
                $saldo_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_auxiliar'];
                $saldo_final_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_final_auxiliar'];
                $plan_cuenta_auxiliar = $libro_mayor_cuenta_y_auxiliar['plan_cuenta_auxiliar'];
                $total_debe_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_debe_auxiliar'];
                $total_haber_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_haber_auxiliar'];
            return Excel::download(new LibroMayorCuentaYAuxiliarExcel($libro_mayor_cuenta_y_auxiliar,$comprobantes,$fecha_i,$fecha_f,$saldo_cuenta,$saldo_final_cuenta,$empresa,$plan_cuenta,$total_debe_cuenta,$total_haber_cuenta,$saldo_auxiliar,$saldo_final_auxiliar,$plan_cuenta_auxiliar,$total_debe_auxiliar,$total_haber_auxiliar),'Libro_Mayor_Cuenta_y_auxiliar.xlsx');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function pdf(Request $request)
    {
        $empresa_id = $request->empresa_id;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $plan_cuenta_id = $request->plan_cuenta_id;
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_cuenta_y_auxiliar = $this->procesarLibroMayorCuentaYAuxiliar($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_id,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_cuenta_y_auxiliar['comprobantes'];
                $saldo_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_cuenta'];
                $saldo_final_cuenta = $libro_mayor_cuenta_y_auxiliar['saldo_final_cuenta'];
                $empresa = $libro_mayor_cuenta_y_auxiliar['empresa'];
                $plan_cuenta = $libro_mayor_cuenta_y_auxiliar['plan_cuenta'];
                $total_debe_cuenta = $libro_mayor_cuenta_y_auxiliar['total_debe_cuenta'];
                $total_haber_cuenta = $libro_mayor_cuenta_y_auxiliar['total_haber_cuenta'];
                $saldo_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_auxiliar'];
                $saldo_final_auxiliar = $libro_mayor_cuenta_y_auxiliar['saldo_final_auxiliar'];
                $plan_cuenta_auxiliar = $libro_mayor_cuenta_y_auxiliar['plan_cuenta_auxiliar'];
                $total_debe_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_debe_auxiliar'];
                $total_haber_auxiliar = $libro_mayor_cuenta_y_auxiliar['total_haber_auxiliar'];

                $pdf = PDF::loadView('libro_mayor_cuenta_general_y_auxiliar.pdf',compact(['empresa','fecha_i','fecha_f','plan_cuenta','comprobantes','saldo_cuenta','saldo_final_cuenta','total_debe_cuenta','total_haber_cuenta','saldo_auxiliar','saldo_final_auxiliar','plan_cuenta_auxiliar','total_debe_auxiliar','total_haber_auxiliar']));
                $pdf->setPaper('LETTER', 'portrait');
                return $pdf->download('Libro_Mayor_Cuenta_Y_Auxiliar.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

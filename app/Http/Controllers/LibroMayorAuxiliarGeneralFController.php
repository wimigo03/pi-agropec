<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\ComprobanteF;
use App\Models\ComprobanteFDetalle;
use App\Models\PlanCuentaAuxiliar;
use App\Models\User;
use App\Models\InicioMesFiscal;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\LibroMayorAuxiliarGeneralFExcel;
use DB;
use PDF;

class LibroMayorAuxiliarGeneralFController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'LIBRO MAYOR POR AUXILIAR GENERAL';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados_comprobantes = Comprobante::ESTADOS_SEARCH;
        return view('libro_mayor_auxiliar_general_f.index', compact('icono','header','empresas','estados_comprobantes'));
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
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_auxiliar_general = $this->procesarLibroMayorAuxiliarGeneral($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_auxiliar_general['comprobantes'];
                $empresa = $libro_mayor_auxiliar_general['empresa'];
                $plan_cuenta_auxiliar = $libro_mayor_auxiliar_general['plan_cuenta_auxiliar'];
                $saldo = $libro_mayor_auxiliar_general['saldo'];
                $saldo_final = $libro_mayor_auxiliar_general['saldo_final'];
                $total_debe = $libro_mayor_auxiliar_general['total_debe'];
                $total_haber = $libro_mayor_auxiliar_general['total_haber'];
        return view('libro_mayor_auxiliar_general_f.index', compact('icono','header','empresa','fecha_i','fecha_f','plan_cuenta_auxiliar','comprobantes','saldo','saldo_final','total_debe','total_haber'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function procesarLibroMayorAuxiliarGeneral($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_auxiliar_id,$estados){
        $comprobantes = DB::table('comprobantef_detalles as a')
                                ->join('comprobantesf as b','b.id','a.comprobantef_id')
                                ->join('plan_cuentas as c','c.id','a.plan_cuenta_id')
                                ->join('centros as d','d.id','a.centro_id')
                                ->join('sub_centros as e','e.id','a.sub_centro_id')
                                ->whereBetween('b.fecha',[$fecha_i,$fecha_f])
                                ->where('a.plan_cuenta_auxiliar_id',$plan_cuenta_auxiliar_id)
                                ->where('b.empresa_id',$empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
                                ->orderBy('b.fecha','asc')
                                ->select(
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
                                        'c.codigo',
                                        'c.nombre as plan_cuenta',
                                        'd.nombre as centro',
                                        'e.nombre as subcentro',
                                        'a.nro_cheque',
                                        'a.glosa',
                                        'a.debe',
                                        'a.haber'
                                        )
                                ->get();

        $empresa = Empresa::find($empresa_id);
        $plan_cuenta_auxiliar = PlanCuentaAuxiliar::find($plan_cuenta_auxiliar_id);
        $saldo = $this->obtenerSaldoPorAuxiliar($fecha_i,$plan_cuenta_auxiliar_id,$empresa_id,$estados);
        $saldo_final = $saldo;
        $total_debe = $comprobantes->sum('debe');
        $total_haber = $comprobantes->sum('haber');
        $saldo_final += $total_debe - $total_haber;
        return [
            'comprobantes' => $comprobantes,
            'empresa' => $empresa,
            'plan_cuenta_auxiliar' => $plan_cuenta_auxiliar,
            'saldo' => $saldo,
            'saldo_final' => $saldo_final,
            'total_debe' => $total_debe,
            'total_haber' => $total_haber
        ];
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

        $sumar_restar = DB::table('comprobantef_detalles as a')
                                ->join('comprobantesf as b','b.id','a.comprobantef_id')
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
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_auxiliar_general = $this->procesarLibroMayorAuxiliarGeneral($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_auxiliar_general['comprobantes'];
                $empresa = $libro_mayor_auxiliar_general['empresa'];
                $plan_cuenta_auxiliar = $libro_mayor_auxiliar_general['plan_cuenta_auxiliar'];
                $saldo = $libro_mayor_auxiliar_general['saldo'];
                $saldo_final = $libro_mayor_auxiliar_general['saldo_final'];
                $total_debe = $libro_mayor_auxiliar_general['total_debe'];
                $total_haber = $libro_mayor_auxiliar_general['total_haber'];
                $fecha_i = $request->fecha_i;
                $fecha_f = $request->fecha_f;
            return Excel::download(new LibroMayorAuxiliarGeneralFExcel($libro_mayor_auxiliar_general,$comprobantes,$empresa,$plan_cuenta_auxiliar,$saldo,$saldo_final,$total_debe,$total_haber,$fecha_i,$fecha_f),'Libro_Mayor_Auxiliar_General.xlsx');
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
        $plan_cuenta_auxiliar_id = $request->plan_cuenta_auxiliar_id;
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_auxiliar_general = $this->procesarLibroMayorAuxiliarGeneral($empresa_id,$fecha_i,$fecha_f,$plan_cuenta_auxiliar_id,$estados);
                $comprobantes = $libro_mayor_auxiliar_general['comprobantes'];
                $empresa = $libro_mayor_auxiliar_general['empresa'];
                $plan_cuenta_auxiliar = $libro_mayor_auxiliar_general['plan_cuenta_auxiliar'];
                $saldo = $libro_mayor_auxiliar_general['saldo'];
                $saldo_final = $libro_mayor_auxiliar_general['saldo_final'];
                $total_debe = $libro_mayor_auxiliar_general['total_debe'];
                $total_haber = $libro_mayor_auxiliar_general['total_haber'];
                $fecha_i = $request->fecha_i;
                $fecha_f = $request->fecha_f;
                $pdf = PDF::loadView('libro_mayor_auxiliar_general_f.pdf',compact(['libro_mayor_auxiliar_general','comprobantes','empresa','plan_cuenta_auxiliar','saldo','saldo_final','total_debe','total_haber','fecha_i','fecha_f']));
                $pdf->setPaper('LETTER', 'portrait');
                return $pdf->download('Libro_Mayor_Auxiliar_General.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

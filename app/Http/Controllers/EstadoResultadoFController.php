<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\ComprobanteF;
use App\Models\ComprobanteFDetalle;
use App\Models\PlanCuenta;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\EstadoResultadoFExcel;
use DB;
use PDF;

class EstadoResultadoFController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'ESTADO DE RESULTADO';

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $show = '0';
        return view('estado_resultado_f.index', compact('icono','header','empresa','show'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $fecha_i = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_i)));
        $fecha_f = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_f)));

        if($fecha_i > $fecha_f){
            return redirect()->back()->with('info_message', '[LA FECHA INICIAL NO PUEDE SER MAYOR A LA FECHA FINAL]')->withInput();
        }

        if($request->estado == '_todos_'){
            $status = ['1','2'];
        }else{
            $status = [$request->estado];
        }
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $estado_resultado = $this->procesarEstadoResultado($fecha_i, $fecha_f, $status, $request->empresa_id);
                $empresa = $estado_resultado['empresa'];
                $ingresos = $estado_resultado['ingresos'];
                $costos = $estado_resultado['costos'];
                $gastos = $estado_resultado['gastos'];
                $nroMaxColumna = $estado_resultado['nroMaxColumna'];
                $total = $estado_resultado['total'];
                $totales = $estado_resultado['totales'];
                $show = '1';
                return view('estado_resultado_f.index', compact('icono','header','empresa','ingresos','costos','gastos','nroMaxColumna','total','totales','show'));
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function procesarEstadoResultado($fecha_i, $fecha_f, $status, $empresa_id){
        $empresa = Empresa::find($empresa_id);
        $ingresos = PlanCuenta::where('codigo','like','4%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $ingresos = PlanCuenta::orderByCodigo($ingresos);
        $costos = PlanCuenta::where('codigo','like','5%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $costos = PlanCuenta::orderByCodigo($costos);
        $gastos = PlanCuenta::where('codigo','like','6%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $gastos = PlanCuenta::orderByCodigo($gastos);

        $totales = [];
        $cuentas = array();

        $tipoOperacion = "-+";
        $planCuentaId = $ingresos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$fecha_i,$fecha_f,$status,$empresa->id,$tipoOperacion,$totales,$cuentas);
        $tipoOperacion = "+-";
        $planCuentaId = $costos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$fecha_i,$fecha_f,$status,$empresa->id,$tipoOperacion,$totales,$cuentas);
        $planCuentaId = $gastos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$fecha_i,$fecha_f,$status,$empresa->id,$tipoOperacion,$totales,$cuentas);

        $nroMaxColumna = 5;
        $total = $totales[$ingresos[0]->id] - $totales[$costos[0]->id] - $totales[$gastos[0]->id];

        return [
            'empresa' => $empresa,
            'ingresos' => $ingresos,
            'costos' => $costos,
            'gastos' => $gastos,
            'nroMaxColumna' => $nroMaxColumna,
            'total' => $total,
            'totales' => $totales,
        ];
    }

    public function sum_total_account_gestion($plan_cuenta_id,$start_date,$end_date,$status,$empresa_id,$tipoOperacion, &$totales, &$cuentas){
        $totalFinal = 0;
        $planCuenta = PlanCuenta::find($plan_cuenta_id);
        if($planCuenta->detalle == '1'){
            $comprobantes = ComprobanteFDetalle::join('comprobantesf as c','c.id','comprobantef_detalles.comprobantef_id')
                                                    ->where('comprobantef_detalles.plan_cuenta_id',$plan_cuenta_id)
                                                    ->whereBetween('c.fecha',[$start_date,$end_date])
                                                    ->where('c.empresa_id',$empresa_id)
                                                    ->whereIn('c.estado',$status)
                                                    ->select('c.id',
                                                                'c.fecha',
                                                                'comprobantef_detalles.plan_cuenta_id',
                                                                'c.nro_comprobante',
                                                                'comprobantef_detalles.glosa',
                                                                'comprobantef_detalles.debe',
                                                                'comprobantef_detalles.haber',
                                                                'comprobantef_detalles.plan_cuenta_id',
                                                                'comprobantef_detalles.nro_cheque',
                                                                'comprobantef_detalles.orden_cheque',
                                                                'c.estado',
                                                                'comprobantef_detalles.plan_cuenta_auxiliar_id as cuentaAux')
                                                    ->get();
            $total = 0;
            foreach ($comprobantes as $comp) {
                if($tipoOperacion == "-+"){
                    $total += $comp->haber;
                    $total -= $comp->debe;
                }else{
                    $total += $comp->debe;
                    $total -= $comp->haber;
                }
            }
            $totalFinal += $total;
            if(!in_array($planCuenta->id,$cuentas)){
                $totales[$planCuenta->id] = $totalFinal;
                array_push($cuentas,$planCuenta->id);
            }
        }else{
            $childs = PlanCuenta::where('parent_id',$planCuenta->id)->get();
            foreach ($childs as $child ) {
                $totalFinalActual = $this->sum_total_account_gestion($child->id,$start_date,$end_date,$status,$empresa_id,$tipoOperacion,$totales,$cuentas);
                $totalFinal += $totalFinalActual;
                if(!in_array($child->id,$cuentas)){
                    $totales[$child->id] = $totalFinalActual;
                    array_push($cuentas,$child->id);
                }
            }
        }
        return $totalFinal;
    }

    public function excel(Request $request)
    {
        $fecha_i = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_i)));
        $fecha_f = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_f)));

        if($fecha_i > $fecha_f){
            return redirect()->back()->with('info_message', '[LA FECHA INICIAL NO PUEDE SER MAYOR A LA FECHA FINAL]')->withInput();
        }

        if($request->estado == '_todos_'){
            $status = ['1','2'];
        }else{
            $status = [$request->estado];
        }

        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $estado_resultado = $this->procesarEstadoResultado($fecha_i, $fecha_f, $status, $request->empresa_id);
                $empresa = $estado_resultado['empresa'];
                $ingresos = $estado_resultado['ingresos'];
                $costos = $estado_resultado['costos'];
                $gastos = $estado_resultado['gastos'];
                $nroMaxColumna = $estado_resultado['nroMaxColumna'];
                $total = $estado_resultado['total'];
                $totales = $estado_resultado['totales'];
            return Excel::download(new EstadoResultadoFExcel($empresa,$ingresos,$costos,$gastos,$nroMaxColumna,$total,$totales),'Estado_de_resultados.xlsx');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function pdf(Request $request)
    {
        $fecha_i = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_i)));
        $fecha_f = date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_f)));

        if($fecha_i > $fecha_f){
            return redirect()->back()->with('info_message', '[LA FECHA INICIAL NO PUEDE SER MAYOR A LA FECHA FINAL]')->withInput();
        }

        if($request->estado == '_todos_'){
            $status = ['1','2'];
        }else{
            $status = [$request->estado];
        }

        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $estado_resultado = $this->procesarEstadoResultado($fecha_i, $fecha_f, $status, $request->empresa_id);
                $empresa = $estado_resultado['empresa'];
                $ingresos = $estado_resultado['ingresos'];
                $costos = $estado_resultado['costos'];
                $gastos = $estado_resultado['gastos'];
                $nroMaxColumna = $estado_resultado['nroMaxColumna'];
                $total = $estado_resultado['total'];
                $totales = $estado_resultado['totales'];
                $pdf = PDF::loadView('estado_resultado_f.pdf',compact(['empresa','ingresos','costos','gastos','nroMaxColumna','total','totales','fecha_i','fecha_f']));
                $pdf->setPaper('LETTER', 'portrait');
                return $pdf->download('Estado_resultado.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

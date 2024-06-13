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
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\BalanceGeneralExcel;
use DB;
use PDF;

class BalanceGeneralController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'BALANCE GENERAL';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $show = '0';
        return view('balance_general.index', compact('icono','header','empresas','show'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));

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
                return view('balance_general.index', compact('icono','header','empresas','empresa','ingresos','costos','gastos','nroMaxColumna','total','totales','show'));
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function procesarEstadoResultado($fecha_i, $fecha_f, $status, $empresa_id){
        $empresa = Empresa::find($empresa_id);
        $ingresos = PlanCuenta::where('codigo','like','1%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $ingresos = PlanCuenta::orderByCodigo($ingresos);
        $costos = PlanCuenta::where('codigo','like','2%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $costos = PlanCuenta::orderByCodigo($costos);
        $gastos = PlanCuenta::where('codigo','like','3%')->where('empresa_id',$empresa->id)->where('estado','1')->get();
        $gastos = PlanCuenta::orderByCodigo($gastos);

        $totales = [];
        $cuentas = array();

        $tipoOperacion = "+-";
        $planCuentaId = $ingresos[0]->id;
        $totales[$planCuentaId] = $this->sum_total_account_gestion($planCuentaId,$fecha_i,$fecha_f,$status,$empresa->id,$tipoOperacion,$totales,$cuentas);
        $tipoOperacion = "-+";
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
            $comprobantes = ComprobanteDetalle::join('comprobantes as c','c.id','comprobante_detalles.comprobante_id')
                                                    ->where('comprobante_detalles.plan_cuenta_id',$plan_cuenta_id)
                                                    ->whereBetween('c.fecha',[$start_date,$end_date])
                                                    ->where('c.empresa_id',$empresa_id)
                                                    ->whereIn('c.estado',$status)
                                                    ->where('comprobante_detalles.estado','1')
                                                    ->select('c.id',
                                                                'c.fecha',
                                                                'comprobante_detalles.plan_cuenta_id',
                                                                'c.nro_comprobante',
                                                                'comprobante_detalles.glosa',
                                                                'comprobante_detalles.debe',
                                                                'comprobante_detalles.haber',
                                                                'comprobante_detalles.plan_cuenta_id',
                                                                'comprobante_detalles.nro_cheque',
                                                                'comprobante_detalles.orden_cheque',
                                                                'c.estado',
                                                                'comprobante_detalles.plan_cuenta_auxiliar_id as cuentaAux')
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
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));

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
            return Excel::download(new BalanceGeneralExcel($empresa,$ingresos,$costos,$gastos,$nroMaxColumna,$total,$totales,$fecha_f),'Balance_General.xlsx');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function pdf(Request $request)
    {
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));

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
                $pdf = PDF::loadView('balance_general.pdf',compact(['empresa','ingresos','costos','gastos','nroMaxColumna','total','totales','fecha_f']));
                $pdf->setPaper('LETTER', 'portrait');
                return $pdf->download('Balance_General.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

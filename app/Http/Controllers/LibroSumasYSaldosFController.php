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
use App\Exportar\LibroSumasYSaldosExcel;
use DB;
use PDF;

class LibroSumasYSaldosFController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'LIBRO SUMAS Y SALDOS';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados_comprobantes = Comprobante::ESTADOS_SEARCH;
        return view('libro_sumas_y_saldos_f.index', compact('icono','header','empresas','estados_comprobantes'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_sumas_y_saldos = $this->obtener_datos_sumas_y_saldos($request->empresa_id, $fecha_i, $fecha_f, $estados);
                if($datos_sumas_y_saldos['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_sumas_y_saldos['comprobantes'];
                $plan_cuentas = $datos_sumas_y_saldos['plan_cuentas'];
                $plan_cuentas_codigo = $datos_sumas_y_saldos['plan_cuentas_codigo'];
                $plan_cuentas_ids = $datos_sumas_y_saldos['plan_cuentas_ids'];
                $empresa = $datos_sumas_y_saldos['empresa'];
                return view('libro_sumas_y_saldos_f.index', compact('icono','header','fecha_i','fecha_f','comprobantes','empresa','plan_cuentas','plan_cuentas_codigo','plan_cuentas_ids'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function obtener_datos_sumas_y_saldos($empresa_id, $fecha_i, $fecha_f, $estados){
        try {
            $comprobantes = DB::table('comprobantef_detalles as a')
                                ->join('comprobantesf as b','b.id','a.comprobantef_id')
                                ->join('plan_cuentas as c','c.id','a.plan_cuenta_id')
                                ->whereBetween('b.fecha',[$fecha_i,$fecha_f])
                                ->where('b.empresa_id',$empresa_id)
                                ->where('a.estado','1')
                                ->whereIn('b.estado',$estados)
                                ->selectRaw('plan_cuenta_id,sum(a.debe) as total_debe_mayor,sum(a.haber) as total_haber_mayor')
                                ->groupBy('a.plan_cuenta_id')
                                ->orderBy('c.codigo','asc')
                                ->get();

            $plan_cuentas = PlanCuenta::query()
                                        ->byPiCliente(Auth::user()->pi_cliente_id)
                                        ->byEmpresa($empresa_id)
                                        ->where('detalle','1')
                                        ->select('nombre','id')
                                        ->get()
                                        ->pluck('nombre','id');
            $plan_cuentas_codigo = PlanCuenta::query()
                                        ->byPiCliente(Auth::user()->pi_cliente_id)
                                        ->byEmpresa($empresa_id)
                                        ->where('detalle','1')
                                        ->select('codigo','id')
                                        ->get()
                                        ->pluck('codigo','id');
            $plan_cuentas_ids = PlanCuenta::query()
                                        ->byPiCliente(Auth::user()->pi_cliente_id)
                                        ->byEmpresa($empresa_id)
                                        ->where('detalle','1')
                                        ->get()
                                        ->pluck('id')
                                        ->toArray();
            $empresa = Empresa::find($empresa_id);

            return [
                'statusResult' => 200,
                'comprobantes' => $comprobantes,
                'plan_cuentas' => $plan_cuentas,
                'plan_cuentas_codigo' => $plan_cuentas_codigo,
                'plan_cuentas_ids' => $plan_cuentas_ids,
                'empresa' => $empresa
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

    public function excel(Request $request)
    {
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_sumas_y_saldos = $this->obtener_datos_sumas_y_saldos($request->empresa_id, $fecha_i, $fecha_f, $estados);
                if($datos_sumas_y_saldos['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_sumas_y_saldos['comprobantes'];
                $plan_cuentas = $datos_sumas_y_saldos['plan_cuentas'];
                $plan_cuentas_codigo = $datos_sumas_y_saldos['plan_cuentas_codigo'];
                $plan_cuentas_ids = $datos_sumas_y_saldos['plan_cuentas_ids'];
                $empresa = $datos_sumas_y_saldos['empresa'];
            return Excel::download(new LibroSumasYSaldosExcel($fecha_i,$fecha_f,$comprobantes,$empresa,$plan_cuentas,$plan_cuentas_codigo,$plan_cuentas_ids),'Libro_SumasYSaldos.xlsx');
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
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $datos_sumas_y_saldos = $this->obtener_datos_sumas_y_saldos($request->empresa_id, $fecha_i, $fecha_f, $estados);
                if($datos_sumas_y_saldos['statusResult'] != 200){
                    return back()->withInput()->with('info_message', '[ERROR] al procesar los datos.');
                }

                $comprobantes = $datos_sumas_y_saldos['comprobantes'];
                $plan_cuentas = $datos_sumas_y_saldos['plan_cuentas'];
                $plan_cuentas_codigo = $datos_sumas_y_saldos['plan_cuentas_codigo'];
                $plan_cuentas_ids = $datos_sumas_y_saldos['plan_cuentas_ids'];
                $empresa = $datos_sumas_y_saldos['empresa'];
                $pdf = PDF::loadView('libro_sumas_y_saldos_f.pdf',compact(['fecha_i','fecha_f','comprobantes','empresa','plan_cuentas','plan_cuentas_codigo','plan_cuentas_ids']));
                $pdf->setPaper('LETTER', 'portrait');
                return $pdf->download('Libro_Sumas_Y_Saldos.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

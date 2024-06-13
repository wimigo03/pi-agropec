<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\Centro;
use App\Models\SubCentro;
use App\Models\User;
use App\Models\InicioMesFiscal;
use Maatwebsite\Excel\Facades\Excel;
use App\Exportar\LibroMayorCentroExcel;
use DB;
use PDF;

class LibroMayorCentroController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'LIBRO MAYOR POR CENTRO';

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $estados_comprobantes = Comprobante::ESTADOS_SEARCH;
        return view('libro_mayor_centro.index', compact('icono','header','empresas','estados_comprobantes'));
    }

    public function getCentros(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $centros = centro::select('nombre','id')
                            ->where('estado','1')
                            ->where('empresa_id',$id)
                            ->orderBy('id','asc')
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

    public function getSubCentros(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $subcentros = Subcentro::select('nombre','id')
                                    ->where('estado','1')
                                    ->where('centro_id',$id)
                                    ->orderBy('id','asc')
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

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa_id = $request->empresa_id;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        $sub_centro_id = $request->sub_centro_id;
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_por_centro = $this->obtener_datos_mayor_por_centro($empresa_id,$fecha_i,$fecha_f,$estados,$sub_centro_id);
                $comprobantes = $libro_mayor_por_centro['comprobantes'];
                $empresa = $libro_mayor_por_centro['empresa'];
                $sub_centro = $libro_mayor_por_centro['sub_centro'];
                $total_debe = $libro_mayor_por_centro['total_debe'];
                $total_haber = $libro_mayor_por_centro['total_haber'];
                return view('libro_mayor_centro.index', compact('icono','header','empresa','fecha_i','fecha_f','comprobantes','sub_centro','total_debe','total_haber'));
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function obtener_datos_mayor_por_centro($empresa_id,$fecha_i,$fecha_f,$estados,$sub_centro_id){
        $comprobantes = DB::table('comprobante_detalles as a')
                                ->join('comprobantes as b','b.id','a.comprobante_id')
                                ->join('plan_cuentas as c','c.id','a.plan_cuenta_id')
                                ->leftjoin('plan_cuentas_auxiliares as d','d.id','a.plan_cuenta_auxiliar_id')
                                ->where('a.sub_centro_id',$sub_centro_id)
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
                                        DB::raw('concat(c.codigo," ",c.nombre) as cuenta_contable'),
                                        DB::raw("if(isnull(a.plan_cuenta_auxiliar_id),'',d.nombre) as auxiliar"),
                                        'a.nro_cheque',
                                        'a.glosa',
                                        'a.debe',
                                        'a.haber')
                                ->get();

        $empresa = Empresa::find($empresa_id);
        $sub_centro = SubCentro::find($sub_centro_id);
        $total_debe = $comprobantes->sum('debe');
        $total_haber = $comprobantes->sum('haber');
        return [
            'comprobantes' => $comprobantes,
            'empresa' => $empresa,
            'sub_centro' => $sub_centro,
            'total_debe' => $total_debe,
            'total_haber' => $total_haber
        ];
    }

    public function excel(Request $request)
    {
        $empresa_id = $request->empresa_id;
        $fecha_i = date('Y-m-d', strtotime($request->fecha_i));
        $fecha_f = date('Y-m-d', strtotime($request->fecha_f));
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        $sub_centro_id = $request->sub_centro_id;
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_por_centro = $this->obtener_datos_mayor_por_centro($empresa_id,$fecha_i,$fecha_f,$estados,$sub_centro_id);
                $comprobantes = $libro_mayor_por_centro['comprobantes'];
                $empresa = $libro_mayor_por_centro['empresa'];
                $sub_centro = $libro_mayor_por_centro['sub_centro'];
                $total_debe = $libro_mayor_por_centro['total_debe'];
                $total_haber = $libro_mayor_por_centro['total_haber'];
            return Excel::download(new LibroMayorCentroExcel($empresa,$fecha_i,$fecha_f,$comprobantes,$sub_centro,$total_debe,$total_haber),'Libro_Mayor_Por_Centro.xlsx');
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
        $estados = $request->estado == '_TODOS_' ? ['1','2'] : [$request->estado];
        $sub_centro_id = $request->sub_centro_id;
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $libro_mayor_por_centro = $this->obtener_datos_mayor_por_centro($empresa_id,$fecha_i,$fecha_f,$estados,$sub_centro_id);
                $comprobantes = $libro_mayor_por_centro['comprobantes'];
                $empresa = $libro_mayor_por_centro['empresa'];
                $sub_centro = $libro_mayor_por_centro['sub_centro'];
                $total_debe = $libro_mayor_por_centro['total_debe'];
                $total_haber = $libro_mayor_por_centro['total_haber'];
                $pdf = PDF::loadView('libro_mayor_centro.pdf',compact(['empresa','fecha_i','fecha_f','comprobantes','sub_centro','total_debe','total_haber']));
                $pdf->setPaper('LETTER', 'landscape');
                return $pdf->download('Libro_Mayor_Centro.pdf');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }
}

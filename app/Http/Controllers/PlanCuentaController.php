<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\PlanCuenta;
use App\Models\Moneda;
use Auth;

class PlanCuentaController extends Controller
{
    const ICONO = 'fa-regular fa-chart-bar fa-fw';
    const INDEX = 'PLAN DE CUENTAS';
    const CREATE = 'REGISTRAR PLAN DE CUENTA';
    const EDITAR = 'MODIFICAR PLAN DE CUENTA';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('plan_cuentas.index',['empresa_id' => Auth::user()->empresa_id, 'status' => '[]']);
        }
        return view('plan_cuentas.indexAfter', compact('empresas'));
    }

    public function index($empresa_id,$status)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estado = $status == 1 ? ['1'] : ['1','2'];
        $empresas_info = Empresa::where('cliente_id',Auth::user()->cliente_id)->get();
        $empresa = Empresa::find($empresa_id);
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        $plan_de_cuentas = PlanCuenta::where('empresa_id',$empresa_id)->whereIn('estado',$estado)->get();
        $tree = $plan_de_cuentas != null ? $this->buildTree($plan_de_cuentas) : null;
        return view('plan_cuentas.index', compact('icono','header','estado','empresas_info','empresa','empresas','plan_de_cuentas','tree'));
    }

    protected function buildTree($nodes)
    {
        $tree = [];

        foreach ($nodes as $node) {
            if($node->estado == '2'){
                $class = 'class="font-italic text-danger"';
                $status = ' DESHABILITADO';
            }else{
                $class = '';
                $status = '';
            }
            $item = [
                'id' => $node->id,
                'parent' => $node->parent_id ? $node->parent_id : '#',
                'text' => '<span ' . $class .'>' . $node->numeracion . ' <b>(' . $node->codigo . ')</b> ' . $node->nombre . $status . '</span>',
            ];

            $tree[] = $item;

            if ($node->children) {
                $item['children'] = $this->buildTree($node->children);
            }
        }

        return $tree;
    }

    public function getDatosPlanCuenta($id){
        $plan_cuenta = PlanCuenta::find($id);
        if($plan_cuenta->count()>0){
            return $plan_cuenta;
        } else return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function create($empresa_id)
    {
        $empresa = Empresa::where('id',$empresa_id)->first();
        return view('plan_cuentas.create', compact('empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:plan_cuentas,nombre,null,id,empresa_id,' . $request->empresa_id
        ]);
        try{
            $codigo = PlanCuenta::select('id')->where('empresa_id',$request->empresa_id)->where('nivel','0')->get()->count() + 1;
            $moneda = Moneda::find($request->moneda_id);
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $request->cliente_id,
                'moneda_id' => $request->moneda_id,
                'pais_id' => $moneda->pais_id,
                'nombre' => $request->nombre,
                'codigo' => $codigo,
                'nivel' => '0',
                'parent_id' => null,
                'auxiliar' => '0',
                'cheque' => '0',
                'detalle' => '0',
                'estado' => '1'
            ];

            $plan_de_cuenta = PlanCuenta::create($datos);

            return redirect()->route('plan_cuentas.index', ['empresa_id' => $request->empresa_id,'status' => '[]','nodeId' => $plan_de_cuenta->id])->with('success_message', 'Se agregó un plan de cuenta principal en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.create', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function create_sub($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $plan_cuenta = PlanCuenta::find($id);
        $empresa = Empresa::find($plan_cuenta->empresa_id);
        return view('plan_cuentas.create-sub', compact('icono','header','plan_cuenta','empresa'));
    }

    public function store_sub(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:plan_cuentas,nombre,NULL,id,empresa_id,' . $request->empresa_id . ',nivel,' . $request->nivel,

        ]);
        try{
            $nivel = $request->nivel + 1;
            $codigo = $request->codigo . '.' . (PlanCuenta::where('estado','1')->where('parent_id',$request->parent_id)->get()->count() + 1);
            $moneda = Moneda::find($request->moneda_id);
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $request->cliente_id,
                'moneda_id' => $request->moneda_id,
                'pais_id' => $moneda->pais_id,
                'nombre' => $request->nombre,
                'codigo' => $codigo,
                'nivel' => $nivel,
                'parent_id' => $request->parent_id,
                'auxiliar' => isset($request->auxiliar) ? '1' : '0',
                'banco' => isset($request->banco) ? '1' : '0',
                'detalle' => isset($request->detalle) ? '1' : '0',
                'estado' => '1'
            ];

            $plan_de_cuenta = PlanCuenta::create($datos);

            return redirect()->route('plan_cuentas.index', ['empresa_id' => $request->empresa_id,'status' => '[]','nodeId' => $plan_de_cuenta->id])->with('success_message', 'Se agregó un plan de cuenta principal en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.create', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id)
    {
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $plan_de_cuenta = PlanCuenta::find($id);
                $planes_seleccionados = PlanCuenta::where('codigo','like',$plan_de_cuenta->codigo . '%')->get();
                foreach($planes_seleccionados as $datos){
                    $plan_cuenta = PlanCuenta::find($datos->id);
                    $parent = PlanCuenta::find($plan_cuenta->parent_id);
                    if($parent != null){
                        if($parent->estado == 2){
                            return redirect()->route('plan_cuentas.index', ['empresa_id' => $plan_de_cuenta->empresa_id,'status' => '[]','nodeId' => $parent->id])->with('error_message', 'La Accion que dese realizar no esta permitida...');
                        }else{
                            $plan_cuenta->update([
                                'estado' => '1'
                            ]);
                        }
                    }else{
                        $plan_cuenta->update([
                            'estado' => '1'
                        ]);
                    }
                }

                return redirect()->route('plan_cuentas.index', ['empresa_id' => $plan_de_cuenta->empresa_id,'status' => '[]','nodeId' => $plan_de_cuenta->id])->with('info_message', 'Plan de Cuenta Habilitado...');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function deshabilitar($id)
    {
        try {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
                $plan_de_cuenta = PlanCuenta::find($id);
                $planes_seleccionados = PlanCuenta::where('codigo','like',$plan_de_cuenta->codigo . '%')->get();
                foreach($planes_seleccionados as $datos){
                    $plan_cuenta = PlanCuenta::find($datos->id);
                    $plan_cuenta->update([
                        'estado' => '2'
                    ]);
                }

                return redirect()->route('plan_cuentas.index', ['empresa_id' => $plan_de_cuenta->empresa_id,'status' => '[]','nodeId' => $plan_de_cuenta->id])->with('info_message', 'Plan de Cuenta Deshabilitado...');
        } catch (\Throwable $th) {
            return view('errors.500');
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $plan_cuenta = PlanCuenta::find($id);
        $empresa = Empresa::find($plan_cuenta->empresa_id);
        return view('plan_cuentas.editar-sub', compact('icono','header','plan_cuenta','empresa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:plan_cuentas,nombre,' . $request->plancuenta_id . ',id,empresa_id,' . $request->empresa_id . ',nivel,' . $request->nivel,
        ]);
        try{
            $plan_de_cuenta = PlanCuenta::find($request->plancuenta_id);
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $request->cliente_id,
                'moneda_id' => $request->moneda_id,
                'nombre' => $request->nombre,
                'auxiliar' => isset($request->auxiliar) ? '1' : '0',
                'banco' => isset($request->banco) ? '1' : '0',
                'detalle' => isset($request->detalle) ? '1' : '0'
            ];

            $plan_de_cuenta->update($datos);

            return redirect()->route('plan_cuentas.index', ['empresa_id' => $request->empresa_id,'status' => '[]','nodeId' => $plan_de_cuenta->id])->with('success_message', 'Se Modifico el Plan de Cuenta Seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.editar', $request->plancuenta_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

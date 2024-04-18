<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\PlanCuentaAuxiliar;
use App\Models\User;
use Auth;
use PDF;

class PlanCuentaAuxiliarController extends Controller
{
    const ICONO = 'fas fa-user-friends fa-fw';
    const INDEX = 'PLAN CUENTAS AUXILIARES';
    const CREATE = 'REGISTRAR AUXILIAR';
    const EDITAR = 'MODIFICAR AUXILIAR';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('plan_cuentas.auxiliar.index',Auth::user()->empresa_id);
        }
        return view('plan_cuentas_auxiliares.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $tipos = PlanCuentaAuxiliar::TIPOS;
        $estados = PlanCuentaAuxiliar::ESTADOS;
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::query()
                                        ->byEmpresa($empresa_id)
                                        ->orderBy('id','desc')
                                        ->paginate(10);
        return view('plan_cuentas_auxiliares.index', compact('icono','header','empresa','tipos','estados','plan_cuentas_auxiliares'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $tipos = PlanCuentaAuxiliar::TIPOS;
        $estados = PlanCuentaAuxiliar::ESTADOS;
        $plan_cuentas_auxiliares = PlanCuentaAuxiliar::query()
                                        ->byEmpresa($request->empresa_id)
                                        ->byNombre($request->nombre)
                                        ->byTipo($request->tipo)
                                        ->byEstado($request->estado)
                                        ->orderBy('id','desc')
                                        ->paginate(10);
        return view('plan_cuentas_auxiliares.index', compact('icono','header','empresa','tipos','estados','plan_cuentas_auxiliares'));
    }

    public function create($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($id);
        return view('plan_cuentas_auxiliares.create', compact('icono','header','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:plan_cuentas_auxiliares,nombre,null,id,empresa_id,' . $request->empresa_id,
        ]);
        try{
            $empresa = Empresa::find($request->empresa_id);
            $user = User::where('id',Auth::user()->id)->first();
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'user_id' => $user->id,
                'nombre' => $request->nombre,
                'tipo' => '2',
                'estado' => '1'
            ];
            $plan_cuentas_auxiliar = PlanCuentaAuxiliar::create($datos);

            return redirect()->route('plan_cuentas.auxiliar.index',['empresa_id' => $request->empresa_id])->with('success_message', 'CUENTA AUXILIAR CREADA CORRECTAMENTE.');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.auxiliar.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $plan_cuenta_auxiliar = PlanCuentaAuxiliar::find($id);
        $empresa = Empresa::find($plan_cuenta_auxiliar->empresa_id);
        return view('plan_cuentas_auxiliares.editar', compact('icono','header','plan_cuenta_auxiliar','empresa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:plan_cuentas_auxiliares,nombre,' . $request->plan_cuenta_auxiliar_id . ',id,empresa_id,' . $request->empresa_id,
        ]);
        try{
            $plan_cuenta_auxiliar = PlanCuentaAuxiliar::find($request->plan_cuenta_auxiliar_id);
            $empresa = Empresa::find($plan_cuenta_auxiliar->empresa_id);
            $user = User::where('id',Auth::user()->id)->first();
            $datos = [
                'user_id' => $user->id,
                'nombre' => $request->nombre
            ];
            $plan_cuenta_auxiliar->update($datos);

            return redirect()->route('plan_cuentas.auxiliar.index',['empresa_id' => $plan_cuenta_auxiliar->empresa_id])->with('success_message', 'CUENTA AUXILIAR MODIFICADA CORRECTAMENTE.');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.auxiliar.editar',$plan_cuenta_auxiliar->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id){
        try{
            $plan_cuentas_auxiliar = PlanCuentaAuxiliar::find($id);
            $plan_cuentas_auxiliar->update([
                'estado' => '1'
            ]);
            return redirect()->route('plan_cuentas.auxiliar.index',$plan_cuentas_auxiliar->empresa_id)->with('info_message', '[CUENTA HABILITADA]');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.auxiliar.index',$plan_cuentas_auxiliar->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id){
        try{
            $plan_cuentas_auxiliar = PlanCuentaAuxiliar::find($id);
            $plan_cuentas_auxiliar->update([
                'estado' => '2'
            ]);
            return redirect()->route('plan_cuentas.auxiliar.index',$plan_cuentas_auxiliar->empresa_id)->with('info_message', '[CUENTA DESHABILITADA]');
        } catch (ValidationException $e) {
            return redirect()->route('plan_cuentas.auxiliar.index',$plan_cuentas_auxiliar->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

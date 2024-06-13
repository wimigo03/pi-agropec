<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\PlanCuenta;
use App\Models\User;
use Auth;
use DB;

class CargoController extends Controller
{
    const ICONO = 'fa-solid fa-diagram-project fa-fw';
    const INDEX = 'CARGOS';
    const CREATE = 'REGISTRAR CARGO';
    const EDITAR = 'MODIFICAR CARGO';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('cargos.index',['empresa_id' => Auth::user()->empresa_id]);
        }
        return view('cargos.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        $cargos = Cargo::where('empresa_id',$empresa_id)->get();
        if(count($cargos) > 0){
            $tree = $this->buildTree($cargos);
        }else{
            return redirect()->route('cargos.index',['empresa_id' => $empresa_id])->with('info_message', 'La empresa seleccionada no tiene cargos agregados por favor comunicarse con la unidad de sistemas.');
        }
        return view('cargos.index', compact('icono','header','empresa','empresas','cargos','tree'));
    }

    protected function buildTree($nodes)
    {
        $tree = [];

        foreach ($nodes as $node) {
            if($node->estado == '2'){
                $class = 'class="font-italic text-danger"';
            }else{
                $class = '';
            }
            $item = [
                'id' => $node->id,
                'parent' => $node->parent_id ? $node->parent_id : '#',
                'text' => '<span ' . $class .'>' . $node->codigo . ' ' . $node->nombre . '</span>',
            ];

            $tree[] = $item;

            if ($node->children) {
                $item['children'] = $this->buildTree($node->children);
            }
        }

        return $tree;
    }

    public function getDatosCargo($id){
        $cargo = Cargo::find($id);
        if($cargo->count()>0){
            return $cargo;
        } else return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function getDatosCargoByEmpresa($id){
        try{
            $cargos = Cargo::where('empresa_id', $id)->where('estado','1')->orderBy('id','asc')->get()->toJson();
            if($cargos){
                return response()->json([
                    'cargos' => $cargos
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $cargo = Cargo::find($id);
        $empresa = Empresa::find($cargo->empresa_id);
        $tipos = Cargo::TIPOS;
        $cuentas_contables = PlanCuenta::where('empresa_id',$cargo->empresa_id)->where('detalle','1')->pluck('nombre','id');
        return view('cargos.create', compact('icono','header','cargo','empresa','tipos','cuentas_contables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cargo' => 'required|unique:cargos,nombre,null,id,empresa_id,' . $request->empresa_id,
            'email' => 'nullable|email|unique:cargos,email,null,id,empresa_id,' . $request->empresa_id,
            'tipo' => 'required'
        ]);
        try{
            $parent = Cargo::select('codigo','nivel')->find($request->parent_id);
            $nivel = $parent->nivel + 1;
            $codigo = $parent->codigo . '.' . (Cargo::where('estado','1')->where('parent_id',$request->parent_id)->get()->count() + 1);

            $cargo = Cargo::create([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => $request->pi_cliente_id,
                'nombre' => $request->cargo,
                'codigo' => $codigo,
                'nivel' => $nivel,
                'parent_id' => $request->parent_id,
                'email' => $request->email,
                'descripcion' => $request->descripcion,
                'alias' => $request->alias,
                'tipo' => $request->tipo,
                'estado' => '1'
                ]);
            return redirect()->route('cargos.index', ['empresa_id' => $request->empresa_id,'nodeId' => $cargo->id])->with('success_message', 'Se agregó un cargo en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('cargos.create',$cargo->id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $cargo = Cargo::find($id);
        $tipos = Cargo::TIPOS;
        return view('cargos.editar', compact('cargo','tipos'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cargo' => 'required|unique:cargos,nombre,' . $request->cargo_id . ',id,empresa_id,' . $request->empresa_id,
            'email' => 'nullable|email|unique:cargos,email,' . $request->cargo_id . ',id,empresa_id,' . $request->empresa_id,
            'tipo' => 'required'
        ]);
        try{
            $cargo = Cargo::find($request->cargo_id);
            $cargo->update([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => $request->pi_cliente_id,
                'nombre' => $request->cargo,
                'parent_id' => $request->parent_id,
                'email' => $request->email,
                'descripcion' => $request->descripcion,
                'alias' => $request->alias,
                'tipo' => $request->tipo
                ]);
            return redirect()->route('cargos.index', ['empresa_id' => $request->empresa_id,'nodeId' => $cargo->id])->with('success_message', 'Se modifico el cargo seleccionado.');
        } catch (ValidationException $e) {
            return redirect()->route('cargos.editar',$cargo->id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($cargo_id)
    {
        $cargo = Cargo::find($cargo_id);
        $cargo->update([
            'estado' => '1'
            ]);
        return redirect()->route('cargos.index', ['empresa_id' => $cargo->empresa_id,'nodeId' => $cargo->id])->with('success_message', 'Cargo Habilitado...');
    }

    public function deshabilitar($cargo_id)
    {
        $cargo = Cargo::find($cargo_id);
        $user = User::select('id')->where('empresa_id',$cargo->empresa_id)->where('cargo_id',$cargo->id)->where('estado','1')->first();
        if($user != null){
            return redirect()->route('cargos.index', ['empresa_id' => $cargo->empresa_id,'nodeId' => $cargo->id])->with('error_message', 'Imposible realizar la accion. Existe un usuario con el cargo seleccionado...');
        }
        $cargo->update([
            'estado' => '2'
            ]);
        return redirect()->route('cargos.index', ['empresa_id' => $cargo->empresa_id,'nodeId' => $cargo->id])->with('success_message', 'Cargo Deshabilitado...');
    }
}

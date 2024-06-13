<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\PlanCuenta;
use Auth;

class CategoriaController extends Controller
{
    const ICONO = 'fas fa-poll-h fa-fw';
    const INDEX = 'CATEGORIAS';
    const CREATE_MASTER = 'REGISTRAR CATEGORIA MASTER';
    const CREATE_SUBMASTER = 'REGISTRAR SUBCATEGORIA MASTER';
    const CREATE_INSUMOS = 'REGISTRAR CATEGORIA INSUMOS';
    const CREATE_SUBINSUMOS = 'REGISTRAR SUBCATEGORIA INSUMOS';
    const EDITAR = 'MODIFICAR CATEGORIA';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('categorias.index',['empresa_id' => Auth::user()->empresa_id, 'status_platos' => '[]', 'status_insumos' => '[]']);
        }
        return view('categorias.indexAfter', compact('empresas'));
    }

    public function index($empresa_id, $status_platos, $status_insumos)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estado_platos = $status_platos == 1 ? ['1'] : ['1','2'];
        $estado_insumos = $status_insumos == 1 ? ['1'] : ['1','2'];
        $empresa = Empresa::find($empresa_id);
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        $categorias_platos = Categoria::where('empresa_id',$empresa_id)->whereIn('estado',$estado_platos)->where('tipo','1')->get();
        $categorias_insumos = Categoria::where('empresa_id',$empresa_id)->whereIn('estado',$estado_insumos)->where('tipo','2')->get();
        $tree = $categorias_platos != null ? $this->buildTree($categorias_platos) : null;
        $tree_insumos = $categorias_insumos != null ? $this->buildTree($categorias_insumos) : null;
        return view('categorias.index', compact('icono','header','estado_platos','estado_insumos','empresa','empresas','categorias_platos','categorias_insumos','tree','tree_insumos'));
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

    public function getDatosCategoria($id){
        $categoria = Categoria::find($id);
        if($categoria->count()>0){
            return $categoria;
        } else return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function getDatosCategoriaInsumos($id){
        $categoria = Categoria::find($id);
        if($categoria->count()>0){
            return $categoria;
        } else return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function getDatosCargoByEmpresa(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $cargos = Cargo::where('empresa_id', $id)->orderBy('id','asc')->get()->toJson();
            if($cargos){
                return response()->json([
                    'cargos' => $cargos
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create_platos($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE_SUBMASTER;
        $categoria = Categoria::find($id);
        $empresa = Empresa::find($categoria->empresa_id);
        $tipo = '1';
        $plan_cuentas = PlanCuenta::where('empresa_id',$categoria->empresa_id)->where('detalle','1')->where('estado','1')->pluck('nombre','id');
        return view('categorias.create', compact('icono','header','categoria','empresa','tipo','plan_cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria' => 'required|unique:categorias,nombre,null,id,empresa_id,' . $request->empresa_id . ',parent_id,' . $request->parent_id,
            'codigo' => 'required|max:4',
            'tipo' => 'required'
        ]);
        try{
            $parent = Categoria::select('numeracion','nivel')->find($request->parent_id);
            $nivel = $parent->nivel + 1;
            $numeracion = $parent->numeracion . '.' . (Categoria::where('estado','1')->where('parent_id',$request->parent_id)->get()->count() + 1);
            $plan_cuenta = PlanCuenta::find($request->plan_cuenta_id);
            $categoria = Categoria::create([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => $request->pi_cliente_id,
                'plan_cuenta_id' => $request->plan_cuenta_id,
                'moneda_id' => $plan_cuenta != null ? $plan_cuenta->moneda_id : null,
                'pais_id' => $plan_cuenta != null ? $plan_cuenta->pais_id : null,
                'nombre' => $request->categoria,
                'codigo' => $request->codigo,
                'numeracion' => $numeracion,
                'nivel' => $nivel,
                'parent_id' => $request->parent_id,
                'detalle' => $request->detalle,
                'tipo' => $request->tipo,
                'estado' => '1'
                ]);
            if($request->tipo == '1'){
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('success_message', 'Se agregó un categoria en la empresa seleccionada.');
            }else{
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('success_message', 'Se agregó un categoria en la empresa seleccionada.');
            }
        } catch (ValidationException $e) {
            return redirect()->route('categoria.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $categoria = Categoria::find($id);
        $empresa = Empresa::find($categoria->empresa_id);
        $tipo = $categoria->tipo == '1' ? 'PLATOS FINAL' : 'INSUMOS';
        $plan_cuentas = PlanCuenta::where('empresa_id',$categoria->empresa_id)->where('detalle','1')->where('estado','1')->get();
        return view('categorias.editar', compact('icono','header','empresa','categoria','tipo','plan_cuentas'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'categoria' => 'required|unique:categorias,nombre,' . $request->categoria_id . ',id,empresa_id,' . $request->empresa_id,
            'codigo' => 'required|max:4',
        ]);
        try{
            $categoria = Categoria::find($request->categoria_id);
            $categoria->update([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => $request->pi_cliente_id,
                'nombre' => $request->categoria,
                'codigo' => $request->codigo,
                'parent_id' => $request->parent_id,
                'detalle' => $request->detalle
                ]);
            if($categoria->tipo == '1'){
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('success_message', 'Se modifico la categoria seleccionado.');
            }else{
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('success_message', 'Se modifico la categoria seleccionado.');
            }
        } catch (ValidationException $e) {
            return redirect()->route('categorias.editar')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function create_platos_master($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE_MASTER;
        $empresa = Empresa::where('id',$empresa_id)->first();
        $tipo = '1';
        $plan_cuentas = PlanCuenta::where('empresa_id',$empresa_id)->where('detalle','1')->where('estado','1')->pluck('nombre','id');
        return view('categorias.create-master', compact('icono','header','empresa','tipo','plan_cuentas'));
    }

    public function store_master(Request $request)
    {
        $request->validate([
            'categoria' => 'required|unique:categorias,nombre,null,id,empresa_id,' . $request->empresa_id,
            'codigo' => 'required|max:4',
            'tipo' => 'required'
        ]);
        try{
            $numeracion = Categoria::where('estado','1')->where('empresa_id',$request->empresa_id)->where('tipo',$request->tipo)->where('nivel',0)->get()->count() + 1;
            $plan_cuenta = PlanCuenta::find($request->plan_cuenta_id);
            $categoria = Categoria::create([
                'empresa_id' => $request->empresa_id,
                'pi_cliente_id' => $request->pi_cliente_id,
                'plan_cuenta_id' => $request->plan_cuenta_id,
                'moneda_id' => $plan_cuenta != null ? $plan_cuenta->moneda_id : null,
                'pais_id' => $plan_cuenta != null ? $plan_cuenta->pais_id : null,
                'nombre' => $request->categoria,
                'codigo' => $request->codigo,
                'numeracion' => $numeracion,
                'nivel' => 0,
                'parent_id' => null,
                'detalle' => $request->detalle,
                'tipo' => $request->tipo,
                'estado' => '1'
                ]);
            if($request->tipo == '1'){
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('success_message', 'Se agregó un Categoria Master en la empresa seleccionada.');
            }else{
                return redirect()->route('categorias.index', ['empresa_id' => $request->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('success_message', 'Se agregó un Categoria Master en la empresa seleccionada.');
            }
        } catch (ValidationException $e) {
            return redirect()->route('categoria.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id)
    {
        $categoria = Categoria::find($id);
        if($categoria->tipo == '1'){
            $categoria->update([
                'estado' => '1'
            ]);
            return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('success_message', 'Categoria Habilitada...');
        }else{
            $categoria->update([
                'estado' => '1'
            ]);
            return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('success_message', 'Categoria Habilitada...');
        }
    }

    public function deshabilitar($id)
    {
        $categoria = Categoria::find($id);
        $producto = Producto::where('categoria_id',$id)->where('estado','1')->first();
        if($categoria->tipo == '1'){
            if($producto == null){
                $categoria->update([
                    'estado' => '2'
                ]);
                return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('success_message', 'Categoria Deshabilitada...');
            }else{
                return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeId' => $categoria->id])->with('error_message', 'Imposible deshabilitar porque Existen Productos en esta Categoria...');
            }
        }else{
            if($producto == null){
                $categoria->update([
                    'estado' => '2'
                ]);
                return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('success_message', 'Categoria Deshabilitada...');
            }else{
                return redirect()->route('categorias.index', ['empresa_id' => $categoria->empresa_id,'status_platos' => '[]','status_insumos' => '[]','nodeIdInsumo' => $categoria->id])->with('error_message', 'Imposible deshabilitar porque Existen Productos en esta Categoria...');
            }
        }
    }

    public function create_insumos_master($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE_INSUMOS;
        $empresa = Empresa::where('id',$empresa_id)->first();
        $tipo = '2';
        $plan_cuentas = PlanCuenta::where('empresa_id',$empresa_id)->where('detalle','1')->where('estado','1')->pluck('nombre','id');
        return view('categorias.create-master-insumos', compact('icono','header','empresa','tipo','plan_cuentas'));
    }

    public function create_insumos($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE_SUBINSUMOS;
        $categoria = Categoria::find($id);
        $empresa = Empresa::find($categoria->empresa_id);
        $tipo = '2';
        $plan_cuentas = PlanCuenta::where('empresa_id',$categoria->empresa_id)->where('detalle','1')->where('estado','1')->pluck('nombre','id');
        return view('categorias.create-insumos', compact('icono','header','empresa','categoria','tipo','plan_cuentas'));
    }

    public function getDatosSubCategorias(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $subcategorias = Categoria::where('parent_id', $id)->where('estado','1')->orderBy('id','asc')->get()->toJson();
            if($subcategorias){
                return response()->json([
                    'subcategorias' => $subcategorias
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

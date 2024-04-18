<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Modulo;
use Auth;
use DB;

class PermissionController extends Controller
{
    const ICONO = 'fas fa-user-cog fa-fw';
    const INDEX = 'PERMISOS';
    const CREATE = 'CREAR PERMISO';
    const EDITAR = 'MODIFICAR PERMISO';

    private function completar_datos_permissions()
    {
        $permissions = DB::table('permissions')->get();
        foreach ($permissions as $permission) {
            $permission = Permission::find($permission->id);
            $permission->update([
                'empresa_id' => '1',
                'cliente_id' => '1',
                'modulo_id' => '2',
            ]);
        }
        dd("completar_datos_permissions finalizado...");
    }

    public function indexAfter()
    {
        //***$this->completar_datos_permissions();
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('permissions.index',Auth::user()->empresa_id);
        }
        return view('permissions.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $modulos = Modulo::pluck('nombre','id');
        $titulos = Permission::where('empresa_id',$empresa_id)->select('title')->groupBy('title')->get();
        $permissions = Permission::query()
                                    ->byEmpresa($empresa_id)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('permissions.index', compact('icono','header','empresa','modulos','titulos','permissions'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $modulos = Modulo::pluck('nombre','id');
        $titulos = Permission::where('empresa_id',$request->empresa_id)->select('title')->groupBy('title')->get();
        $permissions = Permission::query()
                                    ->byEmpresa($request->empresa_id)
                                    ->byModulo($request->modulo_id)
                                    ->byTitulo($request->titulo)
                                    ->byNombre($request->nombre)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('permissions.index', compact('icono','header','empresa','modulos','titulos','permissions'));
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $modulos = Modulo::pluck('nombre','id');
        $titulos = Permission::select('title')->groupBy('title')->orderBy('title','desc')->get();
        return view('permissions.create',compact('icono','header','empresa','modulos','titulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required',
            'titulo' => 'required',
            'nombre' => 'required|unique:permissions,name',
            'descripcion' => 'required',
        ]);
        try{
            $titulo = $request->titulo == '_NUEVO_' ? $request->nuevo_titulo : $request->titulo;
            $empresa = Empresa::find($request->empresa_id);
            $permission = Permission::create([
                'empresa_id' => $empresa->id,
                'cliente_id' => $empresa->cliente_id,
                'modulo_id' => $request->modulo_id,
                'title' => $titulo,
                'name' => $request->nombre,
                'description' => $request->descripcion
            ]);
            return redirect()->route('permissions.index',$request->empresa_id)->with('success_message', 'Se agregó un nuevo permiso.');
        } catch (ValidationException $e) {
            return redirect()->route('permissions.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($permission_id)
    {
        $permission = Permission::find($permission_id);
        $icono = self::ICONO;
        $header = self::EDITAR;
        $empresa = Empresa::find($permission->empresa_id);
        $modulos = Modulo::get();
        $titulos = Permission::select('title')->groupBy('title')->orderBy('title','desc')->get();
        return view('permissions.editar',compact('permission','icono','header','empresa','modulos','titulos'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'modulo_id' => 'required',
            'titulo' => 'required',
            'nombre' => 'required|unique:permissions,name,' . $request->permission_id . ',id,empresa_id,' . $request->empresa_id,
            'descripcion' => 'required',
        ]);
        try{
            $permission = Permission::find($request->permission_id);
            $permission->update([
                'modulo_id' => $request->modulo_id,
                'title' => $request->titulo,
                'name' => $request->nombre,
                'description' => $request->descripcion
            ]);
            return redirect()->route('permissions.index',$request->empresa_id)->with('success_message', 'Se modifico un permiso.');
        } catch (ValidationException $e) {
            return redirect()->route('permissions.editar',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

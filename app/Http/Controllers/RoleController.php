<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\EmpresaModulo;
use Auth;
use DB;

class RoleController extends Controller
{
    const ICONO = 'fas fa-user-shield fa-fw';
    const INDEX = 'ROLES';
    const CREATE = 'REGISTRAR ROL';

    public function completar_datos_roles()
    {
        $roles_permissions = DB::table('role_has_permissions')->select('role_id')->groupBy('role_id')->get();
        foreach ($roles_permissions as $role_permission) {
            $role = Role::find($role_permission->role_id);
            $permissions = DB::table('role_has_permissions')->where('role_id',$role_permission->role_id)->get();
            foreach($permissions as $datos){
                $permission[] = $datos->permission_id;
            }
            foreach ($permission as $permissionId) {
                $permiso = Permission::find($permissionId);
                $role->permissions()->updateExistingPivot($permissionId, [
                    'empresa_id' => $permiso->empresa_id,
                    'cliente_id' => $permiso->cliente_id,
                    'modulo_id' => $permiso->modulo_id
                ]);
            }
        }
        dd("completar_datos_roles finalizado...");
    }

    public function indexAfter()
    {
        /////***/$this->completar_datos_roles();
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('role.index.index',Auth::user()->empresa_id);
        }
        return view('roles.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $roles = Role::where('empresa_id',$empresa_id)->where('id','!=',1)->paginate(10);
        return view('roles.index', compact('icono','header','empresa','roles'));
    }

    public function search(Request $request)
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $roles = Role::query()
                        ->byEmpresa($request->empresa_id)
                        ->byNombre($request->nombre)
                        ->where('id','!=',1)
                        ->paginate(10);
        return view('roles.index', compact('icono','header','empresa','roles'));
    }

    public function getDatosByEmpresa(Request $request){
        try{
            $input = $request->all();
            $id = $input['id'];
            $roles = Role::where('empresa_id', $id)->orderBy('id','asc')->get()->toJson();
            if($roles){
                return response()->json([
                    'roles' => $roles
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        return view('roles.create',compact('icono','header','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required',
            'nombre' => 'required|unique:roles,name',
        ]);
        try{
            $empresa = Empresa::find($request->empresa_id);
            $role = Role::create([
                'cliente_id' => $empresa->cliente_id,
                'empresa_id' => $request->empresa_id,
                'name' => $request->nombre
            ]);
            return redirect()->route('roles.index',$request->empresa_id)->with('success_message', 'Se agregó un nuevo rol.');
        } catch (ValidationException $e) {
            return redirect()->route('roles.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar_index($role_id){
        $icono = self::ICONO;
        $header = self::INDEX;
        $role = Role::find($role_id);
        $empresa = Empresa::find($role->empresa_id);
        $modulos = EmpresaModulo::where('empresa_id',$role->empresa_id)->where('estado','1')->get();
        if(count($modulos) > 0){
            foreach($modulos as $datos){
                $modulos_id[] = $datos->modulo_id;
            }
        }else{
            $modulos_id = null;
        }
        $permisosOrdenados = $this->permisos_ordenados($role->empresa_id, $modulos_id);
        $modulos = DB::table('empresa_modulos as a')
                            ->join('modulos as b','b.id','a.modulo_id')
                            ->where('a.empresa_id', $role->empresa_id)
                            ->where('a.estado','1')
                            ->select('b.nombre','b.id')
                            ->pluck('nombre','id');
        return view('roles.editar',compact('icono','header','empresa','role','permisosOrdenados','modulos'));
    }

    public function editar(Request $request){
        $icono = self::ICONO;
        $header = self::INDEX;
        $role = Role::find($request->role_id);
        $empresa = Empresa::find($role->empresa_id);
        if($request->modulo_id == '_todos_'){
            $modulos = EmpresaModulo::where('empresa_id',$role->empresa_id)->where('estado','1')->get();
            foreach($modulos as $datos){
                $modulos_id[] = $datos->modulo_id;
            }
        }else{
            $modulos_id[] = $request->modulo_id;
        }
        $permisosOrdenados = $this->permisos_ordenados($role->empresa_id, $modulos_id);
        /*$permissions = Permission::query()
                                    ->byEmpresa($role->empresa_id)
                                    ->byModulo($modulos_id)
                                    ->get();*/
        $modulos = DB::table('empresa_modulos as a')
                            ->join('modulos as b','b.id','a.modulo_id')
                            ->where('a.empresa_id', $role->empresa_id)
                            ->select('b.nombre','b.id')
                            ->pluck('nombre','id');
        return view('roles.editar',compact('icono','header','empresa','role','permisosOrdenados','modulos'));
    }

    public function update(Request $request){
        $role = Role::find($request->role_id);
        if(isset($request->permissions)){
            $role->permissions()->sync($request->permissions);
            $permissionsToSync = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();
            //$role->syncPermissions($permissionsToSync);
            foreach ($permissionsToSync as $permissionId) {
                $permiso = Permission::find($permissionId);
                $role->permissions()->updateExistingPivot($permissionId, [
                    'empresa_id' => $permiso->empresa_id,
                    'cliente_id' => $permiso->cliente_id,
                    'modulo_id' => $permiso->modulo_id,
                ]);
            }
        }else{
            $role->permissions()->sync($request->permissions);
        }
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('roles.index',$role->empresa_id)->with('success_message', 'Se agregó permisos.');
    }

    private function permisos_ordenados($empresa_id, $modulos_id){
        if($modulos_id != null){
            $permisos = Permission::query()
                                    ->byEmpresa($empresa_id)
                                    ->byModulo($modulos_id)
                                    ->orderBy('title')->get();
            $permisosOrdenados = array();
            $grupo = array();
            $title = $permisos[0]->title;
            foreach ($permisos as $permiso) {
                if($permiso->title == $title){
                    if(count($grupo) == 0)
                        array_push($grupo,$permiso->title);
                        array_push($grupo,$permiso->id);
                        array_push($grupo,$permiso->description);
                }else{
                    $title = $permiso->title;
                    array_push($permisosOrdenados,$grupo);
                    $grupo = array();
                    array_push($grupo,$permiso->title);
                    array_push($grupo,$permiso->id);
                    array_push($grupo,$permiso->description);
                }
            }
            array_push($permisosOrdenados,$grupo);
            return $permisosOrdenados;
        }
    }
}

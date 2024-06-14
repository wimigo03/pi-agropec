<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Empresa;
use App\Models\Cargo;
use Auth;

class UserController extends Controller
{
    const ICONO = 'fas fa-users fa-fw';
    const INDEX = 'USUARIOS';
    const EDITAR = 'MODIFICAR USUARIO';
    const ASIGNAR = 'ASIGNAR ROLE';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('user.index',Auth::user()->empresa_id);
        }
        return view('users.indexAfter', compact('empresas'));
    }

    public function index()
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $cargos = Cargo::select('nombre')->groupBy('nombre')->pluck('nombre','nombre');
        $roles = Role::select('name')->where('id','!=','1')->groupBy('name')->pluck('name','name');
        $estados = User::ESTADOS;
        $users = User::query()
                        ->where('id','!=',1)
                        ->byPiCliente(Auth::user()->pi_cliente_id)
                        ->orderBy('id','desc')
                        ->paginate(10);
        return view('users.index', compact('icono','header','empresas','cargos','roles','users','estados'));
    }

    public function search(Request $request)
    {//dd($request->all());
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->pluck('nombre_comercial','id');
        $cargos = Cargo::select('nombre')->groupBy('nombre')->pluck('nombre','nombre');
        $roles = Role::select('name')->where('id','!=','1')->groupBy('name')->pluck('name','name');
        $estados = User::ESTADOS;
        $users = User::query()
                        ->where('id','!=',1)
                        ->byPiCliente(Auth::user()->pi_cliente_id)
                        ->byEmpresa($request->empresa_id)
                        ->byCargo($request->cargo_id)
                        ->byRole($request->role_id)
                        ->byNombre($request->nombre)
                        ->byUsername($request->username)
                        ->byEmail($request->email)
                        ->byEstado($request->estado)
                        ->paginate(10);
        return view('users.index', compact('icono','header','empresas','cargos','roles','users','estados'));
    }

    public function editar($user_id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $user = User::find($user_id);
        $empresa = Empresa::find($user->empresa_id);
        return view('users.editar', compact('icono','header','user','empresa'));
    }

    public function update(Request $request){
        try{
            $user = User::find($request->user_id);
            if($request->password != null){
                $user->update([
                    'cargo_id' => $request->cargo_id,
                    'empresa_id' => $request->empresa_id,
                    'pi_cliente_id' => $request->pi_cliente_id,
                    'name' => $request->nombre,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
            }else{
                $user->update([
                    'cargo_id' => $request->cargo_id,
                    'empresa_id' => $request->empresa_id,
                    'pi_cliente_id' => $request->pi_cliente_id,
                    'name' => $request->nombre,
                    'email' => $request->email
                ]);
            }
            return redirect()->route('users.index',$request->empresa_id)->with('success_message', 'Se modificaron los datos del usuario seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('users.editar',$request->user_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function habilitar($id){
        try{
            $user = User::find($id);
            $user->update([
                'estado' => '1'
            ]);
            return redirect()->route('users.index',$user->empresa_id)->with('success_message', 'Se Habilito usuario seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('users.index',$user->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id){
        try{
            $user = User::find($id);
            $user->update([
                'estado' => '2'
            ]);
            return redirect()->route('users.index',$user->empresa_id)->with('success_message', 'Se Habilito usuario seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('users.index',$user->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function asignar($user_id)
    {
        $icono = self::ICONO;
        $header = self::ASIGNAR;
        $user = User::find($user_id);
        $empresa = Empresa::find($user->empresa_id);
        $roles = Role::where('id','!=',1)->get();
        return view('users.asignar', compact('icono','header','user','empresa','roles'));
    }

    public function asignacion(Request $request)
    {
        $user = User::find($request->user_id);
        $user->roles()->sync($request->roles);
        if(isset($request->roles))
        {
            foreach ($request->roles as $roleId) {
                $user->roles()->updateExistingPivot($roleId, [
                    'empresa_id' => $user->empresa_id,
                    'pi_cliente_id' => $user->pi_cliente_id,
                    'cargo_id' => $user->cargo_id
                ]);
            }
        }
        $role = app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        return redirect()->route('users.index',$user->empresa_id)->with('success_message', 'Se Modificaron los roles.');
    }
}

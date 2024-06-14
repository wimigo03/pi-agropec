<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Cargo;
use App\Models\User;
use App\Models\Personal;
use App\Models\PersonalLaboral;
use App\Models\Categoria;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use App\Models\PlanCuenta;
use App\Models\Modulo;
use App\Models\EmpresaModulo;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Auth;

class EmpresaController extends Controller
{
    const ICONO = 'fas fa-user-friends fa-fw';
    const INDEX = 'EMPRESAS';
    const CREATE = 'REGISTRAR EMPRESA';
    const EDITAR = 'MODIFICAR EMPRESA';

    public function index($pi_cliente_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::where('pi_cliente_id',$pi_cliente_id)->paginate(10);
        $cliente = PiCliente::find($pi_cliente_id);
        $estados = Empresa::ESTADOS;
        return view('empresas.index', compact('icono','header','empresas','cliente','estados'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresas = Empresa::query()
                            ->byPiCliente($request->pi_cliente_id)
                            ->byCodigo($request->codigo)
                            ->byNombreComercial($request->nombre_comercial)
                            ->byTelefono($request->telefono)
                            ->paginate(10);
        $cliente = PiCliente::find($request->pi_cliente_id);
        $estados = Empresa::ESTADOS;
        return view('empresas.index', compact('icono','header','empresas','cliente','estados'));
    }

    public function create($pi_cliente_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $cliente = PiCliente::find($pi_cliente_id);
        $modulos = Modulo::where('estado','1')->pluck('nombre','id');
        return view('empresas.create', compact('icono','header','cliente','modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|unique:empresas,nombre_comercial,null,id,pi_cliente_id,' . $request->pi_cliente_id,
            'direccion' => 'required',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'cover' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'alias' => 'required|size:3'
        ]);
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

            $empresa = $this->crear_empresa(
                $request->logo,
                $request->cover,
                $request->pi_cliente_id,
                $request->nombre_comercial,
                $request->alias,
                $request->direccion,
                $request->telefono
            );

            $empresas_modulos = $this->crear_modulos(
                $request->modulo_id,
                $empresa,
                $request->pi_cliente_id
            );

            $cargo = $this->crear_cargo_gerente(
                $empresa,
                $request->pi_cliente_id,
                $request->nombre_comercial
            );

            $user = $this->crear_usuario_gerente(
                $request->nombre_comercial,
                $cargo,
                $empresa,
                $request->pi_cliente_id
            );

            $personal = $this->crear_registro_gerente(
                $user,
                $cargo,
                $empresa,
                $request->pi_cliente_id
            );

            $plan_cuenta = $this->crear_plan_cuentas(
                $empresa,
                $request->pi_cliente_id
            );

            return redirect()->route('empresas.index',$request->pi_cliente_id)->with('success_message', 'Se agregó una empresa correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('empresas.create',$request->pi_cliente_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function crear_empresa($logo,$cover,$pi_cliente_id,$nombre_comercial,$alias,$direccion,$telefono)
    {
        $logo = isset($logo) ? 'logo.'.pathinfo($logo->getClientOriginalName(), PATHINFO_EXTENSION) : null;
        $cover = isset($cover) ? 'cover.'.pathinfo($cover->getClientOriginalName(), PATHINFO_EXTENSION) : null;
        $empresa = Empresa::create([
            'pi_cliente_id' => $pi_cliente_id,
            'nombre_comercial' => $nombre_comercial,
            'alias' => $alias,
            'url_logo' => $logo,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'url_cover' => $cover,
            'estado' => '1'
            ]);

        $empresa_logo = Empresa::find($empresa->id);
        $empresa_logo->update([
            'url_logo' => 'uploads/empresas/' . $empresa->id . '/logos/' . $logo,
            'url_cover' => 'uploads/empresas/' . $empresa->id . '/logos/' . $cover
        ]);

        $logo = isset($request->logo) ? $request->logo->move(public_path('uploads/empresas/' . $empresa->id . '/logos/'), $logo) : null;
        $cover = isset($request->cover) ? $request->cover->move(public_path('uploads/empresas/' . $empresa->id . '/logos/'), $cover) : null;

        return $empresa;
    }

    public function crear_modulos($modulo_id,$empresa,$pi_cliente_id)
    {
        $cont = 0;
        while($cont < count($modulo_id)){
            $empresa_modulo = EmpresaModulo::create([
                'empresa_id' => $empresa->id,
                'pi_cliente_id' => $pi_cliente_id,
                'modulo_id' => $modulo_id[$cont],
                'fecha_registro' => date('Y-m-d'),
                'estado' => '1'
            ]);

            $cont++;
        }

        return $empresa_modulo;
    }

    public function crear_cargo_gerente($empresa,$pi_cliente_id,$nombre_comercial)
    {
        $cargo = Cargo::create([
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $pi_cliente_id,
            'plan_cuenta_id'=> null,
            'nombre' => 'GERENTE GENERAL',
            'codigo' => '1',
            'nivel' => '0',
            'parent_id' => null,
            'email' => null,
            'descripcion' => $nombre_comercial,
            'alias' => 'GRAL',
            'tipo' => '2',
            'estado' => '1'
        ]);

        return $cargo;
    }

    public function crear_usuario_gerente($nombre_comercial,$cargo,$empresa,$pi_cliente_id)
    {
        $username = substr($nombre_comercial, 0, 5);
        $username_minus = strtolower($username);
        $user = User::create([
            'cargo_id' => $cargo->id,
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $pi_cliente_id,
            'name' => $nombre_comercial,
            'username' => $username_minus,
            'password' => bcrypt('123456'),
            'estado' => '1'
        ]);

        return $user;
    }

    public function crear_registro_gerente($user,$cargo,$empresa,$pi_cliente_id)
    {
        $personal = Personal::create([
            'user_id' => $user->id,
            'cargo_id' => $cargo->id,
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $pi_cliente_id,
            'estado' => '1'
        ]);

        $gestion = substr(date('Y'), 2, 4);
        $nro_contrato = count(PersonalLaboral::where('empresa_id',$empresa->id)->get()) + 1;
        $nro_contrato = str_pad($nro_contrato, 3, '0', STR_PAD_LEFT);
        $codigo_ingreso = $empresa->alias . '-' . $gestion . '-' . $nro_contrato;

        $personal_laboral = PersonalLaboral::create([
            'personal_id' => $personal->id,
            'user_id' => $user->id,
            'cargo_id' => $cargo->id,
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $pi_cliente_id,
            'horario_id' => NULL,
            'codigo_ingreso' => $codigo_ingreso,
            'biometrico_id' => NULL,
            'tipo_contrato' => NULL,
            'fecha_contrato_fijo' => NULL,
            'profesion_ocupacion' => NULL,
            'banco' => NULL,
            'nro_cuenta' => NULL,
            'estado' => '1'
        ]);

        return $personal;
    }

    public function crear_plan_cuentas($empresa,$pi_cliente_id)
    {
        $cuentas = PlanCuenta::CUENTAS;
        $cont = 1;
        while($cont <= count($cuentas)){
            $plan_de_cuenta = PlanCuenta::create([
                'empresa_id' => $empresa->id,
                'pi_cliente_id' => $pi_cliente_id,
                'moneda_id' => 2,
                'pais_id' => 1,
                'nombre' => $cuentas[$cont],
                'codigo' => $cont,
                'nivel' => '0',
                'parent_id' => null,
                'auxiliar' => '0',
                'banco' => '0',
                'detalle' => '0',
                'estado' => '1'
            ]);

            $cont++;
        }

        return $plan_de_cuenta;
    }

    public function editar($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $empresa_cliente = Empresa::find($empresa_id);
        $cliente = PiCliente::find($empresa_cliente->pi_cliente_id);
        $modulos = Modulo::where('estado','1')->pluck('nombre','id');
        $modulos_empresas = EmpresaModulo::where('empresa_id',$empresa_id)->get();
        return view('empresas.editar', compact('icono','header','empresa_cliente','cliente','modulos','modulos_empresas'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre_comercial' => 'required|unique:empresas,nombre_comercial,' . $request->empresa_id . ',id,pi_cliente_id,' . $request->pi_cliente_id,
            'direccion' => 'required',
            'logo' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'cover' => 'nullable|file|mimes:png,jpg,jpeg|max:2048'
        ]);
        try{
            $logo = isset($request->logo) ? 'logo.'.pathinfo($request->logo->getClientOriginalName(), PATHINFO_EXTENSION) : null;
            $cover = isset($request->cover) ? 'cover.'.pathinfo($request->cover->getClientOriginalName(), PATHINFO_EXTENSION) : null;
            $empresa = Empresa::find($request->empresa_id);
            $datos = [
                'pi_cliente_id' => $request->pi_cliente_id,
                'nombre_comercial' => $request->nombre_comercial,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'alias' => $request->alias
            ];

            if(isset($request->modulo_id)){
                $cont = 0;
                while($cont < count($request->modulo_id)){
                    $empresa_modulo = EmpresaModulo::create([
                        'empresa_id' => $empresa->id,
                        'pi_cliente_id' => $request->pi_cliente_id,
                        'modulo_id' => $request->modulo_id[$cont],
                        'fecha_registro' => date('Y-m-d'),
                        'estado' => '1'
                    ]);

                    $cont++;
                }
            }
            $empresa->update($datos);
            if($logo != null){
                $empresa->update([
                    'url_logo' => 'uploads/empresas/' . $empresa->id . '/logos/' . $logo,
                ]);
            }

            if($cover != null){
                $empresa->update([
                    'url_cover' => 'uploads/empresas/' . $empresa->id . '/logos/' . $cover,
                ]);
            }

            $logo = isset($request->logo) ? $request->logo->move(public_path('uploads/empresas/' . $empresa->id . '/logos/'), $logo) : null;
            $cover = isset($request->cover) ? $request->cover->move(public_path('uploads/empresas/' . $empresa->id . '/logos/'), $cover) : null;
            return redirect()->route('empresas.index',$request->pi_cliente_id)->with('success_message', 'Se modifico una empresa correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('empresas.update',$request->pi_cliente_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function modulo_habilitar($empresa_modulo_id)
    {
        try{
            $empresa_modulo = EmpresaModulo::find($empresa_modulo_id);
            $empresa_modulo->update([
                'estado' => '1'
            ]);
            return redirect()->route('empresas.editar',$empresa_modulo->empresa_id)->with('success_message', '[MODULO HABILITADO].');
        } catch (ValidationException $e) {
            return redirect()->route('empresas.editar',$empresa_modulo->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function modulo_deshabilitar($empresa_modulo_id)
    {
        try{
            $empresa_modulo = EmpresaModulo::find($empresa_modulo_id);
            $empresa_modulo->update([
                'estado' => '2'
            ]);

            $roles_permissions = DB::table('role_has_permissions')
                                        ->where('empresa_id',$empresa_modulo->empresa_id)
                                        ->where('modulo_id',$empresa_modulo->modulo_id)
                                        ->select('role_id')
                                        ->groupBy('role_id')
                                        ->get();
            if($roles_permissions != null){
                foreach ($roles_permissions as $role_permission) {
                    $role = Role::find($role_permission->role_id);
                    $roles_permissions = DB::table('role_has_permissions')
                                        ->where('role_id',$role_permission->role_id)
                                        ->where('empresa_id',$empresa_modulo->empresa_id)
                                        ->where('modulo_id','!=',$empresa_modulo->modulo_id)
                                        ->get();
                    if(count($roles_permissions) > 0){
                        foreach($roles_permissions as $datos){
                            $permission[] = $datos->permission_id;
                        }
                        $role->permissions()->sync($permission);
                    }else{
                        $role->permissions()->sync(null);
                    }
                }
            }
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            return redirect()->route('empresas.editar',$empresa_modulo->empresa_id)->with('success_message', '[MODULO DESHABILITADO].');
        } catch (ValidationException $e) {
            return redirect()->route('empresas.editar',$empresa_modulo->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

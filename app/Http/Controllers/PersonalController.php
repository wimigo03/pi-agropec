<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Empresa;
use App\Models\Personal;
use App\Models\PersonalContrato;
use App\Models\PersonalLaboral;
use App\Models\User;
use App\Models\Afp;
use App\Models\Horario;
use App\Models\HorarioDetalle;
use App\Models\Familiar;
use App\Models\Cargo;
use App\Models\PlanCuentaAuxiliar;
use Auth;

class PersonalController extends Controller
{
    const ICONO = 'fas fa-user-friends fa-fw';
    const INDEX = 'PERSONAL';
    const CREATE = 'REGISTRAR PERSONAL';
    const EDITAR = 'MODIFICAR PERSONAL';
    const SHOW = 'DETALLE DE PERSONAL';
    const RETIRAR = 'RETIRAR PERSONAL';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('personal.index',Auth::user()->empresa_id);
        }
        return view('personal.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $cargos = Cargo::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $estados = PersonalLaboral::ESTADOS;
        $personal_laborales = PersonalLaboral::query()
                                                ->byEmpresa($empresa_id)
                                                ->where('estado','1')
                                                ->orderBy('id','desc')
                                                ->paginate(10);
        return view('personal.index', compact('icono','header','empresa','cargos','estados','personal_laborales'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($request->empresa_id);
        $cargos = Cargo::where('empresa_id',$empresa->id)->pluck('nombre','id');
        $estados = PersonalLaboral::ESTADOS;
        $personal_laborales = PersonalLaboral::query()
                                                ->byEmpresa($empresa->id)
                                                ->byCodigoIngreso($request->codigo_ingreso)
                                                ->byCodigoRetiro($request->codigo_retiro)
                                                ->byNroCarnet($request->ci_run)
                                                ->byPrimerNombre($request->primer_nombre)
                                                ->byApellidoPaterno($request->apellido_paterno)
                                                ->byApellidoMaterno($request->apellido_materno)
                                                ->byCargo($request->cargo_id)
                                                ->byContrato($request->file_contrato)
                                                ->byEstado($request->estado)
                                                ->orderBy('id','desc')
                                                ->paginate(10);
        return view('personal.index', compact('icono','header','empresa','cargos','estados','personal_laborales'));

    }

    public function create($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($id);
        $empresas = Empresa::query()
                            ->byCliente()
                            ->where('id',$id)
                            ->pluck('nombre_comercial','id');
        $nacionalidades = Personal::NACIONALIDADES;
        $extensiones = Personal::EXTENSIONES;
        $licencia_categorias = Personal::LICENCIA_CATEGORIAS;
        $cargos = Cargo::where('empresa_id',$empresa->id)->where('estado','1')->pluck('nombre','id');
        $afps = Afp::pluck('nombre','id');
        $horarios = Horario::where('empresa_id',$id)->where('nombre','OFICINA')->pluck('nombre','id');
        $tipo_familiares = Familiar::TIPO_FAMILIARES;
        $ocupaciones = Familiar::OCUPACIONES;
        $niveles_estudio = Familiar::NIVELES_ESTUDIO;
        return view('personal.create',
                compact('icono',
                        'header',
                        'empresa',
                        'empresas',
                        'nacionalidades',
                        'extensiones',
                        'licencia_categorias',
                        'cargos',
                        'afps',
                        'horarios',
                        'tipo_familiares',
                        'ocupaciones',
                        'niveles_estudio'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cargo_id' => 'required',
            'empresa_id' => 'required',
            'ap_paterno' => 'required_without_all:ap_materno',
            'ap_materno' => 'required_without_all:ap_paterno',
            'ci_run' => 'required|numeric|integer|unique:personal,ci_run,null,id,empresa_id,'. $request->empresa_id,
            'extension' => 'required',
            'nacionalidad' => 'required',
            'sexo' => 'required',
            'licencia_conducir' => 'required',
            'lugar_nacimiento' => 'required',
            'fecha_nac' => 'required',
            'domicilio' => 'required',
            'celular' => 'required',
            'estado_civil' => 'required',
            'horario_id' => 'required',
            'tipo_contrato' => 'required',
            'profesion_ocupacion' => 'required',
            'foto' => 'nullable|file|mimes:png,jpg,jpeg|max:2048'
        ]);
        try{
            $empresa = Empresa::where('id',$request->empresa_id)->first();
            $apellidos = $request->ap_paterno != null ? $request->ap_paterno . ' ' . $request->ap_materno : $request->ap_materno;
            $apellido_username = $request->ap_paterno != null ? $request->ap_paterno : $request->ap_materno;
            $username = substr($request->primer_nombre, 0, 2) . $apellido_username;;
            $username_minus = strtolower($username);
            $user = User::create([
                'cargo_id' => $request->cargo_id,
                'empresa_id' => $request->empresa_id,
                'cliente_id' => Auth::user()->cliente_id,
                'name' => $request->primer_nombre . ' ' . $apellidos,
                'username' => $username_minus,
                'password' => bcrypt('123456654321'),
                'estado' => '1'
            ]);

            $datos_personal = ([
                'user_id' => $user->id,
                'cargo_id' => $request->cargo_id,
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'primer_nombre' => $request->primer_nombre,
                'segundo_nombre' => $request->segundo_nombre,
                'apellido_paterno' => $request->ap_paterno,
                'apellido_materno' => $request->ap_materno,
                'ci_run' => $request->ci_run,
                'extension' => $request->extension,
                'nacionalidad' => $request->nacionalidad,
                'sexo' => $request->sexo,
                'licencia_conducir' => $request->licencia_conducir,
                'licencia_categoria' => $request->licencia_categoria,
                'lugar_nacimiento' => $request->lugar_nacimiento,
                'fecha_nac' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_nac))),
                'foto' => null,
                'direccion_domicilio' => $request->domicilio,
                'celular' => $request->celular,
                'telefono_fijo' => $request->telefono,
                'estado_civil' => $request->estado_civil,
                'estado' => '1'
            ]);

            $personal = Personal::create($datos_personal);

            $personal_photo = Personal::find($personal->id);
            $foto = isset($request->foto) ? 'foto.'.pathinfo($request->foto->getClientOriginalName(), PATHINFO_EXTENSION) : null;
            $photo = isset($request->foto) ? 'uploads/empresas/' . $request->empresa_id . '/personal/'. $personal->id . '/foto.'.pathinfo($request->foto->getClientOriginalName(), PATHINFO_EXTENSION) : null;
            $cargar_photo = isset($request->foto) ? $request->foto->move(public_path('uploads/empresas/' . $request->empresa_id . '/personal/' . $personal->id . '/'), $foto) : null;
            $personal_photo->update([
                'foto' => $photo
            ]);


            if(isset($request->nombre_familiar)){
                $cont = 0;
                while($cont < count($request->nombre_familiar)){
                    $datos_familiares = ([
                        'personal_id' => $personal->id,
                        'user_id' => $user->id,
                        'cargo_id' => $request->cargo_id,
                        'empresa_id' => $request->empresa_id,
                        'cliente_id' => $empresa->cliente_id,
                        'nombre' => $request->nombre_familiar[$cont],
                        'tipo' => $request->tipo_familiar[$cont],
                        'observacion' => $request->otro_tipo_familiar[$cont],
                        'ocupacion' => $request->ocupacion_familiar[$cont],
                        'nivel_estudio' => $request->nivel_estudio_familiar[$cont],
                        'telefono' => $request->telefono_familiar[$cont],
                        'edad' => $request->edad_familiar[$cont],
                        'estado' => '1'
                    ]);

                    $familiar = Familiar::create($datos_familiares);
                    $cont++;
                }
            }

            $horario_id = $request->horario_id;

            if($request->horario_id == '_MANUAL_'){
                $datos_horario = ([
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'nombre' => 'MANUAL',
                    'estado' => '1'
                ]);

                $horario = Horario::create($datos_horario);

                $datos_horario_detalle_lunes = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'LUNES',
                    'entrada_1' => $request->dia_inicio_lunes,
                    'salida_1' => $request->dia_final_lunes,
                    'entrada_2' => $request->tarde_final_lunes,
                    'salida_2' => $request->tarde_final_lunes,
                    'estado' => '1'
                ]);

                $horario_detalle_lunes = HorarioDetalle::create($datos_horario_detalle_lunes);

                $datos_horario_detalle_martes = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'MARTES',
                    'entrada_1' => $request->dia_inicio_martes,
                    'salida_1' => $request->dia_final_martes,
                    'entrada_2' => $request->tarde_final_martes,
                    'salida_2' => $request->tarde_final_martes,
                    'estado' => '1'
                ]);

                $horario_detalle_martes = HorarioDetalle::create($datos_horario_detalle_martes);

                $datos_horario_detalle_miercoles = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'MIERCOLES',
                    'entrada_1' => $request->dia_inicio_miercoles,
                    'salida_1' => $request->dia_final_miercoles,
                    'entrada_2' => $request->tarde_final_miercoles,
                    'salida_2' => $request->tarde_final_miercoles,
                    'estado' => '1'
                ]);

                $horario_detalle_miercoles = HorarioDetalle::create($datos_horario_detalle_miercoles);

                $datos_horario_detalle_jueves = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'JUEVES',
                    'entrada_1' => $request->dia_inicio_jueves,
                    'salida_1' => $request->dia_final_jueves,
                    'entrada_2' => $request->tarde_final_jueves,
                    'salida_2' => $request->tarde_final_jueves,
                    'estado' => '1'
                ]);

                $horario_detalle_jueves = HorarioDetalle::create($datos_horario_detalle_jueves);

                $datos_horario_detalle_viernes = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'VIERNES',
                    'entrada_1' => $request->dia_inicio_viernes,
                    'salida_1' => $request->dia_final_viernes,
                    'entrada_2' => $request->tarde_final_viernes,
                    'salida_2' => $request->tarde_final_viernes,
                    'estado' => '1'
                ]);

                $horario_detalle_viernes = HorarioDetalle::create($datos_horario_detalle_viernes);

                $datos_horario_detalle_sabado = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'SABADO',
                    'entrada_1' => $request->dia_inicio_sabado,
                    'salida_1' => $request->dia_final_sabado,
                    'entrada_2' => $request->tarde_final_sabado,
                    'salida_2' => $request->tarde_final_sabado,
                    'estado' => '1'
                ]);

                $horario_detalle_sabado = HorarioDetalle::create($datos_horario_detalle_sabado);

                $datos_horario_detalle_domingo = ([
                    'horario_id' => $horario->id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'dia' => 'DOMINGO',
                    'entrada_1' => $request->dia_inicio_domingo,
                    'salida_1' => $request->dia_final_domingo,
                    'entrada_2' => $request->tarde_final_domingo,
                    'salida_2' => $request->tarde_final_domingo,
                    'estado' => '1'
                ]);

                $horario_detalle_domingo = HorarioDetalle::create($datos_horario_detalle_domingo);

                $horario_id = $horario->id;
            }

            $gestion = substr(date('Y'), 2, 4);
            $nro_contrato = count(PersonalLaboral::where('empresa_id',$request->empresa_id)->get()) + 1;
            $nro_contrato = str_pad($nro_contrato, 3, '0', STR_PAD_LEFT);
            $codigo_ingreso = $empresa->alias . '-' . $gestion . '-' . $nro_contrato;

            $datos_personal_laboral = ([
                'personal_id' => $personal->id,
                'user_id' => $user->id,
                'cargo_id' => $request->cargo_id,
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'horario_id' => $horario_id,
                'codigo_ingreso' => $codigo_ingreso,
                'biometrico_id' => $request->biometrico,
                'tipo_contrato' => $request->tipo_contrato,
                'fecha_contrato_fijo' => $request->fecha_contrato_fijo != null ? date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_contrato_fijo))) : null,
                'profesion_ocupacion' => $request->profesion_ocupacion,
                'banco' => $request->banco,
                'nro_cuenta' => $request->nro_cuenta,
                'estado' => '1'
            ]);

            $personal_laboral = PersonalLaboral::create($datos_personal_laboral);

            if($request->fiscal != null){
                $datos_personal_fiscal = ([
                    'personal_id' => $personal->id,
                    'personal_laboral_id' => $personal_laboral->id,
                    'user_id' => $user->id,
                    'cargo_id' => $request->cargo_id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'afp_id' => $request->afp_id,
                    'tipo' => 'F',
                    'fecha_ingreso' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_ingreso_fiscal))),
                    'sueldo' => $request->haber_basico_fiscal,
                    'tipo_bono' => $request->tipo_bono_fiscal,
                    'bono' => $request->bono_fiscal,
                    'estado' => '1'
                ]);
                $personal_contrato_fiscal = PersonalContrato::create($datos_personal_fiscal);
            }

            if($request->interno != null){
                $datos_personal_interno = ([
                    'personal_id' => $personal->id,
                    'personal_laboral_id' => $personal_laboral->id,
                    'user_id' => $user->id,
                    'cargo_id' => $request->cargo_id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'tipo' => 'I',
                    'fecha_ingreso' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_ingreso_interna))),
                    'sueldo' => $request->haber_basico_interno,
                    'tipo_bono' => $request->tipo_bono_interno,
                    'bono' => $request->bono_interno,
                    'estado' => '1'
                ]);
                $personal_contrato_interno = PersonalContrato::create($datos_personal_interno);
            }

            if($request->servicio != null){
                $datos_personal_servicio = ([
                    'personal_id' => $personal->id,
                    'personal_laboral_id' => $personal_laboral->id,
                    'user_id' => $user->id,
                    'cargo_id' => $request->cargo_id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'tipo' => 'S',
                    'fecha_ingreso' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_ingreso_servicio))),
                    'sueldo' => $request->haber_basico_servicio,
                    'tipo_bono' => $request->tipo_bono_servicio,
                    'bono' => $request->bono_servicio,
                    'estado' => '1'
                ]);
                $personal_contrato_servicio = PersonalContrato::create($datos_personal_servicio);
            }

            $datos_auxiliar = ([
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'user_id' => $user->id,
                'nombre' => $personal->primer_nombre . ' ' . $personal->apellido_paterno . ' ' . $personal->apellido_materno,
                'class_name' => Personal::class,
                'class_name_id' => $personal->id,
                'tipo' => '1',
                'estado' => '1'
            ]);

            $auxiliar = PlanCuentaAuxiliar::create($datos_auxiliar);

            return redirect()->route('personal.index',$request->empresa_id)->with('success_message', 'Se agregó personal en la empresa seleccionada.');
        } catch (ValidationException $e) {
            return redirect()->route('personal.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $personal = Personal::find($id);
        $empresa = Empresa::find($personal->empresa_id);
        $nacionalidades = Personal::NACIONALIDADES;
        $extensiones = Personal::EXTENSIONES;
        $licencia_categorias = Personal::LICENCIA_CATEGORIAS;
        $afps = Afp::get();
        $horarios = Horario::where('empresa_id',$personal->empresa_id)->where('nombre','OFICINA')->pluck('nombre','id');
        $tipo_familiares = Familiar::TIPO_FAMILIARES;
        $ocupaciones = Familiar::OCUPACIONES;
        $niveles_estudio = Familiar::NIVELES_ESTUDIO;
        return view('personal.editar',
                compact('icono',
                        'header',
                        'personal',
                        'empresa',
                        'nacionalidades',
                        'extensiones',
                        'licencia_categorias',
                        'afps',
                        'horarios',
                        'tipo_familiares',
                        'ocupaciones',
                        'niveles_estudio'));
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
                'cliente_id' => $request->cliente_id,
                'nombre' => $request->cargo,
                'parent_id' => $request->parent_id,
                'email' => $request->email,
                'descripcion' => $request->descripcion,
                'alias' => $request->alias,
                'tipo' => $request->tipo
                ]);
            return redirect()->route('cargos.index', ['nodeId' => $cargo->id])->with('success_message', 'Se modifico el cargo seleccionado.');
        } catch (ValidationException $e) {
            return redirect()->route('cargos.editar')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function show($id)
    {
        $icono = self::ICONO;
        $header = self::SHOW;
        $personal_laboral = PersonalLaboral::where('id',$id)->first();
        $personal = Personal::find($personal_laboral->personal_id);
        $personal_contrato_fiscal = PersonalContrato::where('personal_laboral_id',$id)->where('tipo','F')->first();
        $personal_contrato_interno = PersonalContrato::where('personal_laboral_id',$id)->where('tipo','I')->first();
        $personal_contrato_servicio = PersonalContrato::where('personal_laboral_id',$id)->where('tipo','S')->first();
        $empresa = Empresa::find($personal_laboral->empresa_id);
        $horario_laboral = Horario::find($personal_laboral->horario_id);
        $horario_laboral_detalle = HorarioDetalle::where('horario_id',$personal_laboral->horario_id)->get();
        $familiares = Familiar::where('personal_id',$personal_laboral->personal_id)->get();
        return view('personal.show', compact(
                                            'icono',
                                            'header',
                                            'personal',
                                            'empresa',
                                            'personal_laboral',
                                            'personal_contrato_fiscal',
                                            'personal_contrato_interno',
                                            'personal_contrato_servicio',
                                            'horario_laboral',
                                            'horario_laboral_detalle',
                                            'familiares'));
    }

    public function retirar($personal_laboral_id, $tipo)
    {
        $icono = self::ICONO;
        $header = self::RETIRAR;
        $personal_laboral = PersonalLaboral::find($personal_laboral_id);
        $personal = Personal::find($personal_laboral->personal_id);
        $empresa = Empresa::find($personal->empresa_id);
        return view('personal.retirar', compact('icono','header','personal_laboral','personal','empresa','tipo'));
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'fecha_retiro' => 'required',
            'tipo_retiro' => 'required'
        ]);
        try{
            $personal_laboral = PersonalLaboral::find($request->personal_laboral_id);
            $personal = Personal::find($request->personal_id);
            $empresa = Empresa::find($personal->empresa_id);
            $contrato_id = PersonalContrato::where('personal_id',$request->personal_id)->where('tipo',$request->tipo)->where('codigo_retiro',null)->first()->id;
            $personal_contrato = PersonalContrato::find($contrato_id);

            $gestion = date('Y', strtotime(str_replace('/', '-', $request->fecha_retiro)));
            $gestion = substr($gestion, 2, 4);
            $nro_retiro = count(PersonalContrato::where('empresa_id',$empresa->id)->where('tipo',$request->tipo)->where('codigo_retiro','!=',null)->get()) + 1;
            $nro_retiro = str_pad($nro_retiro, 3, '0', STR_PAD_LEFT);
            $codigo_retiro = 'R/' . $empresa->alias . '-' . $gestion . '-' . $nro_retiro;

            $datos_contrato = ([
                'codigo_retiro' => $codigo_retiro,
                'fecha_retiro' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha_retiro))),
                'tipo_retiro' => $request->tipo_retiro,
                'motivo_retiro' => $request->motivo_retiro,
            ]);
            $personal_contrato->update($datos_contrato);

            $contratos = PersonalContrato::where('personal_id',$request->personal_id)->where('codigo_retiro',null)->first();
            if($contratos == null){
                $datos_personal_laboral = ([
                    'estado' => '3'
                ]);
                $personal_laboral->update($datos_personal_laboral);
            }

            return redirect()->route('personal.index',$personal->empresa_id)->with('success_message', 'Retiro realizado correctamente...');
        } catch (ValidationException $e) {
            return redirect()->route('personal.retirar',['personal' => $personal->id, 'tipo' => $request->tipo])
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function file_contrato($id)
    {
        $personal_laboral = PersonalLaboral::find($id);
        $personal = Personal::find($personal_laboral->personal_id);
        return view('personal.fileContrato', compact('personal_laboral','personal'));
    }

    public function file_contrato_store(Request $request)
    {
        $request->validate([
            'file_contrato' => 'required|file|mimes:pdf|max:2048',
        ]);
        try{
            $personal_laboral = PersonalLaboral::find($request->personal_laboral_id);
            $file_contrato = 'contrato_' . strtolower($personal_laboral->codigo_ingreso) . '.' . pathinfo(strtolower($request->file_contrato->getClientOriginalName()), PATHINFO_EXTENSION);
            $empresa = Empresa::find($request->empresa_id);
            $personal_laboral->update([
                'file_contrato' => 'uploads/empresas/' . $empresa->id . '/file/' . $file_contrato
                ]);

            $_file_contrato = $request->file_contrato->move(public_path('uploads/empresas/' . $empresa->id . '/file/'), $file_contrato);

            return redirect()->route('personal.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó un producto correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('personal.file.store',$request->personal_laboral_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

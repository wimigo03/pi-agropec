<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Configuracion;
use App\Models\InicioMesFiscal;
use App\Models\TipoCambio;
use Illuminate\Support\Facades\App;
use DB;
use Illuminate\Support\Facades\Log;

class ConfiguracionController extends Controller
{
    const ICONO = 'fa-solid fa-gear fa-fw';
    const INDEX = 'CONFIGURACIONES';
    const CREATE = 'CREAR CONFIGURACION';
    const EDITAR = 'MODIFICAR CONFIGURACION';
    const INICIOMESFISCAL = 'INICIO DE MES FISCAL';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('configuracion.index',Auth::user()->empresa_id);
        }
        return view('configuracion.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = configuracion::ESTADOS;
        $empresa = Empresa::find($empresa_id);
        $configuraciones = Configuracion::query()
                                            ->ByEmpresa($empresa_id)
                                            ->orderBy('id','desc')
                                            ->paginate(10);
        return view('configuracion.index', compact('icono','header','estados','empresa','configuraciones'));
    }

    public function search(Request $request)
    {
        $fecha = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        $icono = self::ICONO;
        $header = self::INDEX;
        $tipo_precios = TipoPrecio::pluck('nombre','id');
        $tipo_movimientos = PrecioProducto::TIPO_MOVIMIENTOS;
        $estados = PrecioProducto::ESTADOS;
        $empresa = Empresa::find($request->empresa_id);
        $precio_productos = PrecioProducto::query()
                        ->byEmpresa($empresa->id)
                        ->byTipoPrecio($request->tipo_precio_id)
                        ->byCodigo($request->codigo)
                        ->byProducto($request->producto)
                        ->byEstado(1)
                        ->orderBy('id','desc')
                        ->get();
        return view('precio_productos.index', compact('tipo_cambio','icono','header','estados','tipo_precios','tipo_movimientos','empresa','precio_productos'));
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $tipos = configuracion::TIPOS;
        return view('configuracion.create', compact('icono','header','empresa','tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required',
            'nombre' => 'required',
            'detalle' => 'required'
        ]);
        try{
            $empresa = Empresa::find($request->empresa_id);
            $user = User::where('id',Auth::user()->id)->first();
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
                'detalle' => $request->detalle,
                'estado' => '1'
            ];
            $configuracion = Configuracion::create($datos);

            return redirect()->route('configuracion.index',['empresa_id' => $request->empresa_id])->with('success_message', 'CONFIGURACION CREADA.');
        } catch (ValidationException $e) {
            return redirect()->route('configuracion.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function show($configuracion_id)
    {
        $configuracion = Configuracion::find($configuracion_id);
        switch ($configuracion->nombre){
            case 'INICIO_MES_FISCAL':
                return redirect()->route('inicio.mes.fiscal.create',$configuracion_id);
                break;
        }

    }

    public function inicioMesFiscalCreateStore(Request $request)
    {
        $request->validate([
            'dia' => 'required',
            'mes' => 'required',
        ]);

        /*$tipo_cambio = TipoCambio::where('fecha',date('Y-m-d'))->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] actual...');
        }*/

        /*$fecha_comprobante = date('Y') . '-' . $request->mes . '-' . $request->dia;
        $comprobante_controller = new ComprobanteController;
        $comprobante = $comprobante_controller->primerComprobanteMes('3',$request->empresa_id,$fecha_comprobante);
        if($comprobante != null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. EL COMPROBANTE NRO 1 YA EXISTE EN EL MES SELECCIONADO]')->withInput();
        }*/


        try{
            $function = DB::transaction(function () use ($request) {
                $anterior_mes_fiscal = InicioMesFiscal::where('configuracion_id',$request->configuracion_id)
                                                        ->where('empresa_id',$request->empresa_id)
                                                        ->where('estado','1')
                                                        ->orderBy('id','desc')
                                                        ->first();
                if($anterior_mes_fiscal != null){
                    $anteriorMesFiscal = inicioMesFiscal::find($anterior_mes_fiscal->id);
                    $anteriorMesFiscal->update([
                        'estado' =>'2'
                    ]);
                }

                $date = date('Y-m-d');
                $empresa = Empresa::find($request->empresa_id);
                $datos = [
                    'configuracion_id' => $request->configuracion_id,
                    'empresa_id' => $request->empresa_id,
                    'cliente_id' => $empresa->cliente_id,
                    'user_id' => Auth::user()->id,
                    'dia' => $request->dia,
                    'mes' => $request->mes,
                    'fecha' => $date,
                    'estado' => '1'
                ];

                $inicio_mes_fiscal = InicioMesFiscal::create($datos);

                $configuracion = Configuracion::find($request->configuracion_id);
                $configuracion->update([
                        'estado' => '2'
                ]);

                /*$comprobante_controller = new ComprobanteController;
                $datos_comprobante = [
                    'user_id' => Auth::user()->id,
                    'copia' => 'on',
                    'moneda_id' => 2,
                    'empresa_id' => $request->empresa_id,
                    'fecha_comprobante' => $fecha_comprobante,
                    'tipo' => '3',
                    'entregado_recibido' => null,
                    'concepto' => 'BALANCE DE APERTURA',
                    'monto_total' => 0.00
                ];
                $comprobante = $comprobante_controller->crearEncabezadoComprobante($datos_comprobante);*/

                return $inicio_mes_fiscal;
            });
            Log::channel('configuraciones')->info(
                "Configuracion: Creada con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );
            return redirect()->route('configuracion.index',['empresa_id' => $request->empresa_id])->with('success_message', 'CONFIGURACION CREADA CON EXITO. EL TIPO DE CAMBIO TOMA EL VALOR DE LA FECHA ACTUAL');
        } catch (\Exception $e) {
            Log::channel('configuraciones')->info(
                "Error al crear el inicio de mes fiscal: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al crear inicio de mes fiscal]')->withInput();
        }
    }

    public function editar($id)
    {
    }

    public function update(Request $request)
    {
    }

    public function habilitar($id)
    {
    }

    public function deshabilitar($id)
    {
    }
}

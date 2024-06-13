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

class InicioMesFiscalController extends Controller
{
    const ICONO = 'fa-solid fa-gear fa-fw';
    const INDEX = 'INICIO MES FISCAL';
    const CREATE = 'CONFIGURACION INICIO DE MES ACTUAL';
    const SHOW = 'DETALLE INICIO DE MES ACTUAL';

    public function index()
    {
    }

    public function search(Request $request)
    {
    }

    public function create($configuracion_id)
    {
        $configuracion = Configuracion::find($configuracion_id);
        $icono = self::ICONO;
        $empresa = Empresa::find($configuracion->empresa_id);
        if($configuracion->estado == 1)
        {
            $header = self::CREATE;
            $meses = InicioMesFiscal::MESES;
            return view('inicio_mes_fiscal.create', compact('configuracion','icono','header','empresa','meses'));
        }else{
            $header = self::SHOW;
            $inicio_mes_fiscal = InicioMesFiscal::where('configuracion_id',$configuracion_id)->where('estado','1')->first();
            return view('inicio_mes_fiscal.show', compact('configuracion','icono','header','empresa','inicio_mes_fiscal'));
        }
    }

    public function store(Request $request)
    {
        try{
            $function = DB::transaction(function () use ($request) {
                $empresa = Empresa::find($request->empresa_id);
                $user = User::where('id',Auth::user()->id)->first();
                $datos = [
                    'empresa_id' => $empresa->id,
                    'pi_cliente_id' => $empresa->pi_cliente_id,
                    'user_id' => $user->id,
                    'cargo_id' => $user->cargo_id,
                    'configuracion_id' => $request->configuracion_id,
                    'fecha_registro' => date('Y-m-d'),
                    'mes' => $request->mes,
                    'inicio_gestion' => $request->anho,
                    'otro_sistema' => $request->pregunta_1,
                    'fecha_otro_sistema' => date('Y-m-d', strtotime(str_replace('/', '-', $request->fecha))),
                    'estado' => '1'
                ];
                $inicio_mes_fiscal = InicioMesFiscal::create($datos);

                $configuracion = Configuracion::find($request->configuracion_id);
                $configuracion->update([
                    'estado' => '2'
                ]);

                if($request->pregunta_1 == '1')
                {
                    $comprobante_inicial = $this->crearComprobanteInicial(
                        $request->empresa_id,
                        $request->fecha,
                        $user->id
                    );

                    return [
                        'inicio_mes_fiscal' => $inicio_mes_fiscal,
                        'comprobante_inicial' => $comprobante_inicial
                    ];;
                }
                else
                {
                    return $inicio_mes_fiscal;
                }
            });

            Log::channel('configuraciones')->info(
                "Configuracion Inicio de Mes Fiscal: Creada con éxito" . "\n" .
                "Usuario: " . Auth::user()->id . "\n"
            );

            return redirect()->route('configuracion.index',['empresa_id' => $request->empresa_id])->with('success_message', 'CONFIGURACION CREADA.');
        } catch (ValidationException $e) {
            Log::channel('configuraciones')->info(
                "Error al crear Configuracion de Inicio de Mes Fiscal: " . "\n" .
                "Usuario: " . Auth::user()->id . "\n" .
                "Error: " . $e->getMessage() . "\n"
            );
            return redirect()->back()->with('error_message','[Ocurrio un Error al Crear La configuracion de inicio de mes fiscal.]')->withInput();
        }
    }

    public function crearComprobanteInicial($empresa_id, $date, $user_id)
    {
        $comprobante_controller = new ComprobanteController;
        $comprobante = $comprobante_controller->primerComprobanteMes(
            '3',
            $empresa_id,
            $date
        );

        $datos_comprobante = [
            'user_id' => $user_id,
            'copia' => 'on',/**OJO */
            'moneda_id' => 2,
            'empresa_id' => $empresa_id,
            'fecha_comprobante' => $date,
            'tipo' => '3',
            'entregado_recibido' => null,
            'concepto' => 'COMPROBANTE INICIAL',
            'monto_total' => 0.00
        ];

        $comprobante = $comprobante_controller->crearEncabezadoComprobante($datos_comprobante);

        return $comprobante;
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

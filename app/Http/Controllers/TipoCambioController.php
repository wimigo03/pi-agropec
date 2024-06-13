<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\TipoCambio;
use App\Models\Comprobante;
use Auth;
use PDF;

class TipoCambioController extends Controller
{
    const ICONO = 'fa-solid fa-circle-dollar-to-slot fa-fw';
    const INDEX = 'TIPO DE CAMBIOS';
    const CREATE = 'REGISTRAR TIPO DE CAMBIO';
    const EDITAR = 'MODIFICAR TIPO DE CAMBIO';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('tipo.cambio.index',Auth::user()->empresa_id);
        }
        return view('tipo_cambios.indexAfter', compact('empresas'));
    }

    private function completar_fechas()
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');

                $fechaInicial = '2015-01-01';
                $fechaFinal = '2024-05-20';

                $timestampInicial = strtotime($fechaInicial);
                $timestampFinal = strtotime($fechaFinal);

                $fechas = [];

                for ($currentTimestamp = $timestampInicial; $currentTimestamp <= $timestampFinal; $currentTimestamp = strtotime('+1 day', $currentTimestamp)) {

                    //echo date('Y-m-d', $currentTimestamp) . '<br>';
                    $datos = [
                        'empresa_id' => 1,
                        'pi_cliente_id' => 1,
                        'fecha' => date('Y-m-d', $currentTimestamp),
                        'ufv' => '1',
                        'dolar_oficial' => '6.96',
                        'dolar_compra' => '6.96',
                        'dolar_venta' => '6.96',
                        'estado' => '1'
                    ];

                    $cambio = TipoCambio::create($datos);
                }
                dd("ok");
        } finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function index()
    {
        //if(Auth::user()->id == 1){
            //$this->completar_fechas();
        //}
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = TipoCambio::ESTADOS;
        $tipo_cambios = TipoCambio::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('tipo_cambios.index', compact('icono','header','estados','tipo_cambios'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = TipoCambio::ESTADOS;
        $tipo_cambios = TipoCambio::query()
                                ->byPiCliente(Auth::user()->pi_cliente_id)
                                ->byEntreFechas($request->fecha_i,$request->fecha_f)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('tipo_cambios.index', compact('icono','header','estados','tipo_cambios'));

    }

    public function create()
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $cambio_anterior = TipoCambio::query()
                                        ->byPiCliente(Auth::user()->pi_cliente_id)
                                        ->where('fecha','<',date('Y-m-d'))
                                        ->where('estado','1')
                                        ->orderBy('fecha','desc')
                                        ->first();
        return view('tipo_cambios.create', compact('icono','header','cambio_anterior'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required',
            'ufv' => 'required',
            'oficial' => 'required',
            'compra' => 'required',
            'venta' => 'required'
        ]);

        $fecha = date('Y-m-d', strtotime($request->fecha));
        if($fecha > date('Y-m-d')){
            return redirect()->back()->with('info_message', 'La fecha introducida es mayor a la fecha actual.')->withInput();
        }
        $tipo_cambio = TipoCambio::query()->byPiCliente(Auth::user()->pi_cliente_id)->where('fecha',$fecha)->first();
        if($tipo_cambio != null){
            return redirect()->back()->with('info_message', 'Ya existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        try{
            $datos = [
                'pi_cliente_id' => Auth::user()->pi_cliente_id,
                'fecha' => $fecha,
                'ufv' => floatval(str_replace(",", "", $request->ufv)),
                'dolar_oficial' => floatval(str_replace(",", "", $request->oficial)),
                'dolar_compra' => floatval(str_replace(",", "", $request->compra)),
                'dolar_venta' => floatval(str_replace(",", "", $request->venta)),
                'estado' => '1'
            ];
            $cambio = TipoCambio::create($datos);

            return redirect()->route('tipo.cambio.index')->with('success_message', 'Se agregó un tipo de cambio correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('tipo.cambio.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $tipo_cambio = TipoCambio::find($id);
        $comprobantes = Comprobante::query()
                                    ->byPiCliente(Auth::user()->pi_cliente_id)
                                    ->select('id')
                                    ->where('fecha',$tipo_cambio->fecha)
                                    ->whereIn('estado',['1','2'])
                                    ->first();
        if($comprobantes != null){
            return redirect()->back()->with('info_message', '[ACCION NO PERMITIDA. EXISTEN COMPROBANTES PROCESADOS.]')->withInput();
        }
        $icono = self::ICONO;
        $header = self::EDITAR;
        return view('tipo_cambios.editar', compact('tipo_cambio','icono','header'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ufv' => 'required',
            'oficial' => 'required',
            'compra' => 'required',
            'venta' => 'required'
        ]);
        try{
            $tipo_cambio = TipoCambio::find($request->tipo_cambio_id);
            $datos = [
                'ufv' => $request->ufv,
                'dolar_oficial' => $request->oficial,
                'dolar_compra' => $request->compra,
                'dolar_venta' => $request->venta
            ];
            $tipo_cambio->update($datos);

            return redirect()->route('tipo.cambio.index')->with('info_message', 'La informacion fue actualizada.');
        } catch (ValidationException $e) {
            return redirect()->route('tipo.cambio.create')
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

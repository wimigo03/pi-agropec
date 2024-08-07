<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\BalanceApertura;
use App\Models\BalanceAperturaF;
use App\Models\InicioMesFiscal;
use App\Models\Comprobante;
use App\Models\ComprobanteDetalle;
use App\Models\ComprobanteF;
use App\Models\ComprobanteFDetalle;
use App\Models\Sucursal;
use App\Models\PlanCuenta;
use App\Models\PlanCuentaAuxiliar;
use App\Models\User;
use App\Models\TipoCambio;
use App\Models\Moneda;
use App\Models\EmpresaModulo;
use DB;

class BalanceAperturaController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'BALANCE DE APERTURA';
    const CREATE = 'REGISTRAR BALANCE';
    const EDITAR = 'MODIFICAR BALANCE';
    const MODIFICAR_COMPROBANTE = 'MODIFICAR COMPROBANTE';

    public function indexAfter()
    {
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('balance.apertura.index',Auth::user()->empresa_id);
        }
        return view('balance_apertura.indexAfter', compact('empresas'));
    }

    public function index()
    {
        /*$inicioMesFiscal = InicioMesFiscal::where('empresa_id',$empresa_id)->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. FALTA LA CONFIGURACION DE INICIO MES FISCAL]')->withInput();
        }*/
        $icono = self::ICONO;
        $header = self::INDEX;
        //$empresa = Empresa::find($empresa_id);
        $balances = BalanceApertura::query()
                                    ->byPiCliente(Auth::user()->pi_cliente_id)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('balance_apertura.index', compact('icono','header','balances'));
    }

    public function search(Request $request)
    {dd("ok");
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

    public function create()
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        /*$inicioMesFiscal = InicioMesFiscal::select('inicio_gestion')->where('empresa_id',$empresa_id)->where('estado','1')->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('info_message', '[FALTA CONFIGURACION DE INICIO DE GESTION]')->withInput();
        }*/
        $empresas = Empresa::query()->byPiCliente(Auth::user()->pi_cliente_id)->pluck('nombre_comercial','id');
        //$anho = $inicioMesFiscal->pinicio_gestion;
        $anho = date('Y') - 5;
        for($i = $anho; $i <= $anho + 10; $i++){
            $anhos[$i] = $i;
        }
        return view('balance_apertura.create', compact('icono','header','empresas','anhos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required',
            'anho' => 'required'
        ]);

        $inicioMesFiscal = InicioMesFiscal::where('empresa_id',$request->empresa_id)->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. FALTA LA CONFIGURACION DE INICIO MES FISCAL]')->withInput();
        }

        $balanceApertura = BalanceApertura::where('gestion',$request->anho)->first();
        if($balanceApertura != null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. YA EXISTE UN COMPROBANTE PARA EL BALANCE DE APERTURA EN LA GESTION SELECCIONADA]')->withInput();
        }

        $date = $request->anho . '-' . $inicioMesFiscal->mes . '-01';
        $tipo_cambio = TipoCambio::where('fecha',$date)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA ' . $date . ']')->withInput();
        }

        $comprobante_controller = new ComprobanteController;
        $comprobante = $comprobante_controller->primerComprobanteMes('3',$request->empresa_id,$date);
        if($comprobante != null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. EL COMPROBANTE NRO 1 YA EXISTE EN LA GESTION Y CONFIGURACON SELECCIONADA (I)]')->withInput();
        }

        $comprobantef_controller = new ComprobanteFController;
        $comprobantef = $comprobantef_controller->primerComprobanteMes('3',$request->empresa_id,$date);
        if($comprobantef != null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. EL COMPROBANTE NRO 1 YA EXISTE EN LA GESTION Y CONFIGURACON SELECCIONADA (F)]')->withInput();
        }

        $empresa = Empresa::find($request->empresa_id);
        $user = User::find(Auth::user()->id);
        $moneda = Moneda::where('id',2)->first();
        $empresa_modulo = EmpresaModulo::where('empresa_id',$request->empresa_id)->where('modulo_id','3')->where('estado','1')->first();
        $copia = $empresa_modulo != null ? '1' : '2';
        $date =  '01/' . $inicioMesFiscal->mes . '/' . $request->anho;
        $datos_comprobante = [
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $empresa->pi_cliente_id,
            'tipo_cambio_id' => $tipo_cambio->id,
            'user_id' => $user->id,
            'cargo_id' => $user->cargo_id,
            'moneda_id' => $moneda->id,
            'pais_id' => $moneda->pais_id,
            'tipo_cambio' => $tipo_cambio->dolar_oficial,
            'ufv' => $tipo_cambio->ufv,
            'tipo' => '3',
            'entregado_recibido' => null,
            'fecha' => $date,
            'concepto' => 'BALANCE DE APERTURA',
            'monto' => 0.00,
            'moneda' => $moneda->alias,
            'copia' => $copia,
            'estado' => '1'
        ];
        $comprobante = $comprobante_controller->crearEncabezadoComprobante($datos_comprobante);

        $datos_balance_apertura = [
            'empresa_id' => $empresa->id,
            'pi_cliente_id' => $empresa->pi_cliente_id,
            'user_id' => $user->id,
            'cargo_id' => $user->cargo_id,
            'comprobante_id' => $comprobante->id,
            'tipo_cambio_id' => $comprobante->tipo_cambio_id,
            'inicio_mes_fiscal_id' => $inicioMesFiscal->id,
            'configuracion_id' => $inicioMesFiscal->configuracion_id,
            'moneda_id' => $moneda->id,
            'pais_id' => $moneda->pais_id,
            'gestion' => $request->anho,
            'estado' => '1'
        ];
        $balance_apertura = BalanceApertura::create($datos_balance_apertura);

        $empresa_modulo = EmpresaModulo::where('empresa_id',$request->empresa_id)
                                            ->where('modulo_id','3')
                                            ->where('estado','1')
                                            ->first();
        if($empresa_modulo != null){
            $datos_comprobante['comprobante_id'] = $comprobante->id;
            $comprobante_f = $comprobantef_controller->crearEncabezadoComprobante($datos_comprobante);
            $datos_balance_apertura['balance_apertura_id'] = $balance_apertura->id;
            $datos_balance_apertura['comprobante_id'] = $comprobante->id;
            $datos_balance_apertura['comprobantef_id'] = $comprobante_f->id;
            $balance_apertura_f = BalanceAperturaF::create($datos_balance_apertura);
        }

        return redirect()->route('balance.apertura.index',['empresa_id' => $request->empresa_id])->with('success_message', '[BALANCE DE APERTURA CREADO]');
    }

    public function show($producto_id)
    {
        dd($producto_id);
    }

    public function editarb1($comprobante_id)
    {
    }

    public function editarb2($comprobante_id)
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

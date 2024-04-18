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

class BalanceAperturaFController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'BALANCE DE APERTURA';
    const CREATE = 'REGISTRAR BALANCE';
    const EDITAR = 'MODIFICAR BALANCE';
    const MODIFICAR_COMPROBANTE = 'MODIFICAR COMPROBANTE';

    public function index($empresa_id)
    {
        $inicioMesFiscal = InicioMesFiscal::where('empresa_id',$empresa_id)->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. FALTA LA CONFIGURACION DE INICIO MES FISCAL]')->withInput();
        }
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $balances = BalanceAperturaF::query()
                                    ->byEmpresa($empresa_id)
                                    ->orderBy('id','desc')
                                    ->paginate(10);
        return view('balance_apertura_f.index', compact('icono','header','empresa','balances'));
    }

    public function search(Request $request)
    {
        dd("ok");
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        $inicioMesFiscal = InicioMesFiscal::select('inicio_gestion')->where('empresa_id',$empresa_id)->where('estado','1')->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('info_message', '[FALTA CONFIGURACION DE INICIO DE GESTION]')->withInput();
        }
        $anho = $inicioMesFiscal->inicio_gestion;
        for($i = $anho; $i <= $anho + 10; $i++){
            $anhos[$i] = $i;
        }
        return view('balance_apertura_f.create', compact('icono','header','empresa','anhos'));
    }

    public function store(Request $request)
    {dd($request->all());
        $request->validate([
            'anho' => 'required'
        ]);

        $inicioMesFiscal = InicioMesFiscal::where('empresa_id',$request->empresa_id)->first();
        if($inicioMesFiscal == null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. FALTA LA CONFIGURACION DE INICIO MES FISCAL]')->withInput();
        }

        $balanceApertura = BalanceAperturaF::where('gestion',$request->anho)->first();
        if($balanceApertura != null){
            return redirect()->back()->with('error_message','[ERROR EN OPERACION. YA EXISTE UN COMPROBANTE PARA EL BALANCE DE APERTURA EN LA GESTION SELECCIONADA]')->withInput();
        }

        $date = $request->anho . '-' . $inicioMesFiscal->mes . '-01';
        $tipo_cambio = TipoCambio::where('fecha',$date)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA ' . $date . ']')->withInput();
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
            'cliente_id' => $empresa->cliente_id,
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
            'cliente_id' => $empresa->cliente_id,
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

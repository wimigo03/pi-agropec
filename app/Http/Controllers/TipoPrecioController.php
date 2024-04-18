<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\TipoPrecio;
use App\Models\PrecioProducto;
use App\Models\User;
use App\Models\TipoCambio;

class TipoPrecioController extends Controller
{
    const ICONO = 'fa-solid fa-dollar fa-fw';
    const INDEX = 'TIPO DE PRECIOS';
    const CREATE = 'REGISTRAR TIPO DE PRECIO';
    const EDITAR = 'MODIFICAR TIPO DE PRECIO';

    /*public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('precio.productos.index',Auth::user()->empresa_id);
        }
        return view('precio_productos.indexAfter', compact('empresas'));
    }*/

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $estados = TipoPrecio::ESTADOS;
        $empresa = Empresa::find($empresa_id);
        $tipos_precio = TipoPrecio::query()
                            ->byEmpresa($empresa_id)
                            ->orderBy('id','desc')
                            ->paginate(10);
        return view('tipo_precios.index', compact('icono','header','estados','empresa','tipos_precio'));
    }

    public function search(Request $request)
    {
    }

    public function create($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($empresa_id);
        return view('tipo_precios.create', compact('icono','header','empresa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required',
            'nombre' => 'required|unique:tipo_precios,nombre,null,id,empresa_id,' . $request->empresa_id,
        ]);
        $date = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$date)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        try{

            $empresa = Empresa::find($request->empresa_id);
            $datos = [
                'empresa_id' => $empresa->id,
                'cliente_id' => $empresa->cliente_id,
                'nombre' => $request->nombre,
                'observaciones' => $request->observaciones,
                'estado' => '1'
            ];

            $tipo_precio = TipoPrecio::create($datos);

            $user = User::where('id',Auth::user()->id)->first();
            $productos = Producto::where('empresa_id',$empresa->id)->where('estado','1')->get();
            foreach ($productos as $producto) {
                $precio_base = PrecioProducto::select('precio')
                                                ->where('empresa_id',$empresa->id)
                                                ->where('producto_id',$producto->id)
                                                ->where('tipo_precio_id','1')
                                                ->where('estado','1')
                                                ->first();
                $datosPrecioProducto = [
                    'producto_id' => $producto->id,
                    'cliente_id' => $empresa->cliente_id,
                    'empresa_id' => $empresa->id,
                    'categoria_id' => $producto->categoria_id,
                    'categoria_master_id' => $producto->categoria_master_id,
                    'plan_cuenta_id' => $producto->plan_cuenta_id,
                    'unidad_id' => $producto->unidad_id,
                    'moneda_id' => $producto->moneda_id,
                    'pais_id' => $producto->pais_id,
                    'tipo_precio_id' => $tipo_precio->id,
                    'user_id' => $user->id,
                    'cargo_id' => $user->cargo_id,
                    'tipo_cambio' => $tipo_cambio != null ? $tipo_cambio->dolar_oficial : 0,
                    'porcentaje' => 0,
                    'precio' => $precio_base->precio,
                    'estado' => '1'
                ];

                $precio_producto = PrecioProducto::create($datosPrecioProducto);
            }

            return redirect()->route('tipo.precios.index', ['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó un [TIPO DE PRECIO] a los productos existentes.');
        } catch (ValidationException $e) {
            return redirect()->route('tipo.precios.create', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
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
        try{
            $tipo_precio = TipoPrecio::find($id);
            $tipo_precio->update([
                'estado' => '1'
            ]);

            return redirect()->route('tipo.precios.index',$tipo_precio->empresa_id)->with('info_message', 'Se Habilito el [TIPO DE PRECIO] seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('tipo.precios.index',$tipo_precio->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id)
    {
        try{
            $tipo_precio = TipoPrecio::find($id);
            $tipo_precio->update([
                'estado' => '2'
            ]);

            return redirect()->route('tipo.precios.index',$tipo_precio->empresa_id)->with('info_message', 'Se Deshabilito el [TIPO DE PRECIO] seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('tipo.precios.index',$tipo_precio->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

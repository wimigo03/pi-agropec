<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\TipoPrecio;
use App\Models\PrecioProducto;
use App\Models\Categoria;
use App\Models\TipoCambio;
use App\Models\User;

class PrecioProductoController extends Controller
{
    const ICONO = 'fa-solid fa-tag fa-fw';
    const INDEX = 'PRECIOS PRODUCTOS';
    const CREATE = 'REGISTRAR PRECIO VENTA';
    const EDITAR = 'MODIFICAR PRECIO VENTA';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('precio.productos.index',Auth::user()->empresa_id);
        }
        return view('precio_productos.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $fecha = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        $icono = self::ICONO;
        $header = self::INDEX;
        $tipo_precios = TipoPrecio::where('empresa_id',$empresa_id)->pluck('nombre','id');
        $categorias_master = Categoria::where('estado','1')->where('tipo','1')->pluck('nombre','id');
        $tipo_movimientos = PrecioProducto::TIPO_MOVIMIENTOS;
        $estados = PrecioProducto::ESTADOS;
        $empresa = Empresa::find($empresa_id);
        return view('precio_productos.index', compact('tipo_cambio','icono','header','estados','tipo_precios','categorias_master','tipo_movimientos','empresa'));
    }

    public function searchTipoBase(Request $request)
    {
        $fecha = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        $icono = self::ICONO;
        $header = self::INDEX;
        $tipo_precios = TipoPrecio::pluck('nombre','id');
        $categorias_master = Categoria::where('estado','1')->where('tipo','1')->pluck('nombre','id');
        $tipo_movimientos = PrecioProducto::TIPO_MOVIMIENTOS;
        $estados = PrecioProducto::ESTADOS;
        $empresa = Empresa::find($request->empresa_id);
        $precio_productos = PrecioProducto::query()
                        ->byEmpresa($empresa->id)
                        ->byTipoPrecio($request->tipo_precio_id)
                        ->byCategoriaMaster($request->categoria_master_id)
                        ->byCategoria($request->categoria_id)
                        ->byCodigo($request->codigo)
                        ->byProducto($request->producto)
                        ->byEstado(1)
                        ->orderBy('producto_id','desc')
                        ->get();
        return view('precio_productos.searchTipoBase', compact('tipo_cambio','icono','header','estados','tipo_precios','categorias_master','tipo_movimientos','empresa','precio_productos'));
    }

    public function searchTipo(Request $request)
    {
        $fecha = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$fecha)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        $icono = self::ICONO;
        $header = self::INDEX;
        $tipo_precios = TipoPrecio::pluck('nombre','id');
        $categorias_master = Categoria::where('estado','1')->where('tipo','1')->pluck('nombre','id');
        $tipo_movimientos = PrecioProducto::TIPO_MOVIMIENTOS;
        $estados = PrecioProducto::ESTADOS;
        $empresa = Empresa::find($request->empresa_id);
        $precio_productos = PrecioProducto::query()
                        ->byEmpresa($empresa->id)
                        ->byTipoPrecio($request->tipo_precio_id)
                        ->byCategoriaMaster($request->categoria_master_id)
                        ->byCategoria($request->categoria_id)
                        ->byCodigo($request->codigo)
                        ->byProducto($request->producto)
                        ->byEstado(1)
                        ->orderBy('producto_id','desc')
                        ->get();
        return view('precio_productos.searchTipo', compact('tipo_cambio','icono','header','estados','tipo_precios','categorias_master','tipo_movimientos','empresa','precio_productos'));
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

    public function getSubCategorias($empresa_id,$id){
        try{
            $subcategorias = Categoria::where('empresa_id',$empresa_id)
                                        ->where('parent_id', $id)
                                        ->where('estado','1')
                                        ->orderBy('id','asc')
                                        ->get()
                                        ->toJson();
            if($subcategorias){
                return response()->json([
                    'subcategorias' => $subcategorias
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create($empresa_id)
    {
    }

    public function store(Request $request)
    {
        try{
            ini_set('memory_limit','-1');
            ini_set('max_execution_time','-1');
            $empresa = Empresa::find($request->empresa_id);
            $user = User::where('id',Auth::user()->id)->first();
            $cont = 0;
            while($cont < count($request->precio_producto_id)){
                $precio_final = floatval(str_replace(",", "", $request->precio_final[$cont]));
                $porcentaje_detalle = $request->porcentaje_detalle[$cont] == null ? 0 : $request->porcentaje_detalle[$cont];
                $porcentaje_detalle = floatval(str_replace(",", "", $porcentaje_detalle));
                if($precio_final != 0){
                    $precio_producto = PrecioProducto::find($request->precio_producto_id[$cont]);
                    $precio_producto->update([
                        'estado' => '3'
                    ]);
                    $datosPrecioProducto = [
                        'producto_id' => $precio_producto->producto_id,
                        'empresa_id' => $precio_producto->empresa_id,
                        'cliente_id' => $precio_producto->cliente_id,
                        'categoria_id' => $precio_producto->categoria_id,
                        'categoria_master_id' => $precio_producto->categoria_master_id,
                        'plan_cuenta_id' => $precio_producto->plan_cuenta_id,
                        'unidad_id' => $precio_producto->unidad_id,
                        'moneda_id' => $precio_producto->moneda_id,
                        'pais_id' => $precio_producto->pais_id,
                        'tipo_precio_id' => $precio_producto->tipo_precio_id,
                        'user_id' => $user->id,
                        'cargo_id' => $user->cargo_id,
                        'tipo_cambio' => $request->tipo_cambio,
                        'porcentaje' => $porcentaje_detalle == 0 ? $precio_final : $porcentaje_detalle,
                        'precio' => $precio_final,
                        'estado' => '1'
                    ];

                    $precioProducto = PrecioProducto::create($datosPrecioProducto);

                    if($precio_producto->tipo_precio_id == 1){
                        $todos_los_precios = PrecioProducto::where('producto_id',$precio_producto->producto_id)
                                                            ->where('tipo_precio_id','!=',1)
                                                            ->where('estado','1')
                                                            ->get();
                        foreach($todos_los_precios as $data){
                            $precio_producto = PrecioProducto::find($data->id);
                            $datos_pproducto = [
                                'estado' => '3'
                            ];
                            $precio_producto->update($datos_pproducto);

                            $datos_precio_producto = [
                                'producto_id' => $precio_producto->producto_id,
                                'empresa_id' => $precio_producto->empresa_id,
                                'cliente_id' => $precio_producto->cliente_id,
                                'categoria_id' => $precio_producto->categoria_id,
                                'categoria_master_id' => $precio_producto->categoria_master_id,
                                'plan_cuenta_id' => $precio_producto->plan_cuenta_id,
                                'unidad_id' => $precio_producto->unidad_id,
                                'moneda_id' => $precio_producto->moneda_id,
                                'pais_id' => $precio_producto->pais_id,
                                'tipo_precio_id' => $precio_producto->tipo_precio_id,
                                'user_id' => $user->id,
                                'cargo_id' => $user->cargo_id,
                                'tipo_movimiento' => $request->tipo_movimiento,
                                'tipo_cambio' => $request->tipo_cambio,
                                'porcentaje' => $request->porcentaje_detalle[$cont],
                                'precio' => $precio_producto->precio + ($request->porcentaje_detalle[$cont] * $precio_producto->precio / 100),
                                'estado' => '1'
                            ];

                            $precioProductoUpdate = PrecioProducto::create($datos_precio_producto);
                        }
                    }
                }

                $cont++;
            }

            return redirect()->route('precio.productos.index', ['empresa_id' => $request->empresa_id])->with('success_message', '[PRECIOS ACTUALIZADOS]');
        } catch (ValidationException $e) {
            return redirect()->route('precio.productos.index', $request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }finally{
            ini_restore('memory_limit');
            ini_restore('max_execution_time');
        }
    }

    public function show($producto_id)
    {
        dd($producto_id);
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

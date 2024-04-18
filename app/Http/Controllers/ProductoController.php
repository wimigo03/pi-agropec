<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\PrecioProducto;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\Unidad;
use App\Models\User;
use App\Models\TipoCambio;
use App\Models\TipoPrecio;
use Auth;
use PDF;

class ProductoController extends Controller
{
    const ICONO = 'fas fa-wine-glass-alt fa-fw';
    const INDEX = 'PRODUCTOS';
    const CREATE = 'REGISTRAR PRODUCTO';
    const EDITAR = 'MODIFICAR PRODUCTO';
    const SHOW = 'DETALLE PRODUCTO';

    public function indexAfter()
    {
        $empresas = Empresa::query()
                            ->byCliente()
                            ->pluck('nombre_comercial','id');
        if(count($empresas) == 1 && Auth::user()->id != 1){
            return redirect()->route('productos.index',Auth::user()->empresa_id);
        }
        return view('productos.indexAfter', compact('empresas'));
    }

    public function index($empresa_id)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa = Empresa::find($empresa_id);
        $categorias_master = Categoria::where('empresa_id',$empresa_id)->where('nivel','0')->pluck('nombre','id');
        $categorias = Categoria::where('empresa_id',$empresa_id)->where('nivel','1')->pluck('nombre','id');
        $tipos = Categoria::TIPOS;
        $estados = Producto::ESTADOS;
        $productos = Producto::query()
                                ->byEmpresa($empresa_id)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('productos.index', compact('icono','header','empresa','categorias_master','categorias','tipos','estados','productos'));
    }

    public function search(Request $request)
    {
        $icono = self::ICONO;
        $header = self::INDEX;
        $empresa_id = $request->empresa_id;
        $empresa = Empresa::find($empresa_id);
        $categorias_master = Categoria::where('empresa_id',$empresa_id)->where('nivel','0')->pluck('nombre','id');
        $categorias = Categoria::where('empresa_id',$empresa_id)->where('nivel','1')->pluck('nombre','id');
        $tipos = Categoria::TIPOS;
        $estados = Producto::ESTADOS;
        $productos = Producto::query()
                                ->byEmpresa($empresa_id)
                                ->byProducto($request->producto_id)
                                ->byNombre($request->nombre)
                                ->byNombreFactura($request->nombre_factura)
                                ->byCodigo($request->codigo)
                                ->byUnidad($request->unidad_id)
                                ->byCategoriaMaster($request->categoria_master_id)
                                ->byCategoria($request->categoria_id)
                                ->byTipo($request->tipo)
                                ->byEstado($request->estado)
                                ->orderBy('id','desc')
                                ->paginate(10);
        return view('productos.index', compact('icono','header','empresa','categorias_master','categorias','tipos','estados','productos'));

    }

    public function create($id)
    {
        $icono = self::ICONO;
        $header = self::CREATE;
        $empresa = Empresa::find($id);
        $categorias_master = Categoria::where('empresa_id',$id)->where('tipo','1')->where('nivel',0)->where('estado','1')->pluck('nombre','id');
        $categorias = Categoria::where('empresa_id',$id)->where('tipo','1')->where('nivel',1)->where('estado','1')->pluck('nombre','id');
        $unidades = Unidad::where('empresa_id',$id)->where('estado','1')->pluck('nombre','id');
        $tipos = Unidad::TIPOS;
        return view('productos.create', compact('icono','header','empresa','categorias_master','categorias','unidades','tipos'));
    }

    public function getCodigoMaster($id)
    {
        $categoria_master = Categoria::find($id);
        if($categoria_master != null){
            $codigo = $categoria_master->codigo . '-';
            return response()->json([
                'codigo' => $codigo
            ]);
        }

        return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function getCodigo($id)
    {
        $categoria = Categoria::find($id);
        if($categoria != null){
            $categoria_master = Categoria::find($categoria->parent_id);
            if($categoria_master != null){
                $numeracion = Producto::where('categoria_id',$id)->get()->count() + 1;
                $numeracion = $numeracion != null ? str_pad($numeracion, 3, '0', STR_PAD_LEFT) : '001';
                $codigo = $categoria_master->codigo . '-' . $categoria->codigo . '-' . $numeracion;
                return response()->json([
                    'codigo' => $codigo,
                    'categoria_master' => $categoria_master->nombre,
                    'categoria_master_id' => $categoria_master->id
                ]);
            }
        }

        return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function getCodigoSinCategoria($id)
    {
        $categoria_master = Categoria::find($id);
        $numeracion = Producto::where('categoria_master_id',$id)->where('categoria_id',null)->get()->count() + 1;
        $numeracion = $numeracion != null ? str_pad($numeracion, 3, '0', STR_PAD_LEFT) : '001';
        $codigo = $categoria_master->codigo . '-00-' . $numeracion;
        return response()->json([
            'codigo' => $codigo,
            //'categoria_master' => $categoria_master->nombre,
            //'categoria_master_id' => $categoria_master->id
        ]);

        return response()->json(['error'=>'Algo Salio Mal']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_master_id' => 'required',
            //'categoria_id' => 'required',
            'unidad_id' => 'required',
            'nombre' => 'required|unique:productos,nombre,null,id,empresa_id,' . $request->empresa_id . ',categoria_master_id,' . $request->categoria_master_id,
            'nombre_factura' => 'required|unique:productos,nombre_factura,null,id,empresa_id,' . $request->empresa_id . ',categoria_master_id,' . $request->categoria_master_id,
            'codigo' => 'required',
            'detalle' => 'required',
            'foto_1' => 'nullable|file|mimes:png|max:2048',
            'foto_2' => 'nullable|file|mimes:png|max:2048',
            'foto_3' => 'nullable|file|mimes:png|max:2048',
        ]);
        $date = date('Y-m-d');
        $tipo_cambio = TipoCambio::where('fecha',$date)->first();
        if($tipo_cambio == null){
            return redirect()->back()->with('info_message', 'No existe un tipo de cambio para la [FECHA] seleccionada...')->withInput();
        }
        try{
            $foto_1 = isset($request->foto_1) ? 'prin_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_1->getClientOriginalName()), PATHINFO_EXTENSION) : null;
            $foto_2 = isset($request->foto_2) ? 'alt(1)_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_2->getClientOriginalName()), PATHINFO_EXTENSION) : null;
            $foto_3 = isset($request->foto_3) ? 'alt(2)_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_3->getClientOriginalName()), PATHINFO_EXTENSION) : null;

            $empresa = Empresa::find($request->empresa_id);
            if($request->categoria_id != null){
                $categoria = Categoria::find($request->categoria_id);
            }else{
                $categoria = Categoria::find($request->categoria_master_id);
            }
            $user = User::where('id',Auth::user()->id)->first();
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'categoria_master_id' => $request->categoria_master_id,
                'categoria_id' => $request->categoria_id,
                'plan_cuenta_id' => $categoria->plan_cuenta_id,
                'moneda_id' => $categoria->moneda_id,
                'pais_id' => $categoria->pais_id,
                'unidad_id' => $request->unidad_id,
                'nombre' => $request->nombre,
                'nombre_factura' => $request->nombre_factura,
                'detalle' => $request->detalle,
                'codigo' => $request->codigo,
                'estado' => '1'
            ];
            $producto = Producto::create($datos);

            $fotos = [
                'foto_1' => 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'. $foto_1,
                'foto_2' => 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'. $foto_2,
                'foto_3' => 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'. $foto_3,
            ];
            $producto->update($fotos);

            $foto_1 = isset($request->foto_1) ? $request->foto_1->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_1) : null;
            $foto_2 = isset($request->foto_2) ? $request->foto_2->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_2) : null;
            $foto_3 = isset($request->foto_3) ? $request->foto_3->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_3) : null;

            $tipos_precio = TipoPrecio::where('empresa_id',$request->empresa_id)->where('estado','1')->get();
            foreach($tipos_precio as $tipo_precio){
                $datosPrecioProducto = [
                    'producto_id' => $producto->id,
                    'empresa_id' => $empresa->id,
                    'cliente_id' => $empresa->cliente_id,
                    'categoria_id' => $producto->categoria_id,
                    'categoria_master_id' => $producto->categoria_master_id,
                    'plan_cuenta_id' => $producto->plan_cuenta_id,
                    'unidad_id' => $producto->unidad_id,
                    'moneda_id' => $producto->moneda_id,
                    'pais_id' => $producto->pais_id,
                    'tipo_precio_id' => $tipo_precio->id,
                    'user_id' => $user->id,
                    'cargo_id' => $user->cargo_id,
                    'tipo_movimiento' => '0',
                    'tipo_cambio' => $tipo_cambio != null ? $tipo_cambio->dolar_oficial : 0,
                    'porcentaje' => 0,
                    'precio' => 0,
                    'estado' => '1'
                ];

                $precio_producto = PrecioProducto::create($datosPrecioProducto);
            }

            return redirect()->route('productos.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se agregó un producto correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('productos.create',$request->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function editar($id)
    {
        $icono = self::ICONO;
        $header = self::EDITAR;
        $producto = Producto::find($id);
        $empresa = Empresa::find($producto->empresa_id);
        $categorias_master = Categoria::where('empresa_id',$producto->empresa_id)->where('tipo','1')->where('nivel',0)->where('estado','1')->get();
        $categorias = Categoria::where('empresa_id',$producto->empresa_id)->where('tipo','1')->where('nivel',1)->where('estado','1')->get();
        $unidades = Unidad::where('empresa_id',$producto->empresa_id)->where('estado','1')->get();
        return view('productos.editar', compact('icono','header','producto','empresa','categorias_master','categorias','unidades'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'categoria_master_id' => 'required',
            'categoria_id' => 'required',
            'nombre' => 'required|unique:productos,nombre,' . $request->producto_id . ',id,empresa_id,' . $request->empresa_id,
            'nombre_factura' => 'required|unique:productos,nombre_factura,' . $request->producto_id . ',id,empresa_id,' . $request->empresa_id,
            'codigo' => 'required',
            'detalle' => 'required',
            'foto_1' => 'nullable|file|mimes:png|max:2048',
            'foto_2' => 'nullable|file|mimes:png|max:2048',
            'foto_3' => 'nullable|file|mimes:png|max:2048',
        ]);
        try{
            $foto_1 = isset($request->foto_1) ? 'prin_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_1->getClientOriginalName()), PATHINFO_EXTENSION) : null;
            $foto_2 = isset($request->foto_2) ? 'alt(1)_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_2->getClientOriginalName()), PATHINFO_EXTENSION) : null;
            $foto_3 = isset($request->foto_3) ? 'alt(2)_' . strtolower($request->nombre) . '_' . strtolower($request->nombre_factura) . '.' . pathinfo(strtolower($request->foto_3->getClientOriginalName()), PATHINFO_EXTENSION) : null;

            $empresa = Empresa::find($request->empresa_id);
            $producto = Producto::find($request->producto_id);
            $categoria = Categoria::find($request->categoria_id);
            $datos = [
                'empresa_id' => $request->empresa_id,
                'cliente_id' => $empresa->cliente_id,
                'categoria_master_id' => $request->categoria_master_id,
                'categoria_id' => $request->categoria_id,
                'plan_cuenta_id' => $categoria->plan_cuenta_id,
                'moneda_id' => $categoria->moneda_id,
                'pais_id' => $categoria->pais_id,
                'unidad_id' => $request->unidad_id,
                'nombre' => $request->nombre,
                'nombre_factura' => $request->nombre_factura,
                'detalle' => $request->detalle,
                'codigo' => $request->codigo
            ];
            if (!empty($foto_1)) {
                $datos['foto_1'] = 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/' . $foto_1;
            }
            if (!empty($foto_2)) {
                $datos['foto_2'] = 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/' . $foto_2;
            }
            if (!empty($foto_3)) {
                $datos['foto_3'] = 'uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/' . $foto_3;
            }
            $producto->update($datos);

            $foto_1 = isset($request->foto_1) ? $request->foto_1->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_1) : null;
            $foto_2 = isset($request->foto_2) ? $request->foto_2->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_2) : null;
            $foto_3 = isset($request->foto_3) ? $request->foto_3->move(public_path('uploads/empresas/' . $empresa->id . '/img/productos/' . $producto->id . '/'), $foto_3) : null;

            return redirect()->route('productos.index',['empresa_id' => $request->empresa_id])->with('success_message', 'Se Modifico un producto correctamente.');
        } catch (ValidationException $e) {
            return redirect()->route('productos.editar',$request->producto_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function show($id)
    {
        $icono = self::ICONO;
        $header = self::SHOW;
        $producto = Producto::find($id);
        $empresa = Empresa::find($producto->empresa_id);
        return view('productos.show', compact('icono','header','empresa','producto'));
    }

    public function pdf($id)
    {
        $producto = Producto::find($id);
        $pdf = PDF::loadView('productos.pdf',compact(['producto']));
        $pdf->setPaper('LETTER', 'portrait');
        return $pdf->stream();
    }

    public function habilitar($id){
        try{
            $precio_producto = PrecioProducto::select('id')->where('producto_id',$id)->where('estado','2')->orderBy('id','desc')->first();
            $precio_producto = PrecioProducto::find($precio_producto->id);
            $precio_producto->update([
                'estado' => '1'
            ]);
            $producto = Producto::find($id);
            $producto->update([
                'estado' => '1'
            ]);
            return redirect()->route('productos.index',$producto->empresa_id)->with('info_message', 'Se Habilito el Producto seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('productos.index',$producto->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }

    public function deshabilitar($id){
        try{
            $precio_producto = PrecioProducto::select('id')->where('producto_id',$id)->where('estado','1')->orderBy('id','desc')->first();
            $precio_producto = PrecioProducto::find($precio_producto->id);
            $precio_producto->update([
                'estado' => '2'
            ]);
            $producto = Producto::find($id);
            $producto->update([
                'estado' => '2'
            ]);
            return redirect()->route('productos.index',$producto->empresa_id)->with('info_message', 'Se Deshabilito el Producto seleccionado...');
        } catch (ValidationException $e) {
            return redirect()->route('productos.index',$producto->empresa_id)
                ->withErrors($e->validator->errors())
                ->withInput();
        }
    }
}

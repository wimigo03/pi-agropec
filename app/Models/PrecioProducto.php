<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Categoria;
use App\Models\PlanCuenta;
use App\Models\Unidad;
use App\Models\Moneda;
use App\Models\Paises;
use App\Models\TipoPrecio;
use App\Models\User;
use App\Models\Cargo;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $table = 'precio_productos';
    protected $fillable = [
        'producto_id',
        'empresa_id',
        'pi_cliente_id',
        'categoria_id',
        'categoria_master_id',
        'plan_cuenta_id',
        'unidad_id',
        'moneda_id',
        'pais_id',
        'tipo_precio_id',
        'user_id',
        'cargo_id',
        'tipo_cambio',
        'porcentaje',
        'precio',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO',
        '3' => 'MODIFICADO'
    ];

    const TIPO_MOVIMIENTOS = [
        '1' => 'SUBIR',
        '2' => 'BAJAR'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
            case '3':
                return "MODIFICADO";
        }
    }

    public function getPrecioAnteriorBaseAttribute(){
        $producto_base = PrecioProducto::select('precio')
                                        ->where('producto_id',$this->producto_id)
                                        ->where('tipo_precio_id',1)
                                        ->where('estado','3')
                                        ->orderBy('id','desc')
                                        ->first();
        if($producto_base != null){
            return $producto_base->precio;
        }else{
            return 0;
        }
    }

    public function getPrecioBaseAttribute(){
        $producto_base = PrecioProducto::select('precio')
                                        ->where('producto_id',$this->producto_id)
                                        ->where('tipo_precio_id',1)
                                        ->where('estado','1')
                                        ->orderBy('id','desc')
                                        ->first();
        if($producto_base != null){
            return $producto_base->precio;
        }else{
            return 0;
        }
    }

    public function producto(){
        return $this->belongsTo(Producto::class,'producto_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class,'categoria_id','id');
    }

    public function categoria_master(){
        return $this->belongsTo(Categoria::class,'categoria_master_id','id');
    }

    public function plan_cuenta(){
        return $this->belongsTo(PlanCuenta::class,'zona_id','id');
    }

    public function unidad(){
        return $this->belongsTo(Unidad::class,'unidad_id','id');
    }

    public function moneda(){
        return $this->belongsTo(Moneda::class,'moneda_id','id');
    }

    public function pais(){
        return $this->belongsTo(Paises::class,'pais_id','id');
    }

    public function tipo_p(){
        return $this->belongsTo(TipoPrecio::class,'tipo_precio_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('precio_productos.empresa_id', $empresa_id);
        }
    }

    public function scopeByTipoPrecio($query, $tipo_precio_id){
        if($tipo_precio_id){
            return $query->where('tipo_precio_id', $tipo_precio_id);
        }
    }

    public function scopeByCategoriaMaster($query, $categoria_master_id){
        if($categoria_master_id){
            return $query->where('categoria_master_id', $categoria_master_id);
        }
    }

    public function scopeByCategoria($query, $categoria_id){
        if($categoria_id){
            return $query->where('categoria_id', $categoria_id);
        }
    }

    public function scopeByCodigo($query, $codigo){
        if ($codigo) {
                return $query
                    ->whereIn('producto_id', function ($subquery) use($codigo) {
                        $subquery->select('id')
                            ->from('productos')
                            ->where('codigo','LIKE','%'.$codigo.'%')
                            ->where('estado','1');
                    });
        }
    }

    public function scopeByProducto($query, $producto){
        if ($producto) {
                return $query
                    ->whereIn('producto_id', function ($subquery) use($producto) {
                        $subquery->select('id')
                            ->from('productos')
                            ->where('nombre','LIKE','%'.$producto.'%')
                            ->where('estado','1');
                    });
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}

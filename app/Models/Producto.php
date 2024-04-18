<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\PlanCuenta;
use App\Models\Unidad;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'categoria_master_id',
        'categoria_id',
        'plan_cuenta_id',
        'moneda_id',
        'pais_id',
        'unidad_id',
        'nombre',
        'nombre_factura',
        'detalle',
        'codigo',
        'foto_1',
        'foto_2',
        'foto_3',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1': 
                return "H";
            case '2': 
                return "D";
        }
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function categoria_m(){
        return $this->belongsTo(Categoria::class,'categoria_master_id','id');
    }

    public function categoria(){
        return $this->belongsTo(Categoria::class,'categoria_id','id');
    }

    public function plan_cuenta(){
        return $this->belongsTo(PlanCuenta::class,'plan_cuenta_id','id');
    }

    public function unidad(){
        return $this->belongsTo(Unidad::class,'unidad_id','id');
    }

    public function getCategoriaMasterAttribute() {
        $categoria_master = Categoria::select('nombre')->where('id',$this->categoria_master_id)->first();
        if($categoria_master != null){
            return $categoria_master->nombre;
        }
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id)  
            return $query->where('empresa_id', $empresa_id);
    }

    public function scopeByProducto($query, $producto_id){
        if($producto_id)  
            return $query->where('id', $producto_id);
    }

    public function scopeByNombre($query, $nombre){
        if($nombre)  
            return $query->where('nombre', 'like','%'.$nombre.'%');
    }

    public function scopeByNombreFactura($query, $nombre_factura){
        if($nombre_factura)  
            return $query->where('nombre_factura', 'like','%'.$nombre_factura.'%');
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo)  
            return $query->where('codigo', 'like','%'.$codigo.'%');
    }

    public function scopeByUnidad($query, $unidad){
        if ($unidad) {
                return $query
                    ->whereIn('unidad_id', function ($subquery) use($unidad) {
                        $subquery->select('id')
                            ->from('unidades')
                            ->where('nombre','like','%'.$unidad.'%');
                    });
        }
    }

    public function scopeByCategoriaMaster($query, $categoria_master_id){
        if ($categoria_master_id) {
            return $query->where('categoria_master_id', $categoria_master_id);
        }
    }

    public function scopeByCategoria($query, $categoria_id){
        if($categoria_id){
            return $query->where('categoria_id', $categoria_id);
        }
    }

    public function scopeByTipo($query, $tipo){
        if ($tipo) {
                return $query
                    ->whereIn('categoria_id', function ($subquery) use($tipo) {
                        $subquery->select('id')
                            ->from('categorias')
                            ->where('tipo',$tipo);
                    });
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado)  
            return $query->where('estado', $estado);
    }
}

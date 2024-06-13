<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\Centro;

class SubCentro extends Model
{
    use HasFactory;

    protected $table = 'sub_centros';
    protected $fillable = [
        'centro_id',
        'empresa_id',
        'pi_cliente_id',
        'nombre',
        'abreviatura',
        '_create',
        'tipo',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'VIGENTE',
        '2' => 'DESHABILITADO',
        '3' => 'ELIMINADO'
    ];

    const TIPOS = [
        '1' => 'MANUAL',
        '2' => 'AUTOMATICO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "VIGENTE";
            case '2':
                return "DESHABILITADO";
            case '3':
                return "ELIMINADO";
        }
    }

    public function getTiposAttribute(){
        switch ($this->tipo) {
            case '1':
                return "MANUAL";
            case '2':
                return "AUTOMATICO";
        }
    }

    public function getcolorStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "badge-with-padding badge badge-success";
            case '2':
                return "badge-with-padding badge badge-secondary";
            case '3':
                return "badge-with-padding badge badge-danger";
        }
    }

    public function centro(){
        return $this->belongsTo(Centro::class,'centro_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function scopeByPiCliente($query, $pi_cliente_id){
        if($pi_cliente_id){
            return $query->where('pi_cliente_id', $pi_cliente_id);
        }
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeByCentro($query, $centro_id){
        if($centro_id){
            return $query->where('centro_id', $centro_id);
        }
    }

    public function scopeByCentroText($query, $centro){
        if ($centro) {
                return $query
                    ->whereIn('centro_id', function ($subquery) use($centro) {
                        $subquery->select('id')
                            ->from('centros')
                            ->where('nombre',$centro);
                    });
        }
    }

    public function scopeBySubCentroText($query, $subcentro){
        if ($subcentro) {
                return $query
                    ->whereIn('id', function ($subquery) use($subcentro) {
                        $subquery->select('id')
                            ->from('sub_centros')
                            ->where('nombre',$subcentro);
                    });
        }
    }

    public function scopeByCodigo($query, $codigo){
        if($codigo){
            return $query->where('abreviatura', 'like', '%' . $codigo . '%');
        }
    }

    public function scopeByTipo($query, $tipo){
        if($tipo){
            return $query->where('tipo', $tipo);
        }
    }

    public function scopeByCreacion($query, $fecha){
        if($fecha){
            $fecha = date('Y-m-d', strtotime(str_replace('/', '-', $fecha)));
            return $query->where('create', $fecha);
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado){
            return $query->where('estado', $estado);
        }
    }
}

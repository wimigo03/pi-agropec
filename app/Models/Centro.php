<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\SubCentro;

class Centro extends Model
{
    use HasFactory;

    protected $table = 'centros';
    protected $fillable = [
        'empresa_id',
        'pi_cliente_id',
        'nombre',
        'abreviatura',
        '_create',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
        }
    }

    public function subcentros()
    {
        return $this->hasMany(SubCentro::class);
    }

    public function getcolorStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "badge-with-padding badge badge-success";
            case '2':
                return "badge-with-padding badge badge-danger";
        }
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
        if($empresa_id)
            return $query->where('empresa_id', $empresa_id);
    }

    public function scopeByEstado($query, $estado){
        if($estado)
            return $query->where('estado', $estado);
    }
}

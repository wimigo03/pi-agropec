<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa;
use App\Models\Cliente;
use App\Models\Modulo;

class EmpresaModulo extends Model
{
    use HasFactory;

    protected $table = 'empresa_modulos';
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'modulo_id',
        'fecha_registro',
        'estado',
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO',
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
        }
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function modulo(){
        return $this->belongsTo(Modulo::class,'modulo_id','id');
    }
}

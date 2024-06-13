<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'horarios_detalle';
    protected $fillable = [
        'horario_id',
        'empresa_id',
        'pi_cliente_id',
        'dia',
        'entrada_1',
        'salida_1',
        'entrada_2',
        'salida_2',
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
}

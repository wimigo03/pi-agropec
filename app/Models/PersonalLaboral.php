<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;
use App\Models\User;
use App\Models\Cargo;
use App\Models\Empresa;
use App\Models\PiCliente;
use App\Models\PersonalContrato;

class PersonalLaboral extends Model
{
    use HasFactory;

    protected $table = 'personal_laboral';
    protected $fillable = [
        'personal_id',
        'user_id',
        'cargo_id',
        'empresa_id',
        'pi_cliente_id',
        'plan_cuenta_id',
        'horario_id',
        'codigo_ingreso',
        'biometrico_id',
        'tipo_contrato',
        'fecha_contrato_fijo',
        'profesion_ocupacion',
        'banco',
        'nro_cuenta',
        'file_contrato',
        'estado'
    ];

    const ESTADOS = [
        '1' => 'HABILITADO',
        '2' => 'NO HABILITADO',
        '3' => 'RETIRADO'
    ];

    public function getStatusAttribute(){
        switch ($this->estado) {
            case '1':
                return "HABILITADO";
            case '2':
                return "NO HABILITADO";
            case '3':
                return "RETIRADO";
        }
    }

    public function personal(){
        return $this->belongsTo(Personal::class,'personal_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function cargo(){
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function empresa(){
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }

    public function cliente(){
        return $this->belongsTo(PiCliente::class,'pi_cliente_id','id');
    }

    public function getContratoFiscalAttribute() {
        $sw = false;
        $contrato = PersonalContrato::where('personal_id',$this->personal_id)->where('codigo_retiro',null)->where('tipo','F')->first();
        if($contrato != null){
            $sw = true;
        }
        return $sw;
    }

    public function getContratoInternoAttribute() {
        $sw = false;
        $contrato = PersonalContrato::where('personal_id',$this->personal_id)->where('codigo_retiro',null)->where('tipo','I')->first();
        if($contrato != null){
            $sw = true;
        }
        return $sw;
    }

    public function getContratoServicioAttribute() {
        $sw = false;
        $contrato = PersonalContrato::where('personal_id',$this->personal_id)->where('codigo_retiro',null)->where('tipo','S')->first();
        if($contrato != null){
            $sw = true;
        }
        return $sw;
    }

    public function getTotalHaberBasicoAttribute() {
        $contratos = PersonalContrato::where('personal_laboral_id',$this->id)->where('estado','1')->get();
        $total_haber_basico = 0;
        if($contratos != null){
            foreach($contratos as $datos){
                $total_haber_basico = $total_haber_basico + $datos->sueldo;
            }
        }
        return $total_haber_basico;
    }

    public function getTotalBonoAttribute() {
        $contratos = PersonalContrato::where('personal_laboral_id',$this->id)->where('estado','1')->get();
        $total_bono = 0;
        if($contratos != null){
            foreach($contratos as $datos){
                $total_bono = $total_bono + $datos->bono;
            }
        }
        return $total_bono;
    }

    public function getTotalGanadoAttribute() {
        $total_ganado = $this->total_haber_basico + $this->total_bono;
        return $total_ganado;
    }

    public function scopeByEmpresa($query, $empresa_id){
        if($empresa_id){
            return $query->where('empresa_id', $empresa_id);
        }
    }

    public function scopeByCodigoIngreso($query, $codigo_ingreso){
        if($codigo_ingreso){
            return $query->where('codigo_ingreso', $codigo_ingreso);
        }
    }

    public function scopeByCodigoRetiro($query, $codigo_retiro){
        if ($codigo_retiro) {
                return $query
                    ->whereIn('personal_id', function ($subquery) use($codigo_retiro) {
                        $subquery->select('personal_id')
                            ->from('personal_contratos')
                            ->where('codigo_retiro',$codigo_retiro);
                    });
        }
    }

    public function scopeByNroCarnet($query, $nro_carnet){
        if ($nro_carnet) {
                return $query
                    ->whereIn('personal_id', function ($subquery) use($nro_carnet) {
                        $subquery->select('id')
                            ->from('personal')
                            ->where('ci_run',$nro_carnet);
                    });
        }
    }

    public function scopeByPrimerNombre($query, $primer_nombre){
        if ($primer_nombre) {
                return $query
                    ->whereIn('personal_id', function ($subquery) use($primer_nombre) {
                        $subquery->select('id')
                            ->from('personal')
                            ->where('primer_nombre', 'like', '%'.$primer_nombre.'%');
                    });
        }
    }

    public function scopeByApellidoPaterno($query, $apellido_paterno){
        if ($apellido_paterno) {
                return $query
                    ->whereIn('personal_id', function ($subquery) use($apellido_paterno) {
                        $subquery->select('id')
                            ->from('personal')
                            ->where('apellido_paterno', 'like', '%'.$apellido_paterno.'%');
                    });
        }
    }

    public function scopeByApellidoMaterno($query, $apellido_materno){
        if ($apellido_materno) {
                return $query
                    ->whereIn('personal_id', function ($subquery) use($apellido_materno) {
                        $subquery->select('id')
                            ->from('personal')
                            ->where('apellido_materno', 'like', '%'.$apellido_materno.'%');
                    });
        }
    }

    public function scopeByCargo($query, $cargo_id){
        if($cargo_id){
            return $query->where('cargo_id', $cargo_id);
        }
    }

    public function scopeByContrato($query, $file_contrato){
        if($file_contrato == '1'){
            return $query->where('file_contrato', '!=', null);
        }else{
            return $query->where('file_contrato', null);
        }
    }

    public function scopeByEstado($query, $estado){
        if($estado)
            return $query->where('estado', $estado);
    }
}

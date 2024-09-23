<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePlanilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'planilla_id', 'salario_base','bonificacion', 'comisiones', 'cant_hora_extra', 'monto_hora_extra',
        'adelantos', 'prestamos', 'asociacion', 'total', 'observaciones',
        'deudas', 'rebajados','total_ccss','vacaciones','hora_extra','employee_id','aguinaldo'
    ];
}

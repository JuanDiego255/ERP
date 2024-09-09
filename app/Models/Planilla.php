<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'estado','fecha_desde','fecha_hasta','descripcion','business_id','tipo_planilla_id','generada','aprobada'
    ];
}

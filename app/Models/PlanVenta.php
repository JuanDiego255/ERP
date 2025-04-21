<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanVenta extends Model
{
    protected $fillable = [
        'numero',
        'vehiculo_venta_id',
        'vehiculo_recibido_id',
        'vehiculo_recibido_id_dos',
        'cliente_id',
        'business_id',
        'fiador_id',
        'vendedor_id',
        'fecha_plan',
        'tipo_plan',
        'gastos_plan',
        'desc_forma_pago',
        'total_recibido',
        'total_financiado',
        'monto_recibo',
        'monto_efectivo',
        'venta_sin_rebajos'
    ];
}

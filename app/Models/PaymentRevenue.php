<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRevenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'revenue_id', 'cuota', 'monto_general', 'monto_interes',
        'paga', 'interes_c', 'amortiza', 'estado', 'detalle','referencia','cheque','created_at','fecha_interes'
    ];
}

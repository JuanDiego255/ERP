<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_compra', 'descripcion','monto', 'factura', 'product_id', 'proveedor_id',
        'business_id','is_cxp','created_by'
    ];
}

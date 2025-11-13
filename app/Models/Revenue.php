<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = [
        'referencia', 'detalle', 'business_id', 'valor_total',
        'status', 'created_by', 'expense_category_id', 'location_id', 'contact_id','tasa','cuota','tipo_prestamo','moneda','plan_venta_id','plazo','sucursal','check_sms'
    ];

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function category(){
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function boleto(){
        return $this->hasOne('App\Models\Boleto', 'revenue_id', 'id');
    }
}

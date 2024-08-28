<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeHolidays extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_desde', 'fecha_hasta','cantidad', 'employee_id', 'observacion', 'estado'
    ];
}

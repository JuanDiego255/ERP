<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRubros extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo', 'valor','business_id', 'employee_id', 'rubro_id', 'status'
    ];
}

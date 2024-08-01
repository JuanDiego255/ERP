<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubrosPlanilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category','tipo', 'tipo_calculo', 'alias', 'status',
        'business_id'
    ];
}

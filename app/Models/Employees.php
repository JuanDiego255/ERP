<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'telephone','status', 'celular', 'email', 'salario_base',
        'asociacion', 'ccss', 'tipo_pago', 'moneda_pago', 'salario_hora',
        'puesto', 'comision_ventas','business_id'
    ];


    /**
     * Return list of users dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     * @param $include_commission_agents = false (boolean)
     *
     * @return array users
     */
     public static function forDropdown($business_id, $prepend_none = true, $include_commission_agents = false, $prepend_all = false)
    {
        $all_employees = Employees::where('business_id', $business_id)
        ->select('id', 'name')->get();
        $employees = $all_employees->pluck('name', 'id');

        //Prepend none
        if ($prepend_none) {
            $employees = $employees->prepend(__('lang_v1.none'), '');
        }

        //Prepend all
        if ($prepend_all) {
            $employees = $employees->prepend(__('lang_v1.all'), '');
        }
        
        return $employees;
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanillaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'generada' => 'required|string'        
        ];
    }
}

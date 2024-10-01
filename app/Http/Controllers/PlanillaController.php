<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanillaRequest;
use App\Models\DetallePlanilla;
use App\Models\Employees;
use App\Models\Planilla;
use App\Models\TipoPlanilla;
use App\Notifications\CustomerNotification;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class PlanillaController extends Controller
{
    //Metodos para planillas
    /**

     * Get all the vehicles bills.

     *

     * @param Request $request


     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        $planillas = Planilla::where('planillas.business_id', $business_id)
            ->join('tipo_planillas', 'planillas.tipo_planilla_id', '=', 'tipo_planillas.id')
            ->select([
                'tipo_planillas.tipo as tipo',
                'planillas.id as planilla_id',
                'planillas.fecha_desde as fecha_desde',
                'planillas.fecha_hasta as fecha_hasta',
                'planillas.descripcion as descripcion',
                'planillas.estado as estado',
                'planillas.generada as generada',
                'planillas.aprobada as aprobada',
                'planillas.created_at as created_at',
            ])
            ->orderBy('planillas.created_at', 'desc');
        if (request()->ajax()) {

            return Datatables::of($planillas)
                ->addColumn(
                    'action',
                    '
                  
                    @if($generada != 1)
                        @can("planilla.create")
                            <button data-href="{{ action(\'PlanillaController@createPlanillaDetalle\', [$planilla_id]) }}" class="btn btn-xs btn-primary generar_planilla_detalle"><i class="glyphicon glyphicon-edit"></i> @lang("Generar planilla")</button>
                        @endcan
                    @else
                        <a href="{{ action(\'PlanillaController@indexDetallePlanilla\', [$planilla_id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-eye-open"></i>@if($aprobada == 1) @lang("Ver planilla") @else @lang("Gestionar planilla") @endif</a>
                    @endif
                    &nbsp;
                  
                        
                    @can("planilla.delete")
                        <button data-href="{{ action(\'PlanillaController@destroy\', [$planilla_id]) }}" class="btn btn-xs btn-danger delete_planilla_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                )
                ->editColumn('fecha_desde', '{{ @format_date($fecha_desde) }}')
                ->editColumn('fecha_hasta', '{{ @format_date($fecha_hasta) }}')
                ->editColumn('estado', function ($data) {
                    switch ($data->estado) {
                        case "1":
                            $tipo = "Activo";
                            break;
                        case "2":
                            $tipo = "Aprobada";
                            break;
                    }
                    return $tipo;
                })
                ->editColumn('generada', function ($data) {
                    switch ($data->generada) {
                        case "1":
                            $generada = "Generada";
                            break;
                        case "0":
                            $generada = "Pendiente";
                            break;
                    }
                    return $generada;
                })
                ->editColumn('aprobada', function ($data) {
                    switch ($data->aprobada) {
                        case "1":
                            $aprobada = "Aprobada";
                            break;
                        case "0":
                            $aprobada = "Pendiente";
                            break;
                    }
                    return $aprobada;
                })
                ->rawColumns(['action', 'descripcion'])
                ->make(true);
        }

        return view('admin.planillas.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = request()->session()->get('user.business_id');
        $tipo_planillas = TipoPlanilla::where('business_id', $business_id)->get()->pluck('tipo', 'id');
        return view('admin.planillas.create', compact('tipo_planillas'));
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        try {
            $planilla = $request->only([
                'fecha_desde',
                'fecha_hasta',
                'descripcion',
                'tipo_planilla_id'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $planilla['business_id'] = $business_id;
            $planilla['estado'] = 1;
            $planilla['generada'] = 0;
            $planilla['aprobada'] = 0;

            Planilla::create($planilla);

            $output = [
                'success' => 1,
                'msg' => __("Se agregó la planilla con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('planilla-index/')->with('status', $output);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $planilla = Planilla::where('business_id', $business_id)
            ->findOrFail($id);

        return view('admin.planillas.edit')
            ->with(compact('planilla'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        try {
            $planilla = $request->only([
                'generada'
            ]);
            $planilla_get = Planilla::findOrFail($id);

            $planilla_get->update($planilla);
            $output = "Planilla actualizada con éxito";
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = $e->getMessage() . " " . $e->getLine();
        }

        return $output;
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');
                $planilla = Planilla::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $planilla->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Planilla eliminada con éxito")
                ];
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function createPlanillaDetalle($id)
    {
        if (request()->ajax()) {
            try {
                DB::beginTransaction();
                $requestData = new PlanillaRequest([
                    'generada' => 1
                ]);
                $output = $this->update($requestData, $id);
                $employees = Employees::where('status', 1)->get();
                foreach ($employees as $employee) {
                    $total = $employee->salario_base - $employee->ccss;
                    $detalle_planilla['planilla_id'] = $id;
                    $detalle_planilla['employee_id'] = $employee->id;
                    $detalle_planilla['salario_base'] = $employee->salario_base;
                    $detalle_planilla['bonificacion'] = 0;
                    $detalle_planilla['comisiones'] = 0;
                    $detalle_planilla['cant_hora_extra'] = 0;
                    $detalle_planilla['monto_hora_extra'] = 0;
                    $detalle_planilla['adelantos'] = 0;
                    $detalle_planilla['prestamos'] = 0;
                    $detalle_planilla['asociacion'] = 0;
                    $detalle_planilla['total'] = 0;
                    $detalle_planilla['observaciones'] = "";
                    $detalle_planilla['deudas'] = 0;
                    $detalle_planilla['rebajados'] = 0;
                    $detalle_planilla['total_ccss'] = $employee->ccss;
                    $detalle_planilla['vacaciones'] = 0;
                    $detalle_planilla['hora_extra'] = $employee->hora_extra;
                    $detalle_planilla['total'] = $total;
                    DetallePlanilla::create($detalle_planilla);
                };

                DB::commit();
                $output = [
                    'success' => true,
                    'planilla_id' => $id,
                    'msg' => __("Planilla generada con éxito")
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => $e->getMessage()
                ];
            }

            return $output;
        }
    }
    //Fin metodos para planillas
    //Metodos para los tipo de planillas
    /**

     * Get all the vehicles bills.

     *

     * @param Request $request


     */
    public function indexTipoPlanilla()
    {
        $business_id = request()->session()->get('user.business_id');
        $tipo_planilla = TipoPlanilla::where('tipo_planillas.business_id', $business_id)
            ->select([
                'tipo_planillas.id as id',
                'tipo_planillas.tipo as tipo'
            ]);
        if (request()->ajax()) {

            return Datatables::of($tipo_planilla)
                ->addColumn(
                    'action',
                    '@can("employee.update")
                <a href="{{ action(\'PlanillaController@editTipoPlanilla\', [$id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                &nbsp;
                @endcan
                
                @can("employee.delete")
                                                                    <button data-href="{{ action(\'PlanillaController@destroyTipoPlanilla\', [$id]) }}" class="btn btn-xs btn-danger delete_tipo_planilla_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan'
                )
                ->rawColumns(['action', 'tipo'])
                ->removeColumn('id')
                ->make(true);
        }

        return view('admin.planillas.tipo_planillas.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTipoPlanilla()
    {
        return view('admin.planillas.tipo_planillas.create');
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function storeTipoPlanilla(Request $request)
    {
        try {
            $tipo_planilla = $request->only([
                'tipo'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $tipo_planilla['business_id'] = $business_id;

            TipoPlanilla::create($tipo_planilla);

            $output = [
                'success' => 1,
                'msg' => __("Se agregó el tipo de planilla con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('tipo-planilla-index/')->with('status', $output);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editTipoPlanilla($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $tipo = TipoPlanilla::where('business_id', $business_id)
            ->findOrFail($id);

        return view('admin.planillas.tipo_planillas.edit')
            ->with(compact('tipo'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateTipoPlanilla(Request $request, $id)
    {
        try {
            $tipo_planilla = $request->only([
                'tipo'
            ]);

            $business_id = $request->session()->get('user.business_id');
            $tipo_planilla['business_id'] = $business_id;
            $tipo_planilla_get = TipoPlanilla::where('business_id', $business_id)
                ->findOrFail($id);

            $tipo_planilla_get->update($tipo_planilla);
            $output = [
                'success' => 1,
                'msg' => __("Tipo planilla actualizado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('tipo-planilla-index/')->with('status', $output);
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroyTipoPlanilla($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');
                $tipo_planilla = TipoPlanilla::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $tipo_planilla->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Tipo de planilla eliminado con éxito")
                ];
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
    //Fin Metodos para los tipo de planillas
    //Metodos para detalle planillas
    /**

     * Get all the vehicles bills.

     *

     * @param Request $request


     */
    public function indexDetallePlanilla($id)
    {
        $canUpdate = auth()->user()->can('planilla.update');
        $planilla = Planilla::where('id', $id)->first();
        $detalles_planillas = DetallePlanilla::where('detalle_planillas.planilla_id', $id)
            ->join('employees', 'detalle_planillas.employee_id', 'employees.id')
            ->join('planillas', 'detalle_planillas.planilla_id', 'planillas.id')
            ->select([
                'detalle_planillas.id as id',
                'detalle_planillas.salario_base as salario_base',
                'detalle_planillas.bonificacion as bonificacion',
                'detalle_planillas.comisiones as comisiones',
                'detalle_planillas.cant_hora_extra as cant_hora_extra',
                'detalle_planillas.monto_hora_extra as monto_hora_extra',
                'detalle_planillas.adelantos as adelantos',
                'detalle_planillas.prestamos as prestamos',
                'detalle_planillas.aguinaldo as aguinaldo',
                'detalle_planillas.asociacion as asociacion',
                'detalle_planillas.total as total',
                'detalle_planillas.observaciones as observaciones',
                'detalle_planillas.deudas as deudas',
                'detalle_planillas.rebajados as rebajados',
                'detalle_planillas.total_ccss as total_ccss',
                'detalle_planillas.hora_extra as hora_extra',
                'employees.name as name',
                'employees.id as employee_id',
                'planillas.aprobada as aprobada'
            ]);

        if (request()->ajax()) {
            return Datatables::of($detalles_planillas)
                ->addColumn(
                    'action',
                    '<a href="{{ action(\'PlanillaController@viewPayment\', [$id]) }}" class="btn btn-xs btn-info view-planilla"><i class="glyphicon glyphicon-print"></i></a>'
                )
                ->editColumn(
                    'total_ccss',
                    '@can("planilla.update")
        {!! Form::text("total_ccss", number_format($total_ccss, 2, ".", ","), array_merge(["class" => "form-control number"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::text("total_ccss", number_format($total_ccss, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                )
                ->editColumn(
                    'hora_extra',
                    '
        {!! Form::number("hora_extra", $hora_extra, array_merge(["class" => "form-control hora_extra"],  ["readonly"])) !!}
       '
                )
                ->editColumn(
                    'monto_hora_extra',
                    '
        {!! Form::text("monto_hora_extra", number_format($monto_hora_extra, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
       '
                )/* 
                ->editColumn(
                    'rebajados',
                    '@can("planilla.update")
        {!! Form::number("rebajados", $rebajados, array_merge(["class" => "form-control"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::number("rebajados", $rebajados, array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                ) *//* 
                ->editColumn(
                    'deudas',
                    '@can("planilla.update")
        {!! Form::number("deudas", $deudas, array_merge(["class" => "form-control"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::number("deudas", $deudas, array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                ) *//* 
                ->editColumn(
                    'adelantos',
                    '@can("planilla.update")
        {!! Form::number("adelantos", $adelantos, array_merge(["class" => "form-control"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::number("adelantos", $adelantos, array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                ) */
                ->editColumn(
                    'salario_base',
                    '@can("planilla.update")
        {!! Form::text("salario_base", number_format($salario_base, 2, ".", ","), array_merge(["class" => "form-control number"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::text("salario_base", number_format($salario_base, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                )
                ->editColumn(
                    'bonificacion',
                    '@can("planilla.update")
        {!! Form::text("bonificacion", number_format($bonificacion, 2, ".", ","), array_merge(["class" => "form-control number"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::text("bonificacion", number_format($bonificacion, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                )/* 
                ->editColumn(
                    'comisiones',
                    '@can("planilla.update")
        {!! Form::number("comisiones", $comisiones, array_merge(["class" => "form-control"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::number("comisiones", $comisiones, array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                ) */
                ->editColumn(
                    'cant_hora_extra',
                    '@can("planilla.update")
        {!! Form::text("cant_hora_extra", $cant_hora_extra, array_merge(["class" => "form-control cant_hora_extra"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::text("cant_hora_extra", $cant_hora_extra, array_merge(["class" => "form-control cant_hora_extra"], ["readonly"])) !!}
        @endcan'
                )
                ->editColumn(
                    'prestamos',
                    '@can("planilla.update")
        {!! Form::text("prestamos", number_format($prestamos, 2, ".", ","), array_merge(["class" => "form-control number"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::text("prestamos", number_format($prestamos, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                )/* 
                ->editColumn(
                    'asociacion',
                    '@can("planilla.update")
        {!! Form::number("asociacion", $asociacion, array_merge(["class" => "form-control"], $aprobada == 1 ? ["readonly"] : [])) !!}
        @else
        {!! Form::number("asociacion", $asociacion, array_merge(["class" => "form-control"], ["readonly"])) !!}
        @endcan'
                ) */
                ->editColumn(
                    'total',
                    '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$total}}">{{$total}}</span>'
                )
                ->addColumn(
                    'calc_aguinaldo',
                    '
                     @can("planilla.update")
                        <button  {{$aprobada == 1 ? "disabled" : "" }} data-href="{{ action(\'PlanillaController@aguinaldoCalc\', [$id,$employee_id]) }}" class="btn btn-xs btn-success calc_aguinaldo_button text-center"><i class="fas fa-calculator"></i></button>
                    @endcan
                    '
                )
                ->editColumn(
                    'aguinaldo',
                    '
                    @can("planilla.update")
                    {!! Form::text("aguinaldo", number_format($aguinaldo, 2, ".", ","), array_merge(["class" => "form-control number"],  $aprobada == 1 ? ["readonly"] : [])) !!}
                    @else
                    {!! Form::text("aguinaldo", number_format($aguinaldo, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan
                    '
                )
                ->rawColumns(['action', 'salario_base', 'calc_aguinaldo', 'total_ccss', 'aguinaldo', 'hora_extra',  'monto_hora_extra', 'bonificacion', 'cant_hora_extra', 'prestamos', 'total'])
                ->make(true);
        }

        return view('admin.planillas.index_detalle_planilla', compact('planilla', 'id', 'canUpdate'));
    }
    public function viewPayment($id)
    {
        try {
            $detalle = DetallePlanilla::where('detalle_planillas.id', $id)
                ->join('employees', 'detalle_planillas.employee_id', 'employees.id')
                ->join('planillas', 'detalle_planillas.planilla_id', 'planillas.id')
                ->select([
                    'detalle_planillas.id as id',
                    'detalle_planillas.salario_base as salario_base',
                    'detalle_planillas.bonificacion as bonificacion',
                    'detalle_planillas.comisiones as comisiones',
                    'detalle_planillas.cant_hora_extra as cant_hora_extra',
                    'detalle_planillas.monto_hora_extra as monto_hora_extra',
                    'detalle_planillas.adelantos as adelantos',
                    'detalle_planillas.prestamos as prestamos',
                    'detalle_planillas.asociacion as asociacion',
                    'detalle_planillas.total as total',
                    'detalle_planillas.observaciones as observaciones',
                    'detalle_planillas.deudas as deudas',
                    'detalle_planillas.rebajados as rebajados',
                    'detalle_planillas.total_ccss as total_ccss',
                    'detalle_planillas.hora_extra as hora_extra',
                    'employees.name as name',
                    'employees.id as employee_id',
                    'planillas.aprobada as aprobada'
                ])->first();
            return view('admin.planillas.view-modal')->with(compact(
                'detalle'
            ));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
        }
    }
    public function updatePlanillaDetalle(Request $request, $id)
    {
        $total_monto_he = 0;
        $detalle_planilla = DetallePlanilla::findOrFail($id);
        $column = $request->input('column');
        $value = $request->input('value');
        $detalle[$column] = $value;
        $detalle_planilla->update($detalle);
        $total_monto_he = $detalle_planilla->cant_hora_extra * $detalle_planilla->hora_extra;
        $total = $detalle_planilla->salario_base +
            $detalle_planilla->bonificacion +
            $detalle_planilla->comisiones +
            $detalle_planilla->aguinaldo +
            $total_monto_he -
            ($detalle_planilla->adelantos +
                $detalle_planilla->prestamos +
                $detalle_planilla->deudas +
                $detalle_planilla->rebajados +
                $detalle_planilla->total_ccss);
        $detalle_planilla->update(['total' => $total, 'monto_hora_extra' => $total_monto_he]);

        return response()->json(['success' => true, 'total' => $total]);
    }
    public function updateApprove($id)
    {
        try {
            $planilla_get = Planilla::findOrFail($id);
            $planilla['aprobada'] = $planilla_get->aprobada == 1 ? 0 : 1;
            $msg = $planilla_get->aprobada == 1 ? "Se ha desaprobado la planilla" : "Se ha aprobado la planilla";

            $planilla_get->update($planilla);
            $output = [
                'success' => 1,
                'msg' => $msg
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    //Metodos para detalle planillas
    //Enviar correo
    /**
     * Handles the testing of email configuration
     *
     * @return \Illuminate\Http\Response
     */
    public function sendPaymentsEmail($id)
    {
        try {
            $detalles_planillas = DetallePlanilla::where('detalle_planillas.planilla_id', $id)
                ->join('employees', 'detalle_planillas.employee_id', 'employees.id')
                ->join('planillas', 'detalle_planillas.planilla_id', 'planillas.id')
                ->select([
                    'detalle_planillas.id as id',
                    'detalle_planillas.salario_base as salario_base',
                    'detalle_planillas.bonificacion as bonificacion',
                    'detalle_planillas.comisiones as comisiones',
                    'detalle_planillas.cant_hora_extra as cant_hora_extra',
                    'detalle_planillas.monto_hora_extra as monto_hora_extra',
                    'detalle_planillas.adelantos as adelantos',
                    'detalle_planillas.prestamos as prestamos',
                    'detalle_planillas.asociacion as asociacion',
                    'detalle_planillas.total as total',
                    'detalle_planillas.observaciones as observaciones',
                    'detalle_planillas.deudas as deudas',
                    'detalle_planillas.rebajados as rebajados',
                    'detalle_planillas.total_ccss as total_ccss',
                    'detalle_planillas.hora_extra as hora_extra',
                    'employees.name as name',
                    'employees.email as email',
                    'employees.id as employee_id',
                    'planillas.aprobada as aprobada',
                    'planillas.fecha_desde as fecha_desde',
                    'planillas.fecha_hasta as fecha_hasta'
                ])->get();


            foreach ($detalles_planillas as $detalle) {
                if ($detalle->email != "") {
                    $namePdf = "Comprobante de pago del " . $detalle->fecha_desde . " al " . $detalle->fecha_hasta . " - " . $detalle->name;
                    $data = [
                        'to_email' => $detalle->email,
                        'subject' => 'Colilla de pago del: ' . $detalle->fecha_desde . ' al ' . $detalle->fecha_hasta,
                        'email_body' => 'Adjunto encuentra la colilla de pago de esta quincena'
                    ];

                    $data['email_settings'] = request()->session()->get('business.email_settings');

                    $for_pdf = true;
                    $html = view('admin.planillas.email')->with(compact(
                        'detalle',
                        'for_pdf'
                    ))->render();
                    $mpdf = $this->getMpdf();
                    $mpdf->WriteHTML($html);

                    $file = config('constants.mpdf_temp_path') . '/' . time() . '_colilla_de_pago.pdf';
                    $mpdf->Output($file, 'F');

                    $data['attachment'] =  $file;
                    $data['attachment_name'] =  $namePdf;
                    Notification::route('mail', $data['to_email'])->notify(new CustomerNotification($data));

                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }

            $output = ['success' => true, 'msg' => __('lang_v1.notification_sent_successfully')];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return $output;
    }
    public function sendPaymentsEmailDetallado($id)
    {
        try {
            $detalle = DetallePlanilla::where('detalle_planillas.id', $id)
                ->join('employees', 'detalle_planillas.employee_id', 'employees.id')
                ->join('planillas', 'detalle_planillas.planilla_id', 'planillas.id')
                ->select([
                    'detalle_planillas.id as id',
                    'detalle_planillas.salario_base as salario_base',
                    'detalle_planillas.bonificacion as bonificacion',
                    'detalle_planillas.comisiones as comisiones',
                    'detalle_planillas.cant_hora_extra as cant_hora_extra',
                    'detalle_planillas.monto_hora_extra as monto_hora_extra',
                    'detalle_planillas.adelantos as adelantos',
                    'detalle_planillas.prestamos as prestamos',
                    'detalle_planillas.asociacion as asociacion',
                    'detalle_planillas.total as total',
                    'detalle_planillas.observaciones as observaciones',
                    'detalle_planillas.deudas as deudas',
                    'detalle_planillas.rebajados as rebajados',
                    'detalle_planillas.total_ccss as total_ccss',
                    'detalle_planillas.hora_extra as hora_extra',
                    'employees.name as name',
                    'employees.email as email',
                    'employees.id as employee_id',
                    'planillas.aprobada as aprobada',
                    'planillas.fecha_desde as fecha_desde',
                    'planillas.fecha_hasta as fecha_hasta'
                ])->first();
            if ($detalle->email != "") {
                $namePdf = "Comprobante de pago del " . $detalle->fecha_desde . " al " . $detalle->fecha_hasta . " - " . $detalle->name;
                $data = [
                    'to_email' => $detalle->email,
                    'subject' => 'Colilla de pago del: ' . $detalle->fecha_desde . ' al ' . $detalle->fecha_hasta,
                    'email_body' => 'Adjunto encuentra la colilla de pago de esta quincena'
                ];

                $data['email_settings'] = request()->session()->get('business.email_settings');

                $for_pdf = true;
                $html = view('admin.planillas.email')->with(compact(
                    'detalle',
                    'for_pdf'
                ))->render();
                $mpdf = $this->getMpdf();
                $mpdf->WriteHTML($html);

                $file = config('constants.mpdf_temp_path') . '/' . time() . '_colilla_de_pago.pdf';
                $mpdf->Output($file, 'F');

                $data['attachment'] =  $file;
                $data['attachment_name'] =  $namePdf;
                Notification::route('mail', $data['to_email'])->notify(new CustomerNotification($data));

                if (file_exists($file)) {
                    unlink($file);
                }
            }

            $output = ['success' => true, 'msg' => __('lang_v1.notification_sent_successfully')];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage()
            ];
        }

        return $output;
    }
    public function aguinaldoCalc($id, $employee_id)
    {
        try {
            DB::beginTransaction();
            if (request()->ajax()) {
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;
                //Se reversa el antiguo aguinaldo
                $detalle_planilla = DetallePlanilla::findOrFail($id);
                if ($detalle_planilla->aguinaldo > 0) {
                    $detalle_planilla->update(['total' => $detalle_planilla->total - $detalle_planilla->aguinaldo, 'aguinaldo' => 0]);
                    DB::commit();
                }

                $sumaTotal = DetallePlanilla::whereYear('planillas.fecha_hasta', $currentYear)
                    ->whereMonth('planillas.fecha_hasta', '<=', $currentMonth)
                    ->where('detalle_planillas.employee_id', $employee_id)
                    ->join('employees', 'detalle_planillas.employee_id', 'employees.id')
                    ->join('planillas', 'detalle_planillas.planilla_id', 'planillas.id')
                    ->selectRaw('
                    SUM(detalle_planillas.salario_base) + 
                    SUM(detalle_planillas.bonificacion) +
                    SUM(detalle_planillas.comisiones) +
                    SUM(detalle_planillas.monto_hora_extra)
                    as suma_total')
                    ->first()
                    ->suma_total;
                $sumaTotal = $sumaTotal / 12;
                //Actualiza el total
                $total_monto_he = 0;

                $total_monto_he = $detalle_planilla->cant_hora_extra * $detalle_planilla->hora_extra;
                $total = $detalle_planilla->salario_base +
                    $detalle_planilla->bonificacion +
                    $detalle_planilla->comisiones +
                    $sumaTotal +
                    $total_monto_he -
                    ($detalle_planilla->adelantos +
                        $detalle_planilla->prestamos +
                        $detalle_planilla->deudas +
                        $detalle_planilla->rebajados +
                        $detalle_planilla->total_ccss);

                //Se calcula el nuevo
                $detalle_planilla->update(['total' => $total, 'monto_hora_extra' => $total_monto_he, 'aguinaldo' => $sumaTotal]);
                $output = [
                    'success' => true,
                    'msg' => __("Se calculó el aguinaldo con éxito ")
                ];
                DB::commit();
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $output = [
                'success' => false,
                'msg' => $th->getMessage()
            ];
        }
        return json_encode($output);
    }
}

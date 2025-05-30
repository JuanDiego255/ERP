<?php

namespace App\Http\Controllers;

use App\Models\EmployeeHolidays;
use App\Models\EmployeeRubros;
use App\Models\Employees;
use App\Models\RubrosPlanilla;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function index()
    {
        if (!auth()->user()->can('employee.view') && !auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $employees = Employees::where('business_id', $business_id)
                ->select([
                    'id',
                    'hora_extra',
                    'name',
                    'email',
                    'telephone',
                    'celular',
                    'status',
                    'salario_base',
                    'asociacion',
                    'ccss',
                    'tipo_pago',
                    'moneda_pago',
                    'salario_hora',
                    'puesto',
                    'comision_ventas',
                    'business_id',
                    'created_at'
                ]);

            return Datatables::of($employees)
                ->addColumn('action', function ($row) {
                    $action_buttons = '';
                    if (auth()->user()->can('employee.update')) {
                        $action_buttons .= '<a href="' . action('EmployeeController@edit', [$row->id]) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> ' . __("messages.edit") . '</a> ';
                    }
                    if (auth()->user()->can('employee.view')) {
                        $action_buttons .= '<a href="' . action('EmployeeController@show', [$row->id]) . '" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> ' . __("Gestionar") . '</a> ';
                    }
                    if (auth()->user()->can('employee.delete')) {
                        $button_text = $row->status == 1 ? __("Deshabilitar") : __("Activar");
                        $button_class = $row->status == 1 ? "btn-danger" : "btn-success";

                        $action_buttons .= '<button data-href="' . action('EmployeeController@destroy', [$row->id]) . '" class="btn btn-xs ' . $button_class . ' delete_user_button"><i class="glyphicon glyphicon-refresh"></i> ' . $button_text . '</button>';
                    }

                    return $action_buttons;
                })
                ->removeColumn('id')
                ->editColumn('created_at', '{{ @format_date($created_at) }}')
                ->rawColumns(['action', 'name'])
                ->make(true);
        }

        return view('admin.employees.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.employees.create');
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $employee_details = $request->only([
                'name',
                'telephone',
                'celular',
                'email',
                'salario_base',
                'asociacion',
                'ccss',
                'tipo_pago',
                'moneda_pago',
                'salario_hora',
                'puesto',
                'comision_ventas',
                'hora_extra',
                'fecha_ingreso'
            ]);

            $employee_details['status'] = 1;
            $business_id = $request->session()->get('user.business_id');
            $employee_details['business_id'] = $business_id;

            //Create the employee
            Employees::create($employee_details);
            $output = [
                'success' => 1,
                'msg' => __("Se agregó el empleado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('employees')->with('status', $output);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createRubro()
    {
        $employee_id = request()->get('employee_id');
        $rubros = RubrosPlanilla::where('status', 1)->get()->pluck('name', 'id');
        return view('admin.rubros.tab_rubros.create')
            ->with(compact('employee_id', 'rubros'));
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function storeRubro(Request $request)
    {
        if (!auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $employee_rubro = $request->only([
                'tipo',
                'rubro_id',
                'valor',
                'status',
                'employee_id'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $employee_rubro['business_id'] = $business_id;

            $exist_rubro = EmployeeRubros::where('business_id', $business_id)
                ->where('employee_id', $employee_rubro['employee_id'])
                ->where('rubro_id', $employee_rubro['rubro_id'])->exists();
            if ($exist_rubro) {
                $output = [
                    'success' => 0,
                    'msg' => __("Este rubro ya ha sido agregado")
                ];
            } else {
                EmployeeRubros::create($employee_rubro);
                $output = [
                    'success' => 1,
                    'msg' => __("Se agregó el rubro con éxito")
                ];
            }
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return $output;
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        if (!auth()->user()->can('employee.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $employee = Employees::where('business_id', $business_id)
            ->findOrFail($id);

        return view('admin.employees.edit')
            ->with(compact('employee'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $employee_details = $request->only([
                'name',
                'telephone',
                'celular',
                'email',
                'salario_base',
                'asociacion',
                'ccss',
                'tipo_pago',
                'moneda_pago',
                'salario_hora',
                'puesto',
                'comision_ventas',
                'hora_extra'
            ]);

            $employee_details['status'] = 1;
            $business_id = $request->session()->get('user.business_id');
            $employee_details['business_id'] = $business_id;
            $employee = Employees::where('business_id', $business_id)
                ->findOrFail($id);

            $employee->update($employee_details);
            $output = [
                'success' => 1,
                'msg' => __("Empleado actualiado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('employees')->with('status', $output);
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function updateRubro(Request $request)
    {
        if (!auth()->user()->can('user.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $employee_rubro = $request->only([
                'tipo',
                'rubro_id',
                'valor',
                'status',
                'employee_id',
                'id'
            ]);
            $id = $employee_rubro['id'];

            $employee_rubro['status'] = 1;
            $business_id = $request->session()->get('user.business_id');
            $employee_rubro['business_id'] = $business_id;
            $employee = EmployeeRubros::where('business_id', $business_id)
                ->findOrFail($id);

            $employee->update($employee_rubro);
            $output = [
                'success' => 1,
                'msg' => __("Rubro actualizado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
            dd("");
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
        if (!auth()->user()->can('employee.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $employee = Employees::where('business_id', $business_id)
                    ->where('id', $id)
                    ->first();

                if ($employee) {
                    $employee->status = $employee->status == 1 ? 0 : 1;
                    $employee->save();

                    $output = [
                        'success' => true,
                        'msg' => __("Estado del empleado actualizado con éxito")
                    ];
                } else {
                    $output = [
                        'success' => false,
                        'msg' => __("Empleado no encontrado")
                    ];
                }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('employee.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $employee = Employees::where('business_id', $business_id)
            ->find($id);
        $employees = Employees::forDropdown($business_id, false);

        return view('admin.employees.show')->with(compact('employee', 'employees'));
    }
    /**
     * get docus & note index page
     * through ajax
     * @return \Illuminate\Http\Response
     */
    public function getDocAndNoteIndexPage(Request $request)
    {
        if (request()->ajax()) {

            return view('admin.rubros.tab_rubros.index');
        }
    }
    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroyRubro($id)
    {
        if (!auth()->user()->can('employee.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');
                $employee = EmployeeRubros::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $employee->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Rubro eliminado con éxito")
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
    public function storeAction(Request $request)
    {
        if (!auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $employee_action = $request->only([
                'fecha_desde',
                'fecha_hasta',
                'estado',
                'employee_id',
                'observacion'
            ]);
            $fecha_desde = $request->input('fecha_desde');
            $fecha_hasta = $request->input('fecha_hasta');
            $employee_id = $request->input('employee_id');

            $date1 = new DateTime($fecha_desde);
            $date2 = new DateTime($fecha_hasta);

            $interval = $date1->diff($date2);
            $days = $interval->days;
            $employee_action['cantidad'] = $days;
            $employee_action['estado'] = 1;

            $business_id = $request->session()->get('user.business_id');
            $employee_action['business_id'] = $business_id;

            EmployeeHolidays::create($employee_action);
            $employee = Employees::where('business_id', $business_id)
                ->findOrFail($employee_id);
            $employee_vacaciones['vacaciones'] = $employee->vacaciones - $days;
            $employee->update($employee_vacaciones);
            $output = [
                'success' => 1,
                'msg' => __("Se agregó la acción con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect()->back()->with('status', $output);
    }
    public function getVendedores()
    {
        if (request()->ajax()) {
            $term = request()->input('q', '');

            $business_id = request()->session()->get('user.business_id');

            $employees = Employees::where('business_id', $business_id);

            if (!empty($term)) {
                $employees->where(function ($query) use ($term) {
                    $query->where('name', 'like', '%' . $term . '%');
                });
            }

            $employees->select(
                'employees.id',
                'employees.name AS text',
            )->where('employees.puesto', 'vendedor')
                ->where('status', 1);
            $employees = $employees->get();
            return json_encode($employees);
        }
    }
}

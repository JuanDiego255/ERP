<?php

namespace App\Http\Controllers;

use App\Models\RubrosPlanilla;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RubrosController extends Controller
{
    //
    /**

     * Get all the blogs.

     *

     * @param Request $request


     */
    public function index()
    {
        if (!auth()->user()->can('rubros.view') && !auth()->user()->can('rubros.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $employees = RubrosPlanilla::where('business_id', $business_id)
                ->select([
                    'id', 'name', 'category', 'tipo', 'tipo_calculo', 'alias', 'status', 'business_id', 'created_at'
                ]);

            return Datatables::of($employees)
                ->addColumn(
                    'action',
                    '@can("employee.update")
                <a href="{{ action(\'RubrosController@edit\', [$id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                &nbsp;
                @endcan
                
                @can("employee.delete")
                                                                    <button data-href="{{ action(\'RubrosController@destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan'
                                )


                ->removeColumn('id')
                ->editColumn('created_at', '{{ @format_date($created_at) }}')
                ->editColumn('category', function ($data) {
                    return $data->category == 'ingreso' ? 'Ingreso' : 'Deducción';
                })
                ->editColumn('status', function ($data) {
                    return $data->status == '1' ? 'Activo' : 'Inactivo';
                })
                ->editColumn('tipo', function ($data) {
                    switch ($data->tipo) {
                        case "cant_salarios":
                            $tipo = "Cant. Salarios";
                            break;
                        case "monto":
                            $tipo = "Monto";
                            break;
                        case "cant_dias":
                            $tipo = "Cant. Días";
                            break;
                        case "cant_horas":
                            $tipo = "Cant. Horas";
                            break;
                    }
                    return $tipo;
                })
                ->editColumn('tipo_calculo', function ($data) {
                    switch ($data->tipo_calculo) {
                        case "normal":
                            $tipo = "Normal";
                            break;
                        case "extra_diurna":
                            $tipo = "Extra diurna";
                            break;
                        case "doble":
                            $tipo = "Doble";
                            break;
                    }
                    return $tipo;
                })
                ->rawColumns(['action', 'name'])
                ->make(true);
        }

        return view('admin.rubros.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('rubros.create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.rubros.create');
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('rubros.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $rubros_details = $request->only([
                'name', 'category', 'tipo', 'tipo_calculo', 'alias',
                'status'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $rubros_details['business_id'] = $business_id;

            //Create the employee
            RubrosPlanilla::create($rubros_details);
            $output = [
                'success' => 1,
                'msg' => __("Se agregó el rubro con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('rubros')->with('status', $output);
    }
    /**

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        if (!auth()->user()->can('rubros.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $rubro = RubrosPlanilla::where('business_id', $business_id)
            ->findOrFail($id);

        return view('admin.rubros.edit')
            ->with(compact('rubro'));
    }
    /**

     *Update the form data into the respective table

     *

     * @param Request $request

     * @param $id


     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('rubros.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $rubros_details = $request->only([
                'name', 'category', 'tipo', 'tipo_calculo', 'alias',
                'status'
            ]);
           
            $business_id = $request->session()->get('user.business_id');
            $rubros_details['business_id'] = $business_id;
            $rubro = RubrosPlanilla::where('business_id', $business_id)
                ->findOrFail($id);

            $rubro->update($rubros_details);
            $output = [
                'success' => 1,
                'msg' => __("Rubro actualiado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('rubros')->with('status', $output);
    }

    /**

     * delete the data from the respective table.

     *

     * @param $id


     */
    public function destroy($id)
    {
        if (!auth()->user()->can('rubros.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');
                $rubro = RubrosPlanilla::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $rubro->delete();
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\VehicleBill;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class BillVehicleController extends Controller
{
    //
    /**

     * Get all the vehicles bills.

     *

     * @param Request $request


     */
    public function indexBill($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $bills = VehicleBill::where('vehicle_bills.business_id', $business_id)
            ->where('vehicle_bills.product_id', $id)
            ->join('products', 'vehicle_bills.product_id', '=', 'products.id')
            ->join('contacts', 'vehicle_bills.proveedor_id', '=', 'contacts.id')
            ->select([
                'vehicle_bills.id as bill_id',
                'vehicle_bills.fecha_compra as fecha_compra',
                'products.name as name',
                'contacts.name as prov_name',
                'products.id as product_id',
                'vehicle_bills.descripcion as descripcion',
                'vehicle_bills.monto as monto',
                'vehicle_bills.factura as factura',
                'vehicle_bills.business_id',
                'vehicle_bills.created_at'
            ]);
        if (request()->ajax()) {
            
            return Datatables::of($bills)
                ->addColumn(
                    'action',
                    '@can("employee.update")
                <a href="{{ action(\'Admin\BillVehicleController@edit\', [$bill_id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                &nbsp;
                @endcan
                
                @can("employee.delete")
                                                                    <button data-href="{{ action(\'Admin\BillVehicleController@destroy\', [$bill_id]) }}" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan'
                )
                ->editColumn('fecha_compra', '{{ @format_date($fecha_compra) }}')
                ->editColumn('monto', '{{"₡ ". number_format($monto) }}')
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
        $totalMonto = (clone $bills)->sum('vehicle_bills.monto');
        $cant_gastos = (clone $bills)->count();

        return view('admin.vehicle-bills.index', compact('id', 'totalMonto', 'cant_gastos'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $types = [];
        $types['supplier'] = __('report.supplier');
        $types['customer'] = __('report.customer');
        $types['guarantor'] = __('report.guarantor');
        $types['both'] = __('lang_v1.both_supplier_customer');
        return view('admin.vehicle-bills.create')
            ->with('tipo', '')
            ->with(compact('types', 'id'));
    }
    /**

     *Insert the form data into the respective table

     *

     * @param Request $request


     */
    public function store(Request $request)
    {
        try {
            $bill_details = $request->only([
                'fecha_compra',
                'monto',
                'factura',
                'descripcion',
                'product_id',
                'proveedor_id'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $bill_details['business_id'] = $business_id;

            //Create the employee
            VehicleBill::create($bill_details);
            $output = [
                'success' => 1,
                'msg' => __("Se agregó el gasto con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('products/bills/' . $request->product_id)->with('status', $output);
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
                $rubro = VehicleBill::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $rubro->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Gasto eliminado con éxito")
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

     * Redirects to edit blog view.

     *

     * @param $id


     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');
        $bill = VehicleBill::where('business_id', $business_id)
            ->findOrFail($id);

        $proveedor = Contact::where('id', $bill->proveedor_id)->first();
        $prov_name = $proveedor->name;

        return view('admin.vehicle-bills.edit')
            ->with(compact('bill', 'prov_name'));
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
            $bill_details = $request->only([
                'fecha_compra',
                'monto',
                'factura',
                'descripcion',
                'product_id',
                'proveedor_id'
            ]);

            $business_id = $request->session()->get('user.business_id');
            $bill_details['business_id'] = $business_id;
            $bill = VehicleBill::where('business_id', $business_id)
                ->findOrFail($id);

            $bill->update($bill_details);
            $output = [
                'success' => 1,
                'msg' => __("Gasto actualiado con éxito")
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('products/bills/' . $request->product_id)->with('status', $output);
    }
}
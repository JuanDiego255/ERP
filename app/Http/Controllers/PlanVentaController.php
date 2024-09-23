<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Category;
use App\Models\PaymentRevenue;
use App\Models\PlanVenta;
use App\Models\Product;
use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PlanVentaController extends Controller
{
    //
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');
        $planillas = PlanVenta::where('plan_ventas.business_id', $business_id)
            ->join('contacts', 'plan_ventas.cliente_id', '=', 'contacts.id')
            ->join('products', 'plan_ventas.vehiculo_venta_id', '=', 'products.id')
            ->select([
                'contacts.name as name',
                'plan_ventas.id as id',
                'plan_ventas.numero as numero',
                'products.name as vehiculo',
                'plan_ventas.fecha_plan as fecha_plan'
            ])
            ->orderBy('plan_ventas.fecha_plan', 'desc');
        if (request()->ajax()) {

            return DataTables::of($planillas)
                ->addColumn(
                    'action',
                    '@can("employee.update")
                    <a href="{{ action(\'PlanVentaController@edit\', [$id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                    &nbsp;
                @endcan
                @can("employee.delete")
                    <button data-href="{{ action(\'PlanVentaController@destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_plan_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan'
                )
                ->editColumn('fecha_plan', '{{ @format_date($fecha_plan) }}')
                ->rawColumns(['action', 'name'])
                ->make(true);
        }

        return view('admin.plan_ventas.index');
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

        $categories = Category::forDropdown($business_id, 'product');

        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');

        //Get all business locations
        $business_locations = BusinessLocation::forDropdown($business_id);

        //Duplicate product
        $duplicate_product = null;
        $rack_details = null;

        $sub_categories = [];
        if (!empty(request()->input('d'))) {

            $duplicate_product = Product::where('business_id', $business_id)->find(request()->input('d'));
            $duplicate_product->name .= ' (copia)';

            if (!empty($duplicate_product->category_id)) {
                $sub_categories = Category::where('business_id', $business_id)
                    ->where('parent_id', $duplicate_product->category_id)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }

        $common_settings = session()->get('business.common_settings');
        $business = Business::find($business_id);

        $business_id = request()->session()->get('user.business_id');
        $plan = PlanVenta::where('plan_ventas.business_id', $business_id)
            ->join('contacts as cli', 'plan_ventas.cliente_id', '=', 'cli.id')
            ->join('contacts as fdr', 'plan_ventas.fiador_id', '=', 'fdr.id')
            ->join('employees as emp', 'plan_ventas.vendedor_id', '=', 'emp.id')
            ->join('revenues as cxc', 'plan_ventas.id', '=', 'cxc.plan_venta_id')
            ->leftJoin('products as vv', 'plan_ventas.vehiculo_venta_id', '=', 'vv.id')
            ->leftJoin('products as vr', 'plan_ventas.vehiculo_recibido_id', '=', 'vr.id')
            ->select(
                'plan_ventas.*',
                'cli.name as cliente_name',
                'fdr.name as fiador_name',
                'emp.name as vendedor_name',
                'vv.name as veh_venta',
                'vr.name as veh_rec',
                'cxc.plazo as plazo',
                'cxc.tasa as tasa',
                'cxc.cuota as cuota',
                'cxc.tipo_prestamo as tipo_prestamo',
                'cxc.moneda as moneda'
            )
            ->where('plan_ventas.id', $id) // Filtramos por el ID aquí
            ->firstOrFail();

        return view('admin.plan_ventas.edit')
            ->with(compact('plan', 'categories', 'brands', 'business_locations', 'duplicate_product', 'sub_categories', 'rack_details'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $categories = Category::forDropdown($business_id, 'product');

        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');

        //Get all business locations
        $business_locations = BusinessLocation::forDropdown($business_id);

        //Duplicate product
        $duplicate_product = null;
        $rack_details = null;

        $sub_categories = [];
        if (!empty(request()->input('d'))) {

            $duplicate_product = Product::where('business_id', $business_id)->find(request()->input('d'));
            $duplicate_product->name .= ' (copia)';

            if (!empty($duplicate_product->category_id)) {
                $sub_categories = Category::where('business_id', $business_id)
                    ->where('parent_id', $duplicate_product->category_id)
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }

        $common_settings = session()->get('business.common_settings');

        $listaCSTCSOSN = Product::listaCSTCSOSN();
        $listaCST_PIS_COFINS = Product::listaCST_PIS_COFINS();
        $listaCST_IPI = Product::listaCST_IPI();
        $unidadesDeMedida = Product::unidadesMedida();
        $business = Business::find($business_id);
        return view('admin.plan_ventas.create', compact('categories', 'brands', 'business_locations', 'duplicate_product', 'sub_categories', 'rack_details'));
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

                $employee = PlanVenta::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $employee->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Plan de venta eliminado con éxito")
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
    public function store(Request $request)
    {
        if (!auth()->user()->can('employee.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $plan_details = $request->only([
                'numero',
                'vehiculo_venta_id',
                'vehiculo_recibido_id',
                'business_id',
                'cliente_id',
                'fiador_id',
                'vendedor_id',
                'fecha_plan',
                'tipo_plan',
                'gastos_plan',
                'desc_forma_pago',
                'total_recibido',
                'total_financiado',
                'monto_recibo',
                'monto_efectivo',
                'venta_sin_rebajos'
            ]);
            $business_id = $request->session()->get('user.business_id');
            $plan_details['business_id'] = $business_id;
            $plan_details['vehiculo_venta_id'] = $request->vehiculo_venta_id_hidden;
            $plan_details['vehiculo_recibido_id'] = $request->vehiculo_recibido_id_hidden ?? null;

            //Create the employee
            $plan = PlanVenta::create($plan_details);

            //Crear CxC
            $cxc['business_id'] = $business_id;
            $cxc['referencia'] = $request->numero;
            $cxc['detalle'] = $request->desc_forma_pago;
            $cxc['valor_total'] = $request->total_financiado;
            $cxc['status'] = 0;
            $cxc['contact_id'] = $request->cliente_id;
            $cxc['tasa'] = $request->tasa;
            $cxc['cuota'] = $request->cuota;
            $cxc['tipo_prestamo'] = $request->tipo_prestamo;
            $cxc['moneda'] = $request->moneda;
            $cxc['plan_venta_id'] = $plan->id;
            $cxc['plazo'] = $request->plazo;
            $cxc['created_by'] = Auth::user()->id;
            $cxc_reg = Revenue::create($cxc);
            //Primer linea de pago CxC
            $cxc_pay['revenue_id'] = $cxc_reg->id;
            $cxc_pay['cuota'] = $request->cuota;
            $cxc_pay['monto_general'] = $request->total_financiado;
            $cxc_pay['paga'] = 0;
            $cxc_pay['interes_c'] = 0;
            $cxc_pay['amortiza'] = 0;
            PaymentRevenue::create($cxc_pay);
            $vehicle = Product::where('business_id', $business_id)
                ->where('id', $request->vehiculo_venta_id_hidden)
                ->firstOrFail();
            $vendido['is_inactive'] = 1;
            $vehicle->update($vendido);
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __("Se agregó el plan de venta con éxito")
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => __("messages.something_went_wrong")
            ];
        }

        return redirect('/plan-ventas-index')->with('status', $output);
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
            DB::beginTransaction();
            $plan_details = $request->only([
                'numero',
                'vehiculo_venta_id',
                'vehiculo_recibido_id',
                'business_id',
                'cliente_id',
                'fiador_id',
                'vendedor_id',
                'fecha_plan',
                'tipo_plan',
                'gastos_plan',
                'desc_forma_pago',
                'total_recibido',
                'total_financiado',
                'monto_recibo',
                'monto_efectivo',
                'venta_sin_rebajos'
            ]);

            //Formatear montos
            $plan_details['total_recibido'] = isset($plan_details['total_recibido'])
                ? floatval(str_replace(',', '', $plan_details['total_recibido']))
                : null;

            $plan_details['total_financiado'] = isset($plan_details['total_financiado'])
                ? floatval(str_replace(',', '', $plan_details['total_financiado']))
                : null;

            $plan_details['monto_recibo'] = isset($plan_details['monto_recibo'])
                ? floatval(str_replace(',', '', $plan_details['monto_recibo']))
                : null;

            $plan_details['monto_efectivo'] = isset($plan_details['monto_efectivo'])
                ? floatval(str_replace(',', '', $plan_details['monto_efectivo']))
                : null;
            //Formatear montos
            $business_id = $request->session()->get('user.business_id');
            $plan_details['business_id'] = $business_id;
            $plan_details['vehiculo_venta_id'] = $request->vehiculo_venta_id_hidden;
            $plan_details['vehiculo_recibido_id'] = $request->vehiculo_recibido_id_hidden;
            $plan = PlanVenta::where('business_id', $business_id)
                ->findOrFail($id);

            $plan->update($plan_details);
            $cuota =  isset($request->cuota)
                ? floatval(str_replace(',', '', $request->cuota))
                : null;

            $cxc_item = Revenue::where('business_id', $business_id)
                ->where('plan_venta_id', $id)
                ->firstOrFail();
            $cxc['business_id'] = $business_id;
            $cxc['referencia'] = $request->numero;
            $cxc['detalle'] = $request->desc_forma_pago;
            $cxc['valor_total'] = $plan_details['total_financiado'];
            $cxc['status'] = 0;
            $cxc['contact_id'] = $request->cliente_id;
            $cxc['tasa'] = $request->tasa;
            $cxc['cuota'] = $cuota;
            $cxc['tipo_prestamo'] = $request->tipo_prestamo;
            $cxc['moneda'] = $request->moneda;
            $cxc['plan_venta_id'] = $plan->id;
            $cxc['plazo'] = $request->plazo;
            $cxc['created_by'] = Auth::user()->id;
            $cxc_item->update($cxc);
            //Primer linea de pago CxC
            $pay = PaymentRevenue::where('revenue_id', $cxc_item->id)
                ->firstOrFail();
            $cxc_pay['cuota'] = $cuota;
            $cxc_pay['monto_general'] = $plan_details['total_financiado'];
            $cxc_pay['paga'] = 0;
            $cxc_pay['interes_c'] = 0;
            $cxc_pay['amortiza'] = 0;
            $pay->update($cxc_pay);
            DB::commit();
            $output = [
                'success' => 1,
                'msg' => __("Plan de venta actualizado con éxito")
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
            dd($e->getMessage());
            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('/plan-ventas-index')->with('status', $output);
    }
}

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
use App\Utils\TransactionUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PlanVentaController extends Controller
{
    //
    protected $transactionUtil;
    public function __construct(TransactionUtil $transactionUtil)
    {
        $this->transactionUtil = $transactionUtil;
    }
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
                'products.model as model',
                'plan_ventas.fecha_plan as fecha_plan'
            ])
            ->orderBy('plan_ventas.fecha_plan', 'desc');
        if (request()->ajax()) {

            return DataTables::of($planillas)
                ->addColumn(
                    'action',
                    '
                    <a href="{{ action(\'PlanVentaController@edit\', [$id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                    &nbsp;
                
                    @can("plan_venta.delete")
                        <button data-href="{{ action(\'PlanVentaController@destroy\', [$id]) }}" class="btn btn-xs btn-danger delete_plan_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan
                    <a href="{{ action(\'PlanVentaController@viewPlan\', [$id]) }}" class="btn btn-xs btn-success view-plan"><i class="glyphicon glyphicon-print"></i></a>
                    '
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
        if (!auth()->user()->can('plan_venta.update')) {
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

        $common_settings = session()->get('business.common_settings');
        $business = Business::find($business_id);
        $plan = PlanVenta::where('plan_ventas.business_id', $business_id)
            ->join('contacts as cli', 'plan_ventas.cliente_id', '=', 'cli.id')
            ->leftJoin('contacts as fdr', 'plan_ventas.fiador_id', '=', 'fdr.id')
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
    public function viewPlan($id)
    {
        try {
            $business_id = request()->session()->get('user.business_id');
            $plan = PlanVenta::where('plan_ventas.business_id', $business_id)
                ->join('contacts as cli', 'plan_ventas.cliente_id', '=', 'cli.id')
                ->leftJoin('contacts as fdr', 'plan_ventas.fiador_id', '=', 'fdr.id')
                ->join('employees as emp', 'plan_ventas.vendedor_id', '=', 'emp.id')
                ->join('revenues as cxc', 'plan_ventas.id', '=', 'cxc.plan_venta_id')
                ->leftJoin('products as vv', 'plan_ventas.vehiculo_venta_id', '=', 'vv.id')
                ->leftJoin('brands as b', 'vv.brand_id', '=', 'b.id')
                ->leftJoin('products as vr', 'plan_ventas.vehiculo_recibido_id', '=', 'vr.id')
                ->select(
                    'plan_ventas.*',
                    'cli.name as cliente_name',
                    'cli.identificacion as cliente_ident',
                    'cli.state as cliente_state',
                    'cli.position as cliente_puesto',
                    'cli.mobile as cliente_tel',
                    'cli.landmark as cliente_direccion',
                    'cli.email as cliente_email',
                    'fdr.name as fiador_name',
                    'fdr.identificacion as fiador_ident',
                    'fdr.state as fiador_state',
                    'fdr.position as fiador_puesto',
                    'fdr.mobile as fiador_tel',
                    'fdr.landmark as fiador_direccion',
                    'fdr.email as fiador_email',
                    'emp.name as vendedor_name',
                    'vv.name as veh_venta',
                    'vv.model as model',
                    'vv.bin as bin',
                    'vv.motor as motor',
                    'vv.color as color',
                    'vv.placa as placa',
                    'vv.monto_venta as monto_venta',
                    'vv.product_description as observacion',
                    'b.name as marca',
                    'cxc.plazo as plazo',
                    'cxc.tasa as tasa',
                    'cxc.cuota as cuota',
                    'cxc.tipo_prestamo as tipo_prestamo',
                    'cxc.moneda as moneda'
                )
                ->where('plan_ventas.id', $id) // Filtramos por el ID aquí
                ->firstOrFail();
            return view('admin.plan_ventas.view-modal')->with(compact(
                'plan'
            ));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('plan_venta.create')) {
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
        if (!auth()->user()->can('plan_venta.delete')) {
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
        if (!auth()->user()->can('plan_venta.create')) {
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


            //Formatear montos
            $total_financiado_format = isset($plan_details['total_financiado'])
                ? floatval(str_replace(',', '', $plan_details['total_financiado']))
                : null;
            $total_recibido_format = isset($plan_details['total_recibido'])
                ? floatval(str_replace(',', '', $plan_details['total_recibido']))
                : null;
            $monto_rec_format = isset($plan_details['monto_recibo'])
                ? floatval(str_replace(',', '', $plan_details['monto_recibo']))
                : null;
            $monto_efect_format = isset($plan_details['monto_efectivo'])
                ? floatval(str_replace(',', '', $plan_details['monto_efectivo']))
                : null;
            $monto_vent_format = isset($plan_details['venta_sin_rebajos'])
                ? floatval(str_replace(',', '', $plan_details['venta_sin_rebajos']))
                : null;

            $plan_details['total_recibido'] = $total_recibido_format;
            $plan_details['total_financiado'] = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
            $plan_details['monto_recibo'] = $monto_rec_format;
            $plan_details['monto_efectivo'] = $monto_efect_format;
            $plan_details['venta_sin_rebajos'] = $monto_vent_format;
            //Formatear montos
            //Create the employee
            $plan = PlanVenta::create($plan_details);
            $cuota =  isset($request->cuota)
                ? floatval(str_replace(',', '', $request->cuota))
                : null;

            //Crear CxC
            $cxc['business_id'] = $business_id;
            $cxc['sucursal'] = "GRECIA";
            $cxc['referencia'] = $request->numero;
            $cxc['detalle'] = $request->desc_forma_pago;
            $cxc['valor_total'] = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
            $cxc['status'] = 0;
            $cxc['contact_id'] = $request->cliente_id;
            $cxc['tasa'] = $request->tasa;
            $cxc['cuota'] = $cuota;
            $cxc['tipo_prestamo'] = $request->tipo_prestamo;
            $cxc['moneda'] = $request->moneda;
            $cxc['plan_venta_id'] = $plan->id;
            $cxc['plazo'] = $request->plazo;
            $cxc['created_by'] = Auth::user()->id;
            $cxc_reg = Revenue::create($cxc);
            //Primer linea de pago CxC
            $cxc_pay['referencia'] = $this->transactionUtil->getInvoiceNumber($business_id, 'final', "");
            $cxc_pay['revenue_id'] = $cxc_reg->id;
            $cxc_pay['cuota'] = $cuota;
            $cxc_pay['monto_general'] = $total_financiado_format;
            $cxc_pay['paga'] = $request->tipo_plan == 1 ? $total_recibido_format : 0;
            $cxc_pay['interes_c'] = 0;
            $cxc_pay['amortiza'] = $request->tipo_plan == 1 ? $total_recibido_format : 0;
            PaymentRevenue::create($cxc_pay);
            $vehicle = Product::where('business_id', $business_id)
                ->where('id', $request->vehiculo_venta_id_hidden)
                ->firstOrFail();
            $vendido['is_inactive'] = 1;
            $vendido['monto_venta'] = $monto_vent_format;
            $vehicle->update($vendido);
            //Vehículo recibido update
            if ($request->vehiculo_recibido_id_hidden) {
                $vehicle = Product::where('business_id', $business_id)
                    ->where('id', $request->vehiculo_recibido_id_hidden)
                    ->firstOrFail();
                if ($vehicle->is_inactive == 1) {
                    $stay = $vehicle->stay + 1;
                    $veh_recibido['is_inactive'] = 0;
                    $veh_recibido['stay'] = $stay;
                    $vehicle->update($veh_recibido);
                }
            }
            //Vehículo recibido update
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
        if (!auth()->user()->can('plan_venta.update')) {
            abort(403, 'Unauthorized action.');
        }
        try {
            $business_id = $request->session()->get('user.business_id');
            $cxc_item = Revenue::where('business_id', $business_id)
                ->where('plan_venta_id', $id)
                ->firstOrFail();
            $countPayment = PaymentRevenue::where('revenue_id', $cxc_item->id)->count();

            if (request()->ajax()) {
                DB::beginTransaction();
                $cxc['tasa'] = $request->tasa;
                $cxc_item->update($cxc);
                DB::commit();
                $output = [
                    'success' => true,
                    'msg' => 'Se ha actualizado la información de la cuenta'
                ];
                return $output;
            }
            if ($countPayment > 1) {
                $output = [
                    'success' => 0,
                    'msg' => __("No puedes modificar un plan de ventas que tiene más de un pago realizado en su cuenta por cobrar")
                ];
                return redirect('/plan-ventas-index')->with('status', $output);
            }
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
            $total_financiado_format = isset($plan_details['total_financiado'])
                ? floatval(str_replace(',', '', $plan_details['total_financiado']))
                : null;
            $total_recibido_format = isset($plan_details['total_recibido'])
                ? floatval(str_replace(',', '', $plan_details['total_recibido']))
                : null;
            $monto_rec_format = isset($plan_details['monto_recibo'])
                ? floatval(str_replace(',', '', $plan_details['monto_recibo']))
                : null;
            $monto_efect_format = isset($plan_details['monto_efectivo'])
                ? floatval(str_replace(',', '', $plan_details['monto_efectivo']))
                : null;
            $monto_vent_format = isset($plan_details['venta_sin_rebajos'])
                ? floatval(str_replace(',', '', $plan_details['venta_sin_rebajos']))
                : null;

            $plan_details['total_recibido'] = $total_recibido_format;
            $plan_details['total_financiado'] = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
            $plan_details['monto_recibo'] = $monto_rec_format;
            $plan_details['monto_efectivo'] = $monto_efect_format;
            $plan_details['venta_sin_rebajos'] = $monto_vent_format;
            //Formatear montos

            $plan_details['business_id'] = $business_id;
            $plan_details['vehiculo_venta_id'] = $request->vehiculo_venta_id_hidden;
            $plan_details['vehiculo_recibido_id'] = $request->vehiculo_recibido_id_hidden;
            $plan = PlanVenta::where('business_id', $business_id)
                ->findOrFail($id);

            $plan->update($plan_details);
            $cuota =  isset($request->cuota)
                ? floatval(str_replace(',', '', $request->cuota))
                : null;


            $cxc['business_id'] = $business_id;
            $cxc['referencia'] = $request->numero;
            $cxc['detalle'] = $request->desc_forma_pago;
            $cxc['sucursal'] = "GRECIA";
            $cxc['valor_total'] = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
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
            $cxc_pay = [
                'cuota' => $cuota,
                'monto_general' => $total_financiado_format,
                'paga' => $request->tipo_plan == 1 ? $total_recibido_format : 0,
                'interes_c' => 0,
                'amortiza' => $request->tipo_plan == 1 ? $total_recibido_format : 0
            ];

            // Actualizar o crear el registro
            $pay = PaymentRevenue::updateOrCreate(
                ['revenue_id' => $cxc_item->id], // Criterio de búsqueda
                $cxc_pay // Valores a actualizar o crear
            );
            $vehicle = Product::where('business_id', $business_id)
                ->where('id', $request->vehiculo_venta_id_hidden)
                ->firstOrFail();
            $vendido['monto_venta'] = $monto_vent_format;
            $vehicle->update($vendido);
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

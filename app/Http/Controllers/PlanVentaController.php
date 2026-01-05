<?php

namespace App\Http\Controllers;

use App\Models\Audit;
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
            ->join('contacts as cli', 'plan_ventas.cliente_id', '=', 'cli.id')
            ->leftJoin('contacts as fdr', 'plan_ventas.fiador_id', '=', 'fdr.id')
            ->join('products', 'plan_ventas.vehiculo_venta_id', '=', 'products.id')
            ->select([
                'cli.name as name',
                'fdr.name as fiador_name',
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
        $cxc_item = Revenue::where('business_id', $business_id)
            ->where('plan_venta_id', $id)
            ->firstOrFail();

        $countPayment = PaymentRevenue::where('revenue_id', $cxc_item->id)->count();
        $hasMultiplePayments = $countPayment > 1;

        $common_settings = session()->get('business.common_settings');
        $business = Business::find($business_id);
        $plan = PlanVenta::where('plan_ventas.business_id', $business_id)
            ->join('contacts as cli', 'plan_ventas.cliente_id', '=', 'cli.id')
            ->leftJoin('contacts as fdr', 'plan_ventas.fiador_id', '=', 'fdr.id')
            ->join('employees as emp', 'plan_ventas.vendedor_id', '=', 'emp.id')
            ->join('revenues as cxc', 'plan_ventas.id', '=', 'cxc.plan_venta_id')
            ->leftJoin('products as vv', 'plan_ventas.vehiculo_venta_id', '=', 'vv.id')
            ->leftJoin('products as vr', 'plan_ventas.vehiculo_recibido_id', '=', 'vr.id')
            ->leftJoin('products as vr2', 'plan_ventas.vehiculo_recibido_id_dos', '=', 'vr2.id')
            ->select(
                'plan_ventas.*',
                'cli.name as cliente_name',
                'fdr.name as fiador_name',
                'emp.name as vendedor_name',
                'vv.name as veh_venta',
                'vr.name as veh_rec',
                'vr.id as veh_rec_id',
                'vr.placa as placa',
                'vr.model as model',
                'vr.bin as bin',
                'vr2.id as veh_rec_id_dos',
                'vr2.name as veh_rec_dos',
                'vr2.model as model_dos',
                'vr2.placa as placa_dos',
                'vr.bin as bin_dos',
                'cxc.plazo as plazo',
                'cxc.tasa as tasa',
                'cxc.cuota as cuota',
                'cxc.tipo_prestamo as tipo_prestamo',
                'cxc.moneda as moneda'
            )
            ->where('plan_ventas.id', $id) // Filtramos por el ID aquí
            ->firstOrFail();

        return view('admin.plan_ventas.edit')
            ->with(compact('plan', 'categories', 'brands', 'hasMultiplePayments', 'business_locations', 'duplicate_product', 'sub_categories', 'rack_details'));
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
                ->leftJoin('products as vr2', 'plan_ventas.vehiculo_recibido_id_dos', '=', 'vr2.id')
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
                    'cxc.moneda as moneda',
                    'vr.id as veh_rec_id',
                    'vr.name as veh_rec',
                    'vr.model as model_rec',
                    'vr.bin as bin_rec',
                    'vr.placa as placa_rec',
                    'vr2.id as veh_rec_id_dos',
                    'vr2.name as veh_rec_dos',
                    'vr2.model as model_rec_dos',
                    'vr2.bin as bin_rec_dos',
                    'vr2.placa as placa_rec_dos'
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
                'vehiculo_recibido_id_dos',
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
            $plan_details['vehiculo_recibido_id_dos'] = $request->vehiculo_recibido_id_dos_hidden ?? null;


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
            $cxc['status'] = $request->tipo_plan == 1 ? 1 : 0;
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
            $hasMultiplePayments = $countPayment > 1;
            // Mantener comportamiento actual para AJAX (no tocamos esta parte)
            if (request()->ajax()) {
                DB::beginTransaction();
                $cxc = [
                    'tasa'   => $request->tasa,
                    'status' => $request->status,
                    'cuota'  => $request->cuota,
                    'detalle' => $request->detalle,
                ];
                $cxc_item->update($cxc);
                DB::commit();
                return [
                    'success' => true,
                    'msg' => 'Se ha actualizado la información de la cuenta'
                ];
            }
            DB::beginTransaction();
            // Helper para parsear montos con comas
            $moneyToFloat = function ($value) {
                return isset($value) ? floatval(str_replace(',', '', $value)) : null;
            };
            // 1) Tomar datos del plan
            $plan_details = $request->only([
                'numero',
                'vehiculo_venta_id',
                'vehiculo_recibido_id',
                'vehiculo_recibido_id_dos',
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

            // 2) Si hay múltiples pagos, NO permitir actualizar campos restringidos
            $restrictedFields = [
                'total_recibido',
                'total_financiado',
                'monto_recibo',
                'monto_efectivo',
                'venta_sin_rebajos',
                'vehiculo_venta_id',
                'vehiculo_recibido_id',
                'vehiculo_recibido_id_dos',
                'tipo_plan',
            ];

            if ($hasMultiplePayments && $request->is_new == 0) {
                foreach ($restrictedFields as $field) {
                    unset($plan_details[$field]);
                }
            }

            // 3) Parsear montos (solo si NO hay múltiples pagos, porque esos campos no deben tocarse)
            $total_financiado_format = $moneyToFloat($request->total_financiado);
            $total_recibido_format   = $moneyToFloat($request->total_recibido);
            $monto_rec_format        = $moneyToFloat($request->monto_recibo);
            $monto_efect_format      = $moneyToFloat($request->monto_efectivo);
            $monto_vent_format       = $moneyToFloat($request->venta_sin_rebajos);

            if (!$hasMultiplePayments || $request->is_new == 1) {
                $plan_details['total_recibido']    = $total_recibido_format;
                $plan_details['total_financiado']  = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
                $plan_details['monto_recibo']      = $monto_rec_format;
                $plan_details['monto_efectivo']    = $monto_efect_format;
                $plan_details['venta_sin_rebajos'] = $monto_vent_format;
            }

            // 4) Campos siempre válidos del plan
            $plan_details['business_id'] = $business_id;

            // Solo reasignar vehículos si NO hay múltiples pagos
            if (!$hasMultiplePayments || $request->is_new == 1) {
                $plan_details['vehiculo_venta_id'] = $request->vehiculo_venta_id_hidden;
                $plan_details['vehiculo_recibido_id'] = $request->vehiculo_recibido_id_hidden;
                $plan_details['vehiculo_recibido_id_dos'] = $request->vehiculo_recibido_id_dos_hidden;
            }

            $plan = PlanVenta::where('business_id', $business_id)->findOrFail($id);
            $plan->update($plan_details);

            // 5) Cuota (se puede actualizar siempre)
            $cuota = $moneyToFloat($request->cuota);

            // 6) Update del CxC (Revenue)
            //    OJO: si hay múltiples pagos, NO recalcular ni tocar status/valor_total derivados de tipo_plan/montos
            $cxc = [
                'business_id'   => $business_id,
                'referencia'    => $request->numero,
                'detalle'       => $request->desc_forma_pago,
                'sucursal'      => "GRECIA",
                'contact_id'    => $request->cliente_id,
                'tasa'          => $request->tasa,
                'cuota'         => $cuota,
                'tipo_prestamo' => $request->tipo_prestamo,
                'moneda'        => $request->moneda,
                'plan_venta_id' => $plan->id,
                'plazo'         => $request->plazo,
                'created_by'    => Auth::user()->id,
            ];

            if (!$hasMultiplePayments || $request->is_new == 1) {
                $cxc['valor_total'] = $request->tipo_plan == 1 ? $total_recibido_format : $total_financiado_format;
                $cxc['status']      = $request->tipo_plan == 1 ? 1 : 0;
            }

            $restrictedFields = [
                'cuota'
            ];

            if ($hasMultiplePayments && $request->is_new == 0) {
                foreach ($restrictedFields as $field) {
                    unset($cxc[$field]);
                }
            }

            $cxc_item->update($cxc);

            // 7) PaymentRevenue::updateOrCreate SOLO si NO hay múltiples pagos
            if (!$hasMultiplePayments) {
                $cxc_pay = [
                    'cuota'         => $cuota,
                    'monto_general'  => $total_financiado_format,
                    'paga'          => $request->tipo_plan == 1 ? $total_recibido_format : 0,
                    'interes_c'     => 0,
                    'amortiza'      => $request->tipo_plan == 1 ? $total_recibido_format : 0,
                ];

                PaymentRevenue::updateOrCreate(
                    ['revenue_id' => $cxc_item->id],
                    $cxc_pay
                );

                // 8) Update de monto_venta del vehículo SOLO si NO hay múltiples pagos (depende de venta_sin_rebajos)
                $vehicle = Product::where('business_id', $business_id)
                    ->where('id', $request->vehiculo_venta_id_hidden)
                    ->firstOrFail();

                $vehicle->update(['monto_venta' => $monto_vent_format]);
            } else {
                if ($request->is_new == 1) {
                    $cxc_pay[] = [
                        'cuota'         => $cuota,
                        'monto_general' => 0,
                        'paga'          => 0,
                        'interes_c'     => 0,
                        'detalle' => 'Arreglo de pago',
                        'referencia' => $this->transactionUtil->getInvoiceNumber($business_id, 'final', ""),
                        'amortiza'      => 0,
                        'created_at' => Carbon::now('America/Costa_Rica')->format('Y-m-d H:i:s'),
                        'fecha_interes' => Carbon::now('America/Costa_Rica')->format('Y-m-d H:i:s')
                    ];
                    $cxc_pay[] = [
                        'cuota'         => $cuota,
                        'monto_general' => $total_financiado_format,
                        'paga'          => $request->tipo_plan == 1 ? $total_recibido_format : 0,
                        'referencia' => $this->transactionUtil->getInvoiceNumber($business_id, 'final', ""),
                        'detalle' => 'Nueva Deuda',
                        'interes_c'     => 0,
                        'amortiza'      => $request->tipo_plan == 1 ? $total_recibido_format : 0,
                        'created_at' => Carbon::now('America/Costa_Rica')->format('Y-m-d H:i:s'),
                        'fecha_interes' => Carbon::now('America/Costa_Rica')->format('Y-m-d H:i:s')
                    ];

                    $cxc_item->payments()->createMany($cxc_pay);
                    // 8) Update de monto_venta del vehículo SOLO si NO hay múltiples pagos (depende de venta_sin_rebajos)
                    $vehicle = Product::where('business_id', $business_id)
                        ->where('id', $request->vehiculo_venta_id_hidden)
                        ->firstOrFail();
                    $vehicle->update(['monto_venta' => $monto_vent_format]);

                    $user_id = $request->session()->get('user.id');
                    $audit['type'] = "cxc";
                    $audit['type_transaction'] = "creación";
                    $audit['change'] = "Nuevo monto general = " . $total_financiado_format; // Cada cambio en una nueva línea
                    $audit['update_by'] = $user_id;
                    Audit::create($audit);
                }
            }

            DB::commit();
            $msg = __("Plan de venta actualizado con éxito");
            if ($hasMultiplePayments)
                $msg = __("Plan de venta actualizado con éxito (Este plan ya cuenta con pagos realizados, por lo cual los montos no se modificarán)");
            if ($request->is_new == 1 && $hasMultiplePayments)
                $msg = __("Plan de venta actualizado con éxito (Se re-planteó el plan con éxito)");

            $output = [
                'success' => 1,
                'msg' => $msg
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

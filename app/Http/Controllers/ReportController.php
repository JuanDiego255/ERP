<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\BusinessLocation;
use App\Models\CashRegister;
use App\Models\Category;

use App\Charts\CommonChart;
use App\Models\Audit;
use App\Models\Contact;

use App\Models\CustomerGroup;
use App\Models\ExpenseCategory;
use App\Models\Product;
use App\Models\PurchaseLine;
use App\Models\Restaurant\ResTable;
use App\Models\Revenue;
use App\Models\SellingPriceGroup;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\TransactionSellLine;
use App\Models\TransactionSellLinesPurchaseLines;
use App\Models\Unit;
use App\Models\User;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Models\Variation;
use App\Models\VariationLocationDetails;
use App\Models\VehicleBill;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * All Utils instance.
     *
     */
    protected $transactionUtil;
    protected $productUtil;
    protected $moduleUtil;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ProductUtil $productUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->productUtil = $productUtil;
        $this->moduleUtil = $moduleUtil;
    }
    /**
     * Shows profit\loss of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getProfitLoss(Request $request)
    {
        if (!auth()->user()->can('profit_loss_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $bills = VehicleBill::where('vehicle_bills.business_id', $business_id)
            ->join('products', 'vehicle_bills.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('contacts', 'vehicle_bills.proveedor_id', '=', 'contacts.id')
            ->leftJoin('users AS usr', 'vehicle_bills.created_by', '=', 'usr.id')
            ->select([
                'vehicle_bills.id as bill_id',
                'vehicle_bills.fecha_compra as fecha_compra',
                DB::raw("CONCAT(products.name, ' (', products.model, ')') as model_name"),
                'contacts.name as prov_name',
                'products.id as product_id',
                'products.name',
                'products.is_inactive as is_inactive',
                'categories.name as category_name',
                'vehicle_bills.descripcion as descripcion',
                'vehicle_bills.monto as monto',
                'vehicle_bills.factura as factura',
                'vehicle_bills.business_id',
                'vehicle_bills.created_at',
                DB::raw("CONCAT(COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by")
            ]);

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $start = request()->start_date;
            $end =  request()->end_date;
            $bills->whereDate('vehicle_bills.fecha_compra', '>=', $start)
                ->whereDate('vehicle_bills.fecha_compra', '<=', $end);
        }

        if (request()->has('payment_status')) {
            $payment_status = request()->get('payment_status');
            if (!empty($payment_status)) {
                if ($payment_status != -1) {
                    $bills->where('products.is_inactive', $payment_status == 2 ? 0 : 1);
                }
            }
        }
        if (request()->has('category_name')) {
            $category_name = request()->get('category_name');
            if (!empty($category_name)) {
                $bills->where('categories.id', $category_name);
            }
        }
        $bills->orderBy('vehicle_bills.fecha_compra', 'desc');
        if (request()->ajax()) {

            return Datatables::of($bills)
                ->addColumn(
                    'action',
                    '@can("product.update")
                <a href="{{ action(\'Admin\BillVehicleController@edit\', [$bill_id]) }}" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</a>
                &nbsp;
                @endcan
                
                @can("product.delete")
                                                                    <button data-href="{{ action(\'Admin\BillVehicleController@destroy\', [$bill_id]) }}" class="btn btn-xs btn-danger delete_user_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                @endcan'
                )
                ->editColumn('fecha_compra', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->format('Y/m/d g:i A');
                })
                ->editColumn('model_name', function ($row) {
                    $product = $row->is_inactive == 1 ? $row->model_name . ' <span class="label bg-gray">' . __("Vendido") . '</span>' : $row->name;


                    return $product;
                })
                ->editColumn('monto', '{{"₡ ". number_format($monto) }}')
                ->rawColumns(['action', 'model_name'])
                ->make(true);
        }
        $categories = Category::forDropdown($business_id, 'product');

        return view('report.profit_loss')->with(compact(
            'categories'
        ));
    }
    /**
     * Shows product report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getPurchaseSell(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');

            $purchase_details = $this->transactionUtil->getPurchaseTotals($business_id, $start_date, $end_date, $location_id);

            $sell_details = $this->transactionUtil->getSellTotals(
                $business_id,
                $start_date,
                $end_date,
                $location_id
            );

            $transaction_types = [
                'purchase_return',
                'sell_return'
            ];

            $transaction_totals = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id
            );

            $total_purchase_return_inc_tax = $transaction_totals['total_purchase_return_inc_tax'];
            $total_sell_return_inc_tax = $transaction_totals['total_sell_return_inc_tax'];

            $difference = [
                'total' => $sell_details['total_sell_inc_tax'] + $total_sell_return_inc_tax - $purchase_details['total_purchase_inc_tax'] - $total_purchase_return_inc_tax,
                'due' => $sell_details['invoice_due'] - $purchase_details['purchase_due']
            ];

            return [
                'purchase' => $purchase_details,
                'sell' => $sell_details,
                'total_purchase_return' => $total_purchase_return_inc_tax,
                'total_sell_return' => $total_sell_return_inc_tax,
                'difference' => $difference
            ];
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.purchase_sell')
            ->with(compact('business_locations'));
    }
    /**
     * Shows report for Supplier
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerSuppliers(Request $request)
    {
        if (!auth()->user()->can('contacts_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $contacts = Contact::where('contacts.business_id', $business_id)
                ->join('transactions AS t', 'contacts.id', '=', 't.contact_id')
                ->active()
                ->groupBy('contacts.id')
                ->select(
                    DB::raw("SUM(IF(t.type = 'purchase', final_total, 0)) as total_purchase"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', final_total, 0)) as total_purchase_return"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', final_total, 0)) as total_invoice"),
                    DB::raw("SUM(IF(t.type = 'purchase', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_paid"),
                    DB::raw("SUM(IF(t.type = 'sell' AND t.status = 'final', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as invoice_received"),
                    DB::raw("SUM(IF(t.type = 'sell_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as sell_return_paid"),
                    DB::raw("SUM(IF(t.type = 'purchase_return', (SELECT SUM(amount) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as purchase_return_received"),
                    DB::raw("SUM(IF(t.type = 'sell_return', final_total, 0)) as total_sell_return"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', final_total, 0)) as opening_balance"),
                    DB::raw("SUM(IF(t.type = 'opening_balance', (SELECT SUM(IF(is_return = 1,-1*amount,amount)) FROM transaction_payments WHERE transaction_payments.transaction_id=t.id), 0)) as opening_balance_paid"),
                    'contacts.supplier_business_name',
                    'contacts.name',
                    'contacts.id',
                    'contacts.type as contact_type'
                );
            $permitted_locations = auth()->user()->permitted_locations();

            if ($permitted_locations != 'all') {
                $contacts->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($request->input('customer_group_id'))) {
                $contacts->where('contacts.customer_group_id', $request->input('customer_group_id'));
            }

            if (!empty($request->input('contact_type'))) {
                $contacts->whereIn('contacts.type', [$request->input('contact_type'), 'both']);
            }

            return Datatables::of($contacts)
                ->editColumn('name', function ($row) {
                    $name = $row->name;
                    if (!empty($row->supplier_business_name)) {
                        $name .= ', ' . $row->supplier_business_name;
                    }
                    return '<a href="' . action('ContactController@show', [$row->id]) . '" target="_blank" class="no-print">' .
                        $name .
                        '</a><span class="print_section">' . $name . '</span>';
                })
                ->editColumn('total_purchase', function ($row) {
                    return '<span class="display_currency total_purchase" data-orig-value="' . $row->total_purchase . '" data-currency_symbol = true>' . $row->total_purchase . '</span>';
                })
                ->editColumn('total_purchase_return', function ($row) {
                    return '<span class="display_currency total_purchase_return" data-orig-value="' . $row->total_purchase_return . '" data-currency_symbol = true>' . $row->total_purchase_return . '</span>';
                })
                ->editColumn('total_sell_return', function ($row) {
                    return '<span class="display_currency total_sell_return" data-orig-value="' . $row->total_sell_return . '" data-currency_symbol = true>' . $row->total_sell_return . '</span>';
                })
                ->editColumn('total_invoice', function ($row) {
                    return '<span class="display_currency total_invoice" data-orig-value="' . $row->total_invoice . '" data-currency_symbol = true>' . $row->total_invoice . '</span>';
                })
                ->addColumn('due', function ($row) {
                    $due = ($row->total_invoice - $row->invoice_received - $row->total_sell_return + $row->sell_return_paid) - ($row->total_purchase - $row->total_purchase_return + $row->purchase_return_received - $row->purchase_paid);

                    if ($row->contact_type == 'supplier') {
                        $due -= $row->opening_balance - $row->opening_balance_paid;
                    } else {
                        $due += $row->opening_balance - $row->opening_balance_paid;
                    }

                    return '<span class="display_currency total_due" data-orig-value="' . $due . '" data-currency_symbol=true data-highlight=true>' . $due . '</span>';
                })
                ->addColumn(
                    'opening_balance_due',
                    '<span class="display_currency opening_balance_due" data-currency_symbol=true data-orig-value="{{$opening_balance - $opening_balance_paid}}">{{$opening_balance - $opening_balance_paid}}</span>'
                )
                ->removeColumn('supplier_business_name')
                ->removeColumn('invoice_received')
                ->removeColumn('purchase_paid')
                ->removeColumn('id')
                ->rawColumns(['total_purchase', 'total_invoice', 'due', 'name', 'total_purchase_return', 'total_sell_return', 'opening_balance_due'])
                ->make(true);
        }

        $customer_group = CustomerGroup::forDropdown($business_id, false, true);
        $types = [
            '' => __('lang_v1.all'),
            'customer' => __('report.customer'),
            'supplier' => __('report.supplier')
        ];

        return view('report.contact')
            ->with(compact('customer_group', 'types'));
    }
    /**
     * Shows product stock report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $selling_price_groups = SellingPriceGroup::where('business_id', $business_id)
            ->get();
        $allowed_selling_price_group = false;
        foreach ($selling_price_groups as $selling_price_group) {
            if (auth()->user()->can('selling_price_group.' . $selling_price_group->id)) {
                $allowed_selling_price_group = true;
                break;
            }
        }

        if ($this->moduleUtil->isModuleInstalled('Manufacturing') && (auth()->user()->can('superadmin') || $this->moduleUtil->hasThePermissionInSubscription($business_id, 'manufacturing_module'))) {
            $show_manufacturing_data = true;
        } else {
            $show_manufacturing_data = false;
        }

        //Return the details in ajax call
        if ($request->ajax()) {
            $query = Variation::join('products as p', 'p.id', '=', 'variations.product_id')
                ->join('units', 'p.unit_id', '=', 'units.id')
                ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                ->leftjoin('business_locations as l', 'vld.location_id', '=', 'l.id')
                ->join('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                ->where('p.business_id', $business_id)
                ->whereIn('p.type', ['single', 'variable']);

            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = '';

            if ($permitted_locations != 'all') {
                $query->whereIn('vld.location_id', $permitted_locations);

                $locations_imploded = implode(', ', $permitted_locations);
                $location_filter .= "AND transactions.location_id IN ($locations_imploded) ";
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');

                $query->where('vld.location_id', $location_id);

                $location_filter .= "AND transactions.location_id=$location_id";

                //If filter by location then hide products not available in that location
                $query->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
                    ->where(function ($q) use ($location_id) {
                        $q->where('pl.location_id', $location_id);
                    });
            }

            if (!empty($request->input('category_id'))) {
                $query->where('p.category_id', $request->input('category_id'));
            }
            if (!empty($request->input('sub_category_id'))) {
                $query->where('p.sub_category_id', $request->input('sub_category_id'));
            }
            if (!empty($request->input('brand_id'))) {
                $query->where('p.brand_id', $request->input('brand_id'));
            }
            if (!empty($request->input('unit_id'))) {
                $query->where('p.unit_id', $request->input('unit_id'));
            }

            $tax_id = request()->get('tax_id', null);
            if (!empty($tax_id)) {
                $query->where('p.tax', $tax_id);
            }

            $type = request()->get('type', null);
            if (!empty($type)) {
                $query->where('p.type', $type);
            }

            $only_mfg_products = request()->get('only_mfg_products', 0);
            if (!empty($only_mfg_products)) {
                $query->join('mfg_recipes as mr', 'mr.variation_id', '=', 'variations.id');
            }

            $active_state = request()->get('active_state', null);
            if ($active_state == 'active') {
                $query->where('p.is_inactive', 0);
            }
            if ($active_state == 'inactive') {
                $query->where('p.is_inactive', 1);
            }
            $not_for_selling = request()->get('not_for_selling', null);
            if ($not_for_selling == 'true') {
                $query->where('p.not_for_selling', 1);
            }

            if (!empty(request()->get('repair_model_id'))) {
                $query->where('p.repair_model_id', request()->get('repair_model_id'));
            }

            //TODO::Check if result is correct after changing LEFT JOIN to INNER JOIN
            $pl_query_string = $this->productUtil->get_pl_quantity_sum_string('pl');

            if (request()->input('for') == 'view_product' && !empty(request()->input('product_id'))) {
                $location_filter = 'AND transactions.location_id=l.id';
            }

            $products = $query->select(
                // DB::raw("(SELECT SUM(quantity) FROM transaction_sell_lines LEFT JOIN transactions ON transaction_sell_lines.transaction_id=transactions.id WHERE transactions.status='final' $location_filter AND
                //     transaction_sell_lines.product_id=products.id) as total_sold"),

                DB::raw("(SELECT SUM(TSL.quantity - TSL.quantity_returned) FROM transactions 
                    JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell' $location_filter 
                    AND TSL.variation_id=variations.id) as total_sold"),
                DB::raw("(SELECT SUM(IF(transactions.type='sell_transfer', TSL.quantity, 0) ) FROM transactions 
                    JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell_transfer' $location_filter AND (TSL.variation_id=variations.id)) as total_transfered"),
                DB::raw("(SELECT SUM(IF(transactions.type='stock_adjustment', SAL.quantity, 0) ) FROM transactions 
                    JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='stock_adjustment' $location_filter
                    AND (SAL.variation_id=variations.id)) as total_adjusted"),
                DB::raw("(SELECT SUM( COALESCE(pl.quantity - ($pl_query_string), 0) * purchase_price_inc_tax) FROM transactions 
                    JOIN purchase_lines AS pl ON transactions.id=pl.transaction_id
                    WHERE transactions.status='received' $location_filter 
                    AND (pl.variation_id=variations.id)) as stock_price"),
                DB::raw("(SELECT price_inc_tax from variation_group_prices as vgp WHERE vgp.variation_id=variations.id AND vgp.price_group_id=l.selling_price_group_id) as group_price"),
                DB::raw("SUM(vld.qty_available) as stock"),
                'variations.sub_sku as sku',
                'p.name as product',
                'p.type',
                'p.id as product_id',
                'units.short_name as unit',
                'p.enable_stock as enable_stock',
                'variations.sell_price_inc_tax as unit_price',
                'pv.name as product_variation',
                'variations.name as variation_name',
                'l.name as location_name',
                'l.id as location_id',
                'variations.id as variation_id'
            );

            if ($show_manufacturing_data) {
                $pl_query_string = $this->productUtil->get_pl_quantity_sum_string('PL');
                $products->addSelect(
                    DB::raw("(SELECT COALESCE(SUM(PL.quantity - ($pl_query_string)), 0) FROM transactions 
                        JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                        WHERE transactions.status='received' AND transactions.type='production_purchase' $location_filter 
                        AND (PL.variation_id=variations.id)) as total_mfg_stock")
                );
            }
            $products->groupBy('variations.id');

            //To show stock details on view product modal
            if (request()->input('for') == 'view_product' && !empty(request()->input('product_id'))) {
                $products->where('p.id', request()->input('product_id'))
                    ->groupBy('l.id');

                $product_stock_details = $products->get();

                return view('product.partials.product_stock_details')->with(compact('product_stock_details'));
            }

            $datatable =  Datatables::of($products)
                ->editColumn('stock', function ($row) {
                    if ($row->enable_stock) {
                        $stock = $row->stock ? $row->stock : 0;
                        return  '<span data-is_quantity="true" class="current_stock display_currency" data-orig-value="' . (float)$stock . '" data-unit="' . $row->unit . '" data-currency_symbol=false > ' . (float)$stock . '</span>' . ' ' . $row->unit;
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('product', function ($row) {
                    $name = $row->product;
                    if ($row->type == 'variable') {
                        $name .= ' - ' . $row->product_variation . '-' . $row->variation_name;
                    }
                    return $name;
                })
                ->editColumn('total_sold', function ($row) {
                    $total_sold = 0;
                    if ($row->total_sold) {
                        $total_sold =  (float)$row->total_sold;
                    }

                    return '<span data-is_quantity="true" class="display_currency total_sold" data-currency_symbol=false data-orig-value="' . $total_sold . '" data-unit="' . $row->unit . '" >' . $total_sold . '</span> ' . $row->unit;
                })
                ->editColumn('total_transfered', function ($row) {
                    $total_transfered = 0;
                    if ($row->total_transfered) {
                        $total_transfered =  (float)$row->total_transfered;
                    }

                    return '<span data-is_quantity="true" class="display_currency total_transfered" data-currency_symbol=false data-orig-value="' . $total_transfered . '" data-unit="' . $row->unit . '" >' . $total_transfered . '</span> ' . $row->unit;
                })
                ->editColumn('total_adjusted', function ($row) {
                    $total_adjusted = 0;
                    if ($row->total_adjusted) {
                        $total_adjusted =  (float)$row->total_adjusted;
                    }

                    return '<span data-is_quantity="true" class="display_currency total_adjusted" data-currency_symbol=false  data-orig-value="' . $total_adjusted . '" data-unit="' . $row->unit . '" >' . $total_adjusted . '</span> ' . $row->unit;
                })
                ->editColumn('unit_price', function ($row) use ($allowed_selling_price_group) {
                    $html = '';
                    if (auth()->user()->can('access_default_selling_price')) {
                        $html .= '<span class="display_currency" data-currency_symbol=true >'
                            . $row->unit_price . '</span>';
                    }

                    if ($allowed_selling_price_group) {
                        $html .= ' <button type="button" class="btn btn-primary btn-xs btn-modal no-print" data-container=".view_modal" data-href="' . action('ProductController@viewGroupPrice', [$row->product_id]) . '">' . __('lang_v1.view_group_prices') . '</button>';
                    }

                    return $html;
                })
                ->editColumn('stock_price', function ($row) {
                    $html = '<span class=" total_stock_price" data-currency_symbol=true data-orig-value="'
                        . (float)$row->stock_price . '">'
                        . number_format($row->stock_price, 2, ',', '.') . '</span>';

                    return $html;
                })
                ->editColumn('stock_value_by_sale_price', function ($row) {
                    $stock = $row->stock ? $row->stock : 0;
                    $unit_selling_price = (float)$row->group_price > 0 ? $row->group_price : $row->unit_price;
                    $stock_price = $stock * $unit_selling_price;
                    return  '<span class="stock_value_by_sale_price" data-orig-value="' . (float)$stock_price . '" data-currency_symbol=true > ' . number_format($stock_price, 2, ',', '.') . '</span>';
                })
                ->addColumn('potential_profit', function ($row) {
                    $stock = $row->stock ? $row->stock : 0;
                    $unit_selling_price = (float)$row->group_price > 0 ? $row->group_price : $row->unit_price;
                    $stock_price_by_sp = $stock * $unit_selling_price;
                    $potential_profit = $stock_price_by_sp - $row->stock_price;

                    return  '<span class="potential_profit " data-orig-value="' . (float)$potential_profit . '" data-currency_symbol=true > ' . number_format($potential_profit, 2, ',', '.') . '</span>';
                })
                ->removeColumn('enable_stock')
                ->removeColumn('unit')
                ->removeColumn('id');

            $raw_columns  = [
                'unit_price',
                'total_transfered',
                'total_sold',
                'total_adjusted',
                'stock',
                'stock_price',
                'stock_value_by_sale_price',
                'potential_profit'
            ];

            if ($show_manufacturing_data) {
                $datatable->editColumn('total_mfg_stock', function ($row) {
                    $total_mfg_stock = 0;
                    if ($row->total_mfg_stock) {
                        $total_mfg_stock =  (float)$row->total_mfg_stock;
                    }

                    return '<span data-is_quantity="true" class="display_currency total_mfg_stock" data-currency_symbol=false  data-orig-value="' . $total_mfg_stock . '" data-unit="' . $row->unit . '" >' . $total_mfg_stock . '</span> ' . $row->unit;
                });
                $raw_columns[] = 'total_mfg_stock';
            }

            return $datatable->rawColumns($raw_columns)->make(true);
        }

        $categories = Category::forDropdown($business_id, 'product');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.stock_report')
            ->with(compact('categories', 'brands', 'units', 'business_locations', 'show_manufacturing_data'));
    }
    /**
     * Shows product stock details
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockDetails(Request $request)
    {
        //Return the details in ajax call
        if ($request->ajax()) {
            $business_id = $request->session()->get('user.business_id');
            $product_id = $request->input('product_id');
            $query = Product::leftjoin('units as u', 'products.unit_id', '=', 'u.id')
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->join('product_variations as pv', 'pv.id', '=', 'v.product_variation_id')
                ->leftjoin('variation_location_details as vld', 'v.id', '=', 'vld.variation_id')
                ->where('products.business_id', $business_id)
                ->where('products.id', $product_id)
                ->whereNull('v.deleted_at');

            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = '';
            if ($permitted_locations != 'all') {
                $query->whereIn('vld.location_id', $permitted_locations);
                $locations_imploded = implode(', ', $permitted_locations);
                $location_filter .= "AND transactions.location_id IN ($locations_imploded) ";
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');

                $query->where('vld.location_id', $location_id);

                $location_filter .= "AND transactions.location_id=$location_id";
            }

            $product_details =  $query->select(
                'products.name as product',
                'u.short_name as unit',
                'pv.name as product_variation',
                'v.name as variation',
                'v.sub_sku as sub_sku',
                'v.sell_price_inc_tax',
                DB::raw("SUM(vld.qty_available) as stock"),
                DB::raw("(SELECT SUM(IF(transactions.type='sell', TSL.quantity - TSL.quantity_returned, -1* TPL.quantity) ) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id

                    LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id

                    WHERE transactions.status='final' AND transactions.type='sell' $location_filter 
                    AND (TSL.variation_id=v.id OR TPL.variation_id=v.id)) as total_sold"),
                DB::raw("(SELECT SUM(IF(transactions.type='sell_transfer', TSL.quantity, 0) ) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell_transfer' $location_filter 
                    AND (TSL.variation_id=v.id)) as total_transfered"),
                DB::raw("(SELECT SUM(IF(transactions.type='stock_adjustment', SAL.quantity, 0) ) FROM transactions 
                    LEFT JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='stock_adjustment' $location_filter 
                    AND (SAL.variation_id=v.id)) as total_adjusted")
                // DB::raw("(SELECT SUM(quantity) FROM transaction_sell_lines LEFT JOIN transactions ON transaction_sell_lines.transaction_id=transactions.id WHERE transactions.status='final' $location_filter AND
                //     transaction_sell_lines.variation_id=v.id) as total_sold")
            )
                ->groupBy('v.id')
                ->get();

            return view('report.stock_details')
                ->with(compact('product_details'));
        }
    }
    /**
     * Shows tax report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getTaxReport(Request $request)
    {
        if (!auth()->user()->can('tax_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $location_id = $request->get('location_id');

            $input_tax_details = $this->transactionUtil->getInputTax($business_id, $start_date, $end_date, $location_id);

            $input_tax = view('report.partials.tax_details')->with(['tax_details' => $input_tax_details])->render();

            $output_tax_details = $this->transactionUtil->getOutputTax($business_id, $start_date, $end_date, $location_id);

            $expense_tax_details = $this->transactionUtil->getExpenseTax($business_id, $start_date, $end_date, $location_id);

            $output_tax = view('report.partials.tax_details')->with(['tax_details' => $output_tax_details])->render();

            $expense_tax = view('report.partials.tax_details')->with(['tax_details' => $expense_tax_details])->render();

            return [
                'input_tax' => $input_tax,
                'output_tax' => $output_tax,
                'expense_tax' => $expense_tax,
                'tax_diff' => $output_tax_details['total_tax'] - $input_tax_details['total_tax'] - $expense_tax_details['total_tax']
            ];
        }
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.tax_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows trending products
     *
     * @return \Illuminate\Http\Response
     */
    public function getTrendingProducts(Request $request)
    {
        if (!auth()->user()->can('trending_product_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $filters = request()->only(['category', 'sub_category', 'brand', 'unit', 'limit', 'location_id', 'product_type']);

        $date_range = request()->input('date_range');

        if (!empty($date_range)) {
            $date_range_array = explode('~', $date_range);
            $filters['start_date'] = $this->transactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->transactionUtil->uf_date(trim($date_range_array[1]));
        }

        $products = $this->productUtil->getTrendingProducts($business_id, $filters);

        $values = [];
        $labels = [];
        foreach ($products as $product) {
            $values[] = (float) $product->total_unit_sold;
            $labels[] = $product->product . ' (' . $product->unit . ')';
        }

        $chart = new CommonChart;
        $chart->labels($labels)
            ->dataset(__('report.total_unit_sold'), 'column', $values);

        $categories = Category::forDropdown($business_id, 'product');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.trending_products')
            ->with(compact('chart', 'categories', 'brands', 'units', 'business_locations'));
    }

    public function getTrendingProductsAjax()
    {
        $business_id = request()->session()->get('user.business_id');
    }
    /**
     * Shows expense report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getExpenseReport(Request $request)
    {
        if (!auth()->user()->can('expense_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $filters = $request->only(['category', 'location_id']);

        $date_range = $request->input('date_range');

        if (!empty($date_range)) {
            $date_range_array = explode('~', $date_range);
            $filters['start_date'] = $this->transactionUtil->uf_date(trim($date_range_array[0]));
            $filters['end_date'] = $this->transactionUtil->uf_date(trim($date_range_array[1]));
        } else {
            $filters['start_date'] = \Carbon::now()->startOfMonth()->format('Y-m-d');
            $filters['end_date'] = \Carbon::now()->endOfMonth()->format('Y-m-d');
        }

        $expenses = $this->transactionUtil->getExpenseReport($business_id, $filters);

        $values = [];
        $labels = [];
        foreach ($expenses as $expense) {
            $values[] = (float) $expense->total_expense;
            $labels[] = !empty($expense->category) ? $expense->category : __('report.others');
        }

        $chart = new CommonChart;
        $chart->labels($labels)
            ->title(__('report.expense_report'))
            ->dataset(__('report.total_expense'), 'column', $values);

        $categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.expense_report')
            ->with(compact('chart', 'categories', 'business_locations', 'expenses'));
    }

    /**
     * Shows stock adjustment report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockAdjustmentReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $query =  Transaction::where('business_id', $business_id)
                ->where('type', 'stock_adjustment');

            //Check for permitted locations of a user
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('location_id', $permitted_locations);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }
            $location_id = $request->get('location_id');
            if (!empty($location_id)) {
                $query->where('location_id', $location_id);
            }

            $stock_adjustment_details = $query->select(
                DB::raw("SUM(final_total) as total_amount"),
                DB::raw("SUM(total_amount_recovered) as total_recovered"),
                DB::raw("SUM(IF(adjustment_type = 'normal', final_total, 0)) as total_normal"),
                DB::raw("SUM(IF(adjustment_type = 'abnormal', final_total, 0)) as total_abnormal")
            )->first();
            return $stock_adjustment_details;
        }
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.stock_adjustment_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows register report of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegisterReport(Request $request)
    {
        if (!auth()->user()->can('register_report.view')) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $registers = CashRegister::join(
                'users as u',
                'u.id',
                '=',
                'cash_registers.user_id'
            )
                ->leftJoin(
                    'business_locations as bl',
                    'bl.id',
                    '=',
                    'cash_registers.location_id'
                )
                ->where('cash_registers.business_id', $business_id)
                ->select(
                    'cash_registers.*',
                    DB::raw(
                        "CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(u.email, '')) as user_name"
                    ),
                    'bl.name as location_name'
                );

            if (!empty($request->input('user_id'))) {
                $registers->where('cash_registers.user_id', $request->input('user_id'));
            }
            if (!empty($request->input('status'))) {
                $registers->where('cash_registers.status', $request->input('status'));
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            if (!empty($start_date) && !empty($end_date)) {
                $registers->whereDate('cash_registers.created_at', '>=', $start_date)
                    ->whereDate('cash_registers.created_at', '<=', $end_date);
            }
            return Datatables::of($registers)
                ->editColumn('total_card_slips', function ($row) {
                    if ($row->status == 'close') {
                        return $row->total_card_slips;
                    } else {
                        return '';
                    }
                })
                ->editColumn('total_cheques', function ($row) {
                    if ($row->status == 'close') {
                        return $row->total_cheques;
                    } else {
                        return '';
                    }
                })
                ->editColumn('closed_at', function ($row) {
                    if ($row->status == 'close') {
                        return $this->productUtil->format_date($row->closed_at, true);
                    } else {
                        return '';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $this->productUtil->format_date($row->created_at, true);
                })
                ->editColumn('closing_amount', function ($row) {
                    if ($row->status == 'close') {
                        return '<span class="display_currency" data-currency_symbol="true">' .
                            $row->closing_amount . '</span>';
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', '<button type="button" data-href="{{action(\'CashRegisterController@show\', [$id])}}" class="btn btn-xs btn-info btn-modal" 
                data-container=".view_register"><i class="fas fa-eye" aria-hidden="true"></i> @lang("messages.view")</button>')
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, ''), '<br>', COALESCE(u.email, '')) like ?", ["%{$keyword}%"]);
                })
                ->rawColumns(['action', 'user_name', 'closing_amount'])
                ->make(true);
        }

        $users = User::forDropdown($business_id, false);

        return view('report.register_report')
            ->with(compact('users'));
    }

    /**
     * Shows sales representative report
     *
     * @return \Illuminate\Http\Response
     */
    public function getSalesRepresentativeReport(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $users = User::allUsersDropdown($business_id, false);
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.sales_representative')
            ->with(compact('users', 'business_locations'));
    }

    /**
     * Shows sales representative total expense
     *
     * @return json
     */
    public function getSalesRepresentativeTotalExpense(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->ajax()) {
            $business_id = $request->session()->get('user.business_id');

            $filters = $request->only(['expense_for', 'location_id', 'start_date', 'end_date']);

            $total_expense = $this->transactionUtil->getExpenseReport($business_id, $filters, 'total');

            return $total_expense;
        }
    }

    /**
     * Shows sales representative total sales
     *
     * @return json
     */
    public function getSalesRepresentativeTotalSell(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');
            $created_by = $request->get('created_by');

            $sell_details = $this->transactionUtil->getSellTotals($business_id, $start_date, $end_date, $location_id, $created_by);

            //Get Sell Return details
            $transaction_types = [
                'sell_return'
            ];
            $sell_return_details = $this->transactionUtil->getTransactionTotals(
                $business_id,
                $transaction_types,
                $start_date,
                $end_date,
                $location_id,
                $created_by
            );

            $total_sell_return = !empty($sell_return_details['total_sell_return_exc_tax']) ? $sell_return_details['total_sell_return_exc_tax'] : 0;
            $total_sell = $sell_details['total_sell_exc_tax'] - $total_sell_return;

            return [
                'total_sell_exc_tax' => $sell_details['total_sell_exc_tax'],
                'total_sell_return_exc_tax' => $total_sell_return,
                'total_sell' => $total_sell
            ];
        }
    }

    /**
     * Shows sales representative total commission
     *
     * @return json
     */
    public function getSalesRepresentativeTotalCommission(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $location_id = $request->get('location_id');
            $commission_agent = $request->get('commission_agent');

            $sell_details = $this->transactionUtil->getTotalSellCommission($business_id, $start_date, $end_date, $location_id, $commission_agent);

            //Get Commision
            $commission_percentage = User::find($commission_agent)->cmmsn_percent;
            $total_commission = $commission_percentage * $sell_details['total_sales_with_commission'] / 100;

            return [
                'total_sales_with_commission' =>
                $sell_details['total_sales_with_commission'],
                'total_commission' => $total_commission,
                'commission_percentage' => $commission_percentage
            ];
        }
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockExpiryReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //TODO:: Need to display reference number and edit expiry date button

        //Return the details in ajax call
        if ($request->ajax()) {
            $query = PurchaseLine::leftjoin(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->leftjoin(
                    'products as p',
                    'purchase_lines.product_id',
                    '=',
                    'p.id'
                )
                ->leftjoin(
                    'variations as v',
                    'purchase_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->leftjoin(
                    'product_variations as pv',
                    'v.product_variation_id',
                    '=',
                    'pv.id'
                )
                ->leftjoin('business_locations as l', 't.location_id', '=', 'l.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                //->whereNotNull('p.expiry_period')
                //->whereNotNull('p.expiry_period_type')
                //->whereNotNull('exp_date')
                ->where('p.enable_stock', 1);
            // ->whereRaw('purchase_lines.quantity > purchase_lines.quantity_sold + quantity_adjusted + quantity_returned');

            $permitted_locations = auth()->user()->permitted_locations();

            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');
                $query->where('t.location_id', $location_id)
                    //If filter by location then hide products not available in that location
                    ->join('product_locations as pl', 'pl.product_id', '=', 'p.id')
                    ->where(function ($q) use ($location_id) {
                        $q->where('pl.location_id', $location_id);
                    });
            }

            if (!empty($request->input('category_id'))) {
                $query->where('p.category_id', $request->input('category_id'));
            }
            if (!empty($request->input('sub_category_id'))) {
                $query->where('p.sub_category_id', $request->input('sub_category_id'));
            }
            if (!empty($request->input('brand_id'))) {
                $query->where('p.brand_id', $request->input('brand_id'));
            }
            if (!empty($request->input('unit_id'))) {
                $query->where('p.unit_id', $request->input('unit_id'));
            }
            if (!empty($request->input('exp_date_filter'))) {
                $query->whereDate('exp_date', '<=', $request->input('exp_date_filter'));
            }

            $only_mfg_products = request()->get('only_mfg_products', 0);
            if (!empty($only_mfg_products)) {
                $query->where('t.type', 'production_purchase');
            }

            $report = $query->select(
                'p.name as product',
                'p.sku',
                'p.type as product_type',
                'v.name as variation',
                'pv.name as product_variation',
                'l.name as location',
                'mfg_date',
                'exp_date',
                'u.short_name as unit',
                DB::raw("SUM(COALESCE(quantity, 0) - COALESCE(quantity_sold, 0) - COALESCE(quantity_adjusted, 0) - COALESCE(quantity_returned, 0)) as stock_left"),
                't.ref_no',
                't.id as transaction_id',
                'purchase_lines.id as purchase_line_id',
                'purchase_lines.lot_number'
            )
                ->having('stock_left', '>', 0)
                ->groupBy('purchase_lines.exp_date')
                ->groupBy('purchase_lines.lot_number');

            return Datatables::of($report)
                ->editColumn('name', function ($row) {
                    if ($row->product_type == 'variable') {
                        return $row->product . ' - ' .
                            $row->product_variation . ' - ' . $row->variation;
                    } else {
                        return $row->product;
                    }
                })
                ->editColumn('mfg_date', function ($row) {
                    if (!empty($row->mfg_date)) {
                        return $this->productUtil->format_date($row->mfg_date);
                    } else {
                        return '--';
                    }
                })
                // ->editColumn('exp_date', function ($row) {
                //     if (!empty($row->exp_date)) {
                //         $carbon_exp = \Carbon::createFromFormat('Y-m-d', $row->exp_date);
                //         $carbon_now = \Carbon::now();
                //         if ($carbon_now->diffInDays($carbon_exp, false) >= 0) {
                //             return $this->productUtil->format_date($row->exp_date) . '<br><small>( <span class="time-to-now">' . $row->exp_date . '</span> )</small>';
                //         } else {
                //             return $this->productUtil->format_date($row->exp_date) . ' &nbsp; <span class="label label-danger no-print">' . __('report.expired') . '</span><span class="print_section">' . __('report.expired') . '</span><br><small>( <span class="time-from-now">' . $row->exp_date . '</span> )</small>';
                //         }
                //     } else {
                //         return '--';
                //     }
                // })
                ->editColumn('ref_no', function ($row) {
                    return '<button type="button" data-href="' . action('PurchaseController@show', [$row->transaction_id])
                        . '" class="btn btn-link btn-modal" data-container=".view_modal"  >' . $row->ref_no . '</button>';
                })
                ->editColumn('stock_left', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency stock_left" data-currency_symbol=false data-orig-value="' . $row->stock_left . '" data-unit="' . $row->unit . '" >' . $row->stock_left . '</span> ' . $row->unit;
                })
                ->addColumn('edit', function ($row) {
                    $html =  '<button type="button" class="btn btn-primary btn-xs stock_expiry_edit_btn" data-transaction_id="' . $row->transaction_id . '" data-purchase_line_id="' . $row->purchase_line_id . '"> <i class="fa fa-edit"></i> ' . __("messages.edit") .
                        '</button>';

                    if (!empty($row->exp_date)) {
                        $carbon_exp = \Carbon::createFromFormat('Y-m-d', $row->exp_date);
                        $carbon_now = \Carbon::now();
                        if ($carbon_now->diffInDays($carbon_exp, false) < 0) {
                            $html .=  ' <button type="button" class="btn btn-warning btn-xs remove_from_stock_btn" data-href="' . action('StockAdjustmentController@removeExpiredStock', [$row->purchase_line_id]) . '"> <i class="fa fa-trash"></i> ' . __("lang_v1.remove_from_stock") .
                                '</button>';
                        }
                    }

                    return $html;
                })
                ->rawColumns(['exp_date', 'ref_no', 'edit', 'stock_left'])
                ->make(true);
        }

        $categories = Category::forDropdown($business_id, 'product');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);
        $view_stock_filter = [
            \Carbon::now()->subDay()->format('Y-m-d') => __('report.expired'),
            \Carbon::now()->addWeek()->format('Y-m-d') => __('report.expiring_in_1_week'),
            \Carbon::now()->addDays(15)->format('Y-m-d') => __('report.expiring_in_15_days'),
            \Carbon::now()->addMonth()->format('Y-m-d') => __('report.expiring_in_1_month'),
            \Carbon::now()->addMonths(3)->format('Y-m-d') => __('report.expiring_in_3_months'),
            \Carbon::now()->addMonths(6)->format('Y-m-d') => __('report.expiring_in_6_months'),
            \Carbon::now()->addYear()->format('Y-m-d') => __('report.expiring_in_1_year')
        ];

        return view('report.stock_expiry_report')
            ->with(compact('categories', 'brands', 'units', 'business_locations', 'view_stock_filter'));
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getStockExpiryReportEditModal(Request $request, $purchase_line_id)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $purchase_line = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'products as p',
                    'purchase_lines.product_id',
                    '=',
                    'p.id'
                )
                ->where('purchase_lines.id', $purchase_line_id)
                ->where('t.business_id', $business_id)
                ->select(['purchase_lines.*', 'p.name', 't.ref_no'])
                ->first();

            if (!empty($purchase_line)) {
                if (!empty($purchase_line->exp_date)) {
                    $purchase_line->exp_date = date('m/d/Y', strtotime($purchase_line->exp_date));
                }
            }

            return view('report.partials.stock_expiry_edit_modal')
                ->with(compact('purchase_line'));
        }
    }

    /**
     * Update product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStockExpiryReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            //Return the details in ajax call
            if ($request->ajax()) {
                DB::beginTransaction();

                $input = $request->only(['purchase_line_id', 'exp_date']);

                $purchase_line = PurchaseLine::join(
                    'transactions as t',
                    'purchase_lines.transaction_id',
                    '=',
                    't.id'
                )
                    ->join(
                        'products as p',
                        'purchase_lines.product_id',
                        '=',
                        'p.id'
                    )
                    ->where('purchase_lines.id', $input['purchase_line_id'])
                    ->where('t.business_id', $business_id)
                    ->select(['purchase_lines.*', 'p.name', 't.ref_no'])
                    ->first();

                if (!empty($purchase_line) && !empty($input['exp_date'])) {
                    $purchase_line->exp_date = $this->productUtil->uf_date($input['exp_date']);
                    $purchase_line->save();
                }

                DB::commit();

                $output = [
                    'success' => 1,
                    'msg' => __('lang_v1.updated_succesfully')
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return $output;
    }

    /**
     * Shows product stock expiry report
     *
     * @return \Illuminate\Http\Response
     */
    public function getCustomerGroup(Request $request)
    {
        if (!auth()->user()->can('contacts_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            $query = Transaction::leftjoin('customer_groups AS CG', 'transactions.customer_group_id', '=', 'CG.id')
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                ->groupBy('transactions.customer_group_id')
                ->select(DB::raw("SUM(final_total) as total_sell"), 'CG.name');

            $group_id = $request->get('customer_group_id', null);
            if (!empty($group_id)) {
                $query->where('transactions.customer_group_id', $group_id);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('transactions.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('transactions.location_id', $location_id);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }


            return Datatables::of($query)
                ->editColumn('total_sell', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->total_sell . '</span>';
                })
                ->rawColumns(['total_sell'])
                ->make(true);
        }

        $customer_group = CustomerGroup::forDropdown($business_id, false, true);
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.customer_group')
            ->with(compact('customer_group', 'business_locations'));
    }

    /**
     * Shows product purchase report
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductPurchaseReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = PurchaseLine::join(
                'transactions as t',
                'purchase_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'purchase_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'purchase')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'v.sub_sku',
                    'c.name as supplier',
                    't.id as transaction_id',
                    't.ref_no',
                    't.transaction_date as transaction_date',
                    'purchase_lines.purchase_price_inc_tax as unit_purchase_price',
                    DB::raw('(purchase_lines.quantity - purchase_lines.quantity_returned) as purchase_qty'),
                    'purchase_lines.quantity_adjusted',
                    'u.short_name as unit',
                    DB::raw('((purchase_lines.quantity - purchase_lines.quantity_returned - purchase_lines.quantity_adjusted) * purchase_lines.purchase_price_inc_tax) as subtotal')
                )
                ->groupBy('purchase_lines.id');
            if (!empty($variation_id)) {
                $query->where('purchase_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $supplier_id = $request->get('supplier_id', null);
            if (!empty($supplier_id)) {
                $query->where('t.contact_id', $supplier_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('ref_no', function ($row) {
                    return '<a data-href="' . action('PurchaseController@show', [$row->transaction_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->ref_no . '</a>';
                })
                ->editColumn('purchase_qty', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency purchase_qty" data-currency_symbol=false data-orig-value="' . (float)$row->purchase_qty . '" data-unit="' . $row->unit . '" >' . (float) $row->purchase_qty . '</span> ' . $row->unit;
                })
                ->editColumn('quantity_adjusted', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency quantity_adjusted" data-currency_symbol=false data-orig-value="' . (float)$row->quantity_adjusted . '" data-unit="' . $row->unit . '" >' . (float) $row->quantity_adjusted . '</span> ' . $row->unit;
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol=true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn('unit_purchase_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_purchase_price . '</span>';
                })
                ->rawColumns(['ref_no', 'unit_purchase_price', 'subtotal', 'purchase_qty', 'quantity_adjusted'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id);

        return view('report.product_purchase_report')
            ->with(compact('business_locations', 'suppliers'));
    }

    /**
     * Shows product purchase report
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductSellReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = TransactionSellLine::join(
                'transactions as t',
                'transaction_sell_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'transaction_sell_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('tax_rates', 'transaction_sell_lines.tax_id', '=', 'tax_rates.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'v.sub_sku',
                    'c.name as customer',
                    'c.contact_id',
                    't.id as transaction_id',
                    't.invoice_no',
                    't.transaction_date as transaction_date',
                    'transaction_sell_lines.unit_price_before_discount as unit_price',
                    'transaction_sell_lines.unit_price_inc_tax as unit_sale_price',
                    DB::raw('(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) as sell_qty'),
                    'transaction_sell_lines.line_discount_type as discount_type',
                    'transaction_sell_lines.line_discount_amount as discount_amount',
                    'transaction_sell_lines.item_tax',
                    'tax_rates.name as tax',
                    'u.short_name as unit',
                    DB::raw('((transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax) as subtotal')
                )
                ->groupBy('transaction_sell_lines.id');

            if (!empty($variation_id)) {
                $query->where('transaction_sell_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $customer_id = $request->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('t.contact_id', $customer_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('invoice_no', function ($row) {
                    return '<a data-href="' . action('SellController@show', [$row->transaction_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
                })
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn('unit_sale_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_sale_price . '</span>';
                })
                ->editColumn('sell_qty', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency sell_qty" data-currency_symbol=false data-orig-value="' . (float)$row->sell_qty . '" data-unit="' . $row->unit . '" >' . (float) $row->sell_qty . '</span> ' . $row->unit;
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol = true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })
                ->editColumn('unit_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_price . '</span>';
                })
                ->editColumn('discount_amount', '
                @if($discount_type == "percentage")
                {{@number_format($discount_amount)}} %
                @elseif($discount_type == "fixed")
                {{@number_format($discount_amount)}}
                @endif
                ')
                ->editColumn('tax', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' .
                        $row->item_tax .
                        '</span>' . '<br>' . '<span class="tax" data-orig-value="' . (float)$row->item_tax . '" data-unit="' . $row->tax . '"><small>(' . $row->tax . ')</small></span>';
                })
                ->rawColumns(['invoice_no', 'unit_sale_price', 'subtotal', 'sell_qty', 'discount_amount', 'unit_price', 'tax'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id);

        return view('report.product_sell_report')
            ->with(compact('business_locations', 'customers'));
    }

    /**
     * Shows product purchase report with purchase details
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductSellReportWithPurchase(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = TransactionSellLine::join(
                'transactions as t',
                'transaction_sell_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'transaction_sell_lines_purchase_lines as tspl',
                    'transaction_sell_lines.id',
                    '=',
                    'tspl.sell_line_id'
                )
                ->join(
                    'purchase_lines as pl',
                    'tspl.purchase_line_id',
                    '=',
                    'pl.id'
                )
                ->join(
                    'transactions as purchase',
                    'pl.transaction_id',
                    '=',
                    'purchase.id'
                )
                ->leftjoin('contacts as supplier', 'purchase.contact_id', '=', 'supplier.id')
                ->join(
                    'variations as v',
                    'transaction_sell_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->leftjoin('contacts as c', 't.contact_id', '=', 'c.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'v.sub_sku',
                    'c.name as customer',
                    't.id as transaction_id',
                    't.invoice_no',
                    't.transaction_date as transaction_date',
                    'tspl.quantity as purchase_quantity',
                    'u.short_name as unit',
                    'supplier.name as supplier_name',
                    'purchase.ref_no as ref_no',
                    'purchase.type as purchase_type',
                    'pl.lot_number'
                );

            if (!empty($variation_id)) {
                $query->where('transaction_sell_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(t.transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $customer_id = $request->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('t.contact_id', $customer_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('invoice_no', function ($row) {
                    return '<a data-href="' . action('SellController@show', [$row->transaction_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
                })
                ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')
                ->editColumn('unit_sale_price', function ($row) {
                    return '<span class="display_currency" data-currency_symbol = true>' . $row->unit_sale_price . '</span>';
                })
                ->editColumn('purchase_quantity', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency purchase_quantity" data-currency_symbol=false data-orig-value="' . (float)$row->purchase_quantity . '" data-unit="' . $row->unit . '" >' . (float) $row->purchase_quantity . '</span> ' . $row->unit;
                })
                ->editColumn('ref_no', '
                @if($purchase_type == "opening_stock")
                <i><small class="help-block">(@lang("lang_v1.opening_stock"))</small></i>
                @else
                {{$ref_no}}
                @endif
                ')
                ->rawColumns(['invoice_no', 'purchase_quantity', 'ref_no'])
                ->make(true);
        }
    }

    /**
     * Shows product lot report
     *
     * @return \Illuminate\Http\Response
     */
    public function getLotReport(Request $request)
    {
        if (!auth()->user()->can('stock_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        //Return the details in ajax call
        if ($request->ajax()) {
            $query = Product::where('products.business_id', $business_id)
                ->leftjoin('units', 'products.unit_id', '=', 'units.id')
                ->join('variations as v', 'products.id', '=', 'v.product_id')
                ->join('purchase_lines as pl', 'v.id', '=', 'pl.variation_id')
                ->leftjoin(
                    'transaction_sell_lines_purchase_lines as tspl',
                    'pl.id',
                    '=',
                    'tspl.purchase_line_id'
                )
                ->join('transactions as t', 'pl.transaction_id', '=', 't.id');

            $permitted_locations = auth()->user()->permitted_locations();
            $location_filter = 'WHERE ';

            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);

                $locations_imploded = implode(', ', $permitted_locations);
                $location_filter = " LEFT JOIN transactions as t2 on pls.transaction_id=t2.id WHERE t2.location_id IN ($locations_imploded) AND ";
            }

            if (!empty($request->input('location_id'))) {
                $location_id = $request->input('location_id');
                $query->where('t.location_id', $location_id)
                    //If filter by location then hide products not available in that location
                    ->ForLocation($location_id);

                $location_filter = "LEFT JOIN transactions as t2 on pls.transaction_id=t2.id WHERE t2.location_id=$location_id AND ";
            }

            if (!empty($request->input('category_id'))) {
                $query->where('products.category_id', $request->input('category_id'));
            }

            if (!empty($request->input('sub_category_id'))) {
                $query->where('products.sub_category_id', $request->input('sub_category_id'));
            }

            if (!empty($request->input('brand_id'))) {
                $query->where('products.brand_id', $request->input('brand_id'));
            }

            if (!empty($request->input('unit_id'))) {
                $query->where('products.unit_id', $request->input('unit_id'));
            }

            $only_mfg_products = request()->get('only_mfg_products', 0);
            if (!empty($only_mfg_products)) {
                $query->where('t.type', 'production_purchase');
            }

            $products = $query->select(
                'products.name as product',
                'v.name as variation_name',
                'sub_sku',
                'pl.lot_number',
                'pl.exp_date as exp_date',
                DB::raw("( COALESCE((SELECT SUM(quantity - quantity_returned) from purchase_lines as pls $location_filter variation_id = v.id AND lot_number = pl.lot_number), 0) - 
                    SUM(COALESCE((tspl.quantity - tspl.qty_returned), 0))) as stock"),
                // DB::raw("(SELECT SUM(IF(transactions.type='sell', TSL.quantity, -1* TPL.quantity) ) FROM transactions
                //         LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id

                //         LEFT JOIN purchase_lines AS TPL ON transactions.id=TPL.transaction_id

                //         WHERE transactions.status='final' AND transactions.type IN ('sell', 'sell_return') $location_filter
                //         AND (TSL.product_id=products.id OR TPL.product_id=products.id)) as total_sold"),

                DB::raw("COALESCE(SUM(IF(tspl.sell_line_id IS NULL, 0, (tspl.quantity - tspl.qty_returned)) ), 0) as total_sold"),
                DB::raw("COALESCE(SUM(IF(tspl.stock_adjustment_line_id IS NULL, 0, tspl.quantity ) ), 0) as total_adjusted"),
                'products.type',
                'units.short_name as unit'
            )
                ->whereNotNull('pl.lot_number')
                ->groupBy('v.id')
                ->groupBy('pl.lot_number');

            return Datatables::of($products)
                ->editColumn('stock', function ($row) {
                    $stock = $row->stock ? $row->stock : 0;
                    return '<span data-is_quantity="true" class="display_currency total_stock" data-currency_symbol=false data-orig-value="' . (float)$stock . '" data-unit="' . $row->unit . '" >' . (float)$stock . '</span> ' . $row->unit;
                })
                ->editColumn('product', function ($row) {
                    if ($row->variation_name != 'DUMMY') {
                        return $row->product . ' (' . $row->variation_name . ')';
                    } else {
                        return $row->product;
                    }
                })
                ->editColumn('total_sold', function ($row) {
                    if ($row->total_sold) {
                        return '<span data-is_quantity="true" class="display_currency total_sold" data-currency_symbol=false data-orig-value="' . (float)$row->total_sold . '" data-unit="' . $row->unit . '" >' . (float)$row->total_sold . '</span> ' . $row->unit;
                    } else {
                        return '0' . ' ' . $row->unit;
                    }
                })
                ->editColumn('total_adjusted', function ($row) {
                    if ($row->total_adjusted) {
                        return '<span data-is_quantity="true" class="display_currency total_adjusted" data-currency_symbol=false data-orig-value="' . (float)$row->total_adjusted . '" data-unit="' . $row->unit . '" >' . (float)$row->total_adjusted . '</span> ' . $row->unit;
                    } else {
                        return '0' . ' ' . $row->unit;
                    }
                })
                ->editColumn('exp_date', function ($row) {
                    if (!empty($row->exp_date)) {
                        $carbon_exp = \Carbon::createFromFormat('Y-m-d', $row->exp_date);
                        $carbon_now = \Carbon::now();
                        if ($carbon_now->diffInDays($carbon_exp, false) >= 0) {
                            return $this->productUtil->format_date($row->exp_date) . '<br><small>( <span class="time-to-now">' . $row->exp_date . '</span> )</small>';
                        } else {
                            return $this->productUtil->format_date($row->exp_date) . ' &nbsp; <span class="label label-danger no-print">' . __('report.expired') . '</span><span class="print_section">' . __('report.expired') . '</span><br><small>( <span class="time-from-now">' . $row->exp_date . '</span> )</small>';
                        }
                    } else {
                        return '--';
                    }
                })
                ->removeColumn('unit')
                ->removeColumn('id')
                ->removeColumn('variation_name')
                ->rawColumns(['exp_date', 'stock', 'total_sold', 'total_adjusted'])
                ->make(true);
        }

        $categories = Category::forDropdown($business_id, 'product');
        $brands = Brands::where('business_id', $business_id)
            ->pluck('name', 'id');
        $units = Unit::where('business_id', $business_id)
            ->pluck('short_name', 'id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.lot_report')
            ->with(compact('categories', 'brands', 'units', 'business_locations'));
    }

    /**
     * Shows purchase payment report
     *
     * @return \Illuminate\Http\Response
     */
    public function purchasePaymentReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $supplier_id = $request->get('supplier_id', null);
            $contact_filter1 = !empty($supplier_id) ? "AND t.contact_id=$supplier_id" : '';
            $contact_filter2 = !empty($supplier_id) ? "AND transactions.contact_id=$supplier_id" : '';

            $location_id = $request->get('location_id', null);

            $parent_payment_query_part = empty($location_id) ? "AND transaction_payments.parent_id IS NULL" : "";

            $query = TransactionPayment::leftjoin('transactions as t', function ($join) use ($business_id) {
                $join->on('transaction_payments.transaction_id', '=', 't.id')
                    ->where('t.business_id', $business_id)
                    ->whereIn('t.type', ['purchase', 'opening_balance']);
            })
                ->where('transaction_payments.business_id', $business_id)
                ->where(function ($q) use ($business_id, $contact_filter1, $contact_filter2, $parent_payment_query_part) {
                    $q->whereRaw("(transaction_payments.transaction_id IS NOT NULL AND t.type IN ('purchase', 'opening_balance')  $parent_payment_query_part $contact_filter1)")
                        ->orWhereRaw("EXISTS(SELECT * FROM transaction_payments as tp JOIN transactions ON tp.transaction_id = transactions.id WHERE transactions.type IN ('purchase', 'opening_balance') AND transactions.business_id = $business_id AND tp.parent_id=transaction_payments.id $contact_filter2)");
                })

                ->select(
                    DB::raw("IF(transaction_payments.transaction_id IS NULL, 
                    (SELECT c.name FROM transactions as ts
                    JOIN contacts as c ON ts.contact_id=c.id 
                    WHERE ts.id=(
                    SELECT tps.transaction_id FROM transaction_payments as tps
                    WHERE tps.parent_id=transaction_payments.id LIMIT 1
                    )
                    ),
                    (SELECT c.name FROM transactions as ts JOIN
                    contacts as c ON ts.contact_id=c.id
                    WHERE ts.id=t.id 
                    )
                ) as supplier"),
                    'transaction_payments.amount',
                    'method',
                    'paid_on',
                    'transaction_payments.payment_ref_no',
                    'transaction_payments.document',
                    't.ref_no',
                    't.id as transaction_id',
                    'cheque_number',
                    'card_transaction_number',
                    'bank_account_number',
                    'transaction_no',
                    'transaction_payments.id as DT_RowId'
                )
                ->groupBy('transaction_payments.id');

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $payment_types = $this->transactionUtil->payment_types();

            return Datatables::of($query)
                ->editColumn('ref_no', function ($row) {
                    if (!empty($row->ref_no)) {
                        return '<a data-href="' . action('PurchaseController@show', [$row->transaction_id])
                            . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->ref_no . '</a>';
                    } else {
                        return '';
                    }
                })
                ->editColumn('paid_on', '{{@format_datetime($paid_on)}}')
                ->editColumn('method', function ($row) use ($payment_types) {
                    $method = !empty($payment_types[$row->method]) ? $payment_types[$row->method] : '';
                    if ($row->method == 'cheque') {
                        $method .= '<br>(' . __('lang_v1.cheque_no') . ': ' . $row->cheque_number . ')';
                    } elseif ($row->method == 'card') {
                        $method .= '<br>(' . __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number . ')';
                    } elseif ($row->method == 'bank_transfer') {
                        $method .= '<br>(' . __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number . ')';
                    } elseif ($row->method == 'custom_pay_1') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_2') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_3') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    }
                    return $method;
                })
                ->editColumn('amount', function ($row) {
                    return '<span class="display_currency paid-amount" data-currency_symbol = true data-orig-value="' . $row->amount . '">' . $row->amount . '</span>';
                })
                ->addColumn('action', '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="{{ action("TransactionPaymentController@viewPayment", [$DT_RowId]) }}">@lang("messages.view")
                </button> @if(!empty($document))<a href="{{asset("/uploads/documents/" . $document)}}" class="btn btn-success btn-xs" download=""><i class="fa fa-download"></i> @lang("purchase.download_document")</a>@endif')
                ->rawColumns(['ref_no', 'amount', 'method', 'action'])
                ->make(true);
        }
        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id, false);

        return view('report.purchase_payment_report')
            ->with(compact('business_locations', 'suppliers'));
    }

    /**
     * Shows sell payment report
     *
     * @return \Illuminate\Http\Response
     */
    public function sellPaymentReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        if ($request->ajax()) {
            $customer_id = $request->get('supplier_id', null);
            $contact_filter1 = !empty($customer_id) ? "AND t.contact_id=$customer_id" : '';
            $contact_filter2 = !empty($customer_id) ? "AND transactions.contact_id=$customer_id" : '';

            $location_id = $request->get('location_id', null);
            $parent_payment_query_part = empty($location_id) ? "AND transaction_payments.parent_id IS NULL" : "";

            $query = TransactionPayment::leftjoin('transactions as t', function ($join) use ($business_id) {
                $join->on('transaction_payments.transaction_id', '=', 't.id')
                    ->where('t.business_id', $business_id)
                    ->whereIn('t.type', ['sell', 'opening_balance']);
            })
                ->leftjoin('contacts as c', 't.contact_id', '=', 'c.id')
                ->leftjoin('customer_groups AS CG', 'c.customer_group_id', '=', 'CG.id')
                ->where('transaction_payments.business_id', $business_id)
                ->where(function ($q) use ($business_id, $contact_filter1, $contact_filter2, $parent_payment_query_part) {
                    $q->whereRaw("(transaction_payments.transaction_id IS NOT NULL AND t.type IN ('sell', 'opening_balance') $parent_payment_query_part $contact_filter1)")
                        ->orWhereRaw("EXISTS(SELECT * FROM transaction_payments as tp JOIN transactions ON tp.transaction_id = transactions.id WHERE transactions.type IN ('sell', 'opening_balance') AND transactions.business_id = $business_id AND tp.parent_id=transaction_payments.id $contact_filter2)");
                })
                ->select(
                    DB::raw("IF(transaction_payments.transaction_id IS NULL, 
                    (SELECT c.name FROM transactions as ts
                    JOIN contacts as c ON ts.contact_id=c.id 
                    WHERE ts.id=(
                    SELECT tps.transaction_id FROM transaction_payments as tps
                    WHERE tps.parent_id=transaction_payments.id LIMIT 1
                    )
                    ),
                    (SELECT c.name FROM transactions as ts JOIN
                    contacts as c ON ts.contact_id=c.id
                    WHERE ts.id=t.id 
                    )
                ) as customer"),
                    'transaction_payments.amount',
                    'transaction_payments.is_return',
                    'method',
                    'paid_on',
                    'transaction_payments.payment_ref_no',
                    'transaction_payments.document',
                    'transaction_payments.transaction_no',
                    't.invoice_no',
                    't.id as transaction_id',
                    'cheque_number',
                    'card_transaction_number',
                    'bank_account_number',
                    'transaction_payments.id as DT_RowId',
                    'CG.name as customer_group'
                )
                ->groupBy('transaction_payments.id');

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(paid_on)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($request->get('customer_group_id'))) {
                $query->where('CG.id', $request->get('customer_group_id'));
            }

            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            if (!empty($request->get('payment_types'))) {
                $query->where('transaction_payments.method', $request->get('payment_types'));
            }
            $payment_types = $this->transactionUtil->payment_types();
            return Datatables::of($query)
                ->editColumn('invoice_no', function ($row) {
                    if (!empty($row->transaction_id)) {
                        return '<a data-href="' . action('SellController@show', [$row->transaction_id])
                            . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
                    } else {
                        return '';
                    }
                })
                ->editColumn('paid_on', '{{@format_datetime($paid_on)}}')
                ->editColumn('method', function ($row) use ($payment_types) {
                    $method = !empty($payment_types[$row->method]) ? $payment_types[$row->method] : '';
                    if ($row->method == 'cheque') {
                        $method .= '<br>(' . __('lang_v1.cheque_no') . ': ' . $row->cheque_number . ')';
                    } elseif ($row->method == 'card') {
                        $method .= '<br>(' . __('lang_v1.card_transaction_no') . ': ' . $row->card_transaction_number . ')';
                    } elseif ($row->method == 'bank_transfer') {
                        $method .= '<br>(' . __('lang_v1.bank_account_no') . ': ' . $row->bank_account_number . ')';
                    } elseif ($row->method == 'custom_pay_1') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_2') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    } elseif ($row->method == 'custom_pay_3') {
                        $method .= '<br>(' . __('lang_v1.transaction_no') . ': ' . $row->transaction_no . ')';
                    }
                    if ($row->is_return == 1) {
                        $method .= '<br><small>(' . __('lang_v1.change_return') . ')</small>';
                    }
                    return $method;
                })
                ->editColumn('amount', function ($row) {
                    $amount = $row->is_return == 1 ? -1 * $row->amount : $row->amount;
                    return '<span class="display_currency paid-amount" data-orig-value="' . $amount . '" data-currency_symbol = true>' . $amount . '</span>';
                })
                ->addColumn('action', '<button type="button" class="btn btn-primary btn-xs view_payment" data-href="{{ action("TransactionPaymentController@viewPayment", [$DT_RowId]) }}">@lang("messages.view")
                </button> @if(!empty($document))<a href="{{asset("/uploads/documents/" . $document)}}" class="btn btn-success btn-xs" download=""><i class="fa fa-download"></i> @lang("purchase.download_document")</a>@endif')
                ->rawColumns(['invoice_no', 'amount', 'method', 'action'])
                ->make(true);
        }
        $business_locations = BusinessLocation::forDropdown($business_id);
        $customers = Contact::customersDropdown($business_id, false);
        $payment_types = $this->transactionUtil->payment_types();
        $customer_groups = CustomerGroup::forDropdown($business_id, false, true);

        return view('report.sell_payment_report')
            ->with(compact('business_locations', 'customers', 'payment_types', 'customer_groups'));
    }


    /**
     * Shows tables report
     *
     * @return \Illuminate\Http\Response
     */
    public function getTableReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        if ($request->ajax()) {
            $query = ResTable::leftjoin('transactions AS T', 'T.res_table_id', '=', 'res_tables.id')
                ->where('T.business_id', $business_id)
                ->where('T.type', 'sell')
                ->where('T.status', 'final')
                ->groupBy('res_tables.id')
                ->select(DB::raw("SUM(final_total) as total_sell"), 'res_tables.name as table');

            $location_id = $request->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('T.location_id', $location_id);
            }

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            return Datatables::of($query)
                ->editColumn('total_sell', function ($row) {
                    return '<span class="display_currency" data-currency_symbol="true">' . $row->total_sell . '</span>';
                })
                ->rawColumns(['total_sell'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('report.table_report')
            ->with(compact('business_locations'));
    }

    /**
     * Shows service staff report
     *
     * @return \Illuminate\Http\Response
     */
    public function getServiceStaffReport(Request $request)
    {
        if (!auth()->user()->can('sales_representative.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        $waiters = $this->transactionUtil->serviceStaffDropdown($business_id);

        return view('report.service_staff_report')
            ->with(compact('business_locations', 'waiters'));
    }

    /**
     * Shows product sell report grouped by date
     *
     * @return \Illuminate\Http\Response
     */
    public function getproductSellGroupedReport(Request $request)
    {
        if (!auth()->user()->can('purchase_n_sell_report.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');
        $location_id = $request->get('location_id', null);

        $vld_str = '';
        if (!empty($location_id)) {
            $vld_str = "AND vld.location_id=$location_id";
        }

        if ($request->ajax()) {
            $variation_id = $request->get('variation_id', null);
            $query = TransactionSellLine::join(
                'transactions as t',
                'transaction_sell_lines.transaction_id',
                '=',
                't.id'
            )
                ->join(
                    'variations as v',
                    'transaction_sell_lines.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('products as p', 'pv.product_id', '=', 'p.id')
                ->leftjoin('units as u', 'p.unit_id', '=', 'u.id')
                ->where('t.business_id', $business_id)
                ->where('t.type', 'sell')
                ->where('t.status', 'final')
                ->select(
                    'p.name as product_name',
                    'p.enable_stock',
                    'p.type as product_type',
                    'pv.name as product_variation',
                    'v.name as variation_name',
                    'v.sub_sku',
                    't.id as transaction_id',
                    't.transaction_date as transaction_date',
                    DB::raw('DATE_FORMAT(t.transaction_date, "%Y-%m-%d") as formated_date'),
                    DB::raw("(SELECT SUM(vld.qty_available) FROM variation_location_details as vld WHERE vld.variation_id=v.id $vld_str) as current_stock"),
                    DB::raw('SUM(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) as total_qty_sold'),
                    'u.short_name as unit',
                    DB::raw('SUM((transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax) as subtotal')
                )
                ->groupBy('v.id')
                ->groupBy('formated_date');

            if (!empty($variation_id)) {
                $query->where('transaction_sell_lines.variation_id', $variation_id);
            }
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (!empty($start_date) && !empty($end_date)) {
                $query->whereBetween(DB::raw('date(transaction_date)'), [$start_date, $end_date]);
            }

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $query->whereIn('t.location_id', $permitted_locations);
            }

            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }

            $customer_id = $request->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('t.contact_id', $customer_id);
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('transaction_date', '{{@format_date($formated_date)}}')
                ->editColumn('total_qty_sold', function ($row) {
                    return '<span data-is_quantity="true" class="display_currency sell_qty" data-currency_symbol=false data-orig-value="' . (float)$row->total_qty_sold . '" data-unit="' . $row->unit . '" >' . (float) $row->total_qty_sold . '</span> ' . $row->unit;
                })
                ->editColumn('current_stock', function ($row) {
                    if ($row->enable_stock) {
                        return '<span data-is_quantity="true" class="display_currency current_stock" data-currency_symbol=false data-orig-value="' . (float)$row->current_stock . '" data-unit="' . $row->unit . '" >' . (float) $row->current_stock . '</span> ' . $row->unit;
                    } else {
                        return '';
                    }
                })
                ->editColumn('subtotal', function ($row) {
                    return '<span class="display_currency row_subtotal" data-currency_symbol = true data-orig-value="' . $row->subtotal . '">' . $row->subtotal . '</span>';
                })

                ->rawColumns(['current_stock', 'subtotal', 'total_qty_sold'])
                ->make(true);
        }
    }

    /**
     * Shows product stock details and allows to adjust mismatch
     *
     * @return \Illuminate\Http\Response
     */
    public function productStockDetails()
    {
        if (!auth()->user()->can('report.stock_details')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $stock_details = [];
        $location = null;
        $total_stock_calculated = 0;
        if (!empty(request()->input('location_id'))) {
            $variation_id = request()->get('variation_id', null);
            $location_id = request()->input('location_id');

            $location = BusinessLocation::where('business_id', $business_id)
                ->where('id', $location_id)
                ->first();

            $query = Variation::leftjoin('products as p', 'p.id', '=', 'variations.product_id')
                ->leftjoin('units', 'p.unit_id', '=', 'units.id')
                ->leftjoin('variation_location_details as vld', 'variations.id', '=', 'vld.variation_id')
                ->leftjoin('product_variations as pv', 'variations.product_variation_id', '=', 'pv.id')
                ->where('p.business_id', $business_id)
                ->where('vld.location_id', $location_id);
            if (!is_null($variation_id)) {
                $query->where('variations.id', $variation_id);
            }

            $stock_details = $query->select(
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity, 0)) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell' AND transactions.location_id=$location_id 
                    AND TSL.variation_id=variations.id) as total_sold"),
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity_returned, 0)) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell' AND transactions.location_id=$location_id 
                    AND TSL.variation_id=variations.id) as total_sell_return"),
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity,0)) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='sell_transfer' AND transactions.location_id=$location_id 
                    AND TSL.variation_id=variations.id) as total_sell_transfered"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity,0)) FROM transactions 
                    LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='purchase_transfer' AND transactions.location_id=$location_id 
                    AND PL.variation_id=variations.id) as total_purchase_transfered"),
                DB::raw("(SELECT SUM(COALESCE(SAL.quantity, 0)) FROM transactions 
                    LEFT JOIN stock_adjustment_lines AS SAL ON transactions.id=SAL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='stock_adjustment' AND transactions.location_id=$location_id 
                    AND SAL.variation_id=variations.id) as total_adjusted"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity, 0)) FROM transactions 
                    LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='purchase' AND transactions.location_id=$location_id
                    AND PL.variation_id=variations.id) as total_purchased"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity_returned, 0)) FROM transactions 
                    LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='purchase' AND transactions.location_id=$location_id
                    AND PL.variation_id=variations.id) as total_purchase_return"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity, 0)) FROM transactions 
                    LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='opening_stock' AND transactions.location_id=$location_id
                    AND PL.variation_id=variations.id) as total_opening_stock"),
                DB::raw("(SELECT SUM(COALESCE(PL.quantity, 0)) FROM transactions 
                    LEFT JOIN purchase_lines AS PL ON transactions.id=PL.transaction_id
                    WHERE transactions.status='received' AND transactions.type='production_purchase' AND transactions.location_id=$location_id
                    AND PL.variation_id=variations.id) as total_manufactured"),
                DB::raw("(SELECT SUM(COALESCE(TSL.quantity, 0)) FROM transactions 
                    LEFT JOIN transaction_sell_lines AS TSL ON transactions.id=TSL.transaction_id
                    WHERE transactions.status='final' AND transactions.type='production_sell' AND transactions.location_id=$location_id 
                    AND TSL.variation_id=variations.id) as total_ingredients_used"),
                DB::raw("SUM(vld.qty_available) as stock"),
                'variations.sub_sku as sub_sku',
                'p.name as product',
                'p.id as product_id',
                'p.type',
                'p.sku as sku',
                'units.short_name as unit',
                'p.enable_stock as enable_stock',
                'variations.sell_price_inc_tax as unit_price',
                'pv.name as product_variation',
                'variations.name as variation_name',
                'variations.id as variation_id'
            )
                ->groupBy('variations.id')
                ->get();

            foreach ($stock_details as $index => $row) {
                $total_sold = $row->total_sold ?: 0;
                $total_sell_return = $row->total_sell_return ?: 0;
                $total_sell_transfered = $row->total_sell_transfered ?: 0;

                $total_purchase_transfered = $row->total_purchase_transfered ?: 0;
                $total_adjusted = $row->total_adjusted ?: 0;
                $total_purchased = $row->total_purchased ?: 0;
                $total_purchase_return = $row->total_purchase_return ?: 0;
                $total_opening_stock = $row->total_opening_stock ?: 0;
                $total_manufactured = $row->total_manufactured ?: 0;
                $total_ingredients_used = $row->total_ingredients_used ?: 0;

                $total_stock_calculated = $total_opening_stock + $total_purchased + $total_purchase_transfered + $total_sell_return + $total_manufactured
                    - ($total_sold + $total_sell_transfered + $total_adjusted + $total_purchase_return + $total_ingredients_used);

                $stock_details[$index]->total_stock_calculated = $total_stock_calculated;
            }
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        return view('report.product_stock_details')
            ->with(compact('stock_details', 'business_locations', 'location'));
    }

    /**
     * Adjusts stock availability mismatch if found
     *
     * @return \Illuminate\Http\Response
     */
    public function adjustProductStock()
    {
        if (!auth()->user()->can('report.stock_details')) {
            abort(403, 'Unauthorized action.');
        }

        if (
            !empty(request()->input('variation_id'))
            && !empty(request()->input('location_id'))
            && request()->has('stock')
        ) {
            $business_id = request()->session()->get('user.business_id');

            $vld = VariationLocationDetails::leftjoin(
                'business_locations as bl',
                'bl.id',
                '=',
                'variation_location_details.location_id'
            )
                ->where('variation_location_details.location_id', request()->input('location_id'))
                ->where('variation_id', request()->input('variation_id'))
                ->where('bl.business_id', $business_id)
                ->select('variation_location_details.*')
                ->first();

            if (!empty($vld)) {
                $vld->qty_available = request()->input('stock');
                $vld->save();
            }
        }

        return redirect()->back()->with(['status' => [
            'success' => 1,
            'msg' => __('lang_v1.updated_succesfully')
        ]]);
    }

    /**
     * Retrieves line orders/sales
     *
     * @return obj
     */
    public function serviceStaffLineOrders()
    {
        $business_id = request()->session()->get('user.business_id');

        $query = TransactionSellLine::leftJoin('transactions as t', 't.id', '=', 'transaction_sell_lines.transaction_id')
            ->leftJoin('variations as v', 'transaction_sell_lines.variation_id', '=', 'v.id')
            ->leftJoin('products as p', 'v.product_id', '=', 'p.id')
            ->leftJoin('units as u', 'p.unit_id', '=', 'u.id')
            ->leftJoin('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
            ->leftJoin('users as ss', 'ss.id', '=', 'transaction_sell_lines.res_service_staff_id')
            ->leftjoin(
                'business_locations AS bl',
                't.location_id',
                '=',
                'bl.id'
            )
            ->where('t.business_id', $business_id)
            ->where('t.type', 'sell')
            ->where('t.status', 'final')
            ->whereNotNull('transaction_sell_lines.res_service_staff_id');


        if (!empty(request()->service_staff_id)) {
            $query->where('transaction_sell_lines.res_service_staff_id', request()->service_staff_id);
        }

        if (request()->has('location_id')) {
            $location_id = request()->get('location_id');
            if (!empty($location_id)) {
                $query->where('t.location_id', $location_id);
            }
        }

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $start = request()->start_date;
            $end =  request()->end_date;
            $query->whereDate('t.transaction_date', '>=', $start)
                ->whereDate('t.transaction_date', '<=', $end);
        }

        $query->select(
            'p.name as product_name',
            'p.type as product_type',
            'v.name as variation_name',
            'pv.name as product_variation_name',
            'u.short_name as unit',
            't.id as transaction_id',
            'bl.name as business_location',
            't.transaction_date',
            't.invoice_no',
            'transaction_sell_lines.quantity',
            'transaction_sell_lines.unit_price_before_discount',
            'transaction_sell_lines.line_discount_type',
            'transaction_sell_lines.line_discount_amount',
            'transaction_sell_lines.item_tax',
            'transaction_sell_lines.unit_price_inc_tax',
            DB::raw('CONCAT(COALESCE(ss.first_name, ""), COALESCE(ss.last_name, "")) as service_staff')
        );

        $datatable = Datatables::of($query)
            ->editColumn('product_name', function ($row) {
                $name = $row->product_name;
                if ($row->product_type == 'variable') {
                    $name .= ' - ' . $row->product_variation_name . ' - ' . $row->variation_name;
                }
                return $name;
            })
            ->editColumn(
                'unit_price_inc_tax',
                '<span class="display_currency unit_price_inc_tax" data-currency_symbol="true" data-orig-value="{{$unit_price_inc_tax}}">{{$unit_price_inc_tax}}</span>'
            )
            ->editColumn(
                'item_tax',
                '<span class="display_currency item_tax" data-currency_symbol="true" data-orig-value="{{$item_tax}}">{{$item_tax}}</span>'
            )
            ->editColumn(
                'quantity',
                '<span class="display_currency quantity" data-unit="{{$unit}}" data-currency_symbol="false" data-orig-value="{{$quantity}}">{{$quantity}}</span> {{$unit}}'
            )
            ->editColumn(
                'unit_price_before_discount',
                '<span class="display_currency unit_price_before_discount" data-currency_symbol="true" data-orig-value="{{$unit_price_before_discount}}">{{$unit_price_before_discount}}</span>'
            )
            ->addColumn(
                'total',
                '<span class="display_currency total" data-currency_symbol="true" data-orig-value="{{$unit_price_inc_tax * $quantity}}">{{$unit_price_inc_tax * $quantity}}</span>'
            )
            ->editColumn(
                'line_discount_amount',
                function ($row) {
                    $discount = !empty($row->line_discount_amount) ? $row->line_discount_amount : 0;

                    if (!empty($discount) && $row->line_discount_type == 'percentage') {
                        $discount = $row->unit_price_before_discount * ($discount / 100);
                    }

                    return '<span class="display_currency total-discount" data-currency_symbol="true" data-orig-value="' . $discount . '">' . $discount . '</span>';
                }
            )
            ->editColumn('transaction_date', '{{@format_date($transaction_date)}}')

            ->rawColumns(['line_discount_amount', 'unit_price_before_discount', 'item_tax', 'unit_price_inc_tax', 'item_tax', 'quantity', 'total'])
            ->make(true);

        return $datatable;
    }

    /**
     * Lists profit by product, category, brand, location, invoice and date
     *
     * @return string $by = null
     */
    public function getProfit($by = null)
    {
        $business_id = request()->session()->get('user.business_id');

        $query = TransactionSellLine
            ::join('transactions as sale', 'transaction_sell_lines.transaction_id', '=', 'sale.id')
            ->leftjoin('transaction_sell_lines_purchase_lines as TSPL', 'transaction_sell_lines.id', '=', 'TSPL.sell_line_id')
            ->leftjoin(
                'purchase_lines as PL',
                'TSPL.purchase_line_id',
                '=',
                'PL.id'
            )
            ->join('products as P', 'transaction_sell_lines.product_id', '=', 'P.id')
            ->where('sale.business_id', $business_id)
            ->where('transaction_sell_lines.children_type', '!=', 'combo');
        //If type combo: find childrens, sale price parent - get PP of childrens
        $query->select(DB::raw('SUM(IF (TSPL.id IS NULL AND P.type="combo", ( 
            SELECT Sum((tspl2.quantity - tspl2.qty_returned) * (tsl.unit_price_inc_tax - pl2.purchase_price_inc_tax)) AS total
            FROM transaction_sell_lines AS tsl
            JOIN transaction_sell_lines_purchase_lines AS tspl2
            ON tsl.id=tspl2.sell_line_id 
            JOIN purchase_lines AS pl2 
            ON tspl2.purchase_line_id = pl2.id 
            WHERE tsl.parent_sell_line_id = transaction_sell_lines.id), IF(P.enable_stock=0,(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax,   
            (TSPL.quantity - TSPL.qty_returned) * (transaction_sell_lines.unit_price_inc_tax - PL.purchase_price_inc_tax)) )) AS gross_profit'));

        if (!empty(request()->start_date) && !empty(request()->end_date)) {
            $start = request()->start_date;
            $end =  request()->end_date;
            $query->whereDate('sale.transaction_date', '>=', $start)
                ->whereDate('sale.transaction_date', '<=', $end);
        }

        if ($by == 'product') {
            $query->join('variations as V', 'transaction_sell_lines.variation_id', '=', 'V.id')
                ->leftJoin('product_variations as PV', 'PV.id', '=', 'V.product_variation_id')
                ->addSelect(DB::raw("IF(P.type='variable', CONCAT(P.name, ' - ', PV.name, ' - ', V.name, ' (', V.sub_sku, ')'), CONCAT(P.name, ' (', P.sku, ')')) as product"))
                ->groupBy('V.id');
        }

        if ($by == 'category') {
            $query->join('variations as V', 'transaction_sell_lines.variation_id', '=', 'V.id')
                ->leftJoin('categories as C', 'C.id', '=', 'P.category_id')
                ->addSelect("C.name as category")
                ->groupBy('C.id');
        }

        if ($by == 'brand') {
            $query->join('variations as V', 'transaction_sell_lines.variation_id', '=', 'V.id')
                ->leftJoin('brands as B', 'B.id', '=', 'P.brand_id')
                ->addSelect("B.name as brand")
                ->groupBy('B.id');
        }

        if ($by == 'location') {
            $query->join('business_locations as L', 'sale.location_id', '=', 'L.id')
                ->addSelect("L.name as location")
                ->groupBy('L.id');
        }

        if ($by == 'invoice') {
            $query->addSelect('sale.invoice_no', 'sale.id as transaction_id')
                ->groupBy('sale.invoice_no');
        }

        if ($by == 'date') {
            $query->addSelect("sale.transaction_date")
                ->groupBy(DB::raw('DATE(sale.transaction_date)'));
        }

        if ($by == 'day') {
            $results = $query->addSelect(DB::raw("DAYNAME(sale.transaction_date) as day"))
                ->groupBy(DB::raw('DAYOFWEEK(sale.transaction_date)'))
                ->get();

            $profits = [];
            foreach ($results as $result) {
                $profits[strtolower($result->day)] = $result->gross_profit;
            }
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            return view('report.partials.profit_by_day')->with(compact('profits', 'days'));
        }

        if ($by == 'customer') {
            $query->join('contacts as CU', 'sale.contact_id', '=', 'CU.id')
                ->addSelect("CU.name as customer")
                ->groupBy('sale.contact_id');
        }

        $datatable = Datatables::of($query)
            ->editColumn(
                'gross_profit',
                '<span class="display_currency gross-profit" data-currency_symbol="true" data-orig-value="{{$gross_profit}}">{{$gross_profit}}</span>'
            );

        if ($by == 'category') {
            $datatable->editColumn(
                'category',
                '{{$category ?? __("lang_v1.uncategorized")}}'
            );
        }
        if ($by == 'brand') {
            $datatable->editColumn(
                'brand',
                '{{$brand ?? __("report.others")}}'
            );
        }

        if ($by == 'date') {
            $datatable->editColumn('transaction_date', '{{@format_date($transaction_date)}}');
        }

        $raw_columns = ['gross_profit'];
        if ($by == 'invoice') {
            $datatable->editColumn('invoice_no', function ($row) {
                return '<a data-href="' . action('SellController@show', [$row->transaction_id])
                    . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->invoice_no . '</a>';
            });
            $raw_columns[] = 'invoice_no';
        }
        return $datatable->rawColumns($raw_columns)
            ->make(true);
    }

    /**
     * Shows items report from sell purchase mapping table
     *
     * @return \Illuminate\Http\Response
     */
    public function itemsReport()
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $query = TransactionSellLinesPurchaseLines::leftJoin('transaction_sell_lines 
                as SL', 'SL.id', '=', 'transaction_sell_lines_purchase_lines.sell_line_id')
                ->leftJoin('stock_adjustment_lines 
                as SAL', 'SAL.id', '=', 'transaction_sell_lines_purchase_lines.stock_adjustment_line_id')
                ->leftJoin('transactions as sale', 'SL.transaction_id', '=', 'sale.id')
                ->leftJoin('transactions as stock_adjustment', 'SAL.transaction_id', '=', 'stock_adjustment.id')
                ->join('purchase_lines as PL', 'PL.id', '=', 'transaction_sell_lines_purchase_lines.purchase_line_id')
                ->join('transactions as purchase', 'PL.transaction_id', '=', 'purchase.id')
                ->join('business_locations as bl', 'purchase.location_id', '=', 'bl.id')
                ->join(
                    'variations as v',
                    'PL.variation_id',
                    '=',
                    'v.id'
                )
                ->join('product_variations as pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('products as p', 'PL.product_id', '=', 'p.id')
                ->join('units as u', 'p.unit_id', '=', 'u.id')
                ->leftJoin('contacts as suppliers', 'purchase.contact_id', '=', 'suppliers.id')
                ->leftJoin('contacts as customers', 'sale.contact_id', '=', 'customers.id')
                ->where('purchase.business_id', $business_id)
                ->select(
                    'v.sub_sku as sku',
                    'p.type as product_type',
                    'p.name as product_name',
                    'v.name as variation_name',
                    'pv.name as product_variation',
                    'u.short_name as unit',
                    'purchase.transaction_date as purchase_date',
                    'purchase.ref_no as purchase_ref_no',
                    'purchase.type as purchase_type',
                    'suppliers.name as supplier',
                    'PL.purchase_price_inc_tax as purchase_price',
                    'sale.transaction_date as sell_date',
                    'stock_adjustment.transaction_date as stock_adjustment_date',
                    'sale.invoice_no as sale_invoice_no',
                    'stock_adjustment.ref_no as stock_adjustment_ref_no',
                    'customers.name as customer',
                    'transaction_sell_lines_purchase_lines.quantity as quantity',
                    'SL.unit_price_inc_tax as selling_price',
                    'SAL.unit_price as stock_adjustment_price',
                    'transaction_sell_lines_purchase_lines.stock_adjustment_line_id',
                    'transaction_sell_lines_purchase_lines.sell_line_id',
                    'transaction_sell_lines_purchase_lines.purchase_line_id',
                    'transaction_sell_lines_purchase_lines.qty_returned',
                    'bl.name as location'
                );

            if (!empty(request()->purchase_start) && !empty(request()->purchase_end)) {
                $start = request()->purchase_start;
                $end =  request()->purchase_end;
                $query->whereDate('purchase.transaction_date', '>=', $start)
                    ->whereDate('purchase.transaction_date', '<=', $end);
            }
            if (!empty(request()->sale_start) && !empty(request()->sale_end)) {
                $start = request()->sale_start;
                $end =  request()->sale_end;
                $query->where(function ($q) use ($start, $end) {
                    $q->where(function ($qr) use ($start, $end) {
                        $qr->whereDate('sale.transaction_date', '>=', $start)
                            ->whereDate('sale.transaction_date', '<=', $end);
                    })->orWhere(function ($qr) use ($start, $end) {
                        $qr->whereDate('stock_adjustment.transaction_date', '>=', $start)
                            ->whereDate('stock_adjustment.transaction_date', '<=', $end);
                    });
                });
            }

            $supplier_id = request()->get('supplier_id', null);
            if (!empty($supplier_id)) {
                $query->where('suppliers.id', $supplier_id);
            }

            $customer_id = request()->get('customer_id', null);
            if (!empty($customer_id)) {
                $query->where('customers.id', $customer_id);
            }

            $location_id = request()->get('location_id', null);
            if (!empty($location_id)) {
                $query->where('purchase.location_id', $location_id);
            }

            $only_mfg_products = request()->get('only_mfg_products', 0);
            if (!empty($only_mfg_products)) {
                $query->where('purchase.type', 'production_purchase');
            }

            return Datatables::of($query)
                ->editColumn('product_name', function ($row) {
                    $product_name = $row->product_name;
                    if ($row->product_type == 'variable') {
                        $product_name .= ' - ' . $row->product_variation . ' - ' . $row->variation_name;
                    }

                    return $product_name;
                })
                ->editColumn('purchase_date', '{{@format_datetime($purchase_date)}}')
                ->editColumn('purchase_ref_no', function ($row) {
                    $html = $row->purchase_type == 'purchase' ? '<a data-href="' . action('PurchaseController@show', [$row->purchase_line_id])
                        . '" href="#" data-container=".view_modal" class="btn-modal">' . $row->purchase_ref_no . '</a>' : $row->purchase_ref_no;
                    if ($row->purchase_type == 'opening_stock') {
                        $html .= '(' . __('lang_v1.opening_stock') . ')';
                    }
                    return $html;
                })
                ->editColumn('purchase_price', function ($row) {
                    return '<span class="display_currency purchase_price" data-currency_symbol=true data-orig-value="' . $row->purchase_price . '">' . $row->purchase_price . '</span>';
                })
                ->editColumn('sell_date', '@if(!empty($sell_line_id)) {{@format_datetime($sell_date)}} @else {{@format_datetime($stock_adjustment_date)}} @endif')

                ->editColumn('sale_invoice_no', function ($row) {
                    $invoice_no = !empty($row->sell_line_id) ? $row->sale_invoice_no : $row->stock_adjustment_ref_no . '<br><small>(' . __('stock_adjustment.stock_adjustment') . '</small>';

                    return $invoice_no;
                })
                ->editColumn('quantity', function ($row) {
                    $html = '<span data-is_quantity="true" class="display_currency quantity" data-currency_symbol=false data-orig-value="' . (float)$row->quantity . '" data-unit="' . $row->unit . '" >' . (float) $row->quantity . '</span> ' . $row->unit;
                    if ($row->qty_returned > 0) {
                        $html .= '<small><i>(<span data-is_quantity="true" class="display_currency" data-currency_symbol=false>' . (float) $row->quantity . '</span> ' . $row->unit . ' ' . __('lang_v1.returned') . ')</i></small>';
                    }

                    return $html;
                })
                ->editColumn('selling_price', function ($row) {
                    $selling_price = !empty($row->sell_line_id) ? $row->selling_price : $row->stock_adjustment_price;

                    return '<span class="display_currency row_selling_price" data-currency_symbol=true data-orig-value="' . $selling_price . '">' . $selling_price . '</span>';
                })

                ->addColumn('subtotal', function ($row) {
                    $selling_price = !empty($row->sell_line_id) ? $row->selling_price : $row->stock_adjustment_price;
                    $subtotal = $selling_price * $row->quantity;
                    return '<span class="display_currency row_subtotal" data-currency_symbol=true data-orig-value="' . $subtotal . '">' . $subtotal . '</span>';
                })

                ->filterColumn('sale_invoice_no', function ($query, $keyword) {
                    $query->where('sale.invoice_no', 'like', ["%{$keyword}%"])
                        ->orWhere('stock_adjustment.ref_no', 'like', ["%{$keyword}%"]);
                })

                ->rawColumns(['subtotal', 'selling_price', 'quantity', 'purchase_price', 'sale_invoice_no', 'purchase_ref_no'])
                ->make(true);
        }

        $suppliers = Contact::suppliersDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);
        $business_locations = BusinessLocation::forDropdown($business_id);
        return view('report.items_report')->with(compact('suppliers', 'customers', 'business_locations'));
    }

    /**
     * Shows purchase report
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseReport()
    {
        if ((!auth()->user()->can('purchase.view') && !auth()->user()->can('purchase.create') && !auth()->user()->can('view_own_purchase')) || empty(config('constants.show_report_606'))) {
            abort(403, 'Unauthorized action.');
        }
        $business_id = request()->session()->get('user.business_id');
        if (request()->ajax()) {
            $payment_types = $this->transactionUtil->payment_types();
            $purchases = Transaction::leftJoin('contacts', 'transactions.contact_id', '=', 'contacts.id')
                ->join(
                    'business_locations AS BS',
                    'transactions.location_id',
                    '=',
                    'BS.id'
                )
                ->leftJoin(
                    'transaction_payments AS TP',
                    'transactions.id',
                    '=',
                    'TP.transaction_id'
                )
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'purchase')
                ->with(['payment_lines'])
                ->select(
                    'transactions.id',
                    'transactions.ref_no',
                    'contacts.name',
                    'contacts.contact_id',
                    'final_total',
                    'total_before_tax',
                    'discount_amount',
                    'discount_type',
                    'tax_amount',
                    DB::raw('DATE_FORMAT(transaction_date, "%Y/%m") as purchase_year_month'),
                    DB::raw('DATE_FORMAT(transaction_date, "%d") as purchase_day')
                )
                ->groupBy('transactions.id');

            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $purchases->whereIn('transactions.location_id', $permitted_locations);
            }

            if (!empty(request()->supplier_id)) {
                $purchases->where('contacts.id', request()->supplier_id);
            }
            if (!empty(request()->location_id)) {
                $purchases->where('transactions.location_id', request()->location_id);
            }
            if (!empty(request()->input('payment_status')) && request()->input('payment_status') != 'overdue') {
                $purchases->where('transactions.payment_status', request()->input('payment_status'));
            } elseif (request()->input('payment_status') == 'overdue') {
                $purchases->whereIn('transactions.payment_status', ['due', 'partial'])
                    ->whereNotNull('transactions.pay_term_number')
                    ->whereNotNull('transactions.pay_term_type')
                    ->whereRaw("IF(transactions.pay_term_type='days', DATE_ADD(transactions.transaction_date, INTERVAL transactions.pay_term_number DAY) < CURDATE(), DATE_ADD(transactions.transaction_date, INTERVAL transactions.pay_term_number MONTH) < CURDATE())");
            }

            if (!empty(request()->status)) {
                $purchases->where('transactions.status', request()->status);
            }

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $purchases->whereDate('transactions.transaction_date', '>=', $start)
                    ->whereDate('transactions.transaction_date', '<=', $end);
            }

            if (!auth()->user()->can('purchase.view') && auth()->user()->can('view_own_purchase')) {
                $purchases->where('transactions.created_by', request()->session()->get('user.id'));
            }

            return Datatables::of($purchases)
                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final_total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn(
                    'total_before_tax',
                    '<span class="display_currency total_before_tax" data-currency_symbol="true" data-orig-value="{{$total_before_tax}}">{{$total_before_tax}}</span>'
                )
                ->editColumn(
                    'tax_amount',
                    '<span class="display_currency tax_amount" data-currency_symbol="true" data-orig-value="{{$tax_amount}}">{{$tax_amount}}</span>'
                )
                ->editColumn(
                    'discount_amount',
                    function ($row) {
                        $discount = !empty($row->discount_amount) ? $row->discount_amount : 0;

                        if (!empty($discount) && $row->discount_type == 'percentage') {
                            $discount = $row->total_before_tax * ($discount / 100);
                        }

                        return '<span class="display_currency total-discount" data-currency_symbol="true" data-orig-value="' . $discount . '">' . $discount . '</span>';
                    }
                )
                ->addColumn('payment_year_month', function ($row) {
                    $year_month = '';
                    if (!empty($row->payment_lines->first())) {
                        $year_month = \Carbon::parse($row->payment_lines->first()->paid_on)->format('Y/m');
                    }
                    return $year_month;
                })
                ->addColumn('payment_day', function ($row) {
                    $payment_day = '';
                    if (!empty($row->payment_lines->first())) {
                        $payment_day = \Carbon::parse($row->payment_lines->first()->paid_on)->format('d');
                    }
                    return $payment_day;
                })
                ->addColumn('payment_method', function ($row) use ($payment_types) {
                    $methods = array_unique($row->payment_lines->pluck('method')->toArray());
                    $count = count($methods);
                    $payment_method = '';
                    if ($count == 1) {
                        $payment_method = $payment_types[$methods[0]];
                    } elseif ($count > 1) {
                        $payment_method = __('lang_v1.checkout_multi_pay');
                    }

                    $html = !empty($payment_method) ? '<span class="payment-method" data-orig-value="' . $payment_method . '" data-status-name="' . $payment_method . '">' . $payment_method . '</span>' : '';

                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        if (auth()->user()->can("purchase.view")) {
                            return  action('PurchaseController@show', [$row->id]);
                        } else {
                            return '';
                        }
                    }
                ])
                ->rawColumns(['final_total', 'total_before_tax', 'tax_amount', 'discount_amount', 'payment_method'])
                ->make(true);
        }

        $business_locations = BusinessLocation::forDropdown($business_id);
        $suppliers = Contact::suppliersDropdown($business_id, false);
        $orderStatuses = $this->productUtil->orderStatuses();

        return view('report.purchase_report')
            ->with(compact('business_locations', 'suppliers', 'orderStatuses'));
    }

    /**
     * Shows sale report
     *
     * @return \Illuminate\Http\Response
     */
    public function saleReport()
    {
        if ((!auth()->user()->can('sell.view') && !auth()->user()->can('sell.create') && !auth()->user()->can('direct_sell.access') && !auth()->user()->can('view_own_sell_only')) || empty(config('constants.show_report_607'))) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id, false);
        $customers = Contact::customersDropdown($business_id, false);

        return view('report.sale_report')
            ->with(compact('business_locations', 'customers'));
    }

    /**
     * Calculates stock values
     *
     * @return array
     */
    public function getStockValue()
    {
        $business_id = request()->session()->get('user.business_id');
        $end_date = \Carbon::now()->format('Y-m-d');
        $location_id = request()->input('location_id');
        $filters = request()->only(['category_id', 'sub_category_id', 'brand_id', 'unit_id']);
        //Get Closing stock
        $closing_stock_by_pp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            false,
            $filters
        );
        $closing_stock_by_sp = $this->transactionUtil->getOpeningClosingStock(
            $business_id,
            $end_date,
            $location_id,
            false,
            true,
            $filters
        );
        $potential_profit = $closing_stock_by_sp - $closing_stock_by_pp;
        $profit_margin = empty($closing_stock_by_sp) ? 0 : ($potential_profit / $closing_stock_by_sp) * 100;

        return [
            'closing_stock_by_pp' => $closing_stock_by_pp,
            'closing_stock_by_sp' => $closing_stock_by_sp,
            'potential_profit' => $potential_profit,
            'profit_margin' => $profit_margin
        ];
    }
    /**
     * Shows profit\loss of a business
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAudit(Request $request)
    {
        if (!auth()->user()->can('report.audit')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $audits = Audit::leftJoin('users AS usr', 'audits.update_by', '=', 'usr.id')
            ->select([
                'audits.*',
                DB::raw("CONCAT(COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as updated_by_name")
            ]);

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $audits->whereDate('audits.created_at', '>=', $startOfMonth)
            ->whereDate('audits.created_at', '<=', $endOfMonth)
            ->orderBy('audits.created_at', 'asc');


        if (request()->has('payment_status')) {
            $payment_status = request()->get('payment_status');
            if (!empty($payment_status)) {
                $audits->where('audits.type', $payment_status == 1 ? "cxp" : "gastos");
            }
        }
        if (request()->ajax()) {

            return Datatables::of($audits)
                ->addColumn(
                    'action',
                    ''
                )
                ->editColumn('updated_at', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->format('Y/m/d g:i A');
                })->editColumn('change', function ($row) {
                    // Reemplazar cada punto por un salto de línea y eliminar el punto
                    return nl2br(str_replace('*.*', '', $row->change));
                })
                ->rawColumns(['action', 'change'])
                ->make(true);
        }
        return view('admin.audits.index');
    }
    public function destroyAudit()
    {
        Audit::truncate();
        $output = [
            'success' => 1,
            'msg' => __('Auditorías eliminadas con éxito')
        ];
        return redirect('audits')->with('status', $output);
    }
    public function getCxcReport(Request $request)
    {
        if (!auth()->user()->can('profit_loss_report.view')) { // o crea un permiso nuevo si quieres
            abort(403, 'Unauthorized action.');
        }

        $business_id = $request->session()->get('user.business_id');

        return view('report.cxc');
    }

    public function generateCxcReport(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        // 1) Rango visible (meses a mostrar)
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $start = \Carbon\Carbon::parse($request->date_start)->startOfMonth();
            $end   = \Carbon\Carbon::parse($request->date_end)->endOfMonth();
            $rango = "Reporte del: {$start->toDateString()} al {$end->toDateString()}";
        } else {
            $end   = $request->filled('date_end') ? \Carbon\Carbon::parse($request->date_end)->endOfMonth() : now()->endOfMonth();
            $start = $end->copy()->subMonths(6)->startOfMonth();
            $rango = "Reporte al: {$end->toDateString()}";
        }

        // 2) Meses en formato YYYY-MM
        $months = [];
        for ($c = $start->copy(); $c <= $end; $c->addMonth()) {
            $months[] = $c->format('Y-m');
        }

        // 3) Cuentas creadas hasta fin del rango (sin filtrar por status aquí)
        $revenuesQ = Revenue::where('revenues.business_id', $business_id)
            ->leftJoin('contacts AS ct', 'revenues.contact_id', '=', 'ct.id')
            ->leftJoin('plan_ventas AS pv', 'revenues.plan_venta_id', '=', 'pv.id')
            ->leftJoin('products AS v', 'pv.vehiculo_venta_id', '=', 'v.id')
            ->select([
                'revenues.id as rev_id',
                'revenues.referencia',
                'revenues.status',
                'revenues.sucursal',
                'revenues.valor_total',
                'revenues.created_at',
                'revenues.cuota', // <<< ADD: cuota mensual para efectividad
                'ct.id as cliente_id',
                'ct.name as cliente',
                'v.name as vehiculo',
                'v.model as model',
            ])
            ->whereDate('revenues.created_at', '<=', $end->toDateString())
            ->where('pv.tipo_plan', 2);

        // Filtro opcional por sucursal (sobre cuentas)
        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $revenuesQ->where('revenues.sucursal', $request->location_id);
            $rango .= " (Sucursal: {$request->location_id})";
        }

        $revenues = $revenuesQ
            ->orderBy('ct.name', 'asc')
            ->orderBy('revenues.created_at', 'asc')
            ->get();

        // 4) Pagos por MES (hasta fin del rango) - paga/amortiza para métricas y payoff
        $paymentsByMonthQ = DB::table('payment_revenues AS pr')
            ->join('revenues AS r', 'pr.revenue_id', '=', 'r.id')
            ->where('r.business_id', $business_id)
            ->whereDate('pr.created_at', '<=', $end->toDateString())
            ->where('pr.detalle', 'not like', '%PERDIDA%')
            ->where('pr.detalle', 'not like', '%RECONOCIMIENTO%')
            ->select([
                'pr.revenue_id',
                DB::raw("DATE_FORMAT(pr.created_at, '%Y-%m') AS ym"),
                DB::raw('SUM(pr.paga) AS paga_mes'),
                DB::raw('SUM(pr.amortiza) AS amortiza_mes'),
            ])
            ->groupBy('pr.revenue_id', 'ym');

        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $paymentsByMonthQ->where('r.sucursal', $request->location_id);
        }

        $paymentsByMonthRaw = $paymentsByMonthQ->get();
        $pagaByRevenue     = []; // [rev_id][ym]
        $amortizaByRevenue = []; // [rev_id][ym]
        foreach ($paymentsByMonthRaw as $p) {
            $pagaByRevenue[$p->revenue_id][$p->ym]     = (float)$p->paga_mes;
            $amortizaByRevenue[$p->revenue_id][$p->ym] = (float)$p->amortiza_mes;
        }

        // 4bis) Último monto_general por cuenta/mes (hasta fin del rango)
        $lastSaldoByRevYm = [];   // [rev_id][ym] => último monto_general del mes
        $lastSaldoRowsQ = DB::table('payment_revenues AS pr')
            ->join('revenues AS r', 'pr.revenue_id', '=', 'r.id')
            ->where('r.business_id', $business_id)
            ->whereDate('pr.created_at', '<=', $end->toDateString())
            ->select([
                'pr.revenue_id',
                DB::raw("DATE_FORMAT(pr.created_at, '%Y-%m') AS ym"),
                'pr.created_at',
                'pr.id',
                'pr.monto_general',
            ]);

        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $lastSaldoRowsQ->where('r.sucursal', $request->location_id);
        }

        $lastSaldoRows = $lastSaldoRowsQ
            ->orderBy('pr.created_at', 'asc')
            ->orderBy('pr.id', 'asc')
            ->get();

        foreach ($lastSaldoRows as $row) {
            $lastSaldoByRevYm[$row->revenue_id][$row->ym] = (float)$row->monto_general;
        }

        // 4ter) Último monto_general antes del inicio (saldo semilla)
        $preStartSaldo = [];   // [rev_id] => último monto_general antes de $start
        $preStartSaldoRowsQ = DB::table('payment_revenues AS pr')
            ->join('revenues AS r', 'pr.revenue_id', '=', 'r.id')
            ->where('r.business_id', $business_id)
            ->whereDate('pr.created_at', '<', $start->toDateString())
            ->select([
                'pr.revenue_id',
                'pr.created_at',
                'pr.id',
                'pr.monto_general',
            ]);

        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $preStartSaldoRowsQ->where('r.sucursal', $request->location_id);
        }

        $preStartSaldoRows = $preStartSaldoRowsQ
            ->orderBy('pr.created_at', 'asc')
            ->orderBy('pr.id', 'asc')
            ->get();

        foreach ($preStartSaldoRows as $row) {
            $preStartSaldo[$row->revenue_id] = (float)$row->monto_general; // queda el último
        }

        // 5) Amortización acumulada antes del inicio (para payoff/visibilidad)
        $preStartPaidQ = DB::table('payment_revenues AS pr')
            ->join('revenues AS r', 'pr.revenue_id', '=', 'r.id')
            ->where('r.business_id', $business_id)
            ->whereDate('pr.created_at', '<', $start->toDateString())
            ->select([
                'pr.revenue_id',
                DB::raw('SUM(pr.amortiza) AS pagado_previo')
            ])
            ->groupBy('pr.revenue_id');

        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $preStartPaidQ->where('r.sucursal', $request->location_id);
        }

        $preStartPaidRaw = $preStartPaidQ->get();
        $preStartPaid = []; // [rev_id] => amortizado antes del inicio
        foreach ($preStartPaidRaw as $row) {
            $preStartPaid[$row->revenue_id] = (float)$row->pagado_previo;
        }

        // 6) Construcción por cliente/mes
        $EPS = 0.005;
        $byClient    = [];
        $revByClient = $revenues->groupBy('cliente_id');

        // === ADD: Acumuladores globales para EFECTIVIDAD ===
        $cuotaEsperadaPorMes = []; // [ym] => suma de cuota esperada del mes (denominador)
        $recaudadoPorMes     = []; // [ym] => suma de paga real del mes (numerador)

        foreach ($revByClient as $cliente_id => $clientRevs) {
            $clienteNombre = optional($clientRevs->first())->cliente ?? 'SIN NOMBRE';

            // 6.1 amortización acumulada por cuenta/mes + payoff
            $accumAmortizaByRevYm = []; // [rev_id][ym] = amortiza acumulada
            $payoffYmByRev        = []; // [rev_id] => 'YYYY-MM' | '__BEFORE__' | null

            foreach ($clientRevs as $rev) {
                $revId       = $rev->rev_id;
                $totalCuenta = (float)$rev->valor_total;

                // amortización acumulada antes de $start
                $acum = $preStartPaid[$revId] ?? 0.0;

                // --- semilla de SALDO REAL antes del inicio ---
                $saldoCarry = isset($preStartSaldo[$revId])
                    ? (float)$preStartSaldo[$revId]
                    : max($totalCuenta - $acum, 0.0); // fallback si nunca hubo PR antes del inicio

                // payoff antes del rango si el saldo real ya era ~0
                if ($saldoCarry <= $EPS) {
                    $payoffYmByRev[$revId] = '__BEFORE__';
                } else {
                    $payoffYmByRev[$revId] = null;
                }

                // rellenar amortización acumulada por mes
                foreach ($months as $ym) {
                    $acum += $amortizaByRevenue[$revId][$ym] ?? 0.0;
                    $accumAmortizaByRevYm[$revId][$ym] = $acum;

                    // actualizar SALDO REAL por mes con el último monto_general de ese mes (si existe)
                    if (isset($lastSaldoByRevYm[$revId][$ym])) {
                        $saldoCarry = (float)$lastSaldoByRevYm[$revId][$ym];
                    } // si no, se mantiene

                    // marcar payoff cuando el SALDO REAL llega a ~0 por primera vez
                    if (is_null($payoffYmByRev[$revId]) && $saldoCarry <= $EPS) {
                        $payoffYmByRev[$revId] = $ym; // payoff por SALDO REAL
                    }
                }
            }

            // 6.2 Filtrar cuentas (excluir canceladas antes del rango)
            $filteredRevs = $clientRevs->filter(function ($rev) use ($payoffYmByRev) {
                $flag = $payoffYmByRev[$rev->rev_id] ?? null;
                return $flag !== '__BEFORE__';
            })->values();

            if ($filteredRevs->isEmpty()) {
                continue;
            }

            // 6.3 Armar filas por mes con saldo REAL (monto_general) y regla de inclusión por saldoCierreMes
            $rows = [];
            $carrySaldoPorRev = []; // [rev_id] => último saldo conocido real

            foreach ($months as $ym) {
                [$yy, $mm] = explode('-', $ym);
                $monthEnd  = \Carbon\Carbon::createMidnightDate((int)$yy, (int)$mm, 1)->endOfMonth();
                $prevYm    = \Carbon\Carbon::createFromFormat('Y-m', $ym)->subMonth()->format('Y-m');

                $totalMes  = 0.0;
                $pagaMes   = 0.0;
                $amortMes  = 0.0;
                $amortAcum = 0.0;
                $saldoMes  = 0.0;

                foreach ($filteredRevs as $rev) {
                    $revId     = $rev->rev_id;
                    $totalCta  = (float)$rev->valor_total;
                    $createdOk = \Carbon\Carbon::parse($rev->created_at) <= $monthEnd;

                    $flag = $payoffYmByRev[$revId] ?? null;

                    // presencia por payoff
                    $showByPayoff = false;
                    if ($createdOk) {
                        if ($flag === '__BEFORE__') {
                            $showByPayoff = false;
                        } elseif (is_null($flag)) {
                            $showByPayoff = true;
                        } else {
                            $showByPayoff = ($ym <= $flag); // hasta payoff inclusive
                        }
                    }
                    if (!$showByPayoff) {
                        continue;
                    }

                    // movimientos del mes
                    $pagaThis  = (float)($pagaByRevenue[$revId][$ym] ?? 0.0);
                    $amortThis = (float)($amortizaByRevenue[$revId][$ym] ?? 0.0);
                    $hadMovement = (abs($pagaThis) > $EPS || abs($amortThis) > $EPS);

                    // === ADD: EFECTIVIDAD (denominador y numerador) ===
                    // Denominador: suma de la cuota exacta de todas las cuentas "presentes" ese mes
                    $cuotaRev = isset($rev->cuota) ? (float)$rev->cuota : 0.0;
                    if (!isset($cuotaEsperadaPorMes[$ym])) $cuotaEsperadaPorMes[$ym] = 0.0;
                    if (!isset($recaudadoPorMes[$ym]))     $recaudadoPorMes[$ym]     = 0.0;

                    $cuotaEsperadaPorMes[$ym] += $cuotaRev; // cuenta aunque no haya movimiento
                    $recaudadoPorMes[$ym]     += $pagaThis; // pagos reales de ese mes
                    // === FIN ADD ===

                    // amortización acumulada previa
                    $amortAcumPrev = isset($accumAmortizaByRevYm[$revId][$prevYm])
                        ? (float)$accumAmortizaByRevYm[$revId][$prevYm]
                        : (float)($preStartPaid[$revId] ?? 0.0);

                    // --- saldo real de CIERRE del mes ---
                    // a) si hay PR en el mes → último monto_general del mes
                    if (isset($lastSaldoByRevYm[$revId][$ym])) {
                        $carrySaldoPorRev[$revId] = (float)$lastSaldoByRevYm[$revId][$ym];
                    } else {
                        // b) si no hay PR en el mes pero ya tenemos carry → se mantiene
                        if (!isset($carrySaldoPorRev[$revId])) {
                            // c) si no hay carry aún → usar saldo semilla o (fallback) estimado
                            $saldoPrevEstimado = max($totalCta - $amortAcumPrev, 0.0);
                            $carrySaldoPorRev[$revId] = isset($preStartSaldo[$revId])
                                ? (float)$preStartSaldo[$revId]
                                : (float)$saldoPrevEstimado;
                        }
                    }

                    $saldoCierreMes = (float)$carrySaldoPorRev[$revId];

                    // --- regla de inclusión SIEMPRE por saldoCierreMes o por movimiento ---
                    if (!($saldoCierreMes > $EPS || $hadMovement)) {
                        continue;
                    }

                    // acumular totales del mes
                    $totalMes  += $totalCta;
                    $pagaMes   += $pagaThis;
                    $amortMes  += $amortThis;

                    // DESPUÉS: el total real del mes para esta cuenta = saldo real de cierre + amortización acumulada al mes
                    $amortAcumCuentaAlMes = isset($accumAmortizaByRevYm[$revId][$ym])
                        ? (float)$accumAmortizaByRevYm[$revId][$ym]
                        : ($amortAcumPrev + $amortThis);

                    $totalRealCuentaMes = max($saldoCierreMes, 0.0) + max($amortAcumCuentaAlMes, 0.0);

                    $totalMes  += $totalRealCuentaMes;
                    $amortAcum += $amortAcumCuentaAlMes; // mantenemos esta métrica como antes
                    $saldoMes  += max($saldoCierreMes, 0.0);
                }

                // descartar filas totalmente vacías
                $isEmptyRow = (abs($totalMes) < $EPS) && (abs($pagaMes) < $EPS) && (abs($amortMes) < $EPS) && (abs($saldoMes) < $EPS);

                if (!$isEmptyRow) {
                    $rows[$ym] = [
                        'total'         => $totalCta,
                        'paga_mes'      => $pagaMes,
                        'amortiza_mes'  => $amortMes,
                        'amortiza_acum' => $amortAcum,
                        'saldo'         => $saldoMes, // ← viene de monto_general
                    ];
                }
            }

            if (empty($rows)) {
                continue;
            }

            // Estado final (si existe el último mes entre sus filas)
            $finalYm = end($months);
            $final   = $rows[$finalYm] ?? ['total' => 0, 'paga_mes' => 0, 'amortiza_mes' => 0, 'amortiza_acum' => 0, 'saldo' => 0];

            $byClient[$cliente_id] = [
                'cliente' => $clienteNombre,
                'rows'    => $rows,
                'final'   => $final,
            ];
        }

        // 7) Totales por mes (solo con filas realmente generadas)
        $totalesPorMes = [];
        foreach ($months as $ym) {
            $t = $paga_mes = $amort_mes = $amort_acum = $saldo_sum = 0.0;

            foreach ($byClient as $c) {
                if (!isset($c['rows'][$ym])) {
                    continue;
                }
                $row = $c['rows'][$ym];
                $t          += $row['total'];
                $paga_mes   += $row['paga_mes'];
                $amort_mes  += $row['amortiza_mes'];
                $amort_acum += $row['amortiza_acum'];
                $saldo_sum  += $row['saldo']; // real
            }

            if (abs($t) < $EPS && abs($paga_mes) < $EPS && abs($amort_mes) < $EPS && abs($saldo_sum) < $EPS) {
                continue;
            }

            $totalesPorMes[$ym] = [
                'total'        => $t,
                'paga_mes'     => $paga_mes,
                'amortiza_mes' => $amort_mes,
                'saldo'        => $saldo_sum,
            ];
        }

        // 7bis) Efectividad por mes y global (ADD)
        $efectividadPorMes = []; // [ym] => porcentaje (0..100) o null
        $sumaCuotaGlobal   = 0.0;
        $sumaRecaudoGlobal = 0.0;

        foreach ($months as $ym) {
            $den = $cuotaEsperadaPorMes[$ym] ?? 0.0; // cuota esperada
            $num = $recaudadoPorMes[$ym]     ?? 0.0; // paga real

            if ($den > $EPS) {
                $efectividadPorMes[$ym] = ($num / $den) * 100.0;
            } else {
                $efectividadPorMes[$ym] = null; // sin cuota ese mes
            }

            $sumaCuotaGlobal   += $den;
            $sumaRecaudoGlobal += $num;
        }

        $efectividadGlobal = ($sumaCuotaGlobal > $EPS)
            ? ($sumaRecaudoGlobal / $sumaCuotaGlobal) * 100.0
            : null;

        // 8) Estado al final del rango (último mes visible con totales)
        $lastYm = end($months);
        if (!isset($totalesPorMes[$lastYm])) {
            $keys = array_keys($totalesPorMes);
            $lastYm = empty($keys) ? end($months) : end($keys);
        }

        $estado_final_total  = $totalesPorMes[$lastYm]['total']  ?? 0.0;
        $estado_final_saldo  = $totalesPorMes[$lastYm]['saldo']  ?? 0.0;

        // “pagado” final = amortización acumulada al último mes (sumando solo filas generadas)
        $estado_final_pagado = 0.0;
        foreach ($byClient as $c) {
            if (isset($c['rows'][$lastYm])) {
                $estado_final_pagado += $c['rows'][$lastYm]['amortiza_acum'] ?? 0.0;
            }
        }

        return view('report.partials.cxc-monthly-balance', [
            'months'                => $months,
            'byClient'              => $byClient,
            'totalesPorMes'         => $totalesPorMes,
            'rango'                 => $rango,
            'estado_final_total'    => $estado_final_total,
            'estado_final_pagado'   => $estado_final_pagado,
            'estado_final_saldo'    => $estado_final_saldo,

            // ADD: métricas de efectividad
            'cuotaEsperadaPorMes'   => $cuotaEsperadaPorMes, // denominador por mes
            'recaudadoPorMes'       => $recaudadoPorMes,     // numerador por mes
            'efectividadPorMes'     => $efectividadPorMes,   // %
            'efectividadGlobal'     => $efectividadGlobal,   // %
        ]);
    }

    public function generateCxcReportDetailed(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $query = Revenue::where('revenues.business_id', $business_id)
            ->leftJoin('contacts AS ct', 'revenues.contact_id', '=', 'ct.id')
            ->leftJoin('payment_revenues AS pr', 'revenues.id', '=', 'pr.revenue_id')
            ->leftJoin('plan_ventas AS pv', 'revenues.plan_venta_id', '=', 'pv.id')
            ->leftJoin('products AS v', 'pv.vehiculo_venta_id', '=', 'v.id')
            ->select([
                'revenues.id as rev_id',
                'revenues.referencia',
                'revenues.location_id',
                'revenues.status',
                'revenues.sucursal',
                'revenues.valor_total',
                'revenues.detalle',
                'revenues.created_at',
                'ct.id as cliente_id',
                'ct.name as cliente',
                'v.name as vehiculo',
                'v.model as model',
                DB::raw('COALESCE(SUM(pr.amortiza), 0) as amount_paid'),
                DB::raw('COALESCE(MIN(pr.monto_general), -1) as min_general_amount')
            ])
            ->groupBy(
                'revenues.id',
                'revenues.referencia',
                'revenues.location_id',
                'revenues.status',
                'revenues.sucursal',
                'revenues.valor_total',
                'revenues.detalle',
                'revenues.created_at',
                'ct.id',
                'ct.name',
                'v.name',
                'v.model'
            );

        // Fechas (creación)
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $start = \Carbon\Carbon::parse($request->date_start)->startOfDay();
            $end   = \Carbon\Carbon::parse($request->date_end)->endOfDay();
            $query->whereBetween('revenues.created_at', [$start, $end]);
            $rango = "Reporte del: {$start->toDateString()} al {$end->toDateString()}";
        } else {
            $end   = $request->filled('date_end')
                ? \Carbon\Carbon::parse($request->date_end)->endOfDay()
                : now()->endOfDay();
            $start = $end->copy()->subMonths(6)->startOfDay();
            $query->whereBetween('revenues.created_at', [$start, $end]);
            $rango = "Reporte al: {$end->toDateString()}";
        }

        // Estado: 1 = pendiente, 2 = cancelado
        if ($request->has('status') && $request->status !== null && $request->status !== '' && (int)$request->status !== -1) {
            if ((int)$request->status === 1) {
                $query->where('revenues.status', 1);
            }
            if ((int)$request->status === 2) {
                $query->where('revenues.status', 0);
            }
        }

        // Sucursal
        if ($request->has('location_id') && !empty($request->location_id) && $request->location_id !== 'TODAS') {
            $query->where('revenues.sucursal', $request->location_id);
            $rango .= " (Sucursal: {$request->location_id})";
        }

        $rows = $query->orderBy('revenues.created_at', 'desc')->get();

        // Agrupar por mes (YYYY-MM) y calcular: total, pagado, pendiente
        $grouped = $rows->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->created_at)->format('Y-m');
        })->map(function ($collection) {
            // Cálculos por fila (y acumulados por mes)
            $detalle = $collection->map(function ($r) {
                $valor_inicial = ($r->min_general_amount !== null && $r->min_general_amount >= 0)
                    ? $r->min_general_amount
                    : $r->valor_total;

                $pagado    = (float)$r->amount_paid;
                $total     = (float)$r->valor_total;
                $pendiente = max($total - $pagado, 0); // evita negativos

                return (object)[
                    'fecha'          => \Carbon\Carbon::parse($r->created_at)->format('Y-m-d'),
                    'cliente'        => $r->cliente,
                    'referencia'     => $r->referencia,
                    'valor_inicial'  => $valor_inicial,
                    'total_pagar'    => $total,       // total sin rebajos (como pediste)
                    'pagado'         => $pagado,
                    'pendiente'      => $pendiente,
                    'vehiculo'       => $r->vehiculo,
                    'modelo'         => $r->model,
                    'status'         => (int)$r->status === 1 ? 'Pendiente' : 'Cancelado',
                    'sucursal'       => $r->sucursal,
                ];
            });

            $subtotal_total     = $detalle->sum('total_pagar');
            $subtotal_pagado    = $detalle->sum('pagado');
            $subtotal_pendiente = $detalle->sum('pendiente');

            return (object)[
                'detalle'            => $detalle,
                'subtotal_total'     => $subtotal_total,
                'subtotal_pagado'    => $subtotal_pagado,
                'subtotal_pendiente' => $subtotal_pendiente,
            ];
        });

        // Totales generales
        $total_general_total     = $grouped->sum('subtotal_total');
        $total_general_pagado    = $grouped->sum('subtotal_pagado');
        $total_general_pendiente = $grouped->sum('subtotal_pendiente');

        return view('report.partials.cxc-modal', [
            'grouped'                 => $grouped,
            'rango'                   => $rango,
            'total_general_total'     => $total_general_total,
            'total_general_pagado'    => $total_general_pagado,
            'total_general_pendiente' => $total_general_pendiente,
        ]);
    }
}

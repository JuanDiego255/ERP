<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Audit;
use App\Models\BusinessLocation;
use App\Models\ExpenseCategory;
use App\Models\TaxRate;
use App\Models\Transaction;
use App\Models\City;
use App\Models\Contact;
use App\Models\DetailTransaction;
use App\Models\Pais;
use App\Models\User;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mpdf\Tag\Details;
use Yajra\DataTables\Facades\DataTables;

class ExpenseController extends Controller
{
    /**
     * Constructor
     *
     * @param TransactionUtil $transactionUtil
     * @return void
     */
    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil)
    {
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->dummyPaymentLine = [
            'method' => 'cash',
            'amount' => 0,
            'note' => '',
            'card_transaction_number' => '',
            'card_number' => '',
            'card_type' => '',
            'card_holder_name' => '',
            'card_month' => '',
            'card_year' => '',
            'card_security' => '',
            'cheque_number' => '',
            'bank_account_number' => '',
            'is_return' => 0,
            'transaction_no' => '',
            'data_base' => date('d/m/Y'),
            'intervalo' => '',
            'vencimento' => date('d/m/Y'),
            'qtd_parcelas' => 1
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('view_own_expense')) {
            abort(403, 'Unauthorized action.');
        }
        $current_path = request()->path();
        $is_report = $current_path == "expense-report" ? true : false;

        if (request()->ajax()) {
            $is_report = $current_path == "expense-report" ? true : false;
            $business_id = request()->session()->get('user.business_id');

            $expenses = Transaction::leftJoin('expense_categories AS ec', 'transactions.expense_category_id', '=', 'ec.id')
                ->join(
                    'business_locations AS bl',
                    'transactions.location_id',
                    '=',
                    'bl.id'
                )
                ->join(
                    'contacts AS ct',
                    'transactions.contact_id',
                    '=',
                    'ct.id'
                )
                ->leftJoin('tax_rates as tr', 'transactions.tax_id', '=', 'tr.id')
                ->leftJoin('vehicle_bills as vb', 'transactions.ref_no', '=', 'vb.factura')
                ->leftJoin('products as pro', 'vb.product_id', '=', 'pro.id')
                ->leftJoin('users AS U', 'transactions.expense_for', '=', 'U.id')
                ->leftJoin('users AS usr', 'transactions.created_by', '=', 'usr.id')
                ->leftJoin(
                    'transaction_payments AS TP',
                    'transactions.id',
                    '=',
                    'TP.transaction_id'
                )
                ->where('transactions.business_id', $business_id)
                ->where('transactions.type', 'expense')
                ->select(
                    'transactions.id',
                    'transactions.contact_id',
                    'transactions.check_report',
                    'transactions.document',
                    'transaction_date',
                    'transactions.fecha_vence',
                    'ref_no',
                    'ct.name as contact_name',
                    'ct.contact_id as prov_id',
                    'ec.name as category',
                    'payment_status',
                    'transactions.additional_notes',
                    'final_total',
                    'bl.name as location_name',
                    DB::raw("CONCAT(COALESCE(pro.name, ''),' (',COALESCE(pro.model, 'N/A'),') ') as vehicle"),
                    DB::raw("CONCAT(COALESCE(U.surname, ''),' ',COALESCE(U.first_name, ''),' ',COALESCE(U.last_name,'')) as expense_for"),
                    DB::raw("CONCAT(tr.name ,' (', tr.amount ,' )') as tax"),
                    DB::raw('SUM(TP.amount) as amount_paid'),
                    DB::raw("CONCAT(COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by")
                )
                ->groupBy('transactions.id')
                ->orderBy('ct.name', 'asc');

            //Add condition for expense for,used in sales representative expense report & list of expense
            if (request()->has('expense_for')) {
                $expense_for = request()->get('expense_for');
                if (!empty($expense_for)) {
                    $expenses->where('transactions.expense_for', $expense_for);
                }
            }

            //Add condition for location,used in sales representative expense report & list of expense
            if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $expenses->where('transactions.location_id', $location_id);
                }
            }

            //Add condition for expense category, used in list of expense,
            if (request()->has('expense_category_id')) {
                $expense_category_id = request()->get('expense_category_id');
                if (!empty($expense_category_id)) {
                    $expenses->where('transactions.expense_category_id', $expense_category_id);
                }
            }

            //Add condition for start and end date filter, uses in sales representative expense report & list of expense
            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end =  request()->end_date;
                $expenses->whereDate('transaction_date', '>=', $start)
                    ->whereDate('transaction_date', '<=', $end);
            }

            //Add condition for start and end date filter vence, uses in sales representative expense report & list of expense
            if (!empty(request()->start_vence_date) && !empty(request()->end_vence_date)) {
                $start = request()->start_vence_date;
                $end =  request()->end_vence_date;
                $expenses->whereDate('fecha_vence', '>=', $start)
                    ->whereDate('fecha_vence', '<=', $end);
            }

            //Add condition for expense category, used in list of expense,
            if (request()->has('expense_category_id')) {
                $expense_category_id = request()->get('expense_category_id');
                if (!empty($expense_category_id)) {
                    $expenses->where('transactions.expense_category_id', $expense_category_id);
                }
            }
            $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $expenses->whereIn('transactions.location_id', $permitted_locations);
            }

            //Add condition for payment status for the list of expense
            if (request()->has('payment_status')) {
                $payment_status = request()->get('payment_status');
                if (!empty($payment_status)) {
                    $payment_status == "paid" ? $expenses->where('transactions.payment_status', "paid") : $expenses->where('transactions.payment_status', "!=", "paid");
                }
            }

            /* $is_admin = $this->moduleUtil->is_admin(auth()->user(), $business_id);
            if (!$is_admin && auth()->user()->can('view_own_expense')) {
                $expenses->where('transactions.created_by', request()->session()->get('user.id'));
            } */
            return Datatables::of($expenses)
                ->addColumn('action', function ($row) use ($is_report) {
                    $action = '';
                    if (!$is_report) {
                        $action .= '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                    data-toggle="dropdown" aria-expanded="false"> ' . __("messages.actions") . '<span class="caret"></span><span class="sr-only">Toggle Dropdown
                    </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';


                        // Mostrar botón de editar si no es reporte
                        if (auth()->user()->can('cxp.update'))
                            $action .= '<li><a href="' . action('ExpenseController@edit', [$row->id]) . '"><i class="glyphicon glyphicon-edit"></i> ' . __("messages.edit") . '</a></li>';

                        // Descargar documento
                        if ($row->document) {
                            $action .= '<li><a href="' . url('uploads/documents/' . $row->document) . '" download=""><i class="fa fa-download" aria-hidden="true"></i> ' . __("purchase.download_document") . '</a></li>';
                            if (isFileImage($row->document)) {
                                $action .= '<li><a href="#" data-href="' . url('uploads/documents/' . $row->document) . '" class="view_uploaded_document"><i class="fa fa-picture-o" aria-hidden="true"></i>' . __("lang_v1.view_document") . '</a></li>';
                            }
                        }

                        // Eliminar
                        if (auth()->user()->can('cxp.delete'))
                            $action .= '<li><a data-href="' . action('ExpenseController@destroy', [$row->id]) . '" class="delete_expense"><i class="glyphicon glyphicon-trash"></i> ' . __("messages.delete") . '</a></li>';

                        // Agregar pago si no está pagado
                        if ($row->payment_status != "paid" && auth()->user()->can('purchase.payments')) {
                            $action .= '<li><a href="' . action("TransactionPaymentController@addPayment", [$row->id]) . '" class="add_payment_modal"><i class="fas fa-money-bill-alt" aria-hidden="true"></i> ' . __("purchase.add_payment") . '</a></li>';
                        }
                        if (auth()->user()->can('purchase.view'))
                            $action .= '<li><a href="' . action("TransactionPaymentController@show", [$row->id]) . '" class="view_payment_modal"><i class="fas fa-money-bill-alt" aria-hidden="true" ></i> ' . __("purchase.view_payments") . '</a></li>
                    </ul>
                </div>';
                    }


                    return $action;
                })
                ->addColumn('mass_check', function ($row) {
                    return '<input type="checkbox" class="row-select" value="' . $row->id . '"'
                        . ($row->check_report == 1 ? ' checked' : '') . '>';
                })
                ->removeColumn('id')
                ->editColumn(
                    'final_total',
                    '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$final_total}}">{{$final_total}}</span>'
                )
                ->editColumn('transaction_date', '{{@format_datetime($transaction_date)}}')
                ->editColumn('fecha_vence', '{{@format_datetime($fecha_vence)}}')
                ->editColumn(
                    'payment_status',
                    '<a href="{{ action("TransactionPaymentController@show", [$id])}}" class="view_payment_modal payment-status no-print" data-orig-value="{{$payment_status}}" data-status-name="{{__(\'lang_v1.\' . $payment_status)}}"><span class="label @payment_status($payment_status)">
                {{__(\'lang_v1.\' . $payment_status)}}
                </span></a><span class="print_section">{{__(\'lang_v1.\' . $payment_status)}}</span>'

                )
                // ->editColumn('payment_status' , function ($row) {
                //     $payment_status = $row->payment_status;
                //     $id = $row->id;
                //     $t = '<a href="{{ action("TransactionPaymentController@show", [$id])}}" class="view_payment_modal payment-status no-print" data-orig-value="{{$payment_status}}" data-status-name="{{__(\'lang_v1.\' . $payment_status)}}"><span class="label due">
                //     {{__(\'lang_v1.\' . $payment_status)}}
                //     </span></a><span class="print_section">{{__(\'lang_v1.\' . $payment_status)}}</span>';

                //     return $t;
                // })
                ->addColumn('contact', function ($row) {
                    if ($row->contact) return $row->contact->name;
                    return "";
                })
                ->addColumn('payment_due', function ($row) {
                    $due = $row->final_total - $row->amount_paid;
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</span>';
                })
                ->rawColumns(['final_total', 'action', 'mass_check', 'payment_status', 'payment_due'])
                ->make(true);
        }

        $business_id = request()->session()->get('user.business_id');

        $categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');

        $users = User::forDropdown($business_id, false, true, true);

        $business_locations = BusinessLocation::forDropdown($business_id, true);

        return view('expense.index')
            ->with(compact('categories', 'business_locations', 'users', 'is_report'));
    }
    public function generateReport(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        $query = Transaction::join('contacts as ct', 'transactions.contact_id', '=', 'ct.id')
            ->leftJoin(
                'transaction_payments AS TP',
                'transactions.id',
                '=',
                'TP.transaction_id'
            )
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'expense')
            ->where('transactions.check_report', 1)
            ->select(
                'ct.name as provider',
                DB::raw("GROUP_CONCAT(transactions.ref_no SEPARATOR ', ') as invoices"),
                DB::raw("SUM(transactions.final_total) as total"),
                DB::raw("SUM(TP.amount) as amount")

            )
            ->groupBy('transactions.contact_id')
            ->orderBy('provider', 'asc');
        $type = request()->get('type');
        // Filtros globales
        if ($type == 0) {
            if ($request->filled('date_start') && $request->filled('date_end')) {
                $query->whereBetween('transactions.transaction_date', [$request->date_start, $request->date_end]);
                $rango = "Reporte del (Se filtro por fecha de creación): " . $request->date_start . " al " . $request->date_end;
            } else {
                $query->where('transactions.transaction_date', '<=', $request->date_end);
                $rango = "Reporte al (Se filtro por fecha de creación): " . $request->date_end;
            }
        } else {
            if ($request->filled('date_vence_start') && $request->filled('date_vence_end')) {
                $query->whereBetween('transactions.fecha_vence', [$request->date_vence_start, $request->date_vence_end]);
                $rango = "Reporte del (Se filtro por fecha vence): " . $request->date_vence_start . " al " . $request->date_vence_end;
            } else {
                $query->where('transactions.fecha_vence', '<=', $request->date_vence_end);
                $rango = "Reporte al (Se filtro por fecha vence): " . $request->date_vence_end;
            }
        }

        if ($request->filled('location_id')) {
            $query->where('transactions.location_id', $request->location_id);
        }
        if ($request->filled('expense_category_id')) {
            $query->where('transactions.expense_category_id', $request->expense_category_id);
        }
        if (request()->has('payment_status')) {
            $payment_status = request()->get('payment_status');
            if (!empty($payment_status)) {
                $payment_status == "paid" ? $query->where('transactions.payment_status', "paid") : $query->where('transactions.payment_status', "!=", "paid");
            }
        }

        // Filtros de DataTable
        if ($request->filled('table_filters')) {
            $filters = $request->input('table_filters');
            foreach ($filters as $index => $value) {
                switch ($index) {
                    case '3': // Columna Contact
                        $query->where('ct.name', 'LIKE', "%$value%");
                        break;
                    case '4': // Columna Ref No
                        $query->where('transactions.ref_no', 'LIKE', "%$value%");
                        break;
                        // Agrega casos adicionales según las columnas filtrables
                }
            }
        }

        $report = $query->get();

        return view('expense.view-modal', compact('report', 'rango'));
    }
    public function generateReportDetail(Request $request)
    {
        $business_id = $request->session()->get('user.business_id');

        // Consulta principal
        $query = Transaction::join('contacts as ct', 'transactions.contact_id', '=', 'ct.id')
            ->leftJoin('transaction_payments AS TP', 'transactions.id', '=', 'TP.transaction_id')
            ->leftJoin('vehicle_bills as vb', 'transactions.ref_no', '=', 'vb.factura')
            ->leftJoin('products as pro', 'vb.product_id', '=', 'pro.id')
            ->where('transactions.business_id', $business_id)
            ->where('transactions.type', 'expense')
            ->where('transactions.check_report', 1)
            ->select(
                'ct.name as provider', // Nombre del proveedor
                'transactions.ref_no as invoice', // Factura
                'transactions.final_total as total', // Total
                DB::raw('COALESCE(transactions.final_total - SUM(TP.amount), transactions.final_total) as balance'), // Saldo
                DB::raw('SUM(TP.amount) as advance_amount'), // Monto adelantado
                DB::raw("CONCAT(COALESCE(pro.name, ''),' (',COALESCE(pro.model, 'N/A'),') ') as vehicle"),
                'transactions.fecha_vence as fecha_vence', // Fecha vence
                'transactions.additional_notes as detail' // Detalle
            )
            ->groupBy(
                'transactions.id',
                'transactions.contact_id',
                'ct.name',
                'transactions.ref_no',
                'transactions.final_total',
                'transactions.fecha_vence',
                'transactions.additional_notes'
            )
            ->orderBy('ct.name', 'asc')
            ->orderBy('transactions.id', 'asc');

        // Filtros globales
        $type = request()->get('type');
        // Filtros globales
        if ($type == 0) {
            if ($request->filled('date_start') && $request->filled('date_end')) {
                $query->whereBetween('transactions.transaction_date', [$request->date_start, $request->date_end]);
                $rango = "Reporte del (Se filtro por fecha de creación): " . $request->date_start . " al " . $request->date_end;
            } else {
                $query->where('transactions.transaction_date', '<=', $request->date_end);
                $rango = "Reporte al (Se filtro por fecha de creación): " . $request->date_end;
            }
        } else {
            if ($request->filled('date_vence_start') && $request->filled('date_vence_end')) {
                $query->whereBetween('transactions.fecha_vence', [$request->date_vence_start, $request->date_vence_end]);
                $rango = "Reporte del (Se filtro por fecha vence): " . $request->date_vence_start . " al " . $request->date_vence_end;
            } else {
                $query->where('transactions.fecha_vence', '<=', $request->date_vence_end);
                $rango = "Reporte al (Se filtro por fecha vence): " . $request->date_vence_end;
            }
        }
        if ($request->filled('location_id')) {
            $query->where('transactions.location_id', $request->location_id);
        }
        if ($request->filled('expense_category_id')) {
            $query->where('transactions.expense_category_id', $request->expense_category_id);
        }
        if ($request->filled('payment_status')) {
            $payment_status = $request->get('payment_status');
            $query->where('transactions.payment_status', $payment_status == 'paid' ? 'paid' : '!=', 'paid');
        }

        // Filtros de DataTable
        if ($request->filled('table_filters')) {
            $filters = $request->input('table_filters');
            foreach ($filters as $index => $value) {
                switch ($index) {
                    case '3': // Columna Contact
                        $query->where('ct.name', 'LIKE', "%$value%");
                        break;
                    case '4': // Columna Ref No
                        $query->where('transactions.ref_no', 'LIKE', "%$value%");
                        break;
                        // Agrega casos adicionales según las columnas filtrables
                }
            }
        }

        // Ejecutar consulta y obtener resultados
        $report = $query->get();
        $accumulated_totals = [];
        foreach ($report as $row) {
            if (!isset($accumulated_totals[$row->provider])) {
                $accumulated_totals[$row->provider] = 0;
            }
            $accumulated_totals[$row->provider] += $row->total;
            $row->accumulated = $accumulated_totals[$row->provider];
        }

        return view('expense.view-modal-detail', compact('report', 'rango'));
    }
    public function updateCheckReport(Request $request)
    {
        try {
            $business_id = $request->session()->get('user.business_id');
            $prov_id_req = $request->prov_id;
            $checked = $request->checked;
            $ref_no = $request->ref_no;
            $prov_id = Contact::where('contact_id', $prov_id_req)
                ->where('business_id', $business_id)
                ->where('type', 'supplier')
                ->firstOrFail()->id;

            $factura = Transaction::where('contact_id', $prov_id)
                ->where('ref_no', $ref_no)
                ->where('business_id', $business_id)
                ->where('type', 'expense')
                ->firstOrFail();

            $data = [
                'check_report' => $checked
            ];

            $factura->update($data);

            return response()->json(['result' => true]);
        } catch (\Exception $th) {
            // Manejo de errores
            return response()->json(['result' => $th->getMessage()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('cxp.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $expense_categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');
        $users = User::forDropdown($business_id, true, true);

        $taxes = TaxRate::forBusinessDropdown($business_id, true, true);

        $payment_line = $this->dummyPaymentLine;

        $payment_types = $this->transactionUtil->payment_types();

        //Accounts
        $accounts = [];
        if ($this->moduleUtil->isModuleEnabled('account')) {
            $accounts = Account::forDropdown($business_id, true, false, true);
        }

        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('guarantor.create')) {
            $types['guarantor'] = __('report.guarantor');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }

        return view('expense.create')
            ->with('tipo', '')
            ->with('cities', $this->prepareCities())
            ->with('estados', $this->prepareUFs())
            ->with('paises', $this->preparePaises())
            ->with(compact('expense_categories', 'business_locations', 'users', 'taxes', 'payment_line', 'payment_types', 'accounts', 'types'));
    }

    private function preparePaises()
    {
        $paises = Pais::all();
        $temp = [];
        foreach ($paises as $p) {
            // array_push($temp, $c->id => $c->nome);
            $temp[$p->codigo] = "$p->codigo - $p->nome";
        }
        return $temp;
    }

    private function prepareCities()
    {
        $cities = City::all();
        $temp = [];
        foreach ($cities as $c) {
            // array_push($temp, $c->id => $c->nome);
            $temp[$c->id] = $c->nome . " ($c->uf)";
        }
        return $temp;
    }

    private function prepareUFs()
    {
        return [
            "AC" => "AC",
            "AL" => "AL",
            "AM" => "AM",
            "AP" => "AP",
            "BA" => "BA",
            "CE" => "CE",
            "DF" => "DF",
            "ES" => "ES",
            "GO" => "GO",
            "MA" => "MA",
            "MG" => "MG",
            "MS" => "MS",
            "MT" => "MT",
            "PA" => "PA",
            "PB" => "PB",
            "PE" => "PE",
            "PI" => "PI",
            "PR" => "PR",
            "RJ" => "RJ",
            "RN" => "RN",
            "RS" => "RS",
            "RO" => "RO",
            "RR" => "RR",
            "SC" => "SC",
            "SE" => "SE",
            "SP" => "SP",
            "TO" => "TO"

        ];
    }

    public function checkFacId(Request $request)
    {
        $ref_no = $request->ref_no;

        $valid = 0;
        if (!empty($ref_no)) {
            $business_id = $request->session()->get('user.business_id');
            $query = Transaction::where('business_id', $business_id)
                ->where('ref_no', $ref_no);
            $count = $query->count();
            if ($count > 0) {
                $valid = 1;
            }
        }
        return response()->json(['valid' => $valid]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('cxp.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {
                return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
            }

            //Validate document size
            $request->validate([
                'document' => 'file|max:' . (config('constants.document_size_limit') / 1000)
            ]);

            $transaction_data = $request->only(['ref_no', 'transaction_date', 'fecha_vence', 'plazo', 'location_id', 'final_total', 'expense_for', 'additional_notes', 'expense_category_id', 'tax_id']);

            $user_id = $request->session()->get('user.id');
            $transaction_data['business_id'] = $business_id;
            $transaction_data['created_by'] = $user_id;

            $transaction_data['contact_id'] = $request->contact_id;

            $transaction_data['type'] = 'expense';
            $transaction_data['status'] = 'final';
            $transaction_data['plazo'] = $request->plazo ? $request->plazo : 0;
            $transaction_data['payment_status'] = 'due';
            $transaction_data['transaction_date'] = $this->transactionUtil->uf_date($transaction_data['transaction_date'], true);
            $transaction_data['final_total'] = $this->transactionUtil->num_uf(
                $transaction_data['final_total']
            );

            $transaction_data['total_before_tax'] = $transaction_data['final_total'];
            if (!empty($transaction_data['tax_id'])) {
                $tax_details = TaxRate::find($transaction_data['tax_id']);
                $transaction_data['total_before_tax'] = $this->transactionUtil->calc_percentage_base($transaction_data['final_total'], $tax_details->amount);
                $transaction_data['tax_amount'] = $transaction_data['final_total'] - $transaction_data['total_before_tax'];
            }

            DB::beginTransaction();

            //Update reference count
            $ref_count = $this->transactionUtil->setAndGetReferenceCount('expense');
            //Generate reference number
            if (empty($transaction_data['ref_no'])) {
                $transaction_data['ref_no'] = $this->transactionUtil->generateReferenceNumber('expense', $ref_count);
            }

            //upload document
            $document_name = $this->transactionUtil->uploadFile($request, 'document', 'documents');
            if (!empty($document_name)) {
                $transaction_data['document'] = $document_name;
            }
            $transaction = Transaction::create($transaction_data);
            $descripciones = $request->input('descripcion');
            $precios = $request->input('precio');
            $cantidades = $request->input('cantidad');

            // Iterar sobre los valores para procesarlos
            if ($descripciones == null) {
                $output = [
                    'success' => 0,
                    'msg' => __('Debe agregar líneas en el detalle')
                ];
                return redirect()->back()->with('status', $output);
            }
            foreach ($descripciones as $index => $descripcion) {
                $precio = str_replace(',', '', $precios[$index]);
                $cantidad = $cantidades[$index];

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'total' => $precio,
                    'cantidad' => $cantidad,
                    'descripcion' => $descripcion,
                ]);
            }
            // Registrar la auditoría de los datos guardados
            $cambios = [];
            $transaction_audit = $request->only([
                'ref_no',
                'final_total',
                'transaction_date',
                'additional_notes',
                'fecha_vence',
                'plazo'
            ]);
            foreach ($transaction_audit as $campo => $valor) {
                switch ($campo) {
                    case "ref_no":
                        $campo = "factura";
                        break;
                    case "final_total":
                        $campo = "total";
                        break;
                    case "transaction_date":
                        $campo = "fecha";
                        break;
                    case "additional_notes":
                        $campo = "notas";
                        break;
                    case "contact_id":
                        $campo = "número de cliente";
                        break;
                }
                $campo_formateado = str_replace('_', ' ', $campo);
                // Agregar el campo y su valor al arreglo de cambios
                $cambios[] = "$campo_formateado => $valor *.*";
            }

            // Guardar los cambios en la tabla de auditoría
            $user_id = $request->session()->get('user.id');
            $audit['type'] = "cxp";
            $audit['type_transaction'] = "creación";
            $audit['change'] = implode("\n", $cambios); // Cada cambio en una nueva línea
            $audit['update_by'] = $user_id;
            Audit::create($audit);
            //add expense payment
            //$this->transactionUtil->createOrUpdatePaymentLines($transaction, $request->input('payment'), $business_id);

            //update payment status
            $this->transactionUtil->updatePaymentStatus($transaction->id, $transaction->final_total);

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __('Se agregó la cuenta por pagar con éxito')
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            echo $e->getMessage();
            die;

            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return redirect('expenses')->with('status', $output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('cxp.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        //Check if subscribed or not
        if (!$this->moduleUtil->isSubscribed($business_id)) {
            return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
        }

        $business_locations = BusinessLocation::forDropdown($business_id);

        $expense_categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');
        $expense = Transaction::where('business_id', $business_id)
            ->where('id', $id)
            ->first();

        $expense_details = DetailTransaction::where('transaction_id', $id)->get();

        $users = User::forDropdown($business_id, true, true);

        $taxes = TaxRate::forBusinessDropdown($business_id, true, true);

        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('guarantor.create')) {
            $types['guarantor'] = __('report.guarantor');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }

        return view('expense.edit')
            ->with('tipo', '')
            ->with('cities', $this->prepareCities())
            ->with('estados', $this->prepareUFs())
            ->with('paises', $this->preparePaises())
            ->with(compact('expense', 'expense_details', 'expense_categories', 'business_locations', 'users', 'taxes', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('cxp.update')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //Validate document size
            $request->validate([
                'document' => 'file|max:' . (config('constants.document_size_limit') / 1000)
            ]);

            $transaction_data = $request->only(['ref_no', 'fecha_vence', 'plazo', 'transaction_date', 'location_id', 'final_total', 'expense_for', 'additional_notes', 'expense_category_id', 'tax_id']);

            $business_id = $request->session()->get('user.business_id');

            //Check if subscribed or not
            if (!$this->moduleUtil->isSubscribed($business_id)) {
                return $this->moduleUtil->expiredResponse(action('ExpenseController@index'));
            }

            $transaction_data['transaction_date'] = $this->transactionUtil->uf_date($transaction_data['transaction_date'], true);
            $transaction_data['final_total'] = $this->transactionUtil->num_uf(
                $transaction_data['final_total']
            );

            $transaction_data['contact_id'] = $request->contact_id;
            $transaction_data['plazo'] = $request->plazo ? $request->plazo : 0;

            //upload document
            $document_name = $this->transactionUtil->uploadFile($request, 'document', 'documents');
            if (!empty($document_name)) {
                $transaction_data['document'] = $document_name;
            }

            $transaction_data['total_before_tax'] = $transaction_data['final_total'];
            if (!empty($transaction_data['tax_id'])) {
                $tax_details = TaxRate::find($transaction_data['tax_id']);
                $transaction_data['total_before_tax'] = $this->transactionUtil->calc_percentage_base($transaction_data['final_total'], $tax_details->amount);
                $transaction_data['tax_amount'] = $transaction_data['final_total'] - $transaction_data['total_before_tax'];
            }

            $transaction = Transaction::where('business_id', $business_id)
                ->where('id', $id)->first();

            $cambios = [];
            $transaction_audit = $request->only([
                'ref_no',
                'final_total',
                'transaction_date',
                'additional_notes',
                'fecha_vence',
                'plazo'
            ]);
            $cambios[] = "Factura modificada: $transaction->ref_no";
            foreach ($transaction_audit as $campo => $nuevo_valor) {
                $valor_antiguo = $transaction->$campo;
                if ($nuevo_valor != $valor_antiguo) {
                    switch ($campo) {
                        case "ref_no":
                            $campo = "factura";
                            break;
                        case "final_total":
                            $campo = "total";
                            break;
                        case "transaction_date":
                            $campo = "fecha";
                            break;
                        case "additional_notes":
                            $campo = "notas";
                            break;
                        case "contact_id":
                            $campo = "número de cliente";
                            break;
                    }
                    // Reemplazar guiones bajos por espacios en el nombre del campo
                    $campo_formateado = str_replace('_', ' ', $campo);

                    // Agregar el cambio al arreglo de auditoría                   
                    $cambios[] .= "$campo_formateado => Se cambió el valor: $valor_antiguo por el valor: $nuevo_valor";
                }
            }

            $transaction->update($transaction_data);
            $user_id = $request->session()->get('user.id');
            if (!empty($cambios)) {
                $audit = new Audit();
                $audit->type = "cxp";
                $audit->type_transaction = "modificación";
                $audit->change = implode("*.*\n", $cambios);
                $audit->update_by = $user_id;
                $audit->save();
            }

            $descripciones = $request->input('descripcion');
            $precios = $request->input('precio');
            $cantidades = $request->input('cantidad');
            $detalles_ids = $request->input('detalle_id');

            // Obtener los IDs de los detalles existentes para determinar cuáles eliminar
            $existingDetailIds = DetailTransaction::where('transaction_id', $id)->pluck('id')->toArray();
            // Arrays para manejar nuevos y actualizados            
            $updatedDetails = [];
            if ($descripciones != null) {
                foreach ($descripciones as $index => $descripcion) {
                    $precio = str_replace(',', '', $precios[$index]);
                    $cantidad = $cantidades[$index];
                    $detalleId = $detalles_ids[$index] ?? null; // Puede ser null si es nuevo

                    if ($detalleId) {
                        $detalle = DetailTransaction::find($detalleId);
                        if ($detalle) {
                            $detalle->update([
                                'descripcion' => $descripcion,
                                'total' => $precio,
                                'cantidad' => $cantidad
                            ]);
                            $updatedDetails[] = $detalleId;
                        }
                    } else {
                        DetailTransaction::create([
                            'transaction_id' => $id,
                            'descripcion' => $descripcion,
                            'total' => $precio,
                            'cantidad' => $cantidad
                        ]);
                    }
                }
            }

            $detailsToDelete = array_diff($existingDetailIds, $updatedDetails);
            DetailTransaction::destroy($detailsToDelete);

            $output = [
                'success' => 1,
                'msg' => __('Cuenta editada con éxito')
            ];
        } catch (\Exception $e) {
            \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => __($e->getMessage())
            ];
        }

        return redirect('expenses')->with('status', $output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!auth()->user()->can('expense.access') && !auth()->user()->can('cxp.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');
                $user_id = $request->session()->get('user.id');

                $expense = Transaction::where('business_id', $business_id)
                    ->where('type', 'expense')
                    ->where('id', $id)
                    ->first();
                $expense->delete();

                // Guardar auditoría antes de eliminar el registro
                $audit = new Audit();
                $audit->type = "cxp";
                $audit->type_transaction = "eliminación";
                $audit->change = "Cuenta eliminada, factura: {$expense->ref_no} eliminada el día: " . Carbon::now()->format('Y-m-d H:i:s');
                $audit->update_by = $user_id;
                $audit->save();
                //Delete account transactions
                AccountTransaction::where('transaction_id', $expense->id)->delete();

                $output = [
                    'success' => true,
                    'msg' => __("Cuenta eliminada!")
                ];
            } catch (\Exception $e) {
                \Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __("messages.something_went_wrong")
                ];
            }

            return $output;
        }
    }
}

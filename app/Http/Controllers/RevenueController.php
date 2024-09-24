<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use App\Utils\ModuleUtil;
use App\Utils\TransactionUtil;
use App\Models\BusinessLocation;
use App\Models\ExpenseCategory;
use App\Models\User;
use App\Models\City;
use App\Models\Revenue;
use App\Models\TaxRate;
use App\Models\Account;
use App\Models\PaymentRevenue;
use App\Utils\ContactUtil;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RevenueController extends Controller
{

    protected $contactUtil;

    public function __construct(TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ContactUtil $contactUtil)
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

        $this->contactUtil = $contactUtil;
    }

    public function index()
    {
        if (!auth()->user()->can('cxc.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {

            $business_id = request()->session()->get('user.business_id');

            $revenues = Revenue::where('revenues.business_id', $business_id)
                ->leftJoin(
                    'contacts AS ct',
                    'revenues.contact_id',
                    '=',
                    'ct.id'
                )
                ->leftJoin(
                    'payment_revenues AS pr',
                    'revenues.id',
                    '=',
                    'pr.revenue_id'
                )
                ->select([
                    'revenues.id as rev_id',
                    'revenues.referencia',
                    'revenues.expense_category_id',
                    'revenues.location_id',
                    'revenues.status',
                    'revenues.valor_total',
                    'revenues.detalle',
                    'revenues.created_by',
                    'ct.contact_id',
                    'ct.name',
                    DB::raw('COALESCE(SUM(pr.amortiza), 0) as amount_paid')
                ])
                ->groupBy('revenues.id', 'ct.contact_id', 'ct.name')
                ->orderBy('rev_id', 'desc');

            if (request()->has('status')) {
                $status = request()->get('status');
                if ($status == 0) {
                    $revenues->where('revenues.status', 0);
                }

                if ($status == 1) {
                    $revenues->where('revenues.status', 1);
                }
            }

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $revenues->whereDate('revenues.created_at', '>=', $start)
                    ->whereDate('revenues.created_at', '<=', $end);
            }

            /* if (request()->has('location_id')) {
                $location_id = request()->get('location_id');
                if (!empty($location_id)) {
                    $revenues->where('revenues.location_id', $location_id);
                }
            } */

            /* $permitted_locations = auth()->user()->permitted_locations();
            if ($permitted_locations != 'all') {
                $revenues->whereIn('revenues.location_id', $permitted_locations);
            } */

            return Datatables::of($revenues)

                ->addColumn(
                    'action',
                    function ($row) {
                        $html = '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle btn-xs" 
                    data-toggle="dropdown" aria-expanded="false"> Acciones<span class="caret"></span><span class="sr-only">Toggle Dropdown
                    </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-left" role="menu">';



                        $html .= '<li><a href="' . action('RevenueController@receive', [$row->rev_id]) . '"><i class="fa fa-eye"></i> Detallar</a></li>';

                        $html .= '<li>
                    <a data-href="' . action('RevenueController@destroy', [$row->rev_id]) . '" class="delete_revenue"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>
                    </li>
                    <li class="divider"></li>';

                        $html .= '</ul></div>';
                        return $html;
                    }
                )
                ->editColumn('amount_paid', function ($row) {
                    $due = $row->valor_total - $row->amount_paid;
                    return '<span class="display_currency payment_due" data-currency_symbol="true" data-orig-value="' . $due . '">' . $due . '</span>';
                })
                ->editColumn(
                    'status',
                    function ($row) {
                        if (($row->valor_total - $row->amount_paid) == 0) {
                            return '<span class="label bg-success">Pagado</span>';
                        } else {
                            return '<span class="label bg-yellow">Pendiente</span>';
                        }
                    }
                )

                ->addColumn(
                    'contact',
                    function ($row) {

                        return $row->name;
                    }
                )
                ->editColumn(
                    'valor_total',
                    '<span class="display_currency final-total" data-currency_symbol="true" data-orig-value="{{$valor_total}}">{{$valor_total}}</span>'
                )
                ->removeColumn('rev_id')
                ->rawColumns(['action', 'checkbox', 'valor_total', 'status', 'amount_paid'])
                ->make(true);
        }

        $business_id = request()->session()->get('user.business_id');

        $categories = ExpenseCategory::where('business_id', $business_id)
            ->pluck('name', 'id');

        $users = User::forDropdown($business_id, false, true, true);

        $business_locations = BusinessLocation::forDropdown($business_id, true);


        return view('revenues.index')
            ->with(compact('categories', 'business_locations', 'users'));
    }
    public function storeRow($id)
    {
        if (!auth()->user()->can('cxc.create')) {
            return response()->json(['success' => false, 'msg' => 'No cuentas con permisos para realizar modificaciones, o insertar nuevas lineas']);
        }
        try {
            $record = PaymentRevenue::where('payment_revenues.revenue_id', $id)
                ->join('revenues', 'payment_revenues.revenue_id', '=', 'revenues.id')
                ->select(
                    'payment_revenues.id',
                    'payment_revenues.created_at',
                    'payment_revenues.monto_general',
                    'revenues.tasa',
                    'revenues.valor_total as monto_general_first',
                    'revenues.cuota'
                )
                ->orderBy('payment_revenues.monto_general', 'asc')
                ->first();
            $monto_general = isset($record->monto_general) ? $record->monto_general : $record->monto_general_first;
            $interes = round($monto_general * ($record->tasa / 100), 2);
            $cxc_pay['revenue_id'] = $id;
            $cxc_pay['monto_general'] = round($monto_general - ($record->cuota - $interes), 2);
            $cxc_pay['interes_c'] = round($interes, 2);
            $cxc_pay['paga'] = round($record->cuota, 2);
            $cxc_pay['amortiza'] = round($record->cuota - $interes, 2);
            PaymentRevenue::create($cxc_pay);
            return response()->json(['success' => true]);
        } catch (Exception $th) {
            return response()->json(['success' => false, 'msg' => 'Ocurrió un error al insertar la linea']);
        }
    }
    public function destroy($id)
    {
        if (!auth()->user()->can('cxc.delete')) {
            $output = [
                'success' => false,
                'msg' => __("No cuentas con permisos para eliminar cuentas")
            ];
            return $output;
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $revenue = Revenue::where('business_id', $business_id)
                    ->where('id', $id)
                    ->first();
                $revenue->delete();


                $output = [
                    'success' => true,
                    'msg' => 'cuenta por cobrar removida.'
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
    public function receive($id)
    {
        $item = DB::table('revenues as rev')
            ->join('plan_ventas as pv', 'rev.plan_venta_id', '=', 'pv.id')
            ->join('products as vv', 'pv.vehiculo_venta_id', '=', 'vv.id')
            ->join('contacts as cli', 'rev.contact_id', '=', 'cli.id')
            ->select(
                'vv.name as veh_venta',
                'vv.model as modelo',
                'vv.placa as placa',
                'rev.id as id',
                'rev.created_at as created_at',
                'rev.tipo_prestamo as tipo_prestamo',
                'rev.moneda as moneda',
                'rev.cuota as cuota',
                'rev.tasa as tasa',
                'rev.valor_total as valor_total',
                'rev.detalle as detalle',
                'rev.plazo as plazo',
                'cli.name as name',
                'cli.identificacion as identificacion',
                'cli.tipo_identificacion as tipo_identificacion',
                'cli.landline as telephone',
                'cli.mobile as celular',
                'cli.email as email',
                'cli.landmark as direccion'
            )
            ->where('rev.id', $id)
            ->first();

        //$payment_types = $this->transactionUtil->payment_types(); 
        
        $canUpdate = true;
        if(!auth()->user()->can('cxc.update')){
            $canUpdate = false;
        }

        if (request()->ajax()) {
            $payment_revenues = PaymentRevenue::where('payment_revenues.revenue_id', $id)
                ->select([
                    'payment_revenues.id as id',
                    'payment_revenues.created_at as created_at',
                    'payment_revenues.fecha_interes as fecha_interes',
                    'payment_revenues.referencia as referencia',
                    'payment_revenues.detalle as detalle',
                    'payment_revenues.paga as paga',
                    'payment_revenues.amortiza as amortiza',
                    'payment_revenues.interes_c as interes_c',
                    'payment_revenues.monto_general as monto_general',
                    'payment_revenues.revenue_id as rev_id'
                ]);

            if (!empty(request()->start_date) && !empty(request()->end_date)) {
                $start = request()->start_date;
                $end = request()->end_date;
                $payment_revenues->whereDate('payment_revenues.created_at', '>=', $start)
                    ->whereDate('payment_revenues.created_at', '<=', $end);
            }
            return Datatables::of($payment_revenues)
                ->addColumn(
                    'action',
                    '<a href="{{ action(\'RevenueController@viewPayment\', [$id,$rev_id]) }}" class="btn btn-xs btn-info view-payment"><i class="glyphicon glyphicon-print"></i></a>
                     @can("cxc.delete")
                        <button data-href="{{ action(\'RevenueController@destroyRow\', [$id]) }}" class="btn btn-xs btn-danger delete_row_button"><i class="glyphicon glyphicon-trash"></i></button>
                    @endcan
                    '
                )
                ->addColumn(
                    'calcular',
                    '
                     @can("cxc.update")
                        <button data-href="{{ action(\'RevenueController@updateCalc\', [$id]) }}" class="btn btn-xs btn-success update_row_button text-center"><i class="fas fa-calculator"></i></button>
                    @endcan
                    '
                )
                ->editColumn(
                    'created_at',
                    '@can("cxc.update")
                    {!! Form::text("created_at", @format_date($created_at), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("created_at", @format_date($created_at), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->editColumn(
                    'fecha_interes',
                    '@can("cxc.update")
                    {!! Form::text("fecha_interes", @format_date($fecha_interes), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("fecha_interes", @format_date($fecha_interes), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->editColumn(
                    'referencia',
                    '
                    @can("cxc.update")
                    {!! Form::text("referencia", $referencia, array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("referencia", $referencia, array_merge(["class" => "form-control"],  ["readonly"])) !!}
                    @endcan
                    
                '
                )
                ->editColumn(
                    'detalle',
                    '
                    @can("cxc.update")
                    {!! Form::text("detalle", $detalle, array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("detalle", $detalle, array_merge(["class" => "form-control"],  ["readonly"])) !!}
                    @endcan                    
                '
                )
                ->editColumn(
                    'paga',
                    '@can("cxc.update")
                    {!! Form::text("paga", number_format($paga, 2, ".", ","), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("paga", number_format($paga, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->editColumn(
                    'amortiza',
                    '@can("cxc.update")
                    {!! Form::text("amortiza", number_format($amortiza, 2, ".", ","), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("amortiza", number_format($amortiza, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->editColumn(
                    'interes_c',
                    '@can("cxc.update")
                    {!! Form::text("interes_c", number_format($interes_c, 2, ".", ","), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("interes_c", number_format($interes_c, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->editColumn(
                    'monto_general',
                    '@can("cxc.update")
                    {!! Form::text("monto_general", number_format($monto_general, 2, ".", ","), array_merge(["class" => "form-control"])) !!}
                    @else
                    {!! Form::text("monto_general", number_format($monto_general, 2, ".", ","), array_merge(["class" => "form-control"], ["readonly"])) !!}
                    @endcan'
                )
                ->rawColumns(['action', 'calcular', 'fecha_interes', 'created_at', 'referencia', 'detalle',  'monto_general', 'amortiza', 'paga', 'interes_c', 'monto_general'])
                ->make(true);
        }

        return view('revenues.receive', compact('item', 'id','canUpdate'));
    }
    public function updatePayment(Request $request, $id, $revenue_id)
    {
        try {
            $detalle_planilla = PaymentRevenue::findOrFail($id);
            $column = $request->input('column');
            $value = $request->input('value');
            $detalle[$column] = $value;
            if ($column == "created_at" || $column == "fecha_interes") {
                $fechaFormateada = Carbon::createFromFormat('d/m/Y', $value);
                $detalle[$column] = $fechaFormateada;
            }
            $detalle_planilla->update($detalle);
            if ($column === "paga") {
                $record = PaymentRevenue::where('payment_revenues.revenue_id', $revenue_id)
                    ->where('payment_revenues.id', '!=', $id)
                    ->join('revenues', 'payment_revenues.revenue_id', '=', 'revenues.id')
                    ->select(
                        'payment_revenues.id',
                        'payment_revenues.created_at',
                        'payment_revenues.monto_general',
                        'revenues.tasa',
                        'revenues.valor_total as monto_general_first',
                        'revenues.cuota'
                    )
                    ->orderBy('payment_revenues.monto_general', 'asc')
                    ->first();
                $lastRecord = PaymentRevenue::orderBy('id', 'desc')->first();
                if ($id == $lastRecord->id) {
                    $monto_general = isset($record->monto_general) ? $record->monto_general : $record->monto_general_first;
                    $interes = round($monto_general * ($record->tasa / 100), 2);
                    $cxc_pay['monto_general'] = round($monto_general - ($value - $interes), 2);
                    $cxc_pay['interes_c'] = round($interes, 2);
                    $cxc_pay['amortiza'] = round($value - $interes, 2);
                    $detalle_planilla->update($cxc_pay);
                }
            }
        } catch (Exception $th) {
            return response()->json(['success' => false, 'msg' => $th->getMessage()]);
        }
        return response()->json(['success' => true, 'msg' => $column]);
    }
    public function updateCalc(Request $request, $id)
    {
        $detalle_planilla = PaymentRevenue::findOrFail($id);
        $saldo = isset($request->saldo) ? floatval(str_replace(',', '', $request->saldo)) : 0;
        $amortiza = isset($request['amortiza']) ? floatval(str_replace(',', '', $request['amortiza'])) : 0;
        $detalle["monto_general"] = $saldo - $amortiza;
        $detalle_planilla->update($detalle);

        return response()->json(['success' => true,'request' => $saldo]);
    }
    public function destroyRow($id)
    {
        if (request()->ajax()) {
            try {
                $planilla = PaymentRevenue::where('id', $id)->first();
                $planilla->delete();
                $output = [
                    'success' => true,
                    'msg' => __("Linea eliminada con éxito")
                ];
            } catch (\Exception $e) {
                Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => $e->getMessage()
                ];
            }

            return $output;
        }
    }
    public function viewPayment($id, $revenue_id)
    {
        try {
            $item = DB::table('revenues as rev')
                ->join('plan_ventas as pv', 'rev.plan_venta_id', '=', 'pv.id')
                ->join('payment_revenues as pr', 'rev.id', '=', 'pr.revenue_id')
                ->join('products as vv', 'pv.vehiculo_venta_id', '=', 'vv.id')
                ->join('contacts as cli', 'rev.contact_id', '=', 'cli.id')
                ->select(
                    'vv.name as veh_venta',
                    'vv.model as modelo',
                    'vv.placa as placa',
                    'vv.combustible as combustible',
                    'vv.color as color',
                    'rev.id as id',
                    'rev.created_at as created_at',
                    'rev.tipo_prestamo as tipo_prestamo',
                    'rev.moneda as moneda',
                    'rev.cuota as cuota',
                    'rev.tasa as tasa',
                    'rev.valor_total as valor_total',
                    'rev.detalle as detalle',
                    'rev.plazo as plazo',
                    'cli.name as name',
                    'cli.identificacion as identificacion',
                    'cli.tipo_identificacion as tipo_identificacion',
                    'cli.landline as telephone',
                    'cli.mobile as celular',
                    'cli.email as email',
                    'cli.landmark as direccion',
                    'pr.paga as paga',
                    'pr.interes_c as interes_c',
                    'pr.amortiza as amortiza',
                    'pr.fecha_interes as fecha_interes',
                    'pr.created_at as fecha_pago',
                    'pr.referencia as referencia',
                    'pr.monto_general as monto_general'
                )
                ->where('rev.id', $revenue_id)
                ->where('pr.id', $id)
                ->first();
            return view('revenues.view-modal')->with(compact(
                'item',
                'id'
            ));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
        }
    }
    public function sendPaymentsWhatsDetallado($id, $revenue_id)
    {
        try {
            // Obtener los datos necesarios
            $item = DB::table('revenues as rev')
                ->join('plan_ventas as pv', 'rev.plan_venta_id', '=', 'pv.id')
                ->join('payment_revenues as pr', 'rev.id', '=', 'pr.revenue_id')
                ->join('products as vv', 'pv.vehiculo_venta_id', '=', 'vv.id')
                ->join('contacts as cli', 'rev.contact_id', '=', 'cli.id')
                ->select(
                    'vv.name as veh_venta',
                    'vv.model as modelo',
                    'vv.placa as placa',
                    'vv.combustible as combustible',
                    'vv.color as color',
                    'rev.id as id',
                    'rev.created_at as created_at',
                    'rev.tipo_prestamo as tipo_prestamo',
                    'rev.moneda as moneda',
                    'rev.cuota as cuota',
                    'rev.tasa as tasa',
                    'rev.valor_total as valor_total',
                    'rev.detalle as detalle',
                    'rev.plazo as plazo',
                    'cli.name as name',
                    'cli.identificacion as identificacion',
                    'cli.tipo_identificacion as tipo_identificacion',
                    'cli.landline as telephone',
                    'cli.mobile as celular',
                    'cli.email as email',
                    'cli.landmark as direccion',
                    'pr.paga as paga',
                    'pr.interes_c as interes_c',
                    'pr.fecha_interes as fecha_interes',
                    'pr.created_at as fecha_pago',
                    'pr.amortiza as amortiza',
                    'pr.referencia as referencia',
                    'pr.monto_general as monto_general'
                )
                ->where('rev.id', $revenue_id)
                ->where('pr.id', $id)
                ->first();

            // Nombre del PDF y generación
            $namePdf = "Comprobante_de_recibo_de_pago_" . time() . ".pdf";
            $for_pdf = true;
            $logo_url = public_path('images/logo_ag_cor.png');

            // Renderización del HTML para PDF
            $html = view('revenues.whats')->with(compact('item', 'for_pdf', 'logo_url'))->render();
            $mpdf = $this->getMpdf();
            $mpdf->WriteHTML($html);

            // Definir la ruta para guardar el archivo en una ubicación pública
            $file = public_path('pdfs/') . $namePdf;
            $mpdf->Output($file, 'F');

            // Crear el enlace de WhatsApp
            $publicFileUrl = url('pdfs/' . $namePdf);
            $whatsappMessage = urlencode("Hola, en la siguiente URL puedes encontrar el recibo del pago realizado " . $publicFileUrl);

            // Número de teléfono del cliente (modificar según sea necesario)
            $whatsappNumber = $item->celular;
            $whatsappLink = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";

            // Retornar el enlace en la respuesta AJAX
            $output = [
                'success' => true,
                'msg' => __('Se generó el enlace para compartir por WhatsApp, se abrió una nueva ventana'),
                'whatsapp_link' => $whatsappLink
            ];
        } catch (\Exception $e) {
            Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());

            $output = [
                'success' => false,
                'msg' => "File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage()
            ];
        }

        return $output;
    }
}

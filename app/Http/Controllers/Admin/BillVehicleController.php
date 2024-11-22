<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\Contact;
use App\Models\DetailTransaction;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\VehicleBill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function indexBill($id,$type)
    {
        $was_received = Product::where('id',$id)->first()->receive_date;
        $business_id = request()->session()->get('user.business_id');
        $all_cars = Product::where('products.business_id', $business_id)
            ->join('vehicle_bills', 'products.id', '=', 'vehicle_bills.product_id')
            ->select(
                'products.id',
                DB::raw("CONCAT(products.name, ' (', products.bin, ')') as name")
            )
            ->groupBy('products.id', 'products.name', 'products.model')
            ->get();

        $cars = $all_cars->pluck('name', 'id');

        $bills = VehicleBill::where('vehicle_bills.business_id', $business_id)
            ->where('vehicle_bills.product_id', $id)
            ->join('products', 'vehicle_bills.product_id', '=', 'products.id')
            ->join('contacts', 'vehicle_bills.proveedor_id', '=', 'contacts.id')
            ->leftJoin('users AS usr', 'vehicle_bills.created_by', '=', 'usr.id')
            ->select([
                'vehicle_bills.id as bill_id',
                'vehicle_bills.fecha_compra as fecha_compra',
                DB::raw("CONCAT(products.name, ' (', products.model, ')') as name"),
                'contacts.name as prov_name',
                'products.id as product_id',
                'vehicle_bills.descripcion as descripcion',
                'vehicle_bills.monto as monto',
                'vehicle_bills.factura as factura',
                'vehicle_bills.business_id',
                'vehicle_bills.created_at',
                DB::raw("CONCAT(COALESCE(usr.first_name, ''),' ',COALESCE(usr.last_name,'')) as added_by")
            ]);

        if($was_received != ""){
            if($type != 1){
                $bills->whereDate('vehicle_bills.fecha_compra', '<', $was_received);
            }else{
                $bills->whereDate('vehicle_bills.fecha_compra', '>=', $was_received);
            }           
        }
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
                    return \Carbon\Carbon::parse($row->fecha_compra)->format('Y/m/d g:i A');
                })
                ->editColumn('monto', '{{"₡ ". number_format($monto, 2, ".", ",") }}')
                ->rawColumns(['action', 'name'])
                ->make(true);
        }
        $totalMonto = (clone $bills)->sum('vehicle_bills.monto');        
        $cant_gastos = (clone $bills)->count();

        return view('admin.vehicle-bills.index', compact('id', 'totalMonto', 'cant_gastos', 'cars','was_received','type'));
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
            DB::beginTransaction();

            // Obtener los detalles de la factura del request
            $bill_details = $request->only([
                'fecha_compra',
                'monto',
                'factura',
                'descripcion',
                'product_id',
                'proveedor_id'
            ]);

            // Validar y formatear la fecha
            $fechaFormateada = null;
            if (preg_match('/\d{2}\/\d{2}\/\d{4}$/', $request->fecha_compra)) {
                // Formato dd/MM/yyyy
                $fechaFormateada = Carbon::createFromFormat('d/m/Y', $request->fecha_compra);
            } elseif (preg_match('/\d{2}\/\d{2}\/\d{2}$/', $request->fecha_compra)) {
                // Formato dd/MM/yy
                $fechaFormateada = Carbon::createFromFormat('d/m/y', $request->fecha_compra);
            } else {
                return response()->json(['success' => false, 'msg' => 'Formato de fecha inválido, formato correcto (dd/MM/yyyy o dd/MM/yy)']);
            }

            // Verificar que la fecha coincide con el formato proporcionado
            if (!$fechaFormateada || !in_array($fechaFormateada->format('d/m/Y'), [$request->fecha_compra, $fechaFormateada->format('d/m/y')])) {
                return response()->json(['success' => false, 'msg' => 'Formato de fecha inválido']);
            }

            // Preparar los datos de la nueva factura
            $monto = isset($request->monto) ? floatval(str_replace(',', '', $request->monto)) : null;
            $business_id = $request->session()->get('user.business_id');
            $bill_details['business_id'] = $business_id;
            $bill_details['fecha_compra'] = $fechaFormateada;
            $bill_details['monto'] = $monto;
            $bill_details['is_cxp'] = $request->is_cxp ? 1 : 0;

            // Crear el registro en VehicleBill
            $bill = VehicleBill::create($bill_details);

            // Ingresar a cuentas por pagar si es CxP
            if ($request->is_cxp) {
                $user_id = $request->session()->get('user.id');
                $transaction_data = [
                    'business_id' => $business_id,
                    'created_by' => $user_id,
                    'location_id' => 3,
                    'type' => "expense",
                    'status' => "final",
                    'is_quotation' => "0",
                    'payment_status' => "due",
                    'contact_id' => $request->proveedor_id,
                    'ref_no' => $request->factura,
                    'transaction_date' => $fechaFormateada,
                    'fecha_vence' => $request->fecha_vence,
                    'additional_notes' => $request->descripcion,
                    'final_total' => $monto,
                    'total_before_tax' => $monto,
                    'plazo' => $request->plazo
                ];
                $transaction = Transaction::create($transaction_data);

                DetailTransaction::create([
                    'transaction_id' => $transaction->id,
                    'total' => $monto,
                    'cantidad' => 1,
                    'descripcion' => $request->descripcion
                ]);
            }

            // Registrar la auditoría de los datos guardados
            $cambios = [];
            $bill_details_audit = $request->only([
                'fecha_compra',
                'monto',
                'factura',
                'descripcion'
            ]);
            foreach ($bill_details_audit as $campo => $valor) {
                // Reemplazar guiones bajos por espacios en el nombre del campo
                $campo_formateado = str_replace('_', ' ', $campo);
                // Agregar el campo y su valor al arreglo de cambios
                $cambios[] = "$campo_formateado => $valor *.*";
            }

            // Guardar los cambios en la tabla de auditoría
            $user_id = $request->session()->get('user.id');
            $audit = new Audit();
            $audit->type = "gastos";
            $audit->type_transaction = "creación";
            $audit->change = implode("\n", $cambios); // Cada cambio en una nueva línea
            $audit->update_by = $user_id;
            $audit->save();

            DB::commit();

            $output = [
                'success' => 1,
                'msg' => __("Se agregó el gasto con éxito")
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . "Line:" . $e->getLine() . "Message:" . $e->getMessage());
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
                DB::beginTransaction();
                $business_id = request()->session()->get('user.business_id');
                $user_id = request()->session()->get('user.id'); // Obtener el ID del usuario
                $bill = VehicleBill::where('business_id', $business_id)
                    ->where('id', $id)->first();
                $is_cxp = $bill->is_cxp;

                if ($is_cxp == 1) {
                    $factura = Transaction::where('business_id', $business_id)
                        ->where('ref_no', $bill->factura)
                        ->firstOrFail();
                    $factura->delete();
                }

                // Guardar auditoría antes de eliminar el registro
                $msg_cxp = $is_cxp == 1 ? " (Ligada a CxP)" : "";
                $audit = new Audit();
                $audit->type = "gastos";
                $audit->type_transaction = "eliminación";
                $audit->change = "Gasto eliminado, factura: {$bill->factura} eliminada el día: " . Carbon::now()->format('Y-m-d H:i:s') . $msg_cxp;
                $audit->update_by = $user_id;
                $audit->save();

                $bill->delete();
                DB::commit();

                $output = [
                    'success' => true,
                    'msg' => __("Gasto eliminado con éxito")
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());

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
            DB::beginTransaction();
            $user_id = $request->session()->get('user.id');

            // Obtener datos actuales del registro
            $bill = VehicleBill::where('business_id', $request->session()->get('user.business_id'))
                ->findOrFail($id);
            $is_cxp = $bill->is_cxp;

            // Crear arreglo para registrar cambios
            $cambios = [];

            // Formatear fecha
            $fechaFormateada = null;
            if (preg_match('/\d{2}\/\d{2}\/\d{4}$/', $request->fecha_compra)) {
                // Formato dd/MM/yyyy
                $fechaFormateada = Carbon::createFromFormat('d/m/Y', $request->fecha_compra);
            } elseif (preg_match('/\d{2}\/\d{2}\/\d{2}$/', $request->fecha_compra)) {
                // Formato dd/MM/yy
                $fechaFormateada = Carbon::createFromFormat('d/m/y', $request->fecha_compra);
            } else {
                return response()->json(['success' => false, 'msg' => 'Formato de fecha inválido, formato correcto (dd/MM/yyyy o dd/MM/yy)']);
            }

            // Verifica si el formato es correcto y que la fecha formateada coincida con la entrada
            if ($fechaFormateada && $fechaFormateada->format('d/m/Y') === $request->fecha_compra || $fechaFormateada->format('d/m/y') === $request->fecha_compra) {
                // Formato válido
                // Aquí puedes continuar con la lógica deseada
            } else {
                return response()->json(['success' => false, 'msg' => 'Formato de fecha inválido']);
            }

            $bill_details = $request->only([
                'fecha_compra',
                'monto',
                'factura',
                'descripcion',
                'product_id',
                'proveedor_id'
            ]);
            $bill_details['fecha_compra'] = $fechaFormateada;
            $monto = isset($request['monto']) ? floatval(str_replace(',', '', $request['monto'])) : 0;
            $bill_details['monto'] = $monto;
            // Verificar cambios y agregar al arreglo de auditoría
            foreach ($bill_details as $campo => $nuevo_valor) {
                $valor_antiguo = $bill->$campo;
                if ($nuevo_valor != $valor_antiguo) {
                    // Reemplazar guiones bajos por espacios en el nombre del campo
                    $campo_formateado = str_replace('_', ' ', $campo);

                    // Agregar el cambio al arreglo de auditoría
                    $cambios[] = "$campo_formateado => Se cambió el valor: $valor_antiguo por el valor: $nuevo_valor";
                }
            }

            // Si es CxP, también actualizar la transacción asociada
            if ($is_cxp == 1) {
                $factura = Transaction::where('business_id', $bill->business_id)
                    ->where('ref_no', $bill->factura)
                    ->firstOrFail();

                $data = [
                    'ref_no' => $request->factura,
                    'contact_id' => $request->proveedor_id,
                    'total_before_tax' => $monto,
                    'final_total' => $monto,
                    'additional_notes' => $request->descripcion,
                    'transaction_date' => $fechaFormateada
                ];

                $factura->update($data);
            }

            // Actualizar datos en bill
            $bill->update($bill_details);
            DB::commit();

            // Log o registro de cambios
            if (!empty($cambios)) {
                $audit = new Audit();
                $audit->type = "gastos";
                $audit->type_transaction = "modificación";
                $audit->change = implode("*.*\n", $cambios);
                $audit->update_by = $user_id;
                $audit->save();
            }

            $output = [
                'success' => 1,
                'msg' => __("Gasto actualizado con éxito")
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency("File:" . $e->getFile() . " Line:" . $e->getLine() . " Message:" . $e->getMessage());

            $output = [
                'success' => 0,
                'msg' => $e->getMessage() . " " . $e->getLine()
            ];
        }

        return redirect('products/bills/' . $request->product_id)->with('status', $output);
    }
}

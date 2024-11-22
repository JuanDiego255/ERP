<div class="modal-dialog modal-lg" role="document" id="cxp_detail">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <div class="row">
                <div class="col-sm-3 text-left invoice-col">
                    <img src="/images/logo_ag_cor.png" alt="Logo de la Empresa">
                </div>
            </div>
            <div class="row">
                <center>
                    <h4 class="modal-title" id="modalTitle">Reporte de Cuentas por Pagar Autos Grecia (S.R.L)</h4>
                    <p>{{ $rango }}</p>
                </center>
            </div>

        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th>Monto Adelantos</th>
                        <th>Fecha Vence</th>
                        <th>Detalle</th>
                        <th>Acumulado</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $current_provider = null; // Variable para rastrear el proveedor actual
                        $subtotal = 0; // Subtotal por proveedor
                        $grand_total = 0; // Subtotal general para todos los proveedores
                    @endphp
                    @foreach ($report as $row)
                        @if ($current_provider !== $row->provider)
                            @if (!is_null($current_provider))
                                <!-- Fila de subtotal del proveedor -->
                                <tr style="font-weight: bold; background-color: #f9f9f9;">
                                    <td colspan="6" class="text-right">Subtotal de {{ $current_provider }}:</td>
                                    <td>₡{{ number_format($subtotal, 2) }}</td>
                                </tr>
                                @php
                                    $grand_total += $subtotal; // Acumular subtotal en el total general
                                @endphp
                            @endif
                            @php
                                $current_provider = $row->provider;
                                $subtotal = 0; // Reiniciar el subtotal para el nuevo proveedor
                            @endphp
                            <!-- Fila del proveedor -->
                            <tr style="font-weight: bold; background-color: #e9ecef;">
                                <td colspan="7">{{ $current_provider }}</td>
                            </tr>
                        @endif
                        @php
                            $subtotal += $row->balance; // Acumular el total de la factura en el subtotal
                        @endphp
                        <tr>
                            <td>{{ $row->invoice }}</td>
                            <td>₡{{ number_format($row->total, 2) }}</td>
                            <td>₡{{ number_format($row->balance, 2) }}</td>
                            <td>₡{{ number_format($row->advance_amount, 2) }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->due_date)->format('d-m-Y') }}</td>
                            <td>{{ $row->detail }}</td>
                            <td>₡{{ number_format($row->total - $row->advance_amount, 2) }}</td>
                        </tr>
                    @endforeach

                    <!-- Fila de subtotal del último proveedor -->
                    @if (!is_null($current_provider))
                        <tr style="font-weight: bold; background-color: #f9f9f9;">
                            <td colspan="6" class="text-right">Subtotal de {{ $current_provider }}:</td>
                            <td>₡{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @php
                            $grand_total += $subtotal; // Acumular el subtotal del último proveedor en el total general
                        @endphp
                    @endif

                    <!-- Fila de subtotal general -->
                    <tr style="font-weight: bold; background-color: #d9edf7;">
                        <td colspan="6" class="text-right">Subtotal General:</td>
                        <td>₡{{ number_format($grand_total, 2) }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThisDetail();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

@php
    $saldo_final = 0;
@endphp
<div class="modal-dialog modal-lg" role="document" id="cuepagModal">
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
                    <h4 class="modal-title" id="modalTitle">Reporte de Cuentas por Pagar (CUEPAG)</h4>
                    <p>{{ $rango }}</p>
                </center>
            </div>  
           
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-ag">
                            <th>Proveedor</th>
                            <th>Facturas</th>
                            <th>Total a pagar</th>
                            <th>Método de pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $row)
                            @php
                                $saldo_final += $row->total - $row->amount;
                            @endphp
                            <tr>
                                <td>{{ $row->provider }}</td>
                                <td>{{ $row->invoices }}</td>
                                <td>₡{{ number_format($row->total - $row->amount, 2) }}</td>
                                <td></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay datos disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 text-danger footer-total">
                            <td>Saldo total a pagar</td>
                            <td></td>
                            <td>₡{{ number_format($saldo_final, 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThis();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

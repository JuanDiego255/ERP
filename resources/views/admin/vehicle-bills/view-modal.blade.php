@php
    $saldo_final = 0;
@endphp
<div class="modal-dialog modal-lg" role="document" id="billsModalReport">
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
                    <h4 class="modal-title" id="modalTitle">Reporte General de Gastos de Vehículos</h4>
                    <p>{{ $rango }}</p>
                </center>
            </div>  
           
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-ag">
                            <th>Fecha</th>
                            <th>Vehículo</th>
                            <th>Proveedor</th>
                            <th>Detalle</th>
                            <th>Factura</th>
                            <th>Monto</th>
                            <th>Vendido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($report as $row)
                            @php
                                $saldo_final += $row->monto;
                            @endphp
                            <tr>
                                <td>{{ $row->fecha_compra }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->prov_name }}</td>
                                <td>{{ $row->descripcion }}</td>
                                <td>{{ $row->factura }}</td>
                                <td>₡{{ number_format($row->monto, 2) }}</td>
                                <td>{{ $row->is_inactive == 1 ? "Vendido" : "No Vendido"  }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No hay datos disponibles.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray font-17 text-danger footer-total">
                            <td>Total de gastos</td>
                            <td></td>
                            <td></td>
                            <td></td>
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

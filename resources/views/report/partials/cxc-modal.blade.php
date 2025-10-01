{{-- resources/views/report/partials/cxc-modal.blade.php --}}
@php
    function mesBonito($ym) {
        // $ym = "YYYY-MM"
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $ym . '-01'); // <-- FIX
        return \Illuminate\Support\Str::ucfirst($date->translatedFormat('F Y'));
    }
@endphp
<div class="modal-dialog modal-lg" role="document" id="cxcModalReport">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <div class="row">
                <div class="col-sm-3 text-left invoice-col">
                    <img src="/images/logo_ag_cor.png" alt="Logo de la Empresa">
                </div>
            </div>
            <div class="row">
                <center>
                    <h4 class="modal-title">Reporte General de Cuentas por Cobrar</h4>
                    <p>{{ $rango }}</p>
                </center>
            </div>
        </div>

        <div class="modal-body">
            @forelse($grouped as $ym => $data)
                <div class="table-responsive">
                    <h4 class="bg-ag" style="padding:8px;border-radius:4px;">{{ mesBonito($ym) }}</h4>

                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-ag">
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Referencia</th>
                                <th>Valor inicial</th>
                                <th>Total a pagar</th>
                                <th>Vehículo</th>
                                <th>Modelo</th>
                                <th>Estado</th>
                                <th>Sucursal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->detalle as $row)
                                <tr>
                                    <td>{{ $row->fecha }}</td>
                                    <td>{{ $row->cliente }}</td>
                                    <td>{{ $row->referencia }}</td>
                                    <td>₡{{ number_format($row->valor_inicial, 2) }}</td>
                                    <td>
                                        <div><strong>Total:</strong> ₡{{ number_format($row->total_pagar, 2) }}</div>
                                        <div><small>Pagado:</small> ₡{{ number_format($row->pagado, 2) }}</div>
                                        <div><small>Pendiente:</small> ₡{{ number_format($row->pendiente, 2) }}</div>
                                    </td>
                                    <td>{{ $row->vehiculo }}</td>
                                    <td>{{ $row->modelo }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>{{ $row->sucursal }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray font-17 footer-total">
                                <td colspan="3"><strong>Subtotal del mes</strong></td>
                                <td></td>
                                <td>
                                    <div><strong>Total:</strong> ₡{{ number_format($data->subtotal_total, 2) }}</div>
                                    <div><small>Pagado:</small> ₡{{ number_format($data->subtotal_pagado, 2) }}</div>
                                    <div><small>Pendiente:</small> ₡{{ number_format($data->subtotal_pendiente, 2) }}
                                    </div>
                                </td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <hr>
            @empty
                <div class="alert alert-info">No hay datos disponibles en el rango seleccionado.</div>
            @endforelse
        </div>

        <div class="modal-footer">
            <div class="pull-left text-left" style="margin-right:auto">
                <h4 style="margin:0 0 6px 0;"><strong>Total general:</strong>
                    ₡{{ number_format($total_general_total, 2) }}</h4>
                <div><small><strong>Pagado:</strong> ₡{{ number_format($total_general_pagado, 2) }}</small></div>
                <div><small><strong>Pendiente:</strong> ₡{{ number_format($total_general_pendiente, 2) }}</small></div>
            </div>
            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThisCxc();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

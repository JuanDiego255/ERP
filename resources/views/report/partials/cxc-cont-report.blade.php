<div class="modal-dialog modal-lg" role="document" id="cxcModalReportCont">
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
                    <h4 class="modal-title">
                        @if ((int) $status === 0)
                            Reporte de clientes que compraron a CRÉDITO
                        @elseif ((int) $status === 1)
                            Reporte de clientes que compraron de CONTADO
                        @else
                            Reporte de clientes (todas las formas de pago)
                        @endif
                    </h4>
                    <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </center>
            </div>
        </div>

        <div class="modal-body">
            @if ($clientes->isEmpty())
                <p class="text-muted">No se encontraron clientes con los filtros seleccionados.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Vehículo</th>
                                <th>Modelo</th>
                                <th>Monto pagado</th>
                                <th>Fecha compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $cli)
                                <tr>
                                    <td>
                                        <strong>{{ $cli->cliente }}</strong><br>
                                        <small>Ref: {{ $cli->referencia }}</small>
                                    </td>
                                    <td>{{ $cli->telefono ?: '—' }}</td>
                                    <td>{{ $cli->vehiculo ?: '—' }}</td>
                                    <td>{{ $cli->modelo ?: '—' }}</td>
                                    <td class="text-right">
                                        ₡{{ number_format($cli->monto_pagado ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($cli->fecha_compra)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThisCxc();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

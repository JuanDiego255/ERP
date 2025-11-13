{{-- resources/views/report/partials/cxp-monthly-balance.blade.php --}}
@php
    function mesBonito($ym)
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $ym . '-01'); // día fijo para evitar saltos de mes
        return \Illuminate\Support\Str::ucfirst($date->translatedFormat('F Y'));
    }
@endphp

<div class="modal-dialog modal-lg" role="document" id="cxpModalReport">
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
                    <h4 class="modal-title">Saldos mensuales por proveedor (Cuentas por Pagar)</h4>
                    <p>{{ $rango }}</p>
                </center>
            </div>
        </div>

        <div class="modal-body">
            @php
                $montoTotalGeneral = 0;
                $montoTotalSaldo = 0;
            @endphp

            @forelse($months as $ym)
                <div class="table-responsive">

                    <h4 class="bg-ag"
                        style="padding:8px;border-radius:4px;display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                        <span>{{ mesBonito($ym) }}</span>
                    </h4>

                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-ag">
                                <th>Proveedor</th>
                                <th class="text-right">Total facturas (al mes)</th>
                                <th class="text-right">Pagado del mes</th>
                                <th class="text-right">Saldo a fin del mes</th>
                                <th class="text-right">Cargos del mes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $mes_total = 0;
                                $mes_pagado = 0;
                                $mes_saldo = 0;
                                $mes_cargos = 0;
                            @endphp

                            @foreach ($byProveedor as $proveedor_id => $info)
                                @php
                                    $row = $info['rows'][$ym] ?? [
                                        'total' => 0,
                                        'pagado_mes' => 0,
                                        'saldo' => 0,
                                        'cargos_mes' => 0,
                                    ];

                                    $mes_total += $row['total'];
                                    $mes_pagado += $row['pagado_mes'];
                                    $mes_saldo += $row['saldo'];
                                    $mes_cargos += $row['cargos_mes'];

                                    $montoTotalGeneral += $row['total'];
                                    $montoTotalSaldo += $row['saldo'];
                                @endphp

                                <tr>
                                    <td>{{ $info['proveedor'] }}</td>
                                    <td class="text-right">₡{{ number_format($row['total'], 2) }}</td>
                                    <td class="text-right">₡{{ number_format($row['pagado_mes'], 2) }}</td>
                                    <td class="text-right text-dark font-weight-bold">
                                        <strong>₡{{ number_format($row['saldo'], 2) }}</strong>
                                    </td>
                                    <td class="text-right">₡{{ number_format($row['cargos_mes'], 2) }}</td>
                                </tr>
                            @endforeach

                            {{-- Subtotales del mes --}}
                            <tr class="bg-gray font-16 footer-total">
                                <td class="text-right"><strong>Subtotal del mes</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_total, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_pagado, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_saldo, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_cargos, 2) }}</strong></td>
                            </tr>

                            {{-- Fila de encabezado de significado (similar a CXC) --}}
                            <tr class="bg-gray font-16 footer-total">
                                <td class="text-right"><strong></strong></td>
                                <td class="text-right"><strong>Total Inicial</strong></td>
                                <td class="text-right"><strong>Pagos</strong></td>
                                <td class="text-right"><strong>Saldo Pendiente</strong></td>
                                <td class="text-right"><strong>Cargos del Mes</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
            @empty
                <div class="alert alert-info">No hay datos disponibles en el rango seleccionado.</div>
            @endforelse
        </div>

        <div class="modal-footer">
            {{-- Si quisieras mostrar el estado global al final del rango, puedes descomentar y adaptar esto: --}}
            {{--
            <div class="pull-left text-left" style="margin-right:auto">
                <h4 style="margin:0 0 6px 0;"><strong>Estado al final del rango:</strong></h4>
                <div><strong>Total facturas:</strong> ₡{{ number_format($montoTotalGeneral, 2) }}</div>
                <div><strong>Pagado:</strong>
                    ₡{{ number_format($montoTotalGeneral - $montoTotalSaldo, 2) }}</div>
                <div class="text-danger"><strong>Pendiente:</strong> ₡{{ number_format($montoTotalSaldo, 2) }}</div>
            </div>
            --}}

            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThisCxp();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

{{-- resources/views/report/partials/cxc-monthly-balance.blade.php --}}
@php
    function mesBonito($ym)
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m-d', $ym . '-01'); // día fijo para evitar saltos de mes
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
                    <h4 class="modal-title">Saldos mensuales por cliente (Cuentas por Cobrar)</h4>
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
                    <h4 class="bg-ag" style="padding:8px;border-radius:4px;">{{ mesBonito($ym) }}</h4>

                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-ag">
                                <th>Cliente</th>
                                <th class="text-right">Total cuentas (al mes)</th>
                                <th class="text-right">Paga del mes</th>
                                <th class="text-right">Amortiza del mes</th>
                                <th class="text-right">Saldo a fin del mes</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $mes_total = 0;
                                $mes_paga = 0;
                                $mes_amort = 0;
                                $mes_saldo = 0;
                            @endphp

                            @foreach ($byClient as $cliente_id => $info)
                                @php
                                    $row = $info['rows'][$ym] ?? [
                                        'total' => 0,
                                        'paga_mes' => 0,
                                        'amortiza_mes' => 0,
                                        'saldo' => 0,
                                    ];
                                    $mes_total += $row['total'];
                                    $mes_paga += $row['paga_mes'];
                                    $mes_amort += $row['amortiza_mes'];
                                    $mes_saldo += $row['saldo'];
                                    $montoTotalGeneral += $row['total'];
                                    $montoTotalSaldo += $row['saldo'];
                                @endphp
                                <tr>
                                    <td>{{ $info['cliente'] }}</td>
                                    <td class="text-right">₡{{ number_format($row['total'], 2) }}</td>
                                    <td class="text-right">₡{{ number_format($row['paga_mes'], 2) }}</td>
                                    <td class="text-right">₡{{ number_format($row['amortiza_mes'], 2) }}</td>
                                    <td class="text-right text-danger">
                                        <strong>₡{{ number_format($row['saldo'], 2) }}</strong>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray font-16 footer-total">
                                <td class="text-right"><strong>Subtotal del mes</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_total, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_paga, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_amort, 2) }}</strong></td>
                                <td class="text-right"><strong>₡{{ number_format($mes_saldo, 2) }}</strong></td>
                            </tr>
                            <tr class="bg-gray font-16 footer-total">
                                <td class="text-right"><strong></strong></td>
                                <td class="text-right"><strong>Total Inicial</strong></td>
                                <td class="text-right"><strong>Cuotas</strong></td>
                                <td class="text-right"><strong>Amortización</strong></td>
                                <td class="text-right"><strong>Saldo Pendiente</strong></td>
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
            {{-- <div class="pull-left text-left" style="margin-right:auto">
                <h4 style="margin:0 0 6px 0;"><strong>Estado al final del rango:</strong></h4>
                <div><strong>Total:</strong> ₡{{ number_format($montoTotalGeneral, 2) }}</div>
                <div><strong>Pagado (acum. amortiza):</strong>
                    ₡{{ number_format($montoTotalGeneral - $montoTotalSaldo, 2) }}</div>
                <div class="text-danger"><strong>Pendiente:</strong> ₡{{ number_format($montoTotalSaldo, 2) }}</div>
            </div> --}}

            <button type="button" class="btn btn-primary no-print" aria-label="Print" onclick="printThisCxc();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

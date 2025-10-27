<div class="modal-dialog modal-lg" role="document" id="cxcModalReportVenc">
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
                    <h4 class="modal-title">Reporte por vencimiento de CXC</h4>
                    <p>Generado el {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                </center>
            </div>
        </div>

        <div class="modal-body">

            {{-- BLOQUE 30 DÍAS --}}
            @if ($plazo >= 30)
                <h4><strong>Clientes con 30 días de atraso</strong></h4>

                @if ($bucket30->isEmpty())
                    <p class="text-muted">No hay clientes con exactamente 30 días de atraso.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha del pago</th>
                                    <th>Últ. Pago Interés</th>
                                    <th>Saldo pendiente</th>
                                    <th>Días de atraso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bucket30 as $rev)
                                    <tr>
                                        <td>
                                            <strong>{{ $rev->cliente }}</strong><br>
                                            <small>Ref: {{ $rev->referencia }}</small>
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->ultimo_pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            ₡{{ number_format($rev->saldo_pendiente, 2) }}
                                        </td>

                                        <td class="text-center">
                                            {{ $rev->dias_atraso }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr>
            @endif


            {{-- BLOQUE 60 DÍAS --}}
            @if ($plazo >= 60)
                <h4><strong>Clientes con 60 días de atraso</strong></h4>

                @if ($bucket60->isEmpty())
                    <p class="text-muted">No hay clientes con exactamente 60 días de atraso.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha del pago</th>
                                    <th>Últ. Pago Interés</th>
                                    <th>Saldo pendiente</th>
                                    <th>Días de atraso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bucket60 as $rev)
                                    <tr>
                                        <td>
                                            <strong>{{ $rev->cliente }}</strong><br>
                                            <small>Ref: {{ $rev->referencia }}</small>
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->ultimo_pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>

                                        <td class="text-right">
                                            ₡{{ number_format($rev->saldo_pendiente, 2) }}
                                        </td>

                                        <td class="text-center">
                                            {{ $rev->dias_atraso }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <hr>
            @endif


            {{-- BLOQUE 90+ DÍAS --}}
            @if ($plazo >= 90)
                <h4><strong>Clientes con 90+ días de atraso</strong></h4>

                @if ($bucket90plus->isEmpty())
                    <p class="text-muted">No hay clientes con 90 días o más de atraso.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Fecha del pago</th>
                                    <th>Últ. Pago Interés</th>
                                    <th>Saldo pendiente</th>
                                    <th>Días de atraso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bucket90plus as $rev)
                                    <tr>
                                        <td>
                                            <strong>{{ $rev->cliente }}</strong><br>
                                            <small>Ref: {{ $rev->referencia }}</small>
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>
                                        <td>
                                            @if ($rev->ultimo_pago_fecha)
                                                {{ \Carbon\Carbon::parse($rev->ultimo_pago_fecha)->format('d/m/Y') }}
                                            @else
                                                Sin pagos
                                            @endif
                                        </td>

                                        <td class="text-right text-danger">
                                            <strong>₡{{ number_format($rev->saldo_pendiente, 2) }}</strong>
                                        </td>

                                        <td class="text-center">
                                            {{ $rev->dias_atraso }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
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

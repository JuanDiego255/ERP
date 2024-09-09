<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">{{ $detalle->name }} - COMPROBANTE DE PAGO</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-4 invoice-col">
                    <h4 class="text-blue text-uppercase">Ingresos</h4>
                    @if ($detalle->salario_base > 0)
                        <b>@lang('Salario base'):</b>
                        ₡{{ number_format($detalle->salario_base) }}<br>
                    @endif
                    @if ($detalle->bonificacion > 0)
                        <b>@lang('Bonificación'):</b>
                        ₡{{ number_format($detalle->bonificacion) }}<br>
                    @endif
                    @if ($detalle->comisiones > 0)
                        <b>@lang('Comisiones'):</b>
                        ₡{{ number_format($detalle->comisiones) }}<br>
                    @endif
                    @if ($detalle->cant_hora_extra > 0)
                        <b>@lang('Cant. Horas'):</b>
                        {{ $detalle->cant_hora_extra }}<br>
                        <b>@lang('Hora Extra. Emp'):</b>
                        ₡{{ number_format($detalle->hora_extra) }}<br>
                        <b>@lang('Monto Hora Extra'):</b>
                        ₡{{ number_format($detalle->monto_hora_extra) }}<br>
                    @endif
                </div>
                <div class="col-sm-4 invoice-col">
                    <h4 class="text-blue text-uppercase">Deducciones</h4>
                    @if ($detalle->adelantos > 0)
                        <b>@lang('Adelantos'):</b>
                        ₡{{ number_format($detalle->adelantos) }}<br>
                    @endif
                    @if ($detalle->prestamos > 0)
                        <b>@lang('Prestamos'):</b>
                        ₡{{ number_format($detalle->prestamos) }}<br>
                    @endif
                    @if ($detalle->deudas > 0)
                        <b>@lang('Deudas'):</b>
                        ₡{{ number_format($detalle->deudas) }}<br>
                    @endif
                    @if ($detalle->rebajados > 0)
                        <b>@lang('Rebajos'):</b>
                        ₡{{ number_format($detalle->rebajados) }}<br>
                    @endif
                    @if ($detalle->total_ccss > 0)
                        <b>@lang('C.C.S.S'):</b>
                        ₡{{ number_format($detalle->total_ccss) }}<br>
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Total Ingresos:
                    </b>₡{{ number_format($detalle->salario_base + $detalle->bonificacion + $detalle->comisiones + $detalle->monto_hora_extra) }}
                </div>

                <div class="col-sm-6 invoice-col">
                    <b>Total Deducciones:
                    </b>₡{{ number_format($detalle->adelantos + $detalle->prestamos + $detalle->deudas + $detalle->rebajados + $detalle->total_ccss) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                    <b class=" text-green">Total a pagar:</b> ₡{{ number_format($detalle->total) }}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" aria-label="Print"
                onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

<input type="hidden" id="payment_id" value="{{ $id }}">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <div class="row">
                <div class="col-sm-12 text-center">
                    <img src="/images/logo_ag_cor.png" alt="Logo de la Empresa" >
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <h5 class="modal-title" id="modalTitle">Autos Grecia A Su Servicio, Grecia, Costa Rica</h5>
                    <h5>Tel: 2494-7694</h5>
                    <h5>{{ @format_datetime('now') }}</h5>
                    <h5 class="text-info text-center">RECIBO DE DINERO</h5>
                </div>               
            </div>
          
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-4 invoice-col">
                    <b>Recibí de:</b> {{ $item->name }}<br>
                    <b>Interés pagado al:</b> {{ $item->created_at }}<br>
                    <b>Fecha de pago:</b> {{ $item->fecha_pago }}
                    <b>Fecha de interés:</b> {{ $item->fecha_interes }}
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Cantidad recibida:</b> ₡{{ number_format($item->paga) }}<br>
                    <b>Recibo:</b> {{ $item->referencia }}
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Vehículo comprado:</b>{{ $item->veh_venta }}<br>
                    <b>Modelo:</b> {{ $item->modelo }}<br>
                    <b>Color:</b> {{ $item->color }}<br>
                    <b>Gasolina:</b> {{ $item->combustible }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
                <div class="col-sm-6">
                    <b class="mb-2">Saldo anterior:</b> ₡{{ number_format($item->monto_general + $item->amortiza) }}
                    <br>
                    <b class="mb-2">Intereses pagados:</b> ₡{{ number_format($item->interes_c) }}<br>
                    <b class="mb-2">Monto que amortiza:</b> ₡{{ number_format($item->amortiza) }}<br>
                    <b class="mb-2">Saldo actual:</b> ₡{{ number_format($item->monto_general) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
                <div class="col-sm-6 invoice-col">
                    <br>
                    <b class=" text-danger">Firma:</b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 invoice-col">
                    <br>
                    <span style="border-bottom: 2px solid #000; display: inline-block; width: 100%;">&nbsp;</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary no-print" aria-label="Print"
                onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button>
            <button type="button" class="btn btn-info sendPaymentWhats no-print" aria-label="Print"
                id="sendPaymentWhats">
                <i class="fa fa-share"></i> @lang('Enviar por WhatsApp')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

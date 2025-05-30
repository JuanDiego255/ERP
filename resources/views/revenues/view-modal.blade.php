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
                    <h3 class="modal-title" id="modalTitle">Autos Grecia A Su Servicio, Grecia, Costa Rica</h3>
                    <h4>Tel: 2494-7694</h4>
                    <h4>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</h4>
                    <h4 class="text-info text-center">RECIBO DE DINERO</h4>
                </div>               
            </div>
          
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-4 invoice-col text-receive">
                    <b>Recibí de:</b> {{ $item->name }}<br>                    
                    <b>Fecha de pago:</b> {{ $item->fecha_pago }}<br>
                    <b>Interés pagado al:</b> {{ $item->fecha_interes }}
                </div>
                <div class="col-sm-4 invoice-col text-receive">
                    <b>Cantidad recibida:</b> ₡{{ number_format($item->paga) }}<br>
                    <b>Recibo:</b> {{ $item->referencia }}
                </div>
                <div class="col-sm-4 invoice-col text-receive">
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
                <div class="col-sm-6 text-receive">
                    <b class="mb-2">Saldo anterior:</b> ₡{{ number_format($item->monto_general + $item->amortiza) }}
                    <br>
                    <b class="mb-2">Intereses pagados:</b> ₡{{ number_format($item->interes_c) }}<br>
                    <b class="mb-2">Monto que amortiza:</b> ₡{{ number_format($item->amortiza) }}<br>
                    <b class="mb-2">Saldo a la fecha de interés:</b> ₡{{ number_format($item->monto_general) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <hr>
                </div>
                <div class="col-sm-6 invoice-col text-receive">
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
                id="whats">
                <i class="fa fa-share"></i> @lang('Enviar por WhatsApp')
            </button>
            <button type="button" class="btn btn-info sendPaymentDetail no-print" aria-label="Print" id="email">
                <i class="fa fa-envelope"></i> @lang('Enviar comprobante')
            </button>
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>

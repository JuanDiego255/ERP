<style>
    .no-margin {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <div class="row">
                <div class="col-sm-3 text-left invoice-col">
                    <img src="/images/logo_ag_cor.png" alt="Logo de la Empresa">
                </div>
                <div class="col-sm-9 text-left invoice-col">
                    <h5 class="modal-title" id="modalTitle">AUTOS GRECIA LTDA - PLAN DE VENTAS</h5>
                    <p>Tel: 2494-7694 / 2494-4155 <br> 550 MTS Oeste Sucursal C.C.S.S</p>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <b>No. Plan: </b>{{ $plan->numero }}<br>
                    <b>Vendedor: </b>{{ $plan->vendedor_name }}<br>
                    <b>Tipo Plan: </b>{{ $plan->tipo_plan == 1 ? 'Contado' : 'Crédito' }}<br>
                    <b>Fecha: </b>{{ $plan->fecha_plan }}<br>
                </div>
            </div>
            <hr class="no-margin">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success">Información del cliente</h4>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Nombre: </b>{{ $plan->cliente_name }}<br>
                    <b>Cédula: </b>{{ $plan->cliente_ident }}<br>
                    <b>Teléfono: </b>{{ $plan->cliente_tel }}<br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Estado: </b>{{ $plan->cliente_state }}<br>
                    <b>Puesto: </b>{{ $plan->cliente_puesto }}<br>
                    <b>E-mail: </b>{{ $plan->cliente_email }}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <b>Dirección: </b>{{ $plan->cliente_direccion }}<br>
                </div>
            </div>
            <hr class="no-margin">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success">Información del fiador</h4>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Nombre: </b>{{ $plan->fiador_name }}<br>
                    <b>Cédula: </b>{{ $plan->fiador_ident }}<br>
                    <b>Teléfono: </b>{{ $plan->fiador_tel }}<br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Estado: </b>{{ $plan->fiador_state }}<br>
                    <b>Puesto: </b>{{ $plan->fiador_puesto }}<br>
                    <b>E-mail: </b>{{ $plan->fiador_email }}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <b>Dirección: </b>{{ $plan->fiador_direccion }}<br>
                </div>
            </div>
            <hr class="no-margin">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success">Vehículo vendido</h4>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Marca: </b>{{ $plan->marca }}<br>
                    <b>No. Motor: </b>{{ $plan->motor }}<br>
                    <b>Año: </b>{{ $plan->model }}<br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Estilo: </b>{{ $plan->veh_venta }}<br>
                    <b>No. Chasis: </b>{{ $plan->bin }}<br>
                    <b>Color: </b>{{ $plan->color }}<br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Placa: </b>{{ $plan->placa }}<br>
                    <b>Valor: </b>₡{{ number_format($plan->monto_venta) }}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <b>Observaciones: </b>{{ $plan->observacion }}<br>
                </div>
            </div>
            <hr class="no-margin">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success">Forma de pago</h4>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Recibido (Vehículo): </b>₡{{ number_format($plan->monto_recibo) }}<br>
                    <b>Total recibido: </b>₡{{ number_format($plan->total_recibido) }}<br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Monto Efectivo: </b>₡{{ number_format($plan->monto_efectivo) }}<br>
                    <b>Total financiado: </b>₡{{ number_format($plan->total_financiado) }}<br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Gastos traspaso: </b>{{ $plan->gastos_plan }}<br>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <br>
                    <b>Forma de pago: </b>{{ $plan->desc_forma_pago }}<br>
                </div>
            </div>
            <hr class="no-margin">
            <div class="row">
                <div class="col-sm-6 invoice-col">
                    <br>
                    <b class=" text-danger">Firma Ejecutivo:</b>
                </div>
                <div class="col-sm-6 invoice-col">
                    <br>
                    <b class=" text-danger">Firma Cliente:</b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 invoice-col">
                    <br>
                    <span style="border-bottom: 2px solid #000; display: inline-block; width: 100%;">&nbsp;</span>
                </div>
                <div class="col-sm-6 invoice-col">
                    <br>
                    <span style="border-bottom: 2px solid #000; display: inline-block; width: 100%;">&nbsp;</span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <span class="text-muted text-center">*Este documento es para efectos de proforma, sin valor comercial*</span>
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
</div>

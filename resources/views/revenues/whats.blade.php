<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<style>
    .text-blue {
        color: #0073b7 !important
    }

    .text-center {
        text-align: center
    }

    .text-info {
        color: #2ebfd5 !important
    }

    .row {
        margin-right: -15px;
        margin-left: -15px
    }

    .col-lg-1,
    .col-lg-10,
    .col-lg-11,
    .col-lg-12,
    .col-lg-2,
    .col-lg-3,
    .col-lg-4,
    .col-lg-5,
    .col-lg-6,
    .col-lg-7,
    .col-lg-8,
    .col-lg-9,
    .col-md-1,
    .col-md-10,
    .col-md-11,
    .col-md-12,
    .col-md-2,
    .col-md-3,
    .col-md-4,
    .col-md-5,
    .col-md-6,
    .col-md-7,
    .col-md-8,
    .col-md-9,
    .col-sm-1,
    .col-sm-10,
    .col-sm-11,
    .col-sm-12,
    .col-sm-2,
    .col-sm-3,
    .col-sm-4,
    .col-sm-5,
    .col-sm-6,
    .col-sm-7,
    .col-sm-8,
    .col-sm-9,
    .col-xs-1,
    .col-xs-10,
    .col-xs-11,
    .col-xs-12,
    .col-xs-2,
    .col-xs-3,
    .col-xs-4,
    .col-xs-5,
    .col-xs-6,
    .col-xs-7,
    .col-xs-8,
    .col-xs-9 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px
    }

    .invoice-col {
        float: left;
        width: 33.3333333%
    }

    .text-uppercase {
        text-transform: uppercase
    }

    .text-green {
        color: #00a65a !important
    }

    .text-danger {
        color: #a94442
    }

    .linea {
        border-bottom: 2px solid #000;
        display: inline-block;
        width: 100%;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-sm-12 text-center">
            <img src="{{ $logo_url }}" alt="Logo de la Empresa">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <h5 id="modalTitle">Autos Grecia A Su Servicio, Grecia, Costa Rica</h5>
            <h5>Tel: 2494-7694</h5>
            <h5></h5>
            <h5 class="text-info text-center">RECIBO DE DINERO</h5>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-4 invoice-col">
        <b>Recibí de:</b> {{ $item->name }}<br>
        <b>Interés pagado al:</b> {{ $item->created_at }}<br>
        <b>Fecha de pago:</b> {{ $item->fecha_pago }}
        <b>Fecha de interés:</b> {{ $item->fecha_interes }}
    </div>
    <div class="col-sm-4 invoice-col">
        <b>Cantidad recibida:</b> {{ number_format($item->paga) }}<br>
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
        <b>Saldo anterior: </b> {{ number_format($item->monto_general + $item->amortiza) }}
        <br>
        <b>Intereses pagados: </b> {{ number_format($item->interes_c) }}<br>
        <b>Monto que amortiza: </b> {{ number_format($item->amortiza) }}<br>
        <b>Saldo actual: </b> {{ number_format($item->monto_general) }}
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
    <div class="col-sm-6 invoice-col">
        <br>
        <b class="text-danger">Firma:</b>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 invoice-col">
        <br>
        <span class="linea">&nbsp;</span>
    </div>
</div>

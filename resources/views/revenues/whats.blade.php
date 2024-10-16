<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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
        color: #a94442;
        font-weight: bold;
    }
    .text-black {
        color: #000;
        font-weight: bold;
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
            <img src="{{ public_path() . '/images/logo_ag_cor.png' }}" alt="Logo de la Empresa" style="max-width: 100%; height: auto;">
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
        <b class="text-black">Recibí de:</b> {{ $item->name }}<br>
        <b class="text-black">Interés pagado al:</b> {{ $item->created_at }}<br>
        <b class="text-black">Fecha de pago:</b> {{ $item->fecha_pago }}<br>
        <b class="text-black">Fecha de interés:</b> {{ $item->fecha_interes }}
    </div>
    <div class="col-sm-4 invoice-col">
        <b class="text-black">Cantidad recibida:</b> ₡{{ number_format($item->paga) }}<br>
        <b class="text-black">Recibo:</b> {{ $item->referencia }}
    </div>
    <div class="col-sm-3">
        <b class="text-black">Vehículo comprado:</b>{{ $item->veh_venta }}<br>
        <b class="text-black">Modelo:</b> {{ $item->modelo }}<br>
        <b class="text-black">Color:</b> {{ $item->color }}<br>
        <b class="text-black">Gasolina:</b> {{ $item->combustible }}
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
    <div class="col-sm-6">
        <b class="text-black">Saldo anterior: </b> ₡{{ number_format($item->monto_general + $item->amortiza) }}
        <br>
        <b class="text-black">Intereses pagados: </b> ₡{{ number_format($item->interes_c) }}<br>
        <b class="text-black">Monto que amortiza: </b> ₡{{ number_format($item->amortiza) }}<br>
        <b class="text-black">Saldo actual: </b> ₡{{ number_format($item->monto_general) }}
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
    <div class="col-sm-12">
        <br>
        <span class="linea">&nbsp;</span>
    </div>
</div>

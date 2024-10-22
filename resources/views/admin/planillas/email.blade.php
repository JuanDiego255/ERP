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
</style>
<div class="row">
    <h4 class="text-blue">{{ $detalle->name }} - COMPROBANTE DE PAGO DEL {{ $detalle->fecha_desde }} AL
        {{ $detalle->fecha_hasta }}</h4>
</div>
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
    @if ($detalle->aguinaldo > 0)
        <div class="col-sm-4 invoice-col">
            <h4 class="text-blue text-uppercase">Aguinaldo</h4>
            <b>@lang('Aguinaldo'):</b>
            ₡{{ number_format($detalle->aguinaldo) }}<br>
        </div>
    @endif
</div>
<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
    <div class="col-sm-4 invoice-col">
        <b>Total Ingresos:
        </b>₡{{ number_format($detalle->salario_base + $detalle->bonificacion + $detalle->comisiones + $detalle->monto_hora_extra) }}
    </div>

    <div class="col-sm-4 invoice-col">
        <b>Total Deducciones:
        </b>₡{{ number_format($detalle->adelantos + $detalle->prestamos + $detalle->deudas + $detalle->rebajados + $detalle->total_ccss) }}
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
    <div class="col-sm-4 invoice-col">

        <b class=" text-green">Total a pagar:</b>
        ₡{{ number_format($detalle->total - $detalle->aguinaldo) }}
    </div>
    <div class="col-sm-4 invoice-col">

    </div>
    @if ($detalle->aguinaldo > 0)
        <div class="col-sm-4 invoice-col">
            <b class=" text-green">Aguinaldo:</b> ₡{{ number_format($detalle->aguinaldo) }}
        </div>
    @endif
</div>

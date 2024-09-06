@extends('layouts.app')

@section('title', __('Agregar gasto'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Agregar gasto')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('Admin\BillVehicleController@store'),
            'method' => 'post',
            'id' => 'user_add_form',
        ]) !!}
        <div class="row">
            {!! Form::hidden('product_id', $id, ['id' => 'product_id']) !!}
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('fecha_compra', __('Fecha Compra') . ':*') !!}
                            {!! Form::date('fecha_compra', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', __('Monto') . ':*') !!}
                            {!! Form::text('monto', null, ['class' => 'form-control', 'required', 'placeholder' => __('Monto')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('factura', __('Factura') . ':*') !!}
                            {!! Form::text('factura', null, ['class' => 'form-control','id' => 'factura', 'required', 'placeholder' => __('# Factura')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('proveedor_id', __('purchase.supplier') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select('proveedor_id', [], null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('messages.please_select'),
                                    'required',
                                    'id' => 'supplier_id',
                                ]) !!}
                                {{-- <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1" id="plazo_container">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Días plazo') . ':*') !!}
                            {!! Form::text('plazo', null, ['class' => 'form-control', 'required', 'id' => 'plazo']) !!}
                        </div>
                    </div>
                    <div class="col-sm-3 d-none" id="fecha_vence_container">
                        <div class="form-group">
                            {!! Form::label('fecha_vence', __('Fecha Vence') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('fecha_vence', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'fecha_vence',
                                ]) !!}
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <div class="form-group">
                            {!! Form::label('descripcion', __('Descripcion') . ':*') !!}
                            {!! Form::textarea('descripcion', null, [
                                'class' => 'form-control',
                                'placeholder' => __('Descripción del gasto'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('is_cxp', 1, false, ['class' => '', 'id' => 'is_cxp']) !!}
                                {{ __('Generar cuenta por pagar') }}
                            </label>
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang('messages.save')</button>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            @include('contact.create', ['quick_add' => true])
        </div>
    @stop
    @section('javascript')
        <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#fecha_vence_container, #plazo_container').hide();
                $('#plazo').on('input', function() {
                    // Obtiene el valor del plazo
                    var plazo = parseInt($(this).val());

                    // Verifica si es un número válido
                    if (!isNaN(plazo) && plazo > 0) {
                        // Calcula la fecha de vencimiento
                        var fechaVence = new Date();
                        fechaVence.setDate(fechaVence.getDate() + plazo);

                        // Formatea la fecha en el formato deseado (YYYY-MM-DD)
                        var dia = ("0" + fechaVence.getDate()).slice(-2);
                        var mes = ("0" + (fechaVence.getMonth() + 1)).slice(-2);
                        var anio = fechaVence.getFullYear();

                        // Asigna la fecha formateada al campo de fecha_vence
                        $('#fecha_vence').val(anio + '-' + mes + '-' + dia);
                    } else {
                        // Si el plazo no es válido, limpia el campo de fecha_vence
                        $('#fecha_vence').val('');
                    }
                });
                $('#factura').on('blur', function() {
                    var factura = $(this).val();
                    var is_cxp = $('#is_cxp').is(':checked');
                    if (factura != "" && is_cxp) {
                        $.ajax({
                            url: '/expense/check-ref_no',
                            type: 'POST',
                            data: {
                                ref_no: factura
                            },
                            success: function(response) {
                                if (response.valid) {
                                    swal({
                                        title: "La factura digitada ya existe",
                                        icon: 'warning',
                                        buttons: {
                                            confirm: {
                                                text: "OK",
                                                value: true,
                                                visible: true,
                                                className: "",
                                                closeModal: true
                                            }
                                        },
                                        dangerMode: true,
                                    }).then(willDelete => {
                                        if (willDelete) {
                                            $('#factura').val('').focus();
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Manejo de errores
                                console.error("Error in validation request:", error);
                            }
                        });
                    }
                });
            });
        </script>
    @endsection

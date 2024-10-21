@extends('layouts.app')

@section('title', __('Editar gasto'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Editar gasto')<p>Si la factura esta ligada a Cuentas Por Pagar, no puedes modificar la fecha de vencimiento
            </p>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('Admin\BillVehicleController@update', [$bill->id]),
            'method' => 'PUT',
            'id' => 'bill_edit_form',
        ]) !!}
        {!! Form::hidden('product_id', $bill->product_id, ['id' => 'product_id']) !!}
        {!! Form::hidden('is_cxp', $bill->is_cxp, ['id' => 'is_cxp']) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('fecha_compra', __('Fecha Compra') . ':*') !!}
                            {!! Form::text('fecha_compra', \Carbon\Carbon::parse($bill->fecha_compra)->format('d/m/y'), ['class' => 'form-control fecha', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', __('Monto') . ':*') !!}
                            {!! Form::text('monto', number_format($bill->monto, 2, '.', ','), [
                                'class' => 'form-control number',
                                'required',
                                'placeholder' => __('Monto'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('factura', __('Factura') . ':*') !!}
                            {!! Form::text('factura', $bill->factura, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('# Factura'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('proveedor_id', __('purchase.supplier') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select(
                                    'proveedor_id',
                                    [$bill->proveedor_id => $bill->proveedor_id ? $prov_name : ''],
                                    $bill->proveedor_id,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => __('messages.please_select'),
                                        'required',
                                        'id' => 'supplier_id',
                                    ],
                                ) !!}
                                {{-- <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            {!! Form::label('descripcion', __('Descripcion') . ':*') !!}
                            {!! Form::textarea('descripcion', $bill->descripcion, [
                                'class' => 'form-control',
                                'placeholder' => __('Descripción del gasto'),
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang('messages.update')</button>
            </div>
        </div>
        {!! Form::close() !!}
    @stop
    @section('javascript')
        <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
        <script>
            $(document).ready(function() {
                $('#factura').on('blur', function() {
                    var factura = $(this).val();
                    var is_cxp = $('#is_cxp').val();
                    if (factura != "" && is_cxp == 1) {
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
                                            $('#factura').focus();
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
                $('.number').on('input', function() {
                    let input = $(this).val().replace(/[^0-9.]/g, ''); // Permite números y un punto decimal

                    // Asegúrate de que sólo haya un punto decimal
                    if ((input.match(/\./g) || []).length > 1) {
                        input = input.replace(/\.+$/, ""); // Remueve puntos adicionales
                    }

                    if (input) {
                        // Si el número tiene parte decimal, no lo formateamos con comas aún
                        let parts = input.split('.');
                        let formatted = new Intl.NumberFormat('en-US').format(parts[0]); // Formatea los miles
                        if (parts[1] !== undefined) {
                            input = formatted + '.' + parts[1]; // Recompone el número con la parte decimal
                        } else {
                            input = formatted;
                        }
                        $(this).val(input);
                    }
                });
                $('.fecha').on('input', function() {
                    let input = $(this).val().replace(/\D/g, ''); // Elimina todo lo que no sea un número
                    if (input.length <= 2) {
                        $(this).val(input);
                    } else if (input.length <= 4) {
                        $(this).val(`${input.slice(0, 2)}/${input.slice(2)}`);
                    } else {
                        $(this).val(`${input.slice(0, 2)}/${input.slice(2, 4)}/${input.slice(4, 8)}`);
                    }
                });
            });
        </script>
    @endsection

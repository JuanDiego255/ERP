@extends('layouts.app')

@section('title', __('Crear plan de venta'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Crear plan de venta')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('PlanVentaController@store'),
            'method' => 'post',
            'id' => 'plan_venta_add_form',
        ]) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['title' => __('Información del plan de ventas')])
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('fecha_plan', __('Fecha plan') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('fecha_plan', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'fecha_plan',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('numero', __('No. Plan Venta') . ':*') !!}
                            {!! Form::text('numero', null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Número de plan'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('tipo_plan', 'Tipo' . ':*') !!}
                        {!! Form::select(
                            'tipo_plan',
                            ['1' => 'Contado', '2' => 'Crédito'],
                            !empty($employee->tipo_plan) ? $employee->tipo_plan : null,
                            ['class' => 'form-control', 'id' => 'tipo_plan', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vendedor_id', __('Vendedor') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>

                                {!! Form::select('vendedor_id', [], null, [
                                    'class' => 'form-control mousetrap',
                                    'id' => 'vendedor_id',
                                    'placeholder' => 'Seleccione un vendedor',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endcomponent
                @component('components.widget', ['title' => __('Información involucrados')])
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('cliente_id', __('contact.customer') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>

                                {!! Form::select('cliente_id', [], null, [
                                    'class' => 'form-control mousetrap',
                                    'id' => 'customer_id',
                                    'placeholder' => 'Seleccione un cliente',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('fiador_id', __('Fiador') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>

                                {!! Form::select('fiador_id', [], null, [
                                    'class' => 'form-control mousetrap',
                                    'id' => 'fiador_id',
                                    'placeholder' => 'Seleccione un fiador',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endcomponent
                @component('components.widget', ['title' => __('Vehículos')])
                    {!! Form::hidden('vehiculo_recibido_id_hidden', null, ['id' => 'vehiculo_recibido_id_hidden']) !!}
                    {!! Form::hidden('vehiculo_venta_id_hidden', null, ['id' => 'vehiculo_venta_id_hidden']) !!}
                    {!! Form::hidden('venta_sin_rebajos', null, ['id' => 'venta_sin_rebajos']) !!}
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('vehiculo_venta_id', __('Vehículo venta') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::text('vehiculo_venta_id', null, [
                                    'class' => 'form-control vehiculo-input',
                                    'required',
                                    'id' => 'vehiculo_venta_id',
                                    'placeholder' => __('Seleccione un vehículo'),
                                    'data-target' => 'vehiculo_venta_id',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('vehiculo_venta_id', __('Vehículo recibido')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::text('vehiculo_recibido_id', null, [
                                    'class' => 'form-control vehiculo-input',
                                    'id' => 'vehiculo_recibido_id',
                                    'placeholder' => __('Seleccione un vehículo'),
                                    'data-target' => 'vehiculo_recibido_id',
                                ]) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_product"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-default bg-red btn-flat remove_cars" data-name="">Eliminar Vehículos</button>
                    </div>
                @endcomponent
                @component('components.widget', ['title' => __('Otros datos')])
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('monto_recibo', __('Vehículo recibido')) !!}
                            {!! Form::number('monto_recibo', 0, [
                                'class' => 'form-control',
                                'id' => 'monto_recibo',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('monto_efectivo', __('Total efectivo')) !!}
                            {!! Form::number('monto_efectivo', 0, [
                                'class' => 'form-control',
                                'id' => 'monto_efectivo',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('total_recibido', __('Total recibido')) !!}
                            {!! Form::number('total_recibido', 0, [
                                'class' => 'form-control',
                                'id' => 'total_recibido',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('total_financiado', __('Total financiado')) !!}
                            {!! Form::number('total_financiado', 0, [
                                'class' => 'form-control',
                                'id' => 'total_financiado',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('gastos_plan', __('Gasto plan') . ':*') !!}
                            {!! Form::text('gastos_plan', null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Gastos del plan'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('desc_forma_pago', __('Forma de pago') . ':*') !!}
                            {!! Form::text('desc_forma_pago', null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Descripción forma de pago'),
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
                @component('components.widget', ['title' => __('Información Cuentas por Cobrar')])
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Plazo') . ':*') !!}
                            {!! Form::number('plazo', null, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('tasa', __('Tasa') . ':*') !!}
                            {!! Form::number('tasa', null, [
                                'class' => 'form-control',
                                'required',
                                'step' => '0.01'
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('cuota', __('Cuota mensual') . ':*') !!}
                            {!! Form::number('cuota', null, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('tipo_prestamo', 'Tipo prestamo' . ':*') !!}
                        {!! Form::select(
                            'tipo_prestamo',
                            ['1' => 'Cuota Nivelada', '2' => 'Intereses'],
                            !empty($employee->tipo_prestamo) ? $employee->tipo_prestamo : null,
                            ['class' => 'form-control', 'id' => 'tipo_prestamo', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('moneda', 'Moneda' . ':*') !!}
                        {!! Form::select(
                            'moneda',
                            ['1' => 'Colones', '2' => 'Dolares'],
                            !empty($employee->moneda) ? $employee->tipo_prestamo : null,
                            ['class' => 'form-control', 'id' => 'moneda', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
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
        <div class="modal fade car_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            @include('admin.plan_ventas.modal-car')
        </div>
        <div class="modal fade car_new_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
            @include('admin.plan_ventas.modal-new-car')
        </div>
    </section>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#customer_id').select2({
                ajax: {
                    url: '/contacts/customers',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function(data) {

                    var template = data.text;
                    if (typeof(data.total_rp) != "undefined") {
                        var rp = data.total_rp ? data.total_rp : 0;
                        template += "<br><i class='fa fa-gift text-success'></i> " + rp;
                    }

                    return template;
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        var name = $('#customer_id')
                            .data('select2')
                            .dropdown.$search.val();
                        return (
                            '<button type="button" data-name="' +
                            name +
                            '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                            __translate('add_name_as_new_customer', {
                                name: name
                            }) +
                            '</button>'
                        );
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
            });
            $('#fiador_id').select2({
                ajax: {
                    url: '/contacts/guarantor',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function(data) {

                    var template = data.text;
                    if (typeof(data.total_rp) != "undefined") {
                        var rp = data.total_rp ? data.total_rp : 0;
                        template += "<br><i class='fa fa-gift text-success'></i> " + rp;
                    }

                    return template;
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        var name = $('#fiador_id')
                            .data('select2')
                            .dropdown.$search.val();
                        return (
                            '<button type="button" data-name="' +
                            name +
                            '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                            __translate('add_name_as_new_customer', {
                                name: name
                            }) +
                            '</button>'
                        );
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
            });
            $('#vendedor_id').select2({
                ajax: {
                    url: '/employees/vendedor',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function(data) {

                    var template = data.text;

                    return template;
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        var name = $('#vendedor_id')
                            .data('select2')
                            .dropdown.$search.val();
                        return (
                            '<button type="button" data-name="' +
                            name +
                            '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                            __translate('add_name_as_new_customer', {
                                name: name
                            }) +
                            '</button>'
                        );
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
            });
            $(document).on('click', '.add_new_car', function() {
                // $('#customer_id').select2('close');
                var name = $(this).data('name');
                $('.car_modal')
                    .find('input#name')
                    .val(name);
                $('.car_modal').modal('show');
            });
            $(document).on('click', '.add_new_product', function() {
                $('.car_new_modal').modal('show');
            });
            $('#vehiculo_id').select2({
                ajax: {
                    url: '/get/vehicles',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                templateResult: function(data) {
                    var template = data.text;
                    return template;
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        var name = $('#vehiculo_id')
                            .data('select2')
                            .dropdown.$search.val();
                        return (
                            '<button type="button" data-name="' +
                            name +
                            '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                            __translate('add_name_as_new_customer', {
                                name: name
                            }) +
                            '</button>'
                        );
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            });
            $('#vehiculo_id').on('select2:select', function(e) {
                var data = e.params.data; // Los datos del vehículo seleccionado

                // Completar los campos con la información del vehículo
                $('#marca').val(data.marca);
                $('#fecha_ingreso').val(data.fecha_ingreso);
                $('#vin').val(data.vin);
                $('#dua').val(data.dua);
                $('#placa').val(data.placa);
                $('#combustible').val(data.combustible);
                $('#model').val(data.model);
                $('#color').val(data.color);
                $('#gastos').val(__currency_trans_from_en(data.gastos, true, true));
            });
            function limpiarModal() {
                // Limpiar el campo de selección de vehículos
                $('#vehiculo_id').val(null).trigger('change'); // Limpiar select2
                // Limpiar los campos de texto asociados
                $('#descripcion').val('');
                $('#fecha_ingreso').val('');
                $('#vin').val('');
                $('#dua').val('');
                $('#placa').val('');
                $('#combustible').val('');
                $('#model').val('');
                $('#color').val('');
                $('#gastos').val('');
                $('#monto_venta').val(0);
                $('#efectivo').val(0);
                $('#monto_recibo_modal').val(0);
            }
            var vehiculosSeleccionados = {};
            var vehiculoInputs = ['vehiculo_venta_id', 'vehiculo_recibido_id'];

            // Manejar selección de vehículo
            $('.vehiculo-input').on('click', function() {
                currentInput = $(this).data('target');

                if (currentInput.includes('recibido')) {
                    // Si es recibido, mostrar el campo "monto_recibo" y ocultar "fecha_venta", "monto_venta", "efectivo"
                    $('#fecha_venta').closest('.form-group').hide();
                    $('#monto_venta').closest('.form-group').hide();
                    $('#efectivo').closest('.form-group').hide();
                    $('#monto_recibo_modal').closest('.form-group').show();
                } else {
                    // Si es venta, mostrar los campos "fecha_venta", "monto_venta", "efectivo" y ocultar "monto_recibo"
                    $('#fecha_venta').closest('.form-group').show();
                    $('#monto_venta').closest('.form-group').show();
                    $('#efectivo').closest('.form-group').show();
                    $('#monto_recibo_modal').closest('.form-group').hide();
                }
                limpiarModal();
                $('.car_modal').modal('show');
            });

            // Verificar si un vehículo ya está seleccionado
            function vehiculoSeleccionado(vehiculoId) {
                return Object.values(vehiculosSeleccionados).some(function(vehiculo) {
                    return vehiculo.id === vehiculoId;
                });
            }

            // Guardar el vehículo seleccionado
            $(document).on('click', '.save_vehicle', function() {
                var selectedVehicleId = $('#vehiculo_id').val();
                var monto = 0;

                // Determinar si es "recibido" o "venta" para obtener el monto correcto
                if (currentInput.includes('recibido')) {
                    monto = parseFloat($('#monto_recibo_modal').val()) || 0;
                } else {
                    monto = parseFloat($('#efectivo').val()) || 0;
                }

                if (selectedVehicleId !== "" && monto > 0) {
                    var selectedVehicleName = $('#vehiculo_id option:selected').text();

                    // Si ya había un vehículo en el input actual, restar su monto
                    if (vehiculosSeleccionados[currentInput]) {
                        var vehiculoAnteriorMonto = vehiculosSeleccionados[currentInput].monto;
                        sumarRestarMonto(vehiculosSeleccionados[currentInput].tipo, -vehiculoAnteriorMonto);
                    }

                    // Asignar el nuevo vehículo y su monto
                    $('#' + currentInput).val(selectedVehicleName);
                    $('#' + currentInput + '_hidden').val(selectedVehicleId);

                    vehiculosSeleccionados[currentInput] = {
                        id: selectedVehicleId,
                        monto: monto,
                        tipo: currentInput.includes('recibido') ? 'recibido' : 'venta'
                    };

                    sumarRestarMonto(vehiculosSeleccionados[currentInput].tipo, monto);

                    $('.car_modal').modal('hide');
                } else {
                    toastr.error("Debe seleccionar un vehículo y llenar los montos correspondientes.");
                }
            });
            $(document).on('click', '.remove_cars', function() {
                $('#total_financiado').val(0);
                $('#total_recibido').val(0);
                $('#monto_recibo').val(0);
                $('#monto_efectivo').val(0);
                $('#vehiculo_venta_id').val("");
                $('#vehiculo_recibido_id').val("");
            });

            // Función para sumar/restar montos de efectivo/recibo
            function sumarRestarMonto(tipo, monto) {
                var montoActual;
                if (tipo === 'venta') {
                    montoVenta = parseFloat($('#monto_venta').val()) || 0;                  
                    $('#monto_efectivo').val(monto);
                    if (montoVenta > monto) {
                        $('#venta_sin_rebajos').val(montoVenta);
                        $('#total_financiado').val(montoVenta - monto);
                    }
                } else {
                    $('#monto_recibo').val(monto);
                }
                // Actualizar el total recibido
                actualizarTotalRecibido(tipo);
            }
            // Actualizar el campo total_recibido
            function actualizarTotalRecibido(tipo) {
                var montoEfectivo = parseFloat($('#monto_efectivo').val()) || 0;
                var montoRecibo = parseFloat($('#monto_recibo').val()) || 0;
                $('#total_recibido').val(montoEfectivo + montoRecibo);
                totalRecibido = parseFloat($('#total_recibido').val()) || 0;
                ventaSinRebajos = parseFloat($('#venta_sin_rebajos').val()) || 0;
                if(ventaSinRebajos > totalRecibido){
                    $('#total_financiado').val(ventaSinRebajos - totalRecibido);
                }        
            }

            // Recalcular montos si se cambia el valor de efectivo o recibo manualmente
            $('#monto_efectivo, #monto_recibo').on('input', function() {
                actualizarTotalRecibido();
            });
            $(document).on('submit', 'form#product_add_form', function(e) {
                e.preventDefault();
                var data = $(this).serialize();
                $.ajax({
                    method: 'post',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        // Manejar la respuesta exitosa
                        toastr.success('El vehículo ha sido guardado con éxito');
                        $('#vehiculo_recibido_id').val(response.name);
                        $('#vehiculo_recibido_id_hidden').val(response.product_id);
                        $('.car_new_modal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores
                        alert('Ocurrió un error. Por favor intenta nuevamente.');
                        console.log(xhr.responseText); // Para depuración
                    },
                });
            });
        });
    </script>
@endsection

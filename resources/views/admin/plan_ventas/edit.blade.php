@extends('layouts.app')

@section('title', __('Editar plan de venta'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Editar plan de venta')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('PlanVentaController@update', [$plan->id]),
            'method' => 'PUT',
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
                                {!! Form::date('fecha_plan', $plan->fecha_plan, [
                                    'class' => 'form-control',
                                    'id' => 'fecha_plan',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('numero', __('No. Plan Venta') . ':*') !!}
                            {!! Form::text('numero', $plan->numero, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Número de plan'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-4">
                        {!! Form::label('tipo_plan', 'Tipo' . ':*') !!}
                        {!! Form::select('tipo_plan', ['1' => 'Contado', '2' => 'Crédito'], $plan->tipo_plan, [
                            'class' => 'form-control',
                            'id' => 'tipo_plan',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vendedor_id', __('Vendedor') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                <input type="hidden" id="default_vendedor_id" value="{{ $plan->vendedor_id }}">
                                <input type="hidden" id="default_vendedor_name" value="{{ $plan->vendedor_name }}">
                                {!! Form::select('vendedor_id', [], $plan->vendedor_id, [
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
                                <input type="hidden" id="default_customer_id" value="{{ $plan->cliente_id }}">
                                <input type="hidden" id="default_customer_name" value="{{ $plan->cliente_name }}">

                                {!! Form::select('cliente_id', [], $plan->cliente_id, [
                                    'class' => 'form-control',
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
                                <input type="hidden" id="default_fiador_id" value="{{ $plan->fiador_id }}">
                                <input type="hidden" id="default_fiador_name" value="{{ $plan->fiador_name }}">
                                {!! Form::select('fiador_id', [], $plan->fiador_id, [
                                    'class' => 'form-control mousetrap',
                                    'id' => 'fiador_id',
                                    'placeholder' => 'Seleccione un fiador',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                @endcomponent
                @component('components.widget', ['title' => __('Vehículos')])
                    {!! Form::hidden('vehiculo_recibido_id_hidden', $plan->vehiculo_recibido_id, [
                        'id' => 'vehiculo_recibido_id_hidden',
                    ]) !!}
                    {!! Form::hidden('vehiculo_recibido_id_dos_hidden', $plan->vehiculo_recibido_id_dos, [
                        'id' => 'vehiculo_recibido_id_dos_hidden',
                    ]) !!}
                    {!! Form::hidden('venta_sin_rebajos', $plan->venta_sin_rebajos, ['id' => 'venta_sin_rebajos']) !!}
                    {!! Form::hidden('vehiculo_venta_id_hidden', $plan->vehiculo_venta_id, ['id' => 'vehiculo_venta_id_hidden']) !!}
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehiculo_venta_id', __('Vehículo venta') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::text('vehiculo_venta_id', $plan->veh_venta, [
                                    'class' => 'form-control vehiculo-input',
                                    'required',
                                    'id' => 'vehiculo_venta_id',
                                    'placeholder' => __('Seleccione un vehículo'),
                                    'data-target' => 'vehiculo_venta_id',
                                    'data-url' => 'sold',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehiculo_venta_id', __('Vehículo recibido')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::text('vehiculo_recibido_id', $plan->veh_rec, [
                                    'class' => 'form-control vehiculo-input',
                                    'id' => 'vehiculo_recibido_id',
                                    'placeholder' => __('Seleccione un vehículo'),
                                    'data-target' => 'vehiculo_recibido_id',
                                    'data-url' => 'receive',
                                ]) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_product"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehiculo_recibido_id_dos', __('Vehículo recibido 2')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::text('vehiculo_recibido_id_dos', $plan->veh_rec_dos, [
                                    'class' => 'form-control vehiculo-input',
                                    'id' => 'vehiculo_recibido_id_dos',
                                    'placeholder' => __('Seleccione un vehículo'),
                                    'data-target' => 'vehiculo_recibido_id_dos',
                                    'data-url' => 'receive',
                                ]) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_product"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-default bg-red btn-flat remove_cars" data-name="">Eliminar
                            Vehículos</button>
                    </div>
                @endcomponent
                @if (isset($plan->veh_rec) || isset($plan->veh_rec_dos))
                    @component('components.widget', ['title' => __('Vehículos recibidos')])
                        <div class="col-sm-12">
                            <h4>Vehículo 1</h4>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>
                                {!! Form::text('vehiculo_uno_label', $plan->veh_rec, [
                                    'class' => 'form-control precio',
                                    'readonly',
                                ]) !!}
                                <span class="input-group-btn">
                                    <a target="_blank" href="{{ url('/products/bills/' . $plan->veh_rec_id . '/1') }}" class="btn btn-default bg-white btn-flat" data-name=""><i
                                            class="fa fa-eye text-primary fa-lg"></i></a>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::text('model_uno_label', $plan->model . ' - Modelo', [
                                    'class' => 'form-control precio',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::text('placa_uno_label', $plan->placa . ' - Placa', [
                                    'class' => 'form-control precio',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::text('bin_uno_label', $plan->bin . ' - VIN', [
                                    'class' => 'form-control precio',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                        @if (isset($plan->veh_rec_dos))
                            <div class="col-sm-12">
                                <h4>Vehículo 2</h4>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-car"></i>
                                    </span>
                                    {!! Form::text('vehiculo_dos_label', $plan->veh_rec_dos, [
                                        'class' => 'form-control precio',
                                        'readonly',
                                    ]) !!}
                                    <span class="input-group-btn">
                                        <a target="_blank" href="{{ url('/products/bills/' . $plan->veh_rec_id_dos . '/1') }}"
                                            class="btn btn-default bg-white btn-flat" data-name=""><i
                                                class="fa fa-eye text-primary fa-lg"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::text('model_dos_label', $plan->model_dos . ' - Modelo', [
                                        'class' => 'form-control precio',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::text('placa_dos_label', $plan->placa_dos . ' - Placa', [
                                        'class' => 'form-control precio',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::text('bin_dos_label', $plan->bin_dos . ' - VIN', [
                                        'class' => 'form-control precio',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        @endif
                    @endcomponent
                @endif
                @component('components.widget', ['title' => __('Otros datos')])
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('monto_recibo', __('Vehículo recibido')) !!}
                            {!! Form::text('monto_recibo', number_format($plan->monto_recibo, 2, '.', ','), [
                                'class' => 'form-control precio',
                                'id' => 'monto_recibo',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('monto_efectivo', __('Total efectivo')) !!}
                            {!! Form::text('monto_efectivo', number_format($plan->monto_efectivo, 2, '.', ','), [
                                'class' => 'form-control precio',
                                'id' => 'monto_efectivo',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('total_recibido', __('Total recibido')) !!}
                            {!! Form::text('total_recibido', number_format($plan->total_recibido, 2, '.', ','), [
                                'class' => 'form-control precio',
                                'id' => 'total_recibido',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('total_financiado', __('Total financiado')) !!}
                            {!! Form::text(
                                'total_financiado',
                                $plan->tipo_plan == 1 ? 0 : number_format($plan->total_financiado, 2, '.', ','),
                                [
                                    'class' => 'form-control display_currency precio',
                                    'id' => 'total_financiado',
                                    'required',
                                    'min' => 0,
                                ],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('gastos_plan', __('Gasto plan') . ':*') !!}
                            {!! Form::text('gastos_plan', $plan->gastos_plan, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Gastos del plan'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('desc_forma_pago', __('Forma de pago') . ':*') !!}
                            {!! Form::text('desc_forma_pago', $plan->desc_forma_pago, [
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
                            {!! Form::number('plazo', $plan->plazo, [
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('tasa', __('Tasa') . ':*') !!}
                            {!! Form::number('tasa', $plan->tasa, [
                                'class' => 'form-control',
                                'required',
                                'step' => '0.01',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('cuota', __('Cuota mensual') . ':*') !!}
                            {!! Form::text('cuota', number_format($plan->cuota, 2, '.', ','), [
                                'class' => 'form-control precio',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('tipo_prestamo', 'Tipo prestamo' . ':*') !!}
                        {!! Form::select('tipo_prestamo', ['1' => 'Cuota Nivelada', '2' => 'Intereses'], $plan->tipo_prestamo, [
                            'class' => 'form-control',
                            'id' => 'tipo_prestamo',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('moneda', 'Moneda' . ':*') !!}
                        {!! Form::select('moneda', ['1' => 'Colones', '2' => 'Dolares'], $plan->moneda, [
                            'class' => 'form-control',
                            'id' => 'moneda',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                @endcomponent
            </div>
        </div>
        @can('plan_venta.update')
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right"
                        id="submit_user_button">@lang('messages.save')</button>
                </div>
            </div>
        @endcan
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
            //JS Clientes
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
            set_default_customer()

            function set_default_customer() {
                var default_customer_id = $('#default_customer_id').val();
                var default_customer_name = $('#default_customer_name').val();
                var exists = $('select#customer_id option[value=' + default_customer_id + ']').length;
                if (exists == 0) {
                    $('select#customer_id').append(
                        $('<option>', {
                            value: default_customer_id,
                            text: default_customer_name
                        })
                    );
                }

                $('select#customer_id')
                    .val(default_customer_id)
                    .trigger('change');

                customer_set = true;
            }
            //JS Clientes End
            //JS Fiador
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
            var tipo_plan_val = $('#tipo_plan').val();

            if (tipo_plan_val != 1) {
                set_default_fiador();
            }

            function set_default_fiador() {
                var default_fiador_id = $('#default_fiador_id').val();
                var default_fiador_name = $('#default_fiador_name').val();
                var exists = $('select#fiador_id option[value=' + default_fiador_id + ']').length;
                if (exists == 0) {
                    $('select#fiador_id').append(
                        $('<option>', {
                            value: default_fiador_id,
                            text: default_fiador_name
                        })
                    );
                }

                $('select#fiador_id')
                    .val(default_fiador_id)
                    .trigger('change');

                customer_set = true;
            }
            //JS Fiador End
            //JS Vendedor
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
            set_default_vendedor()

            function set_default_vendedor() {
                var default_vendedor_id = $('#default_vendedor_id').val();
                var default_vendedor_name = $('#default_vendedor_name').val();
                var exists = $('select#vendedor_id option[value=' + default_vendedor_id + ']').length;
                if (exists == 0) {
                    $('select#vendedor_id').append(
                        $('<option>', {
                            value: default_vendedor_id,
                            text: default_vendedor_name
                        })
                    );
                }

                $('select#vendedor_id')
                    .val(default_vendedor_id)
                    .trigger('change');

                customer_set = true;
            }
            //JS Vendedor End
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
            var vehiculoInputs = ['vehiculo_venta_id', 'vehiculo_recibido_id', 'vehiculo_recibido_id_dos'];

            // Manejar selección de vehículo
            $('.vehiculo-input').on('click', function() {
                var newUrl = $(this).data('url');
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
                $('#vehiculo_id').select2({
                    ajax: {
                        url: '/get/vehicles/' + newUrl,
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
                limpiarModal();
                $('.car_modal').modal('show');
            });

            // Recalcular montos si se cambia el valor de efectivo o recibo manualmente
            $('#monto_efectivo, #monto_recibo').on('input', function() {
                actualizarTotalRecibido();
            });

            // Validar el tipo de plan y activar/desactivar el requerido de tipo_prestamo y fiador_id
            $('#tipo_plan').on('change', function() {
                var tipoPlan = $(this).val(); // Obtener el valor seleccionado
                var tipoPrestamo = $('#tipo_prestamo'); // Seleccionar el campo tipo_prestamo
                var fiadorId = $('#fiador_id'); // Seleccionar el campo fiador_id

                if (tipoPlan == '1') { // Si el tipo es Contado
                    tipoPrestamo.prop('required',
                        false); // Quitar el requerido de tipo_prestamo
                    tipoPrestamo.val('').trigger(
                        'change'); // Limpiar la selección de tipo_prestamo
                    tipoPrestamo.closest('.form-group')
                        .hide(); // Ocultar tipo_prestamo si es necesario

                    fiadorId.prop('required', false); // Quitar el requerido de fiador_id
                    fiadorId.val('').trigger('change'); // Limpiar la selección de fiador_id
                    fiadorId.closest('.form-group').hide(); // Ocultar fiador_id si es necesario
                } else { // Si el tipo es Crédito
                    tipoPrestamo.prop('required',
                        true); // Activar el requerido de tipo_prestamo
                    tipoPrestamo.closest('.form-group')
                        .show(); // Mostrar tipo_prestamo si estaba oculto

                    fiadorId.prop('required', true); // Activar el requerido de fiador_id
                    fiadorId.closest('.form-group')
                        .show(); // Mostrar fiador_id si estaba oculto
                }
            });

            // Activar la validación al cargar la página en caso de que haya valores preseleccionados
            $('#tipo_plan').trigger('change');
        });
    </script>
@endsection

@extends('layouts.app')
@section('title', 'Cuentas por Cobrar - Financiamiento')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1></h1>
        <div class="col-md-4 col-xs-12 mt-15 pull-right mb-10">
            <select name="contact_id" class="form-control select2" id="contact_id">
                <option selected value="{{ $contact->id }}">
                    {{ $contact->name . ' (' . $contact->contact_id . ') - (#PV ' . $item->numero . ')' }}
                </option>
                @foreach ($contacts as $contact_id => $contact_data)
                    <option value="{{ $contact_data['rev_id'] }}">
                        {{ $contact_data['contact'] }}

                    </option>
                @endforeach
            </select>
        </div>

    </section>

    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="revenue_id" value="{{ $item->id }}">
        <input type="hidden" id="can_update" value="{{ $canUpdate }}">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open([
                    'url' => action('ProductController@update', [$item->vehiculo_id]),
                    'method' => 'PUT',
                    'id' => 'product_edit_form_receive',
                ]) !!}
                @component('components.widget-accordion', [
                    'title' => __('Información del vehículo'),
                    'id' => 'accordionVehicle',
                ])
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('name', __('Vehículo')) !!}
                            {!! Form::text('name', $item->veh_venta, [
                                'class' => 'form-control',
                                'id' => 'name',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('modelo', __('Modelo')) !!}
                            {!! Form::text('modelo', $item->modelo, [
                                'class' => 'form-control',
                                'id' => 'modelo',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('placa', __('Placa')) !!}
                            {!! Form::text('placa', $item->placa, [
                                'class' => 'form-control',
                                'id' => 'placa',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 5px;">
                        <button type="submit" class="btn btn-primary mt-2">@lang('messages.update')</button>
                    </div>
                @endcomponent
                {!! Form::close() !!}
                {!! Form::open([
                    'url' => action('PlanVentaController@update', [$item->plan_venta_id]),
                    'method' => 'PUT',
                    'id' => 'plan_venta_edit_form',
                ]) !!}
                @component('components.widget-accordion', [
                    'title' => __('Información Cuenta Por Cobrar'),
                    'id' => 'accordionCXC',
                ])
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('aprobado', __('Aprobado Por')) !!}
                            {!! Form::text('aprobado', 'Dubilia Dobles', [
                                'class' => 'form-control',
                                'id' => 'aprobado',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('fecha_inicio', __('Fecha')) !!}
                            {!! Form::text('fecha_inicio', $item->created_at, [
                                'class' => 'form-control',
                                'id' => 'fecha_inicio',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        {!! Form::label('tipo_prestamo', 'Tipo prestamo') !!}
                        {!! Form::select('tipo_prestamo', ['1' => 'Cuota Nivelada', '2' => 'Intereses'], $item->tipo_prestamo, [
                            'class' => 'form-control',
                            'id' => 'tipo_prestamo',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-2">
                        {!! Form::label('moneda', 'Moneda') !!}
                        {!! Form::select('moneda', ['1' => 'Colones', '2' => 'Dolares'], $item->moneda, [
                            'class' => 'form-control',
                            'id' => 'moneda',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-2">
                        {!! Form::label('status', 'Estado') !!}
                        {!! Form::select(
                            'status',
                            ['0' => 'Pendiente', '1' => 'Cobrado', '2' => 'Judicial', '3' => 'Pérdida'],
                            $item->status,
                            [
                                'class' => 'form-control',
                                'id' => 'status',
                                'required',
                                'placeholder' => __('messages.please_select'),
                            ],
                        ) !!}
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('tasa', __('Tasa')) !!}
                            {!! Form::text('tasa', $item->tasa, [
                                'class' => 'form-control',
                                'id' => 'tasa',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Plazo')) !!}
                            {!! Form::text('plazo', $item->plazo, [
                                'class' => 'form-control',
                                'id' => 'plazo',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('cuota', __('Cuota Mensual')) !!}
                            {!! Form::text('cuota', number_format($item->cuota, 2, '.', ','), [
                                'class' => 'form-control',
                                'id' => 'cuota',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('valor_total', __('Monto total')) !!}
                            {!! Form::text('valor_total', number_format($item->valor_total, 2, '.', ','), [
                                'class' => 'form-control',
                                'id' => 'valor_total',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('detalle', __('Detalle') . '') !!}
                        {!! Form::textarea('detalle', $item->detalle, ['class' => 'form-control', 'required', 'rows' => 3]) !!}
                    </div>
                    <div class="col-md-12" style="margin-top: 5px;">
                        <button type="submit" class="btn btn-primary mt-2">@lang('messages.update')</button>
                    </div>
                @endcomponent
                {!! Form::close() !!}
                {!! Form::open([
                    'url' => action('ContactController@update', [$item->cliente_id]),
                    'method' => 'PUT',
                    'id' => 'contact_edit_form_receive',
                ]) !!}
                @component('components.widget-accordion', [
                    'title' => __('Información del cliente'),
                    'id' => 'accordionCliente',
                ])
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('name', __('Nombre')) !!}
                                {!! Form::text('name', $item->name, [
                                    'class' => 'form-control',
                                    'id' => 'name',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('identificacion', __('Identificación')) !!}
                                {!! Form::text('identificacion', $item->identificacion, [
                                    'class' => 'form-control',
                                    'id' => 'identificacion',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('tipo_ident', __('Tipo Identifiación')) !!}
                                {!! Form::text('tipo_ident', $item->tipo_identificacion == 'f' ? 'Física' : 'Jurídica', [
                                    'class' => 'form-control',
                                    'id' => 'tipo_ident',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('telephone', __('Teléfono')) !!}
                                {!! Form::text('landline', $item->telephone, [
                                    'class' => 'form-control',
                                    'id' => 'telephone',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('celular', __('Celular')) !!}
                                {!! Form::text('mobile', $item->celular, [
                                    'class' => 'form-control',
                                    'id' => 'celular',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('email', __('Correo electrónico')) !!}
                                {!! Form::text('email', $item->email, [
                                    'class' => 'form-control',
                                    'id' => 'email',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('direccion', __('Dirección Exacta') . '') !!}
                            {!! Form::textarea('landmark', $item->direccion, [
                                'class' => 'form-control',
                                'required',
                                'rows' => 3,
                            ]) !!}
                        </div>
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary mt-2">@lang('messages.update')</button>
                        </div>
                    </div>
                @endcomponent
                {!! Form::close() !!}
                {{--  <div class="row">
                    <div class="col-md-12">
                        @component('components.filters', ['title' => __('report.filters'), 'id' => 'expenseFilter'])
                            <div class="col-md-4">
                                <div class="form-group">
                                    {!! Form::label('expense_date_range', __('Tomar pagos desde') . ':') !!}
                                    {!! Form::text('date_range', null, [
                                        'placeholder' => __('lang_v1.select_a_date_range'),
                                        'class' => 'form-control',
                                        'id' => 'expense_date_range',
                                        'readonly',
                                    ]) !!}
                                </div>
                            </div>
                        @endcomponent
                    </div>
                </div> --}}
                <div class="container-pay">
                    @component('components.widget-accordion', [
                        'title' => __('Gestión de pagos en esta cuenta'),
                        'id' => 'accordionPagos',
                    ])
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('email', __('Correo electrónico para envíos')) !!}
                                {!! Form::text('opc_email', $item->email, [
                                    'class' => 'form-control',
                                    'id' => 'opc_email',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="payments">
                                    <thead>
                                        <tr>
                                            <th>@lang('messages.action')</th>
                                            <th>@lang('ID')</th>
                                            <th>@lang('Fecha pago')</th>
                                            <th>@lang('Fecha Interés')</th>
                                            <th>@lang('No. Ref')</th>
                                            <th>@lang('Detalle')</th>
                                            <th>@lang('Monto Pagado')</th>
                                            <th>@lang('Amortiza')</th>
                                            <th>@lang('Interés C')</th>
                                            <th>@lang('Calcular')</th>
                                            <th>@lang('Saldo')</th>
                                            <th class="text-center"><a class="btn btn-primary btn_add_row"
                                                    href="{{ action('RevenueController@storeRow', [$item->id]) }}">
                                                    <i class="fa fa-plus"></i> @lang('messages.add')</a></th>
                                            <th class="text-center">
                                                <button type="button" class="btn btn-info sendReport no-print"
                                                    aria-label="Print" id="report">
                                                    <i class="fa fa-envelope"></i> Enviar EC
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>
        </div>
    </section>

@endsection
<div class="modal fade" id="view_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            var revenue_id = $('#revenue_id').val();
            var dates = $('#expense_date_range').val();
            var name = $('#name').val();
            var email = $('#email').val();
            var vehiculo = $('#vehiculo').val();
            var placa = $('#placa').val();
            var htmlContent = "";
            var modelo = $('#modelo').val();
            var can_update = $('#can_update').val();
            $('#contact_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var contactId = $(this).val();
                var revId = selectedOption.data('rev-id');
                var urlContact = "{{ url('/revenues/receive') }}/" + contactId;
                window.location = urlContact;
            });
            var payment_table = $('#payments').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/payments/revenues/' + $('#contact_id').val() + '/' + revenue_id,
                    data: function(d) {
                        /* 
                                                d.start_date = $('input#expense_date_range')
                                                    .data('daterangepicker')
                                                    .startDate.format('YYYY-MM-DD'); */
                    },
                },
                pageLength: 500,
                columnDefs: [{
                        targets: [0],
                        width: "80px"
                    }, // Ancho de la columna 'action'
                    {
                        targets: [1],
                        width: "50px"
                    }, // Ancho de la columna 'id'
                    {
                        targets: [2],
                        width: "10px"
                    }, // Ajusta más columnas si es necesario
                    // Otros ajustes de columnas...
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css({
                        'padding': '5px',
                        'font-size': '12px'
                    });
                    $('#payments thead th').css({
                        'width': '90px',
                        'height': '30px',
                        'padding': '5px',
                        'font-size': '12px'
                    });
                    $(row).find('input').css({
                        'width': '90px',
                        'height': '30px',
                        'padding': '5px',
                        'font-size': '12px'
                    });

                    $(row).find('input.saldo').css({
                        'width': '110px',
                        'height': '30px',
                        'padding': '5px'
                    });
                },
                columns: [{
                        "data": "action",
                        orderable: false
                    },
                    {
                        "data": "id",
                        orderable: false
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "fecha_interes"
                    },
                    {
                        "data": "referencia"
                    },
                    {
                        "data": "detalle",
                        orderable: false
                    },
                    {
                        "data": "paga",
                        orderable: false
                    },
                    {
                        "data": "amortiza",
                        orderable: false
                    },
                    {
                        "data": "interes_c",
                        orderable: false
                    },
                    {
                        "data": "calcular",
                        orderable: false
                    },
                    {
                        "data": "monto_general",
                        orderable: false
                    },
                    {
                        "data": "empty",
                        orderable: false
                    },
                    {
                        "data": "empty_email",
                        orderable: false
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    toggleInputs();
                },
                initComplete: function() {
                    $('.dataTables_paginate').css('margin-top', '15px');
                },
                dom: '<"text-center"B><"top"p>rtip',
                buttons: [{
                        extend: 'pageLength',
                        text: 'Mostrando 500',
                        titleAttr: 'Mostrar registros'
                    },
                    {
                        extend: 'colvis',
                        text: 'Visibilidad de columna'
                    },
                    {
                        extend: 'print',
                        text: 'Estado de Cuenta',
                        customize: function(win) {
                            $(win.document.body).find('h1').remove();
                            $(win.document.body).find('div.printHeader').remove();
                            var body = $(win.document.body).find('table tbody');

                            // Función para formatear valores monetarios
                            function formatCurrency(value) {
                                value = value.toFixed(2);
                                var formattedValue = new Intl.NumberFormat('es-ES', {
                                    minimumFractionDigits: 3,
                                    maximumFractionDigits: 3
                                }).format(value);
                                return __currency_trans_from_en(formattedValue, true, true);
                            }

                            // Llama a la función que agrupa los datos de la planilla
                            var groupedData =
                                groupPaymentData(); // Ajusta la función si es necesario
                            var grandTotalPaga = 0;
                            var grandTotalAmortiza = 0;
                            var grandTotalInteres = 0;

                            body.empty();

                            // Insertar las filas agrupadas en el reporte
                            groupedData.forEach(function(row) {
                                var formattedPaga = __currency_trans_from_en(row.paga, true,
                                    true);
                                var formattedAmortiza = __currency_trans_from_en(row
                                    .amortiza, true, true);
                                var formattedInteres = __currency_trans_from_en(row
                                    .interes_c, true, true);
                                var formattedSaldo = __currency_trans_from_en(row.saldo,
                                    true, true);

                                // Acumular los totales
                                grandTotalPaga += parseFloat(row.paga);
                                grandTotalAmortiza += parseFloat(row.amortiza);
                                grandTotalInteres += parseFloat(row.interes_c);

                                // Inserta cada fila con sus datos formateados
                                body.append(
                                    '<tr>' +
                                    '<td>' + row.fecha_pago + '</td>' +
                                    '<td>' + row.fecha_interes + '</td>' +
                                    '<td>' + row.referencia + '</td>' +
                                    '<td>' + row.detalle + '</td>' +
                                    '<td>' + formattedPaga + '</td>' +
                                    '<td>' + formattedAmortiza + '</td>' +
                                    '<td>' + formattedInteres + '</td>' +
                                    '<td>' + formattedSaldo + '</td>' +
                                    '</tr>'
                                );
                            });

                            // Formatear los totales
                            var formattedGrandTotalPaga = formatCurrency(grandTotalPaga);
                            var formattedGrandTotalAmortiza = formatCurrency(grandTotalAmortiza);
                            var formattedGrandTotalInteres = formatCurrency(grandTotalInteres);

                            // Agregar la fila final con los totales
                            body.append(
                                '<tr style="background-color: #d9d9d9;">' +
                                // Fondo gris claro más oscuro para los totales
                                '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>' +
                                '<td><strong>' + formattedGrandTotalPaga + '</strong></td>' +
                                '<td><strong>' + formattedGrandTotalAmortiza +
                                '</strong></td>' +
                                '<td><strong>' + formattedGrandTotalInteres + '</strong></td>' +
                                '<td></td>' + // Columna de saldo vacía en la fila de totales
                                '</tr>'
                            );

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th style="background-color: #023b9d; color: white; padding:8px;">Fecha de pago</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Fecha de interés</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">No. Ref</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Detalle</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Monto pagado</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Amortiza</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Interes Corriente</th>' +
                                '<th style="background-color: #023b9d; color: white;  padding:8px;">Saldo</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de Estado de cuenta de: ' +
                                    name + '</h3>' +
                                    '<p style="margin-top: 5px; text-align:center;">Generado al: ' +
                                    dates + '</p>' +
                                    '</div>' +
                                    '<div class="text-center" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">' +
                                    '<h4 style="text-align: center; margin: 0;">Información del Vehículo</h4>' +
                                    '<p style="margin: 5px 0;"><strong>Marca:</strong> ' +
                                    vehiculo + '</p>' +
                                    '<p style="margin: 5px 0;"><strong>Modelo:</strong> ' + modelo +
                                    '</p>' +
                                    '<p style="margin: 5px 0;"><strong>Placa:</strong> ' + placa +
                                    '</p>' +
                                    '</div>'
                                );

                            $(win.document.body).find('table')
                                .addClass('display')
                                .css({
                                    'font-size': 'inherit',
                                    'border-collapse': 'collapse',
                                    'width': '100%'
                                })
                                .find('td').css({
                                    'border': '1px solid #ddd',
                                    'padding': '8px',
                                    'color': '#666' // Color gris para el texto de las celdas
                                });

                            // Establecer los colores intermitentes en las filas
                            $(win.document.body).find('table tbody tr:odd').css({
                                'background-color': '#f2f2f2', // Gris claro
                                'color': '#666', // Texto en gris
                                'font-size': '9pt' // Tamaño de letra más pequeño
                            });
                            $(win.document.body).find('table tbody tr:even').css({
                                'background-color': '#e6e6e6', // Gris un poco más oscuro
                                'color': '#666', // Texto en gris
                                'font-size': '9pt' // Tamaño de letra más pequeño
                            });

                            htmlContent = $(win.document.body).html();
                        }
                    }


                ]
            });
            if ($('#expense_date_range').length == 1) {
                $('#expense_date_range').daterangepicker(
                    dateRangeSettings,
                    function(start) {
                        $('#expense_date_range').val(
                            start.format(moment_date_format)
                        );
                        payment_table.ajax.reload();
                    }
                );

                $('#expense_date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $('#product_sr_date_filter').val('');
                    payment_table.ajax.reload();
                });
            }

            function groupPaymentData() {
                var selected_rows = [];
                var i = 0;
                $('#payments tbody tr').each(function() {
                    var row = $(this);
                    var fecha_pago = row.find('td:eq(2) input[type="text"]').val() || '';
                    var fecha_interes = row.find('td:eq(3) input[type="text"]').val() || '';
                    var referencia = row.find('td:eq(4) input[type="text"]').val() || '';
                    var detalle = row.find('td:eq(5) input[type="text"]').val() || '';
                    var paga = row.find('td:eq(6) input[type="text"]').val() || '0';
                    var amortiza = row.find('td:eq(7) input[type="text"]').val() || '0';
                    var interes_c = row.find('td:eq(8) input[type="text"]').val() || '0';
                    var saldo = row.find('td:eq(10) input[type="text"]').val() || '0';

                    selected_rows[i++] = {
                        fecha_pago: fecha_pago.trim(),
                        fecha_interes: fecha_interes.trim(),
                        referencia: referencia.trim(),
                        detalle: detalle.trim(),
                        paga: paga,
                        amortiza: amortiza,
                        interes_c: interes_c,
                        saldo: saldo
                    };
                });

                return selected_rows;
            }
            $('#payments').on('focus', 'input[type="text"], input[type="number"]', function() {
                var input = $(this);
                var valorSinFormato = input.val().replace(/,/g, '');
                input.data('initialValue', valorSinFormato);
            });
            $('#payments').on('blur', 'input[type="text"], input[type="number"]', function() {
                var input = $(this);
                var bOk = true;
                var value = input.val().replace(/,/g, '');
                var initialValue = input.data('initialValue');
                var column_name = input.attr('name');
                var row_id = input.closest('tr').find('td').eq(1).text();
                var fecha_pago = input.closest('tr').find('td').eq(2).find('input').val();
                var fecha_interes_act = input.closest('tr').find('td').eq(3).find('input').val();
                var totalColumns = input.closest('tr').find('td').length;
                var allRows = input.closest('tbody').find('tr');
                var penultimaFila = allRows.eq(allRows.length - 2);
                var saldo_anterior = penultimaFila.find('td').eq(10).find('input').val();
                var fecha_pago_interes_ant = penultimaFila.find('td').eq(3).find('input').val();
                var tasa = $('#tasa').val();
                var cuota = $('#cuota').val().replace(/,/g, '').replace(/\.\d+$/, '');
                var inputType = input.attr('type'); // Obtiene el tipo de input
                // Verificar si es un input de tipo "text" o "number" y realizar las validaciones correspondientes
                var isValid = false;
                if (inputType === 'text') {
                    // Validar que el campo de texto no esté vacío
                    isValid = value.trim() !== '';
                } else if (inputType === 'number') {
                    // Validar que el número sea mayor o igual a 0
                    isValid = value >= 0;
                }

                // Solo procede si el valor cambió, la entrada es válida y se pueden hacer actualizaciones
                if (value != initialValue && isValid && saldo_anterior != 0) {
                    if (column_name === "paga") {
                        saldo_anterior = saldo_anterior.replace(/,/g, '');
                        var diasCalcPorSaldo = calcDiasInteres(saldo_anterior, tasa, value,
                            fecha_interes_act);
                        var diasCalcEntreFechas = diferenciaEnDias(fecha_pago_interes_ant,
                            fecha_interes_act);
                        if (diasCalcPorSaldo < diasCalcEntreFechas) {
                            //Calcular nueva fecha
                            var diasCubiertos = diasCalcPorSaldo;
                            var partesFecha = fecha_pago_interes_ant.split('/');
                            var dia = parseInt(partesFecha[0], 10);
                            var mes = parseInt(partesFecha[1], 10) - 1; // Los meses en JavaScript son 0-11
                            var anio = parseInt(partesFecha[2], 10);
                            var fechaInicial = new Date(anio, mes, dia);
                            // Sumar los días cubiertos a la fecha de pago
                            fechaInicial.setDate(fechaInicial.getDate() + diasCubiertos);

                            // Formatear la nueva fecha a formato dd/MM/yyyy
                            var nuevoDia = fechaInicial.getDate().toString().padStart(2, '0');
                            var nuevoMes = (fechaInicial.getMonth() + 1).toString().padStart(2,
                                '0'); // Ajustar el mes
                            var nuevoAnio = fechaInicial.getFullYear();
                            var nuevaFechaPago = `${nuevoDia}/${nuevoMes}/${nuevoAnio}`;
                            fecha_interes_cero = nuevaFechaPago;

                            swal({
                                title: LANG.sure,
                                text: 'La cuota pagada solo cubre hasta el día ' +
                                    nuevaFechaPago +
                                    ',¿Desea continuar?',
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) => {
                                if (willDelete) {
                                    fecha_interes_act = fecha_interes_cero;
                                    ejecutarAjax();
                                } else {
                                    restablecerValorInicial
                                        (); // Restablece y formatea el valor inicial si el usuario cancela
                                }
                            });

                        } else {
                            ejecutarAjax
                                (); // Si no hay problema con la amortización, realiza la solicitud AJAX directamente
                        }
                    } else {
                        ejecutarAjax
                            (); // Si la validación de la cuota no es relevante, realiza la solicitud AJAX
                    }
                }

                function restablecerValorInicial() {
                    // Formatea el valor inicial con comas para los miles
                    let formattedInitialValue = new Intl.NumberFormat('en-US').format(initialValue);
                    input.val(formattedInitialValue); // Restablece el valor inicial formateado
                }

                function ejecutarAjax() {
                    // Guardar la posición del siguiente input antes de la recarga
                    var currentInputIndex = input.closest('td').index() - 1;

                    $.ajax({
                        url: '/payment-revenue-update/' + row_id + '/' + revenue_id,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            column: column_name,
                            value: value,
                            saldo_anterior: saldo_anterior,
                            fecha_pago_anterior: fecha_pago_interes_ant,
                            fecha_interes_act: fecha_interes_act
                        },
                        success: function(response) {
                            console.log(response.msg);
                            if (response.success) {
                                if (response.msg == -1) {
                                    swal({
                                        title: "Problema entre fechas",
                                        text: 'La fecha de interés es menor que la fecha de interés anterior. No se pudieron realizar los cambios',
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    }).then((willDelete) => {
                                        input.closest('tr').find('td').eq(3).find(
                                            'input').focus().select();
                                        input.val(initialValue);
                                    });
                                    return;
                                }
                                htmlContent = "";
                                if (column_name == "paga") {
                                    // Encontrar y almacenar los valores de los inputs en la fila procesada
                                    ['amortiza', 'interes_c', 'monto_general', 'paga'].forEach(
                                        function(
                                            inputName) {
                                            // Buscar el input con el nombre especificado en la fila procesada
                                            var inputField = input.closest('tr').find(
                                                `input[name="${inputName}"]`);

                                            if (inputField.length > 0) {
                                                // Asignar el valor desde response.data al input
                                                if (response.data[inputName] !==
                                                    undefined && response.data[
                                                        inputName] !== null) {
                                                    inputField.val(response.data[
                                                        inputName]);
                                                }
                                            } else {
                                                console.warn(
                                                    `No se encontró un input con el nombre '${inputName}' en la fila procesada`
                                                );
                                            }
                                        });
                                } else if (column_name == "amortiza" || column_name ==
                                    "interes_c" || column_name == "monto_general" ||
                                    column_name == "created_at" || column_name ==
                                    "fecha_interes") {
                                    [column_name].forEach(
                                        function(
                                            inputName) {
                                            // Buscar el input con el nombre especificado en la fila procesada
                                            var inputField = input.closest('tr').find(
                                                `input[name="${inputName}"]`);

                                            if (inputField.length > 0) {
                                                // Asignar el valor desde response.data al input
                                                inputField.val(response.data[inputName]);
                                            } else {
                                                console.warn(
                                                    `No se encontró un input con el nombre '${inputName}' en la fila procesada`
                                                );
                                            }
                                        });
                                }
                            } else {
                                console.log(response.msg);
                            }
                        },
                        error: function(xhr) {
                            // Manejo de error
                        }
                    });
                }
            });
            $('#payments').on('input', '.fecha', function() {
                let input = $(this).val().replace(/\D/g, ''); // Elimina todo lo que no sea un número
                if (input.length <= 2) {
                    $(this).val(input);
                } else if (input.length <= 4) {
                    $(this).val(`${input.slice(0, 2)}/${input.slice(2)}`);
                } else {
                    $(this).val(`${input.slice(0, 2)}/${input.slice(2, 4)}/${input.slice(4, 8)}`);
                }
            });
            $('#payments').on('input', '.number', function() {
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
            $(document).on('click', '.btn_add_row', function(event) {
                event.preventDefault(); // Evita que el enlace siga el href de forma predeterminada

                var href = $(this).attr('href'); // Obtiene el href del botón

                $.ajax({
                    url: href, // Usa el href como la URL para la petición AJAX
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            payment_table.ajax.reload();
                            setTimeout(function() {
                                var lastRow = $(
                                    '#payments tbody tr:last'
                                ); // Selecciona la última fila
                                if (lastRow.length) {
                                    lastRow[0].scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                    // Enfoca la segunda columna (índice 1, ya que los índices empiezan en 0)
                                    var secondColumnInput = lastRow.closest('tr').find(
                                        'td').eq(2).find('input');
                                    if (secondColumnInput.length) {
                                        secondColumnInput.focus().select();
                                    }
                                }
                            }, 1000);
                            toggleInputs();
                        } else {
                            swal({
                                title: response.msg,
                                icon: "warning",
                                buttons: true,
                                dangerMode: false,
                            });
                        }
                    }
                });
            });
            $(document).on('click', 'button.delete_row_button', function() {

                swal({
                    title: LANG.sure,
                    text: 'Esta linea será eliminada, desea continuar?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();
                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    payment_table.ajax.reload();
                                    toggleInputs();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'button.update_row_button', function() {
                var currentRow = $(this).closest('tr');
                var allRows = $(this).closest('tbody').find('tr');
                var penultimaFila = allRows.eq(allRows.length - 2);
                var input_fecha_act = $(this).closest('tr').find('td').eq(2).find('input');
                // Obtén los valores de las celdas de la fila actual y de la penúltima fila
                var saldo = penultimaFila.find('td').eq(10).find('input[type="text"]').val().replace(/,/g,
                    '');
                var paga = currentRow.find('td').eq(6).find('input[type="text"]').val().replace(/,/g, '');
                var fecha_anterior_int = penultimaFila.find('td').eq(3).find('input[type="text"]').val();
                var fecha_actual = currentRow.find('td').eq(3).find('input[type="text"]').val();
                var tasa = $('#tasa').val();
                var href = $(this).data('href');
                // Calcula la cantidad de días por saldo y entre fechas
                var diasCalcPorSaldo = calcDiasInteres(saldo, tasa, paga,
                    fecha_actual);
                var diasCalcEntreFechas = diferenciaEnDias(fecha_anterior_int,
                    fecha_actual);

                if (diasCalcPorSaldo < diasCalcEntreFechas) {
                    // Calcula la nueva fecha de pago cuando el saldo no cubre todos los días
                    var diasCubiertos = diasCalcPorSaldo;
                    var partesFecha = fecha_anterior_int.split('/');
                    var dia = parseInt(partesFecha[0], 10);
                    var mes = parseInt(partesFecha[1], 10) - 1;
                    var anio = parseInt(partesFecha[2], 10);
                    var fechaInicial = new Date(anio, mes, dia);
                    fechaInicial.setDate(fechaInicial.getDate() + diasCubiertos);

                    var nuevoDia = fechaInicial.getDate().toString().padStart(2, '0');
                    var nuevoMes = (fechaInicial.getMonth() + 1).toString().padStart(2, '0');
                    var nuevoAnio = fechaInicial.getFullYear();
                    fecha_interes_cero = `${nuevoDia}/${nuevoMes}/${nuevoAnio}`;
                    // Mensaje de confirmación
                    swal({
                        title: LANG.sure,
                        text: 'La cuota pagada solo cubre hasta el día ' + fecha_interes_cero +
                            ',¿Desea continuar?',
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            fecha_actual = fecha_interes_cero;
                            realizarAjax(href);
                        }
                    });
                } else {
                    realizarAjax(href); // Si no hay problemas, ejecuta el AJAX sin mensaje
                }

                function realizarAjax(href) {
                    var data = {
                        saldo: saldo,
                        paga: paga,
                        fecha_anterior_int: fecha_anterior_int,
                        fecha_actual: fecha_actual,
                        tasa: tasa,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                    $.ajax({
                        method: "POST",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result) {
                            if (result.success == true) {
                                if (result.msg == -1) {
                                    swal({
                                        title: "Problema entre fechas",
                                        text: 'La fecha de interés es menor que la fecha de interés anterior. No se pudo realizar el cálculo',
                                        icon: "warning",
                                        buttons: true,
                                        dangerMode: true,
                                    }).then((willDelete) => {
                                        input_fecha_act.closest('tr').find('td').eq(3)
                                            .find('input').focus().select();
                                    });
                                    return;
                                }
                                toastr.success(result.msg);
                                //payment_table.ajax.reload();
                                ['amortiza', 'interes_c', 'monto_general', 'paga'].forEach(
                                    function(
                                        inputName) {
                                        // Buscar el input con el nombre especificado en la fila procesada
                                        var inputField = currentRow.closest('tr').find(
                                            `input[name="${inputName}"]`);

                                        if (inputField.length > 0) {
                                            // Asignar el valor desde response.data al input
                                            inputField.val(result.data[inputName]);
                                        } else {
                                            console.warn(
                                                `No se encontró un input con el nombre '${inputName}' en la fila procesada`
                                            );
                                        }
                                    });
                                htmlContent = "";
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
            });
            $(document).on('click', 'a.view-payment', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    dataType: 'html',
                    success: function(result) {
                        $('#view_payment_modal')
                            .html(result)
                            .modal('show');
                        __currency_convert_recursively($('#view_payment_modal'));
                    },
                });
            });
            $(document).on('click', 'button.sendReport', function(e) {
                e.preventDefault();
                var htmlContentWithoutImage = htmlContent.replace(/<img[^>]*>/g, '');
                var opc_email = $('#opc_email').val();
                if (htmlContent == "") {
                    toastr.warning("Debe generar el *ESTADO DE CUENTA* para que se cargue la plantilla");
                    return;
                }
                $.ajax({
                    url: '/send-payment-report',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        html_content: htmlContentWithoutImage,
                        name: name,
                        dates: dates,
                        vehiculo: vehiculo,
                        modelo: modelo,
                        placa: placa,
                        email: opc_email
                    },
                    success: function(response) {
                        toastr.success(
                            'El estado de cuenta se ha enviado por correo al cliente.');
                    },
                    error: function(xhr) {
                        alert('Ocurrió un error al enviar el estado de cuenta.');
                    }
                });
            });

            function toggleInputs() {
                // Usar la API de DataTables para obtener las filas visibles
                htmlContent = "";
                var allRows = payment_table.rows({
                    'search': 'applied'
                }).nodes(); // Accede a todas las filas visibles

                var totalRows = allRows.length; // Contar el número de filas visibles

                if (totalRows === 0) {
                    return; // Si no hay filas, salir de la función
                }

                // Iterar sobre las filas visibles
                $(allRows).each(function(index, row) {
                    var inputs = $(row).find(
                        'input[type="text"]').not(
                        '[name="fecha_interes"], [name="referencia"], [name="detalle"], [name="created_at"]'
                    );
                    // Encuentra los inputs en cada fila

                    // Si solo hay una fila, los inputs quedan habilitados (sin readonly)
                    if (totalRows === 1) {
                        inputs.prop('readonly', true); // Elimina readonly si solo hay una fila
                    } else {
                        // Si es la última fila, habilita los inputs (sin readonly), de lo contrario, agrégales readonly
                        if (index === totalRows - 1 && can_update == true) {
                            inputs.prop('readonly', false); // Última fila editable
                        } else {
                            inputs.prop('readonly', true); // Otras filas solo de lectura
                        }
                    }
                });
            }

            function calcDiasInteres(saldo_anterior, tasa, paga, fecha_interes_act) {
                const mes_dias = 30;
                const calc_tasa = tasa === 0 ? saldo_anterior : saldo_anterior * (tasa / 100);
                const pago_diario = calc_tasa / mes_dias;
                dias_calculados = paga / pago_diario;
                dias_calculados = Math.floor(dias_calculados);
                return dias_calculados;
            }

            function diferenciaEnDias(fechaInicio, fechaFin) {
                const [diaInicio, mesInicio, anioInicio] = fechaInicio.split('/').map(Number);
                const [diaFin, mesFin, anioFin] = fechaFin.split('/').map(Number);

                const fechaInicioObj = new Date(anioInicio, mesInicio - 1, diaInicio);
                const fechaFinObj = new Date(anioFin, mesFin - 1, diaFin);
                const diferenciaMilisegundos = fechaFinObj - fechaInicioObj;
                const diferenciaDias = Math.floor(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

                return diferenciaDias;
            }
            toggleInputs();
            $(document).on('click', 'button.sendPaymentWhats, button.sendPaymentDetail', function() {
                var buttonId = $(this).attr('id');
                var data = $(this).serialize();
                var pay_id = $('#payment_id').val();
                var opc_email = $('#opc_email').val();

                $.ajax({
                    method: 'get',
                    url: '/payment-send-whats-id/' + pay_id + '/' + revenue_id + '/' + buttonId +
                        '/' + opc_email,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            if (result.type === "whats") {
                                toastr.success(result.msg);
                                window.open(result.whatsapp_link, '_blank');
                            } else {
                                toastr.success("Correo enviado con éxito al cliente");
                            }

                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    </script>
@endsection

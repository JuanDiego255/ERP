@extends('layouts.app')
@section('title', 'Cuentas por Cobrar - Financiamiento')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1></h1>
        <div class="col-md-4 col-xs-12 mt-15 pull-right mb-10">
            {!! Form::select('contact_id', $contacts, $contact->id, [
                'class' => 'form-control select2',
                'id' => 'contact_id',
            ]) !!}
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="revenue_id" value="{{ $item->id }}">
        <input type="hidden" id="can_update" value="{{ $canUpdate }}">
        <div class="row">
            <div class="col-md-12">
                @component('components.widget-accordion', [
                    'title' => __('Información del vehículo'),
                    'id' => 'accordionVehicle',
                ])
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehículo', __('Vehículo')) !!}
                            {!! Form::text('vehículo', $item->veh_venta, [
                                'class' => 'form-control',
                                'id' => 'vehiculo',
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
                                'readonly',
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
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
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
                    <div class="form-group col-sm-3">
                        {!! Form::label('tipo_prestamo', 'Tipo prestamo') !!}
                        {!! Form::select('tipo_prestamo', ['1' => 'Cuota Nivelada', '2' => 'Intereses'], $item->tipo_prestamo, [
                            'class' => 'form-control',
                            'id' => 'tipo_prestamo',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('moneda', 'Moneda') !!}
                        {!! Form::select('moneda', ['1' => 'Colones', '2' => 'Dolares'], $item->moneda, [
                            'class' => 'form-control',
                            'id' => 'moneda',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('tasa', __('Tasa')) !!}
                            {!! Form::text('tasa', $item->tasa, [
                                'class' => 'form-control',
                                'id' => 'tasa',
                                'readonly',
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
                        {!! Form::textarea('detalle', $item->detalle, ['class' => 'form-control', 'required', 'readonly', 'rows' => 3]) !!}
                    </div>
                @endcomponent
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
                                    'readonly',
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
                                {!! Form::text('telephone', $item->telephone, [
                                    'class' => 'form-control',
                                    'id' => 'telephone',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('celular', __('Celular')) !!}
                                {!! Form::text('celular', $item->celular, [
                                    'class' => 'form-control',
                                    'id' => 'celular',
                                    'readonly',
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
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('direccion', __('Dirección Exacta') . '') !!}
                            {!! Form::textarea('direccion', $item->direccion, [
                                'class' => 'form-control',
                                'required',
                                'readonly',
                                'rows' => 3,
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
                <div class="row">
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
                </div>
                <div class="container-pay">
                    @component('components.widget-accordion', [
                        'title' => __('Gestión de pagos en esta cuenta'),
                        'id' => 'accordionPagos',
                    ])
                        @slot('tool')
                            <div class="box-tools">

                            </div>
                        @endslot
                        <div class="col-md-4">
                            <button type="button" class="btn btn-info sendReport no-print" aria-label="Print" id="report">
                                <i class="fa fa-envelope"></i> Enviar Reporte
                            </button>
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
            $('#contact_id').change(function() {
                window.location = "{{ url('/revenues/receive/') }}/" + $(this).val();
            });
            var revenue_id = $('#revenue_id').val();
            var dates = $('#expense_date_range').val();
            var name = $('#name').val();
            var email = $('#email').val();
            var vehiculo = $('#vehiculo').val();
            var placa = $('#placa').val();
            var htmlContent = "";
            var modelo = $('#modelo').val();
            var can_update = $('#can_update').val();
            var payment_table = $('#payments').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/payments/revenues/' + $('#contact_id').val(),
                    data: function(d) {
                        d.start_date = $('input#expense_date_range')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
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
                console.log(initialValue + ' - ' + value);
                var column_name = input.attr('name');
                var row_id = input.closest('tr').find('td').eq(1).text();
                var fecha_pago = input.closest('tr').find('td').eq(2).find('input').val();
                var totalColumns = input.closest('tr').find('td').length;
                var allRows = input.closest('tbody').find('tr');
                var penultimaFila = allRows.eq(allRows.length - 2);
                var saldo = penultimaFila.find('td').eq(10).find('input').val();
                var tasa = $('#tasa').val();
                var cuota = $('#cuota').val().replace(/,/g, '').replace(/\.\d+$/, '');
                var inputType = input.attr('type'); // Obtiene el tipo de input
                var fecha_interes_cero = "";

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
                if (value != initialValue && isValid && saldo != 0) {
                    var pagoDiario = cuota / 30;
                    var diasCubiertos = Math.floor(value / pagoDiario);
                    var partesFecha = fecha_pago.split('/');
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
                    if (column_name === "paga" && parseFloat(value) < parseFloat(cuota)) {
                        saldo = saldo.replace(/,/g, '').replace(/\.\d+$/, '');
                        var interes_calc = parseFloat(saldo * (tasa / 100));
                        var amortiza = parseFloat(value - interes_calc);
                        if (amortiza < 0) {
                            swal({
                                title: LANG.sure,
                                text: 'La cuota pagada solo cubre ' + diasCubiertos +
                                    ' días, nueva fecha de interés: ' + nuevaFechaPago +
                                    ',¿Desea continuar?',
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) => {
                                if (willDelete) {
                                    ejecutarAjax
                                        (1,
                                            fecha_interes_cero
                                        ); // Llama a la función para realizar la solicitud AJAX
                                } else {
                                    restablecerValorInicial
                                        (); // Restablece y formatea el valor inicial si el usuario cancela
                                }
                            });
                        } else {
                            ejecutarAjax
                                (
                                    0, fecha_interes_cero
                                ); // Si no hay problema con la amortización, realiza la solicitud AJAX directamente
                        }
                    } else {
                        ejecutarAjax
                            (0,
                                fecha_interes_cero
                            ); // Si la validación de la cuota no es relevante, realiza la solicitud AJAX
                    }
                }

                function restablecerValorInicial() {
                    // Formatea el valor inicial con comas para los miles
                    let formattedInitialValue = new Intl.NumberFormat('en-US').format(initialValue);
                    input.val(formattedInitialValue); // Restablece el valor inicial formateado
                }

                function ejecutarAjax(es_cero, pfecha_interes_cero) {
                    // Deshabilita todos los campos de entrada mientras se procesa la solicitud
                    console.log(es_cero);
                    $('input[type="text"], input[type="number"]').prop('disabled', true);

                    // Guardar la posición del siguiente input antes de la recarga
                    var currentInputIndex = input.closest('td').index() - 1;

                    $.ajax({
                        url: '/payment-revenue-update/' + row_id + '/' + revenue_id,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            column: column_name,
                            value: value,
                            es_cero: es_cero,
                            fecha_interes_cero: pfecha_interes_cero
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                htmlContent = "";
                                payment_table.ajax.reload(function() {
                                    // Buscar la fila por el 'row_id' después de recargar la tabla
                                    var updatedRow = $('#payments tbody tr').filter(
                                        function() {
                                            return $(this).find('td').eq(1)
                                                .text() === row_id;
                                        });

                                    // Encontrar el siguiente input dentro de esa fila
                                    var nextInput = updatedRow.find('td input').eq(
                                        currentInputIndex);

                                    // Si existe el siguiente input, aplicar el foco y seleccionar el texto
                                    if (nextInput.length > 0) {
                                        nextInput.focus().select();
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            // Manejo de error
                        },
                        complete: function() {
                            // Rehabilita los campos de entrada después de que la solicitud se complete
                            $('input[type="text"], input[type="number"]').prop('disabled',
                                false);
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
                        console.log(response);
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
                                    console.log(result)
                                    toastr.success(result.msg);
                                    payment_table.ajax.reload();
                                    toggleInputs();
                                } else {
                                    console.log(result)
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

                // Obtén los valores de las celdas de la fila actual y de la penúltima fila
                var saldo = penultimaFila.find('td').eq(10).find('input[type="text"]').val();
                var amortiza = currentRow.find('td').eq(7).find('input[type="text"]').val();
                var interes = currentRow.find('td').eq(8).find('input[type="text"]').val();

                swal({
                    title: LANG.sure,
                    text: 'Desea realizar el cálculo para esta linea?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');

                        // Incluye los valores en el objeto data que se envía en la solicitud
                        var data = {
                            saldo: saldo,
                            amortiza: amortiza,
                            interes: interes,
                            _token: $('meta[name="csrf-token"]').attr(
                                'content')
                        };

                        $.ajax({
                            method: "POST",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    console.log(result);
                                    toastr.success(result.msg);
                                    payment_table.ajax.reload();
                                    htmlContent = "";
                                } else {
                                    console.log(result);
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'a.view-payment', function(e) {
                e.preventDefault();
                console.log(htmlContent);
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
                console.log(htmlContentWithoutImage);
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
                        email: email
                    },
                    success: function(response) {
                        toastr.success(
                            'El estado de cuenta se ha enviado por correo al cliente.');
                    },
                    error: function(xhr) {
                        console.log(xhr)
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
                    console.log("No hay filas en la tabla.");
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
            toggleInputs();
            $(document).on('click', 'button.sendPaymentWhats, button.sendPaymentDetail', function() {
                var buttonId = $(this).attr('id');
                var data = $(this).serialize();
                var pay_id = $('#payment_id').val();

                $.ajax({
                    method: 'get',
                    url: '/payment-send-whats-id/' + pay_id + '/' + revenue_id + '/' + buttonId,
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

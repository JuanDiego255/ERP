@extends('layouts.app')
@section('title', __('Gastos de vehículos - Reportes'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Gastos de vehículos - Reportes')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="print_section">
            <h2>{{ session()->get('business.name') }} - @lang('report.profit_loss')</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('expense_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'expense_date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('expense_payment_status', __('purchase.payment_status') . ':') !!}
                            {!! Form::select(
                                'expense_payment_status',
                                ['-1' => __('Todos'), '1' => __('Vendido'), '2' => __('No vendido')],
                                null,
                                ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')],
                            ) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div id="pl_data_div">
            </div>
        </div>
        <div class="row no-print">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#profit_by_products" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes"
                                    aria-hidden="true"></i> @lang('Gastos por vehículo')</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="profit_by_products">
                            @include('report.partials.profit_by_products')
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            if ($('#expense_date_range').length == 1) {
                $('#expense_date_range').daterangepicker(
                    dateRangeSettings,
                    function(start, end) {
                        $('#expense_date_range').val(
                            start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                        );
                        bills_table.ajax.reload();
                    }
                );

                $('#expense_date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $('#product_sr_date_filter').val('');
                    bills_table.ajax.reload();
                });
            }
            $('select#expense_payment_status')
                .on(
                    'change',
                    function() {
                        bills_table.ajax.reload();
                    }
                );
            var bills_table = $('#bills_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/reports/profit-loss',
                    data: function(d) {
                        d.payment_status = $('select#expense_payment_status').val();
                        d.start_date = $('input#expense_date_range')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        d.end_date = $('input#expense_date_range')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                    },
                },
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                "columns": [{
                        "data": "fecha_compra"
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'prov_name',
                        name: 'contacts.name'
                    },
                    {
                        "data": "descripcion"
                    },
                    {
                        "data": "monto"
                    },
                    {
                        data: 'factura',
                        name: 'vehicle_bills.factura'
                    },
                    {
                        "data": "added_by"
                    }
                ],
                buttons: [{
                        extend: 'pageLength',
                        text: 'Mostrando 25',
                        titleAttr: 'Mostrar registros'
                    },
                    {
                        extend: 'print',
                        text: 'Reporte General',
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
                            var groupedData = groupBillsData();
                            var grandTotalMonto = 0;
                            var vehiculo = "";
                            body.empty();
                            // Insertar las filas agrupadas en el reporte
                            groupedData.forEach(function(row) {
                                var formattedMonto = __currency_trans_from_en(row.monto,
                                    true,
                                    true);

                                // Acumular los totales
                                grandTotalMonto += parseFloat(row.monto);
                                // Inserta cada fila con sus datos formateados
                                body.append(
                                    '<tr>' +
                                    '<td>' + row.fecha_compra + '</td>' +
                                    '<td>' + row.vehiculo.replace('Vendido', '').trim() + '</td>' +
                                    '<td>' + row.proveedor + '</td>' +
                                    '<td>' + row.descripcion + '</td>' +
                                    '<td>' + row.factura + '</td>' +
                                    '<td>' + formattedMonto + '</td>' +
                                    '<td>' + (row.vehiculo.includes('Vendido') ? 'Sí' : 'No') + '</td>' +
                                    '</tr>'
                                );
                                vehiculo = row.vehiculo;
                            });

                            // Formatear los totales
                            var formattedGrandTotalMonto = __currency_trans_from_en(grandTotalMonto,
                                true, true);
                            // Agregar la fila final con los totales
                            body.append(
                                '<tr>' +
                                '<td colspan="5" style="text-align: right;"><strong>Total:</strong></td>' +
                                '<td><strong>' + formattedGrandTotalMonto + '</strong></td>' +
                                '<td colspan="1" style="text-align: right;"></td>' +
                                '</tr>'
                            );

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th>Fecha</th>' +
                                '<th>Vehículo</th>' +
                                '<th>Proveedor</th>' +
                                '<th>Detalle</th>' +
                                '<th>Factura</th>' +
                                '<th>Monto</th>' +
                                '<th>Vendido</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de gastos de vehículos general</h3>' +
                                    '</div>' +
                                    // Sección de información del vehículo
                                    '<div class="text-justify" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">' +
                                    '<p style="margin: 5px 0;"><strong>En este reporte puede detallar los gastos aplicados a distintos vehículos, donde puede observar cada gasto, y la sumatoria de todos estos.</strong></p>' +
                                    '</div>'
                                );

                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('font-size', 'inherit');
                        }
                    }
                ],
                initComplete: function() {
                    $('.dataTables_paginate').css('margin-top', '15px');
                    var api = this.api();
                    var filterableColumns = [1, 2, 5];
                    $('#bills_table thead').append('<tr class="filter-row"></tr>');
                    api.columns().every(function(index) {
                        var column = this;
                        var headerCell = $(column.header());
                        var th = $('<th></th>').appendTo('.filter-row');
                        if (filterableColumns.includes(index)) {
                            var input = $(
                                '<input type="text" class="form-control" placeholder="Buscar ' +
                                headerCell.text() + '" style="width: 100%;" />');

                            input.appendTo(th)
                                .on('keyup change', function() {
                                    if (column.search() !== this.value) {
                                        console.log(this.value);
                                        column.search(this.value).draw();
                                    }
                                });
                        }
                    });
                }
            });

            function groupBillsData() {
                var selected_rows = [];
                var i = 0;
                $('#bills_table tbody tr').each(function() {
                    var row = $(this);
                    var fecha_compra = row.find('td:eq(0)').text();
                    var vehiculo = row.find('td:eq(1)').text();
                    var proveedor = row.find('td:eq(2)').text();
                    var descripcion = row.find('td:eq(3)').text();
                    var monto = parseFloat(row.find('td:eq(4)').text().replace(/[^\d.-]/g, ''));
                    var factura = row.find('td:eq(5)').text();

                    selected_rows[i++] = {
                        fecha_compra: fecha_compra.trim(),
                        vehiculo: vehiculo.trim(),
                        proveedor: proveedor.trim(),
                        descripcion: descripcion.trim(),
                        factura: factura.trim(),
                        monto: monto
                    };
                });

                return selected_rows;
            }
        });
    </script>

@endsection

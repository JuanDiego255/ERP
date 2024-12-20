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
                    <div id="div_date_report_start" class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date_report_start', __('Fecha (Inicial)') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('date_report_start', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'date_report_start',
                                ]) !!}
                            </div>
                        </div>
                        <p class="help-block">Campo no requerido, al no tomarlo en cuenta se mostrarán todos los gastos antiguos
                        </p>
                    </div>
                    <div id="div_date_report_end" class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date_report_end', __('Fecha (Final)') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('date_report_end', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'date_report_end',
                                ]) !!}
                            </div>
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
        <div class="modal fade" id="view_bills_report_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
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
                        data: 'model_name',
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
                dom: '<"text-center"B><"top"p>rtip',
                buttons: [{
                        extend: 'pageLength',
                        text: 'Mostrando 25',
                        titleAttr: 'Mostrar registros'
                    },
                    {
                        extend: 'colvis',
                        text: 'Visibilidad de columna'
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
            $(document).on('click', '#generate_report_bills', function() {

                if ($('#date_report_end').val() == null || $('#date_report_end').val() == "") {
                    swal({
                        title: "Debe seleccionar la fecha final del filtro",
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
                            $('#date_report_end').focus();
                        }
                    });
                    return;
                }
                let url = '/bills/generate-report'; // Actualiza esta ruta
                let dataTable = $('#bills_table').DataTable();

                // Captura filtros aplicados en DataTable
                let tableFilters = {};
                dataTable.columns().every(function() {
                    if (this.search()) {
                        tableFilters[this.index()] = this.search();
                    }
                });
                // Genera los datos combinados de los filtros globales y de columnas
                //Fechas Filtros
                var date_start = $('#date_report_start').val() ? $('#date_report_start').val() : null;
                var date_end = $('#date_report_end').val() ? $('#date_report_end').val() : null;
                let data = {
                    date_start: date_start,
                    status: $('select#expense_payment_status').val(),
                    date_end: date_end,
                    table_filters: tableFilters
                };
                console.log(data);
                // Envía los datos combinados al backend
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'html',
                    data: data,
                    success: function(result) {
                        $('#view_bills_report_modal')
                            .html(result)
                            .modal('show');
                        __currency_convert_recursively($('#view_bills_report_modal'));
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al generar el reporte:', error);
                    }
                });
            });
        });

        function printThis() {
            $("#billsModalReport").printThis({
                importCSS: true, // Importa los estilos de la página
                importStyle: true, // Importa las hojas de estilo
                loadCSS: "/public/css/print.css", // Ruta a una hoja de estilo específica para impresión
                pageTitle: false, // Título de la página
                removeInline: false,
                printDelay: 500, // Tiempo de espera antes de imprimir
                header: false, // Opcional: cabecera en cada página
                footer: null // Opcional: pie de página
            });
        }
    </script>

@endsection

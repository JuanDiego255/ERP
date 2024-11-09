@extends('layouts.app')
@section('title', __('Auditorias'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Auditorias de los módulos Cuentas Por Pagar y Gastos')
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="print_section">
            <h2>{{ session()->get('business.name') }} - @lang('report.profit_loss')</h2>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @component('components.filters', ['title' => __('report.filters')])
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('expense_payment_status', __('Tipo') . ':') !!}
                                {!! Form::select('expense_payment_status', ['2' => __('Gastos'), '1' => __('CXP')], null, [
                                    'class' => 'form-control select2',
                                    'style' => 'width:100%',
                                    'id' => 'expense_payment_status',
                                ]) !!}
                            </div>
                        </div>
                    @endcomponent
                </div>
            </div>

            <div class="row no-print">
                
                <div class="col-md-12">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#profit_by_products" data-toggle="tab" aria-expanded="true"><i class="fa fa-cubes"
                                        aria-hidden="true"></i> @lang('Auditorias')</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="profit_by_products">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" id="audits_table">
                                        <thead>
                                            <tr>
                                                <th>@lang('Cambios')</th>
                                                <th>@lang('Ejecutado por')</th>
                                                <th>@lang('Fecha de ejecución')</th>
                                                <th>@lang('Transacción')</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
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
            var selectElement = document.getElementById('expense_payment_status');
            var selectedValue = selectElement.value;
            var typeSelected = selectedValue == 2 ? "Gastos" : "Cuentas Por Pagar"
            $('select#expense_payment_status')
                .on(
                    'change',
                    function() {
                        audits_table.ajax.reload();
                    }
                );

            var audits_table = $('#audits_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/audits',
                    data: function(d) {
                        d.payment_status = $('select#expense_payment_status').val();
                    },
                },
                columnDefs: [{
                    "targets": [2],
                    "orderable": false,
                    "searchable": false
                }],
                "columns": [{
                        "data": "change"
                    },
                    {
                        data: 'updated_by_name',
                        name: 'usr.first_name'
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at'
                    },
                    {
                        data: 'type_transaction',
                        name: 'type_transaction'
                    }
                ],
                buttons: [{
                        extend: 'pageLength',
                        text: 'Mostrando 25',
                        titleAttr: 'Mostrar registros'
                    },
                    {
                        extend: 'print',
                        text: 'Reporte Auditorias',
                        customize: function(win) {
                            $(win.document.body).find('h1').remove();
                            $(win.document.body).find('div.printHeader').remove();
                            var body = $(win.document.body).find('table tbody');

                            var groupedData = groupAudits();
                            // Insertar las filas agrupadas en el reporte
                            groupedData.forEach(function(row) {
                                body.append(
                                    '<tr>' +

                                    '<td>' + row.change + '</td>' +
                                    '<td>' + row.update_by + '</td>' +
                                    '<td>' + row.fecha + '</td>' +
                                    '<td>' + row.type_transaction + '</td>' +
                                    '</tr>'
                                );
                            });

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th>Cambios</th>' +
                                '<th>Actualizado por</th>' +
                                '<th>Fecha de modificación</th>' + 
                                '<th>Transacción</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de auditoria: ' +
                                    typeSelected + '</h3>' +
                                    '</div>' +
                                    // Sección de información del vehículo
                                    '<div class="text-justify" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">' +
                                    '<p style="margin: 5px 0;"><strong>En este reporte puede detallar los cambios realizados por los empleados en el módulo de gastos y CXP.</strong></p>' +
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
                    var filterableColumns = [1];
                    $('#audits_table thead').append('<tr class="filter-row"></tr>');
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

            function groupAudits() {
                var selected_rows = [];
                var i = 0;
                $('#bills_table tbody tr').each(function() {
                    var row = $(this);
                    var change = row.find('td:eq(0)').text();
                    var update_by = row.find('td:eq(1)').text();
                    var fecha = row.find('td:eq(2)').text();
                    var type_transaction = row.find('td:eq(3)').text();

                    selected_rows[i++] = {
                        change: change.trim(),
                        update_by: update_by.trim(),
                        fecha: fecha.trim(),
                        type_transaction: type_transaction.trim()
                    };
                });

                return selected_rows;
            }
        });
    </script>

@endsection

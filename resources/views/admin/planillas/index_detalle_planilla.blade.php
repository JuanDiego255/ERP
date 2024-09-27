@extends('layouts.app')
@section('title', __('Planilla Generada'))

@section('content')

    <!-- Content Header (Page header) -->
    <input type="hidden" id="planilla_id" value="{{ $id }}">
    <input type="hidden" id="canUpdate" value="{{ $canUpdate }}">
    <input type="hidden" id="aprobada" value="{{ $planilla->aprobada }}">
    <input type="hidden" id="fecha_desde" value="{{ $planilla->fecha_desde }}">
    <input type="hidden" id="fecha_hasta" value="{{ $planilla->fecha_hasta }}">
    <section class="content-header">
        <h1>@lang('Planilla')
            <small>@lang('generada del: '){{ $planilla->fecha_desde }} al {{ $planilla->fecha_hasta }}</small>
        </h1>
        <!-- <ol class="breadcrumb">
                                                                                                                                                                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                                                                                                                                                        <li class="active">Here</li>
                                                                                                                                                                                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', [
            'class' => 'box-primary',
            'title' => __(
                'Todos los empleados (Al realizar cambios en los rubros no es necesario agregar los separador de miles, el sistema los detecta automaticamente, al usar comas o puntos no se actualiza el campo)'),
        ])
            @can('user.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="planillas">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('Linea')</th>
                                <th>@lang('Emp. ID')</th>
                                <th>@lang('Empleado')</th>
                                <th>@lang('Salario base')</th>
                                <th>@lang('Bonificación')</th>
                                {{-- <th>@lang('Comisiones')</th> --}}
                                <th>@lang('Hora Extra. Emp')</th>
                                <th>@lang('Cant. Hora Extra')</th>
                                <th>@lang('Monto Hora Extra')</th>
                                {{--  <th>@lang('Adelantos')</th> --}}
                                <th>@lang('Prestamos')</th>
                                {{-- <th>@lang('Deudas')</th>
                                <th>@lang('Rebajados')</th> --}}
                                <th>@lang('C.C.S.S')</th>
                                <th>@lang('Calcular Aguinaldo')</th>
                                <th>@lang('Aguinaldo')</th>
                                <th>@lang('Total')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="12"><strong>@lang('sale.total'):</strong></td>
                                <td id="footer_payment_status_count"></td>
                                <td><span class="display_currency" id="total" data-currency_symbol ="true"></span></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcan
        @endcomponent
        {!! Form::open([
            'url' => action('PlanillaController@updateApprove', ['id' => $id]),
            'method' => 'post',
            'id' => 'planilla_update_approve_form',
        ]) !!}
        @can('planilla.update')
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">
                        @if ($planilla->aprobada == 0)
                            @lang('Aprobar Planilla')
                        @else
                            @lang('Desaprobar Planilla')
                        @endif
                    </button>
                </div>
            </div>
        @endcan
        {!! Form::close() !!}

        <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
    <div class="modal fade" id="view_product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script type="text/javascript">
        //Roles table
        var planilla_id = $('#planilla_id').val();
        var canUpdate = $('#canUpdate').val();
        var aprobada = $('#aprobada').val();
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        $(document).ready(function() {
            var users_table = $('#planillas').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/planilla-detalle-index/' + planilla_id,
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        "data": "action"
                    },
                    {
                        "data": "id"
                    },
                    {
                        "data": "employee_id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "salario_base"
                    },
                    {
                        "data": "bonificacion"
                    },
                    // { "data": "comisiones" },
                    {
                        "data": "hora_extra"
                    },
                    {
                        "data": "cant_hora_extra"
                    },
                    {
                        "data": "monto_hora_extra"
                    },
                    // { "data": "adelantos" },
                    {
                        "data": "prestamos"
                    },
                    // { "data": "deudas" },
                    // { "data": "rebajados" },
                    {
                        "data": "total_ccss"
                    },
                    {
                        "data": "calc_aguinaldo"
                    },
                    {
                        "data": "aguinaldo"
                    },
                    {
                        "data": "total"
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    var total = sum_table_col($('#planillas'), 'final-total');
                    updatePlanillaTotal();
                },
                dom: '<"text-center"B><"top"p>frtip',
                initComplete: function() {
                    $('.dataTables_paginate').css('margin-top', '15px');
                },
                buttons: [{
                        extend: 'pageLength',
                        text: 'Mostrando 25',
                        titleAttr: 'Mostrar registros'
                    },
                    {
                        extend: 'colvis',
                        text: 'Visibilidad de columna'
                    },
                    {
                        text: 'Enviar Comprobantes',
                        action: function(e, dt, node, config) {
                            var data = $(this).serialize();
                            $.ajax({
                                method: 'get',
                                url: '/planilla-send-payments/' + planilla_id,
                                dataType: 'json',
                                data: data,
                                success: function(result) {
                                    if (result.success === true) {
                                        toastr.success(result.msg);
                                    } else {
                                        toastr.error(result.msg);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    toastr.error(
                                        'Ocurrió un error al enviar los comprobantes.'
                                    );
                                }
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Reporte Planilla',
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
                                groupPlanillaData(); // Ajusta la función si es necesario
                            var grandTotal = 0; // Para almacenar el total general de salarios

                            body.empty();

                            // Insertar las filas agrupadas en el reporte
                            groupedData.forEach(function(row) {
                                // Calcula totales por fila si es necesario
                                grandTotal += row.total; // Sumar al total general

                                // Formateo de los valores para la tabla usando la función de formato
                                var formattedSalarioBase = __currency_trans_from_en(row
                                    .salario_base, true, true);
                                var formattedBonificacion = __currency_trans_from_en(row
                                    .bonificacion, true, true);
                                var formattedHoraExtra = __currency_trans_from_en(row
                                    .monto_hora_extra, true, true);
                                var formattedPrestamos = __currency_trans_from_en(row
                                    .prestamos, true, true);
                                var formattedTotal = formatCurrency(row.total);
                                var formattedCCSS = __currency_trans_from_en(row.total_ccss,
                                    true, true);

                                // Inserta cada fila con sus datos formateados
                                body.append(
                                    '<tr>' +
                                    '<td>' + row.name + '</td>' +
                                    '<td>' + formattedSalarioBase + '</td>' +
                                    '<td>' + formattedBonificacion + '</td>' +
                                    '<td>' + formattedHoraExtra + '</td>' +
                                    '<td>' + formattedPrestamos + '</td>' +
                                    '<td>' + formattedCCSS + '</td>' +
                                    '<td>' + formattedTotal + '</td>' +
                                    '</tr>'
                                );
                            });

                            // Formatear y agregar el total general al final
                            var formattedGrandTotal = formatCurrency(grandTotal);

                            $(win.document.body).append(
                                '<div style="text-align: right; margin-top: 20px; font-weight: bold;">' +
                                'Monto Total de Planilla: ' + formattedGrandTotal +
                                '</div>'
                            );

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th>Nombre</th>' +
                                '<th>Salario Base</th>' +
                                '<th>Bonificación</th>' +
                                '<th>Monto Hora</th>' +
                                '<th>Préstamos</th>' +
                                '<th>C.C.S.S</th>' +
                                '<th>Total</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de Planilla Autos Grecia (S.R.L)</h3>' +
                                    '<p style="margin-top: 5px; text-align:center;">Planilla del: ' +
                                    fecha_desde + ' al ' + fecha_hasta + '</p>' +
                                    '</div>'
                                );

                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('font-size', 'inherit');
                        }
                    }


                ]
            });

            function groupPlanillaData() {
                var selected_rows = [];
                var i = 0;
                // Recorre cada fila de la tabla de planillas
                $('#planillas tbody tr').each(function() {
                    var row = $(this);

                    // Extrae los valores desde los inputs dentro de cada columna correspondiente
                    var employee_id = row.find('td:eq(2)').text(); // ID del empleado (texto)
                    var name = row.find('td:eq(3)').text(); // Nombre del empleado (texto)
                    var salario_base = row.find('td:eq(4) input[type="text"]').val() || '0';
                    var bonificacion = row.find('td:eq(5) input[type="text"]').val() || '0';
                    var hora_extra = parseFloat(row.find('td:eq(6) input').val()) ||
                        0; // Hora extra (input)
                    var cant_hora_extra = parseInt(row.find('td:eq(7) input').val()) ||
                        0; // Cantidad de horas extra (input)
                    var monto_hora_extra = row.find('td:eq(8) input[type="text"]').val() || '0';
                    var prestamos = row.find('td:eq(9) input[type="text"]').val() || '0';
                    var total_ccss = row.find('td:eq(10) input[type="text"]').val() || '0';
                    var total = parseFloat(row.find('td:eq(13)').text().replace(/[^\d.-]/g, ''));

                    // Agrega la fila seleccionada con los datos relevantes al array de filas seleccionadas
                    selected_rows[i++] = {
                        employee_id: employee_id.trim(),
                        name: name.trim(),
                        salario_base: salario_base,
                        bonificacion: bonificacion,
                        hora_extra: hora_extra,
                        cant_hora_extra: cant_hora_extra,
                        monto_hora_extra: monto_hora_extra,
                        prestamos: prestamos,
                        total_ccss: total_ccss,
                        total: total
                    };
                });

                return selected_rows;
            }
            $('#planillas').on('focus', 'input[type="text"]', function() {
                var input = $(this);
                var valorSinFormato = input.val().replace(/,/g, '').replace(/\.\d+$/,
                    ''); // Elimina todo lo que sigue al punto
                input.data('initialValue', valorSinFormato);
            });
            $('#planillas').on('blur', 'input[type="text"]', function() {
                var input = $(this);
                var value = input.val();
                var initialValue = input.data('initialValue'); // Recupera el valor inicial
                var column_name = input.attr('name');
                var row_id = input.closest('tr').find('td').eq(1).text();
                var employee_id = input.closest('tr').find('td').eq(2).text();

                // Solo procede si el valor cambió
                if (value != initialValue && value >= 0 && canUpdate && aprobada != 1) {
                    // Deshabilita todos los campos de entrada mientras se procesa la solicitud
                    $('input[type="text"]').prop('disabled', true);

                    $.ajax({
                        url: '/planilla-detalle-update/' + row_id,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            column: column_name,
                            value: value
                        },
                        success: function(response) {
                            if (response.success) {
                                users_table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            // Manejo de error
                        },
                        complete: function() {
                            // Rehabilita los campos de entrada después de que la solicitud se complete
                            $('input[type="number"]').prop('disabled', false);
                        }
                    });
                }
            });

            function updatePlanillaTotal() {
                var total = sum_table_col($('#planillas'), 'final-total'); // Reutilizando tu función de suma
                $('#total').text(total);
                __currency_convert_recursively($('#planillas')); // Conversión de moneda si aplica
            }
            $(document).on('click', 'button.calc_aguinaldo_button', function() {
                swal({
                    title: LANG.sure,
                    text: 'Se calculará el aguinaldo, desea continuar?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');
                        var data = $(this).serialize();
                        $.ajax({
                            method: "POST",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    users_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
        $(document).on('click', 'button.sendPaymentDetail', function() {
            var data = $(this).serialize();
            var detalle_id = $('#detalle_id').val();

            $.ajax({
                method: 'get',
                url: '/planilla-send-payments-id/' + detalle_id,
                dataType: 'json',
                data: data,
                success: function(result) {
                    if (result.success === true) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });
        $(document).on('click', 'a.view-planilla', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function(result) {
                    console.log(result)
                    $('#view_product_modal')
                        .html(result)
                        .modal('show');
                    __currency_convert_recursively($('#view_product_modal'));
                },
            });
        });
    </script>

@endsection

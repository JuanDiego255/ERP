@extends('layouts.app')
@section('title', __('Planilla Generada'))

@section('content')

    <!-- Content Header (Page header) -->
    <input type="hidden" id="planilla_id" value="{{ $id }}">
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
        @component('components.widget', ['class' => 'box-primary', 'title' => __('Todos los empleados')])
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
                                <th>@lang('Comisiones')</th>
                                <th>@lang('Hora Extra. Emp')</th>
                                <th>@lang('Cant. Hora Extra')</th>
                                <th>@lang('Monto Hora Extra')</th>
                                <th>@lang('Adelantos')</th>
                                <th>@lang('Prestamos')</th>
                                <th>@lang('Deudas')</th>
                                <th>@lang('Rebajados')</th>
                                <th>@lang('C.C.S.S')</th>
                                <th>@lang('Total')</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="14"><strong>@lang('sale.total'):</strong></td>
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
                    {
                        "data": "comisiones"
                    },
                    {
                        "data": "hora_extra"
                    },
                    {
                        "data": "cant_hora_extra"
                    },
                    {
                        "data": "monto_hora_extra"
                    },
                    {
                        "data": "adelantos"
                    },
                    {
                        "data": "prestamos"
                    },
                    {
                        "data": "deudas"
                    },
                    {
                        "data": "rebajados"
                    },
                    {
                        "data": "total_ccss"
                    },
                    {
                        "data": "total"
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    // Sum and display total
                    var total = sum_table_col($('#planillas'), 'final-total');
                    updatePlanillaTotal();
                }
            });

            $('#planillas').on('blur', 'input[type="number"]', function() {
                var input = $(this);
                var value = input.val();
                var column_name = input.attr('name');
                var row_id = input.closest('tr').find('td').eq(1).text();
                var employee_id = input.closest('tr').find('td').eq(2).text();

                if (value >= 0) {
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
                            // Handle error
                        }
                    });
                }
            });

            function updatePlanillaTotal() {
                var total = sum_table_col($('#planillas'), 'final-total'); // Reutilizando tu función de suma
                $('#total').text(total);
                __currency_convert_recursively($('#planillas')); // Conversión de moneda si aplica
            }
        });

        $(document).on('click', 'a.view-planilla', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function(result) {
                    $('#view_product_modal')
                        .html(result)
                        .modal('show');
                    __currency_convert_recursively($('#view_product_modal'));
                },
            });
        });
    </script>

@endsection

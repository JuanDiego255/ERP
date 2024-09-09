@extends('layouts.app')
@section('title', __('Planillas'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Planilla')
            <small>@lang('Administrar planillas')</small>
        </h1>
        <!-- <ol class="breadcrumb">
                            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                            <li class="active">Here</li>
                        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('Todas las planilla')])
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{ action('PlanillaController@create') }}">
                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
            @endslot
            @can('user.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="planillas">
                        <thead>
                            <tr>
                                <th>@lang('No. Planilla')</th>
                                <th>@lang('Descripcion')</th>
                                <th>@lang('Fecha inicio')</th>
                                <th>@lang('Fecha fin')</th>
                                <th>@lang('Tipo')</th>
                                <th>@lang('Estado')</th>
                                <th>@lang('Generada')</th>
                                <th>@lang('Aprobada')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcan
        @endcomponent

        <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        //Roles table
        $(document).ready(function() {
            var users_table = $('#planillas').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/planilla-index',
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        "data": "planilla_id"
                    },
                    {
                        "data": "descripcion"
                    },
                    {
                        "data": "fecha_desde"
                    },
                    {
                        "data": "fecha_hasta"
                    },
                    {
                        "data": "tipo"
                    },
                    {
                        "data": "estado"
                    },
                    {
                        "data": "generada"
                    },
                    {
                        "data": "aprobada"
                    },
                    {
                        "data": "action"
                    }
                ]
            });

            $(document).on('click', 'button.delete_planilla_button', function() {
                swal({
                    title: LANG.sure,
                    text: 'Esta planilla será eliminada, desea continuar?',
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
                                    users_table.ajax.reload();
                                } else {
                                    console.log(result)
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'button.generar_planilla_detalle', function() {
                swal({
                    title: "Se generará la planilla",
                    text: '¿desea continuar?',
                    icon: "info",
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
                                    console.log(result.planilla_id);
                                    window.location.href = "/planilla-detalle-index/" +
                                        result.planilla_id;
                                } else {
                                    console.log(result)
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

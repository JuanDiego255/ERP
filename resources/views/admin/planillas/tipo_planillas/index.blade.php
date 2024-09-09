@extends('layouts.app')
@section('title', __('Tipo planillas'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Tipos de planilla')
            <small>@lang('Administrar tipos de planilla')</small>
        </h1>
        <!-- <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                        <li class="active">Here</li>
                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('Todos los tipos de planilla')])
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" href="{{ action('PlanillaController@createTipoPlanilla') }}">
                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                </div>
            @endslot
            @can('user.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tipo_planilla_table">
                        <thead>
                            <tr>
                                <th>@lang('Tipo')</th>
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
            var users_table = $('#tipo_planilla_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/tipo-planilla-index',
                columnDefs: [{
                    "targets": [1],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [{
                        "data": "tipo"
                    },
                    {
                        "data": "action"
                    }
                ]
            });

            $(document).on('click', 'button.delete_tipo_planilla_button', function() {
                swal({
                    title: LANG.sure,
                    text: 'Este tipo de planilla será eliminado, desea continuar?',
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
        });
    </script>
@endsection

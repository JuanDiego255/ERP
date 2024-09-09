@extends('layouts.app')
@section('title', __('Gastos por vehículo'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Gastos')
            <small>@lang('Administrar los gastos por vehículos')</small>
        </h1>
        <!-- <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                        <li class="active">Here</li>
                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="vehicle_id" value="{{ $id }}">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <h3 class="profile-username text-center">
                            Gastos totales
                        </h3>

                        <p class="text-muted text-center" title="@lang('user.role')">

                        </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>@lang('Cantidad de gastos:')</b>
                                <a class="pull-right">{{$cant_gastos}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('Total:')</b>
                                <a class="pull-right">₡{{number_format($totalMonto)}}</a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-9">

                @component('components.widget', ['class' => 'box-primary', 'title' => __('Todos los gastos')])
                    @can('product.create')
                        @slot('tool')
                            <div class="box-tools">
                                <a class="btn btn-block btn-primary" href="{{ action('Admin\BillVehicleController@create', [$id]) }}">
                                    <i class="fa fa-plus"></i> @lang('Nuevo gasto')</a>
                            </div>
                        @endslot
                    @endcan
                    @can('product.view')
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="bills_table">
                                <thead>
                                    <tr>
                                        <th>@lang('Fecha compra')</th>
                                        <th>@lang('Vehículo')</th>
                                        <th>@lang('Proveedor')</th>
                                        <th>@lang('Descripción')</th>
                                        <th>@lang('Monto')</th>
                                        <th>@lang('Factura')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    @endcan
                @endcomponent
            </div>
        </div>

        <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        //Bills Vehicles table
        $(document).ready(function() {
            // Obtener el ID del vehículo desde un input oculto o similar
            var vehicle_id = $('#vehicle_id').val();

            // Actualizar la URL de la solicitud AJAX con el ID del vehículo
            var users_table = $('#bills_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/products/bills/' + vehicle_id, // Incluyendo el ID del vehículo en la URL
                    type: 'GET',
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
                        "data": "name"
                    },
                    {
                        "data": "prov_name"
                    },
                    {
                        "data": "descripcion"
                    },
                    {
                        "data": "monto"
                    },
                    {
                        "data": "factura"
                    },
                    {
                        "data": "action"
                    }
                ]
            });

            // Manejo del botón de eliminación
            $(document).on('click', 'button.delete_user_button', function() {
                swal({
                    title: LANG.sure,
                    text: 'Este gasto será eliminado, desea continuar?',
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

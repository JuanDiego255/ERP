@extends('layouts.app')
@section('title', __('Plan de ventas'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Plan de ventas')
        </h1>
        <!-- <ol class="breadcrumb">
                                        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                        <li class="active">Here</li>
                                    </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('Todos los planes de ventas')])
            @can('plan_venta.create')
                @slot('tool')
                    <div class="box-tools">
                        <a class="btn btn-block btn-primary" href="{{ action('PlanVentaController@create') }}">
                            <i class="fa fa-plus"></i> @lang('messages.add')</a>
                    </div>
                @endslot
            @endcan
            @can('user.view')
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="plan_ventas">
                        <thead>
                            <tr>
                                <th>@lang('No. Plan')</th>
                                <th>@lang('Cliente')</th>
                                <th>@lang('Fiador')</th>
                                <th>@lang('Vehículo vendido')</th>
                                <th>@lang('Modelo')</th>
                                <th>@lang('Fecha plan (yyyy-MM-dd)')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            @endcan
        @endcomponent

        <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

        <div class="modal fade" id="view_plan" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    <script type="text/javascript">
        //Roles table
        $(document).ready(function() {
            var users_table = $('#plan_ventas').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/plan-ventas-index',
                columnDefs: [{
                    "targets": [4],
                    "orderable": false,
                    "searchable": false
                }],
                dom: '<"text-center"B><"top"p>rtip',
                columns: [{
                        "data": "numero"
                    },
                    {
                        data: 'name',
                        name: 'cli.name'
                    },
                    {
                        data: 'fiador_name',
                        name: 'fdr.name'
                    },
                    {
                        data: 'vehiculo',
                        name: 'products.name'
                    },
                    {
                        data: 'model',
                        name: 'products.model'
                    },
                    {
                        "data": "fecha_plan"
                    },
                    {
                        "data": "action"
                    }
                ],
                initComplete: function() {
                    $('.dataTables_paginate').css('margin-top', '15px');
                    var api = this.api();
                    var filterableColumns = [0, 1, 2,
                    3,4,5];
                    $('#plan_ventas thead').append('<tr class="filter-row"></tr>');
                    api.columns().every(function(index) {
                        var column = this;
                        var headerCell = $(column.header());
                        var th = $('<th></th>').appendTo('.filter-row');

                        // Verifica si el índice de la columna está en el arreglo de columnas filtrables
                        if (filterableColumns.includes(index)) {
                            // Crear el input de búsqueda
                            var input = $(
                                '<input type="text" class="form-control" placeholder="Buscar ' +
                                headerCell.text() + '" style="width: 100%;" />');

                            // Verificar si la columna tiene data: 'contact'
                            if (column.dataSrc() === 'contact') {
                                input.attr('name', 'contact_search');
                                input.attr('id', 'contact_search');
                            }

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

            $(document).on('click', 'button.delete_plan_button', function() {
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
        });       
        $(document).on('click', 'a.view-plan', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                dataType: 'html',
                success: function(result) {
                    console.log(result)
                    $('#view_plan')
                        .html(result)
                        .modal('show');
                    __currency_convert_recursively($('#view_plan'));
                },
            });
        }); 
    </script>
@endsection

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
                                <a class="pull-right">{{ $cant_gastos }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('Total:')</b>
                                <a class="pull-right">₡{{ number_format($totalMonto) }}</a>
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
                                        <th>@lang('messages.action')</th>
                                        <th>@lang('Fecha compra')</th>
                                        <th>@lang('Vehículo')</th>
                                        <th>@lang('Proveedor')</th>
                                        <th>@lang('Descripción')</th>
                                        <th>@lang('Monto')</th>
                                        <th>@lang('Factura')</th>
                                        <th>@lang('Creado por')</th>
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
                "columns": [
                    {
                        "data": "action"
                    },
                    {
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
                                    '<td>' + row.proveedor + '</td>' +
                                    '<td>' + row.descripcion + '</td>' +
                                    '<td>' + row.factura + '</td>' +
                                    '<td>' + formattedMonto + '</td>' +
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
                                '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>' +
                                '<td><strong>' + formattedGrandTotalMonto + '</strong></td>' +
                                '</tr>'
                            );

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th>Fecha</th>' +
                                '<th>Proveedor</th>' +
                                '<th>Detalle</th>' +
                                '<th>Factura</th>' +
                                '<th>Monto</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de gastos sobre el vehículo: ' +
                                    vehiculo + '</h3>' +
                                    '</div>' +
                                    // Sección de información del vehículo
                                    '<div class="text-justify" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">' +
                                    '<p style="margin: 5px 0;"><strong>En este reporte puede detallar los gastos aplicados a un vehículo en específico, donde puede observar cada gasto, y la sumatoria de todos estos.</strong></p>' +
                                    '</div>'
                                );

                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('font-size', 'inherit');
                        }
                    }
                ]
            });

            function groupBillsData() {
                var selected_rows = [];
                var i = 0;
                $('#bills_table tbody tr').each(function() {
                    var row = $(this);
                    var fecha_compra = row.find('td:eq(1)').text();
                    var vehiculo = row.find('td:eq(2)').text();
                    var proveedor = row.find('td:eq(3)').text();
                    var descripcion = row.find('td:eq(4)').text();
                    var monto = parseFloat(row.find('td:eq(5)').text().replace(/[^\d.-]/g, ''));
                    var factura = row.find('td:eq(6)').text();

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

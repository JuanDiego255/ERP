@extends('layouts.app')
@section('title', 'Cuentas por Cobrar - Financiamiento')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <input type="hidden" id="revenue_id" value="{{ $id }}">
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', [
                    'title' => __(
                        'Información del vehículo (Si deseas cambiar el vehículo debes modificar el plan de ventas ligado a esta cuenta)'),
                ])
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehículo', __('Vehículo')) !!}
                            {!! Form::text('vehículo', $item->veh_venta, [
                                'class' => 'form-control',
                                'id' => 'vehiculo',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('modelo', __('Modelo')) !!}
                            {!! Form::text('modelo', $item->modelo, [
                                'class' => 'form-control',
                                'id' => 'modelo',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('placa', __('Placa')) !!}
                            {!! Form::text('placa', $item->placa, [
                                'class' => 'form-control',
                                'id' => 'placa',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
                @component('components.widget', [
                    'title' => __(
                        'Información Cuenta Por Cobrar (Para modificar la información de la cuenta debes hacerlo desde el plan de ventas)'),
                ])
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('aprobado', __('Aprobado Por')) !!}
                            {!! Form::text('aprobado', 'Dubilia Dobles', [
                                'class' => 'form-control',
                                'id' => 'aprobado',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('fecha_inicio', __('Fecha')) !!}
                            {!! Form::text('fecha_inicio', $item->created_at, [
                                'class' => 'form-control',
                                'id' => 'fecha_inicio',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('tipo_prestamo', 'Tipo prestamo') !!}
                        {!! Form::select('tipo_prestamo', ['1' => 'Cuota Nivelada', '2' => 'Intereses'], $item->tipo_prestamo, [
                            'class' => 'form-control',
                            'id' => 'tipo_prestamo',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="form-group col-sm-3">
                        {!! Form::label('moneda', 'Moneda') !!}
                        {!! Form::select('moneda', ['1' => 'Colones', '2' => 'Dolares'], $item->moneda, [
                            'class' => 'form-control',
                            'id' => 'moneda',
                            'readonly',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('tasa', __('Tasa')) !!}
                            {!! Form::text('tasa', $item->tasa, [
                                'class' => 'form-control',
                                'id' => 'tasa',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Plazo')) !!}
                            {!! Form::text('plazo', $item->plazo, [
                                'class' => 'form-control',
                                'id' => 'plazo',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('cuota', __('Cuota Mensual')) !!}
                            {!! Form::text('cuota', number_format($item->cuota, 2, '.', ','), [
                                'class' => 'form-control',
                                'id' => 'cuota',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('valor_total', __('Monto total')) !!}
                            {!! Form::text('valor_total', number_format($item->valor_total, 2, '.', ','), [
                                'class' => 'form-control',
                                'id' => 'valor_total',
                                'readonly',
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('detalle', __('Detalle') . '') !!}
                        {!! Form::textarea('detalle', $item->detalle, ['class' => 'form-control', 'required', 'readonly', 'rows' => 3]) !!}
                    </div>
                @endcomponent
                @component('components.widget', [
                    'title' => __(
                        'Información del cliente (Si deseas cambiar la información del cliente debes hacerlo desde el módulo de clientes)'),
                ])
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('name', __('Nombre')) !!}
                                {!! Form::text('name', $item->name, [
                                    'class' => 'form-control',
                                    'id' => 'name',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('identificacion', __('Identificación')) !!}
                                {!! Form::text('identificacion', $item->identificacion, [
                                    'class' => 'form-control',
                                    'id' => 'identificacion',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('tipo_ident', __('Tipo Identifiación')) !!}
                                {!! Form::text('tipo_ident', $item->tipo_identificacion == 'f' ? 'Física' : 'Jurídica', [
                                    'class' => 'form-control',
                                    'id' => 'tipo_ident',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('telephone', __('Teléfono')) !!}
                                {!! Form::text('telephone', $item->telephone, [
                                    'class' => 'form-control',
                                    'id' => 'telephone',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('celular', __('Celular')) !!}
                                {!! Form::text('celular', $item->celular, [
                                    'class' => 'form-control',
                                    'id' => 'celular',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('email', __('Correo electrónico')) !!}
                                {!! Form::text('email', $item->email, [
                                    'class' => 'form-control',
                                    'id' => 'email',
                                    'readonly',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::label('direccion', __('Dirección Exacta') . '') !!}
                            {!! Form::textarea('direccion', $item->direccion, [
                                'class' => 'form-control',
                                'required',
                                'readonly',
                                'rows' => 3,
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
                <div class="row">
                    <div class="col-md-12">
                        @component('components.filters', ['title' => __('report.filters')])
                            <div class="col-md-4">
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
                        @endcomponent
                    </div>
                </div>
                @component('components.widget', [
                    'title' => __('Gestión de pagos en esta cuenta'),
                ])
                    @slot('tool')
                        <div class="box-tools">
                            <a class="btn btn-block btn-primary btn_add_row"
                                href="{{ action('RevenueController@storeRow', [$id]) }}">
                                <i class="fa fa-plus"></i> @lang('messages.add')</a>
                        </div>
                    @endslot
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="payments">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('ID')</th>
                                    <th>@lang('Fecha pago')</th>
                                    <th>@lang('Fecha interés')</th>
                                    <th>@lang('Referencia')</th>
                                    <th>@lang('Detalle')</th>
                                    <th>@lang('Monto Pagado')</th>
                                    <th>@lang('Amortiza')</th>
                                    <th>@lang('Interés Corriente')</th>
                                    <th>@lang('Calcular')</th>
                                    <th>@lang('Saldo')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>

@endsection
<div class="modal fade" id="view_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            var revenue_id = $('#revenue_id').val();
            var dates = $('#expense_date_range').val();
            var name = $('#name').val();
            var vehiculo = $('#vehiculo').val();
            var placa = $('#placa').val();
            var modelo = $('#modelo').val();
            var payment_table = $('#payments').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/payments/revenues/' + revenue_id,
                    data: function(d) {
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
                columns: [{
                        "data": "action"
                    },
                    {
                        "data": "id"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "fecha_interes"
                    },
                    {
                        "data": "referencia"
                    },
                    {
                        "data": "detalle"
                    },
                    {
                        "data": "paga"
                    },
                    {
                        "data": "amortiza"
                    },
                    {
                        "data": "interes_c"
                    },
                    {
                        "data": "calcular"
                    },
                    {
                        "data": "monto_general"
                    }
                ],
                fnDrawCallback: function(oSettings) {
                    toggleInputs();
                },
                dom: '<"text-center"B>frtip',
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

                            // Llama a la función que agrupa los datos de la planilla
                            var groupedData =
                        groupPaymentData(); // Ajusta la función si es necesario
                            var grandTotalPaga = 0;
                            var grandTotalAmortiza = 0;
                            var grandTotalInteres = 0;

                            body.empty();

                            // Insertar las filas agrupadas en el reporte
                            groupedData.forEach(function(row) {
                                var formattedPaga = __currency_trans_from_en(row.paga, true,
                                    true);
                                var formattedAmortiza = __currency_trans_from_en(row
                                    .amortiza, true, true);
                                var formattedInteres = __currency_trans_from_en(row
                                    .interes_c, true, true);
                                var formattedSaldo = __currency_trans_from_en(row.saldo,
                                    true, true);

                                // Acumular los totales
                                grandTotalPaga += parseFloat(row.paga);
                                grandTotalAmortiza += parseFloat(row.amortiza);
                                grandTotalInteres += parseFloat(row.interes_c);

                                // Inserta cada fila con sus datos formateados
                                body.append(
                                    '<tr>' +
                                    '<td>' + row.fecha_pago + '</td>' +
                                    '<td>' + row.fecha_interes + '</td>' +
                                    '<td>' + row.referencia + '</td>' +
                                    '<td>' + row.detalle + '</td>' +
                                    '<td>' + formattedPaga + '</td>' +
                                    '<td>' + formattedAmortiza + '</td>' +
                                    '<td>' + formattedInteres + '</td>' +
                                    '<td>' + formattedSaldo + '</td>' +
                                    '</tr>'
                                );
                            });

                            // Formatear los totales
                            var formattedGrandTotalPaga = __currency_trans_from_en(grandTotalPaga
                                .toFixed(2), true, true);
                            var formattedGrandTotalAmortiza = __currency_trans_from_en(
                                grandTotalAmortiza.toFixed(2), true, true);
                            var formattedGrandTotalInteres = __currency_trans_from_en(
                                grandTotalInteres.toFixed(2), true, true);

                            // Agregar la fila final con los totales
                            body.append(
                                '<tr>' +
                                '<td colspan="4" style="text-align: right;"><strong>Total:</strong></td>' +
                                '<td><strong>' + formattedGrandTotalPaga + '</strong></td>' +
                                '<td><strong>' + formattedGrandTotalAmortiza +
                                '</strong></td>' +
                                '<td><strong>' + formattedGrandTotalInteres + '</strong></td>' +
                                '<td></td>' + // Columna de saldo vacía en la fila de totales
                                '</tr>'
                            );

                            // Ajustar encabezados de la tabla para la planilla
                            $(win.document.body).find('table thead tr').html(
                                '<th>Fecha de pago</th>' +
                                '<th>Fecha de interés</th>' +
                                '<th>No. Ref</th>' +
                                '<th>Detalle</th>' +
                                '<th>Monto pagado</th>' +
                                '<th>Amortiza</th>' +
                                '<th>Interes Corriente</th>' +
                                '<th>Saldo</th>'
                            );

                            // Personalizar el estilo del documento
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .prepend(
                                    '<img src="' + window.location.origin +
                                    '/images/logo_ag.png" style="margin-bottom: 5px;" />' +
                                    '<div style="text-align: center; margin-bottom: 10px;">' +
                                    '<h3 style="margin: 0;">Reporte de Estado de cuenta de: ' +
                                    name + '</h3>' +
                                    '<p style="margin-top: 5px; text-align:center;">Generado al: ' +
                                    dates + '</p>' +
                                    '</div>' +
                                    // Sección de información del vehículo
                                    '<div class="text-center" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; background-color: #f9f9f9;">' +
                                    '<h4 style="text-align: center; margin: 0;">Información del Vehículo</h4>' +
                                    '<p style="margin: 5px 0;"><strong>Marca:</strong> ' +
                                    vehiculo + '</p>' +
                                    '<p style="margin: 5px 0;"><strong>Modelo:</strong> ' + modelo +
                                    '</p>' +
                                    '<p style="margin: 5px 0;"><strong>Placa:</strong> ' + placa +
                                    '</p>' +
                                    '</div>'
                                );

                            $(win.document.body).find('table')
                                .addClass('display')
                                .css('font-size', 'inherit');
                        }
                    }

                ]
            });
            if ($('#expense_date_range').length == 1) {
                $('#expense_date_range').daterangepicker(
                    dateRangeSettings,
                    function(start, end) {
                        $('#expense_date_range').val(
                            start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                        );
                        payment_table.ajax.reload();
                    }
                );

                $('#expense_date_range').on('cancel.daterangepicker', function(ev, picker) {
                    $('#product_sr_date_filter').val('');
                    payment_table.ajax.reload();
                });
            }

            function groupPaymentData() {
                var selected_rows = [];
                var i = 0;
                $('#payments tbody tr').each(function() {
                    var row = $(this);
                    var fecha_pago = row.find('td:eq(2) input[type="text"]').val() || '';
                    var fecha_interes = row.find('td:eq(3) input[type="text"]').val() || '';
                    var referencia = row.find('td:eq(4) input[type="text"]').val() || '';
                    var detalle = row.find('td:eq(5) input[type="text"]').val() || '';
                    var paga = parseFloat(row.find('td:eq(6) input[type="number"]').val()) || 0;
                    var amortiza = parseFloat(row.find('td:eq(7) input[type="number"]').val()) || 0;
                    var interes_c = parseFloat(row.find('td:eq(8) input[type="number"]').val()) || 0;
                    var saldo = parseFloat(row.find('td:eq(10) input[type="number"]').val()) || 0;

                    selected_rows[i++] = {
                        fecha_pago: fecha_pago.trim(),
                        fecha_interes: fecha_interes.trim(),
                        referencia: referencia.trim(),
                        detalle: detalle.trim(),
                        paga: paga,
                        amortiza: amortiza,
                        interes_c: interes_c,
                        saldo: saldo
                    };
                });

                return selected_rows;
            }
            $('#payments').on('focus', 'input[type="text"], input[type="number"]', function() {
                var input = $(this);
                input.data('initialValue', input.val());
            });
            $('#payments').on('blur', 'input[type="text"], input[type="number"]', function() {
                var input = $(this);
                var value = input.val();
                var initialValue = input.data('initialValue'); // Recupera el valor inicial
                var column_name = input.attr('name');
                var row_id = input.closest('tr').find('td').eq(1).text();
                var totalColumns = input.closest('tr').find('td').length;
                var allRows = input.closest('tbody').find('tr');
                var penultimaFila = allRows.eq(allRows.length - 2);
                var saldo = penultimaFila.find('td').eq(9).find('input').val();
                var inputType = input.attr('type'); // Obtiene el tipo de input   
                // Verificar si es un input de tipo "text" o "number" y realizar las validaciones correspondientes
                var isValid = false;
                if (inputType === 'text') {
                    // Validar que el campo de texto no esté vacío
                    isValid = value.trim() !== '';
                } else if (inputType === 'number') {
                    // Validar que el número sea mayor o igual a 0
                    isValid = value >= 0;
                }

                // Solo procede si el valor cambió, la entrada es válida y se pueden hacer actualizaciones
                if (value != initialValue && isValid && saldo != 0) {
                    // Deshabilita todos los campos de entrada mientras se procesa la solicitud
                    $('input[type="text"], input[type="number"]').prop('disabled', true);

                    $.ajax({
                        url: '/payment-revenue-update/' + row_id + '/' + revenue_id,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            column: column_name,
                            value: value
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                payment_table.ajax.reload();
                            }
                        },
                        error: function(xhr) {
                            // Manejo de error
                        },
                        complete: function() {
                            // Rehabilita los campos de entrada después de que la solicitud se complete
                            $('input[type="text"], input[type="number"]').prop('disabled',
                                false);
                        }
                    });
                }
            });
            $(document).on('click', '.btn_add_row', function(event) {
                event.preventDefault(); // Evita que el enlace siga el href de forma predeterminada

                var href = $(this).attr('href'); // Obtiene el href del botón

                $.ajax({
                    url: href, // Usa el href como la URL para la petición AJAX
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            payment_table.ajax.reload();
                            toggleInputs();
                        }
                    }
                });
            });
            $(document).on('click', 'button.delete_row_button', function() {

                swal({
                    title: LANG.sure,
                    text: 'Esta linea será eliminada, desea continuar?',
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
                                    payment_table.ajax.reload();
                                    toggleInputs();
                                } else {
                                    console.log(result)
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'button.update_row_button', function() {
                var currentRow = $(this).closest('tr');
                var allRows = $(this).closest('tbody').find('tr');
                var penultimaFila = allRows.eq(allRows.length - 2);

                // Obtén los valores de las celdas de la fila actual y de la penúltima fila
                var saldo = penultimaFila.find('td').eq(9).find('input').val();
                var amortiza = currentRow.find('td').eq(6).find('input').val();
                var interes = currentRow.find('td').eq(7).find('input').val();

                swal({
                    title: LANG.sure,
                    text: 'Desea realizar el cálculo para esta linea?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var href = $(this).data('href');

                        // Incluye los valores en el objeto data que se envía en la solicitud
                        var data = {
                            saldo: saldo,
                            amortiza: amortiza,
                            interes: interes,
                            _token: $('meta[name="csrf-token"]').attr(
                                'content')
                        };

                        $.ajax({
                            method: "POST",
                            url: href,
                            dataType: "json",
                            data: data,
                            success: function(result) {
                                if (result.success == true) {
                                    console.log(result);
                                    toastr.success(result.msg);
                                    payment_table.ajax.reload();
                                } else {
                                    console.log(result);
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
            $(document).on('click', 'a.view-payment', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    dataType: 'html',
                    success: function(result) {
                        $('#view_payment_modal')
                            .html(result)
                            .modal('show');
                        __currency_convert_recursively($('#view_payment_modal'));
                    },
                });
            });

            function toggleInputs() {
                // Usar la API de DataTables para obtener las filas visibles
                var allRows = payment_table.rows({
                    'search': 'applied'
                }).nodes(); // Accede a todas las filas visibles

                var totalRows = allRows.length; // Contar el número de filas visibles

                if (totalRows === 0) {
                    console.log("No hay filas en la tabla.");
                    return; // Si no hay filas, salir de la función
                }

                // Iterar sobre las filas visibles
                $(allRows).each(function(index, row) {
                    var inputs = $(row).find(
                        'input[type="number"]'); // Encuentra los inputs en cada fila

                    // Si solo hay una fila, los inputs quedan habilitados (sin readonly)
                    if (totalRows === 1) {
                        inputs.prop('readonly', false); // Elimina readonly si solo hay una fila
                    } else {
                        // Si es la última fila, habilita los inputs (sin readonly), de lo contrario, agrégales readonly
                        if (index === totalRows - 1) {
                            inputs.prop('readonly', false); // Última fila editable
                        } else {
                            inputs.prop('readonly', true); // Otras filas solo de lectura
                        }
                    }
                });
            }
            toggleInputs();
            $(document).on('click', 'button.sendPaymentWhats', function() {
                var data = $(this).serialize();
                var pay_id = $('#payment_id').val();

                $.ajax({
                    method: 'get',
                    url: '/payment-send-whats-id/' + pay_id + '/' + revenue_id,
                    dataType: 'json',
                    data: data,
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            window.open(result.whatsapp_link, '_blank');
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    </script>
@endsection

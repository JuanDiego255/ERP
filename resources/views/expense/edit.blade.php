@extends('layouts.app')
@section('title', 'Editar cuenta por pagar')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Editar cuenta</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('ExpenseController@update', [$expense->id]),
            'method' => 'PUT',
            'id' => 'add_expense_form',
            'files' => true,
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':*') !!}
                            {!! Form::select('location_id', $business_locations, $expense->location_id, [
                                'class' => 'form-control select2',
                                'placeholder' => __('messages.please_select'),
                                'required',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('supplier_id', __('purchase.supplier') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>

                                {!! Form::select(
                                    'contact_id',
                                    [$expense->contact_id => $expense->contact ? $expense->contact->name : ''],
                                    $expense->contact_id,
                                    ['class' => 'form-control', 'placeholder' => __('messages.please_select'), 'required', 'id' => 'supplier_id'],
                                ) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('ref_no', __('Factura') . ':*') !!}
                            {!! Form::text('ref_no', $expense->ref_no, ['class' => 'form-control', 'required','id' => 'ref_no']) !!}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Días plazo') . ':*') !!}
                            {!! Form::text('plazo', $expense->plazo, ['class' => 'form-control', 'required', 'id' => 'plazo']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('transaction_date', __('messages.date') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('transaction_date', @format_datetime($expense->transaction_date), [
                                    'class' => 'form-control',
                                    'readonly',
                                    'required',
                                    'id' => 'expense_transaction_date',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('fecha_vence', __('Fecha Vence') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('fecha_vence', @format_datetime($expense->fecha_vence), [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'fecha_vence',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('additional_notes', 'Observación:') !!}
                            {!! Form::textarea('additional_notes', $expense->additional_notes, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>


                    <input type="hidden" value="@num_format($expense->final_total)" name="final_total" id="final_total">


                </div>
            </div>
        </div> <!--box end-->

        @component('components.widget', [
            'class' => 'box-primary',
            'id' => 'payment_rows_div',
            'title' => __('Detalle artículo'),
        ])
            <div class="payment_row">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('descripcion', 'Descripción:*') !!}
                            {!! Form::text('descripcion', null, ['class' => 'form-control', 'id' => 'descripcion']) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('precio', 'Precio:*') !!}
                            {!! Form::number('precio', null, ['class' => 'form-control', 'id' => 'precio', 'step' => '0.01']) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('cantidad', 'Cantidad:*') !!}
                            {!! Form::number('cantidad', null, ['class' => 'form-control', 'id' => 'cantidad']) !!}
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <button type="button" class="btn btn-primary" id="add-row" style="margin-top: 25px;">Añadir</button>
                    </div>
                </div>
                <table class="table table-bordered table-striped" id="detalle-table">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Las filas dinámicas serán agregadas aquí -->
                    </tbody>
                </table>
                <hr>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <strong>Valor total:</strong>
                            <span id="payment_due">{{ @num_format($expense->final_total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
            <a href="{{url('/expenses')}}" class="btn btn-info pull-right">Cancelar</a>
        </div>
        {!! Form::close() !!}
    </section>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
@endsection
@section('javascript')
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    <script>
        var plazo = $('#plazo').val();
        plazo = parseInt(plazo);
        plazoSetFecha(plazo);
        $('#plazo').on('input', function() {
            // Obtiene el valor del plazo
            plazoSetFecha(parseInt($(this).val()));
        });

        function plazoSetFecha(input) {
            var plazo = input;
            // Verifica si es un número válido
            if (!isNaN(plazo) && plazo > 0) {
                // Calcula la fecha de vencimiento
                var fechaVence = new Date();
                fechaVence.setDate(fechaVence.getDate() + plazo);

                // Formatea la fecha en el formato deseado (YYYY-MM-DD)
                var dia = ("0" + fechaVence.getDate()).slice(-2);
                var mes = ("0" + (fechaVence.getMonth() + 1)).slice(-2);
                var anio = fechaVence.getFullYear();

                // Asigna la fecha formateada al campo de fecha_vence
                $('#fecha_vence').val(anio + '-' + mes + '-' + dia);
            } else {
                // Si el plazo no es válido, limpia el campo de fecha_vence
                $('#fecha_vence').val('');
            }
        }
        const expenseDetails = @json($expense_details);

        // Función para agregar una fila con datos pre-cargados
        function addRow(descripcion, total, cantidad) {
            var subtotal = total * cantidad;

            var newRow = `
        <tr>
            <td>{!! Form::text('descripcion[]', '', ['class' => 'form-control']) !!}</td>
            <td>{!! Form::number('precio[]', '', ['class' => 'form-control precio']) !!}</td>
            <td>{!! Form::number('cantidad[]', '', ['class' => 'form-control cantidad']) !!}</td>
            <td class="subtotal">${subtotal.toFixed(2)}</td>
            <td>
                <input type="hidden" name="detalle_id[]" value="{{ $detail->id ?? '' }}">
                <button class="btn btn-xs btn-danger delete_user_button remove-row"><i class="glyphicon glyphicon-trash"></i> Borrar</button>
            </td>
        </tr>`;

            // Reemplazar los valores de los inputs con los datos
            newRow = newRow.replace('value=""', `value="${descripcion}"`);
            newRow = newRow.replace('value=""', `value="${total}"`);
            newRow = newRow.replace('value=""', `value="${cantidad}"`);
            $('#detalle-table tbody').append(newRow);
        }
        $(document).ready(function() {
            expenseDetails.forEach(detail => {
                addRow(detail.descripcion, parseFloat(detail.total), parseInt(detail.cantidad));
            });
            calculateExpensePaymentDue();
        });

        function calculateExpensePaymentDue() {
            var final_total = 0;
            $('#detalle-table tbody tr').each(function() {
                var subtotal = parseFloat($(this).find('.subtotal').text()) || 0;
                final_total += subtotal;
            });
            $('#payment_due').text(__currency_trans_from_en(final_total, true, false));
            $('input#final_total').val(final_total.toFixed(2));
        }
        $('#add-row').click(function() {
            // Obtener los valores de los inputs
            var descripcion = $('#descripcion').val();
            var precio = parseFloat($('#precio').val()) || 0;
            var cantidad = parseFloat($('#cantidad').val()) || 0;

            // Validar que los campos no estén vacíos
            if (descripcion && precio > 0 && cantidad > 0) {
                addRow(descripcion, precio, cantidad);

                // Actualizar el total
                calculateExpensePaymentDue();

                // Limpiar los inputs
                $('#descripcion').val('');
                $('#precio').val('');
                $('#cantidad').val('');
            } else {
                alert('Por favor, completa todos los campos con valores válidos.');
            }
        });
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateExpensePaymentDue();
        });
        $('#ref_no').on('blur', function() {
            var factura = $(this).val();
            if (factura != "") {
                $.ajax({
                    url: '/expense/check-ref_no',
                    type: 'POST',
                    data: {
                        ref_no: factura
                    },
                    success: function(response) {
                        if (response.valid) {
                            swal({
                                title: "La factura digitada ya existe",
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
                                    $('#ref_no').val('').focus();
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores
                        console.error("Error in validation request:", error);
                    }
                });
            }
        });
    </script>
@endsection

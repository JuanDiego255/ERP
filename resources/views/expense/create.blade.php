@extends('layouts.app')
@section('title', 'Agregar cuenta por pagar')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Nueva cuenta por pagar</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('ExpenseController@store'),
            'method' => 'post',
            'id' => 'add_expense_form',
            'files' => true,
        ]) !!}
        <div class="box box-primary">
            <div class="box-body">
                <div class="row">

                    @if (count($business_locations) == 1)
                        @php
                            $default_location = current(array_keys($business_locations->toArray()));
                        @endphp
                    @else
                        @php $default_location = null; @endphp
                    @endif
                    <input type="hidden" name="final_total" id="final_total">
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('Sucursal') . ':*') !!}
                            {!! Form::select('location_id', $business_locations, $default_location, [
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
                                {!! Form::select('contact_id', [], null, [
                                    'class' => 'form-control',
                                    'placeholder' => __('messages.please_select'),
                                    'required',
                                    'id' => 'supplier_id',
                                ]) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('expense_category_id', 'Categoria:') !!}
						{!! Form::select('expense_category_id', $expense_categories, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div> --}}
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('ref_no', __('Num Factura') . ':*') !!}
                            {!! Form::text('ref_no', null, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Días plazo') . ':*') !!}
                            {!! Form::text('plazo', null, ['class' => 'form-control', 'required', 'id' => 'plazo']) !!}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            {!! Form::label('transaction_date', __('messages.date') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('transaction_date', @format_datetime('now'), [
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
                                {!! Form::date('fecha_vence', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'required',
                                    'id' => 'fecha_vence',
                                ]) !!}
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-sm-3">
					<div class="form-group">
						{!! Form::label('expense_for', 'Conta para:') !!} @show_tooltip('Escolha o usuário para quem a conta está relacionada. (opcional)')
						{!! Form::select('expense_for', $users, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				</div> --}}
                    {{-- <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('document', 'Documento anexo' . ':') !!}
						{!! Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
						<p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
						@includeIf('components.document_help_text')</p>
					</div>
				</div> --}}

                    <div class="clearfix"></div>
                    {{-- <div class="col-md-4">
					<div class="form-group">
						{!! Form::label('tax_id', __('product.applicable_tax') . ':' ) !!}
						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-info"></i>
							</span>
							{!! Form::select('tax_id', $taxes['tax_rates'], null, ['class' => 'form-control'], $taxes['attributes']); !!}

							<input type="hidden" name="tax_calculation_amount" id="tax_calculation_amount" 
							value="0">
						</div>
					</div>
				</div> --}}
                    {{-- <div class="col-sm-4">
					<div class="form-group">
						{!! Form::label('final_total', __('sale.total_amount') . ':*') !!}
						{!! Form::text('final_total', null, ['class' => 'form-control input_number', 'placeholder' => __('sale.total_amount'), 'required']); !!}
					</div>
				</div> --}}
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('additional_notes', 'Observación' . ':') !!}
                        {!! Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
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
                            <span id="payment_due">{{ @num_format(0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endcomponent
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary pull-right">@lang('messages.save')</button>
        </div>
        {!! Form::close() !!}
    </section>

    <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        @include('contact.create', ['quick_add' => true])
    </div>
@endsection
@section('javascript')
    <script type="text/javascript">
        $(document).on('change', 'input#final_total, input.payment-amount', function() {
            calculateExpensePaymentDue();
        });
        function calculateExpensePaymentDue() {
            var final_total = 0;

            // Recorrer cada fila de la tabla para calcular el total
            $('#detalle-table tbody tr').each(function() {
                var subtotal = parseFloat($(this).find('.subtotal').text()) || 0;
                final_total += subtotal;
            });

            // Mostrar el total final calculado
            $('#payment_due').text(__currency_trans_from_en(final_total, true, false));
            $('input#final_total').val(final_total.toFixed(2));
        }
        $('#add-row').click(function() {
            // Obtener los valores de los inputs
            var descripcion = $('#descripcion').val();
            var precio = parseFloat($('#precio').val()) || 0;
            var cantidad = parseFloat($('#cantidad').val()) || 0;
            var subtotal = precio * cantidad;

            // Validar que los campos no estén vacíos
            if (descripcion && precio > 0 && cantidad > 0) {
                // Crear una nueva fila con los valores y un botón de eliminar
                var newRow = `
            <tr>
                <td>{!! Form::text('descripcion[]', '', ['class' => 'form-control']) !!}</td>
                <td>{!! Form::number('precio[]', '', ['class' => 'form-control precio']) !!}</td>
                <td>{!! Form::number('cantidad[]', '', ['class' => 'form-control cantidad']) !!}</td>
                <td class="subtotal">${subtotal.toFixed(2)}</td>
                <td><button class="btn btn-xs btn-danger delete_user_button remove-row"><i class="glyphicon glyphicon-trash"></i> Borrar</button></td>
            </tr>`;

                // Cambiar los valores de los inputs con valores actuales
                newRow = newRow.replace('value=""', `value="${descripcion}"`);
                newRow = newRow.replace('value=""', `value="${precio}"`);
                newRow = newRow.replace('value=""', `value="${cantidad}"`);

                // Agregar la nueva fila a la tabla
                $('#detalle-table tbody').append(newRow);

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
        $('#plazo').on('input', function() {
            // Obtiene el valor del plazo
            var plazo = parseInt($(this).val());

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
        });
    </script>
    <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>

@endsection

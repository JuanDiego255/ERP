<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Seleccionar vehículo</h4>
        </div>

        <div class="modal-body">
            @component('components.widget', ['title' => __('Información del vehículo')])
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vehiculo_id', __('Seleccionar un vehículo') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-car"></i>
                                </span>

                                {!! Form::select('vehiculo_id', [], null, [
                                    'class' => 'form-control mousetrap',
                                    'id' => 'vehiculo_id',
                                    'placeholder' => 'Seleccione un vehículo',
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('marca', __('Marca')) !!}
                            <div class="input-group">
                                {!! Form::text('marca', null, [
                                    'class' => 'form-control',
                                    'id' => 'marca',
                                    'readonly',
                                    'required',
                                    'placeholder' => __('Descripción'),
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('fecha_ingreso', __('Fecha ingreso')) !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::text('fecha_ingreso', null, [
                                    'class' => 'form-control',
                                    'id' => 'fecha_ingreso',
                                    'readonly',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('vin', __('VIN')) !!}
                            <div class="input-group">
                                {!! Form::text('vin', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'vin',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('dua', __('DUA')) !!}
                            <div class="input-group">
                                {!! Form::text('dua', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'dua',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('placa', __('Placa')) !!}
                            <div class="input-group">
                                {!! Form::text('placa', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'placa',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('combustible', __('Combustible')) !!}
                            <div class="input-group">
                                {!! Form::text('combustible', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'combustible',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('model', __('Modelo')) !!}
                            <div class="input-group">
                                {!! Form::text('model', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'model',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('color', __('Color')) !!}
                            <div class="input-group">
                                {!! Form::text('color', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'color',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('gastos', __('Total gastos')) !!}
                            <div class="input-group">
                                {!! Form::text('gastos', null, [
                                    'class' => 'form-control',
                                    'readonly',
                                    'id' => 'gastos',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endcomponent
            @component('components.widget', ['title' => __('Venta')])
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('fecha_venta', __('Fecha venta')) !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            {!! Form::date('fecha_venta', null, [
                                'class' => 'form-control',
                                'id' => 'fecha_venta',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('monto_venta', __('Monto venta')) !!}
                        <div class="input-group">
                            {!! Form::text('monto_venta', 0, [
                                'class' => 'form-control number',
                                'id' => 'monto_venta',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('efectivo', __('Efectivo')) !!}
                        <div class="input-group">
                            {!! Form::text('efectivo', 0, [
                                'class' => 'form-control number',
                                'id' => 'efectivo',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('monto_recibo_modal', __('Monto Recibo')) !!}
                        <div class="input-group">
                            {!! Form::text('monto_recibo_modal', 0, [
                                'class' => 'form-control number',
                                'id' => 'monto_recibo_modal',
                                'required',
                                'min' => 0,
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary save_vehicle" id="save_vehicle">@lang('Seleccionar')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

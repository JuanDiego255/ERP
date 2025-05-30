<div class="modal-dialog modal-lg" role="document">
    {!! Form::open([
        'action' => 'EmployeeController@updateRubro',
        'id' => 'rubros_employee_form',
        'method' => 'put',
    ]) !!}
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">@lang('Editar rubro')</h4>
        </div>
        {!! Form::hidden('employee_id', $employee_rubros->employee_id, ['class' => 'form-control']) !!}
        {!! Form::hidden('id', $employee_rubros->id, ['class' => 'form-control']) !!}
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('tipo', 'Tipo' . ':*') !!}
                    {!! Form::select('tipo', ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'], $employee_rubros->tipo, [
                        'class' => 'form-control',
                        'id' => 'tipo',
                        'required',
                        'placeholder' => __('messages.please_select'),
                    ]) !!}
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('rubro_id', 'Rubro' . ':*') !!}
                        {!! Form::select('rubro_id', $rubros, $employee_rubros->rubro_id, [
                            'class' => 'form-control',
                            'id' => 'rubro_id',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-group">
                            {!! Form::label('valor', __('Valor') . ':*') !!}
                            {!! Form::text('valor', $employee_rubros->valor, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Valor'),
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" @if ($employee_rubros->status == 1) checked @endif name="status"
                                    value="1"> @lang('Estado')
                                <i class="fa fa-info-circle" data-toggle="tooltip" title="@lang('Habilita este rubro a la hora de generar la planilla')"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                @lang('messages.update')
            </button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                @lang('messages.close')
            </button>
        </div>
    </div><!-- /.modal-content -->
    {!! Form::close() !!}
</div><!-- /.modal-dialog -->

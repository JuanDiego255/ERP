@extends('layouts.app')

@section('title', __('Editar rubro'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Editar rubro')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('RubrosController@update', [$rubro->id]),
            'method' => 'PUT',
            'id' => 'user_edit_form',
        ]) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('name', __('Rubro') . ':*') !!}
                            {!! Form::text('name', $rubro->name, ['class' => 'form-control', 'required', 'placeholder' => __('Rubro')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('alias', __('Alias') . ':*') !!}
                            {!! Form::text('alias', $rubro->alias, ['class' => 'form-control', 'required', 'placeholder' => __('Alias')]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('category', 'Categoría' . ':*') !!}
                        {!! Form::select(
                            'category',
                            ['ingreso' => 'Ingreso', 'deduccion' => 'Deducción'],
                            !empty($rubro->category) ? $rubro->category : null,
                            ['class' => 'form-control', 'id' => 'category', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('tipo', 'Tipo (Indica el valor al crear un rubro al colaborador)' . ':*') !!}
                        {!! Form::select(
                            'tipo',
                            [
                                'monto' => 'Monto',
                                'cant_salarios' => 'Cant. Salarios',
                                'cant_horas' => 'Cant. Horas',
                                'cant_dias' => 'Cant. Días',
                            ],
                            !empty($rubro->tipo) ? $rubro->tipo : null,
                            ['class' => 'form-control', 'id' => 'tipo', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('tipo_calculo', 'Tipo calculo' . ':*') !!}
                        {!! Form::select(
                            'tipo_calculo',
                            ['normal' => 'Normal', 'extra_diurna' => 'Extra diurna', 'doble' => 'Doble'],
                            !empty($rubro->tipo_calculo) ? $rubro->tipo_calculo : null,
                            ['class' => 'form-control', 'id' => 'tipo_calculo', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('status', 'Estado' . ':*') !!}
                        {!! Form::select(
                            'status',
                            ['1' => 'Activo', '0' => 'Inactivo'],
                            $rubro->status,
                            ['class' => 'form-control', 'id' => 'status', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang('messages.update')</button>
            </div>
        </div>
        {!! Form::close() !!}
    @stop
    @section('javascript')
    @endsection

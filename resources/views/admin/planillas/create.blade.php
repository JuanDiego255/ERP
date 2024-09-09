@extends('layouts.app')

@section('title', __('Crear planilla'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Crear planilla')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open(['url' => action('PlanillaController@store'), 'method' => 'post', 'id' => 'planilla_add_form']) !!}
        <div class="row">
            <div class="col-md-12">

                @component('components.widget')
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('fecha_desde', __('Fecha Desde') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('fecha_desde', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'fecha_desde',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('fecha_hasta', __('Fecha Hasta') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                {!! Form::date('fecha_hasta', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'fecha_hasta',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('tipo_planilla_id', 'Tipo planilla' . ':*') !!}
                            {!! Form::select('tipo_planilla_id', $tipo_planillas, null, [
                                'class' => 'form-control',
                                'id' => 'tipo_planilla_id',
                                'required',
                                'placeholder' => __('messages.please_select'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            {!! Form::label('descripcion', __('Descripcion') . ':') !!}
                            {!! Form::textarea('descripcion', null, [
                                'class' => 'form-control',
                                'placeholder' => __('Descripción de la planilla'),
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang('messages.save')</button>
            </div>
        </div>
        {!! Form::close() !!}
    @stop

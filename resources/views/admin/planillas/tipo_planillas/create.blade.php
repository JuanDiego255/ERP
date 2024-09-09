@extends('layouts.app')

@section('title', __('Agregar tipo de planilla'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Agregar tipo de planilla')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open(['url' => action('PlanillaController@storeTipoPlanilla'), 'method' => 'post', 'id' => 'tipo_planilla_add_form']) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('tipo', __('Tipo') . ':*') !!}
                            {!! Form::text('tipo', null, ['class' => 'form-control', 'required', 'placeholder' => __('Tipo de planilla')]) !!}
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

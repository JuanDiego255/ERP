@extends('layouts.app')

@section('title', __('Envìo de SMS de prueba'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Envìo de SMS de prueba')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open(['url' => action('SmsController@send'), 'method' => 'post', 'id' => 'user_add_form']) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('mensaje', __('Mensaje') . ':*') !!}
                            {!! Form::text('mensaje', null, ['class' => 'form-control', 'required', 'placeholder' => __('Mensaje')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('telefono', __('Teléfono') . ':') !!}
                            {!! Form::text('telefono', null, ['class' => 'form-control', 'placeholder' => __('Teléfono')]) !!}
                        </div>
                    </div>
                    
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">Enviar SMS</button>
            </div>
        </div>
        {!! Form::close() !!}
    @stop
    @section('javascript')
    @endsection

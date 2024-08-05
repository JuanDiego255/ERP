@extends('layouts.app')

@section('title', __('Agregar empleado'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Agregar empleado')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open(['url' => action('EmployeeController@store'), 'method' => 'post', 'id' => 'user_add_form']) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('name', __('Nombre Completo') . ':*') !!}
                            {!! Form::text('name', null, ['class' => 'form-control','required', 'placeholder' => __('Nombre')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('telephone', __('Teléfono') . ':*') !!}
                            {!! Form::text('telephone', null, ['class' => 'form-control','required', 'placeholder' => __('Teléfono')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('celular', __('Celular') . ':') !!}
                            {!! Form::text('celular', null, ['class' => 'form-control', 'placeholder' => __('Celular')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('email', __('Correo') . ':*') !!}
                            {!! Form::text('email', null, ['class' => 'form-control','required', 'placeholder' => __('Correo')]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <hr />
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('salario_base', __('Salario Base') . ':*') !!}
                            {!! Form::text('salario_base', null, ['class' => 'form-control','required', 'placeholder' => __('Salario base')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('asociacion', __('Asociación') . ':') !!}
                            {!! Form::text('asociacion', null, ['class' => 'form-control', 'placeholder' => __('Asociación')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('ccss', __('Deducción C.C.S.S') . ':*') !!}
                            {!! Form::text('ccss', null, ['class' => 'form-control','required', 'placeholder' => __('Deducción C.C.S.S')]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('tipo_pago', 'Tipo pago' . ':*') !!}
                        {!! Form::select('tipo_pago', ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'], !empty($employee->puesto) ? $employee->gender : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); !!}
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('moneda_pago', __('Moneda pago') . ':') !!}
                            {!! Form::text('moneda_pago', 'Colones', ['class' => 'form-control','readonly', 'placeholder' => __('Pago')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('salario_hora', __('Salario por hora') . ':') !!}
                            {!! Form::text('salario_hora', null, ['class' => 'form-control', 'placeholder' => __('Salario por hora')]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('puesto', 'Puesto' . ':*') !!}
                        {!! Form::select('puesto', ['vendedor' => 'Vendedor', 'legal' => 'Legal', 'admin' => 'Adminstrativo'], !empty($employee->puesto) ? $employee->puesto : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); !!}
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('comision_ventas', __('Comisión por ventas') . ':') !!}
                            {!! Form::text('comision_ventas', null, ['class' => 'form-control', 'placeholder' => __('Comisión por ventas')]) !!}
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
    @section('javascript')
    @endsection

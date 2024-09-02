@extends('layouts.app')

@section('title', __('Editar empleado'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Editar empleado')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('EmployeeController@update', [$employee->id]),
            'method' => 'PUT',
            'id' => 'user_edit_form',
        ]) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary'])
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('name', __('Nombre') . ':*') !!}
                            {!! Form::text('name', $employee->name, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Nombre'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('telephone', __('Teléfono') . ':') !!}
                            {!! Form::text('telephone', $employee->telephone, [
                                'class' => 'form-control',                                
                                'placeholder' => __('Teléfono'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('celular', __('Celular') . ':') !!}
                            {!! Form::text('celular', $employee->celular, [
                                'class' => 'form-control',
                                'placeholder' => __('Celular'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('email', __('Correo') . ':*') !!}
                            {!! Form::text('email', $employee->email, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Correo'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('salario_base', __('Salario base') . ':*') !!}
                            {!! Form::text('salario_base', $employee->salario_base, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Salario base'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('ccss', __('Deducción C.C.S.S') . ':') !!}
                            {!! Form::text('ccss', $employee->ccss, [
                                'class' => 'form-control',
                                'placeholder' => __('Deducción C.C.S.S'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('asociacion', __('Asociación') . ':') !!}
                            {!! Form::text('asociacion', $employee->asociacion, [
                                'class' => 'form-control',
                                'placeholder' => __('Asociación'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('tipo_pago', 'Tipo pago' . ':') !!}
                        {!! Form::select(
                            'tipo_pago',
                            ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'],
                            !empty($employee->tipo_pago) ? $employee->tipo_pago : null,
                            ['class' => 'form-control', 'id' => 'gender', 'required', 'placeholder' => __('messages.please_select')],
                        ) !!}
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('moneda_pago', __('Moneda pago') . ':') !!}
                            {!! Form::text('moneda_pago', $employee->moneda_pago, [
                                'class' => 'form-control',
                                'readonly',
                                'placeholder' => __('Moneda pago'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('salario_hora', __('Salario por hora') . ':') !!}
                            {!! Form::text('salario_hora', $employee->salario_hora, [
                                'class' => 'form-control',
                                'placeholder' => __('Salario por hora'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('puesto', 'Puesto' . ':*') !!}
                        {!! Form::select('puesto', ['vendedor' => 'Vendedor', 'legal' => 'Legal', 'admin' => 'Adminstrativo'], !empty($employee->puesto) ? $employee->puesto : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); !!}
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {!! Form::label('comision_ventas', __('Comisión Ventas') . ':') !!}
                            {!! Form::text('comision_ventas', $employee->comision_ventas, [
                                'class' => 'form-control',
                                'placeholder' => __('Comisión Ventas'),
                            ]) !!}
                        </div>
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

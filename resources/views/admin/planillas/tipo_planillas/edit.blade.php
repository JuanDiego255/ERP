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
            'url' => action('PlanillaController@updateTipoPlanilla', [$tipo->id]),
            'method' => 'PUT',
            'id' => 'user_edit_form',
        ]) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('tipo', __('Tipo') . ':*') !!}
                            {!! Form::text('tipo', $tipo->tipo, ['class' => 'form-control', 'required', 'placeholder' => __('Tipo de planilla')]) !!}
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

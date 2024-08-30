@extends('layouts.app')

@section('title', __('Editar gasto'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('Editar gasto')</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('Admin\BillVehicleController@update', [$bill->id]),
            'method' => 'PUT',
            'id' => 'bill_edit_form',
        ]) !!}
        {!! Form::hidden('product_id', $bill->product_id, ['id' => 'product_id']) !!}
        <div class="row">
            <div class="col-md-12">
                @component('components.widget')
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('fecha_compra', __('Fecha Compra') . ':*') !!}
                            {!! Form::date('fecha_compra', $bill->fecha_compra, ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('monto', __('Monto') . ':*') !!}
                            {!! Form::text('monto', $bill->monto, ['class' => 'form-control', 'required', 'placeholder' => __('Monto')]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('factura', __('Factura') . ':*') !!}
                            {!! Form::text('factura', $bill->factura, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('# Factura'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('proveedor_id', __('purchase.supplier') . ':*') !!}
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </span>
                                {!! Form::select(
                                    'proveedor_id',
                                    [$bill->proveedor_id => $bill->proveedor_id ? $prov_name : ''],
                                    $bill->proveedor_id,
                                    [
                                        'class' => 'form-control',
                                        'placeholder' => __('messages.please_select'),
                                        'required',
                                        'id' => 'supplier_id',
                                    ],
                                ) !!}
                                {{-- <span class="input-group-btn">
                                    <button type="button" class="btn btn-default bg-white btn-flat add_new_supplier"
                                        data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span> --}}
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group">
                            {!! Form::label('descripcion', __('Descripcion') . ':*') !!}
                            {!! Form::textarea('descripcion', $bill->descripcion, [
                                'class' => 'form-control',
                                'placeholder' => __('Descripción del gasto'),
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
        <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script>
    @endsection

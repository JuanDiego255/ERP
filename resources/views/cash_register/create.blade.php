@extends('layouts.app')
@section('title', __('cash_register.open_cash_register'))

@section('content')
    <style type="text/css">



    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>@lang('cash_register.open_cash_register')</h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        {!! Form::open([
            'url' => action('CashRegisterController@store'),
            'method' => 'post',
            'id' => 'add_cash_register_form',
        ]) !!}
        <div class="box box-solid">
            <div class="box-body">
                <br><br><br>
                <input type="hidden" name="sub_type" value="{{ $sub_type }}">
                <div class="row">
                    @if ($business_locations->count() > 0)
                        <div
                            class="col-sm-3 @if (count($business_locations) == 1) col-sm-offset-3 @else col-sm-offset-1 @endif">
                            <div class="form-group">
                                {!! Form::label('amount', 'Valor em dinheiro:*') !!}
                                {!! Form::text('amount', null, [
                                    'class' => 'form-control money',
                                    'placeholder' => __('cash_register.enter_amount'),
                                    'required',
                                ]) !!}
                            </div>
                        </div>
                        @if (count($business_locations) > 1)
                            <!-- <div class="clearfix"></div> -->
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::label('location_id', 'Local:*') !!}
                                    {!! Form::select('location_id', $business_locations, null, [
                                        'class' => 'form-control select2',
                                        'placeholder' => __('lang_v1.select_location'),
                                        'required',
                                    ]) !!}
                                </div>
                            </div>
                        @else
                            {!! Form::hidden('location_id', array_key_first($business_locations->toArray())) !!}
                        @endif
                        <div class="col-sm-3">
                            <button type="submit" style="margin-top: 23px;"
                                class="btn btn-primary pull-left">@lang('cash_register.open_register')</button>
                        </div>
                    @else
                        <div class="col-sm-8 col-sm-offset-2 text-center">
                            <h3>@lang('lang_v1.no_location_access_found')</h3>
                        </div>
                    @endif
                </div>
                <br><br><br>
            </div>
        </div>
        {!! Form::close() !!}
    </section>
    <!-- /.content -->
@endsection

@extends('layouts.app')
@section('title', 'Cuentas por cobrar')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Cuentas por cobrar</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('Sucursal') . ':') !!}
                        {!! Form::select('location_id', [ 'GRECIA' => 'GRECIA','NICOYA' => 'NICOYA'], null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                
               {{--  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('expense_category_id', 'Categoria:') !!}
                        {!! Form::select('expense_category_id', $categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'expense_category_id']); !!}
                    </div>
                </div> --}}{{-- 
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('expense_date_range', __('report.date_range') . ':') !!}
                        {!! Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'expense_date_range', 'readonly']); !!}
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('expense_payment_status',  __('purchase.payment_status') . ':') !!}
                        {!! Form::select('expense_payment_status', ['2' => 'Pendiente', '1' => 'Cobrado'], null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => ''])
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="revenue_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>Cliente</th>
                                <th>Plan de venta</th>                                
                                <th>Valor Inicial</th>
                                <th>Total a cobrar</th>
                                <th>Vehículo</th>
                                <th>Modelo</th>
                                <th>Sucursal</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="2"><strong>@lang('sale.total'):</strong></td>
                                <td id="footer_payment_status_count"></td>
                                <td><span class="display_currency" id="footer_revenue_total" data-currency_symbol ="true"></span></td>
                                <td><span class="display_currency" id="footer_total_due" data-currency_symbol ="true"></span></td>
                                <td colspan="4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcomponent
        </div>
    </div>

</section>
<!-- /.content -->
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
@stop
@section('javascript')
 <script src="{{ asset('js/revenue.js') }}"></script>

@endsection
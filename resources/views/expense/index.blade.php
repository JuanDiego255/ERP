@extends('layouts.app')
@section('title', $is_report == true ? 'Cuentas Por Pagar - Reportes' : 'Cuentas Por Pagar')
<style>
    .d-none {
        display: none !important;
    }

    .d-block {
        display: block !important;
    }
</style>
@section('content')
    <input type="hidden" id="is_report" value="{{ $is_report }}" name="is_report">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>{{ $is_report == true ? 'Cuentas Por Pagar - Reportes' : 'Cuentas Por Pagar' }}</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('purchase.business_location') . ':') !!}
                            {!! Form::select('location_id', $business_locations, null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    {{--     <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label('expense_for', __('expense.expense_for').':') !!}
                        {!! Form::select('expense_for', $users, null, ['class' => 'form-control select2']); !!}
                    </div>
                </div> --}}
                    {{--  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('expense_category_id',__('expense.expense_category').':') !!}
                        {!! Form::select('expense_category_id', $categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'expense_category_id']); !!}
                    </div>
                </div> --}}
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('expense_payment_status', __('purchase.payment_status') . ':') !!}
                            {!! Form::select('expense_payment_status', ['due' => __('lang_v1.due'), 'paid' => __('lang_v1.paid')], null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('type', 'Filtrar fechas por:') !!}
                            {!! Form::select('type', ['0' => 'Fecha de creación', '1' => 'Fecha vence'], null, [
                                'class' => 'form-control select2',
                                'id' => 'type',
                            ]) !!}
                        </div>
                    </div>
                    <div id="div_date" class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('expense_date_range', __('report.date_range') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'expense_date_range',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                    <div id="div_date_vence" class="col-md-3 d-none">
                        <div class="form-group">
                            {!! Form::label('expense_date_vence', __('Rango de vencimiento') . ':') !!}
                            {!! Form::text('date_range', null, [
                                'placeholder' => __('lang_v1.select_a_date_range'),
                                'class' => 'form-control',
                                'id' => 'expense_date_vence',
                                'readonly',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => 'Todas las cuentas por pagar'])
                    @if ($is_report == false)
                        @can('expense.access')
                            @slot('tool')
                                <div class="box-tools">
                                    <a class="btn btn-block btn-primary" href="{{ action('ExpenseController@create') }}">
                                        <i class="fa fa-plus"></i> @lang('messages.add')</a>
                                </div>
                            @endslot
                        @endcan
                    @endif
                    <button id="generate_report" class="btn btn-primary">Rep. CUEPAG</button>
                    <button id="generate_report_detail" class="btn btn-primary">Rep. Cuentas Por Pagar (AG)</button>
                    <div id="report_container" style="margin-top: 20px;"></div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="expense_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>Incluir</th>
                                    <th>Proveedor</th>
                                    <th>@lang('Factura')</th>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('Vence')</th>
                                    <th>@lang('sale.payment_status')</th>
                                    <th>@lang('sale.total_amount')</th>
                                    <th>Total a Pagar</th>
                                    <th>Detalle</th>
                                    <th>@lang('lang_v1.added_by')</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="bg-gray font-17 text-center footer-total">
                                    <td colspan="6"><strong>@lang('sale.total'):</strong></td>
                                    <td id="footer_payment_status_count"></td>
                                    <td><span class="display_currency" id="footer_expense_total"
                                            data-currency_symbol ="true"></span></td>
                                    <td><span class="display_currency" id="footer_total_due"
                                            data-currency_symbol ="true"></span></td>
                                    <td colspan="2"></td>
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
    <div class="modal fade payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade" id="view_product_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade" id="view_cxp_detail_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>
@stop
@section('javascript')
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    <script type="text/javascript">
        function printThis() {
            $("#cuepagModal").printThis({
                importCSS: true, // Importa los estilos de la página
                importStyle: true, // Importa las hojas de estilo
                loadCSS: "/public/css/print.css", // Ruta a una hoja de estilo específica para impresión
                pageTitle: false, // Título de la página
                removeInline: false,
                printDelay: 500, // Tiempo de espera antes de imprimir
                header: false, // Opcional: cabecera en cada página
                footer: null // Opcional: pie de página
            });
        }
        function printThisDetail() {
            $("#cxp_detail").printThis({
                importCSS: true, // Importa los estilos de la página
                importStyle: true, // Importa las hojas de estilo
                loadCSS: "/public/css/print.css", // Ruta a una hoja de estilo específica para impresión
                pageTitle: false, // Título de la página
                removeInline: false,
                printDelay: 500, // Tiempo de espera antes de imprimir
                header: false, // Opcional: cabecera en cada página
                footer: null // Opcional: pie de página
            });
        }
    </script>
@endsection

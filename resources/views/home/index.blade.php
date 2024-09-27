@extends('layouts.app')
@section('title', __('home.home'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <!-- <h1>{{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
                                                    </h1> -->
    </section>
    @if (auth()->user()->can('dashboard.data'))
        <!-- Main content -->
        <section class="content content-custom no-print">
            <br>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    @if (count($all_locations) > 1)
                        {!! Form::select('dashboard_location', $all_locations, null, [
                            'class' => 'form-control select2',
                            'placeholder' => __('lang_v1.select_location'),
                            'id' => 'dashboard_location',
                        ]) !!}
                    @endif
                </div>
                <div class="col-md-8 col-xs-12">
                    <div class="btn-group pull-right" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input type="radio" name="date-filter" data-start="{{ date('Y-m-d') }}"
                                data-end="{{ date('Y-m-d') }}" checked> {{ 'Hoy' }}
                        </label>
                        <label class="btn btn-info">
                            <input type="radio" name="date-filter" data-start="{{ $date_filters['this_week']['start'] }}"
                                data-end="{{ $date_filters['this_week']['end'] }}"> Esta Semana
                        </label>
                        <label class="btn btn-info">
                            <input type="radio" name="date-filter" data-start="{{ $date_filters['this_month']['start'] }}"
                                data-end="{{ $date_filters['this_month']['end'] }}"> Este Mes
                        </label>
                        <label class="btn btn-info">
                            <input type="radio" name="date-filter" data-start="{{ $date_filters['this_fy']['start'] }}"
                                data-end="{{ $date_filters['this_fy']['end'] }}"> Este Ano
                        </label>
                    </div>
                </div>
            </div>
            <br>
            <div class="row row-custom">
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <div class="info-box info-box-new-style">
                        <a href="{{ action('ProductController@showByItem', ['type' => 0]) }}" class="view-cars">
                            <span class="info-box-icon bg-green"><i class="fa fa-car"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Autos en exhibición') }}</span>
                            <span class="info-box-number vehicle_count"><i
                                    class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <div class="info-box info-box-new-style">
                        <a href="{{ action('ProductController@showByItem', ['type' => 1]) }}" class="view-cars">
                            <span class="info-box-icon bg-green">
                                <i class="fa fa-wrench"></i>
                            </span>
                        </a>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Vehículo en mantenimiento') }}</span>
                            <span class="info-box-number vehicle_mant"><i
                                    class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <div class="info-box info-box-new-style">
                        <span class="info-box-icon bg-green">
                            <i class="fa fa-dollar"></i>
                            <i class="fa fa-car-crash"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">{{ __('Gastos en vehículos') }}</span>
                            <span class="info-box-number vehicle_due"><i
                                    class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <!-- <div class="clearfix visible-sm-block"></div> -->


                <!-- expense -->
                <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                    <div class="info-box info-box-new-style">
                        <span class="info-box-icon bg-green">
                            <i class="fas fa-minus-circle"></i>
                        </span>

                        <div class="info-box-content">
                            <span class="info-box-text">
                                {{ __('Monto a pagar CxP') }}
                            </span>
                            <span class="info-box-number total_expense"><i
                                    class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row row-custom">
            </div>
            @if (!empty($widgets['after_sale_purchase_totals']))
                @foreach ($widgets['after_sale_purchase_totals'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
            @if (!empty($all_locations))
                <!-- sales chart start -->
                <div class="row">
                    <div class="col-sm-12">
                        @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_last_30_days')])
                            {!! $sells_chart_1->container() !!}
                        @endcomponent
                    </div>
                </div>
            @endif
            @if (!empty($widgets['after_sales_last_30_days']))
                @foreach ($widgets['after_sales_last_30_days'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
            {{-- @if (!empty($all_locations))
                <div class="row">
                    <div class="col-sm-12">
                        @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_current_fy')])
                            {!! $sells_chart_2->container() !!}
                        @endcomponent
                    </div>
                </div>
            @endif --}}
            <!-- sales chart end -->
            @if (!empty($widgets['after_sales_current_fy']))
                @foreach ($widgets['after_sales_current_fy'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
            <!-- products less than alert quntity -->
            {{--  <div class="row">

                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                        @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                        @endslot
                        @slot('title')
                            {{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))
                        @endslot
                        <table class="table table-bordered table-striped" id="sales_payment_dues_table">
                            <thead>
                                <tr>
                                    <th>@lang('contact.customer')</th>
                                    <th>@lang('sale.invoice_no')</th>
                                    <th>@lang('home.due_amount')</th>
                                </tr>
                            </thead>
                        </table>
                    @endcomponent
                </div>

                <div class="col-sm-6">

                    @component('components.widget', ['class' => 'box-warning'])
                        @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                        @endslot
                        @slot('title')
                            {{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))
                        @endslot
                        <table class="table table-bordered table-striped" id="purchase_payment_dues_table">
                            <thead>
                                <tr>
                                    <th>@lang('purchase.supplier')</th>
                                    <th>@lang('purchase.ref_no')</th>
                                    <th>@lang('home.due_amount')</th>
                                </tr>
                            </thead>
                        </table>
                    @endcomponent

                </div>
            </div> --}}

            {{-- <div class="row">

                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                        @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                        @endslot
                        @slot('title')
                            {{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))
                        @endslot
                        <table class="table table-bordered table-striped" id="stock_alert_table">
                            <thead>
                                <tr>
                                    <th>@lang('sale.product')</th>
                                    <th>@lang('business.location')</th>
                                    <th>@lang('report.current_stock')</th>
                                </tr>
                            </thead>
                        </table>
                    @endcomponent
                </div>
                @can('stock_report.view')
                    @if (session('business.enable_product_expiry') == 1)
                        <div class="col-sm-6">
                            @component('components.widget', ['class' => 'box-warning'])
                                @slot('icon')
                                    <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                                @endslot
                                @slot('title')
                                    {{ __('home.stock_expiry_alert') }} @show_tooltip( __('tooltip.stock_expiry_alert', [ 'days'
                                    =>session('business.stock_expiry_alert_days', 30) ]) )
                                @endslot
                                <input type="hidden" id="stock_expiry_alert_days"
                                    value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
                                <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
                                    <thead>
                                        <tr>
                                            <th>@lang('business.product')</th>
                                            <th>@lang('business.location')</th>
                                            <th>@lang('report.stock_left')</th>
                                            <th>@lang('product.expires_in')</th>
                                        </tr>
                                    </thead>
                                </table>
                            @endcomponent
                        </div>
                    @endif
                @endcan
            </div> --}}

            @if (!empty($widgets['after_dashboard_reports']))
                @foreach ($widgets['after_dashboard_reports'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
        </section>
        <!-- /.content -->
        <div class="modal fade" id="view_product_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel">
        </div>
    @stop
    @section('javascript')
        <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
        @if (!empty($all_locations))
            {!! $sells_chart_1->script() !!}
            {!! $sells_chart_2->script() !!}
        @endif
        <script>
            $(document).on('click', 'a.view-cars', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var type = href.split('/').pop();
                $.ajax({
                    url: href,
                    dataType: 'html',
                    success: function(result) {
                        $('#view_product_modal')
                            .html(result);                      

                        $('#view_product_modal').modal('show');
                        __currency_convert_recursively($('#view_product_modal_container'));
                    },
                });
            });
        </script>
    @endif
@endsection

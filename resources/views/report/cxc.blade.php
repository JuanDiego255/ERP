{{-- resources/views/report/cxc.blade.php --}}
@extends('layouts.app')
@section('title', __('Cuentas por cobrar - Reporte'))

@section('content')
    <section class="content-header">
        <h1>@lang('Cuentas por cobrar - Reporte')</h1>
    </section>

    <section class="content">
        <div class="print_section">
            <h2>{{ session()->get('business.name') }} - @lang('Cuentas por cobrar')</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div id="div_date_report_start" class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date_report_start', __('Fecha (Inicial)') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::date('date_report_start', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'date_report_start',
                                ]) !!}
                            </div>
                        </div>
                        <p class="help-block">
                            Campo no requerido. Si no se indica, se toman 6 meses hacia atrás con base en la fecha final o hoy.
                        </p>
                    </div>

                    <div id="div_date_report_end" class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('date_report_end', __('Fecha (Final)') . ':') !!}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                {!! Form::date('date_report_end', @format_datetime('now'), [
                                    'class' => 'form-control',
                                    'id' => 'date_report_end',
                                ]) !!}
                            </div>
                        </div>
                    </div>

{{--                     <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('expense_payment_status', __('purchase.payment_status') . ':') !!}
                            {!! Form::select('expense_payment_status', ['2' => 'Pendiente', '1' => 'Cobrado'], null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div> --}}

                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('Sucursal') . ':') !!}
                            {!! Form::select('location_id', ['GRECIA' => 'GRECIA', 'NICOYA' => 'NICOYA','TODAS' => 'TODAS'], null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('allow', __('Mostrar Columnas Sensibles') . ':') !!}
                            {!! Form::select('allow', ['1' => 'SI', '2' => 'NO'], null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                @endcomponent
            </div>
        </div>

        <div class="row">
            <div id="pl_data_div"></div>
        </div>

        <div class="row no-print">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#cxc_by_month" data-toggle="tab" aria-expanded="true">
                                <i class="fa fa-calendar"></i> @lang('CXC por mes')
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="cxc_by_month">
                            <button id="generate_report_cxc" class="btn btn-primary mb-3 text-center">Generar
                                Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view_cxc_report_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel"></div>
    </section>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#generate_report_cxc', function() {
                if (!$('#date_report_end').val()) {
                    swal({
                        title: "Debe seleccionar la fecha final del filtro",
                        icon: 'warning',
                        buttons: {
                            confirm: {
                                text: "OK",
                                value: true,
                                visible: true,
                                closeModal: true
                            }
                        },
                        dangerMode: true,
                    }).then(ok => {
                        if (ok) $('#date_report_end').focus();
                    });
                    return;
                }

                let url = '{{ route('cxc.generateReport') }}';
                let data = {
                    date_start: $('#date_report_start').val() ? $('#date_report_start').val() : null,
                    date_end: $('#date_report_end').val() ? $('#date_report_end').val() : null,
                    //status: $('#expense_payment_status').val(),
                    location_id: $('#location_id').val(),
                    allow: $('#allow').val()
                };
                console.log(data);

                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'html',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        $('#view_cxc_report_modal').html(result).modal('show');
                        __currency_convert_recursively($('#view_cxc_report_modal'));
                    },
                    error: function(xhr) {
                        console.error('Error al generar el reporte:', xhr?.responseText || xhr);
                    }
                });
            });
        });

        function printThisCxc() {
            $("#cxcModalReport").printThis({
                importCSS: true,
                importStyle: true,
                loadCSS: "/public/css/print.css",
                pageTitle: false,
                removeInline: false,
                printDelay: 500,
                header: false,
                footer: null
            });
        }
    </script>
@endsection

{{-- resources/views/report/cxc.blade.php --}}
@extends('layouts.app')
@section('title', __('Cuentas por cobrar - Reporte Vencimiento'))

@section('content')
    <section class="content-header">
        <h1>@lang('Cuentas por cobrar - Reporte Vencimiento')</h1>
    </section>

    <section class="content">
        <div class="print_section">
            <h2>{{ session()->get('business.name') }} - @lang('Cuentas por cobrar')</h2>
        </div>

        <div class="row">
            <div class="col-md-12">
                @component('components.filters', ['title' => __('report.filters')])
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('plazo', __('Plazo por vencimiento') . ':') !!}
                            {!! Form::select('plazo', ['30' => '30 días', '60' => '60 días','90' => '90 días o +'], null, [
                                'class' => 'form-control select2',
                                'style' => 'width:100%',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('location_id', __('Sucursal') . ':') !!}
                            {!! Form::select('location_id', ['GRECIA' => 'GRECIA', 'NICOYA' => 'NICOYA','TODAS' => 'TODAS'], null, [
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
                                <i class="fa fa-calendar"></i> @lang('CXC por vencimiento')
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="cxc_by_month">
                            <button id="generate_report_cxc_venc" class="btn btn-primary mb-3 text-center">Generar
                                Reporte</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="view_cxc_venc_report_modal" tabindex="-1" role="dialog"
            aria-labelledby="gridSystemModalLabel"></div>
    </section>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '#generate_report_cxc_venc', function() {
                if (!$('#plazo').val()) {
                    swal({
                        title: "Debe seleccionar el plazo",
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
                        if (ok) $('#plazo').focus();
                    });
                    return;
                }

                let url = '{{ route('cxc-venc.generateReport') }}';
                let data = {
                    plazo: $('#plazo').val() ? $('#plazo').val() : null,
                    location_id: $('#location_id').val()
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
                        $('#view_cxc_venc_report_modal').html(result).modal('show');
                        __currency_convert_recursively($('#view_cxc_venc_report_modal'));
                    },
                    error: function(xhr) {
                        console.error('Error al generar el reporte:', xhr?.responseText || xhr);
                    }
                });
            });
        });

        function printThisCxc() {
            $("#cxcModalReportVenc").printThis({
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

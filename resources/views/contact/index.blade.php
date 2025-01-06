@extends('layouts.app')
@section('title', __('lang_v1.' . $type . 's'))
@php
    $api_key = env('GOOGLE_MAP_API_KEY');
@endphp
@if (!empty($api_key))
    @section('css')
        @include('contact.partials.google_map_styles')
    @endsection
@endif
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> @lang('lang_v1.' . $type . 's')
        </h1>
        <!-- <ol class="breadcrumb">
                                                    <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
                                                    <li class="active">Here</li>
                                                </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        <input type="hidden" value="{{ $type }}" id="contact_type">
        @if ($type == 'customer')
            <div class="row">
                <div class="col-md-12">
                    @component('components.filters', ['title' => __('report.filters')])
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('customer_filter', __('Filtrar Clientes Por') . ':') !!}
                                {!! Form::select(
                                    'customer_filter',
                                    ['1' => __('Todos '), '2' => __('Con saldo'), '3' => __('SIn saldo')],
                                    null,
                                    [
                                        'class' => 'form-control select2',
                                        'style' => 'width:100%',
                                        'id' => 'customer_filter',
                                    ],
                                ) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('mes_atraso', __('Meses de atraso') . ':') !!}
                                {!! Form::select(
                                    'mes_atraso',
                                    [
                                        '0' => __('Todos'),
                                        '1' => __('1 mes'),
                                        '2' => __('2 meses'),
                                        '3' => __('3 meses'),
                                        '4' => __('4 meses'),
                                        '5' => __('5 meses'),
                                        '6' => __('6 meses'),
                                        '7' => __('7 meses'),
                                        '8' => __('8 meses'),
                                        '9' => __('9 meses'),
                                        '10' => __('10 meses'),
                                        '11' => __('11 meses'),
                                        '12' => __('12 meses'),
                                    ],
                                    null,
                                    [
                                        'class' => 'form-control select2',
                                        'style' => 'width:100%',
                                        'id' => 'mes_atraso',
                                    ],
                                ) !!}
                            </div>
                            <p class="help-block">Al seleccionar todos no tomará en cuenta los meses de atraso</p>
                        </div>
                        {{--   <div id="div_date_report_start" class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('date_report_start', __('Fecha Inicial') . ':') !!}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    {!! Form::date('date_report_start', @format_datetime('now'), [
                                        'class' => 'form-control',
                                        'id' => 'date_report_start',
                                    ]) !!}
                                </div>
                            </div>
                            <p class="help-block">Campo no requerido</p>
                        </div>
                        <div id="div_date_report_end" class="col-md-3">
                            <div class="form-group">
                                {!! Form::label('date_report_end', __('Fecha Final') . ':') !!}
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    {!! Form::date('date_report_end', @format_datetime('now'), [
                                        'class' => 'form-control',
                                        'id' => 'date_report_end',
                                    ]) !!}
                                </div>
                            </div>
                        </div> --}}
                    @endcomponent
                </div>
            </div>
        @endif
        @component('components.widget', [
            'class' => 'box-primary',
            'title' => __('contact.all_your_contact', ['contacts' => __('lang_v1.' . $type . 's')]),
        ])
            @if (auth()->user()->can('supplier.create') ||
                    auth()->user()->can('customer.create') ||
                    auth()->user()->can('guarantor.create'))
                @slot('tool')
                    <div class="box-tools">
                        <button type="button" class="btn btn-block btn-primary btn-modal"
                            data-href="{{ action('ContactController@create', ['type' => $type]) }}" data-container=".contact_modal">
                            <i class="fa fa-plus"></i> @lang('messages.add')</button>
                    </div>
                @endslot
            @endif
            @if (auth()->user()->can('supplier.view') ||
                    auth()->user()->can('customer.view') ||
                    auth()->user()->can('guarantor.view'))
                @if ($type == 'customer')
                    <button id="generate_customer_exc" class="btn btn-primary">Generar Excel</button>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="contact_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.action')</th>
                                <th>@lang('lang_v1.contact_id')</th>
                                <th>@lang('contact.name')</th>
                                <th>@lang('business.email')</th>
                                <th>@lang('contact.mobile')</th>
                                @if ($type == 'customer')
                                    <th>Total</th>
                                    <th>Deuda a pagar</th>
                                    <th>Pagado</th>
                                    <th>Ultimo pago</th>
                                @endif
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @if ($type == 'customer')
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        @endcomponent

        <div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>
        <div class="modal fade pay_contact_due_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->
@stop
@section('javascript')
    @if (!empty($api_key))
        <script>
            // This example adds a search box to a map, using the Google Place Autocomplete
            // feature. People can enter geographical searches. The search box will return a
            // pick list containing a mix of places and predicted search terms.

            // This example requires the Places library. Include the libraries=places
            // parameter when you first load the API. For example:
            // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">


            function initAutocomplete() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {
                        lat: -33.8688,
                        lng: 151.2195
                    },
                    zoom: 10,
                    mapTypeId: 'roadmap'
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                        map.setCenter(initialLocation);
                    });
                }


                // Create the search box and link it to the UI element.
                var input = document.getElementById('shipping_address');
                var searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                // Bias the SearchBox results towards current map's viewport.
                map.addListener('bounds_changed', function() {
                    searchBox.setBounds(map.getBounds());
                });

                var markers = [];
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener('places_changed', function() {
                    var places = searchBox.getPlaces();

                    if (places.length == 0) {
                        return;
                    }

                    // Clear out the old markers.
                    markers.forEach(function(marker) {
                        marker.setMap(null);
                    });
                    markers = [];

                    // For each place, get the icon, name and location.
                    var bounds = new google.maps.LatLngBounds();
                    places.forEach(function(place) {
                        if (!place.geometry) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        var icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25)
                        };

                        // Create a marker for each place.
                        markers.push(new google.maps.Marker({
                            map: map,
                            icon: icon,
                            title: place.name,
                            position: place.geometry.location
                        }));

                        //set position field value
                        var lat_long = [place.geometry.location.lat(), place.geometry.location.lng()]
                        $('#position').val(lat_long);

                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                    });
                    map.fitBounds(bounds);
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&libraries=places" async defer></script>
        <script type="text/javascript">
            $(document).on('shown.bs.modal', '.contact_modal', function(e) {
                initAutocomplete();
            });
        </script>
    @endif

@endsection

@extends('layouts.app')
@php
    $heading = !empty($module_category_data['heading']) ? $module_category_data['heading'] : __('category.categories');
@endphp
@section('title', $heading)

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{$heading }}
        
        @if(isset($module_category_data['heading_tooltip']))
            @show_tooltip($module_category_data['heading_tooltip'])
        @endif
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @php
        $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;
    @endphp
    <input type="hidden" id="category_type" value="{{request()->get('type')}}">
    @component('components.widget', ['class' => 'box-primary'])
        @can('category.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{action('TaxonomyController@create')}}?type={{request()->get('type')}}" 
                    data-container=".category_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('category.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="category_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>@if(!empty($module_category_data['taxonomy_label'])) {{$module_category_data['taxonomy_label']}} @else Categoria @endif</th>
                            <th>@lang( 'lang_v1.description' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade category_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
@includeIf('taxonomy.taxonomies_js')
@endsection

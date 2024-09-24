@php
    $colspan = 15;
    $custom_labels = json_decode(session('business.custom_labels'), true);
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-striped ajax_view hide-footer" id="product_table">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>
                <th><input type="checkbox" id="select-all-row"></th>
                <th>&nbsp;</th>
                <th>@lang('Vehículo')</th>
                <th>@lang('product.category')</th>
                <th>@lang('product.brand')</th>
                <th>@lang('vehiculos.model')</th>
                <th>@lang('vehiculos.color')</th>
                <th>@lang('vehiculos.vin')</th>
                <th>@lang('vehiculos.placa')</th>
                <th>@lang('vehiculos.dua')</th>
                <th>@lang('vehiculos.comprado_a')</th>
                <th>@lang('vehiculos.created_at')</th>
                <th>@lang('product.sku')</th>
                <th style="display: none;"></th>
                


            </tr>
        </thead>

    </table>


</div>

<div class="row" style="margin-left: 5px;">
    <tr>
        <td colspan="{{ $colspan }}">
            <div style="display: flex; width: 100%;">
                @can('product.delete')
                    {!! Form::open([
                        'url' => action('ProductController@massDestroy'),
                        'method' => 'post',
                        'id' => 'mass_delete_form',
                    ]) !!}
                    {!! Form::hidden('selected_rows', null, ['id' => 'selected_rows']) !!}
                    {!! Form::submit(__('lang_v1.delete_selected'), ['class' => 'btn btn-xs btn-danger', 'id' => 'delete-selected']) !!}
                    {!! Form::close() !!}
                @endcan
                @can('product.update')
                    {!! Form::open(['url' => action('ProductController@bulkEdit'), 'method' => 'post', 'id' => 'bulk_edit_form']) !!}
                    {!! Form::hidden('selected_products', null, ['id' => 'selected_products_for_edit']) !!}
                    <button type="submit" class="btn btn-xs btn-primary" id="edit-selected"> <i
                            class="fa fa-edit"></i>{{ __('lang_v1.bulk_edit') }}</button>
                    {!! Form::close() !!}
                @endcan

                {!! Form::open([
                    'url' => action('ProductController@massDeactivate'),
                    'method' => 'post',
                    'id' => 'mass_deactivate_form',
                ]) !!}
                {!! Form::hidden('selected_products', null, ['id' => 'selected_products']) !!}
                {!! Form::submit('Desactivar seleccionado', ['class' => 'btn btn-xs btn-warning', 'id' => 'deactivate-selected']) !!}
                {!! Form::close() !!} @show_tooltip('Desactivar los vehículos seleccionados')
            </div>
        </td>
    </tr>
</div>

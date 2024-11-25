<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Agregar vehículo</h4>
        </div>
        {!! Form::open([
            'url' => action('ProductController@store'),
            'id' => 'product_add_form',
            'class' => 'product_form_modal ',
            'files' => true,
        ]) !!}
        <div class="modal-body">
            @component('components.widget', ['class' => 'box-primary'])
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('name', __('vehiculos.product_name') . ':*') !!}
                            {!! Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('vehiculos.product_name'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4 @if (!session('business.enable_brand')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('brand_id', __('product.brand') . ':') !!}
                            <div class="input-group">
                                {!! Form::select(
                                    'brand_id',
                                    $brands,
                                    !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null,
                                    ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                                ) !!}
                                <span class="input-group-btn">
                                    <button type="button" @if (!auth()->user()->can('brand.create')) disabled @endif
                                        class="btn btn-default bg-white btn-flat btn-modal"
                                        data-href="{{ action('BrandController@create', ['quick_add' => true]) }}"
                                        title="@lang('brand.add_brand')" data-container=".view_modal"><i
                                            class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('color', __('Color') . ':*') !!}
                            {!! Form::text('color', !empty($duplicate_product->color) ? $duplicate_product->color : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Color'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('model', __('Modelo') . ':*') !!}
                            {!! Form::text('model', !empty($duplicate_product->model) ? $duplicate_product->model : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Modelo'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('bin', __('VIN') . ':*') !!}
                            {!! Form::text('bin', !empty($duplicate_product->bin) ? $duplicate_product->bin : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('VIN'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('placa', __('Placa') . ':*') !!}
                            {!! Form::text('placa', !empty($duplicate_product->placa) ? $duplicate_product->placa : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Placa'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('dua', __('Dua') . ':*') !!}
                            {!! Form::text('dua', !empty($duplicate_product->dua) ? $duplicate_product->dua : null, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Dua'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('comprado_a', __('Comprado a') . ':') !!}
                            {!! Form::text('comprado_a', !empty($duplicate_product->comprado_a) ? $duplicate_product->comprado_a : null, [
                                'class' => 'form-control',
                                'placeholder' => __('Comprado a'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('kilometraje', __('Kilometraje') . ':') !!}
                            {!! Form::text('kilometraje', !empty($duplicate_product->kilometraje) ? $duplicate_product->kilometraje : null, [
                                'class' => 'form-control',
                                'placeholder' => __('Kilometraje'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('traccion', __('Tracción') . ':') !!}
                            {!! Form::text('traccion', !empty($duplicate_product->traccion) ? $duplicate_product->traccion : null, [
                                'class' => 'form-control',
                                'placeholder' => __('Tracción'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('combustible', __('Tipo de combustible') . ':') !!}
                            {!! Form::text('combustible', !empty($duplicate_product->combustible) ? $duplicate_product->combustible : null, [
                                'class' => 'form-control',
                                'placeholder' => __('Tipo de combustible'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('motor', __('Motor') . ':') !!}
                            {!! Form::text('motor', !empty($duplicate_product->motor) ? $duplicate_product->motor : null, [
                                'class' => 'form-control',
                                'placeholder' => __('Motor'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('monto_venta', __('Monto venta') . ':') !!}
                            {!! Form::text('monto_venta', !empty($duplicate_product->monto_venta) ? $duplicate_product->monto_venta : null, [
                                'class' => 'form-control precio',
                                'placeholder' => __('Monto'),
                            ]) !!}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-4 @if (!session('business.enable_category')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('category_id', __('product.category') . ':') !!}
                            {!! Form::select(
                                'category_id',
                                $categories,
                                !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null,
                                ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'],
                            ) !!}
                        </div>
                    </div>

                    @php
                        $default_location = null;
                        if (count($business_locations) == 1) {
                            $default_location = array_key_first($business_locations->toArray());
                        }
                    @endphp
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('product_locations', __('business.business_locations') . ':') !!} @show_tooltip(__('lang_v1.product_location_help'))
                            {!! Form::select('product_locations[]', $business_locations, $default_location, [
                                'class' => 'form-control select2',
                                'multiple',
                                'id' => 'product_locations',
                            ]) !!}
                        </div>
                    </div>



                    <div class="clearfix"></div>

                   
                    <div class="form-group col-md-3">
                        {!! Form::label('state', 'Se encuentra en' . ':') !!}
                        {!! Form::select('state', ['0' => 'Exhibición', '1' => 'Mantenimiento', '2' => 'Vendido'], null, [
                            'class' => 'form-control',
                            'id' => 'gender',
                            'placeholder' => __('messages.please_select'),
                        ]) !!}
                    </div>
                    @if (!empty($common_settings['enable_product_warranty']))
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('warranty_id', __('lang_v1.warranty') . ':') !!}
                                {!! Form::select('warranty_id', $warranties, null, [
                                    'class' => 'form-control select2',
                                    'placeholder' => __('messages.please_select'),
                                ]) !!}
                            </div>
                        </div>
                    @endif
                    <!-- include module fields -->
                    @if (!empty($pos_module_data))
                        @foreach ($pos_module_data as $key => $value)
                            @if (!empty($value['view_path']))
                                @includeIf($value['view_path'], ['view_data' => $value['view_data']])
                            @endif
                        @endforeach
                    @endif
                    <div class="clearfix"></div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            {!! Form::label('product_description', __('Descripción') . ':') !!}
                            {!! Form::textarea(
                                'product_description',
                                !empty($duplicate_product->product_description) ? $duplicate_product->product_description : null,
                                ['class' => 'form-control'],
                            ) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::label('image', __('Imagen del vehículo') . ':') !!}
                            {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*']) !!}
                            <small>
                                <p class="help-block">@lang('purchase.max_file_size', ['size' => config('constants.document_size_limit') / 1000000]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p>
                            </small>
                        </div>
                    </div>
                </div>
            @endcomponent
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary save_vehicle_new" id="save_vehicle">@lang('Guardar nuevo vehículo')</button>
        </div>
        {!! Form::close() !!}
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

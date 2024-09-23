<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">

        @php

            if (isset($update_action)) {
                $url = $update_action;
                $customer_groups = [];
                $opening_balance = 0;
                $lead_users = $contact->leadUsers->pluck('id');
            } else {
                $url = action('ContactController@update', [$contact->id]);
                $sources = [];
                $life_stages = [];
                $users = [];
                $lead_users = [];
            }
        @endphp

        {!! Form::open(['url' => $url, 'method' => 'PUT', 'id' => 'contact_edit_form']) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Editar</h4>
        </div>

        <div class="modal-body">

            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('type', __('contact.contact_type') . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            {!! Form::select('type', $types, $contact->type, [
                                'class' => 'form-control',
                                'id' => 'contact_type',
                                'placeholder' => __('messages.please_select'),
                                'required',
                            ]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">

                        <label for="product_custom_field2">Identificación:</label>

                        <input class="form-control" required placeholder="Identificación" name="identificacion" value="{{$contact->identificacion}}"
                            type="text" id="identificacion">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', 'Razón Social' . ':*') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            {!! Form::text('name', $contact->name, ['class' => 'form-control', 'placeholder' => 'Razón Social', 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('contact_id', __('lang_v1.contact_id') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-id-badge"></i>
                            </span>
                            <input type="hidden" id="hidden_id" value="{{ $contact->id }}">
                            {!! Form::text('contact_id', $contact->contact_id, [
                                'class' => 'form-control',
                                'placeholder' => __('lang_v1.contact_id'),
                            ]) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>


                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::label('tipo', 'Tipo' . ':') !!}
                        <div class="input-group" style="width: 100%;">

                            {!! Form::select('tipo', ['j' => 'Juridica', 'f' => 'Fisica'], $type, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4 customer_fields">
                    <div class="form-group">
                        {!! Form::label('credit_limit', __('lang_v1.credit_limit') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa-money-bill-alt"></i>
                            </span>
                            {!! Form::text('credit_limit', @num_format($contact->credit_limit), ['class' => 'form-control input_number']) !!}
                        </div>
                        <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
                    </div>
                </div>

                <div class="col-md-12">
                    <hr />
                </div>
                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for="product_custom_field2">Cantón*:</label>
                        <input class="form-control" value="{{ $contact->city }}" required placeholder="Cantón"
                            name="city" type="text" id="city">
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for="product_custom_field2">Distrito*:</label>
                        <input class="form-control" value="{{ $contact->bairro }}" required placeholder="Distrito"
                            name="bairro" type="text" id="bairro">
                    </div>
                </div>

                <div class="col-md-4 ">
                    <div class="form-group">
                        <label for="product_custom_field2">Barrio*:</label>
                        <input class="form-control" value="{{ $contact->rua }}" required placeholder="Barrio"
                            name="rua" type="text" id="rua">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('landmark', __('Dirección Exacta') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            {!! Form::text('landmark', $contact->landmark, [
                                'class' => 'form-control',
                                'placeholder' => __('Dirección Exacta'),
                            ]) !!}
                        </div>
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('email', __('business.email') . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            {!! Form::text('email', $contact->email, ['class' => 'form-control', 'placeholder' => __('business.email')]) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('mobile', 'Celular' . ':') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-mobile"></i>
                            </span>
                            {!! Form::text('mobile', $contact->mobile, ['class' => 'form-control', 'placeholder' => 'Celular']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('landline', 'Teléfono Fijo:') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            {!! Form::text('landline', $contact->landline, ['class' => 'form-control', 'placeholder' => 'Teléfono Fijo']) !!}
                        </div>
                    </div>
                </div>


                <div class="clearfix"></div>
                <div class="col-md-12">
                    <hr>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('state', 'Estado Civil:') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-heart"></i>
                            </span>
                            {!! Form::text('state', $contact->state, ['class' => 'form-control', 'placeholder' => 'Estado Civil']) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('position', 'Puesto:') !!}
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-life-ring"></i>
                            </span>
                            {!! Form::text('position', $contact->position, ['class' => 'form-control', 'placeholder' => 'Puesto']) !!}
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-8 col-md-offset-2" >
      <strong>{{ __('lang_v1.shipping_address') }}</strong><br>
      {!! Form::text('shipping_address', $contact->shipping_address, [
          'class' => 'form-control',
          'placeholder' => __('lang_v1.search_address'),
          'id' => 'shipping_address',
      ]) !!}
      <div id="map"></div>
    </div> -->

            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

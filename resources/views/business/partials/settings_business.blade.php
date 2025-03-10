<div class="pos-tab-content active">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('name',__('business.business_name') . ':*') !!}
                {!! Form::text('name', $business->name, ['class' => 'form-control', 'required',
                'placeholder' => __('business.business_name')]); !!}
            </div>
        </div>{{-- 
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('razao_social',__('business.business_razao') . ':*') !!}
                {!! Form::text('razao_social', $business->razao_social, ['class' => 'form-control', 'required',
                'placeholder' => __('business.business_razao'), 'minlength' => '10']); !!}

            </div>
        </div> --}}

{{--         <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('start_date', 'Data de início:') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    
                    {!! Form::text('start_date', @format_date($business->start_date), ['class' => 'form-control start-date-picker','placeholder' => __('business.start_date'), 'readonly']); !!}
                </div>
            </div>
        </div> --}}
{{--         <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_profit_percent', __('business.default_profit_percent') . ':*') !!} @show_tooltip(__('tooltip.default_profit_percent'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    {!! Form::text('default_profit_percent', @num_format($business->default_profit_percent), ['class' => 'form-control input_number']); !!}
                </div>
            </div>
        </div> --}}
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('currency_id', __('business.currency') . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    {!! Form::select('currency_id', $currencies, $business->currency_id, ['class' => 'form-control select2','placeholder' => __('business.currency'), 'required']); !!}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('currency_symbol_placement', __('lang_v1.currency_symbol_placement') . ':') !!}
                {!! Form::select('currency_symbol_placement', ['before' => __('lang_v1.before_amount'), 'after' => __('lang_v1.after_amount')], $business->currency_symbol_placement, ['class' => 'form-control select2', 'required']); !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('time_zone', __('business.time_zone') . ':') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-clock"></i>
                    </span>
                    {!! Form::select('time_zone', $timezone_list, $business->time_zone, ['class' => 'form-control select2', 'required']); !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('business_logo', __('business.upload_logo') . ':') !!}
                {!! Form::file('business_logo', ['accept' => 'image/jpeg']); !!}
                <p class="help-block"><i> @lang('business.logo_help')</i></p>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('fy_start_month', __('business.fy_start_month') . ':') !!} @show_tooltip(__('tooltip.fy_start_month'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    {!! Form::select('fy_start_month', $months, $business->fy_start_month, ['class' => 'form-control select2', 'required']); !!}
                </div>
            </div>
        </div> --}}
        {{-- <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('accounting_method', __('business.accounting_method') . ':*') !!}
                @show_tooltip(__('tooltip.accounting_method'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calculator"></i>
                    </span>
                    {!! Form::select('accounting_method', $accounting_methods, $business->accounting_method, ['class' => 'form-control select2', 'required']); !!}
                </div>
            </div>
        </div> --}}
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('transaction_edit_days', __('business.transaction_edit_days') . ':*') !!}
                @show_tooltip(__('tooltip.transaction_edit_days'))
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-edit"></i>
                    </span>
                    {!! Form::number('transaction_edit_days', $business->transaction_edit_days, ['class' => 'form-control','placeholder' => __('business.transaction_edit_days'), 'required']); !!}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('date_format', __('lang_v1.date_format') . ':*') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    {!! Form::select('date_format', $date_formats, $business->date_format, ['class' => 'form-control select2', 'required']); !!}
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('time_format', __('lang_v1.time_format') . ':*') !!}
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-clock"></i>
                    </span>
                    {!! Form::select('time_format', [12 => __('lang_v1.12_hour'), 24 => __('lang_v1.24_hour')], $business->time_format, ['class' => 'form-control select2', 'required']); !!}
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                {!! Form::label('tipo', 'Tipo' . ':') !!}
                <div class="input-group" style="width: 100%;">

                    {!! Form::select('tipo', ['j' => 'Juridica', 'f' => 'Fisica'], $pessoa, ['class' => 'form-control']); !!}
                </div>
            </div>
        </div>

       {{--  <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('cnpj', 'CPF/CNPJ' . ':*') !!}
                {!! Form::text('cnpj', $business->cnpj, ['class' => 'form-control', 'required', 'data-mask="00.000.000/0000-00"', 
                'placeholder' => 'CNPJ']); !!}
            </div>
        </div> --}}

       {{--  <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('ie', 'IE' . ':*') !!}
                {!! Form::text('ie', $business->ie, ['class' => 'form-control', 'required',
                'placeholder' => 'IE']); !!}
            </div>
        </div> --}}

        <div class="clearfix"></div>
       {{--  <div class="col-sm-4">
            <div class="form-group">
                <label for="certificado">Certificado:</label>
                <input name="certificado" type="file" id="certificado">
                <p class="help-block"><i>O Certificado anterior (se existir) será substituído</i></p>
            </div>
        </div> --}}

        @if($infoCertificado != null && $infoCertificado != -1)
        <h5>Serial: <strong>{{$infoCertificado['serial']}}</strong></h5>
        <h5>Expiração: <strong>{{$infoCertificado['expiracao']}}</strong></h5>
        <h5>ID: <strong>{{$infoCertificado['id']}}</strong></h5>
        @endif

        @if($infoCertificado == -1)
        <h5 style="color: red">Erro na leitura do certificado, verifique a senha e outros dados, e realize o upload novamente!!</h5>
        @endif


        <div class="clearfix"></div>

        {{-- <div class="col-sm-2">
            <div class="form-group">
                {!! Form::label('senha_certificado', 'Contraseña' . ':*') !!}
                {!! Form::text('senha_certificado', '', ['class' => 'form-control',
                'placeholder' => 'Contraseña']); !!}
            </div>
        </div>
 --}}
        <div class="clearfix"></div>

       {{--  <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('rua', 'Calle' . ':*') !!}
                {!! Form::text('rua', $business->rua, ['class' => 'form-control', 'required',
                'placeholder' => 'Calle']); !!}
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                {!! Form::label('numero', 'Número' . ':*') !!}
                {!! Form::text('numero', $business->numero, ['class' => 'form-control', 'required',
                'placeholder' => 'Número']); !!}
            </div>
        </div>
        <div class="col-md-4 customer_fields">
            <div class="form-group">
                {!! Form::label('cidade_id', 'Ciudades:*') !!}
                {!! Form::select('cidade_id', $cities, $business->cidade_id, ['class' => 'form-control select2', 'required']); !!}
            </div>
        </div> --}}

        <div class="clearfix"></div>

       {{--  <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('bairro', 'Barrio' . ':*') !!}
                {!! Form::text('bairro', $business->bairro, ['class' => 'form-control', 'required',
                'placeholder' => 'Barrio']); !!}
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('cep', 'CEP' . ':*') !!}
                {!! Form::text('cep', $business->cep, ['class' => 'form-control', 'required', 'data-mask="00000-000"',
                'placeholder' => 'CEP']); !!}
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('telefone', 'Teléfono' . ':*') !!}
                {!! Form::text('telefone', $business->telefone, ['class' => 'form-control', 'required', 'data-mask="00 000000000"',
                'placeholder' => 'Teléfono']); !!}
            </div>
        </div> --}}


        {{-- <div class="col-md-3">
            <div class="form-group">

                {!! Form::label('regime', 'Regime' . ':') !!}
                {!! Form::select('regime', ['1' => 'Simples', '3' => 'Normal'], $business->regime, ['class' => 'form-control select2', 'required']); !!}
            </div>
        </div> --}}

        <div class="clearfix"></div>
{{-- 
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('ultimo_numero_nfe', 'Ultimo Núm. NFe' . ':*') !!}
                {!! Form::text('ultimo_numero_nfe', $business->ultimo_numero_nfe, ['class' => 'form-control', 'required',
                'placeholder' => 'Ultimo Núm. NFe']); !!}
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('ultimo_numero_nfce', 'Ultimo Núm. NFCe' . ':*') !!}
                {!! Form::text('ultimo_numero_nfce', $business->ultimo_numero_nfce, ['class' => 'form-control', 'required',
                'placeholder' => 'Ultimo Núm. NFCe']); !!}
            </div>
        </div> --}}

       {{--  <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('inscricao_municipal', 'Inscrição municipal' . ':*') !!}
                {!! Form::text('inscricao_municipal', $business->inscricao_municipal, ['class' => 'form-control',
                'placeholder' => 'Inscrição municipal']); !!}
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('numero_serie_nfe', 'Núm. Série NFe' . ':*') !!}
                {!! Form::text('numero_serie_nfe', $business->numero_serie_nfe, ['class' => 'form-control', 'required',
                'placeholder' => 'Núm. Série NF-e']); !!}
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('numero_serie_nfce', 'Núm. Série NFCe' . ':*') !!}
                {!! Form::text('numero_serie_nfce', $business->numero_serie_nfce, ['class' => 'form-control', 'required',
                'placeholder' => 'Núm. Série NFC-e']); !!}
            </div>
        </div> --}}

        <div class="col-md-3">
            <div class="form-group">

                {!! Form::label('ambiente', 'Ambiente' . ':') !!}
                {!! Form::select('ambiente', ['1' => 'Producción', '2' => 'Aprobado'], $business->ambiente, ['class' => 'form-control select2', 'required']); !!}
            </div>
        </div>

        <div class="clearfix"></div>

       {{--  <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('csc_id', 'CSCID' . ':*') !!}
                {!! Form::text('csc_id', $business->csc_id, ['class' => 'form-control', 'required', 
                'placeholder' => 'CSCID']); !!}
            </div>
        </div>

        <div class="col-sm-5">
            <div class="form-group">
                {!! Form::label('csc', 'CSC' . ':*') !!}
                {!! Form::text('csc', $business->csc, ['class' => 'form-control', 'required', 
                'placeholder' => 'CSC']); !!}
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('aut_xml', 'AUT XML' . ':') !!}
                {!! Form::text('aut_xml', $business->aut_xml, ['class' => 'form-control cnpj', 
                'placeholder' => 'AUT XML']); !!}
            </div>
        </div> --}}

    </div>
</div>


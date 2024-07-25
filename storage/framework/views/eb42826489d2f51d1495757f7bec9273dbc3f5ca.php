<div class="pos-tab-content active">
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('name',__('business.business_name') . ':*'); ?>

                <?php echo Form::text('name', $business->name, ['class' => 'form-control', 'required',
                'placeholder' => __('business.business_name')]); ?>

            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('razao_social',__('business.business_razao') . ':*'); ?>

                <?php echo Form::text('razao_social', $business->razao_social, ['class' => 'form-control', 'required',
                'placeholder' => __('business.business_razao'), 'minlength' => '10']); ?>


            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('start_date', 'Data de início:'); ?>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    
                    <?php echo Form::text('start_date', \Carbon::createFromTimestamp(strtotime($business->start_date))->format(session('business.date_format')), ['class' => 'form-control start-date-picker','placeholder' => __('business.start_date'), 'readonly']); ?>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('default_profit_percent', __('business.default_profit_percent') . ':*'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.default_profit_percent') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <?php echo Form::text('default_profit_percent', number_format($business->default_profit_percent, 2, ',', '.'), ['class' => 'form-control input_number']); ?>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('currency_id', __('business.currency') . ':'); ?>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-money-bill-alt"></i>
                    </span>
                    <?php echo Form::select('currency_id', $currencies, $business->currency_id, ['class' => 'form-control select2','placeholder' => __('business.currency'), 'required']); ?>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo Form::label('currency_symbol_placement', __('lang_v1.currency_symbol_placement') . ':'); ?>

                <?php echo Form::select('currency_symbol_placement', ['before' => __('lang_v1.before_amount'), 'after' => __('lang_v1.after_amount')], $business->currency_symbol_placement, ['class' => 'form-control select2', 'required']); ?>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo Form::label('time_zone', __('business.time_zone') . ':'); ?>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-clock"></i>
                    </span>
                    <?php echo Form::select('time_zone', $timezone_list, $business->time_zone, ['class' => 'form-control select2', 'required']); ?>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('business_logo', __('business.upload_logo') . ':'); ?>

                <?php echo Form::file('business_logo', ['accept' => 'image/jpeg']); ?>

                <p class="help-block"><i> <?php echo app('translator')->get('business.logo_help'); ?></i></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <?php echo Form::label('fy_start_month', __('business.fy_start_month') . ':'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.fy_start_month') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php echo Form::select('fy_start_month', $months, $business->fy_start_month, ['class' => 'form-control select2', 'required']); ?>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('accounting_method', __('business.accounting_method') . ':*'); ?>

                <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.accounting_method') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calculator"></i>
                    </span>
                    <?php echo Form::select('accounting_method', $accounting_methods, $business->accounting_method, ['class' => 'form-control select2', 'required']); ?>

                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('transaction_edit_days', __('business.transaction_edit_days') . ':*'); ?>

                <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.transaction_edit_days') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-edit"></i>
                    </span>
                    <?php echo Form::number('transaction_edit_days', $business->transaction_edit_days, ['class' => 'form-control','placeholder' => __('business.transaction_edit_days'), 'required']); ?>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('date_format', __('lang_v1.date_format') . ':*'); ?>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <?php echo Form::select('date_format', $date_formats, $business->date_format, ['class' => 'form-control select2', 'required']); ?>

                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('time_format', __('lang_v1.time_format') . ':*'); ?>

                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fas fa-clock"></i>
                    </span>
                    <?php echo Form::select('time_format', [12 => __('lang_v1.12_hour'), 24 => __('lang_v1.24_hour')], $business->time_format, ['class' => 'form-control select2', 'required']); ?>

                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <?php echo Form::label('tipo', 'Tipo' . ':'); ?>

                <div class="input-group" style="width: 100%;">

                    <?php echo Form::select('tipo', ['j' => 'Juridica', 'f' => 'Fisica'], $pessoa, ['class' => 'form-control']); ?>

                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('cnpj', 'CPF/CNPJ' . ':*'); ?>

                <?php echo Form::text('cnpj', $business->cnpj, ['class' => 'form-control', 'required', 'data-mask="00.000.000/0000-00"', 
                'placeholder' => 'CNPJ']); ?>

            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <?php echo Form::label('ie', 'IE' . ':*'); ?>

                <?php echo Form::text('ie', $business->ie, ['class' => 'form-control', 'required',
                'placeholder' => 'IE']); ?>

            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="certificado">Certificado:</label>
                <input name="certificado" type="file" id="certificado">
                <p class="help-block"><i>O Certificado anterior (se existir) será substituído</i></p>
            </div>
        </div>

        <?php if($infoCertificado != null && $infoCertificado != -1): ?>
        <h5>Serial: <strong><?php echo e($infoCertificado['serial'], false); ?></strong></h5>
        <h5>Expiração: <strong><?php echo e($infoCertificado['expiracao'], false); ?></strong></h5>
        <h5>ID: <strong><?php echo e($infoCertificado['id'], false); ?></strong></h5>
        <?php endif; ?>

        <?php if($infoCertificado == -1): ?>
        <h5 style="color: red">Erro na leitura do certificado, verifique a senha e outros dados, e realize o upload novamente!!</h5>
        <?php endif; ?>


        <div class="clearfix"></div>

        <div class="col-sm-2">
            <div class="form-group">
                <?php echo Form::label('senha_certificado', 'Senha' . ':*'); ?>

                <?php echo Form::text('senha_certificado', '', ['class' => 'form-control',
                'placeholder' => 'Senha']); ?>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-6">
            <div class="form-group">
                <?php echo Form::label('rua', 'Rua' . ':*'); ?>

                <?php echo Form::text('rua', $business->rua, ['class' => 'form-control', 'required',
                'placeholder' => 'Rua']); ?>

            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <?php echo Form::label('numero', 'Número' . ':*'); ?>

                <?php echo Form::text('numero', $business->numero, ['class' => 'form-control', 'required',
                'placeholder' => 'Número']); ?>

            </div>
        </div>
        <div class="col-md-4 customer_fields">
            <div class="form-group">
                <?php echo Form::label('cidade_id', 'Cidade:*'); ?>

                <?php echo Form::select('cidade_id', $cities, $business->cidade_id, ['class' => 'form-control select2', 'required']); ?>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('bairro', 'Bairro' . ':*'); ?>

                <?php echo Form::text('bairro', $business->bairro, ['class' => 'form-control', 'required',
                'placeholder' => 'Bairro']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('cep', 'CEP' . ':*'); ?>

                <?php echo Form::text('cep', $business->cep, ['class' => 'form-control', 'required', 'data-mask="00000-000"',
                'placeholder' => 'CEP']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('telefone', 'Telefone' . ':*'); ?>

                <?php echo Form::text('telefone', $business->telefone, ['class' => 'form-control', 'required', 'data-mask="00 000000000"',
                'placeholder' => 'Telefone']); ?>

            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group">

                <?php echo Form::label('regime', 'Regime' . ':'); ?>

                <?php echo Form::select('regime', ['1' => 'Simples', '3' => 'Normal'], $business->regime, ['class' => 'form-control select2', 'required']); ?>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('ultimo_numero_nfe', 'Ultimo Núm. NFe' . ':*'); ?>

                <?php echo Form::text('ultimo_numero_nfe', $business->ultimo_numero_nfe, ['class' => 'form-control', 'required',
                'placeholder' => 'Ultimo Núm. NFe']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('ultimo_numero_nfce', 'Ultimo Núm. NFCe' . ':*'); ?>

                <?php echo Form::text('ultimo_numero_nfce', $business->ultimo_numero_nfce, ['class' => 'form-control', 'required',
                'placeholder' => 'Ultimo Núm. NFCe']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('inscricao_municipal', 'Inscrição municipal' . ':*'); ?>

                <?php echo Form::text('inscricao_municipal', $business->inscricao_municipal, ['class' => 'form-control',
                'placeholder' => 'Inscrição municipal']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('numero_serie_nfe', 'Núm. Série NFe' . ':*'); ?>

                <?php echo Form::text('numero_serie_nfe', $business->numero_serie_nfe, ['class' => 'form-control', 'required',
                'placeholder' => 'Núm. Série NF-e']); ?>

            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('numero_serie_nfce', 'Núm. Série NFCe' . ':*'); ?>

                <?php echo Form::text('numero_serie_nfce', $business->numero_serie_nfce, ['class' => 'form-control', 'required',
                'placeholder' => 'Núm. Série NFC-e']); ?>

            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">

                <?php echo Form::label('ambiente', 'Ambiente' . ':'); ?>

                <?php echo Form::select('ambiente', ['1' => 'Produção', '2' => 'Homologação'], $business->ambiente, ['class' => 'form-control select2', 'required']); ?>

            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('csc_id', 'CSCID' . ':*'); ?>

                <?php echo Form::text('csc_id', $business->csc_id, ['class' => 'form-control', 'required', 
                'placeholder' => 'CSCID']); ?>

            </div>
        </div>

        <div class="col-sm-5">
            <div class="form-group">
                <?php echo Form::label('csc', 'CSC' . ':*'); ?>

                <?php echo Form::text('csc', $business->csc, ['class' => 'form-control', 'required', 
                'placeholder' => 'CSC']); ?>

            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <?php echo Form::label('aut_xml', 'AUT XML' . ':'); ?>

                <?php echo Form::text('aut_xml', $business->aut_xml, ['class' => 'form-control cnpj', 
                'placeholder' => 'AUT XML']); ?>

            </div>
        </div>

    </div>
</div>

<?php /**PATH /Users/marcos/Documents/laravel_novo/ultimate/resources/views/business/partials/settings_business.blade.php ENDPATH**/ ?>
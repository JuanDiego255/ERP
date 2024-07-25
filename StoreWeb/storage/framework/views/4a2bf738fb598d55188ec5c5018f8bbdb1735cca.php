<div class="col-md-4">
  <div class="form-group">
    <?php echo Form::label('banco', 'Banco' . ':*'); ?>

    <?php echo Form::select('banco', App\Models\Bank::bancos(), isset($item) ? $item->banco : '', 
    ['id' => 'banco', 'class' => 'form-control select2', 'required']); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('agencia', 'Agencia' . '*:'); ?>

    <?php echo Form::text('agencia', isset($item) ? $item->agencia : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Agencia', 'data-mask="00000000"' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('conta', 'Conta' . '*:'); ?>

    <?php echo Form::text('conta', isset($item) ? $item->conta : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Conta', 'data-mask="00000000"' ]); ?>

  </div>
</div>

<div class="col-md-4">
  <div class="form-group">
    <?php echo Form::label('titular', 'Titular' . '*:'); ?>

    <?php echo Form::text('titular', isset($item) ? $item->titular : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Titular' ]); ?>

  </div>
</div>

<div class="col-md-3">
  <div class="form-group">
    <?php echo Form::label('cnpj', 'CPF/CNPJ' . '*:'); ?>

    <?php echo Form::text('cnpj', isset($item) ? $item->cnpj : '', 
    ['class' => 'form-control cpf_cnpj', 'required', 'placeholder' => 'CPF/CNPJ' ]); ?>

  </div>
</div>

<div class="col-md-5">
  <div class="form-group">
    <?php echo Form::label('endereco', 'Endereço' . '*:'); ?>

    <?php echo Form::text('endereco', isset($item) ? $item->endereco : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Endereço' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('cep', 'CEP' . '*:'); ?>

    <?php echo Form::text('cep', isset($item) ? $item->cep : '', 
    ['class' => 'form-control cep', 'required', 'placeholder' => 'CEP' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('bairro', 'Bairro' . '*:'); ?>

    <?php echo Form::text('bairro', isset($item) ? $item->bairro : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Bairro' ]); ?>

  </div>
</div>

<div class="col-md-5">
  <div class="form-group">
    <?php echo Form::label('cidade_id', 'Cidade:*'); ?>

    <?php echo Form::select('cidade_id', ['' => 'Selecione a cidade'] + $cities, isset($item) ? $item->cidade_id : '', ['id' => 'cidade', 'class' => 'form-control select2 featured-field', 'required']); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">

    <?php echo Form::label('padrao', 'Padrão' . ':'); ?>

    <?php echo Form::select('padrao', ['0' => 'Não', '1' => 'Sim'], isset($item) ? $item->padrao : '', ['id' => 'padrao', 'class' => 'form-control', 'required']); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('carteira', 'Carteira' . '*:'); ?>

    <?php echo Form::text('carteira', isset($item) ? $item->carteira : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Carteira' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('convenio', 'Convênio' . '*:'); ?>

    <?php echo Form::text('convenio', isset($item) ? $item->convenio : '', 
    ['class' => 'form-control', 'required', 'placeholder' => 'Convênio' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('juros', 'Juros' . '*:'); ?>

    <?php echo Form::text('juros', isset($item) ? $item->juros : '', 
    ['class' => 'form-control money', 'required', 'placeholder' => 'Juros' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('multa', 'Multa' . '*:'); ?>

    <?php echo Form::text('multa', isset($item) ? $item->multa : '', 
    ['class' => 'form-control money', 'required', 'placeholder' => 'Multa' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">
    <?php echo Form::label('juros_apos', 'Juros após (dias)' . '*:'); ?>

    <?php echo Form::text('juros_apos', isset($item) ? $item->juros_apos : '', 
    ['class' => 'form-control money', 'required', 'placeholder' => 'Juros após (dias)' ]); ?>

  </div>
</div>

<div class="col-md-2">
  <div class="form-group">

    <?php echo Form::label('tipo', 'Tipo' . ':'); ?>

    <?php echo Form::select('tipo', ['Cnab400' => 'Cnab400', 'Cnab240' => 'Cnab240'], isset($item) ? $item->tipo : '', ['id' => 'tipo', 'class' => 'form-control', 'required']); ?>

  </div>
</div>

<?php /**PATH F:\Nova pasta\gestao\resources\views/banks/_forms.blade.php ENDPATH**/ ?>
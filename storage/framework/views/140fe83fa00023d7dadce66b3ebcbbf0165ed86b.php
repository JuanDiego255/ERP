<?php echo Form::open(['url' => action('PaymentController@paymentPix'), 'method' => 'post', 'id' => 'form_pix' ]); ?>


<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <?php echo Form::label('plano_id', 'Plano'); ?>

            <?php echo Form::select('plano_id', ['' => 'Selecione o plano'] + $planos->pluck('info', 'id')->all(), '', ['class' => 'form-control', 'id' => 'plano_id', 'required']); ?>

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo Form::label('payerFirstName', 'Nome'); ?>

            <?php echo Form::text('payerFirstName', null, ['class' => 'form-control', 'placeholder' => 'Nome', 'required' ]); ?>

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo Form::label('payerLastName', 'Sobre Nome'); ?>

            <?php echo Form::text('payerLastName', null, ['class' => 'form-control', 'placeholder' => 'Sobre Nome', 'required' ]); ?>

        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <?php echo Form::label('payerEmail', 'Email'); ?>

            <?php echo Form::email('payerEmail', null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' ]); ?>

        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <?php echo Form::label('docType', 'Tipo do documento'); ?>

            <?php echo Form::select('docType', [], '', ['class' => 'form-control', 'id' => 'docType', 'required', 'data-checkout' => 'docType']); ?>

        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <?php echo Form::label('docNumber', 'Número do documento'); ?>

            <?php echo Form::tel('docNumber', null, ['class' => 'form-control cpf_cnpj', 'placeholder' => 'Número do documento', 'required' ]); ?>

        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-success pull-right" id="submit_button_pix">Pagar com PIX</button>
  </div>
</div>   
<?php echo Form::close(); ?>


<?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/payment/_forms_pix.blade.php ENDPATH**/ ?>
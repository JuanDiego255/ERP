<?php $__env->startSection('title', 'Adicionar Natureza de Operação'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Adicionar Natureza de Operação</h1>
</section>

<!-- Main content -->
<section class="content">
  <?php echo Form::open(['url' => action('NaturezaController@save'), 'method' => 'post', 'id' => 'natureza_form' ]); ?>

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
      
      <div class="col-md-5">
        <div class="form-group">
          <?php echo Form::label('natureza', 'Natureza' . ':*'); ?>

          <?php echo Form::text('natureza', null, ['class' => 'form-control', 'required', 'placeholder' => 'Natureza' ]); ?>

        </div>
      </div>

      <div class="col-md-3 customer_fields">
        <div class="form-group">

          <?php echo Form::label('sobrescreve_cfop', 'Sobrescrever CFOP do produto' . ':'); ?>

          <?php echo Form::select('sobrescreve_cfop', ['0' => 'Não', '1' => 'Sim'], '', ['id' => 'sobrescreve_cfop', 'class' => 'form-control select2', 'required']); ?>

        </div>
      </div>

      <div class="col-md-2 customer_fields">
        <div class="form-group">

          <?php echo Form::label('finNFe', 'Finalidade' . ':'); ?>

          <?php echo Form::select('finNFe', App\Models\NaturezaOperacao::finalidades(), '', ['id' => 'finNFe', 'class' => 'form-control select2', 'required']); ?>

        </div>
      </div>

      <div class="col-md-2 customer_fields">
        <div class="form-group">

          <?php echo Form::label('tipo', 'Tipo' . ':'); ?>

          <?php echo Form::select('tipo', ['1' => 'Saída', '0' => 'Entrada'], '', ['id' => 'tipo', 'class' => 'form-control select2', 'required']); ?>

        </div>
      </div>
      
      <div class="clearfix"></div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('cfop_entrada_estadual', 'CFOP entrada estadual' . '*:'); ?>

          <?php echo Form::text('cfop_entrada_estadual', null, ['class' => 'form-control', 'required', 'placeholder' => 'CFOP entrada estadual', 'data-mask="0000"' ]); ?>

        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('cfop_saida_estadual', 'CFOP saida estadual' . '*:'); ?>

          <?php echo Form::text('cfop_saida_estadual', null, ['class' => 'form-control', 'required', 'placeholder' => 'CFOP saida estadual', 'data-mask="0000"' ]); ?>

        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('cfop_entrada_inter_estadual', 'CFOP entrada outro estado' . '*:'); ?>

          <?php echo Form::text('cfop_entrada_inter_estadual', null, ['class' => 'form-control', 'required', 'placeholder' => 'CFOP entrada outro estado', 'data-mask="0000"' ]); ?>

        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('cfop_saida_inter_estadual', 'CFOP saida outro estado' . '*:'); ?>

          <?php echo Form::text('cfop_saida_inter_estadual', null, ['class' => 'form-control', 'required', 'placeholder' => 'CFOP saida outro estado', 'data-mask="0000"' ]); ?>

        </div>
      </div>

      <?php echo $__env->renderComponent(); ?>
    </div>

  </div>

  <?php if(!empty($form_partials)): ?>
  <?php $__currentLoopData = $form_partials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php echo $partial; ?>

  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary pull-right" id="submit_button"><?php echo app('translator')->get( 'messages.save' ); ?></button>
    </div>
  </div>
  <?php echo Form::close(); ?>

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('javascript'); ?>
  <script type="text/javascript">
    $(document).ready(function(){
    });
    $(document).on('click', '#submit_button', function(e) {
      e.preventDefault();

      $('form#natureza_form').validate()
      if ($('form#natureza_form').valid()) {
        $('form#natureza_form').submit();
      }
    })

    $('#cfop_entrada_estadual').blur(() => {
      let cfop = $('#cfop_entrada_estadual').val()
      if(cfop.length == 4){
        let temp = cfop.substring(1,4)
        $('#cfop_saida_estadual').val('5'+temp)
        $('#cfop_entrada_inter_estadual').val('2'+temp)
        $('#cfop_saida_inter_estadual').val('6'+temp)
      }
    })
  </script>
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/naturezas/register.blade.php ENDPATH**/ ?>
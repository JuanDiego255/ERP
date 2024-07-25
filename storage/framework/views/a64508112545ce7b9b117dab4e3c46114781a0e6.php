<?php $__env->startSection('title', 'Adicionar Conta Bancária'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Adicionar Conta Bancária</h1>
</section>

<!-- Main content -->
<section class="content">
  <?php echo Form::open(['url' => action('BankController@store'), 'method' => 'post', 'id' => 'bank_form' ]); ?>

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
      <?php echo $__env->make('banks._forms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    </div>

  </div>

  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary pull-right" id="submit_button"><?php echo app('translator')->get( 'messages.save' ); ?></button>
    </div>
  </div>
  <?php echo Form::close(); ?>

  <?php $__env->stopSection(); ?> 
</section>

<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">
  $(document).ready(function(){
  });
  $(document).on('click', '#submit_button', function(e) {
    e.preventDefault();

    $('form#bank_form').validate()
    if ($('form#bank_form').valid()) {
      $('form#bank_form').submit();
    }
  })
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/banks/register.blade.php ENDPATH**/ ?>
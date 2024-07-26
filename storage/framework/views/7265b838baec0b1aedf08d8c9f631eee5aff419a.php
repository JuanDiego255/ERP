<?php $__env->startSection('title', 'Erro NF-e'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Erro(s) NF-e</h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget'); ?>
      
      <div class="col-md-12">
        <?php $__currentLoopData = $erros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h3 class="text-danger">*<?php echo e($r, false); ?></h3>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      
      <div class="clearfix"></div>

      
      <?php echo $__env->renderComponent(); ?>
    </div>


  </div>

 
  

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('javascript'); ?>
  <script type="text/javascript">
    
  </script>
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/nfe/erros.blade.php ENDPATH**/ ?>
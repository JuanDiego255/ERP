<?php $__env->startSection('title',  __('cash_register.open_cash_register')); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo app('translator')->get('cash_register.open_cash_register'); ?></h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
<?php echo Form::open(['url' => action('CashRegisterController@store'), 'method' => 'post', 
'id' => 'add_cash_register_form' ]); ?>

  <div class="box box-solid">
    <div class="box-body">
    <br><br><br>
    <input type="hidden" name="sub_type" value="<?php echo e($sub_type, false); ?>">
      <div class="row">
        <?php if($business_locations->count() > 0): ?>
        <div class="col-sm-3 <?php if(count($business_locations) == 1): ?> col-sm-offset-3 <?php else: ?> col-sm-offset-1 <?php endif; ?>">
          <div class="form-group">
            <?php echo Form::label('amount','Valor em dinheiro:*'); ?>

            <?php echo Form::text('amount', null, ['class' => 'form-control money',
              'placeholder' => __('cash_register.enter_amount'), 'required']); ?>

          </div>
        </div>
        <?php if(count($business_locations) > 1): ?>
        <!-- <div class="clearfix"></div> -->
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('location_id', 'Local:*'); ?>

              <?php echo Form::select('location_id', $business_locations, null, ['class' => 'form-control select2',
              'placeholder' => __('lang_v1.select_location'), 'required']); ?>

          </div>
        </div>
        <?php else: ?>
          <?php echo Form::hidden('location_id', array_key_first($business_locations->toArray()) ); ?>

        <?php endif; ?>
        <div class="col-sm-3">
          <button type="submit" style="margin-top: 23px;" class="btn btn-primary pull-left"><?php echo app('translator')->get('cash_register.open_register'); ?></button>
        </div>
        <?php else: ?>
        <div class="col-sm-8 col-sm-offset-2 text-center">
          <h3><?php echo app('translator')->get('lang_v1.no_location_access_found'); ?></h3>
        </div>
      <?php endif; ?>
      </div>
      <br><br><br>
    </div>
  </div>
  <?php echo Form::close(); ?>

</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/cash_register/create.blade.php ENDPATH**/ ?>
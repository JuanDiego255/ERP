<?php $__env->startSection('title', __('cash_register.cash_register')); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo app('translator')->get( 'cash_register.cash_register' ); ?>
        <small><?php echo app('translator')->get( 'cash_register.manage_your_cash_register' ); ?></small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">

	<div class="box">
        <div class="box-header">
        	<h3 class="box-title"><?php echo app('translator')->get( 'cash_register.all_your_cash_register' ); ?></h3>
        	<div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                	data-href="<?php echo e(action('CashRegisterController@create'), false); ?>" 
                	data-container=".location_add_modal">
                	<i class="fa fa-plus"></i> <?php echo app('translator')->get( 'messages.add' ); ?></button>
            </div>
        </div>
        <div class="box-body">
        	<table class="table table-bordered table-striped" id="cash_registers_table">
        		<thead>
        			<tr>
        				<th><?php echo app('translator')->get( 'invoice.name' ); ?></th>
                        <th><?php echo app('translator')->get( 'messages.action' ); ?></th>
        			</tr>
        		</thead>
        	</table>
        </div>
    </div>

    <div class="modal fade location_add_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>
    <div class="modal fade location_edit_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/cash_register/index.blade.php ENDPATH**/ ?>
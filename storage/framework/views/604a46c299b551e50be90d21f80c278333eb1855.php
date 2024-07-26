<div class="col-md-12">
	<form action="<?php echo e(action('\Modules\Superadmin\Http\Controllers\SubscriptionController@confirm', [$package->id]), false); ?>" method="POST">
	 	<?php echo e(csrf_field(), false); ?>

	 	<input type="hidden" name="gateway" value="<?php echo e($k, false); ?>">

	 	<button type="submit" class="btn btn-success"> <i class="fa fa-hand-grab-o"></i> <?php echo e($v, false); ?></button>
	</form>
	<p class="help-block"><?php echo app('translator')->get('superadmin::lang.offline_pay_helptext'); ?></p>
	<p class="help-block"><?php echo nl2br($offline_payment_details) ?? ''; ?></p>
</div><?php /**PATH C:\xampp\htdocs\StoreWeb\Modules\Superadmin\Providers/../Resources/views/subscription/partials/pay_offline.blade.php ENDPATH**/ ?>
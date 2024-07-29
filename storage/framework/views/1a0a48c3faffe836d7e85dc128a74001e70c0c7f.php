<?php $__env->startSection('title', 'Receber conta'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Receber conta</h1>
</section>

<!-- Main content -->
<section class="content">
	<?php echo Form::open(['url' => action('RevenueController@receivePut', [$item->id]), 'method' => 'put', 'id' => 'add_form', 'files' => true ]); ?>

	<div class="box box-primary">
		<div class="box-body">
			<div class="row">

				<div class="col-sm-3">
					<div class="form-group">
						<?php echo Form::label("tipo_pagamento" , 'Forma de pago' . ':*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="fas fa-list"></i>
							</span>
							<?php echo Form::select("tipo_pagamento", $payment_types, $item->forma_pagamento, ['class' => 'form-control col-md-12 payment_types_dropdown', 'required', 'id' => "forma_pagamento", 'style' => 'width:100%;']); ?>

						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<?php echo Form::label('vencimento', 'Vencimento:*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<?php echo Form::text('vencimento', \Carbon\Carbon::parse($item->vencimento)->format('d/m/Y'), ['class' => 'form-control', 'disabled', 'required', 'id' => '']); ?>

						</div>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">
						<?php echo Form::label('recebimento', 'Recebimento:*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<?php echo Form::text('recebimento', \Carbon\Carbon::parse($item->vencimento)->format('d/m/Y'), ['class' => 'form-control', 'readonly', 'required', 'id' => 'vencimento']); ?>

						</div>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">

						<?php echo Form::label('final_total', __('sale.total_amount') . ':*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-tag"></i>
							</span>
							<?php echo Form::text('final_total', number_format($item->valor_total,2), ['class' => 'form-control input_number money', 'readonly', 'placeholder' => __('sale.total_amount'), 'required']); ?>

						</div>
					</div>
				</div>

				<div class="col-sm-2">
					<div class="form-group">

						<?php echo Form::label('valor_recebido', 'Valor recebido:*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="glyphicon glyphicon-tag"></i>
							</span>
							<?php echo Form::text('valor_recebido', number_format($item->valor_total,2), ['class' => 'form-control input_number money', 'placeholder' => __('sale.total_amount'), 'required']); ?>

						</div>
					</div>
				</div>
				

			</div>
		</div>
	</div> <!--box end-->
	<div class="col-sm-12">
		<button type="submit" id="submit_button" class="btn btn-primary pull-right">Receber</button>
	</div>
	<?php echo Form::close(); ?>


</section>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">


</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/revenues/receive.blade.php ENDPATH**/ ?>
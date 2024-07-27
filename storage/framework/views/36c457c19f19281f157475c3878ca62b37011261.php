<?php $__env->startSection('title', 'Adicionar cuenta por cobrar'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Nueva cuenta por cobrar</h1>
</section>

<!-- Main content -->
<section class="content">
	<?php echo Form::open(['url' => action('RevenueController@store'), 'method' => 'post', 'id' => 'add_form', 'files' => true ]); ?>

	<div class="box box-primary">
		<div class="box-body">
			<div class="row">

				<?php if(count($business_locations) == 1): ?>
				<?php 
				$default_location = current(array_keys($business_locations->toArray())) 
				?>
				<?php else: ?>
				<?php $default_location = null; ?>
				<?php endif; ?>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo Form::label('location_id', __('purchase.business_location').':*'); ?>

						<?php echo Form::select('location_id', $business_locations, $default_location, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select'), 'required']); ?>

					</div>
				</div>

				<div class="col-sm-4">
					<div class="form-group">
						<?php echo Form::label('expense_category_id', 'Categoria:'); ?>

						<?php echo Form::select('expense_category_id', $expense_categories, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); ?>

					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo Form::label('referencia', __('purchase.ref_no').':'); ?>

						<?php echo Form::text('referencia', null, ['class' => 'form-control']); ?>

					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo Form::label('vencimento', 'Vencimento:*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</span>
							<?php echo Form::text('vencimento', \Carbon::createFromTimestamp(strtotime('now'))->format(session('business.date_format')), ['class' => 'form-control', 'readonly', 'required', 'id' => 'vencimento']); ?>

						</div>
					</div>
				</div>

				<div class="<?php if(!empty($commission_agent)): ?> col-sm-3 <?php else: ?> col-sm-4 <?php endif; ?>">
					<div class="form-group">
						<?php echo Form::label('contact_id', __('contact.customer') . ':*'); ?>

						<div class="input-group">
							<span class="input-group-addon">
								<i class="fa fa-user"></i>
							</span>
							
							<?php echo Form::select('contact_id', 
							[], null, ['class' => 'form-control mousetrap', 'id' => 'customer_id', 'placeholder' => 'Entre com nome do cliente', 'required']); ?>

							<span class="input-group-btn">
								<button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<?php echo Form::label('document', 'Documento anexo' . ':'); ?>

						<?php echo Form::file('document', ['id' => 'upload_document', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); ?>

						<p class="help-block"><?php echo app('translator')->get('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)]); ?>
						<?php if ($__env->exists('components.document_help_text')) echo $__env->make('components.document_help_text', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></p>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<?php echo Form::label('additional_notes', 'Observação' . ':'); ?>

						<?php echo Form::textarea('additional_notes', null, ['class' => 'form-control', 'rows' => 3]); ?>

					</div>
				</div>
				<div class="clearfix"></div>
				
				<div class="col-sm-3">
					<div class="form-group">
						<?php echo Form::label('final_total', __('sale.total_amount') . ':*'); ?>

						<?php echo Form::text('final_total', null, ['class' => 'form-control input_number money', 'placeholder' => __('sale.total_amount'), 'required']); ?>

					</div>
				</div>
			</div>
		</div>
	</div> <!--box end-->
	<?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'id' => "payment_rows_div", 'title' => __('purchase.add_payment')]); ?>
	<div class="payment_row">
		<div class="row">

			
			<div class="col-sm-3">
				<div class="form-group">
					<?php echo Form::label('valor_recebido', 'Valor recebido:*'); ?>

					<?php echo Form::text('valor_recebido', '', ['class' => 'form-control input_number money', 'placeholder' => __('sale.total_amount')]); ?>

				</div>
			</div>

			<div class="col-sm-3">
				<div class="form-group">
					<?php echo Form::label("tipo_pagamento", 'Forma de pago' . ':*'); ?>

					<div class="input-group">
						<span class="input-group-addon">
							<i class="fas fa-list"></i>
						</span>
						<?php echo Form::select("tipo_pagamento", $payment_types, null, ['class' => 'form-control col-md-12 payment_types_dropdown', 'required', 'id' => "tipo_pagamento", 'style' => 'width:100%;']); ?>

					</div>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-sm-12">
				<div class="pull-right">
					<strong>Valor total:</strong>
					<span id="payment_due"><?php echo e(number_format(0, 2, ',', '.'), false); ?></span>
				</div>
			</div>
		</div>
	</div>
	<?php echo $__env->renderComponent(); ?>
	<div class="col-sm-12">
		<button type="submit" id="submit_button" class="btn btn-primary pull-right"><?php echo app('translator')->get('messages.save'); ?></button>
	</div>
	<?php echo Form::close(); ?>


</section>

<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	<?php echo $__env->make('contact.create', ['quick_add' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">

	$(document).ready(function() {

		$('#customer_id').select2({
			ajax: {
				url: '/contacts/customers',
				dataType: 'json',
				delay: 250,
				data: function(params) {
					return {
						q: params.term,
						page: params.page,
					};
				},
				processResults: function(data) {
					return {
						results: data,
					};
				},
			},
			templateResult: function (data) { 

				var template = data.text + "<br>" + "CPF/CNPJ" + ": " + data.cpf_cnpj;
				if (typeof(data.total_rp) != "undefined") {
					var rp = data.total_rp ? data.total_rp : 0;
					template += "<br><i class='fa fa-gift text-success'></i> " + rp;
				}

				return  template;
			},
			minimumInputLength: 1,
			language: {
				noResults: function() {
					var name = $('#customer_id')
					.data('select2')
					.dropdown.$search.val();
					return (
						'<button type="button" data-name="' +
						name +
						'" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
						__translate('add_name_as_new_customer', { name: name }) +
						'</button>'
						);
				},
			},
			escapeMarkup: function(markup) {
				return markup;
			},
		});

		set_default_customer()
	})
	$(document).on('change', 'input#final_total, input.payment-amount', function() {
		calculateExpensePaymentDue();
	});

	function calculateExpensePaymentDue() {
		var final_total = __read_number($('input#final_total'));
		var payment_amount = __read_number($('input.payment-amount'));
		var payment_due = final_total - payment_amount;
		$('#payment_due').text(__currency_trans_from_en(payment_due, true, false));
	}


	$(document).on('click', '#submit_button', function(e) {
		e.preventDefault();

		$('form#add_form').validate()
		if ($('form#add_form').valid()) {
			$('form#add_form').submit();
		}
	})

	$(document).on('click', '.add_new_customer', function() {
        // $('#customer_id').select2('close');
        var name = $(this).data('name');
        $('.contact_modal')
        .find('input#name')
        .val(name);
        $('.contact_modal')
        .find('select#contact_type')
        .val('customer')
        .closest('div.contact_type_div')
        .addClass('hide');
        $('.contact_modal').modal('show');
    });

	$('form#quick_add_contact')
	.submit(function(e) {
		e.preventDefault();
	})
	.validate({
		rules: {
			contact_id: {
				remote: {
					url: '/contacts/check-contact-id',
					type: 'post',
					data: {
						contact_id: function() {
							return $('#contact_id').val();
						},
						hidden_id: function() {
							if ($('#hidden_id').length) {
								return $('#hidden_id').val();
							} else {
								return '';
							}
						},
					},
				},
			},
		},
		messages: {
			contact_id: {
				remote: LANG.contact_id_already_exists,
			},
		},
		submitHandler: function(form) {
			$(form)
			.find('button[type="submit"]')
			.attr('disabled', true);
			var data = $(form).serialize();
			$.ajax({
				method: 'POST',
				url: $(form).attr('action'),
				dataType: 'json',
				data: data,
				success: function(result) {
					if (result.success == true) {
						$('select#customer_id').append(
							$('<option>', { value: result.data.id, text: result.data.name })
							);
						$('select#customer_id')
						.val(result.data.id)
						.trigger('change');
						$('div.contact_modal').modal('hide');
						toastr.success(result.msg);
					} else {
						toastr.error(result.msg);
					}
				},
			});
		},
	});
	$('.contact_modal').on('hidden.bs.modal', function() {
		$('form#quick_add_contact')
		.find('button[type="submit"]')
		.removeAttr('disabled');
		$('form#quick_add_contact')[0].reset();
	});

	function set_default_customer() {
		var default_customer_id = $('#default_customer_id').val();
		var default_customer_name = $('#default_customer_name').val();
		var exists = $('select#customer_id option[value=' + default_customer_id + ']').length;
		if (exists == 0) {
			$('select#customer_id').append(
				$('<option>', { value: default_customer_id, text: default_customer_name })
				);
		}

		$('select#customer_id')
		.val(default_customer_id)
		.trigger('change');

		customer_set = true;
	}

	// $(document).on('change', 'select#customer_id', function(){
	// 	var default_customer_id = $('#default_customer_id').val();
	// 	if ($(this).val() == default_customer_id) {

	// 		if ($('#rp_redeemed_modal').length) {
	// 			$('#rp_redeemed_modal').val('');
	// 			$('#rp_redeemed_modal').change();
	// 			$('#rp_redeemed_modal').attr('disabled', true);
	// 			$('#available_rp').text('');
	// 			updateRedeemedAmount();
	// 			pos_total_row();
	// 		}
	// 	} else {
	// 		if ($('#rp_redeemed_modal').length) {
	// 			$('#rp_redeemed_modal').removeAttr('disabled');
	// 		}

	// 	}
	// });


</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/revenues/create.blade.php ENDPATH**/ ?>
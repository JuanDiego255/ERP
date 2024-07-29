<?php $__env->startSection('title', 'Gerar NF-e de Entrada'); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">

	<?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>


	<input type="hidden" value="<?php echo e($purchase->id, false); ?>" name="purchase_id">
	<h2 class="box-title">Emitir NF-e Entrada</h2>


	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<h3 class="box-title">Fornecedor</h3>

				<div class="row">
					<div class="col-sm-6">

						<span>Nombre: <strong><?php echo e($purchase->contact->name, false); ?></strong></span><br>
						<span>CNPJ/CPF: <strong><?php echo e($purchase->contact->cpf_cnpj, false); ?></strong></span><br>
						<span>IE/RG: <strong><?php echo e($purchase->contact->ie_rg, false); ?></strong></span>
					</div>

					<div class="col-sm-6">

						<span>Calle: <strong><?php echo e($purchase->contact->rua, false); ?>, <?php echo e($purchase->contact->numero, false); ?></strong></span><br>
						<span>Barrio: <strong><?php echo e($purchase->contact->bairro, false); ?></strong></span><br>
						<span>Ciudad: <strong><?php echo e($purchase->contact->cidade->nome, false); ?> (<?php echo e($purchase->contact->cidade->uf, false); ?>)</strong></span>

					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group">
				<h3 class="box-title">Produtos</h3>


				<div class="">

					<!-- Inicio tabela -->
					<div class="nav-tabs-custom">


						<div class="tab-content">
							<div class="tab-pane active" id="product_list_tab">
								<br><br>
								<div class="table-responsive">
									<div id="product_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row margin-bottom-20 text-center">
											<table class="table table-bordered table-striped ajax_view hide-footer dataTable no-footer" id="product_table" role="grid" aria-describedby="product_table_info" style="width: 1300px;">
												<thead>
													<tr role="row">

														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 200px;" aria-label="Produto">Produto</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Produto">Código</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Produto">NCM</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Produto">Quantidade</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Produto">Valor Unit.</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 100px;" aria-label="Produto">Cod. Barras</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Produto">Unidade</th>
													</tr>
												</thead>

												<tbody>

													<?php $__currentLoopData = $purchase->purchase_lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

													<tr>
														<td style="width: 200px;"><?php echo e($i->product->name, false); ?></td>
														<td style="width: 200px;"><?php echo e($i->product->id, false); ?></td>
														<td style="width: 200px;"><?php echo e($i->product->ncm, false); ?></td>
														<td style="width: 200px;"><?php echo e($i->quantity, false); ?></td>
														<td style="width: 200px;"><?php echo e(number_format($i->purchase_price, 2, ',', ''), false); ?></td>
														<td style="width: 200px;"><?php echo e($i->product->sku, false); ?></td>
														<td style="width: 200px;"><?php echo e($i->product->unit->short_name, false); ?></td>

													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

												</tbody>
											</table>


										</div>

									</div>


								</div>
							</div>
						</div>
					</div>

					<!-- fim tabela -->
				</div>
			</div>
		</div>



		<div class="col-sm-12">
			<div class="form-group">
				<h3 class="box-title">Fatura</h3>
				<div class="">

					<div class="nav-tabs-custom">


						<div class="tab-content">
							<div class="tab-pane active" id="product_list_tab">
								<br><br>
								<div class="table-responsive">
									<div id="product_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row margin-bottom-20 text-center">
											<table class="table table-bordered table-striped ajax_view hide-footer dataTable no-footer" id="product_table" role="grid" aria-describedby="product_table_info" style="width: 700px;">
												<thead>
													<tr role="row">

														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 100px;" aria-label="Produto">Número</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 100px;" aria-label="Produto">Vencimento</th>
														<th class="sorting_disabled" rowspan="1" colspan="1" style="width: 100px;" aria-label="Produto">Valor</th>
													</tr>
												</thead>

												<tbody>

													<?php if(sizeof($purchase->payment_lines) > 0): ?>

													<?php $__currentLoopData = $purchase->payment_lines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

													<tr>
														<td style="width: 80px;"><?php echo e($key+1, false); ?></td>
														<td style="width: 80px;"><?php echo e(\Carbon\Carbon::parse($f->paid_on)->format('d/m/Y'), false); ?></td>
														<td style="width: 80px;"><?php echo e(number_format($f->amount, 2), false); ?></td>
														<tr>
															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
															<?php else: ?>
															<tr>
																<td colspan="3">Fatura Unica</td>
															</tr>
															<?php endif; ?>

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">

						<div class="form-group">

							<div class="col-sm-3">
								<div class="form-group">
									<?php echo Form::label('natureza_id', 'Natureza de Operação'. ':*'); ?>

									<?php echo Form::select('natureza_id', $naturezas, null, ['id' => 'natureza_id', 'class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); ?>

								</div>
							</div>

							<div class="col-sm-3">
								<div class="form-group">
									<?php echo Form::label('tipo_pagamento', 'Tipo de Pagamento'. ':*'); ?>

									<?php echo Form::select('tipo_pagamento', $tiposPagamento, null, ['id' => 'tipo_pagamento', 'class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); ?>

								</div>
							</div>

							
							<div class="col-sm-3"></div>

							<div class="col-sm-3">
								<h4>TOTAL: ₡ <strong><?php echo e(number_format($purchase->total_before_tax, 2, ',', '.'), false); ?></strong></h4>
							</div>

						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">

						<div class="col-md-3">
							<form method="get" action="/nfeEntrada/renderizarDanfe" target="_blank">
								<input type="hidden" value="<?php echo e($purchase->id, false); ?>" name="purchase_id">
								<input type="hidden" value="" id="natureza_renderizar" name="natureza">
								<input type="hidden" value="" id="tipo_pagamento_renderizar" name="tipo_pagamento">
								<button style="width: 100% !important;" class="btn btn-lg btn-primary" type="submit">Renderizar</button>
							</form>
						</div>
						<div class="col-md-3">
							<form method="get" action="/nfeEntrada/gerarXml" target="_blank">
								<input type="hidden" value="<?php echo e($purchase->id, false); ?>" name="purchase_id" id="purchase_id">
								<input type="hidden" value="" id="natureza_xml" name="natureza">
								<input type="hidden" value="" id="tipo_pagamento_xml" name="tipo_pagamento">
								<button style="width: 100%;" class="btn btn-lg btn-danger" type="submit">Gerar XML</button>
							</form>
						</div>
						<div class="col-md-3">

							<input type="hidden" id="token" value="<?php echo e(csrf_token(), false); ?>" name="">
							<a style="width: 100%;" class="btn btn-lg btn-success" id="send-sefaz">Transmitir NF-e Entrada</a>
						</div>

						<br>
						<div class="row" id="action" style="display: none">
							<div class="col-md-12">
								<?php $__env->startComponent('components.widget'); ?>
								<div class="info-box-content">
									<div class="col-md-4 col-md-offset-4">

										<span class="info-box-number total_purchase">
											<strong id="acao"></strong>
											<i class="fas fa-spinner fa-pulse fa-spin fa-fw margin-bottom"></i></span>
										</div>
									</div>
									<?php echo $__env->renderComponent(); ?>

								</div>
							</div>

						</div>
					</div>

				</div>


			</div>

			<?php echo $__env->renderComponent(); ?>


		</section>

		<?php $__env->startSection('javascript'); ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
		<script type="text/javascript">
			$('#perc_venda').mask('000.00')

			$('#natureza_id').change(() => {
				let natureza_id = $('#natureza_id').val();
				$('#natureza_xml').val(natureza_id)
				$('#natureza_renderizar').val(natureza_id)
			})

			$('#tipo_pagamento').change(() => {
				let tipo_pagamento = $('#tipo_pagamento').val();

				$('#tipo_pagamento_xml').val(tipo_pagamento)
				$('#tipo_pagamento_renderizar').val(tipo_pagamento)
			})


			$('#send-sefaz').click(() => {
				let token = $('#token').val();
				let purchase_id = $('#purchase_id').val();
				let natureza_id = $('#natureza_id').val();
				let tipo_pagamento = $('#tipo_pagamento').val();

				if(!natureza_id){
					swal("Erro", "Informe a natureza de operação", "warning")
				}
				if(!tipo_pagamento){
					swal("Erro", "Informe o tipo de pagamento", "warning")
				}
				else{
					$('#action').css('display', 'block')

					setTimeout(() => {
						$('#acao').html('Gerando XML');
					}, 50);

					setTimeout(() => {
						$('#acao').html('Assinando o arquivo');
					}, 800);

					setTimeout(() => {
						$('#acao').html('Transmitindo para sefaz');
					}, 1500);
					var path = window.location.protocol + '//' + window.location.host

					$.ajax
					({
						type: 'POST',
						data: {
							purchase_id: purchase_id,
							_token: token,
							natureza: natureza_id,
							tipo_pagamento: tipo_pagamento
						},
						url: path + '/nfeEntrada/transmitir',
						dataType: 'json',
						success: function(e){
							console.log(e)

							swal("sucesso", "NF-e emitida, recibo: " + e, "success")
							.then(() => {
								window.open(path + '/nfeEntrada/imprimir/'+purchase_id)
								location.href = '/nfeEntrada/ver/'+purchase_id
							});
							$('#action').css('display', 'none')


						}, error: function(e){

							console.log(e)
							if(e.status == 402){
								swal("Erro ao transmitir", e.responseJSON, "error");
								$('#action').css('display', 'none')

							}else{
								$('#action').css('display', 'none')
								let jsError = JSON.parse(e.responseJSON)
								console.log(jsError)
								swal("Erro ao transmitir", jsError.protNFe.infProt.xMotivo, "error");

							}
						}

					})
				}
			})



		</script>
		<?php $__env->stopSection(); ?>


		<!-- /.content -->

		<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/nfe_entrada/novo.blade.php ENDPATH**/ ?>
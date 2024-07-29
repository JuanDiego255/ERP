<?php $__env->startSection('title', 'Cuentas por cobrar'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Cuentas por cobrar</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php $__env->startComponent('components.filters', ['title' => __('report.filters')]); ?>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('location_id',  __('purchase.business_location') . ':'); ?>

                        <?php echo Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); ?>

                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('expense_category_id', 'Categoria:'); ?>

                        <?php echo Form::select('expense_category_id', $categories, null, ['placeholder' =>
                        __('report.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'expense_category_id']); ?>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('expense_date_range', __('report.date_range') . ':'); ?>

                        <?php echo Form::text('date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'id' => 'expense_date_range', 'readonly']); ?>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <?php echo Form::label('expense_payment_status',  __('purchase.payment_status') . ':'); ?>

                        <?php echo Form::select('expense_payment_status', ['1' => 'Recebido', '-1' => 'Pendente'], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); ?>

                    </div>
                </div>
            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => '']); ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expense.access')): ?>
                    <?php $__env->slot('tool'); ?>
                        <div class="box-tools">
                            <a class="btn btn-block btn-primary" href="<?php echo e(action('RevenueController@create'), false); ?>">
                            <i class="fa fa-plus"></i> <?php echo app('translator')->get('messages.add'); ?></a>
                        </div>
                        
                    <?php $__env->endSlot(); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="revenue_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo app('translator')->get('messages.action'); ?></th>
                                <th>Cliente</th>
                                <th>Vencimento</th>
                                <th>Referência</th>
                                <th>Categoria</th>
                                <th><?php echo app('translator')->get('business.location'); ?></th>
                                <th>Status</th>
                                <th>Valor total</th>
                                <th>Valor recebido</th>
                                <th>Observación</th>
                                <th><?php echo app('translator')->get('lang_v1.added_by'); ?></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 text-center footer-total">
                                <td colspan="7"><strong><?php echo app('translator')->get('sale.total'); ?>:</strong></td>
                                <td id="footer_payment_status_count"></td>
                                <td><span class="display_currency" id="footer_revenue_total" data-currency_symbol ="true"></span></td>
                                <td><span class="display_currency" id="footer_total_receive" data-currency_symbol ="true"></span></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php echo $__env->renderComponent(); ?>
        </div>
    </div>

</section>
<!-- /.content -->
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
 <script src="<?php echo e(asset('js/revenue.js'), false); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/revenues/index.blade.php ENDPATH**/ ?>
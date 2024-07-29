<span id="view_contact_page"></span>
<div class="row">
    <div class="col-md-12">
        <div class="col-sm-3">
            <?php echo $__env->make('contact.contact_basic_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-sm-3 mt-56">
            <?php echo $__env->make('contact.contact_more_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <?php if( $contact->type != 'customer'): ?>
            <div class="col-sm-3 mt-56">
                <?php echo $__env->make('contact.contact_tax_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        <?php endif; ?>
        <div class="col-sm-3 mt-56">
            <?php echo $__env->make('contact.contact_payment_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <?php if( $contact->type == 'customer' || $contact->type == 'both'): ?>
            <div class="col-sm-3 <?php if($contact->type != 'both'): ?> mt-56 <?php endif; ?>">
                <strong><?php echo app('translator')->get('lang_v1.total_sell_return'); ?></strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    <?php echo e($contact->total_sell_return, false); ?></span>
                </p>
                <strong><?php echo app('translator')->get('lang_v1.total_sell_return_due'); ?></strong>
                <p class="text-muted">
                    <span class="display_currency" data-currency_symbol="true">
                    <?php echo e($contact->total_sell_return -  $contact->total_sell_return_paid, false); ?></span>
                </p>
            </div>
        <?php endif; ?>

        <?php if( $contact->type == 'supplier' || $contact->type == 'both'): ?>
            <div class="clearfix"></div>
            <div class="col-sm-12">
                <?php if(($contact->total_purchase - $contact->purchase_paid) > 0): ?>
                    <a href="<?php echo e(action('TransactionPaymentController@getPayContactDue', [$contact->id]), false); ?>?type=purchase" class="pay_purchase_due btn btn-primary btn-sm pull-right"><i class="fas fa-money-bill-alt" aria-hidden="true"></i> <?php echo app('translator')->get("contact.pay_due_amount"); ?></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/contact/partials/contact_info_tab.blade.php ENDPATH**/ ?>
<?php echo app('translator')->get('purchase.supplier'); ?>:
<address>
    <strong><?php echo e($transaction->contact->supplier_business_name, false); ?></strong>
    <?php echo e($transaction->contact->name, false); ?>

    <?php if(!empty($transaction->contact->landmark)): ?>
        <br><?php echo e($transaction->contact->landmark, false); ?>

    <?php endif; ?>
    <?php if(!empty($transaction->contact->city) || !empty($transaction->contact->state) || !empty($transaction->contact->country)): ?>
        <br><?php echo e(implode(',', array_filter([$transaction->contact->city, $transaction->contact->state, $transaction->contact->country])), false); ?>

    <?php endif; ?>
    <?php if(!empty($transaction->contact->tax_number)): ?>
        <br><?php echo app('translator')->get('contact.tax_no'); ?>: <?php echo e($transaction->contact->tax_number, false); ?>

    <?php endif; ?>
    <?php if(!empty($transaction->contact->mobile)): ?>
        <br><?php echo app('translator')->get('contact.mobile'); ?>: <?php echo e($transaction->contact->mobile, false); ?>

    <?php endif; ?>
    <?php if(!empty($transaction->contact->email)): ?>
        <br>Email: <?php echo e($transaction->contact->email, false); ?>

    <?php endif; ?>
</address><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/transaction_payment/transaction_supplier_details.blade.php ENDPATH**/ ?>
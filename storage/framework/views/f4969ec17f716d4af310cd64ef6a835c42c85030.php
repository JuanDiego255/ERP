<?php
    $custom_labels = json_decode(session('business.custom_labels'), true);
?>

<?php if(!empty($contact->custom_field1)): ?>
    <strong><?php echo e($custom_labels['contact']['custom_field_1'] ?? __('lang_v1.contact_custom_field1'), false); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->custom_field1, false); ?>

    </p>
<?php endif; ?>

<?php if(!empty($contact->custom_field2)): ?>
    <strong><?php echo e($custom_labels['contact']['custom_field_2'] ?? __('lang_v1.contact_custom_field2'), false); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->custom_field2, false); ?>

    </p>
<?php endif; ?>

<?php if(!empty($contact->custom_field3)): ?>
    <strong><?php echo e($custom_labels['contact']['custom_field_3'] ?? __('lang_v1.contact_custom_field3'), false); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->custom_field3, false); ?>

    </p>
<?php endif; ?>

<?php if(!empty($contact->custom_field4)): ?>
    <strong><?php echo e($custom_labels['contact']['custom_field_4'] ?? __('lang_v1.contact_custom_field4'), false); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->custom_field4, false); ?>

    </p>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/contact/contact_more_info.blade.php ENDPATH**/ ?>
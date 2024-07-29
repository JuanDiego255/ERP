<!-- <strong><?php echo e($contact->name, false); ?></strong><br><br> -->
<h3 class="profile-username">
    <i class="fas fa-user-tie"></i>
    <?php echo e($contact->name, false); ?>

    <small>
        <?php if($contact->type == 'both'): ?>
            <?php echo e(__('role.customer'), false); ?> & <?php echo e(__('role.supplier'), false); ?>

        <?php elseif(($contact->type != 'lead')): ?>
            <?php echo e(__('role.'.$contact->type), false); ?>

        <?php endif; ?>
    </small>
</h3><br>
<strong><i class="fa fa-map-marker margin-r-5"></i> <?php echo app('translator')->get('business.address'); ?></strong>
<p class="text-muted">
    <?php if($contact->landmark): ?>
        <?php echo e($contact->landmark, false); ?>

    <?php endif; ?>


    <?php echo e($contact2->rua . ', ' . $contact2->numero . ' - ' . $contact2->bairro, false); ?>


    <?php echo e(', ' . $contact2->cidade->nome . ' (' . $contact2->cidade->uf . ')', false); ?>


    <?php if($contact->state): ?>
        <?php echo e(', ' . $contact->state, false); ?>

    <?php endif; ?>
    <br>
    <?php if($contact->country): ?>
        <?php echo e($contact->country, false); ?>

    <?php endif; ?>
</p>
<?php if($contact->supplier_business_name): ?>
    <strong><i class="fa fa-briefcase margin-r-5"></i> 
    <?php echo app('translator')->get('business.business_name'); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->supplier_business_name, false); ?>

    </p>
<?php endif; ?>

<strong><i class="fa fa-mobile margin-r-5"></i> Celular</strong>
<p class="text-muted">
    <?php echo e($contact->mobile, false); ?>

</p>
<?php if($contact->landline): ?>
    <strong><i class="fa fa-phone margin-r-5"></i> <?php echo app('translator')->get('contact.landline'); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->landline, false); ?>

    </p>
<?php endif; ?>
<?php if($contact->alternate_number): ?>
    <strong><i class="fa fa-phone margin-r-5"></i> <?php echo app('translator')->get('contact.alternate_contact_number'); ?></strong>
    <p class="text-muted">
        <?php echo e($contact->alternate_number, false); ?>

    </p>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/contact/contact_basic_info.blade.php ENDPATH**/ ?>
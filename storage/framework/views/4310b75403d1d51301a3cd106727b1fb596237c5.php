<?php
    $colspan = 15;
    $custom_labels = json_decode(session('business.custom_labels'), true);
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped ajax_view hide-footer" id="product_table">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all-row"></th>
                <th>&nbsp;</th>
                <th><?php echo app('translator')->get('sale.product'); ?></th>
                <th><?php echo app('translator')->get('product.category'); ?></th>
                <th><?php echo app('translator')->get('product.brand'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.model'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.color'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.vin'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.placa'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.dua'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.comprado_a'); ?></th>
                <th><?php echo app('translator')->get('vehiculos.created_at'); ?></th>
                <th><?php echo app('translator')->get('product.sku'); ?></th>
                <th style="display: none;"></th>
                <th><?php echo app('translator')->get('messages.action'); ?></th>


            </tr>
        </thead>

    </table>


</div>

<div class="row" style="margin-left: 5px;">
    <tr>
        <td colspan="<?php echo e($colspan, false); ?>">
            <div style="display: flex; width: 100%;">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product.delete')): ?>
                    <?php echo Form::open([
                        'url' => action('ProductController@massDestroy'),
                        'method' => 'post',
                        'id' => 'mass_delete_form',
                    ]); ?>

                    <?php echo Form::hidden('selected_rows', null, ['id' => 'selected_rows']); ?>

                    <?php echo Form::submit(__('lang_v1.delete_selected'), ['class' => 'btn btn-xs btn-danger', 'id' => 'delete-selected']); ?>

                    <?php echo Form::close(); ?>

                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('product.update')): ?>
                    <?php echo Form::open(['url' => action('ProductController@bulkEdit'), 'method' => 'post', 'id' => 'bulk_edit_form']); ?>

                    <?php echo Form::hidden('selected_products', null, ['id' => 'selected_products_for_edit']); ?>

                    <button type="submit" class="btn btn-xs btn-primary" id="edit-selected"> <i
                            class="fa fa-edit"></i><?php echo e(__('lang_v1.bulk_edit'), false); ?></button>
                    <?php echo Form::close(); ?>

                <?php endif; ?>

                <?php echo Form::open([
                    'url' => action('ProductController@massDeactivate'),
                    'method' => 'post',
                    'id' => 'mass_deactivate_form',
                ]); ?>

                <?php echo Form::hidden('selected_products', null, ['id' => 'selected_products']); ?>

                <?php echo Form::submit('Desactivar seleccionado', ['class' => 'btn btn-xs btn-warning', 'id' => 'deactivate-selected']); ?>

                <?php echo Form::close(); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . 'Desactivar los vehículos seleccionados' . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
            </div>
        </td>
    </tr>
</div>
<?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/product/partials/product_list.blade.php ENDPATH**/ ?>
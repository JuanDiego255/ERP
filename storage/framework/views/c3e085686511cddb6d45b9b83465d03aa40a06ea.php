<?php
    $heading = !empty($module_category_data['heading']) ? $module_category_data['heading'] : __('category.categories');
?>
<?php $__env->startSection('title', $heading); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo e($heading, false); ?>

        <small>
            <?php echo e($module_category_data['sub_heading'] ?? __( 'category.manage_your_categories' ), false); ?>

        </small>
        <?php if(isset($module_category_data['heading_tooltip'])): ?>
            <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . $module_category_data['heading_tooltip'] . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
        <?php endif; ?>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <?php
        $cat_code_enabled = isset($module_category_data['enable_taxonomy_code']) && !$module_category_data['enable_taxonomy_code'] ? false : true;
    ?>
    <input type="hidden" id="category_type" value="<?php echo e(request()->get('type'), false); ?>">
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category.create')): ?>
            <?php $__env->slot('tool'); ?>
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="<?php echo e(action('TaxonomyController@create'), false); ?>?type=<?php echo e(request()->get('type'), false); ?>" 
                    data-container=".category_modal">
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get( 'messages.add' ); ?></button>
                </div>
            <?php $__env->endSlot(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category.view')): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="category_table">
                    <thead>
                        <tr>
                            <th></th>
                            <th><?php if(!empty($module_category_data['taxonomy_label'])): ?> <?php echo e($module_category_data['taxonomy_label'], false); ?> <?php else: ?> Categoria <?php endif; ?></th>
                            <?php if($cat_code_enabled): ?>
                                <th><?php echo e($module_category_data['taxonomy_code_label'] ?? __( 'category.code' ), false); ?></th>
                            <?php endif; ?>
                            <th><?php echo app('translator')->get( 'lang_v1.description' ); ?></th>
                            <th>Destaque</th>
                            <th>Ecommerce</th>
                            <th><?php echo app('translator')->get( 'messages.action' ); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php endif; ?>
    <?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

    <div class="modal fade category_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
<?php if ($__env->exists('taxonomy.taxonomies_js')) echo $__env->make('taxonomy.taxonomies_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/taxonomy/index.blade.php ENDPATH**/ ?>
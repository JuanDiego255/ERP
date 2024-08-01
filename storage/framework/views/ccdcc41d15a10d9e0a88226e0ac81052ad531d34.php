

<?php $__env->startSection('title', __('Editar rubro')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo app('translator')->get('Editar rubro'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php echo Form::open([
            'url' => action('RubrosController@update', [$rubro->id]),
            'method' => 'PUT',
            'id' => 'user_edit_form',
        ]); ?>

        <div class="row">
            <div class="col-md-12">
                <?php $__env->startComponent('components.widget'); ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('name', __('Rubro') . ':*'); ?>

                            <?php echo Form::text('name', $rubro->name, ['class' => 'form-control', 'required', 'placeholder' => __('Rubro')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('alias', __('Alias') . ':*'); ?>

                            <?php echo Form::text('alias', $rubro->alias, ['class' => 'form-control', 'required', 'placeholder' => __('Alias')]); ?>

                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('category', 'Categoría' . ':*'); ?>

                        <?php echo Form::select(
                            'category',
                            ['ingreso' => 'Ingreso', 'deduccion' => 'Deducción'],
                            !empty($rubro->category) ? $rubro->category : null,
                            ['class' => 'form-control', 'id' => 'category', 'required', 'placeholder' => __('messages.please_select')],
                        ); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('tipo', 'Tipo (Indica el valor al crear un rubro al colaborador)' . ':*'); ?>

                        <?php echo Form::select(
                            'tipo',
                            [
                                'monto' => 'Monto',
                                'cant_salarios' => 'Cant. Salarios',
                                'cant_horas' => 'Cant. Horas',
                                'cant_dias' => 'Cant. Días',
                            ],
                            !empty($rubro->tipo) ? $rubro->tipo : null,
                            ['class' => 'form-control', 'id' => 'tipo', 'required', 'placeholder' => __('messages.please_select')],
                        ); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('tipo_calculo', 'Tipo calculo' . ':*'); ?>

                        <?php echo Form::select(
                            'tipo_calculo',
                            ['normal' => 'Normal', 'extra_diurna' => 'Extra diurna', 'doble' => 'Doble'],
                            !empty($rubro->tipo_calculo) ? $rubro->tipo_calculo : null,
                            ['class' => 'form-control', 'id' => 'tipo_calculo', 'required', 'placeholder' => __('messages.please_select')],
                        ); ?>

                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('status', 'Estado' . ':*'); ?>

                        <?php echo Form::select(
                            'status',
                            ['1' => 'Activo', '0' => 'Inactivo'],
                            $rubro->status,
                            ['class' => 'form-control', 'id' => 'status', 'required', 'placeholder' => __('messages.please_select')],
                        ); ?>

                    </div>
                <?php echo $__env->renderComponent(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button"><?php echo app('translator')->get('messages.update'); ?></button>
            </div>
        </div>
        <?php echo Form::close(); ?>

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('javascript'); ?>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/rubros/edit.blade.php ENDPATH**/ ?>
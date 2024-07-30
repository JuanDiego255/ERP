

<?php $__env->startSection('title', __('Agregar empleado')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo app('translator')->get('Agregar empleado'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php echo Form::open(['url' => action('EmployeeController@store'), 'method' => 'post', 'id' => 'user_add_form']); ?>

        <div class="row">
            <div class="col-md-12">
                <?php $__env->startComponent('components.widget'); ?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('name', __('Nombre Completo') . ':*'); ?>

                            <?php echo Form::text('name', null, ['class' => 'form-control','required', 'placeholder' => __('Nombre')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('telephone', __('Teléfono') . ':*'); ?>

                            <?php echo Form::text('telephone', null, ['class' => 'form-control','required', 'placeholder' => __('Teléfono')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('celular', __('Celular') . ':'); ?>

                            <?php echo Form::text('celular', null, ['class' => 'form-control', 'placeholder' => __('Celular')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('email', __('Correo') . ':*'); ?>

                            <?php echo Form::text('email', null, ['class' => 'form-control','required', 'placeholder' => __('Correo')]); ?>

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <hr />
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('salario_base', __('Salario Base') . ':*'); ?>

                            <?php echo Form::text('salario_base', null, ['class' => 'form-control','required', 'placeholder' => __('Salario base')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('asociacion', __('Asociación') . ':'); ?>

                            <?php echo Form::text('asociacion', null, ['class' => 'form-control', 'placeholder' => __('Asociación')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('ccss', __('Deducción C.C.S.S') . ':*'); ?>

                            <?php echo Form::text('ccss', null, ['class' => 'form-control','required', 'placeholder' => __('Deducción C.C.S.S')]); ?>

                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('tipo_pago', 'Tipo pago' . ':*'); ?>

                        <?php echo Form::select('tipo_pago', ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'], !empty($employee->puesto) ? $employee->gender : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); ?>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('moneda_pago', __('Moneda pago') . ':'); ?>

                            <?php echo Form::text('moneda_pago', 'Colones', ['class' => 'form-control','readonly', 'placeholder' => __('Pago')]); ?>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('salario_hora', __('Salario por hora') . ':'); ?>

                            <?php echo Form::text('salario_hora', null, ['class' => 'form-control', 'placeholder' => __('Salario por hora')]); ?>

                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('puesto', 'Puesto' . ':*'); ?>

                        <?php echo Form::select('puesto', ['vendedor' => 'Vendedor', 'legal' => 'Legal', 'admin' => 'Adminstrativo'], !empty($employee->puesto) ? $employee->puesto : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); ?>

                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <?php echo Form::label('comision_ventas', __('Comisión por ventas') . ':'); ?>

                            <?php echo Form::text('comision_ventas', null, ['class' => 'form-control', 'placeholder' => __('Comisión por ventas')]); ?>

                        </div>
                    </div>
                <?php echo $__env->renderComponent(); ?>
            </div>
        </div>
        <?php echo $__env->make('admin.employees.rubros', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary pull-right" id="submit_user_button"><?php echo app('translator')->get('messages.save'); ?></button>
            </div>
        </div>
        <?php echo Form::close(); ?>

    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('javascript'); ?>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/employees/create.blade.php ENDPATH**/ ?>
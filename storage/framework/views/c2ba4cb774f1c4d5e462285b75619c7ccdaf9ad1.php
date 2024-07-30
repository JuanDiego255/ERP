

<?php $__env->startSection('title', __('Editar empleado')); ?>

<?php $__env->startSection('content'); ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><?php echo app('translator')->get('Editar empleado'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php echo Form::open([
            'url' => action('EmployeeController@update', [$employee->id]),
            'method' => 'PUT',
            'id' => 'user_edit_form',
        ]); ?>

        <div class="row">
            <div class="col-md-12">
                <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('name', __('Nombre') . ':*'); ?>

                            <?php echo Form::text('name', $employee->name, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Nombre'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('telephone', __('Teléfono') . ':*'); ?>

                            <?php echo Form::text('telephone', $employee->telephone, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Teléfono'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('celular', __('Celular') . ':'); ?>

                            <?php echo Form::text('celular', $employee->celular, [
                                'class' => 'form-control',
                                'placeholder' => __('Celular'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('email', __('Correo') . ':*'); ?>

                            <?php echo Form::text('email', $employee->email, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Correo'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('salario_base', __('Salario base') . ':*'); ?>

                            <?php echo Form::text('salario_base', $employee->salario_base, [
                                'class' => 'form-control',
                                'required',
                                'placeholder' => __('Salario base'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('ccss', __('Deducción C.C.S.S') . ':'); ?>

                            <?php echo Form::text('ccss', $employee->ccss, [
                                'class' => 'form-control',
                                'placeholder' => __('Deducción C.C.S.S'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('asociacion', __('Asociación') . ':'); ?>

                            <?php echo Form::text('asociacion', $employee->asociacion, [
                                'class' => 'form-control',
                                'placeholder' => __('Asociación'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('tipo_pago', 'Tipo pago' . ':'); ?>

                        <?php echo Form::select(
                            'tipo_pago',
                            ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'],
                            !empty($employee->tipo_pago) ? $employee->tipo_pago : null,
                            ['class' => 'form-control', 'id' => 'gender', 'required', 'placeholder' => __('messages.please_select')],
                        ); ?>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('moneda_pago', __('Moneda pago') . ':'); ?>

                            <?php echo Form::text('moneda_pago', $employee->moneda_pago, [
                                'class' => 'form-control',
                                'readonly',
                                'placeholder' => __('Moneda pago'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('salario_hora', __('Salario por hora') . ':'); ?>

                            <?php echo Form::text('salario_hora', $employee->salario_hora, [
                                'class' => 'form-control',
                                'placeholder' => __('Salario por hora'),
                            ]); ?>

                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <?php echo Form::label('puesto', 'Puesto' . ':*'); ?>

                        <?php echo Form::select('puesto', ['vendedor' => 'Vendedor', 'legal' => 'Legal', 'admin' => 'Adminstrativo'], !empty($employee->puesto) ? $employee->puesto : null, ['class' => 'form-control', 'id' => 'gender','required', 'placeholder' => __( 'messages.please_select') ]); ?>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php echo Form::label('comision_ventas', __('Comisión Ventas') . ':'); ?>

                            <?php echo Form::text('comision_ventas', $employee->comision_ventas, [
                                'class' => 'form-control',
                                'placeholder' => __('Comisión Ventas'),
                            ]); ?>

                        </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/employees/edit.blade.php ENDPATH**/ ?>
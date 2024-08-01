

<?php $__env->startSection('title', 'Detalles del empleado'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <h3>Detalles del empleado</h3>
            </div>
            <div class="col-md-4 col-xs-12 mt-15 pull-right">
                <?php echo Form::select('employee_id', $employees, $employee->id, [
                    'class' => 'form-control select2',
                    'id' => 'employee_id',
                ]); ?>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <h3 class="profile-username text-center">
                            <?php echo e($employee->name, false); ?>

                        </h3>

                        <p class="text-muted text-center" title="<?php echo app('translator')->get('user.role'); ?>">

                        </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?php echo app('translator')->get('Teléfono'); ?></b>
                                <a class="pull-right"><?php echo e($employee->telephone, false); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo app('translator')->get('Celular'); ?></b>
                                <a class="pull-right"><?php echo e($employee->celular, false); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo app('translator')->get('Correo'); ?></b>
                                <a class="pull-right"><?php echo e($employee->email, false); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo app('translator')->get('Puesto'); ?></b>
                                <a class="pull-right"><?php echo e($employee->puesto, false); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo app('translator')->get('Fecha de ingreso'); ?></b>
                                <a class="pull-right"><?php echo e(\Carbon::createFromTimestamp(strtotime($employee->created_at))->format(session('business.date_format')), false); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><?php echo e(__('Estado'), false); ?></b>
                                <?php if($employee->status == '1'): ?>
                                    <span class="label label-success pull-right">
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="label label-danger pull-right">
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.update')): ?>
                            <a href="<?php echo e(action('EmployeeController@edit', [$employee->id]), false); ?>"
                                class="btn btn-primary btn-block">
                                <i class="glyphicon glyphicon-edit"></i>
                                <?php echo app('translator')->get('messages.edit'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#user_info_tab" data-toggle="tab" aria-expanded="true"><i class="fas fa-user"
                                    aria-hidden="true"></i> <?php echo app('translator')->get('Información del empleado'); ?></a>
                        </li>

                        <li>
                            <a href="#documents_and_notes_tab" data-toggle="tab" aria-expanded="true"><i
                                    class="fas fa-briefcase" aria-hidden="true"></i> Rubros fijos</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="user_info_tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Moneda de pago'); ?>: <?php echo e($employee->moneda_pago, false); ?></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Tipo pago'); ?>: <?php echo e($employee->tipo_pago == 'quincenal' ? 'Quincenal' : 'Mensual', false); ?></strong></p>
                                    </div>                                    
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Salario por hora'); ?>: ₡<?php echo e(number_format($employee->salario_hora), false); ?></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Salario base'); ?>: ₡<?php echo e(number_format($employee->salario_base), false); ?></strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Comision ventas'); ?>: <?php echo e(!empty($employee->comision_ventas) ? $employee->comision_ventas : '--', false); ?></strong></p>
                                    </div>
                                    <?php
                                        $ccss = $employee->ccss;
                                        $salario_base = $employee->salario_base;
                                        $resultado = ($salario_base * $ccss) / 100;
                                    ?>
                                    <div class="col-md-4">
                                        <p><strong><?php echo app('translator')->get('Deduccion C.C.S.S'); ?>: ₡<?php echo e(number_format($resultado), false); ?></strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="documents_and_notes_tab">
                            <input type="hidden" name="employee_id" id="employee_id" value="<?php echo e($employee->id, false); ?>">
                            <div class="document_note_body">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <!-- document & note.js -->
    <?php echo $__env->make('admin.rubros.tab_rubros.document_and_note_js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#employee_id').change(function() {
                if ($(this).val()) {
                    window.location = "<?php echo e(url('/employees'), false); ?>/" + $(this).val();
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/employees/show.blade.php ENDPATH**/ ?>
<div class="modal-dialog modal-lg" role="document">
    <?php echo Form::open(['action' => 'EmployeeController@storeRubro', 'id' => 'rubros_employee_form', 'method' => 'post']); ?>

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">
                <?php echo app('translator')->get('Agregar rubro'); ?>
            </h4>
        </div>
        <!-- model id like project_id, user_id -->
        <?php echo Form::hidden('employee_id', $employee_id, ['class' => 'form-control']); ?>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-4">
                    <?php echo Form::label('tipo', 'Tipo' . ':*'); ?>

                    <?php echo Form::select('tipo', ['quincenal' => 'Quincenal', 'mensual' => 'Mensual'], null, [
                        'class' => 'form-control',
                        'id' => 'tipo',
                        'required',
                        'placeholder' => __('messages.please_select'),
                    ]); ?>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('rubro_id', 'Rubro' . ':*'); ?>

                        <?php echo Form::select('rubro_id', $rubros, null, [
                            'class' => 'form-control',
                            'id' => 'rubro_id',
                            'required',
                            'placeholder' => __('messages.please_select'),
                        ]); ?>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-group">
                            <?php echo Form::label('valor', __('Valor') . ':*'); ?>

                            <?php echo Form::text('valor', null, ['class' => 'form-control', 'required', 'placeholder' => __('Valor')]); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked name="status" value="1"> <?php echo app('translator')->get('Estado'); ?>
                                <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php echo app('translator')->get('Habilita este rubro a la hora de generar la planilla'); ?>"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">
                <?php echo app('translator')->get('messages.save'); ?>
            </button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
                <?php echo app('translator')->get('messages.close'); ?>
            </button>
        </div>
    </div><!-- /.modal-content -->
    <?php echo Form::close(); ?>

</div><!-- /.modal-dialog -->
<?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/rubros/tab_rubros/create.blade.php ENDPATH**/ ?>
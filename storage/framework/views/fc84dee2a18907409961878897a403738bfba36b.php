
<?php $__env->startSection('title', __( 'Empleados' )); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo app('translator')->get( 'Empleados' ); ?>
        <small><?php echo app('translator')->get( 'Administrar empleados' ); ?></small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => __( 'Todos los empleados' )]); ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.create')): ?>
            <?php $__env->slot('tool'); ?>
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" 
                    href="<?php echo e(action('EmployeeController@create'), false); ?>" >
                    <i class="fa fa-plus"></i> <?php echo app('translator')->get( 'messages.add' ); ?></a>
                 </div>
            <?php $__env->endSlot(); ?>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.view')): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="employees_table">
                    <thead>
                        <tr>
                            <th><?php echo app('translator')->get( 'Empleado' ); ?></th>
                            <th><?php echo app('translator')->get( 'Telefono' ); ?></th>
                            <th>E-mail</th>
                            <th><?php echo app('translator')->get( 'Celular' ); ?></th>
                            <th><?php echo app('translator')->get( 'Fecha ingreso' ); ?></th>
                            <th><?php echo app('translator')->get( 'messages.action' ); ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        <?php endif; ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="modal fade user_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        var users_table = $('#employees_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/employees',
                    columnDefs: [ {
                        "targets": [4],
                        "orderable": false,
                        "searchable": false
                    } ],
                    "columns":[
                        {"data":"name"},
                        {"data":"telephone"},
                        {"data":"email"},
                        {"data":"celular"},
                        {"data":"created_at"},
                        {"data":"action"}
                    ]
                });
        $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: 'Este empleado será eliminado, desea continuar?',
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                console.log(result)
                                toastr.success(result.msg);
                                users_table.ajax.reload();
                            } else {
                                console.log(result)
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
             });
        });        
    });   
    
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>
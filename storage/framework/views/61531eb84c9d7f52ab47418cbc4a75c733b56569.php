<?php $__env->startSection('title', 'IBPT'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>IBPT
        <small>Gerencia Tabelas</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => 'Lista de tabelas']); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.create')): ?>
    <?php $__env->slot('tool'); ?>
    <div class="box-tools">
        <a class="btn btn-block btn-primary" 
        href="/ibpt/new" >
        <i class="fa fa-plus"></i> <?php echo app('translator')->get( 'messages.add' ); ?></a>
    </div>
    <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.view')): ?>

    <div class="row">
        <?php $__currentLoopData = $tabelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6">
            <div class="card">
                <div class="card-content">
                    <h3>
                        <strong style="margin-right: 5px;" class="text-info"><?php echo e($i->uf, false); ?></strong> <?php echo e($i->versao, false); ?> - <?php echo e(\Carbon\Carbon::parse($i->updated_at)->format('d/m/Y H:i:s'), false); ?>


                        <a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/ibpt/delete/<?php echo e($i->id, false); ?>" }else{return false} })' href="#!">
                            <i class="fa fa-trash text-danger"></i>
                        </a>
                        <a href="/ibpt/edit/<?php echo e($i->id, false); ?>">
                            <i class="fa fa-edit" aria-hidden="true"></i>
                        </a>
                        <a href="/ibpt/list/<?php echo e($i->id, false); ?>">
                            <i class="fa fa-list text-success" aria-hidden="true"></i>
                        </a>
                    </h3>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php endif; ?>
    <?php echo $__env->renderComponent(); ?>


</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/ibpt/list.blade.php ENDPATH**/ ?>
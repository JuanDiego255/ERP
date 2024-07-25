<?php $__env->startSection('title', 'Lista de NFe'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>NFe
        <small>Lista</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <p>
        <i class="fa fas fa-arrow-circle-down text-success"></i>
        Xml Aprovado
    </p>

    <p>
        <i class="fa fas fa-arrow-circle-down text-danger"></i>
        Xml Cancelado
    </p>
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => 'NFe Lista']); ?>

    <?php if(isset($msg) && sizeof($msg) > 0): ?>
    <?php $__currentLoopData = $msg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <h5 style="color: red"><?php echo e($m, false); ?></h5>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <form action="/nfe/filtro" method="get">
        <div class="row">
            <div class="col-sm-2 col-lg-3">
                <div class="form-group">
                  <label for="product_custom_field2">Data inicial:</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input required class="form-control start-date-picker" placeholder="Data inicial" value="<?php echo e(isset($data_inicio) ? $data_inicio : ''); ?>" data-mask="00/00/0000" name="data_inicio" type="text" id="">
                </div>

            </div>
        </div>
        <div class="col-sm-2 col-lg-3">
            <div class="form-group">
                <label for="product_custom_field2">Data final:</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input required class="form-control start-date-picker" placeholder="Data final" data-mask="00/00/0000" name="data_final" type="text" value="<?php echo e(isset($data_final) ? $data_final : ''); ?>">
                </div>

            </div>
        </div>
        <?php if(is_null($default_location)): ?>

        <div class="col-sm-2 col-lg-3">
            <br>
            <div class="form-group" style="margin-top: 8px;">

                <?php echo Form::select('select_location_id', $business_locations, $select_location_id, ['class' => 'form-control input-sm', 'placeholder' => 'Todas','id' => 'select_location_id', '', 'autofocus'], $bl_attributes); ?>


            </div>

        </div>
        <?php endif; ?>

        <div class="col-sm-2 col-lg-3">
            <div class="form-group"><br>
                <button style="margin-top: 5px;" class="btn btn-block btn-primary">Filtrar</button>
            </div>
        </div>

    </div>
</form>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.view')): ?>
<?php if(sizeof($notasAprovadas) > 0): ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users_table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Número</th>
                <th>Chave</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $notasAprovadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(\Carbon\Carbon::parse($n->created_at)->format('d/m/Y H:i:s'), false); ?></td>
                <td><?php echo e($n->numero_nfe, false); ?></td>
                <td><?php echo e($n->chave, false); ?></td>
                <td><?php echo e($n->estado, false); ?></td>
                <td>
                    <?php if($n->estado == 'APROVADO'): ?>
                    <a title="Baixar XML Aprovado" target="_blank" href="/nfe/baixarXml/<?php echo e($n->id, false); ?>">
                        <i class="fa fas fa-arrow-circle-down text-success"></i>
                    </a>

                    <a title="Imprimir" target="_blank" href="/nfe/imprimir/<?php echo e($n->id, false); ?>">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </a>
                    <?php elseif($n->estado == 'CANCELADO'): ?>
                    <a title="Baixar XML Cancelado" target="_blank" href="/nfe/baixarXmlCancelado/<?php echo e($n->id, false); ?>">
                        <i class="fa fas fa-arrow-circle-down text-danger"></i>
                    </a>
                    <?php endif; ?>


                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>


</div>

<div class="row">
    <div class="col-sm-2 col-lg-4">
        <a target="_blank" href="/nfe/baixarZipXmlAprovado" style="margin-top: 5px;" class="btn btn-block btn-success">Download XML Aprovado</a>
    </div>

</div>

<?php endif; ?>

<div class="clearfix"></div>
<br>

<?php if(sizeof($notasCanceladas) > 0): ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped" id="users_table">
        <thead>
            <tr>
                <th>Data</th>
                <th>Número</th>
                <th>Chave</th>
                <th>Estado</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $notasCanceladas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(\Carbon\Carbon::parse($n->created_at)->format('d/m/Y H:i:s'), false); ?></td>
                <td><?php echo e($n->numero_nfe, false); ?></td>
                <td><?php echo e($n->chave, false); ?></td>
                <td><?php echo e($n->estado, false); ?></td>
                <td>
                    <?php if($n->estado == 'APROVADO'): ?>
                    <a title="Baixar XML Aprovado" target="_blank" href="/nfe/baixarXml/<?php echo e($n->id, false); ?>">
                        <i class="fa fas fa-arrow-circle-down text-success"></i>
                    </a>

                    <a title="Imprimir" target="_blank" href="/nfe/imprimir/<?php echo e($n->id, false); ?>">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </a>
                    <?php elseif($n->estado == 'CANCELADO'): ?>
                    <a title="Baixar XML Cancelado" target="_blank" href="/nfe/baixarXmlCancelado/<?php echo e($n->id, false); ?>">
                        <i class="fa fas fa-arrow-circle-down text-danger"></i>
                    </a>
                    <?php endif; ?>


                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>


</div>

<div class="row">

    <div class="col-sm-2 col-lg-4">
        <a target="_blank" href="/nfe/baixarZipXmlReprovado" style="margin-top: 5px;" class="btn btn-block btn-danger">Download XML Cancelado</a>
    </div>
</div>

<?php endif; ?>

<?php if(sizeof($notasCanceladas) == 0 && sizeof($notasAprovadas) == 0): ?>
<p>Filtro por data para encontrar os arquivos!</p>
<?php endif; ?>

<?php endif; ?>
<?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
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


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/nfe/lista.blade.php ENDPATH**/ ?>
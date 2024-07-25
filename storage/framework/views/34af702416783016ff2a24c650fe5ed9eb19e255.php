<?php $__env->startSection('title', 'IBPT'); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
    .loader {
        border: 12px solid #F4F5FB; /* Light grey */
        border-top: 12px solid #1572E8; /* Blue */
        border-radius: 50%;
        width: 30px;
        height: 30px;
        float: right;
        animation: spin 2s linear infinite;
    }

    @keyframes  spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }
</style>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>IBPT
        <small><?php if(isset($ibpt)): ?> Atualizar <?php else: ?> Inserir <?php endif; ?> Tabela <?php echo e((isset($ibpt) ? $ibpt->uf : ''), false); ?></small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>
<?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => 'IBPT ' . (isset($ibpt) ? $ibpt->uf : '')]); ?>

<?php echo Form::open(['url' => '/ibpt/save', 'method' => 'post', 'id' => 'ibpt_form', 'files' => true ]); ?>


<div class="row">
    <div class="col-sm-3 col-sm-offset-2">
        <div class="form-group">
            <label for="file">Tabela:</label>
            <input required name="file" accept=".csv" type="file" id="file">
            <p>Arquivo .csv</p>
        </div>
    </div>

    <input type="hidden" name="ibpt_id" value="<?php if(isset($ibpt)): ?> <?php echo e($ibpt->id, false); ?> <?php else: ?> 0 <?php endif; ?>">
    <?php if(isset($estados)): ?>
    <div class="col-md-2">
        <div class="form-group">

            <?php echo Form::label('uf', 'UF' . ':'); ?>

            <?php echo Form::select('uf', $estados, '', ['class' => 'form-control select2', 'required']); ?>

        </div>
    </div>
    <?php endif; ?>

    <div class="col-sm-2">
        <div class="form-group">
            <?php echo Form::label('versao', 'Versão' . ':*'); ?>

            <?php echo Form::text('versao', (isset($ibpt) ? $ibpt->versao : ''), ['class' => 'form-control', 'required', 
            'placeholder' => 'Versão']); ?>

        </div>
    </div>


    <div class="col-md-12">

        <div style="display: none" class="loader"></div>

        <button type="submit" class="btn btn-primary pull-right" id="submit_user_button"><?php if(isset($ibpt)): ?> Editar <?php else: ?> Salvar <?php endif; ?></button>
    </div>
</div>



<?php echo Form::close(); ?>



<?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<!-- Main content -->
<section class="content">


</section>
<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">

    $( "#ibpt_form" ).submit(function( event ) {
      $('.loader').css('display', 'block')
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/ibpt/new.blade.php ENDPATH**/ ?>
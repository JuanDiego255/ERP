<?php $__env->startSection('title', 'Adicionar Veiculo'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Adicionar </h1>
</section>

<!-- Main content -->
<section class="content">
  <?php echo Form::open(['url' => action('VeiculoController@save'), 'method' => 'post', 'id' => 'veiculo_form' ]); ?>

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget', ['class' => 'box-primary']); ?>
      
      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('placa', 'Placa' . ':*'); ?>

          <?php echo Form::text('placa', null, ['class' => 'form-control', 'required', 'placeholder' => 'Placa', 'data-mask="AAA-AAAA"' ]); ?>

        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <?php echo Form::label('uf', 'UF' . ':*'); ?>

          <?php echo Form::select('uf', $ufs, '', ['class' => 'form-control select2', 'id' => 'contact_type', 'required']); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('modelo', 'Modelo' . ':*'); ?>

          <?php echo Form::text('modelo', null, ['class' => 'form-control', 'required', 'placeholder' => 'Modelo' ]); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('marca', 'Marca' . ':*'); ?>

          <?php echo Form::text('marca', null, ['class' => 'form-control', 'required', 'placeholder' => 'Marca' ]); ?>

        </div>
      </div>
      
      <div class="clearfix"></div>


      <div class="col-md-2">
        <div class="form-group">
          <?php echo Form::label('cor', 'Cor' . ':*'); ?>

          <?php echo Form::text('cor', null, ['class' => 'form-control', 'required', 'placeholder' => 'Cor' ]); ?>

        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('tipo_carroceira', 'Tipo da carroceria' . ':*'); ?>

          <?php echo Form::select('tipo_carroceira', $tiposCarroceria, '', ['class' => 'form-control select2', 'id' => 'contact_type', 'required']); ?>

        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('tipo_rodado', 'Tipo de rodado' . ':*'); ?>

          <?php echo Form::select('tipo_rodado', $tiposRodado, '', ['class' => 'form-control select2', 'id' => 'contact_type', 'required']); ?>

        </div>
      </div>

      <div class="clearfix"></div>

      <div class="col-md-2">
        <div class="form-group">
          <?php echo Form::label('tara', 'Tara' . ':*'); ?>

          <?php echo Form::text('tara', null, ['class' => 'form-control', 'required', 'placeholder' => 'Tara', 'data-mask="0000000"' ]); ?>

        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <?php echo Form::label('capacidade', 'Capacidade' . ':*'); ?>

          <?php echo Form::text('capacidade', null, ['class' => 'form-control', 'required', 'placeholder' => 'Capacidade', 'data-mask="0000000"' ]); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('proprietario_nome', 'Nome Proprietário' . ':*'); ?>

          <?php echo Form::text('proprietario_nome', null, ['class' => 'form-control', 'required', 'placeholder' => 'Nome Proprietário' ]); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('proprietario_documento', 'Documento Proprietário' . ':*'); ?>

          <?php echo Form::text('proprietario_documento', null, ['class' => 'form-control cpf_cnpj', 'required', 'placeholder' => 'Documento Proprietário' ]); ?>

        </div>
      </div>

      <div class="clearfix"></div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('proprietario_ie', 'I.E Proprietário' . '*:'); ?>

          <?php echo Form::text('proprietario_ie', null, ['class' => 'form-control', 'required', 'placeholder' => 'I.E Proprietário' ]); ?>

        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          <?php echo Form::label('proprietario_uf', 'Proprietário UF' . ':*'); ?>

          <?php echo Form::select('proprietario_uf', $ufs, '', ['class' => 'form-control select2', 'id' => 'contact_type', 'required']); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('proprietario_tp', 'Tipo de Proprietário' . ':*'); ?>

          <?php echo Form::select('proprietario_tp', $tiposProprietario, '', ['class' => 'form-control select2', 'id' => 'contact_type', 'required']); ?>

        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('rntrc', 'RNTRC' . '*:'); ?>

          <?php echo Form::text('rntrc', null, ['class' => 'form-control', 'required, minlength:8', 'placeholder' => 'RNTRC', 'required' ]); ?>

        </div>
      </div>

      
      <?php echo $__env->renderComponent(); ?>
    </div>


  </div>

  <?php if(!empty($form_partials)): ?>
  <?php $__currentLoopData = $form_partials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php echo $partial; ?>

  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary pull-right" id="submit_button"><?php echo app('translator')->get( 'messages.save' ); ?></button>
    </div>
  </div>
  <?php echo Form::close(); ?>

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('javascript'); ?>
  <script type="text/javascript">
    $(document).ready(function(){
    });
    $(document).on('click', '#submit_button', function(e) {

      e.preventDefault();

      $('form#veiculo_form').validate()
      if ($('form#veiculo_form').valid()) {
        $('form#veiculo_form').submit();
      }
    })
  </script>
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/veiculos/register.blade.php ENDPATH**/ ?>
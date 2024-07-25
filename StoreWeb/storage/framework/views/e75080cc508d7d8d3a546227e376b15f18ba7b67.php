<?php $__env->startSection('title', 'Emitir NF-e'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Emitir NF-e</h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget'); ?>
      
      <input type="hidden" id="id" value="<?php echo e($transaction->id, false); ?>" name="">
      <div class="col-md-5">
        <h4>Local: <strong><?php echo e($transaction->location->name, false); ?> - <?php echo e($transaction->location->location_id, false); ?></strong></h4>
        <h4>Ultimo numero NF-e: <strong><?php echo e($transaction->lastNFe($transaction), false); ?></strong></h4>
        <h4>Natureza de Operação: <strong><?php echo e($transaction->natureza->natureza, false); ?></strong></h4>
        <h4>Cliente: <strong><?php echo e($transaction->contact->name, false); ?></strong></h4>
        <h4>CNPJ: <strong><?php echo e($transaction->contact->cpf_cnpj, false); ?></strong></h4>
        <h4>Valor: <strong><?php echo e(number_format($transaction->final_total, 2, ',', ''), false); ?></strong></h4>
        <h4>Froma de pagamento: <strong><?php echo e($payment_method, false); ?></strong></h4>

      </div>
      
      <div class="clearfix"></div>


      <div class="col-md-12">
        <a class="btn btn-lg btn-primary" target="_blank" href="/nfe/renderizar/<?php echo e($transaction->id, false); ?>" id="submit_user_button">Renderizar</a>
        <a class="btn btn-lg btn-danger" target="_blank" href="/nfe/gerarXml/<?php echo e($transaction->id, false); ?>" id="submit_user_button">Gerar XML</a>
        <a class="btn btn-lg btn-success" id="send-sefaz">Transmitir para Sefaz</a>
      </div>

      
      <?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    </div>

  </div>

  <input type="hidden" id="token" value="<?php echo e(csrf_token(), false); ?>" name="">

  <br>
  <div class="row" id="action" style="display: none">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget'); ?>
      <div class="info-box-content">
        <div class="col-md-4 col-md-offset-4">

          <span class="info-box-number total_purchase">
            <strong id="acao"></strong>
            <i class="fas fa-spinner fa-pulse fa-spin fa-fw margin-bottom"></i>
          </span>
        </div>
      </div>
      <?php if (isset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449)): ?>
<?php $component = $__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449; ?>
<?php unset($__componentOriginalb3782d3ccf49a4b25eee4a800b6cc4ec3dc93449); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>

    </div>
  </div>

  <?php $__env->stopSection(); ?>



  <?php $__env->startSection('javascript'); ?>
  <script type="text/javascript">
      // swal("Good job!", "You clicked the button!", "success");
      var path = window.location.protocol + '//' + window.location.host
      var notClick = false;

      $('#send-sefaz').click(() => {
        if(!notClick){
          notClick = true;
          $('#send-sefaz').addClass('disabled')
          $('#action').css('display', 'block')
          let token = $('#token').val();
          let id = $('#id').val();

          setTimeout(() => {
            $('#acao').html('Gerando XML');
          }, 50);

          setTimeout(() => {
            $('#acao').html('Assinando o arquivo');
          }, 800);

          setTimeout(() => {
            $('#acao').html('Transmitindo para sefaz');
          }, 1500);

          $.ajax
          ({
            type: 'POST',
            data: {
              id: id,
              _token: token
            },
            url: path + '/nfe/transmtir',
            dataType: 'json',
            success: function(e){
              console.log(e)

              swal("sucesso", "NF-e emitida, recibo: " + e.recibo, "success")
              .then(() => {
                window.open(path + '/nfe/imprimir/'+id)
                location.reload()
              });
              $('#action').css('display', 'none')


            }, error: function(e){

              console.log(e)
              try{
                if(e.status == 402){
                  swal("Erro ao transmitir", e.responseJSON, "error");
                  $('#action').css('display', 'none')

                }else if(e.status == 407){
                  swal("Erro ao criar Xml", e.responseJSON, "error");
                  $('#action').css('display', 'none')

                }
                else if(e.status == 404){
                  $('#action').css('display', 'none')
                  swal("Erro", e, "error");

                }
                else{
                  $('#action').css('display', 'none')
                  let jsError = JSON.parse(e.responseJSON)
                  console.log(jsError)
                  swal("Erro ao transmitir", "[" + jsError.protNFe.infProt.cStat +  "] - " + jsError.protNFe.infProt.xMotivo, "error");

                }
              }catch{
                try{
                  swal("Erro", e.responseJSON, "error");
                }catch{
                  let js = e.responseJSON
                  swal("Erro", js.message, "error");


                }
              }
            }

          })
        }
      })


    </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/marcos/Documents/laravel_novo/ultimate/resources/views/nfe/novo.blade.php ENDPATH**/ ?>
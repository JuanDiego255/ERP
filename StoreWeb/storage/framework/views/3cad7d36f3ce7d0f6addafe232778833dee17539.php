<?php $__env->startSection('title', 'Lista de Documentos'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Manifesto
        <small>Gerencia documentos</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <?php if(sizeof($business_locations) > 1): ?>
    <?php $__env->startComponent('components.filters', ['title' => __('report.filters')]); ?>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo Form::label('sell_list_filter_location_id',  __('purchase.business_location') . ':'); ?>


            <?php echo Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); ?>

        </div>
    </div>
    <?php if (isset($__componentOriginal46491110b120d4eaaef80fd23f090976133576c9)): ?>
<?php $component = $__componentOriginal46491110b120d4eaaef80fd23f090976133576c9; ?>
<?php unset($__componentOriginal46491110b120d4eaaef80fd23f090976133576c9); ?>
<?php endif; ?>
<?php echo $__env->renderComponent(); ?>
    <?php endif; ?>
    <?php $__env->startComponent('components.widget', ['class' => 'box-primary', 'title' => 'Todas os documentos']); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.create')): ?>
    <?php $__env->slot('tool'); ?>
    <div class="box-tools">
        <a class="btn btn-block btn-success" 
        href="/manifesto/buscarNovosDocumentos" >
        <i class="fa fa-retweet"></i> Novos documentos</a>
    </div>
    <?php $__env->endSlot(); ?>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user.view')): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="users_table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Localização</th>
                    <th>Documento</th>
                    <th>Valor</th>
                    <th>Data emissão</th>
                    <th>Num. Protocolo</th>
                    <th>Chave</th>
                    <th>Estado</th>
                    <th>Ação</th>
                </tr>
            </thead>
        </table>
    </div>
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




<div class="modal fade" id="modal1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <form method="get" action="/manifesto/manifestar">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    <h5 class="modal-title" id="exampleModalLabel">Manifestação de Destinatário</h5>

                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" />
                    <div class="row">
                        <div class="form-group validated col-sm-6 col-lg-6">
                            <label class="col-form-label">Unidade de venda</label>
                            <select class="form-control" name="evento" id="tipo_evento">
                                <option value="1">Ciencia de operção</option>
                                <option value="2">Confirmação</option>
                                <option value="3">Desconhecimento</option>
                                <option value="4">Operação não realizada</option>
                            </select>
                        </div>

                        <div class="form-group validated col-sm-12 col-lg-12" id="div-just" style="display: none">
                            <label class="col-form-label">Justificativa</label>
                            <div class="">
                                <input id="justificativa" type="text" class="form-control" name="justificativa" value="">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Fechar</button>
                    <button type="submit" id="salvarEdit" class="btn btn-success font-weight-bold spinner-white spinner-right">Manifestar</button>
                </div>
            </div>
        </div>
    </form>
</div>



<!-- /.content -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
<script type="text/javascript">

    function openModal(id){
        $('#modal1').modal('show')
        $('#id').val(id)
    }

    $('#tipo_evento').change(() => {
        let tipo = $('#tipo_evento').val();
        if(tipo == 3 || tipo == 4){
            $('#div-just').css('display', 'block')
        }else{
            $('#div-just').css('display', 'none')
        }
    })
    //Roles table
    $(document).ready( function(){

        var users_table = $('#users_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: 
            {
                'url': '/manifesto',
                "data": function ( d ) {
                    console.log(d)
                }
            },

            columns:[
            {"data":"nome", "searchable": true},
            {"data":"location_id", "searchable": true},
            {"data":"documento"},
            {"data":"valor"},
            {"data":"data_emissao"},
            {"data":"num_prot"},
            {"data":"chave"},
            {"data":"tipo"},
            {"data":"action"}
            ]
        });

        
        $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_user,
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
                            toastr.success(result.msg);
                            users_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
      });
        
    });

    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #sell_list_filter_payment_status, #created_by, #sales_cmsn_agnt, #service_staffs',  function() {
        let location_id = $('#sell_list_filter_location_id').val();

        var users_table = $('#users_table').DataTable({
            "bDestroy": true,
            processing: true,
            serverSide: true,
            ajax: 
            {
                'url': '/manifesto/byLocation/'+location_id,

            },

            columns:[
            {"data":"nome", "searchable": true},
            {"data":"location_id", "searchable": true},
            {"data":"documento"},
            {"data":"valor"},
            {"data":"data_emissao"},
            {"data":"num_prot"},
            {"data":"chave"},
            {"data":"tipo"},
            {"data":"action"}
            ]
        });
    });
    
    
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/manifesto/list.blade.php ENDPATH**/ ?>
<style type="text/css">
    .loader {
      border: 16px solid #f3f3f3; /* Light grey */
      border-top: 16px solid #3498db; /* Blue */
      border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 2s linear infinite;
  }

  @keyframes  spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }
</style>
<?php $__env->startSection('title', 'Busca de documentos'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Manifesto

    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    <?php $__env->startComponent('components.widget', ['class' => 'box-danger', 'title' => 'Busca de documentos']); ?>
    <input type="hidden" value="<?php echo e(is_null($default_location) ? 1 : 0, false); ?>" id="default_location">
    <div class="row">
        <?php if(is_null($default_location)): ?>

        <div class="col-sm-2 col-lg-3">
            <br>
            <div class="form-group" style="margin-top: 8px;">
                <?php echo Form::select('select_location_id', $business_locations, $select_location_id, ['class' => 'form-control input-sm', 'placeholder' => 'Selecione o local','id' => 'select_location_id', '', 'autofocus'], $bl_attributes); ?>


            </div>

        </div>

        <div class="col-sm-2 col-lg-3">
            <div class="form-group"><br>
                <button onclick="buscarPorLocation()" style="margin-top: 5px;" class="btn btn-block btn-primary">Buscar</button>
            </div>
        </div>

        <?php endif; ?>

    </div>

    <p style="display: none" id="aguarde" class="text-info">Consultado novos documentos, aguarde ...</p>
    <p id="sem-resultado" style="display: none" class="center-align text-danger">Nenhum novo resultado...</p>
    <div style="display: none" class="loader" id="loader"></div> 

    <div class="table-responsive" id="tbl" style="display: none">

        <p class="text-danger">*Documentos inseridos!!</p>
        <a href="/manifesto" class="btn btn-info">Voltar</a>
        <br>
        <table class="table table-bordered table-striped" id="users_table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Documento</th>
                    <th>Valor</th>
                    <th>Chave</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

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
    //Roles table
    var path = window.location.protocol + '//' + window.location.host

    $(document).ready( function(){
        let default_location = $('#default_location').val();
        if(default_location == 0){
            filtrar()
        }
    });

    function filtrar(){
        $('#aguarde').css('display', 'block')
        $('#loader').css('display', 'block')
        $.get(path + '/manifesto/getDocumentosNovos')
        .done(value => {
            console.log(value)
            $('#aguarde').css('display', 'none')
            $('#loader').css('display', 'none')

            if(value.length > 0){
                montaTabela(value, (html) => {

                    console.log(html)
                    $('#users_table tbody').html(html)
                    $('#tbl').css('display', 'block')
                })
                swal("Sucesso", "Foram encontrados " + value.length + " novos registros!", "success")
            }else{
                swal("Sucesso", "A requisição obteve sucesso, porém sem novos registros!!", "success")
                $('#sem-resultado').css('display', 'block')

            }

        })
        .fail(err => {
            console.log(err)
            $('#loader').css('display', 'none')
            $('#aguarde').css('display', 'none')
            swal("Erro", "Erro ao realizar consulta", "warning")
        })
    }

    function buscarPorLocation(){
        $('#aguarde').css('display', 'block')
        $('#loader').css('display', 'block')
        let location = $('#select_location_id').val();
        if(location){
            $.get(path + '/manifesto/getDocumentosNovosLocation', 
                {location: location})
            .done(value => {
                console.log(value)
                $('#aguarde').css('display', 'none')
                $('#loader').css('display', 'none')

                if(value.length > 0){
                    montaTabela(value, (html) => {

                        console.log(html)
                        $('#users_table tbody').html(html)
                        $('#tbl').css('display', 'block')
                    })
                    swal("Sucesso", "Foram encontrados " + value.length + " novos registros!", "success")
                }else{
                    swal("Sucesso", "A requisição obteve sucesso, porém sem novos registros!!", "success")
                    $('#sem-resultado').css('display', 'block')

                }

            })
            .fail(err => {
                console.log(err)
                $('#loader').css('display', 'none')
                $('#aguarde').css('display', 'none')
                swal("Erro", "Erro ao realizar consulta", "warning")
            })
        }else{
            swal("Erro", "Selecine a localização!!", "error")
        }
    }

    function montaTabela(array, call){
        let html = '';
        array.map(v => {
            console.log(v)
            html += '<tr>';
            html += '<td>' + v.nome[0] +'</td>';
            html += '<td>' + v.documento[0] +'</td>';
            html += '<td>' + v.valor[0] +'</td>';
            html += '<td>' + v.chave[0] +'</td>';
            html += '</tr>';
        })

        call(html)
    }

    
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Nova pasta\gestao\resources\views/manifesto/novos_docs.blade.php ENDPATH**/ ?>
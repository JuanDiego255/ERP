<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    <?php echo Form::open(['url' => action('BusinessLocationController@store'), 'method' => 'post', 'id' => 'business_location_add_form', 'files' => true ]); ?>


    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title"><?php echo app('translator')->get( 'business.add_business_location' ); ?></h4>
    </div>

    <div class="modal-body">
      <div class="row">

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('cnpj', 'CNPJ' . ':*'); ?>

            <?php echo Form::text('cnpj', '', ['class' => 'form-control cpf_cnpj', 'required',
            'placeholder' => 'CNPJ']); ?>

          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <?php echo Form::label('tipo', 'UF' . ':'); ?>

            <div class="input-group" style="width: 100%;">
              <span class="input-group-addon">
                <a onclick="buscaDados()"><i class="fa fa-search"></i></a>
              </span>
              <?php echo Form::select('uf', $estados, '', ['id' => 'uf2', 'class' => 'form-control select2 featured-field']); ?>

            </div>
          </div>
        </div>
        <div class="col-sm-7">
          <div class="form-group">
            <?php echo Form::label('name', 'Nome Fantasia' . ':*'); ?>

            <?php echo Form::text('name', '', ['class' => 'form-control', 'required', 'placeholder' => 'Nome' ]); ?>

          </div>
        </div>

        <div class="col-sm-12">
          <div class="form-group">
            <?php echo Form::label('razao_social',__('business.business_razao') . ':*'); ?>

            <?php echo Form::text('razao_social', '', ['class' => 'form-control', 'required',
            'placeholder' => __('business.business_razao')]); ?>

            <?php if($errors->has('razao_social')): ?>
            <span class="text-danger"><?php echo e($errors->first('razao_social'), false); ?></span>
            <?php endif; ?>

          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-sm-2">
          <div class="form-group">
            <?php echo Form::label('location_id', __( 'lang_v1.location_id' ) . ':'); ?>

            <?php echo Form::text('location_id', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.location_id' ) ]); ?>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('landmark', __( 'business.landmark' ) . ':'); ?>

            <?php echo Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => __( 'business.landmark' ) ]); ?>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('zip_code', __( 'business.zip_code' ) . ':*'); ?>

            <?php echo Form::text('zip_code', '', ['class' => 'form-control', 'placeholder' => __( 'business.zip_code'), 'required', 'data-mask="00000-000"' ]); ?>

          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <?php echo Form::label('cidade_id', 'Cidade:*'); ?><br>
            <?php echo Form::select('cidade_id', $cities, '4000', ['class' => 'form-control select2', 'required', 'style' => 'width: 100%']); ?>

          </div>
        </div>


        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('ie', 'IE' . ':*'); ?>

            <?php echo Form::text('ie', '', ['class' => 'form-control', 'required',
            'placeholder' => 'IE']); ?>

          </div>
        </div>


        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('rua', 'Rua' . ':*'); ?>

            <?php echo Form::text('rua', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Rua']); ?>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('numero', 'Número' . ':*'); ?>

            <?php echo Form::text('numero', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Número']); ?>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('bairro', 'Bairro' . ':*'); ?>

            <?php echo Form::text('bairro', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Bairro']); ?>

          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <?php echo Form::label('telefone', 'Telefone' . ':*'); ?>

            <?php echo Form::text('telefone', '', ['class' => 'form-control', 'required', 'data-mask="00 000000000"',
            'placeholder' => 'Telefone']); ?>

          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">

            <?php echo Form::label('regime', 'Regime' . ':'); ?>

            <?php echo Form::select('regime', ['1' => 'Simples', '3' => 'Normal'], '', ['class' => 'form-control select2', 'required']); ?>

          </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('ultimo_numero_nfe', 'Ultimo Núm. NFe' . ':*'); ?>

            <?php echo Form::text('ultimo_numero_nfe', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Ultimo Núm. NFe']); ?>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('ultimo_numero_nfce', 'Ultimo Núm. NFCe' . ':*'); ?>

            <?php echo Form::text('ultimo_numero_nfce', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Ultimo Núm. NFCe']); ?>

          </div>
        </div>

       
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('inscricao_municipal', 'Inscrição municipal' . ':*'); ?>

            <?php echo Form::text('inscricao_municipal', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Inscrição municipal']); ?>

          </div>
        </div>

        <!-- <div class="clearfix"></div> -->

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('numero_serie_nfe', 'Núm. Série NFe' . ':*'); ?>

            <?php echo Form::text('numero_serie_nfe', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Núm. Série NF-e']); ?>

          </div>
        </div>


        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('numero_serie_nfce', 'Núm. Série NFCe' . ':*'); ?>

            <?php echo Form::text('numero_serie_nfce', '', ['class' => 'form-control', 'required',
            'placeholder' => 'Núm. Série NFCe']); ?>

          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">

            <?php echo Form::label('ambiente', 'Ambiente' . ':'); ?>

            <?php echo Form::select('ambiente', ['2' => 'Homologação', '1' => 'Produção'], '', ['class' => 'form-control select2', 'required']); ?>

          </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('csc_id', 'CSCID' . ':*'); ?>

            <?php echo Form::text('csc_id', '', ['class' => 'form-control', 'required', 
            'placeholder' => 'CSCID']); ?>

          </div>
        </div>

        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('csc', 'CSC' . ':*'); ?>

            <?php echo Form::text('csc', '', ['class' => 'form-control', 'required', 
            'placeholder' => 'CSC']); ?>

          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-sm-4">
          <div class="form-group">
            <?php echo Form::label('mobile', 'Celular' . ':'); ?>

            <?php echo Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Celular']); ?>

          </div>
        </div>
        <div class="col-sm-4">
          <div class="form-group">
            <?php echo Form::label('alternate_number', __( 'business.alternate_number' ) . ':'); ?>

            <?php echo Form::text('alternate_number', null, ['class' => 'form-control', 'placeholder' => __( 'business.alternate_number')]); ?>

          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('email', __( 'business.email' ) . ':'); ?>

            <?php echo Form::email('email', null, ['class' => 'form-control', 'placeholder' => __( 'business.email')]); ?>

          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('website', __( 'lang_v1.website' ) . ':'); ?>

            <?php echo Form::text('website', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.website')]); ?>

          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('invoice_scheme_id', __('invoice.invoice_scheme') . ':*'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.invoice_scheme') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
            <?php echo Form::select('invoice_scheme_id', $invoice_schemes, null, ['class' => 'form-control', 'required',
            'placeholder' => __('messages.please_select')]); ?>

          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('invoice_layout_id', __('invoice.invoice_layout') . ':*'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.invoice_layout') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
            <?php echo Form::select('invoice_layout_id', $invoice_layouts, null, ['class' => 'form-control', 'required',
            'placeholder' => __('messages.please_select')]); ?>

          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <?php echo Form::label('selling_price_group_id', 'Grupo de preço de venda padrão' . ':'); ?> 
            <?php echo Form::select('selling_price_group_id', $price_groups, null, ['class' => 'form-control',
            'placeholder' => __('messages.please_select')]); ?>

          </div>
        </div>
        <div class="clearfix"></div>
        <?php
        $custom_labels = json_decode(session('business.custom_labels'), true);
        $location_custom_field1 = !empty($custom_labels['location']['custom_field_1']) ? $custom_labels['location']['custom_field_1'] : __('lang_v1.location_custom_field1');
        $location_custom_field2 = !empty($custom_labels['location']['custom_field_2']) ? $custom_labels['location']['custom_field_2'] : __('lang_v1.location_custom_field2');
        $location_custom_field3 = !empty($custom_labels['location']['custom_field_3']) ? $custom_labels['location']['custom_field_3'] : __('lang_v1.location_custom_field3');
        $location_custom_field4 = !empty($custom_labels['location']['custom_field_4']) ? $custom_labels['location']['custom_field_4'] : __('lang_v1.location_custom_field4');
        ?>
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('custom_field1', $location_custom_field1 . ':'); ?>

            <?php echo Form::text('custom_field1', null, ['class' => 'form-control', 
            'placeholder' => $location_custom_field1]); ?>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('custom_field2', $location_custom_field2 . ':'); ?>

            <?php echo Form::text('custom_field2', null, ['class' => 'form-control', 
            'placeholder' => $location_custom_field2]); ?>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('custom_field3', $location_custom_field3 . ':'); ?>

            <?php echo Form::text('custom_field3', null, ['class' => 'form-control', 
            'placeholder' => $location_custom_field3]); ?>

          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <?php echo Form::label('custom_field4', $location_custom_field4 . ':'); ?>

            <?php echo Form::text('custom_field4', null, ['class' => 'form-control', 
            'placeholder' => $location_custom_field4]); ?>

          </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="col-sm-12">
          <div class="form-group">
            <?php echo Form::label('featured_products', __('lang_v1.pos_screen_featured_products') . ':'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('lang_v1.featured_products_help') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
            <?php echo Form::select('featured_products[]', [], null, ['class' => 'form-control',
            'id' => 'featured_products', 'multiple']); ?>

          </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="col-sm-12">
          <strong>Formas de pagamento: <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . 'habilite as formas de pagamento' . '" data-html="true" data-trigger="hover"></i>';
                }
                ?></strong>
          <div class="form-group">
            <table class="table table-condensed table-striped">
              <thead>
                <tr>
                  <th class="text-center"><?php echo app('translator')->get('lang_v1.payment_method'); ?></th>
                  <th class="text-center">Ativo</th>
                  <th class="text-center <?php if(empty($accounts)): ?> hide <?php endif; ?>"><?php echo app('translator')->get('lang_v1.default_accounts'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('lang_v1.default_account_help') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $__currentLoopData = $payment_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td class="text-center"><?php echo e($value, false); ?></td>
                  <td class="text-center"><?php echo Form::checkbox('default_payment_accounts[' . $key . '][is_enabled]', 1, true); ?></td>
                  <td class="text-center <?php if(empty($accounts)): ?> hide <?php endif; ?>">
                    <?php echo Form::select('default_payment_accounts[' . $key . '][account]', $accounts, null, ['class' => 'form-control input-sm']); ?>

                  </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary"><?php echo app('translator')->get( 'messages.save' ); ?></button>
      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get( 'messages.close' ); ?></button>
    </div>

    <?php echo Form::close(); ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('#cidade_id').select2();

        var cpfMascara = function(val) {
          return val.replace(/\D/g, "").length > 11
          ? "00.000.000/0000-00"
          : "000.000.000-009";
        },
        cpfOptions = {
          onKeyPress: function(val, e, field, options) {
            field.mask(cpfMascara.apply({}, arguments), options);
          }
        };

        $(".cpf_cnpj").mask(cpfMascara, cpfOptions);
      });

      function buscaDados(){
        let uf = $('#uf2').val();
        let cnpj = $('#cnpj').val();

        var path = window.location.protocol + '//' + window.location.host
        $.ajax
        ({
          type: 'GET',
          data: {
            cnpj: cnpj,
            uf: uf
          },
          url: path + '/nfe/consultaCadastro',

          dataType: 'json',
          success: function(e){
            console.log(e)
            if(e.infCons.infCad){
              let info = e.infCons.infCad;
              console.log(info)

              $('#ie_rg').val(info.IE)
              $('#razao_social').val(info.xNome)
              $('#name').val(info.xFant ? info.xFant : info.xNome)

              $('#rua').val(info.ender.xLgr)
              $('#numero').val(info.ender.nro)
              $('#bairro').val(info.ender.xBairro)
              let cep = info.ender.CEP;
              $('#zip_code').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))

              findCidade(info.ender.xMun, (res) => {

                if(res){

                  var $option = $("<option selected></option>").val(res.id).text(res.nome + " (" + res.uf + ")");
                  $('#cidade_id').append($option).trigger('change');

                }
              })

            }else{
              swal('Algo deu errado', e.infCons.xMotivo, 'warning')
            }
          },
          error: function(e){
            console.log("err",e.responseText)
            swal('Algo deu errado', e.responseText, 'warning')

          }
        });
      }

      function findCidade(nomeCidade, call){
        var path = window.location.protocol + '//' + window.location.host
        $.get(path + '/nfe/findCidade', {nome: nomeCidade} )
        .done((success) => {
          call(success)
        })
        .fail((err) => {
          call(err)
        })
      }
    </script>

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog --><?php /**PATH F:\Nova pasta\gestao\resources\views/business_location/create.blade.php ENDPATH**/ ?>
<div class="modal-dialog modal-xl" role="document">
  <div class="modal-content">
    <?php
    $form_id = 'contact_add_form';
    if(isset($quick_add)){
      $form_id = 'quick_add_contact';
    }

    if(isset($store_action)) {
      $url = $store_action;
      $type = 'lead';
      $customer_groups = [];
    } else {
      $url = action('ContactController@store');
      $type = '';
      $sources = [];
      $life_stages = [];
      $users = [];
    }
    ?>
    <?php echo Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]); ?>


    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Nuevo <?php echo e($tipo == 'customer' ? 'Cliente' : 'Contacto', false); ?></h4>
    </div>

    <div class="modal-body">
      <div class="row">

        <div class="col-md-2">
          <div class="form-group">
            <?php echo Form::label('tipo', 'Tipo' . ':'); ?>

            <div class="input-group" style="width: 100%;">

              <?php echo Form::select('tipo', ['j' => 'Juridica', 'f' => 'Fisica'], '', ['class' => 'form-control']); ?>

            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">

            <label for="product_custom_field2">CNPJ/CPF:</label>

            <input class="form-control featured-field" required placeholder="CPF/CNPJ" data-mask="00.000.000/0000-00" name="cpf_cnpj" type="text" id="cpf_cnpj">
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

        <div class="col-md-4">
          <div class="form-group">
            <label for="product_custom_field2">INS.ESTADUAL / RG:</label>
            <input class="form-control featured-field" placeholder="I.E/RG" name="ie_rg" id="ie_rg">
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-md-6">
          <div class="form-group">
            <?php echo Form::label('name', 'Razón Social/Nombre' . ':*'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              <?php echo Form::text('name', null, ['id' => 'name', 'class' => 'form-control featured-field','placeholder' => 'Razón Social', 'required']); ?>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <?php echo Form::label('supplier_business_name', __('business.business_name') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-briefcase"></i>
              </span>
              <?php echo Form::text('supplier_business_name', null, ['id' => 'nome_fantasia', 'class' => 'form-control', 'required', 'placeholder' => __('business.business_name')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-3 contact_type_div">
          <div class="form-group">

            <?php echo Form::label('type', __('contact.contact_type') . ':*' ); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              <?php echo Form::select('type', $types, $tipo, ['class' => 'form-control', 'id' => 'contact_type','placeholder' => __('messages.please_select'), 'required']); ?>

            </div>
          </div>
        </div>

        <div class="col-md-2 customer_fields">
          <div class="form-group">

            <?php echo Form::label('consumidor_final', 'Consumidor final' . ':'); ?>

            <?php echo Form::select('consumidor_final', ['1' => 'Sim', '0' => 'Não'], '', ['id' => 'consumidor_final', 'class' => 'form-control select2 featured-field', 'required']); ?>

          </div>
        </div>

        <div class="col-md-2 customer_fields">
          <div class="form-group">

            <?php echo Form::label('contribuinte', 'Contribuinte' . ':'); ?>

            <?php echo Form::select('contribuinte', ['1' => 'Sim', '0' => 'Não'], '', ['id' => 'contribuinte', 'class' => 'form-control select2 featured-field', 'required']); ?>

          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-12">
          <hr/>
        </div>

        <div class="col-md-2 ">
          <div class="form-group">
            <label for="product_custom_field2">CEP*:</label>
            <input class="form-control  featured-field" required placeholder="CEP" name="cep" data-mask="00000-000" type="text" id="cep">
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <label for="product_custom_field2">Calle*:</label>
            <input class="form-control featured-field" required placeholder="Calle" name="rua" type="text" id="rua">
          </div>
        </div>
        <div class="col-md-2 ">
          <div class="form-group">
            <label for="product_custom_field2">Nº*:</label>
            <input class="form-control featured-field" required placeholder="Nº" name="numero" type="text" id="numero">
          </div>
        </div>

        <div class="col-md-3 ">
          <div class="form-group">
            <?php echo Form::label('complement', 'Complemento:'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              <?php echo Form::text('complement', null, ['class' => 'form-control',
              'placeholder' => 'Complemento']); ?>

            </div>
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <label for="product_custom_field2">Barrio*:</label>
            <input class="form-control featured-field" required placeholder="Barrio" name="bairro" type="text" id="bairro">
          </div>
        </div>

        <div class="col-md-5">
          <div class="form-group">
            <?php echo Form::label('city_id', 'Ciudades:*'); ?>

            <?php echo Form::select('city_id', $cities, '', ['id' => 'cidade', 'class' => 'form-control select2 featured-field', 'required']); ?>

          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <?php echo Form::label('email', __('business.email') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-envelope"></i>
              </span>
              <?php echo Form::text('email', null, ['class' => 'form-control','placeholder' => __('business.email')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <?php echo Form::label('landmark', __('business.landmark') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-map-marker"></i>
              </span>
              <?php echo Form::text('landmark', null, ['class' => 'form-control',
              'placeholder' => __('business.landmark')]); ?>

            </div>
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-md-3">
          <div class="form-group">
            <?php echo Form::label('landline','Teléfono Fijo:'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-phone"></i>
              </span>
              <?php echo Form::text('landline', null, ['class' => 'form-control', 'placeholder' => 'Teléfono Fijo']); ?>

            </div>
          </div>
        </div>


        <div class="col-md-3">
          <div class="form-group">
            <?php echo Form::label('alternate_number', 'Teléfono alternativo' . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-phone"></i>
              </span>
              <?php echo Form::text('alternate_number', null, ['class' => 'form-control', 'placeholder' => __('contact.alternate_contact_number')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <?php echo Form::label('mobile', 'Celular' . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-mobile"></i>
              </span>
              <?php echo Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => 'Celular']); ?>

            </div>
          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-12">
          <hr/>
        </div>

        <!-- <div class="col-md-8" >
          <strong><?php echo e(__('lang_v1.shipping_address'), false); ?></strong><br>
          <?php echo Form::text('shipping_address', null, ['class' => 'form-control',
          'placeholder' => 'Endeço de entrega', 'id' => 'shipping_address']); ?>

          <div id="map"></div>
        </div> -->
        <div class="col-md-12">
          <h5>Dirección de envío</h5>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label for="product_custom_field2">CEP:</label>
            <input class="form-control  featured-field" placeholder="CEP" name="cep_entrega" data-mask="00000-000" type="text" id="cep_entrega">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="product_custom_field2">Calle:</label>
            <input class="form-control featured-field" placeholder="Calle" name="rua_entrega" type="text" id="rua_entrega">
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <label for="product_custom_field2">Nº:</label>
            <input class="form-control featured-field" placeholder="Nº" name="numero_entrega" type="text" id="numero_entrega">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label for="product_custom_field2">Barrio:</label>
            <input class="form-control featured-field" placeholder="Barrio" name="bairro_entrega" type="text" id="bairro_entrega">
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <?php echo Form::label('city_id_entrega', 'Ciudad:'); ?>

            <?php echo Form::select('city_id_entrega', $cities, '', ['id' => 'cidade_entrega', 'class' => 'form-control select2 featured-field']); ?>

          </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-12">
          <hr/>
        </div>

        <div class="col-md-4 customer_fields">
          <div class="form-group">
            <?php echo Form::label('credit_limit', __('lang_v1.credit_limit') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
              </span>
              <?php echo Form::text('credit_limit', null, ['class' => 'form-control input_number']); ?>

            </div>
            <p class="help-block"><?php echo app('translator')->get('lang_v1.credit_limit_help'); ?></p>
          </div>
        </div>

        <div class="col-md-4 opening_balance">
          <div class="form-group">
            <?php echo Form::label('opening_balance', __('lang_v1.opening_balance') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa-money-bill-alt"></i>
              </span>
              <?php echo Form::text('opening_balance', 0, ['class' => 'form-control input_number']); ?>

            </div>
          </div>
        </div>

        <div class="col-md-4 pay_term">
          <div class="form-group">
            <div class="multi-input">
              <?php echo Form::label('pay_term_number', __('contact.pay_term') . ':'); ?> <?php
                if(session('business.enable_tooltip')){
                    echo '<i class="fa fa-info-circle text-info hover-q no-print " aria-hidden="true" 
                    data-container="body" data-toggle="popover" data-placement="auto bottom" 
                    data-content="' . __('tooltip.pay_term') . '" data-html="true" data-trigger="hover"></i>';
                }
                ?>
              <br/>
              <?php echo Form::number('pay_term_number', null, ['class' => 'form-control width-40 pull-left', 'placeholder' => __('contact.pay_term')]); ?>


              <?php echo Form::select('pay_term_type', ['months' => __('lang_v1.months'), 'days' => __('lang_v1.days')], '', ['class' => 'form-control width-60 pull-left','placeholder' => __('messages.please_select')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-4 lead_additional_div">
          <div class="form-group">
            <?php echo Form::label('crm_life_stage', __('lang_v1.life_stage') . ':' ); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa fa-life-ring"></i>
              </span>
              <?php echo Form::select('crm_life_stage', $life_stages, null , ['class' => 'form-control', 'id' => 'crm_life_stage','placeholder' => __('messages.please_select')]); ?>

            </div>
          </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-md-4">
          <div class="form-group">
            <?php echo Form::label('tax_number', __('contact.tax_no') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-info"></i>
              </span>
              <?php echo Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => __('contact.tax_no')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <?php echo Form::label('contact_id', __('lang_v1.contact_id') . ':'); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-id-badge"></i>
              </span>
              <?php echo Form::text('contact_id', null, ['class' => 'form-control','placeholder' => __('lang_v1.contact_id')]); ?>

            </div>
          </div>
        </div>


        <!-- lead additional field -->
        <div class="col-md-4 lead_additional_div">
          <div class="form-group">
            <?php echo Form::label('crm_source', __('lang_v1.source') . ':' ); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fas fa fa-search"></i>
              </span>
              <?php echo Form::select('crm_source', $sources, null , ['class' => 'form-control', 'id' => 'crm_source','placeholder' => __('messages.please_select')]); ?>

            </div>
          </div>
        </div>

        <div class="col-md-6 lead_additional_div">
          <div class="form-group">
            <?php echo Form::label('user_id', __('lang_v1.assigned_to') . ':*' ); ?>

            <div class="input-group">
              <span class="input-group-addon">
                <i class="fa fa-user"></i>
              </span>
              <?php echo Form::select('user_id[]', $users, null , ['class' => 'form-control select2', 'id' => 'user_id', 'multiple', 'required', 'style' => 'width: 100%;']); ?>

            </div>
          </div>
        </div>

        <div class="clearfix"></

          <div class="clearfix"></div>


          <div class="col-md-12">
            <hr/>
          </div>


          <?php
          $custom_labels = json_decode(session('business.custom_labels'), true);
          $contact_custom_field1 = !empty($custom_labels['contact']['custom_field_1']) ? $custom_labels['contact']['custom_field_1'] : __('lang_v1.contact_custom_field1');
          $contact_custom_field2 = !empty($custom_labels['contact']['custom_field_2']) ? $custom_labels['contact']['custom_field_2'] : __('lang_v1.contact_custom_field2');
          $contact_custom_field3 = !empty($custom_labels['contact']['custom_field_3']) ? $custom_labels['contact']['custom_field_3'] : __('lang_v1.contact_custom_field3');
          $contact_custom_field4 = !empty($custom_labels['contact']['custom_field_4']) ? $custom_labels['contact']['custom_field_4'] : __('lang_v1.contact_custom_field4');
          ?>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('custom_field1', $contact_custom_field1 . ':'); ?>

              <?php echo Form::text('custom_field1', null, ['class' => 'form-control',
              'placeholder' => __('lang_v1.contact_custom_field1')]); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('custom_field2', $contact_custom_field2 . ':'); ?>

              <?php echo Form::text('custom_field2', null, ['class' => 'form-control',
              'placeholder' => $contact_custom_field2]); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('custom_field3', $contact_custom_field3 . ':'); ?>

              <?php echo Form::text('custom_field3', null, ['class' => 'form-control',
              'placeholder' => $contact_custom_field3]); ?>

            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <?php echo Form::label('custom_field4', $contact_custom_field4 . ':'); ?>

              <?php echo Form::text('custom_field4', null, ['class' => 'form-control',
              'placeholder' => $contact_custom_field4]); ?>

            </div>
          </div>
          <?php echo Form::hidden('position', null, ['id' => 'position']); ?>


        </div>
      </div>



      <div class="col-md-3" style="display: none">
        <div class="form-group">
          <?php echo Form::label('city', __('business.city') . ':'); ?>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-map-marker"></i>
            </span>
            <?php echo Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('business.city')]); ?>

          </div>
        </div>
      </div>
      <div class="col-md-3" style="display: none">
        <div class="form-group">
          <?php echo Form::label('state', __('business.state') . ':'); ?>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-map-marker"></i>
            </span>
            <?php echo Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('business.state')]); ?>

          </div>
        </div>
      </div>

      <div class="col-md-3" style="display: none">
        <div class="form-group">
          <?php echo Form::label('country', __('business.country') . ':'); ?>

          <div class="input-group">
            <span class="input-group-addon">
              <i class="fa fa-globe"></i>
            </span>
            <?php echo Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('business.country')]); ?>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get( 'messages.save' ); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get( 'messages.close' ); ?></button>
      </div>

      <?php echo Form::close(); ?>




    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->


  <script type="text/javascript">
    $('#cpf_cnpj').mask('00.000.000/0000-00')
    $('#cep').mask('00000-000')
    $('#tipo').change((val) => {
      let t = $('#tipo').val()

      if(t == 'j'){
        $('#cpf_cnpj').mask('00.000.000/0000-00')
      }else{
        $('#cpf_cnpj').mask('000.000.000-00')
        $('#nome_fantasia').removeAttr('required')

      }
    })

    function buscaDados(){
      let uf = $('#uf2').val();
      let cnpj = $('#cpf_cnpj').val();

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
          // console.log(e)
          if(e.infCons.infCad){
            let info = e.infCons.infCad;
            // console.log(info)

            $('#ie_rg').val(info.IE)
            $('#name').val(info.xNome)
            $('#nome_fantasia').val(info.xFant ? info.xFant : info.xNome)

            $('#rua').val(info.ender.xLgr)
            $('#rua_entrega').val(info.ender.xLgr)
            $('#numero').val(info.ender.nro)
            $('#numero_entrega').val(info.ender.nro)
            $('#bairro').val(info.ender.xBairro)
            $('#bairro_entrega').val(info.ender.xBairro)
            let cep = info.ender.CEP;
            $('#cep').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))
            $('#cep_entrega').val(cep.substring(0, 5) + '-' + cep.substring(5, 9))

            findCidade(info.ender.xMun, (res) => {

              if(res){
                // console.log(res)
                // var $option = $("<option selected></option>").val(res.id).text(res.nome + " (" + res.uf + ")");
                // $('#cidade').append($option).trigger('change');
                $('#cidade').val(res.id).change();
                $('#cidade_entrega').val(res.id).change();
                // $('#cidade_entrega').append($option).trigger('change');

              }
            })

          }else{
            swal('Algo deu errado', e.infCons.xMotivo, 'warning')
          }
        },
        error: function(e){
          console.log("err", e.responseText)
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

  /**
    Busca os dados do CNPJ na API
    */
    // function getDataFromCNPJ(cnpj) {
    //   if (cnpj.length < 18 ) {
    //     return false;
    //   }
    //   cnpj = cnpj.replaceAll('-', '');
    //   cnpj = cnpj.replaceAll('.', '');
    //   cnpj = cnpj.replaceAll('/', '');
    //   $.get('http://gestor.sefacilsistemas.com.br/consult/cnpj', { cnpj: cnpj })
    //   .done((response) => {
    //     $('#name').val(response.nome);
    //     $('#nome_fantasia').val(response.fantasia);
    //   });
    // }

    // $('#cpf_cnpj').keyup((event) => {
    //   getDataFromCNPJ(event.target.value);
    // }); 

  /**
    Busca os dados do CEP na API
    */
    function getDataFromCep(cep) {
      if (cep.length < 9 ) {
        return false;
      }else{
        cep = cep.replace("-", "")
        $.get('https://ws.apicep.com/cep.json', { code: cep })
        .done((response) => {
          $('#bairro').val(response.district);
          $('#bairro_entrega').val(response.district);
          $('#rua').val(response.address);
          $('#rua_entrega').val(response.address);
          $('#uf2').val(response.state);
          $('#uf2').select2();
          findCidade(response.city, (res) => {
            console.log(res)
            if(res){
              // var $option = $("<option selected></option>").val(res.id).change()
              // var $option = $("<option selected></option>").val(res.id).text(res.nome + " (" + res.uf + ")");
              // $('#cidade').append($option).trigger('change');
              $('#cidade').val(res.id).change()
              $('#cidade_entrega').val(res.id).change()
            }
          });
        })
      }
    }

    $('#cep').keyup((event) => {
      getDataFromCep(event.target.value);
    });

  </script>



<?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/contact/create.blade.php ENDPATH**/ ?>
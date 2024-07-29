<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <?php
            $form_id = 'contact_add_form';
            if (isset($quick_add)) {
                $form_id = 'quick_add_contact';
            }

            if (isset($store_action)) {
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
        <?php echo Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id]); ?>


        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Nuevo <?php echo e($tipo == 'customer' ? 'Cliente' : 'Contacto', false); ?></h4>
        </div>

        <div class="modal-body">
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('tipo', 'Tipo Identificación' . ':'); ?>

                        <div class="input-group" style="width: 100%;">

                            <?php echo Form::select('tipo', ['j' => 'Juridica', 'f' => 'Fisica'], '', ['class' => 'form-control']); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">

                        <label for="product_custom_field2">Identificación:</label>

                        <input class="form-control featured-field" required placeholder="Identificación" name="cpf_cnpj"
                            type="text" id="cpf_cnpj">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('name', 'Razón Social/Nombre' . ':*'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <?php echo Form::text('name', null, [
                                'id' => 'name',
                                'class' => 'form-control featured-field',
                                'placeholder' => 'Razón Social',
                                'required',
                            ]); ?>

                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-md-3 contact_type_div">
                    <div class="form-group">

                        <?php echo Form::label('type', __('contact.contact_type') . ':*'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-user"></i>
                            </span>
                            <?php echo Form::select('type', $types, $tipo, [
                                'class' => 'form-control',
                                'id' => 'contact_type',
                                'placeholder' => __('messages.please_select'),
                                'required',
                            ]); ?>

                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div class="col-md-12">
                    <hr />
                </div>



                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('city', 'Cantón:'); ?>

                        <input class="form-control featured-field" placeholder="Cantón" name="city" type="text"
                            id="city">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product_custom_field2">Distrito:</label>
                        <input class="form-control featured-field" placeholder="Barrio" name="Distrito" type="text"
                            id="bairro">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product_custom_field2">Barrio:</label>
                        <input class="form-control featured-field" placeholder="Barrio" name="rua" type="text"
                            id="rua">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <?php echo Form::label('landmark', __('business.landmark') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <?php echo Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => __('business.landmark')]); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('email', __('business.email') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <?php echo Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('business.email')]); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('landline', 'Teléfono Fijo:'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-phone"></i>
                            </span>
                            <?php echo Form::text('landline', null, ['class' => 'form-control', 'placeholder' => 'Teléfono Fijo']); ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
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



                <div class="clearfix"></div>
                <div class="col-md-12">
                    <hr />
                </div>

                <!-- <div class="col-md-8" >
          <strong><?php echo e(__('lang_v1.shipping_address'), false); ?></strong><br>
          <?php echo Form::text('shipping_address', null, [
              'class' => 'form-control',
              'placeholder' => 'Endeço de entrega',
              'id' => 'shipping_address',
          ]); ?>

          <div id="map"></div>
        </div> -->
                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('state', __('Estado Civil') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-heart"></i>
                            </span>
                            <?php echo Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('Estado Civil')]); ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <?php echo Form::label('position', __('Puesto') . ':'); ?>

                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fas fa fa-life-ring"></i>
                            </span>
                            <?php echo Form::text('position', null, ['class' => 'form-control', 'placeholder' => __('Puesto')]); ?>

                        </div>
                    </div>
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


                <div class="clearfix"></div>

                <div class="clearfix">
                </div>


                

            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('messages.save'); ?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo app('translator')->get('messages.close'); ?></button>
        </div>

        <?php echo Form::close(); ?>




    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->


<script type="text/javascript">
    $('#cpf_cnpj').mask('00.000.000/0000-00')
    $('#cep').mask('00000-000')
    $('#tipo').change((val) => {
        let t = $('#tipo').val()

        if (t == 'j') {
            $('#cpf_cnpj').mask('00.000.000/0000-00')
        } else {
            $('#cpf_cnpj').mask('000.000.000-00')
            $('#nome_fantasia').removeAttr('required')

        }
    })

    function buscaDados() {
        let uf = $('#uf2').val();
        let cnpj = $('#cpf_cnpj').val();

        var path = window.location.protocol + '//' + window.location.host
        $.ajax({
            type: 'GET',
            data: {
                cnpj: cnpj,
                uf: uf
            },
            url: path + '/nfe/consultaCadastro',

            dataType: 'json',
            success: function(e) {
                // console.log(e)
                if (e.infCons.infCad) {
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

                        if (res) {
                            // console.log(res)
                            // var $option = $("<option selected></option>").val(res.id).text(res.nome + " (" + res.uf + ")");
                            // $('#cidade').append($option).trigger('change');
                            $('#cidade').val(res.id).change();
                            $('#cidade_entrega').val(res.id).change();
                            // $('#cidade_entrega').append($option).trigger('change');

                        }
                    })

                } else {
                    swal('Algo deu errado', e.infCons.xMotivo, 'warning')
                }
            },
            error: function(e) {
                console.log("err", e.responseText)
                swal('Algo deu errado', e.responseText, 'warning')

            }
        });
    }

    function findCidade(nomeCidade, call) {
        var path = window.location.protocol + '//' + window.location.host
        $.get(path + '/nfe/findCidade', {
                nome: nomeCidade
            })
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
        if (cep.length < 9) {
            return false;
        } else {
            cep = cep.replace("-", "")
            $.get('https://ws.apicep.com/cep.json', {
                    code: cep
                })
                .done((response) => {
                    $('#bairro').val(response.district);
                    $('#bairro_entrega').val(response.district);
                    $('#rua').val(response.address);
                    $('#rua_entrega').val(response.address);
                    $('#uf2').val(response.state);
                    $('#uf2').select2();
                    findCidade(response.city, (res) => {
                        console.log(res)
                        if (res) {
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
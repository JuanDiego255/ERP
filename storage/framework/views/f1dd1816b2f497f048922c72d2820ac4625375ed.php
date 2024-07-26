<?php $__env->startSection('title', 'Adicionar Transportadora'); ?>

<?php $__env->startSection('content'); ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Adicionar Transportadora</h1>
</section>

<!-- Main content -->
<section class="content">
  <?php echo Form::open(['url' => action('TransportadoraController@save'), 'method' => 'post', 'id' => 'natureza_add_form' ]); ?>

  <div class="row">
    <div class="col-md-12">
      <?php $__env->startComponent('components.widget'); ?>
      
      <div class="col-md-4">
        <div class="form-group">
          <?php echo Form::label('razao_social', 'Razón Social' . ':*'); ?>

          <?php echo Form::text('razao_social', null, ['class' => 'form-control', 'required', 'placeholder' => 'Razón Social' ]); ?>

        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          <?php echo Form::label('cnpj_cpf', 'CNPJ/CPF' . '*:'); ?>

          <?php echo Form::text('cnpj_cpf', null, ['class' => 'form-control', 'required', 'placeholder' => 'CNPJ/CPF', 'class' => 'form-control cpf_cnpj' ]); ?>

        </div>
      </div>

      <div class="col-md-5">
        <div class="form-group">
          <?php echo Form::label('logradouro', 'Logradouro' . '*:'); ?>

          <?php echo Form::text('logradouro', null, ['class' => 'form-control', 'required', 'placeholder' => 'Logradouro' ]); ?>

        </div>
      </div>
      <div class="clearfix"></div>

      <div class="col-md-4 customer_fields">
        <div class="form-group">
          <?php echo Form::label('cidade_id', 'Ciudades:*'); ?>

          <?php echo Form::select('cidade_id', $cities, '', ['class' => 'form-control select2', 'required']); ?>

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
      <button type="submit" class="btn btn-primary pull-right" id="submit_user_button"><?php echo app('translator')->get( 'messages.save' ); ?></button>
    </div>
  </div>
  <?php echo Form::close(); ?>

  <?php $__env->stopSection(); ?>
  <?php $__env->startSection('javascript'); ?>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#selected_contacts').on('ifChecked', function(event){
        $('div.selected_contacts_div').removeClass('hide');
      });
      $('#selected_contacts').on('ifUnchecked', function(event){
        $('div.selected_contacts_div').addClass('hide');
      });

      $('#allow_login').on('ifChecked', function(event){
        $('div.user_auth_fields').removeClass('hide');
      });
      $('#allow_login').on('ifUnchecked', function(event){
        $('div.user_auth_fields').addClass('hide');
      });
    });

    $('form#user_add_form').validate({
      rules: {
        first_name: {
          required: true,
        },
        email: {
          email: true,
          remote: {
            url: "/business/register/check-email",
            type: "post",
            data: {
              email: function() {
                return $( "#email" ).val();
              }
            }
          }
        },
        password: {
          required: true,
          minlength: 5
        },
        confirm_password: {
          equalTo: "#password"
        },
        username: {
          minlength: 5,
          remote: {
            url: "/business/register/check-username",
            type: "post",
            data: {
              username: function() {
                return $( "#username" ).val();
              },
              <?php if(!empty($username_ext)): ?>
              username_ext: "<?php echo e($username_ext, false); ?>"
              <?php endif; ?>
            }
          }
        }
      },
      messages: {
        password: {
          minlength: 'Password should be minimum 5 characters',
        },
        confirm_password: {
          equalTo: 'Should be same as password'
        },
        username: {
          remote: 'Invalid username or User already exist'
        },
        email: {
          remote: '<?php echo e(__("validation.unique", ["attribute" => __("business.email")]), false); ?>'
        }
      }
    });
    $('#username').change( function(){
      if($('#show_username').length > 0){
        if($(this).val().trim() != ''){
          $('#show_username').html("<?php echo e(__('lang_v1.your_username_will_be'), false); ?>: <b>" + $(this).val() + "<?php echo e($username_ext, false); ?></b>");
        } else {
          $('#show_username').html('');
        }
      }
    });
  </script>
  <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/transportadoras/register.blade.php ENDPATH**/ ?>
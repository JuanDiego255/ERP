@extends('layouts.app')

@section('title', 'Adicionar Transportadora')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Adicionar Transportadora</h1>
</section>

<!-- Main content -->
<section class="content">
  {!! Form::open(['url' => action('TransportadoraController@save'), 'method' => 'post', 'id' => 'natureza_add_form' ]) !!}
  <div class="row">
    <div class="col-md-12">
      @component('components.widget')
      
      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('razao_social', 'Razón Social' . ':*') !!}
          {!! Form::text('razao_social', null, ['class' => 'form-control', 'required', 'placeholder' => 'Razón Social' ]); !!}
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('cnpj_cpf', 'CNPJ/CPF' . '*:') !!}
          {!! Form::text('cnpj_cpf', null, ['class' => 'form-control', 'required', 'placeholder' => 'CNPJ/CPF', 'class' => 'form-control cpf_cnpj' ]); !!}
        </div>
      </div>

      <div class="col-md-5">
        <div class="form-group">
          {!! Form::label('logradouro', 'Patio' . '*:') !!}
          {!! Form::text('logradouro', null, ['class' => 'form-control', 'required', 'placeholder' => 'Logradouro' ]); !!}
        </div>
      </div>
      <div class="clearfix"></div>

      <div class="col-md-4 customer_fields">
        <div class="form-group">
          {!! Form::label('cidade_id', 'Ciudades:*') !!}
          {!! Form::select('cidade_id', $cities, '', ['class' => 'form-control select2', 'required']); !!}
        </div>
      </div>
      

      
      @endcomponent
    </div>


  </div>

  @if(!empty($form_partials))
  @foreach($form_partials as $partial)
  {!! $partial !!}
  @endforeach
  @endif
  <div class="row">
    <div class="col-md-12">
      <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">@lang( 'messages.save' )</button>
    </div>
  </div>
  {!! Form::close() !!}
  @stop
  @section('javascript')
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
              @if(!empty($username_ext))
              username_ext: "{{$username_ext}}"
              @endif
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
          remote: '{{ __("validation.unique", ["attribute" => __("business.email")]) }}'
        }
      }
    });
    $('#username').change( function(){
      if($('#show_username').length > 0){
        if($(this).val().trim() != ''){
          $('#show_username').html("{{__('lang_v1.your_username_will_be')}}: <b>" + $(this).val() + "{{$username_ext}}</b>");
        } else {
          $('#show_username').html('');
        }
      }
    });
  </script>
  @endsection

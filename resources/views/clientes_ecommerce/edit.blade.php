@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Editar Cliente Ecommerce</h1>
</section>

<!-- Main content -->
<section class="content">
  {!! Form::open(['url' => action('ClienteEcommerceController@update', [$cliente->id]), 'method' => 'PUT', 'id' => 'natureza_add_form' ]) !!}
  <div class="row">
    <div class="col-md-12">
      @component('components.widget')
      
      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('nome', 'Nome' . ':*') !!}
          {!! Form::text('nome', $cliente->nome, ['class' => 'form-control', 'placeholder' => 'Nome' ]); !!}
          @if($errors->has('nome'))
          <span class="text-danger">
            {{ $errors->first('nome') }}
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('sobre_nome', 'Sobre Nome' . ':*') !!}
          {!! Form::text('sobre_nome', $cliente->sobre_nome, ['class' => 'form-control', 'placeholder' => 'Sobre Nome' ]); !!}
          @if($errors->has('sobre_nome'))
          <span class="text-danger">
            {{ $errors->first('sobre_nome') }}
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          {!! Form::label('email', 'Email' . ':*') !!}
          {!! Form::text('email', $cliente->email, ['class' => 'form-control', 'placeholder' => 'Email' ]); !!}
          @if($errors->has('email'))
          <span class="text-danger">
            {{ $errors->first('email') }}
          </span>
          @endif
        </div>
      </div>

      <div class="clearfix"></div>

      <div class="col-md-2 customer_fields">
        <div class="form-group">

          {!! Form::label('tipo', 'Tipo' . ':') !!}
          {!! Form::select('tipo', ['f' => 'Fisica', 'j' => 'Juridica'], $tipo, ['id' => 'tipo', 'class' => 'form-control select2', 'id' => 'tipo']); !!}
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('cpf', 'CPF' . ':*', ['id' => 'lbl_doc']) !!}
          {!! Form::text('cpf', $cliente->cpf, ['class' => 'form-control', 'placeholder' => 'CPF', 'id' => 'doc' ]); !!}
          @if($errors->has('cpf'))
          <span class="text-danger">
            {{ $errors->first('cpf') }}
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('ie', 'IE' . ':*', ['id' => 'lbl_ie']) !!}
          {!! Form::text('ie', $cliente->ie, ['class' => 'form-control', 'placeholder' => 'IE', 'id' => 'ie' ]); !!}
          @if($errors->has('ie'))
          <span class="text-danger">
            {{ $errors->first('ie') }}
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-3">
        <div class="form-group">
          {!! Form::label('telefone', 'Teléfono' . ':*') !!}
          {!! Form::text('telefone', $cliente->telefone, ['class' => 'form-control', 'placeholder' => 'Teléfono', 'data-mask="00 00000-0000"', 'data-mask-reverse="true"' ]); !!}
          @if($errors->has('telefone'))
          <span class="text-danger">
            {{ $errors->first('telefone') }}
          </span>
          @endif
        </div>
      </div>

      <div class="col-md-2">
        <div class="form-group">
          {!! Form::label('senha', Contraseña . ':*') !!}
          <input type="password" name="senha" class="form-control">
          @if($errors->has('senha'))
          <span class="text-danger">
            {{ $errors->first('senha') }}
          </span>
          @endif
        </div>
      </div>


      <div class="col-md-2 customer_fields">
        <div class="form-group">

          {!! Form::label('status', 'Ativo' . ':') !!}
          {!! Form::select('status', ['1' => 'Sim', '0' => 'Não'], $cliente->status, ['id' => 'tipo', 'class' => 'form-control select2', 'required']); !!}
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
      <button type="submit" class="btn btn-primary pull-right" id="submit_user_button">
        Actualizar
      </button>
    </div>
  </div>
  {!! Form::close() !!}
  @stop
  @section('javascript')
  <script type="text/javascript">
    $(function(){
      changeTipo()
    })
    $('#tipo').change(() => {
      changeTipo()
    })

    function changeTipo(){
      let t = $('#tipo').val()
      if(t == 'f'){
        $('#lbl_doc').html('CPF:*')
        $('#doc').mask('000.000.000-00', {reverse: true});
        $('#doc').attr({placeholder:"CPF"})
      }else{
        $('#lbl_doc').html('CNPJ:*')
        $('#doc').mask('00.000.000/0000-00', {reverse: true});
        $('#doc').attr({placeholder:"CNPJ"})
      }
    }

  </script>
  @endsection

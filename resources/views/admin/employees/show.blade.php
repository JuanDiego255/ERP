@extends('layouts.app')

@section('title', 'Detalles del empleado')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <h3>Detalles del empleado</h3>
            </div>
            <div class="col-md-4 col-xs-12 mt-15 pull-right">
                {!! Form::select('employee_id', $employees, $employee->id, [
                    'class' => 'form-control select2',
                    'id' => 'employee_id',
                ]) !!}
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <h3 class="profile-username text-center">
                            {{ $employee->name }}
                        </h3>

                        <p class="text-muted text-center" title="@lang('user.role')">

                        </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>@lang('Teléfono')</b>
                                <a class="pull-right">{{ $employee->telephone }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('Celular')</b>
                                <a class="pull-right">{{ $employee->celular }}</a>
                            </li>{{-- 
                            <li class="list-group-item">
                                <b>@lang('Correo')</b>
                                <a class="pull-right">{{ $employee->email }}</a>
                            </li> --}}
                            <li class="list-group-item">
                                <b>@lang('Puesto')</b>
                                <a class="pull-right">{{ $employee->puesto }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>@lang('Ingreso')</b>
                                <a class="pull-right">{{ @format_date($employee->created_at) }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{ __('Estado') }}</b>
                                @if ($employee->status == '1')
                                    <span class="label label-success pull-right">
                                        Activo
                                    </span>
                                @else
                                    <span class="label label-danger pull-right">
                                        Inactivo
                                    </span>
                                @endif
                            </li>
                        </ul>
                        @can('user.update')
                            <a href="{{ action('EmployeeController@edit', [$employee->id]) }}"
                                class="btn btn-primary btn-block">
                                <i class="glyphicon glyphicon-edit"></i>
                                @lang('messages.edit')
                            </a>
                        @endcan
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs nav-justified">
                        <li class="active">
                            <a href="#user_info_tab" data-toggle="tab" aria-expanded="true"><i class="fas fa-user"
                                    aria-hidden="true"></i> @lang('Información del empleado')</a>
                        </li>

                        {{-- <li>
                            <a href="#documents_and_notes_tab" data-toggle="tab" aria-expanded="true"><i
                                    class="fas fa-briefcase" aria-hidden="true"></i> Rubros fijos</a>
                        </li> --}}
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="user_info_tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <p><strong>@lang('Vacaciones'): {{ $employee->vacaciones }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Moneda de pago'): {{ $employee->moneda_pago }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Tipo pago'):
                                                {{ $employee->tipo_pago == 'quincenal' ? 'Quincenal' : 'Mensual' }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Salario por hora'):
                                                ₡{{ number_format($employee->salario_hora) }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Salario base'):
                                                ₡{{ number_format($employee->salario_base) }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Comision ventas'):
                                                {{ !empty($employee->comision_ventas) ? $employee->comision_ventas : '--' }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Deduccion C.C.S.S'): ₡{{ number_format($employee->ccss) }}</strong></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>@lang('Hora Extra'): ₡{{ number_format($employee->hora_extra) }}</strong></p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12">
                                    <hr />
                                </div>
                                {!! Form::open(['url' => action('EmployeeController@storeAction'), 'method' => 'post', 'id' => 'user_add_form']) !!}

                                <div class="col-md-12">
                                    @component('components.widget')
                                        <h3 class="box-title">
                                            <strong>Acciones de personal</strong>
                                        </h3>
                                        {!! Form::hidden('employee_id', $employee->id, ['class' => 'form-control']) !!}
                                        <div class="form-group col-md-12">
                                            {!! Form::label('action', 'Tipo' . ':*') !!}
                                            {!! Form::select('action', ['1' => 'Vacaciones'], !empty($employee->accion) ? $employee->accion : null, [
                                                'class' => 'form-control',
                                                'id' => 'gender',
                                                'required',
                                                'placeholder' => __('messages.please_select'),
                                            ]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('fecha_desde', __('Fecha desde') . ':') !!}
                                                {!! Form::date('fecha_desde', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('fecha_hasta', __('Fecha hasta') . ':') !!}
                                                {!! Form::date('fecha_hasta', null, ['class' => 'form-control', 'required']) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('observacion', __('Observación') . ':') !!}
                                                {!! Form::textarea('observacion', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary pull-right"
                                                    id="submit_user_button">@lang('messages.save')</button>
                                            </div>
                                        </div>
                                    @endcomponent
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                       {{--  <div class="tab-pane" id="documents_and_notes_tab">
                            <input type="hidden" name="employee_id" id="employee_id" value="{{ $employee->id }}">
                            <div class="document_note_body">
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>       

    </section>
@endsection
@section('javascript')
    <!-- document & note.js -->
    @include('admin.rubros.tab_rubros.document_and_note_js')

    <script type="text/javascript">
        $(document).ready(function() {
            $('#employee_id').change(function() {
                if ($(this).val()) {
                    window.location = "{{ url('/employees') }}/" + $(this).val();
                }
            });            
        });
    </script>
@endsection

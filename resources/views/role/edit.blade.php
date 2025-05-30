@extends('layouts.app')
@section('title', 'Editar Controles de acceso')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Editar Controles de acceso</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary'])
            {!! Form::open(['url' => action('RoleController@update', [$role->id]), 'method' => 'PUT', 'id' => 'role_form']) !!}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre:*') !!}
                        {!! Form::text('name', str_replace('#' . auth()->user()->business_id, '', $role->name), [
                            'class' => 'form-control',
                            'required',
                            'placeholder' => __('user.role_name'),
                        ]) !!}
                    </div>
                </div>
            </div>
            {{--   @if (in_array('service_staff', $enabled_modules))
                <div class="row">
                    <div class="col-md-2">
                        <h4>Tipo de usuário</h4>
                    </div>
                    <div class="col-md-9 col-md-offset-1">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('is_service_staff', 1, $role->is_service_staff, ['class' => 'input-icheck']) !!} {{ __('restaurant.service_staff') }}
                                </label>
                                @show_tooltip(__('restaurant.tooltip_service_staff'))
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}
            <div class="row">
                <div class="col-md-3">
                    <label>@lang('user.permissions'):</label>
                </div>
            </div>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.user')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.view', in_array('user.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.user.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.create', in_array('user.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.user.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.update', in_array('user.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.user.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'user.delete', in_array('user.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.user.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            {{--  <div class="row check_group">
                <div class="col-md-3">
                    <h4>Acceso</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.view', in_array('roles.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} Ver Controles de acceso
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.create', in_array('roles.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} Criar Controles de acceso
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.update', in_array('roles.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} Editar Controles de acceso
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'roles.delete', in_array('roles.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} Excluir Controles de acceso
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr> --}}
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Gestión administrativa')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'planilla.view', in_array('planilla.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver planilla') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'planilla.create', in_array('planilla.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Crear planilla') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'planilla.update', in_array('planilla.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar planilla') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'planilla.delete', in_array('planilla.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar planilla') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Empleados')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'employee.view', in_array('employee.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver empleado') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'employee.create', in_array('employee.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Crear empleado') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'employee.update', in_array('employee.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar empleado') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'employee.delete', in_array('employee.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar empleado') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.supplier')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.view', in_array('supplier.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.supplier.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.create', in_array('supplier.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.supplier.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.update', in_array('supplier.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.supplier.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'supplier.delete', in_array('supplier.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.supplier.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.customer')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.view', in_array('customer.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.customer.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.create', in_array('customer.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.customer.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.update', in_array('customer.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.customer.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'customer.delete', in_array('customer.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.customer.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Fiadores')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'guarantor.view', in_array('guarantor.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver fiador') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'guarantor.create', in_array('guarantor.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Crear fiador') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'guarantor.update', in_array('guarantor.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar fiador') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'guarantor.delete', in_array('guarantor.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar fiador') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Vehículos')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.view', in_array('product.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver vehículos') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.create', in_array('product.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Crear vehículos') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.update', in_array('product.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar vehículos') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'product.delete', in_array('product.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar vehículos') }}
                            </label>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'product.opening_stock',
                                    in_array('product.opening_stock', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('lang_v1.add_opening_stock') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'view_purchase_price', in_array('view_purchase_price', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!}
                                {{ __('lang_v1.view_purchase_price') }}
                            </label>
                            @show_tooltip(__('lang_v1.view_purchase_price_tooltip'))
                        </div>
                    </div> --}}
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Cuentas Por Pagar')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxp.view', in_array('cxp.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxp.create', in_array('cxp.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Agregar cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxp.update', in_array('cxp.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxp.delete', in_array('cxp.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'purchase.payments', in_array('purchase.payments', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Agregar pagos') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'purchase.view', in_array('purchase.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver pagos') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Cuentas Por Cobrar')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxc.view', in_array('cxc.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Ver cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxc.create', in_array('cxc.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Agregar cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxc.update', in_array('cxc.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Editar cuentas') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'cxc.delete', in_array('cxc.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('Eliminar cuentas') }}
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Planes de venta')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'plan_venta.view', in_array('plan_venta.view', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Ver planes de venta') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'plan_venta.create', in_array('plan_venta.create', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Agregar plan de venta') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'plan_venta.update', in_array('plan_venta.update', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Editar plan de venta') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'plan_venta.delete', in_array('plan_venta.delete', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Eliminar plan de venta') }}
                            </label>
                        </div>
                    </div>

                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('Reportes')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'report.view',  in_array('report.view', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Ver reportes') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'report.audit',  in_array('report.audit', $role_permissions), ['class' => 'input-icheck']) !!} {{ __('Reporte Auditoria') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            {{--  @if (in_array('purchases', $enabled_modules) || in_array('stock_adjustment', $enabled_modules))
                <div class="row check_group">
                    <div class="col-md-3">
                        <h4>@lang('role.purchase')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.view', in_array('purchase.view', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.purchase.view') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.create', in_array('purchase.create', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.purchase.create') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.update', in_array('purchase.update', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.purchase.update') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.delete', in_array('purchase.delete', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.purchase.delete') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'purchase.payments', in_array('purchase.payments', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!}
                                    {{ __('lang_v1.purchase.payments') }}
                                </label>
                                @show_tooltip(__('lang_v1.purchase_payments'))
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox(
                                        'permissions[]',
                                        'purchase.update_status',
                                        in_array('purchase.update_status', $role_permissions),
                                        ['class' => 'input-icheck'],
                                    ) !!}
                                    {{ __('lang_v1.update_status') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'view_own_purchase', in_array('view_own_purchase', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!}
                                    {{ __('lang_v1.view_own_purchase') }}
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
            @endif --}}
            {{--  <div class="row check_group">
                <div class="col-md-3">
                    <h4>Ventas</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'sell.view', in_array('sell.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.sell.view') }}
                            </label>
                        </div>
                    </div>
                    @if (in_array('pos_sale', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'sell.create', in_array('sell.create', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.sell.create') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'sell.update', in_array('sell.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.sell.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'sell.delete', in_array('sell.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.sell.delete') }}
                            </label>
                        </div>
                    </div>
                    @if (in_array('add_sale', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'direct_sell.access', in_array('direct_sell.access', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.direct_sell.access') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'list_drafts', in_array('list_drafts', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.list_drafts') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'list_quotations', in_array('list_quotations', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.list_quotations') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'view_own_sell_only', in_array('view_own_sell_only', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.view_own_sell_only') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'sell.payments', in_array('sell.payments', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!}
                                {{ __('lang_v1.sell.payments') }}
                            </label>
                            @show_tooltip(__('lang_v1.sell_payments'))
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'edit_product_price_from_sale_screen',
                                    in_array('edit_product_price_from_sale_screen', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!}
                                {{ __('lang_v1.edit_product_price_from_sale_screen') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'edit_product_price_from_pos_screen',
                                    in_array('edit_product_price_from_pos_screen', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!}
                                {{ __('lang_v1.edit_product_price_from_pos_screen') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'edit_product_discount_from_sale_screen',
                                    in_array('edit_product_discount_from_sale_screen', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!}
                                {{ __('lang_v1.edit_product_discount_from_sale_screen') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'edit_product_discount_from_pos_screen',
                                    in_array('edit_product_discount_from_pos_screen', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!}
                                {{ __('lang_v1.edit_product_discount_from_pos_screen') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'discount.access', in_array('discount.access', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!}
                                {{ __('lang_v1.discount.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'access_shipping', in_array('access_shipping', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!}
                                {{ __('lang_v1.access_shipping') }}
                            </label>
                        </div>
                    </div>
                    @if (in_array('types_of_service', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox(
                                        'permissions[]',
                                        'access_types_of_service',
                                        in_array('access_types_of_service', $role_permissions),
                                        ['class' => 'input-icheck'],
                                    ) !!} {{ __('lang_v1.access_types_of_service') }}
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
            </div> --}}
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.brand')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.view', in_array('brand.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.brand.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.create', in_array('brand.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.brand.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.update', in_array('brand.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.brand.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'brand.delete', in_array('brand.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.brand.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            {{--  <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.tax_rate')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.view', in_array('tax_rate.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.tax_rate.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.create', in_array('tax_rate.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.tax_rate.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.update', in_array('tax_rate.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.tax_rate.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_rate.delete', in_array('tax_rate.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.tax_rate.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr> --}}
            {{-- <div class="row check_group">
                <div class="col-md-3">
                    <h4>Unidade</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.view', in_array('unit.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.unit.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.create', in_array('unit.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.unit.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.update', in_array('unit.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.unit.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'unit.delete', in_array('unit.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.unit.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr> --}}
            {{--   <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('category.category')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.view', in_array('category.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.category.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.create', in_array('category.create', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.category.create') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.update', in_array('category.update', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.category.update') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'category.delete', in_array('category.delete', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.category.delete') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr> --}}
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.report')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    @if (in_array('purchases', $enabled_modules) ||
                            in_array('add_sale', $enabled_modules) ||
                            in_array('pos_sale', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox(
                                        'permissions[]',
                                        'purchase_n_sell_report.view',
                                        in_array('purchase_n_sell_report.view', $role_permissions),
                                        ['class' => 'input-icheck'],
                                    ) !!} {{ __('role.purchase_n_sell_report.view') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'tax_report.view', in_array('tax_report.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.tax_report.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'contacts_report.view',
                                    in_array('contacts_report.view', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.contacts_report.view') }}
                            </label>
                        </div>
                    </div>
                    @if (in_array('expenses', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'expense_report.view', in_array('expense_report.view', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.expense_report.view') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'profit_loss_report.view',
                                    in_array('profit_loss_report.view', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.profit_loss_report.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'stock_report.view', in_array('stock_report.view', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.stock_report.view') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'trending_product_report.view',
                                    in_array('trending_product_report.view', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.trending_product_report.view') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'register_report.view',
                                    in_array('register_report.view', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.register_report.view') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'sales_representative.view',
                                    in_array('sales_representative.view', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.sales_representative.view') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('role.settings')</h4>
                </div>
                <div class="col-md-2">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                        </label>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'business_settings.access',
                                    in_array('business_settings.access', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.business_settings.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'barcode_settings.access',
                                    in_array('barcode_settings.access', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.barcode_settings.access') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'invoice_settings.access',
                                    in_array('invoice_settings.access', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('role.invoice_settings.access') }}
                            </label>
                        </div>
                    </div>
                    @if (in_array('expenses', $enabled_modules))
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'expense.access', in_array('expense.access', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('role.expense.access') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'view_own_expense', in_array('view_own_expense', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!}
                                    {{ __('lang_v1.view_own_expense') }}
                                </label>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'access_printers', in_array('access_printers', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!}
                                {{ __('lang_v1.access_printers') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3">
                    <h4>@lang('role.dashboard')</h4>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'dashboard.data', in_array('dashboard.data', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('role.dashboard.data') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row check_group">
                <div class="col-md-3">
                    <h4>@lang('account.account')</h4>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('permissions[]', 'account.access', in_array('account.access', $role_permissions), [
                                    'class' => 'input-icheck',
                                ]) !!} {{ __('lang_v1.access_accounts') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            @if (in_array('tables', $enabled_modules) && in_array('service_staff', $enabled_modules))
                <div class="row check_group">
                    <div class="col-md-1">
                        <h4>@lang('restaurant.bookings')</h4>
                    </div>
                    <div class="col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" class="check_all input-icheck"> {{ __('role.select_all') }}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'crud_all_bookings', in_array('crud_all_bookings', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('restaurant.add_edit_view_all_booking') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'crud_own_bookings', in_array('crud_own_bookings', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('restaurant.add_edit_view_own_booking') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            @endif
            <div class="row">
                <div class="col-md-3">
                    <h4>@lang('lang_v1.access_selling_price_groups')</h4>
                </div>
                <div class="col-md-9">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox(
                                    'permissions[]',
                                    'access_default_selling_price',
                                    in_array('access_default_selling_price', $role_permissions),
                                    ['class' => 'input-icheck'],
                                ) !!} {{ __('lang_v1.default_selling_price') }}
                            </label>
                        </div>
                    </div>
                    @if (count($selling_price_groups) > 0)
                        @foreach ($selling_price_groups as $selling_price_group)
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox(
                                            'spg_permissions[]',
                                            'selling_price_group.' . $selling_price_group->id,
                                            in_array('selling_price_group.' . $selling_price_group->id, $role_permissions),
                                            ['class' => 'input-icheck'],
                                        ) !!} {{ $selling_price_group->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @if (in_array('tables', $enabled_modules))
                <div class="row">
                    <div class="col-md-3">
                        <h4>@lang('restaurant.restaurant')</h4>
                    </div>
                    <div class="col-md-9">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                    {!! Form::checkbox('permissions[]', 'access_tables', in_array('access_tables', $role_permissions), [
                                        'class' => 'input-icheck',
                                    ]) !!} {{ __('lang_v1.access_tables') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @include('role.partials.module_permissions')
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">@lang('messages.update')</button>
                </div>
            </div>

            {!! Form::close() !!}
        @endcomponent
    </section>
    <!-- /.content -->
@endsection

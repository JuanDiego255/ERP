@extends('layouts.app')
@section('title', __('lang_v1.import_contacts'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.import_contacts')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    
    @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>  
        </div>     
    @endif
    
    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary'])
                {!! Form::open(['url' => action('ContactController@postImportContacts'), 'method' => 'post', 'enctype' => 'multipart/form-data' ]) !!}
                    <div class="row">
                        <div class="col-sm-6">
                        <div class="col-sm-8">
                            <div class="form-group">
                                {!! Form::label('name', __( 'product.file_to_import' ) . ':') !!}
                                {!! Form::file('contacts_csv', ['accept'=> '.xls', 'required' => 'required']); !!}
                              </div>
                        </div>
                        <div class="col-sm-4">
                        <br>
                            <button type="submit" class="btn btn-primary">@lang('messages.submit')</button>
                        </div>
                        </div>
                    </div>

                {!! Form::close() !!}
                <br><br>
                <div class="row">
                    <div class="col-sm-4">
                        <a href="{{ asset('files/import_contacts_csv_template.xls') }}" class="btn btn-success" download><i class="fa fa-download"></i> @lang('lang_v1.download_template_file')</a>
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.instructions')])
                <strong>@lang('lang_v1.instruction_line1')</strong><br>
                    @lang('lang_v1.instruction_line2')
                    <br><br>
                <table class="table table-striped">
                    <tr>
                        <th>@lang('lang_v1.col_no')</th>
                        <th>@lang('lang_v1.col_name')</th>
                        <th>@lang('lang_v1.instruction')</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>@lang('contact.contact_type') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                        <td>{!! __('lang_v1.import_contact_type_ins') !!}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>@lang('contact.name') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>@lang('business.business_name') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>@lang('lang_v1.contact_id') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>@lang('lang_v1.contact_id_ins')</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>@lang('contact.tax_no') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>@lang('lang_v1.opening_balance') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>@lang('contact.pay_term') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>@lang('contact.pay_term_period') <br><small class="text-muted">(@lang('lang_v1.required_if_supplier'))</small></td>
                        <td><strong>@lang('lang_v1.pay_term_period_ins')</strong></td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>@lang('lang_v1.credit_limit') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>@lang('business.email') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>@lang('contact.mobile') <small class="text-muted">(@lang('lang_v1.required'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>@lang('contact.alternate_contact_number') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>@lang('contact.landline') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>@lang('business.city') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>@lang('business.state') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td>@lang('business.country') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>17</td>
                        <td>@lang('business.landmark') <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    @php
                        $custom_labels = json_decode(session('business.custom_labels'), true);
                    @endphp
                    <tr>
                        <td>18</td>
                         <td>{{ $custom_labels['contact']['custom_field_1'] ?? __('lang_v1.contact_custom_field1') }} <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td>{{ $custom_labels['contact']['custom_field_2'] ?? __('lang_v1.contact_custom_field2') }} <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>20</td>
                        <td>{{ $custom_labels['contact']['custom_field_3'] ?? __('lang_v1.contact_custom_field3') }} <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>21</td>
                        <td>{{ $custom_labels['contact']['custom_field_4'] ?? __('lang_v1.contact_custom_field4') }} <small class="text-muted">(@lang('lang_v1.optional'))</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>22</td>
                        <td>CNPJ/CPF <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>23</td>
                        <td>IE/RG <small class="text-muted">(Opcional)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>23</td>
                        <td>Ciudad ID <small class="text-muted">(Obrigatório)</small></td>
                        <td>Consulte la lista <a target="_blank" href="/cidades"> aquí</a> </td>
                    </tr>

                    <tr>
                        <td>24</td>
                        <td>CONSUMIDOR FINAL <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>25</td>
                        <td>CONTRIBUINTE <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>25</td>
                        <td>RUA <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>26</td>
                        <td>NUMERO <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>27</td>
                        <td>BARRIO <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>28</td>
                        <td>CEP <small class="text-muted">(Obrigatório)</small></td>
                        <td>&nbsp;</td>
                    </tr>

                </table>
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection
@extends('layouts.app')
@section('title', __( 'Rubros' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'Rubros' )
        <small>@lang( 'Administrar los rubros para las planillas' )</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'Todos los rubros' )])
        @can('user.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" 
                    href="{{action('RubrosController@create')}}" >
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</a>
                 </div>
            @endslot
        @endcan
        @can('user.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="rubros_table">
                    <thead>
                        <tr>
                            <th>@lang( 'Rubro' )</th>
                            <th>@lang( 'Alias' )</th>
                            <th>@lang( 'Categoría' )</th>
                            <th>@lang( 'Tipo' )</th>
                            <th>@lang( 'Tipo calculo' )</th>
                            <th>@lang( 'Estado' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade user_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        var users_table = $('#rubros_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/rubros',
                    columnDefs: [ {
                        "targets": [4],
                        "orderable": false,
                        "searchable": false
                    } ],
                    "columns":[
                        {"data":"name"},
                        {"data":"alias"},
                        {"data":"category"},
                        {"data":"tipo"},
                        {"data":"tipo_calculo"},
                        {"data":"status"},
                        {"data":"action"}
                    ]
                });
        $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: 'Este rubro será eliminado, desea continuar?',
              icon: "warning",
              buttons: true,
              dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();
                    $.ajax({
                        method: "DELETE",
                        url: href,
                        dataType: "json",
                        data: data,
                        success: function(result){
                            if(result.success == true){
                                console.log(result)
                                toastr.success(result.msg);
                                users_table.ajax.reload();
                            } else {
                                console.log(result)
                                toastr.error(result.msg);
                            }
                        }
                    });
                }
             });
        });        
    });   
    
</script>
@endsection

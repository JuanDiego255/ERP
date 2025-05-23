@extends('layouts.app')
@section('title', 'Controles de acceso')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Controles de acceso
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => ''])
        @can('roles.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="btn btn-block btn-primary" 
                    href="{{action('RoleController@create')}}" >
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</a>
                </div>
            @endslot
        @endcan
        @can('roles.view')
            <table class="table table-bordered table-striped" id="roles_table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Compartir</th>
                    </tr>
                </thead>
            </table>
        @endcan
    @endcomponent

</section>
<!-- /.content -->
@stop
@section('javascript')
<script type="text/javascript">
    //Roles table
    $(document).ready( function(){
        var roles_table = $('#roles_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '/roles',
                    buttons:[],
                    columnDefs: [ {
                        "targets": 1,
                        "orderable": false,
                        "searchable": false
                    } ]
                });
        $(document).on('click', 'button.delete_role_button', function(){
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_role,
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
                                toastr.success(result.msg);
                                roles_table.ajax.reload();
                            } else {
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

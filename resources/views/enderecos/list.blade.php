@extends('layouts.app')
@section('title', 'Lista de Endereços')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Endereços cliente <strong>{{$cliente->nome}}</strong>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => 'Todas as Naturezas'])
    @can('user.create')
    @slot('tool')
    <div class="box-tools">
        <a class="btn btn-block btn-primary" 
        href="/naturezas/new" >
        <i class="fa fa-plus"></i> @lang( 'messages.add' )</a>
    </div>
    @endslot
    @endcan
    @can('user.view')
    <div class="table-responsive">
        <table class="table table-bordered table-striped" id="users_table">
            <thead>
                <tr>
                    <th>Calle</th>
                    <th>Número</th>
                    <th>Barrio</th>
                    <th>CEP</th>
                    <th>Cidade</th>
                    <th>UF</th>
                    <th>Complemento</th>

                    <th>Compartir</th>
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
        var users_table = $('#users_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '/enderecosEcommerce/{{$cliente->id}}',
            columnDefs: [ {
                "targets": [4],
                "orderable": false,
                "searchable": false
            } ],
            "columns":[
            {"data":"rua"},
            {"data":"numero"},
            {"data":"bairro"},
            {"data":"cep"},
            {"data":"cidade"},
            {"data":"uf"},
            {"data":"complemento"},
            {"data":"action"}
            ]
        });
        $(document).on('click', 'button.delete_user_button', function(){
            swal({
              title: LANG.sure,
              text: LANG.confirm_delete_user,
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
                            users_table.ajax.reload();
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

<input type="hidden" id="type" value="{{ $type }}">
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalTitle">Vehículos en {{ $type == 0 ? 'mostrador' : 'mantenimiento' }}</h4>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
                <table class="table table-bordered table-striped ajax_view hide-footer" id="product_table">
                    <thead>
                        <tr>
                            <th>@lang('Vehículo')</th>
                            <th>@lang('product.category')</th>
                            <th>@lang('product.brand')</th>
                            <th>@lang('vehiculos.model')</th>
                            <th>@lang('vehiculos.color')</th>
                            <th>@lang('vehiculos.vin')</th>
                            <th>@lang('vehiculos.placa')</th>
                            <th>@lang('vehiculos.dua')</th>
                            <th>@lang('vehiculos.comprado_a')</th>
                            <th>@lang('vehiculos.created_at')</th>
                            <th>@lang('product.sku')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="modal-footer">
           {{--  <button type="button" class="btn btn-primary no-print" aria-label="Print"
                onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang('messages.print')
            </button> --}}
            <button type="button" class="btn btn-default no-print" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var type = $('#type').val();
        $('#product_table').DataTable({
            processing: true,
            serverSide: true,
            aaSorting: [
                [3, 'asc']
            ],
            "ajax": {
                "url": "/products/get-by-item/" + type,
                "data": function(d) {
                    d = __datatable_ajax_callback(d);
                }
            },
            columnDefs: [{
                "targets": [0, 1, 2],
                "orderable": false,
                "searchable": true
            }],
            columns: [{
                    data: 'product',
                    name: 'products.name'
                },
                {
                    data: 'category',
                    name: 'c1.name'
                },
                {
                    data: 'brand',
                    name: 'brands.name'
                },
                {
                    data: 'model',
                    name: 'products.model'
                },
                {
                    data: 'color',
                    name: 'products.color'
                },
                {
                    data: 'bin',
                    name: 'products.bin'
                },
                {
                    data: 'placa',
                    name: 'products.placa'
                },
                {
                    data: 'dua',
                    name: 'products.dua'
                },
                {
                    data: 'comprado_a',
                    name: 'products.comprado_a'
                },
                {
                    data: 'created_at',
                    name: 'products.created_at'
                },
                {
                    data: 'sku',
                    name: 'products.sku'
                },
            ],
            dom: '<"text-center"B><"top"p>frtip',
            fnDrawCallback: function(oSettings) {
                __currency_convert_recursively($('#product_table'));
            },

            initComplete: function() {
                var api = this.api();
                $('.dataTables_paginate').css('margin-top', '15px');

                // Indices de las columnas donde quieres aplicar los filtros
                var filterableColumns = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
                    10
                ]; // Ejemplo: 2 es la tercera columna, 3 la cuarta, etc.

                // Agregar una fila en el encabezado para los filtros de búsqueda
                $('#product_table thead').append(
                    '<tr class="filter-row"></tr>');

                // Para cada columna, verifica si debe tener un filtro y agrégalo
                api.columns().every(function(index) {
                    var column = this;
                    var headerCell = $(column.header());
                    var th = $('<th></th>').appendTo('.filter-row');

                    // Verifica si el índice de la columna está en el arreglo de columnas filtrables
                    if (filterableColumns.includes(index)) {
                        // Crear el input de búsqueda
                        var input = $(
                            '<input type="text" class="form-control" placeholder="Buscar ' +
                            headerCell.text() +
                            '" style="width: 100%;" />');

                        // Verificar si la columna tiene data: 'contact'
                        if (column.dataSrc() === 'contact') {
                            input.attr('name', 'contact_search');
                            input.attr('id', 'contact_search');
                        }

                        input.appendTo(th)
                            .on('keyup change', function() {
                                if (column.search() !== this
                                    .value) {
                                    console.log(this.value);
                                    column.search(this.value)
                                        .draw();
                                }
                            });
                    }
                });
            }
        });
    });
</script>

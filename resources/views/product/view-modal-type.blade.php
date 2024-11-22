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
                            <th>@lang('ID')</th>
                            <th>@lang('Vehículo')</th>
                            <th>@lang('product.category')</th>
                            <th>@lang('product.brand')</th>
                            <th>@lang('vehiculos.model')</th>
                            <th>@lang('vehiculos.color')</th>
                            <th>@lang('vehiculos.vin')</th>
                            <th>@lang('vehiculos.placa')</th>
                            <th>@lang('vehiculos.dua')</th>
                            <th>@lang('vehiculos.created_at')</th>
                            <th>@lang('Precio')</th>
                            <th>@lang('Estado')</th>
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
        product_table = $('#product_table').DataTable({
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
                    data: 'id',
                    name: 'products.id'
                },
                {
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
                    data: 'created_at',
                    name: 'products.created_at'
                },
                {
                    data: 'price',
                    name: 'products.price'
                },
                {
                    data: 'state',
                    name: 'state'
                }
            ],
            dom: '<"text-center"B><"top"p>frtip',
            fnDrawCallback: function(oSettings) {
                __currency_convert_recursively($('#product_table'));
            },

            initComplete: function() {
                var api = this.api();
                $('.dataTables_paginate').css('margin-top', '15px');

                // Indices de las columnas donde quieres aplicar los filtros
                var filterableColumns = [1, 2, 3, 4, 5, 6, 7, 8,
                    9
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

        $('#product_table').on('focus', 'input[type="text"]', function() {
            var input = $(this);
            var valorSinFormato = input.val().replace(/,/g, '').replace(/\.\d+$/,
                '');
            input.data('initialValue', valorSinFormato);
        });
        $('#product_table').on('blur', 'input[type="text"]', function() {
            var input = $(this);
            var value = input.val();
            var value = value.replace(/,/g, '').replace(/\.\d+$/,
                '');
            var initialValue = input.data('initialValue'); // Recupera el valor inicial
            var column_name = input.attr('name');
            var row_id = input.closest('tr').find('td').eq(0).text();
            var totalColumns = input.closest('tr').find('td').length;
            var allRows = input.closest('tbody').find('tr');
            var penultimaFila = allRows.eq(allRows.length - 2);
            var saldo = penultimaFila.find('td').eq(9).find('input').val();
            var inputType = input.attr('type'); // Obtiene el tipo de input   
            // Verificar si es un input de tipo "text" o "number" y realizar las validaciones correspondientes
            var isValid = false;
            if (inputType === 'text') {
                // Validar que el campo de texto no esté vacío
                isValid = value.trim() !== '';
            } else if (inputType === 'number') {
                // Validar que el número sea mayor o igual a 0
                isValid = value >= 0;
            }

            // Solo procede si el valor cambió, la entrada es válida y se pueden hacer actualizaciones
            if (value != initialValue && isValid && saldo != 0) {
                // Deshabilita todos los campos de entrada mientras se procesa la solicitud
                $('input[type="text"], input[type="number"]').prop('disabled', true);

                $.ajax({
                    url: '/vehicle-update-price/' + row_id,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        column: column_name,
                        value: value
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            product_table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        // Manejo de error
                    },
                    complete: function() {
                        // Rehabilita los campos de entrada después de que la solicitud se complete
                        $('input[type="text"], input[type="number"]').prop('disabled',
                            false);
                    }
                });
            }
        });
        $('#product_table').on('input', '.number', function() {
            let input = $(this).val().replace(/[^0-9]/g, ''); // Elimina todo lo que no sea un número
            if (input) {
                // Formatea el número con comas para los miles
                let formatted = new Intl.NumberFormat('en-US').format(input);
                $(this).val(formatted);
            }
        });
        $('#product_table').on('change', '.select-car', function() {
            var input = $(this);
            var value = input.val();
            var row_id = input.closest('tr').find('td').eq(0).text();
            if (value != -1) {
                $.ajax({
                    url: '/vehicle-update-state/' + row_id,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        value: value
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            product_table.ajax.reload();
                        }
                    },
                    error: function(xhr) {
                        // Manejo de error
                    },
                    complete: function() {
                        // Rehabilita los campos de entrada después de que la solicitud se complete
                        $('input[type="text"], input[type="number"]').prop('disabled',
                            false);
                    }
                });
            }
        });
    });
</script>

$(document).ready(function () {

    // var banks_table = $('#revenue_table').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     ajax: '/revenues',
    //     columnDefs: [ {
    //         "targets": [0],
    //         "orderable": false,
    //         "searchable": false
    //     } ],

    // });

    if ($('#expense_date_range').length == 1) {
        $('#expense_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#expense_date_range').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
                revenues_table.ajax.reload();
            }
        );

        $('#expense_date_range').on('cancel.daterangepicker', function (ev, picker) {
            $('#product_sr_date_filter').val('');
            revenues_table.ajax.reload();
        });
    }

    $('#location_id').change(function (ev, picker) {
        revenues_table.ajax.reload();
    });

    $('#expense_payment_status').change(function (ev, picker) {
        revenues_table.ajax.reload();
    });

    var revenues_table = $('#revenue_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [
            [1, 'desc']
        ],
        ajax: {
            url: '/revenues',
            data: function (d) {
                console.log(d)
                d.location_id = $('select#location_id').val();
                d.status = $('select#expense_payment_status').val();
                d.start_date = $('input#expense_date_range')
                    .data('daterangepicker')
                    .startDate.format('YYYY-MM-DD');
                d.end_date = $('input#expense_date_range')
                    .data('daterangepicker')
                    .endDate.format('YYYY-MM-DD');
            },
        },
        columns: [{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'contact',
                name: 'ct.name'
            },
            {
                data: 'referencia',
                name: 'referencia'
            },            
            {
                data: 'valor_total',
                name: 'valor_total'
            },
            {
                data: 'amount_paid',
                name: 'amount_paid'
            },
            {
                data: 'vehiculo',
                name: 'v.name'
            },
            {
                data: 'model',
                name: 'v.model'
            },
            {
                data: 'status',
                name: 'status',
                orderable: false
            },
        ],
        fnDrawCallback: function (oSettings) {
            var revenue_total = sum_table_col($('#revenue_table'), 'final-total');
            var amount_pend = sum_table_col($('#revenue_table'), 'payment_due');

            $('#footer_revenue_total').text(revenue_total);
            $('#footer_total_due').text(amount_pend);

            /* $('#footer_payment_status_count').html(
                __sum_status_html($('#revenue_table'), 'payment-status')
            ); */

            __currency_convert_recursively($('#revenue_table'));
        },
        initComplete: function () {
            var api = this.api();
            $('.dataTables_paginate').css('margin-top', '15px');

            // Indices de las columnas donde quieres aplicar los filtros
            var filterableColumns = [1, 2,5,6]; // Ejemplo: 2 es la tercera columna, 3 la cuarta, etc.

            // Agregar una fila en el encabezado para los filtros de búsqueda
            $('#revenue_table thead').append('<tr class="filter-row"></tr>');

            // Para cada columna, verifica si debe tener un filtro y agrégalo
            api.columns().every(function (index) {
                var column = this;
                var headerCell = $(column.header());
                var th = $('<th></th>').appendTo('.filter-row');

                // Verifica si el índice de la columna está en el arreglo de columnas filtrables
                if (filterableColumns.includes(index)) {
                    // Crear el input de búsqueda
                    var input = $('<input type="text" class="form-control" placeholder="Buscar ' + headerCell.text() + '" style="width: 100%;" />');

                    // Verificar si la columna tiene data: 'contact'
                    if (column.dataSrc() === 'contact') {
                        input.attr('name', 'contact_search');
                        input.attr('id', 'contact_search');
                    }

                    input.appendTo(th)
                        .on('keyup change', function () {
                            if (column.search() !== this.value) {
                                console.log(this.value);
                                column.search(this.value).draw();
                            }
                        });
                }
            });
        },
        dom: '<"text-center"B><"top"p>frtip'

    });


    $(document).on('click', 'a.delete_revenue', function () {
        swal({
            title: LANG.sure,
            text: 'Esta cuenta será eliminada',
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
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            revenues_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        });
    });
});

function selecionarVarios() {
    if ($('.check-boleto').css("visibility") == "hidden") {
        $('.check-boleto').css('visibility', 'visible')
        $('.btn-gerar-boletos').css('display', 'block')
    } else {
        $('.check-boleto').css('visibility', 'hidden')
        $('.btn-gerar-boletos').css('display', 'none')
    }

}

var BOELTOS = []

function boleto_selecionado(id) {

    if ($('.check-' + id).is(':checked')) {
        BOELTOS.push(id)
    } else {
        let temp = BOELTOS.filter((x) => {
            return x != id
        })
        BOELTOS = temp
    }
}

function gerarBoletos() {
    if (BOELTOS.length > 0) {
        var path = window.location.protocol + '//' + window.location.host

        location.href = path + '/boletos/gerarMultiplos/' + BOELTOS
    } else {
        swal("Atenção", "Selecione 1 ou mais boletos.", "warning")
    }
}

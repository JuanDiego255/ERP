$(document).ready(function () {
    $('body').on('click', 'label', function (e) {
        var field_id = $(this).attr('for');
        if (field_id) {
            if ($('#' + field_id).hasClass('select2')) {
                $('#' + field_id).select2('open');
                return false;
            }
        }
    });
    fileinput_setting = {
        showUpload: false,
        showPreview: false,
        browseLabel: LANG.file_browse_label,
        removeLabel: LANG.remove,
    };
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    __select2($('.select2'));

    // popover
    $('body').on('mouseover', '[data-toggle="popover"]', function () {
        if ($(this).hasClass('popover-default')) {
            return false;
        }
        $(this).popover('show');
    });

    //Date picker
    $('.start-date-picker').datepicker({
        autoclose: true,
        endDate: 'today',
    });
    $(document).on('click', '.btn-modal', function (e) {
        e.preventDefault();
        var container = $(this).data('container');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function (result) {
                $(container)
                    .html(result)
                    .modal('show');
            },
        });
    });

    $(document).on('submit', 'form#brand_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.brands_modal').modal('hide');
                    toastr.success(result.msg);
                    brands_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Brands table
    var brands_table = $('#brands_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/brands',
        dom: '<"text-center"B><"top"p>rtip',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
        initComplete: function () {
            $('.dataTables_paginate').css('margin-top', '15px');
            var api = this.api();
            var filterableColumns = [0, 1];
            $('#brands_table thead').append('<tr class="filter-row"></tr>');
            api.columns().every(function (index) {
                var column = this;
                var headerCell = $(column.header());
                var th = $('<th></th>').appendTo('.filter-row');

                // Verifica si el índice de la columna está en el arreglo de columnas filtrables
                if (filterableColumns.includes(index)) {
                    // Crear el input de búsqueda
                    var input = $(
                        '<input type="text" class="form-control" placeholder="Buscar ' +
                        headerCell.text() + '" style="width: 100%;" />');

                    input.appendTo(th)
                        .on('keyup change', function () {
                            if (column.search() !== this.value) {
                                console.log(this.value);
                                column.search(this.value).draw();
                            }
                        });
                }
            });
        }
    });

    $(document).on('click', 'button.edit_brand_button', function () {
        $('div.brands_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#brand_edit_form').submit(function (e) {
                e.preventDefault();
                $(this)
                    .find('button[type="submit"]')
                    .attr('disabled', true);
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            $('div.brands_modal').modal('hide');
                            toastr.success(result.msg);
                            brands_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_brand_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_brand,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            brands_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Start: CRUD for tax Rate

    //Tax Rates table
    var tax_rates_table = $('#tax_rates_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/tax-rates',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
    });

    $(document).on('submit', 'form#tax_rate_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.tax_rate_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_rates_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_tax_rate_button', function () {
        $('div.tax_rate_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#tax_rate_edit_form').submit(function (e) {
                e.preventDefault();
                $(this)
                    .find('button[type="submit"]')
                    .attr('disabled', true);
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            $('div.tax_rate_modal').modal('hide');
                            toastr.success(result.msg);
                            tax_rates_table.ajax.reload();
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_tax_rate_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_tax_rate,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            tax_rates_table.ajax.reload();
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //End: CRUD for tax Rate

    //Start: CRUD for unit
    //Unit table
    var units_table = $('#unit_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/units',
        columnDefs: [{
            targets: 3,
            orderable: false,
            searchable: false,
        }, ],
        columns: [{
                data: 'actual_name',
                name: 'actual_name'
            },
            {
                data: 'short_name',
                name: 'short_name'
            },
            {
                data: 'allow_decimal',
                name: 'allow_decimal'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    });

    $(document).on('submit', 'form#unit_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.unit_modal').modal('hide');
                    toastr.success(result.msg);
                    units_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_unit_button', function () {
        $('div.unit_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#unit_edit_form').submit(function (e) {
                e.preventDefault();
                $(this)
                    .find('button[type="submit"]')
                    .attr('disabled', true);
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            $('div.unit_modal').modal('hide');
                            toastr.success(result.msg);
                            units_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_unit_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_unit,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            units_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Start: CRUD for Contacts
    //contacts table
    var contact_table_type = $('#contact_type').val();
    if (contact_table_type == 'customer' || contact_table_type == 'guarantor' || contact_table_type == 'supplier') {
        var columns = [{
                data: 'action',
                searchable: false,
                orderable: false
            },
            {
                data: 'contact_id',
                name: 'contact_id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'email',
                name: 'email'
            }
        ];

        if (contact_table_type == "customer") {
            Array.prototype.push.apply(columns, [{
                    data: 'total_gen',
                    name: 'total_gen'
                },
                {
                    data: 'total_debt',
                    name: 'total_debt'
                },
                {
                    data: 'total_paid',
                    name: 'total_paid'
                },
                {
                    data: 'last_payment_date',
                    name: 'last_payment_date'
                },
                {
                    data: 'last_int',
                    name: 'last_int'
                }
            ]);
        }
        if (contact_table_type == "guarantor") {
            Array.prototype.push.apply(columns, [{
                data: 'plan_venta_numero',
                name: 'pv.numero'
            }]);
        }
    }
    var buttonsConfig = [{
            extend: 'pageLength',
            text: 'Mostrando 25',
            titleAttr: 'Mostrar registros'
        },
        {
            extend: 'colvis',
            text: 'Visibilidad de columna'
        }
    ];

    contact_table = $('#contact_table').DataTable({
        processing: true,
        serverSide: true,
        "ajax": {
            "url": "/contacts",
            "data": function (d) {
                d.type = $('#contact_type').val();
                d.customer_filter = $('select#customer_filter').val();
                d.mes_atraso = $('select#mes_atraso').val();
                d = __datatable_ajax_callback(d);
            }
        },
        aaSorting: [
            [1, 'desc']
        ],
        columns: columns,
        dom: '<"text-center"B><"top"p>rtip',
        buttons: buttonsConfig,
        fnDrawCallback: function (oSettings) {
            var total_due = sum_table_col($('#contact_table'), 'contact_due');
            $('#footer_contact_due').text(total_due);

            var total_return_due = sum_table_col($('#contact_table'), 'return_due');
            $('#footer_contact_return_due').text(total_return_due);
            __currency_convert_recursively($('#contact_table'));
        },
        initComplete: function () {
            $('.dataTables_paginate').css('margin-top', '15px');
            var api = this.api();

            // Indices de las columnas donde quieres aplicar los filtros
            var filterableColumns = [1, 2, 3]; // Ejemplo: 2 es la tercera columna, 3 la cuarta, etc.

            // Agregar una fila en el encabezado para los filtros de búsqueda
            $('#contact_table thead').append('<tr class="filter-row"></tr>');

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
        }
    });
    $('select#customer_filter').on(
        'change',
        function () {
            contact_table.ajax.reload();
        }
    );
    $('select#mes_atraso').on(
        'change',
        function () {
            contact_table.ajax.reload();
        }
    );
    $(document).on('click', '#generate_customer_exc', function () {
        let url = '/contacts/generate/customer/excel'; // Actualiza esta ruta
        let dataTable = $('#contact_table').DataTable();
        let tableFilters = {};
        let order = dataTable.order(); // Obtener la columna y la dirección de ordenación

        dataTable.columns().every(function () {
            if (this.search()) {
                tableFilters[this.index()] = this.search();
            }
        });

        // Genera los datos combinados de los filtros globales y de columnas
        let data = {
            customer_filter: $('select#customer_filter').val(),
            //mes_atraso: $('select#mes_atraso').val(),
            table_filters: tableFilters,
            order_column: order[0][0], // Columna seleccionada para ordenación
            order_direction: order[0][1] // Dirección de la ordenación
        };

        console.log(data);

        // Envía los datos combinados al backend
        $.ajax({
            url: url,
            method: 'POST',
            xhrFields: {
                responseType: 'blob',
            },
            data: data,
            success: function (result, status, xhr) {
                var disposition = xhr.getResponseHeader('content-disposition');
                var matches = /"([^"]*)"/.exec(disposition);
                var filename = (matches != null && matches[1] ? matches[1] : 'salary.xlsx');

                // The actual download
                var blob = new Blob([result], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);

                link.click();
                document.body.removeChild(link);
            },
            error: function (xhr, status, error) {
                console.error('Error al generar el reporte:', error);
            }
        });
    });

    //On display of add contact modal
    $('.contact_modal').on('shown.bs.modal', function (e) {

        $('div.lead_additional_div').hide();

        if ($('select#contact_type').val() == 'customer') {
            $('div.supplier_fields').hide();
            $('div.customer_fields').show();
        } else if ($('select#contact_type').val() == 'supplier') {
            $('div.supplier_fields').show();
            $('div.customer_fields').hide();
        } else if ($('select#contact_type').val() == 'lead') {
            $('div.supplier_fields').hide();
            $('div.customer_fields').hide();
            $('div.opening_balance').hide();
            $('div.pay_term').hide();
            $('div.lead_additional_div').show();
        }

        $('select#contact_type').change(function () {
            var t = $(this).val();

            if (t == 'supplier') {
                $('div.supplier_fields').fadeIn();
                $('div.customer_fields').fadeOut();
            } else if (t == 'both') {
                $('div.supplier_fields').fadeIn();
                $('div.customer_fields').fadeIn();
            } else if (t == 'customer') {
                $('div.customer_fields').fadeIn();
                $('div.supplier_fields').fadeOut();
            } else if (t == 'lead') {
                $('div.customer_fields').fadeOut();
                $('div.supplier_fields').fadeOut();
                $('div.opening_balance').fadeOut();
                $('div.pay_term').fadeOut();
                $('div.lead_additional_div').fadeIn();
            }
        });

        $(".contact_modal").find('.select2').each(function () {
            $(this).select2();
        });

        $('form#contact_add_form, form#contact_edit_form')
            .submit(function (e) {
                e.preventDefault();
            })
            .validate({
                rules: {
                    contact_id: {
                        remote: {
                            url: '/contacts/check-contact-id',
                            type: 'post',
                            data: {
                                contact_id: function () {
                                    return $('#contact_id').val();
                                },
                                hidden_id: function () {
                                    if ($('#hidden_id').length) {
                                        return $('#hidden_id').val();
                                    } else {
                                        return '';
                                    }
                                },
                            },
                        },
                    },
                },
                messages: {
                    contact_id: {
                        remote: LANG.contact_id_already_exists,
                    },
                },
                submitHandler: function (form) {
                    e.preventDefault();
                    var data = $(form).serialize();
                    $(form)
                        .find('button[type="submit"]')
                        .attr('disabled', true);
                    $.ajax({
                        method: 'POST',
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                $('div.contact_modal').modal('hide');
                                toastr.success(result.msg);

                                if (typeof (contact_table) != 'undefined') {
                                    contact_table.ajax.reload();
                                }

                                var lead_view = urlSearchParam('lead_view');
                                if (lead_view == 'kanban') {
                                    initializeLeadKanbanBoard();
                                } else if (lead_view == 'list_view' && typeof (leads_datatable) != 'undefined') {
                                    leads_datatable.ajax.reload();
                                }

                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                },
            });
    });

    $(document).on('click', '.edit_contact_button', function (e) {
        e.preventDefault();
        $('div.contact_modal').load($(this).attr('href'), function () {
            $(this).modal('show');
        });
    });

    $(document).on('click', '.delete_contact_button', function (e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_contact,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            contact_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('form#contact_add_form, form#contact_edit_form_receive')
        .submit(function (e) {
            e.preventDefault();
            var data = $('form#contact_edit_form_receive').serialize();
            console.log(data);
            $.ajax({
                method: 'POST',
                url: $('form#contact_edit_form_receive').attr('action'),
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });
    $('form#plan_venta_edit_form')
        .submit(function (e) {
            e.preventDefault();
            var data = $('form#plan_venta_edit_form').serialize();
            console.log(data);
            $.ajax({
                method: 'PUT',
                url: $('form#plan_venta_edit_form').attr('action'),
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });
    $('form#product_edit_form_receive')
        .submit(function (e) {
            e.preventDefault();
            var data = $('form#product_edit_form_receive').serialize();
            console.log(data);
            $.ajax({
                method: 'PUT',
                url: $('form#product_edit_form_receive').attr('action'),
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        });

    //Start: CRUD for product variations
    //Variations table
    var variation_table = $('#variation_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/variation-templates',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
    });
    $(document).on('click', '#add_variation_values', function () {
        var html =
            '<div class="form-group"><div class="col-sm-7 col-sm-offset-3"><input type="text" name="variation_values[]" class="form-control" required></div><div class="col-sm-2"><button type="button" class="btn btn-danger delete_variation_value">-</button></div></div>';
        $('#variation_values').append(html);
    });
    $(document).on('click', '.delete_variation_value', function () {
        $(this)
            .closest('.form-group')
            .remove();
    });
    $(document).on('submit', 'form#variation_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success === true) {
                    $('div.variation_modal').modal('hide');
                    toastr.success(result.msg);
                    variation_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.edit_variation_button', function () {
        $('div.variation_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#variation_edit_form').submit(function (e) {
                $(this)
                    .find('button[type="submit"]')
                    .attr('disabled', true);
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            $('div.variation_modal').modal('hide');
                            toastr.success(result.msg);
                            variation_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_variation_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_variation,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            variation_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    var active = false;
    $(document).on('mousedown', '.drag-select', function (ev) {
        active = true;
        $('.active-cell').removeClass('active-cell'); // clear previous selection

        $(this).addClass('active-cell');
        cell_value = $(this)
            .find('input')
            .val();
    });
    $(document).on('mousemove', '.drag-select', function (ev) {
        if (active) {
            $(this).addClass('active-cell');
            $(this)
                .find('input')
                .val(cell_value);
        }
    });

    $(document).mouseup(function (ev) {
        active = false;
        if (
            !$(ev.target).hasClass('drag-select') &&
            !$(ev.target).hasClass('dpp') &&
            !$(ev.target).hasClass('dsp')
        ) {
            $('.active-cell').each(function () {
                $(this).removeClass('active-cell');
            });
        }
    });

    //End: CRUD for product variations
    $(document).on('change', '.toggler', function () {
        var parent_id = $(this).attr('data-toggle_id');
        if ($(this).is(':checked')) {
            $('#' + parent_id).removeClass('hide');
        } else {
            $('#' + parent_id).addClass('hide');
        }
    });
    //Start: CRUD for products
    $(document).on('change', '#category_id', function () {
        get_sub_categories();
    });
    $(document).on('change', '#unit_id', function () {
        get_sub_units();
    });
    if ($('.product_form').length && !$('.product_form').hasClass('create')) {
        show_product_type_form();
    }
    $('#type').change(function () {
        show_product_type_form();
    });

    $(document).on('click', '#add_variation', function () {
        var row_index = $('#variation_counter').val();
        var action = $(this).attr('data-action');
        $.ajax({
            method: 'POST',
            url: '/products/get_product_variation_row',
            data: {
                row_index: row_index,
                action: action
            },
            dataType: 'html',
            success: function (result) {
                if (result) {
                    $('#product_variation_form_part  > tbody').append(result);
                    $('#variation_counter').val(parseInt(row_index) + 1);
                    toggle_dsp_input();
                }
            },
        });
    });
    //End: CRUD for products

    //bussiness settings start

    if ($('form#bussiness_edit_form').length > 0) {
        $('form#bussiness_edit_form').validate({
            ignore: [],
        });

        // logo upload
        $('#business_logo').fileinput(fileinput_setting);

        //Purchase currency
        $('input#purchase_in_diff_currency').on('ifChecked', function (event) {
            $('div#settings_purchase_currency_div, div#settings_currency_exchange_div').removeClass(
                'hide'
            );
        });
        $('input#purchase_in_diff_currency').on('ifUnchecked', function (event) {
            $('div#settings_purchase_currency_div, div#settings_currency_exchange_div').addClass(
                'hide'
            );
        });

        //Product expiry
        $('input#enable_product_expiry').change(function () {
            if ($(this).is(':checked')) {
                $('select#expiry_type').attr('disabled', false);
                $('div#on_expiry_div').removeClass('hide');
            } else {
                $('select#expiry_type').attr('disabled', true);
                $('div#on_expiry_div').addClass('hide');
            }
        });

        $('select#on_product_expiry').change(function () {
            if ($(this).val() == 'stop_selling') {
                $('input#stop_selling_before').attr('disabled', false);
                $('input#stop_selling_before')
                    .focus()
                    .select();
            } else {
                $('input#stop_selling_before').attr('disabled', true);
            }
        });

        //enable_category
        $('input#enable_category').on('ifChecked', function (event) {
            $('div.enable_sub_category').removeClass('hide');
        });
        $('input#enable_category').on('ifUnchecked', function (event) {
            $('div.enable_sub_category').addClass('hide');
        });
    }
    //bussiness settings end

    $('#upload_document').fileinput(fileinput_setting);

    //user profile
    $('form#edit_user_profile_form').validate();
    $('form#edit_password_form').validate({
        rules: {
            current_password: {
                required: true,
                minlength: 5,
            },
            new_password: {
                required: true,
                minlength: 5,
            },
            confirm_password: {
                equalTo: '#new_password',
            },
        },
    });

    //Tax Rates table
    var tax_groups_table = $('#tax_groups_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/group-taxes',
        columnDefs: [{
            targets: [2, 3],
            orderable: false,
            searchable: false,
        }, ],
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'amount',
                name: 'amount'
            },
            {
                data: 'sub_taxes',
                name: 'sub_taxes'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    });
    $('.tax_group_modal').on('shown.bs.modal', function () {
        $('.tax_group_modal')
            .find('.select2')
            .each(function () {
                __select2($(this));
            });
    });

    $(document).on('submit', 'form#tax_group_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.tax_group_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('submit', 'form#tax_group_edit_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.tax_group_modal').modal('hide');
                    toastr.success(result.msg);
                    tax_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    $(document).on('click', 'button.delete_tax_group_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_tax_group,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            tax_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //option-div
    $(document).on('click', '.option-div-group .option-div', function () {
        $(this)
            .closest('.option-div-group')
            .find('.option-div')
            .each(function () {
                $(this).removeClass('active');
            });
        $(this).addClass('active');
        $(this)
            .find('input:radio')
            .prop('checked', true)
            .change();
    });

    $(document).on('change', 'input[type=radio][name=scheme_type]', function () {
        $('#invoice_format_settings').removeClass('hide');
        var scheme_type = $(this).val();
        if (scheme_type == 'blank') {
            $('#prefix')
                .val('')
                .attr('placeholder', 'XXXX')
                .prop('disabled', false);
        } else if (scheme_type == 'year') {
            var d = new Date();
            var this_year = d.getFullYear();
            $('#prefix')
                .val(this_year + '-')
                .attr('placeholder', '')
                .prop('disabled', true);
        }
        show_invoice_preview();
    });
    $(document).on('change', '#prefix', function () {
        show_invoice_preview();
    });
    $(document).on('keyup', '#prefix', function () {
        show_invoice_preview();
    });
    $(document).on('keyup', '#start_number', function () {
        show_invoice_preview();
    });
    $(document).on('change', '#total_digits', function () {
        show_invoice_preview();
    });
    var invoice_table = $('#invoice_table').DataTable({
        processing: true,
        serverSide: true,
        bPaginate: false,
        buttons: [],
        ajax: '/invoice-schemes',
        columnDefs: [{
            targets: 4,
            orderable: false,
            searchable: false,
        }, ],
    });
    $(document).on('submit', 'form#invoice_scheme_add_form', function (e) {
        e.preventDefault();
        $(this)
            .find('button[type="submit"]')
            .attr('disabled', true);
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.invoice_modal').modal('hide');
                    $('div.invoice_edit_modal').modal('hide');
                    toastr.success(result.msg);
                    invoice_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.set_default_invoice', function () {
        var href = $(this).data('href');
        var data = $(this).serialize();

        $.ajax({
            method: 'get',
            url: href,
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success === true) {
                    toastr.success(result.msg);
                    invoice_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $('.invoice_edit_modal').on('shown.bs.modal', function () {
        show_invoice_preview();
    });
    $(document).on('click', 'button.delete_invoice_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.delete_invoice_confirm,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            invoice_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('#add_barcode_settings_form').validate();
    $(document).on('change', '#is_continuous', function () {
        if ($(this).is(':checked')) {
            $('.stickers_per_sheet_div').addClass('hide');
            $('.paper_height_div').addClass('hide');
        } else {
            $('.stickers_per_sheet_div').removeClass('hide');
            $('.paper_height_div').removeClass('hide');
        }
    });

    //initialize iCheck
    $('input[type="checkbox"].input-icheck, input[type="radio"].input-icheck').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
    });
    $(document).on('ifChecked', '.check_all', function () {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function () {
                $(this).iCheck('check');
            });
    });
    $(document).on('ifUnchecked', '.check_all', function () {
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function () {
                $(this).iCheck('uncheck');
            });
    });
    $('.check_all').each(function () {
        var length = 0;
        var checked_length = 0;
        $(this)
            .closest('.check_group')
            .find('.input-icheck')
            .each(function () {
                length += 1;
                if ($(this).iCheck('update')[0].checked) {
                    checked_length += 1;
                }
            });
        length = length - 1;
        if (checked_length != 0 && length == checked_length) {
            $(this).iCheck('check');
        }
    });

    //Business locations CRUD
    business_locations = $('#business_location_table').DataTable({
        processing: true,
        serverSide: true,
        bPaginate: false,
        buttons: [],
        ajax: '/business-location',
        columnDefs: [{
            targets: 10,
            orderable: false,
            searchable: false,
        }, ],
    });
    $('.location_add_modal, .location_edit_modal').on('shown.bs.modal', function (e) {
        $('form#business_location_add_form')
            .submit(function (e) {
                e.preventDefault();
            })
            .validate({
                rules: {
                    location_id: {
                        remote: {
                            url: '/business-location/check-location-id',
                            type: 'post',
                            data: {
                                location_id: function () {
                                    return $('#location_id').val();
                                },
                                hidden_id: function () {
                                    if ($('#hidden_id').length) {
                                        return $('#hidden_id').val();
                                    } else {
                                        return '';
                                    }
                                },
                            },
                        },
                    },
                },
                messages: {
                    location_id: {
                        remote: LANG.location_id_already_exists,
                    },
                },
                submitHandler: function (form) {
                    e.preventDefault();
                    $(form)
                        .find('button[type="submit"]')
                        .attr('disabled', true);
                    var data = $(form).serialize();

                    $.ajax({
                        method: 'POST',
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                $('div.location_add_modal').modal('hide');
                                $('div.location_edit_modal').modal('hide');
                                toastr.success(result.msg);
                                business_locations.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                },
            });

        $('form#business_location_add_form').find('#featured_products').select2({
            minimumInputLength: 2,
            allowClear: true,
            placeholder: '',
            ajax: {
                url: '/products/list?not_for_selling=true',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        term: params.term, // search term
                        page: params.page,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (obj) {
                            var string = obj.name;
                            if (obj.type == 'variable') {
                                string += '-' + obj.variation;
                            }

                            string += ' (' + obj.sub_sku + ')';
                            return {
                                id: obj.variation_id,
                                text: string
                            };
                        })
                    };
                },
            },
        })
    });

    if ($('#header_text').length) {
        init_tinymce('header_text');
    }

    if ($('#footer_text').length) {
        init_tinymce('footer_text');
    }

    //initialize tinyMCE editor for invoice template
    function init_tinymce(editor_id) {
        tinymce.init({
            selector: 'textarea#' + editor_id,
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'table template paste help'
            ],
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify |' +
                ' bullist numlist outdent indent | link image | print preview fullpage | ' +
                'forecolor backcolor',
            menu: {
                favs: {
                    title: 'My Favorites',
                    items: 'code | searchreplace'
                }
            },
            menubar: 'favs file edit view insert format tools table help'
        });
    }


    //Start: CRUD for expense category
    //Expense category table
    var expense_cat_table = $('#expense_category_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/expense-categories',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
    });
    $(document).on('submit', 'form#expense_category_add_form', function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success === true) {
                    $('div.expense_category_modal').modal('hide');
                    toastr.success(result.msg);
                    expense_cat_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('click', 'button.delete_expense_category', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_expense_category,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            expense_cat_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //date filter for expense table
    if ($('#expense_date_range').length == 1) {
        $('#expense_date_range').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#expense_date_range').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
                expense_table.ajax.reload();
            }
        );

        $('#expense_date_range').on('cancel.daterangepicker', function (ev, picker) {
            $('#product_sr_date_filter').val('');
            expense_table.ajax.reload();
        });
    }
    //Filtro segun la fecha seleccionada
    const startOfYear = moment().startOf('year'); // 1 de enero del año actual
    const endOfYear = moment().endOf('year');
    $('#type').change(function () {
        $('#date_vence_report_start').val('');
        $('#date_vence_report_end').val('');
        $('#date_report_start').val('');
        $('#date_report_end').val('');
        if ($(this).val()) {
            if ($(this).val() == 0) {
                $('#div_date').removeClass('d-none');
                $('#div_date_vence').addClass('d-none');
                //Reporte Inicial
                $('#div_date_report_start').removeClass('d-none');
                $('#div_date_report_end').removeClass('d-none');
                //Reporte Final
                $('#div_date_vence_report_start').addClass('d-none');
                $('#div_date_vence_report_end').addClass('d-none');
                // Setear las fechas predeterminadas en #expense_date_range
                $('#expense_date_range').data('daterangepicker').setStartDate(startOfYear);
                $('#expense_date_range').data('daterangepicker').setEndDate(endOfYear);
                $('#expense_date_range').val(
                    startOfYear.format(moment_date_format) + ' ~ ' + endOfYear.format(moment_date_format)
                );
                expense_table.ajax.reload();
            } else {
                $('#div_date').addClass('d-none');
                $('#div_date_vence').removeClass('d-none');
                $('#div_date_report_start').addClass('d-none');
                $('#div_date_vence_report_start').removeClass('d-none');
                $('#div_date_report_end').addClass('d-none');
                $('#div_date_vence_report_end').removeClass('d-none');
                $('#expense_date_vence').data('daterangepicker').setStartDate(startOfYear);
                $('#expense_date_vence').data('daterangepicker').setEndDate(endOfYear);
                $('#expense_date_vence').val(
                    startOfYear.format(moment_date_format) + ' ~ ' + endOfYear.format(moment_date_format)
                );
                expense_table.ajax.reload();
            }
        }
    });
    //Filtro segun la fecha seleccionada
    if ($('#expense_date_vence').length == 1) {
        $('#expense_date_vence').daterangepicker(
            dateRangeSettings,
            function (start, end) {
                $('#expense_date_vence').val(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                );
                expense_table.ajax.reload();
            }
        );

        $('#expense_date_vence').on('cancel.daterangepicker', function (ev, picker) {
            $('#expense_date_vence').val('');
            expense_table.ajax.reload();
        });
    }
    //Expense table
    var buttonsConfig = [{
            extend: 'pageLength',
            text: 'Mostrando 25',
            titleAttr: 'Mostrar registros'
        },
        {
            extend: 'colvis',
            text: 'Visibilidad de columna'
        }
    ];
    /* if (is_report) {
        buttonsConfig.push({
            extend: 'excel',
            text: 'Exportar a Excel'
        }, {
            extend: 'print',
            text: 'Impresión'
        });
    } */
    var is_report = $('#is_report').val();
    var url = is_report == 1 ? '/expense-report' : '/expenses';
    expense_table = $('#expense_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            data: function (d) {
                d.expense_for = $('select#expense_for').val();
                d.location_id = $('select#location_id').val();
                d.expense_category_id = $('select#expense_category_id').val();
                d.payment_status = $('select#expense_payment_status').val();
                if ($('input#expense_date_range').closest('.form-group').is(':visible')) {
                    d.start_date = $('input#expense_date_range')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');
                    d.end_date = $('input#expense_date_range')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                } else {
                    d.start_date = null;
                    d.end_date = null;
                }
                // Validar si el rango de fechas vence está visible antes de enviarlo
                if ($('input#expense_date_vence').closest('.form-group').is(':visible')) {
                    d.start_vence_date = $('input#expense_date_vence')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');
                    d.end_vence_date = $('input#expense_date_vence')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                } else {
                    d.start_vence_date = null;
                    d.end_vence_date = null;
                }
                d.contact = $('input#contact_search').val();
            },
        },
        columns: [{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'mass_check'
            },
            {
                data: 'prov_id',
                name: 'ct.contact_id'
            },
            {
                data: 'contact',
                name: 'ct.name'
            },

            {
                data: 'ref_no',
                name: 'ref_no'
            },
            {
                data: 'transaction_date',
                name: 'transaction_date'
            },
            {
                data: 'fecha_vence',
                name: 'fecha_vence'
            },
            {
                data: 'payment_status',
                name: 'payment_status',
                orderable: false
            },
            {
                data: 'final_total',
                name: 'final_total'
            },
            {
                data: 'payment_due',
                name: 'payment_due'
            },
            {
                data: 'additional_notes',
                name: 'additional_notes'
            },
            {
                data: 'added_by',
                name: 'usr.first_name'
            },
            {
                data: 'vehicle',
                name: 'pro.name'
            }
        ],
        fnDrawCallback: function (oSettings) {
            var expense_total = sum_table_col($('#expense_table'), 'final-total');
            $('#footer_expense_total').text(expense_total);
            var total_due = sum_table_col($('#expense_table'), 'payment_due');
            $('#footer_total_due').text(total_due);

            $('#footer_payment_status_count').html(
                __sum_status_html($('#expense_table'), 'payment-status')
            );

            __currency_convert_recursively($('#expense_table'));
        },
        createdRow: function (row, data, dataIndex) {
            $(row)
                .find('td:eq(6)')
                .attr('class', 'clickable_td');
        },
        dom: '<"text-center"B><"top"p>rtip',
        buttons: buttonsConfig,
        initComplete: function () {
            $('.dataTables_paginate').css('margin-top', '15px');
            var api = this.api();
            var filterableColumns = [3, 4];
            $('#expense_table thead').append('<tr class="filter-row"></tr>');
            api.columns().every(function (index) {
                var column = this;
                var headerCell = $(column.header());
                var th = $('<th></th>').appendTo('.filter-row');
                if (filterableColumns.includes(index)) {
                    var input = $(
                        '<input type="text" class="form-control" placeholder="Buscar ' +
                        headerCell.text() + '" style="width: 100%;" />');

                    input.appendTo(th)
                        .on('keyup change', function () {
                            if (column.search() !== this.value) {
                                console.log(this.value);
                                column.search(this.value).draw();
                            }
                        });
                }
            });
        }
    });

    //Generar CUEPAG
    $(document).on('click', '#generate_report', function () {
        if ($('#type').val() == 0) {
            if ($('#date_report_end').val() == null || $('#date_report_end').val() == "") {
                swal({
                    title: "Debe seleccionar la fecha final del filtro de creación",
                    icon: 'warning',
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        $('#date_report_end').focus();
                    }
                });
                return;
            }
        } else {
            if ($('#date_vence_report_end').val() == null || $('#date_vence_report_end').val() == "") {
                swal({
                    title: "Debe seleccionar la fecha final del filtro de vencimiento",
                    icon: 'warning',
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        $('#date_vence_report_end').focus();
                    }
                });
                return;
            }
        }
        let url = '/expenses/generate-report'; // Actualiza esta ruta
        let dataTable = $('#expense_table').DataTable();

        // Captura filtros aplicados en DataTable
        let tableFilters = {};
        dataTable.columns().every(function () {
            if (this.search()) {
                tableFilters[this.index()] = this.search();
            }
        });
        // Genera los datos combinados de los filtros globales y de columnas
        //Fechas Filtros
        var date_start = $('#date_report_start').val() ? $('#date_report_start').val() : null;
        var date_end = $('#date_report_end').val() ? $('#date_report_end').val() : null;
        var date_vence_start = $('#date_vence_report_start').val() ? $('#date_vence_report_start').val() : null;
        var date_vence_end = $('#date_vence_report_end').val() ? $('#date_vence_report_end').val() : null;
        let data = {
            payment_status: $('select#expense_payment_status').val(),
            location_id: $('select#location_id').val(),
            type: $('#type').val(),
            date_start: date_start,
            date_end: date_end,
            date_vence_start: date_vence_start,
            date_vence_end: date_vence_end,
            table_filters: tableFilters
        };
        console.log(data);
        // Envía los datos combinados al backend
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'html',
            data: data,
            success: function (result) {
                $('#view_product_modal')
                    .html(result)
                    .modal('show');
                __currency_convert_recursively($('#view_product_modal'));
            },
            error: function (xhr, status, error) {
                console.error('Error al generar el reporte:', error);
            }
        });
    });
    //Generar CUEPAG 
    //Generar CXP Detallado
    $(document).on('click', '#generate_report_detail', function () {
        if ($('#type').val() == 0) {
            if ($('#date_report_end').val() == null || $('#date_report_end').val() == "") {
                swal({
                    title: "Debe seleccionar la fecha final del filtro de creación",
                    icon: 'warning',
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        $('#date_report_end').focus();
                    }
                });
                return;
            }
        } else {
            if ($('#date_vence_report_end').val() == null || $('#date_vence_report_end').val() == "") {
                swal({
                    title: "Debe seleccionar la fecha final del filtro de vencimiento",
                    icon: 'warning',
                    buttons: {
                        confirm: {
                            text: "OK",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                    dangerMode: true,
                }).then(willDelete => {
                    if (willDelete) {
                        $('#date_vence_report_end').focus();
                    }
                });
                return;
            }
        }
        let url = '/expenses/generate-report-detail'; // Actualiza esta ruta
        let dataTable = $('#expense_table').DataTable();

        // Captura filtros aplicados en DataTable
        let tableFilters = {};
        dataTable.columns().every(function () {
            if (this.search()) {
                tableFilters[this.index()] = this.search();
            }
        });
        // Genera los datos combinados de los filtros globales y de columnas
        var date_start = $('#date_report_start').val() ? $('#date_report_start').val() : null;
        var date_end = $('#date_report_end').val() ? $('#date_report_end').val() : null;
        var date_vence_start = $('#date_vence_report_start').val() ? $('#date_vence_report_start').val() : null;
        var date_vence_end = $('#date_vence_report_end').val() ? $('#date_vence_report_end').val() : null;
        let data = {
            payment_status: $('select#expense_payment_status').val(),
            location_id: $('select#location_id').val(),
            type: $('#type').val(),
            date_start: date_start,
            date_end: date_end,
            date_vence_start: date_vence_start,
            date_vence_end: date_vence_end,
            table_filters: tableFilters
        };
        // Envía los datos combinados al backend
        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'html',
            data: data,
            success: function (result) {
                $('#view_cxp_detail_modal')
                    .html(result)
                    .modal('show');
                __currency_convert_recursively($('#view_cxp_detail_modal'));
            },
            error: function (xhr, status, error) {
                console.error('Error al generar el reporte:', error);
            }
        });
    });
    //Generar CXP Detallado    

    $('select#location_id, select#expense_for, select#expense_category_id, select#expense_payment_status').on(
        'change',
        function () {
            expense_table.ajax.reload();
        }
    );

    $(document).on('keyup change', 'input#contact_search', function () {
        //expense_table.ajax.reload();
    });


    //Date picker
    $('#expense_transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
        locale: 'es-br'
    });

    $('#vencimento').datetimepicker({
        format: moment_date_format,
        ignoreReadonly: true,
        locale: 'es-br'
    });

    $('#data_inicio_viagem').datetimepicker({
        format: moment_date_format,
        ignoreReadonly: true,
        locale: 'pt-br'
    });

    $(document).on('click', 'a.delete_expense', function (e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_expense,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            expense_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $(document).on('change', '.payment_types_dropdown', function () {
        var payment_type = $(this).val();
        var to_show = null;

        $(this)
            .closest('.payment_row')
            .find('.payment_details_div')
            .each(function () {
                if ($(this).attr('data-type') == payment_type) {
                    to_show = $(this);
                } else {
                    if (!$(this).hasClass('hide')) {
                        $(this).addClass('hide');
                    }
                }
            });

        if (to_show && to_show.hasClass('hide')) {
            to_show.removeClass('hide');
            to_show
                .find('input')
                .filter(':visible:first')
                .focus();
        }
    });

    //Start: CRUD operation for printers

    //Add Printer
    if ($('form#add_printer_form').length == 1) {
        printer_connection_type_field($('select#connection_type').val());
        $('select#connection_type').change(function () {
            var ctype = $(this).val();
            printer_connection_type_field(ctype);
        });

        $('form#add_printer_form').validate();
    }

    //Business Location Receipt setting
    if ($('form#bl_receipt_setting_form').length == 1) {
        if ($('select#receipt_printer_type').val() == 'printer') {
            $('div#location_printer_div').removeClass('hide');
        } else {
            $('div#location_printer_div').addClass('hide');
        }

        $('select#receipt_printer_type').change(function () {
            var printer_type = $(this).val();
            if (printer_type == 'printer') {
                $('div#location_printer_div').removeClass('hide');
            } else {
                $('div#location_printer_div').addClass('hide');
            }
        });

        $('form#bl_receipt_setting_form').validate();
    }

    $(document).on('click', 'a.pay_purchase_due, a.pay_sale_due', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'html',
            success: function (result) {
                $('.pay_contact_due_modal')
                    .html(result)
                    .modal('show');
                __currency_convert_recursively($('.pay_contact_due_modal'));
                $('#paid_on').datetimepicker({
                    format: moment_date_format + ' ' + moment_time_format,
                    ignoreReadonly: true,
                });
                $('.pay_contact_due_modal')
                    .find('form#pay_contact_due_form')
                    .validate();
            },
        });
    });

    //Todays profit modal
    $('#view_todays_profit').click(function () {
        var loader = '<div class="text-center">' + __fa_awesome() + '</div>';
        $('#todays_profit').html(loader);
        $('#todays_profit_modal').modal('show');
    });
    $('#todays_profit_modal').on('shown.bs.modal', function () {
        var start = $('#modal_today').val();
        var end = start;
        var location_id = '';

        updateProfitLoss(start, end, location_id, $('#todays_profit'));
    });

    //Used for Purchase & Sell invoice.
    $(document).on('click', 'a.print-invoice', function (e) {
        e.preventDefault();
        var href = $(this).data('href');

        $.ajax({
            method: 'GET',
            url: href,
            dataType: 'json',
            success: function (result) {
                if (result.success == 1 && result.receipt.html_content != '') {
                    $('#receipt_section').html(result.receipt.html_content);
                    __currency_convert_recursively($('#receipt_section'));
                    __print_receipt('receipt_section');
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Sales commission agent
    var sales_commission_agent_table = $('#sales_commission_agent_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/sales-commission-agents',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
        columns: [{
                data: 'full_name'
            },
            {
                data: 'email'
            },
            {
                data: 'contact_no'
            },
            {
                data: 'address'
            },
            {
                data: 'cmmsn_percent'
            },
            {
                data: 'action'
            },
        ],
    });
    $('div.commission_agent_modal').on('shown.bs.modal', function (e) {
        $('form#sale_commission_agent_form')
            .submit(function (e) {
                e.preventDefault();
            })
            .validate({
                submitHandler: function (form) {
                    e.preventDefault();
                    var data = $(form).serialize();

                    $.ajax({
                        method: $(form).attr('method'),
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                $('div.commission_agent_modal').modal('hide');
                                toastr.success(result.msg);
                                sales_commission_agent_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                },
            });
    });
    $(document).on('click', 'button.delete_commsn_agnt_button', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            sales_commission_agent_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('button#full_screen').click(function (e) {
        element = document.documentElement;
        if (screenfull.isEnabled) {
            screenfull.toggle(element);
        }
    });

    $(document).on('submit', 'form#customer_group_add_form', function (e) {
        e.preventDefault();
        var data = $(this).serialize();

        $.ajax({
            method: 'POST',
            url: $(this).attr('action'),
            dataType: 'json',
            data: data,
            success: function (result) {
                if (result.success == true) {
                    $('div.customer_groups_modal').modal('hide');
                    toastr.success(result.msg);
                    customer_groups_table.ajax.reload();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });

    //Customer Group table
    var customer_groups_table = $('#customer_groups_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/customer-group',
        columnDefs: [{
            targets: 2,
            orderable: false,
            searchable: false,
        }, ],
    });

    $(document).on('click', 'button.edit_customer_group_button', function () {
        $('div.customer_groups_modal').load($(this).data('href'), function () {
            $(this).modal('show');

            $('form#customer_group_edit_form').submit(function (e) {
                e.preventDefault();
                var data = $(this).serialize();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            $('div.customer_groups_modal').modal('hide');
                            toastr.success(result.msg);
                            customer_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            });
        });
    });

    $(document).on('click', 'button.delete_customer_group_button', function () {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_customer_group,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).data('href');
                var data = $(this).serialize();

                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    data: data,
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            customer_groups_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //Delete Sale
    $(document).on('click', '.delete-sale', function (e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                var is_suspended = $(this).hasClass('is_suspended');
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            if (typeof sell_table !== 'undefined') {
                                sell_table.ajax.reload();
                            }
                            //Displays list of recent transactions
                            if (typeof get_recent_transactions !== 'undefined') {
                                get_recent_transactions('final', $('div#tab_final'));
                                get_recent_transactions('draft', $('div#tab_draft'));
                            }
                            if (is_suspended) {
                                $('.view_modal').modal('hide');
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    if ($('form#add_invoice_layout_form').length > 0) {
        $('select#design').change(function () {
            if ($(this).val() == 'columnize-taxes') {
                $('div#columnize-taxes').removeClass('hide');
                $('div#columnize-taxes')
                    .find('input')
                    .removeAttr('disabled', 'false');
            } else {
                $('div#columnize-taxes').addClass('hide');
                $('div#columnize-taxes')
                    .find('input')
                    .attr('disabled', 'true');
            }
        });
    }

    $(document).on('keyup', 'form#unit_add_form input#actual_name', function () {
        $('form#unit_add_form span#unit_name').text($(this).val());
    });
    $(document).on('keyup', 'form#unit_edit_form input#actual_name', function () {
        $('form#unit_edit_form span#unit_name').text($(this).val());
    });

    $('#user_dob').datepicker({
        autoclose: true
    });

    setInterval(function () {
        getTotalUnreadNotifications()
    }, __new_notification_count_interval);

    discounts_table = $('#discounts_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: base_path + '/discount',
        columnDefs: [{
            targets: [0, 8],
            orderable: false,
            searchable: false,
        }, ],
        aaSorting: [1, 'asc'],
        columns: [{
                data: 'row_select'
            },
            {
                data: 'name',
                name: 'discounts.name'
            },
            {
                data: 'starts_at',
                name: 'starts_at'
            },
            {
                data: 'ends_at',
                name: 'ends_at'
            },
            {
                data: 'priority',
                name: 'priority'
            },
            {
                data: 'brand',
                name: 'b.name'
            },
            {
                data: 'category',
                name: 'c.name'
            },
            {
                data: 'location',
                name: 'l.name'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
    });


    types_of_service_table = $('#types_of_service_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: base_path + '/types-of-service',
        columnDefs: [{
            targets: [3],
            orderable: false,
            searchable: false,
        }, ],
        aaSorting: [1, 'asc'],
        columns: [{
                data: 'name',
                name: 'name'
            },
            {
                data: 'description',
                name: 'description'
            },
            {
                data: 'packing_charge',
                name: 'packing_charge'
            },
            {
                data: 'action',
                name: 'action'
            },
        ],
        fnDrawCallback: function (oSettings) {
            __currency_convert_recursively($('#types_of_service_table'));
        },
    });

    //Search Settings
    //Set all labels as select2 options
    label_objects = [];
    search_options = [{
        id: '',
        text: ''
    }];
    var i = 0;
    $('.pos-tab-container label').each(function () {
        label_objects.push($(this));
        var label_text = $(this).text().trim().replace(":", "").replace("*", "");
        search_options.push({
            id: i,
            text: label_text
        });
        i++;
    });
    $('#search_settings').select2({
        data: search_options,
        placeholder: LANG.search,
    });
    $('#search_settings').change(function () {
        //Get label position and add active class to the tab
        var label_index = $(this).val();
        var label = label_objects[label_index];
        $('.pos-tab-content.active').removeClass('active');
        var tab_content = label.closest('.pos-tab-content');
        tab_content.addClass('active');
        tab_index = $('.pos-tab-content').index(tab_content);
        $('.list-group-item.active').removeClass('active');
        $('.list-group-item').eq(tab_index).addClass('active');

        //Highlight the label for three seconds
        $([document.documentElement, document.body]).animate({
            scrollTop: label.offset().top - 100
        }, 500);
        label.css('background-color', 'yellow');
        setTimeout(function () {
            label.css('background-color', '');
        }, 3000);
    });
    $('#add_invoice_layout_form #design').change(function () {
        if ($(this).val() == 'slim') {
            $('#hide_price_div').removeClass('hide');
        } else {
            $('#hide_price_div').addClass('hide');
        }
    });
});

$('.quick_add_product_modal').on('shown.bs.modal', function () {
    $('.quick_add_product_modal')
        .find('.select2')
        .each(function () {
            var $p = $(this).parent();
            $(this).select2({
                dropdownParent: $p
            });
        });
    $('.quick_add_product_modal')
        .find('input[type="checkbox"].input-icheck')
        .each(function () {
            $(this).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });
        });
});


$('.discount_modal').on('shown.bs.modal', function () {
    $('.discount_modal')
        .find('.select2')
        .each(function () {
            var $p = $(this).parent();
            $(this).select2({
                dropdownParent: $p
            });
        });
    $('.discount_modal')
        .find('input[type="checkbox"].input-icheck')
        .each(function () {
            $(this).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
            });
        });
    //Datetime picker
    $('.discount_modal .discount_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });
    $('form#discount_form').validate();
});

$(document).on('submit', 'form#discount_form', function (e) {
    e.preventDefault();
    var data = $(this).serialize();

    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (result) {
            if (result.success == true) {
                $('div.discount_modal').modal('hide');
                toastr.success(result.msg);
                discounts_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('click', 'button.delete_discount_button', function () {
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).data('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        discounts_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

function printer_connection_type_field(ctype) {
    if (ctype == 'network') {
        $('div#path_div').addClass('hide');
        $('div#ip_address_div, div#port_div').removeClass('hide');
    } else if (ctype == 'windows' || ctype == 'linux') {
        $('div#path_div').removeClass('hide');
        $('div#ip_address_div, div#port_div').addClass('hide');
    }
}

function show_invoice_preview() {
    var prefix = $('#prefix').val();
    var start_number = $('#start_number').val();
    var total_digits = $('#total_digits').val();
    var preview = prefix + pad_zero(start_number, total_digits);
    $('#preview_format').text('#' + preview);
}

function pad_zero(str, max) {
    str = str.toString();
    return str.length < max ? pad_zero('0' + str, max) : str;
}

function get_sub_categories() {
    var cat = $('#category_id').val();
    $.ajax({
        method: 'POST',
        url: '/products/get_sub_categories',
        dataType: 'html',
        data: {
            cat_id: cat
        },
        success: function (result) {
            if (result) {
                $('#sub_category_id').html(result);
            }
        },
    });
}

function get_sub_units() {
    //Add dropdown for sub units if sub unit field is visible
    if ($('#sub_unit_ids').is(':visible')) {
        var unit_id = $('#unit_id').val();
        $.ajax({
            method: 'GET',
            url: '/products/get_sub_units',
            dataType: 'html',
            data: {
                unit_id: unit_id
            },
            success: function (result) {
                if (result) {
                    $('#sub_unit_ids').html(result);
                }
            },
        });
    }
}

function show_product_type_form() {

    //Disable Stock management & Woocommmerce sync if type combo
    if ($('#type').val() == 'combo') {
        $('#enable_stock').iCheck('uncheck');
        $('input[name="woocommerce_disable_sync"]').iCheck('check');
    }

    var action = $('#type').attr('data-action');
    var product_id = $('#type').attr('data-product_id');
    $.ajax({
        method: 'POST',
        url: '/products/product_form_part',
        dataType: 'html',
        data: {
            type: $('#type').val(),
            product_id: product_id,
            action: action
        },
        success: function (result) {
            if (result) {
                $('#product_form_part').html(result);
                toggle_dsp_input();
            }
        },
    });
}

$(document).on('click', 'table.ajax_view tbody tr', function (e) {
    if (
        !$(e.target).is('td.selectable_td input[type=checkbox]') &&
        !$(e.target).is('td.selectable_td') &&
        !$(e.target).is('td.clickable_td') &&
        !$(e.target).is('a') &&
        !$(e.target).is('button') &&
        !$(e.target).hasClass('label') &&
        !$(e.target).is('li') &&
        $(this).data('href') &&
        !$(e.target).is('i')
    ) {
        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function (result) {
                $('.view_modal')
                    .html(result)
                    .modal('show');
            },
        });
    }
});
$(document).on('click', 'td.clickable_td', function (e) {
    e.preventDefault();
    e.stopPropagation();
    if (e.target.tagName == 'SPAN' || e.target.tagName == 'TD') {
        return false;
    }
    var link = $(this).find('a');
    if (link.length) {
        if (!link.hasClass('no-ajax')) {
            var href = link.attr('href');
            var container = $('.payment_modal');

            $.ajax({
                url: href,
                dataType: 'html',
                success: function (result) {
                    $(container)
                        .html(result)
                        .modal('show');
                    __currency_convert_recursively(container);
                },
            });
        }
    }
});

$(document).on('click', 'button.select-all', function () {
    var this_select = $(this)
        .closest('.form-group')
        .find('select');
    this_select.find('option').each(function () {
        $(this).prop('selected', 'selected');
    });
    this_select.trigger('change');
});
$(document).on('click', 'button.deselect-all', function () {
    var this_select = $(this)
        .closest('.form-group')
        .find('select');
    this_select.find('option').each(function () {
        $(this).prop('selected', '');
    });
    this_select.trigger('change');
});

$(document).on('change', 'input.row-select', function () {
    var checked = this.checked ? 1 : 0;
    var prov_id = $(this).closest('tr').find('td').eq(2).text();
    var ref_no = $(this).closest('tr').find('td').eq(4).text();
    let url = '/expenses/check-update'; // Actualiza esta ruta
    let data = {
        checked: checked,
        prov_id: prov_id,
        ref_no: ref_no
    };
    $.ajax({
        url: url,
        method: 'POST',
        data: data,
        success: function (result) {},
        error: function (xhr, status, error) {
            console.error('Error al generar el reporte:', error);
        }
    });
});

$(document).on('click', '#select-all-row', function (e) {
    if (this.checked) {
        $(this)
            .closest('table')
            .find('tbody')
            .find('input.row-select')
            .each(function () {
                if (!this.checked) {
                    $(this)
                        .prop('checked', true)
                        .change();
                }
            });
    } else {
        $(this)
            .closest('table')
            .find('tbody')
            .find('input.row-select')
            .each(function () {
                if (this.checked) {
                    $(this)
                        .prop('checked', false)
                        .change();
                }
            });
    }
});

$(document).on('click', 'a.view_purchase_return_payment_modal', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var href = $(this).attr('href');
    var container = $('.payment_modal');

    $.ajax({
        url: href,
        dataType: 'html',
        success: function (result) {
            $(container)
                .html(result)
                .modal('show');
            __currency_convert_recursively(container);
        },
    });
});

$(document).on('click', 'a.view_invoice_url', function (e) {
    e.preventDefault();
    $('div.view_modal').load($(this).attr('href'), function () {
        $(this).modal('show');
    });
    return false;
});
$(document).on('click', '.load_more_notifications', function (e) {
    e.preventDefault();
    var this_link = $(this);
    this_link.text(LANG.loading + '...');
    this_link.attr('disabled', true);
    var page = parseInt($('input#notification_page').val()) + 1;
    var href = '/load-more-notifications?page=' + page;
    $.ajax({
        url: href,
        dataType: 'html',
        success: function (result) {
            if ($('li.no-notification').length == 0) {
                $('ul#notifications_list').append(result);
                // $(result).append(this_link.closest('li'));
            }

            this_link.text(LANG.load_more);
            this_link.removeAttr('disabled');
            $('input#notification_page').val(page);
        },
    });
    return false;
});

$(document).on('click', 'a.load_notifications', function (e) {
    e.preventDefault();
    $('li.load_more_li').addClass('hide');
    var this_link = $(this);
    var href = '/load-more-notifications?page=1';
    $('span.notifications_count').html(__fa_awesome());
    $.ajax({
        url: href,
        dataType: 'html',
        success: function (result) {
            $('li.notification-li').remove();
            $('ul#notifications_list').prepend(result);
            $('span.notifications_count').text('');
            $('li.load_more_li').removeClass('hide');
        },
    });
});

$(document).on('click', 'a.delete_purchase_return', function (e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).attr('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        purchase_return_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

$(document).on('submit', 'form#types_of_service_form', function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $(this).find('button[type="submit"]').attr('disabled', true);
    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (result) {
            if (result.success == true) {
                $('div.type_of_service_modal').modal('hide');
                toastr.success(result.msg);
                types_of_service_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('click', 'button.delete_type_of_service', function (e) {
    e.preventDefault();
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            var href = $(this).data('href');
            var data = $(this).serialize();

            $.ajax({
                method: 'DELETE',
                url: href,
                dataType: 'json',
                data: data,
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        types_of_service_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

$(document).on('submit', 'form#edit_shipping_form', function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $(this)
        .find('button[type="submit"]')
        .attr('disabled', true);
    $.ajax({
        method: $(this).attr('method'),
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (result) {
            if (result.success == true) {
                $('div.view_modal').modal('hide');
                toastr.success(result.msg);
                sell_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        },
    });
});

$(document).on('show.bs.modal', '.register_details_modal, .close_register_modal', function () {
    __currency_convert_recursively($(this));
});

function updateProfitLoss(start = null, end = null, location_id = null, selector = null) {
    if (start == null) {
        var start = $('#profit_loss_date_filter')
            .data('daterangepicker')
            .startDate.format('YYYY-MM-DD');
    }
    if (end == null) {
        var end = $('#profit_loss_date_filter')
            .data('daterangepicker')
            .endDate.format('YYYY-MM-DD');
    }
    if (location_id == null) {
        var location_id = $('#profit_loss_location_filter').val();
    }
    var data = {
        start_date: start,
        end_date: end,
        location_id: location_id
    };
    selector = selector == null ? $('#pl_data_div') : selector;
    var loader = '<div class="text-center">' + __fa_awesome() + '</div>';
    selector.html(loader);
    $.ajax({
        method: 'GET',
        url: '/reports/profit-loss',
        dataType: 'html',
        data: data,
        success: function (html) {
            selector.html(html);
            __currency_convert_recursively(selector);
        },
    });
}

$(document).on('click', 'button.activate-deactivate-location', function () {
    swal({
        title: LANG.sure,
        icon: 'warning',
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            $.ajax({
                url: $(this).data('href'),
                dataType: 'json',
                success: function (result) {
                    if (result.success == true) {
                        toastr.success(result.msg);
                        business_locations.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                },
            });
        }
    });
});

function getTotalUnreadNotifications() {
    if ($('span.notifications_count').lenght) {
        var href = '/get-total-unread';
        $.ajax({
            url: href,
            dataType: 'json',
            global: false,
            success: function (data) {
                if (data.total_unread != 0) {
                    $('span.notifications_count').text(data.total_unread);
                }
            },
        });
    }
}

$(document).on('shown.bs.modal', '.view_modal', function (e) {
    if ($(this).find('#email_body').length) {
        tinymce.init({
            selector: 'textarea#email_body',
        });
    }
});
$(document).on('hidden.bs.modal', '.view_modal', function (e) {
    if ($(this).find('#email_body').length) {
        tinymce.remove("textarea#email_body");
    }

    //check if modal opened then make it scrollable
    if ($('.modal.in').length > 0) {
        $('body').addClass('modal-open');
    }
});
$(document).on('shown.bs.modal', '.quick_add_product_modal', function (e) {
    tinymce.init({
        selector: 'textarea#product_description',
    });
});
$(document).on('hidden.bs.modal', '.quick_add_product_modal', function (e) {
    tinymce.remove("textarea#product_description");
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('.scrolltop:hidden').stop(true, true).fadeIn();
    } else {
        $('.scrolltop').stop(true, true).fadeOut();
    }
});
$(function () {
    $(".scroll").click(function () {
        $("html,body").animate({
            scrollTop: $(".thetop").offset().top
        }, "1000");
        return false
    })
})

$(document).on('click', 'a.update_contact_status', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    $.ajax({
        url: href,
        dataType: 'json',
        success: function (data) {
            if (data.success == true) {
                toastr.success(data.msg);
                contact_table.ajax.reload();
            } else {
                toastr.error(data.msg);
            }
        },
    });
});
//Formato de inputs de tipo precio o montos
$(document).on('input', '.precio', function () {
    let input = $(this).val().replace(/[^0-9.]/g, ''); // Permite números y un punto decimal

    // Asegúrate de que sólo haya un punto decimal
    if ((input.match(/\./g) || []).length > 1) {
        input = input.replace(/\.+$/, ""); // Remueve puntos adicionales
    }

    if (input) {
        // Si el número tiene parte decimal, no lo formateamos con comas aún
        let parts = input.split('.');
        let formatted = new Intl.NumberFormat('en-US').format(parts[0]); // Formatea los miles
        if (parts[1] !== undefined) {
            input = formatted + '.' + parts[1]; // Recompone el número con la parte decimal
        } else {
            input = formatted;
        }
        $(this).val(input);
    }
});
// Actualizar el campo total_recibido en plan de ventas
var vehiculosSeleccionados = {};
var vehiculoInputs = ['vehiculo_venta_id', 'vehiculo_recibido_id'];

function actualizarTotalRecibido(tipo) {
    var montoEfectivo = parseFloat($('#monto_efectivo').val().replace(/,/g, '')) || 0;
    var montoRecibo = parseFloat($('#monto_recibo').val().replace(/,/g, '')) || 0;
    var montoFormat = Intl.NumberFormat('en-US').format(montoEfectivo + montoRecibo);
    $('#total_recibido').val(montoFormat);
    totalRecibido = parseFloat($('#total_recibido').val().replace(/,/g, '')) || 0;
    ventaSinRebajos = parseFloat($('#venta_sin_rebajos').val().replace(/,/g, '')) || 0;
    if (ventaSinRebajos >= totalRecibido) {
        montoFormat = Intl.NumberFormat('en-US').format(ventaSinRebajos - totalRecibido);
        $('#total_financiado').val(montoFormat);
    }
}
// Función para sumar/restar montos de efectivo/recibo en plan de ventas
function sumarRestarMonto(tipo, monto) {
    var montoActual;
    if (tipo === 'venta') {
        montoVenta = parseFloat($('#monto_venta').val().replace(/,/g, '')) || 0;
        var montoFormat = Intl.NumberFormat('en-US').format(monto);
        $('#monto_efectivo').val(montoFormat);
        if (montoVenta > monto) {
            montoFormat = Intl.NumberFormat('en-US').format(montoVenta);
            $('#venta_sin_rebajos').val(montoFormat);
            montoFormat = Intl.NumberFormat('en-US').format(montoVenta - monto);
            $('#total_financiado').val(montoFormat);
        }
    } else {
        // Obtener el valor actual y remover comas para poder convertirlo a número
        let montoActual = parseFloat($('#monto_recibo').val().replace(/,/g, ''));

        // Verificar si es un número válido, si no, asumir que es 0
        montoActual = isNaN(montoActual) ? 0 : montoActual;

        // Sumar el nuevo monto al actual si el actual es mayor que 0
        let montoFinal = montoActual > 0 ? montoActual + monto : monto;

        // Formatear e insertar en el campo
        let montoFormat = Intl.NumberFormat('en-US').format(montoFinal);
        $('#monto_recibo').val(montoFormat);
    }

    // Actualizar el total recibido
    actualizarTotalRecibido(tipo);
}
// Guardar el vehículo seleccionado en plan de ventas
$(document).on('click', '.save_vehicle', function () {
    var selectedVehicleId = $('#vehiculo_id').val();
    var monto = 0;

    // Determinar si es "recibido" o "venta" para obtener el monto correcto
    if (currentInput.includes('recibido')) {
        monto = parseFloat($('#monto_recibo_modal').val().replace(/,/g, '')) || 0;
    } else {
        monto = parseFloat($('#efectivo').val().replace(/,/g, '')) || 0;
    }

    if (selectedVehicleId !== "" && monto >= 0) {
        var selectedVehicleName = $('#vehiculo_id option:selected').text();

        // Si ya había un vehículo en el input actual, restar su monto
        if (vehiculosSeleccionados[currentInput]) {
            var vehiculoAnteriorMonto = vehiculosSeleccionados[currentInput].monto;
            sumarRestarMonto(vehiculosSeleccionados[currentInput].tipo, -vehiculoAnteriorMonto);
        }

        // Asignar el nuevo vehículo y su monto
        $('#' + currentInput).val(selectedVehicleName);
        $('#' + currentInput + '_hidden').val(selectedVehicleId);

        vehiculosSeleccionados[currentInput] = {
            id: selectedVehicleId,
            monto: monto,
            tipo: currentInput.includes('recibido') ? 'recibido' : 'venta'
        };

        sumarRestarMonto(vehiculosSeleccionados[currentInput].tipo, monto);

        $('.car_modal').modal('hide');
    } else {
        toastr.error("Debe seleccionar un vehículo y llenar los montos correspondientes.");
    }
});
// Verificar si un vehículo ya está seleccionado en plan de ventas
function vehiculoSeleccionado(vehiculoId) {
    return Object.values(vehiculosSeleccionados).some(function (vehiculo) {
        return vehiculo.id === vehiculoId;
    });
}
//Remover carros del plan de ventas
$(document).on('click', '.remove_cars', function () {
    $('#total_financiado').val(0);
    $('#total_recibido').val(0);
    $('#monto_recibo').val(0);
    $('#monto_efectivo').val(0);
    $('#vehiculo_venta_id').val("");
    $('#vehiculo_recibido_id').val("");
    $('#vehiculo_recibido_id_dos').val("");
});
//Boton para guardar carro desde plan de ventas
$(document).on('submit', 'form#product_add_form_pv', function (e) {
    e.preventDefault();
    var data = $(this).serialize();
    $.ajax({
        method: 'post',
        url: $(this).attr('action'),
        dataType: 'json',
        data: data,
        success: function (response) {
            // Manejar la respuesta exitosa
            if (response.success == 1) {
                toastr.success('El vehículo ha sido guardado con éxito');
                $('#vehiculo_recibido_id').val(response.name);
                $('#vehiculo_recibido_id_hidden').val(response.product_id);
                $('.car_new_modal').modal('hide');
            } else {
                toastr.warning(response.msg);
            }

        },
        error: function (xhr, status, error) {
            // Manejar errores
            alert('Ocurrió un error. Por favor intenta nuevamente.');
            console.log(xhr.responseText); // Para depuración
        },
    });
});

// Recalcular montos si se cambia el valor de efectivo o recibo manualmente desde plan de ventas
$('#monto_efectivo, #monto_recibo').on('input', function () {
    actualizarTotalRecibido();
});

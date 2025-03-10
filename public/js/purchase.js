$(document).ready(function () {

    if ($('input#iraqi_selling_price_adjustment').length > 0) {
        iraqi_selling_price_adjustment = true;
    } else {
        iraqi_selling_price_adjustment = false;
    }

    //Date picker
    $('#transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
    });

    //get suppliers
    $('#supplier_id').select2({
        ajax: {
            url: '/purchases/get_suppliers',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
        },
        minimumInputLength: 1,
        escapeMarkup: function (m) {
            return m;
        },
        templateResult: function (data) {
            console.log(data)
            if (!data.id) {
                return data.text;
            }
            var html = data.text + ' - ' + data.business_name + ' (' + data.contact_id + ')';
            return html;
        },
        language: {
            noResults: function () {
                var name = $('#supplier_id')
                    .data('select2')
                    .dropdown.$search.val();
                return (
                    '<button type="button" data-name="' +
                    name +
                    '" class="btn btn-link add_new_supplier"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                    __translate('add_name_as_new_supplier', {
                        name: name
                    }) +
                    '</button>'
                );
            },
        },
    }).on('select2:select', function (e) {
        var data = e.params.data;
        $('#pay_term_number').val(data.pay_term_number);
        $('#pay_term_type').val(data.pay_term_type);
    });

    $('#is_cxp').change(function () {
        if ($(this).is(':checked')) {
            // Si está marcado, mostramos los campos
            $('#fecha_vence_container').show();
            $('#plazo_container').show();
            var factura = $('#factura').val();
            if (factura != "") {
                $.ajax({
                    url: '/expense/check-ref_no',
                    type: 'POST',
                    data: {
                        ref_no: factura
                    },
                    success: function (response) {
                        if (response.valid) {
                            swal({
                                title: "La factura digitada ya existe",
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
                                    $('#factura').val('').focus();
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Manejo de errores
                        console.error("Error in validation request:", error);
                    }
                });
            }
        } else {
            // Si no está marcado, ocultamos los campos
            $('#fecha_vence_container').hide();
            $('#plazo_container').hide();
        }
    });

    //Quick add supplier
    $(document).on('click', '.add_new_supplier', function () {
        $('#supplier_id').select2('close');
        var name = $(this).data('name');
        $('.contact_modal')
            .find('input#name')
            .val(name);
        $('.contact_modal')
            .find('select#contact_type')
            .val('supplier')
            .closest('div.contact_type_div')
            .addClass('hide');
        $('.contact_modal').modal('show');
    });

    $('form#quick_add_contact')
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
                            $('select#supplier_id').append(
                                $('<option>', {
                                    value: result.data.id,
                                    text: result.data.name
                                })
                            );
                            $('select#supplier_id')
                                .val(result.data.id)
                                .trigger('change');
                            $('div.contact_modal').modal('hide');
                            toastr.success(result.msg);
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            },
        });
    $('.contact_modal').on('hidden.bs.modal', function () {
        $('form#quick_add_contact')
            .find('button[type="submit"]')
            .removeAttr('disabled');
        $('form#quick_add_contact')[0].reset();
    });

    //Add products
    if ($('#search_product').length > 0) {
        $('#search_product')
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(
                        '/purchases/get_products', {
                            location_id: $('#location_id').val(),
                            term: request.term
                        },
                        response
                    );
                },
                minLength: 2,
                response: function (event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        $(this)
                            .data('ui-autocomplete')
                            ._trigger('select', 'autocompleteselect', ui);
                        $(this).autocomplete('close');
                    } else if (ui.content.length == 0) {
                        var term = $(this).data('ui-autocomplete').term;
                        swal({
                            title: LANG.no_products_found,
                            text: __translate('add_name_as_new_product', {
                                term: term
                            }),
                            buttons: [LANG.cancel, LANG.ok],
                        }).then(value => {
                            if (value) {
                                var container = $('.quick_add_product_modal');
                                $.ajax({
                                    url: '/products/quick_add?product_name=' + term,
                                    dataType: 'html',
                                    success: function (result) {
                                        $(container)
                                            .html(result)
                                            .modal('show');
                                    },
                                });
                            }
                        });
                    }
                },
                select: function (event, ui) {
                    $(this).val(null);
                    get_purchase_entry_row(ui.item.product_id, ui.item.variation_id);
                },
            })
            .autocomplete('instance')._renderItem = function (ul, item) {
                return $('<li>')
                    .append('<div>' + item.text + '</div>')
                    .appendTo(ul);
            };
    }

    $(document).on('click', '.remove_purchase_entry_row', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(value => {
            if (value) {
                $(this)
                    .closest('tr')
                    .remove();
                update_table_total();
                update_grand_total();
                update_table_sr_number();
            }
        });
    });

    //On Change of quantity
    $(document).on('change', '.purchase_quantity', function () {
        var row = $(this).closest('tr');
        var quantity = __read_number($(this), true);
        var purchase_before_tax = __read_number(row.find('input.purchase_unit_cost'), true);
        var purchase_after_tax = __read_number(
            row.find('input.purchase_unit_cost_after_tax'),
            true
        );

        //Calculate sub totals
        var sub_total_before_tax = quantity * purchase_before_tax;
        var sub_total_after_tax = quantity * purchase_after_tax;

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(sub_total_before_tax, false, true)
        );
        __write_number(
            row.find('input.row_subtotal_before_tax_hidden'),
            sub_total_before_tax,
            true
        );

        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);

        update_table_total();
        update_grand_total();
    });

    $(document).on('change', '.purchase_unit_cost_without_discount', function () {
        var purchase_before_discount = __read_number($(this), true);

        var row = $(this).closest('tr');
        var discount_percent = __read_number(row.find('input.inline_discounts'), true);
        var quantity = __read_number(row.find('input.purchase_quantity'), true);

        //Calculations.
        var purchase_before_tax =
            parseFloat(purchase_before_discount) -
            __calculate_amount('percentage', discount_percent, purchase_before_discount);

        __write_number(row.find('input.purchase_unit_cost'), purchase_before_tax, true);

        var sub_total_before_tax = quantity * purchase_before_tax;

        //Tax
        var tax_rate = parseFloat(
            row
            .find('select.purchase_line_tax_id')
            .find(':selected')
            .data('tax_amount')
        );
        var tax = __calculate_amount('percentage', tax_rate, purchase_before_tax);

        var purchase_after_tax = purchase_before_tax + tax;
        var sub_total_after_tax = quantity * purchase_after_tax;

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(sub_total_before_tax, false, true)
        );
        __write_number(
            row.find('input.row_subtotal_before_tax_hidden'),
            sub_total_before_tax,
            true
        );

        __write_number(row.find('input.purchase_unit_cost_after_tax'), purchase_after_tax, true);
        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);

        row.find('.purchase_product_unit_tax_text').text(
            __currency_trans_from_en(tax, false, true)
        );
        __write_number(row.find('input.purchase_product_unit_tax'), tax, true);

        update_inline_profit_percentage(row);
        update_table_total();
        update_grand_total();
    });

    $(document).on('change', '.inline_discounts', function () {
        var row = $(this).closest('tr');

        var discount_percent = __read_number($(this), true);

        var quantity = __read_number(row.find('input.purchase_quantity'), true);
        var purchase_before_discount = __read_number(
            row.find('input.purchase_unit_cost_without_discount'),
            true
        );

        //Calculations.
        var purchase_before_tax =
            parseFloat(purchase_before_discount) -
            __calculate_amount('percentage', discount_percent, purchase_before_discount);

        __write_number(row.find('input.purchase_unit_cost'), purchase_before_tax, true);

        var sub_total_before_tax = quantity * purchase_before_tax;

        //Tax
        var tax_rate = parseFloat(
            row
            .find('select.purchase_line_tax_id')
            .find(':selected')
            .data('tax_amount')
        );
        var tax = __calculate_amount('percentage', tax_rate, purchase_before_tax);

        var purchase_after_tax = purchase_before_tax + tax;
        var sub_total_after_tax = quantity * purchase_after_tax;

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(sub_total_before_tax, false, true)
        );
        __write_number(
            row.find('input.row_subtotal_before_tax_hidden'),
            sub_total_before_tax,
            true
        );

        __write_number(row.find('input.purchase_unit_cost_after_tax'), purchase_after_tax, true);
        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);
        row.find('.purchase_product_unit_tax_text').text(
            __currency_trans_from_en(tax, false, true)
        );
        __write_number(row.find('input.purchase_product_unit_tax'), tax, true);

        update_inline_profit_percentage(row);
        update_table_total();
        update_grand_total();
    });

    $(document).on('change', '.purchase_unit_cost', function () {
        var row = $(this).closest('tr');
        var quantity = __read_number(row.find('input.purchase_quantity'), true);
        var purchase_before_tax = __read_number($(this), true);

        var sub_total_before_tax = quantity * purchase_before_tax;

        //Update unit cost price before discount
        var discount_percent = __read_number(row.find('input.inline_discounts'), true);
        var purchase_before_discount = __get_principle(purchase_before_tax, discount_percent, true);
        __write_number(
            row.find('input.purchase_unit_cost_without_discount'),
            purchase_before_discount,
            true
        );

        //Tax
        var tax_rate = parseFloat(
            row
            .find('select.purchase_line_tax_id')
            .find(':selected')
            .data('tax_amount')
        );
        var tax = __calculate_amount('percentage', tax_rate, purchase_before_tax);

        var purchase_after_tax = purchase_before_tax + tax;
        var sub_total_after_tax = quantity * purchase_after_tax;

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(sub_total_before_tax, false, true)
        );
        __write_number(
            row.find('input.row_subtotal_before_tax_hidden'),
            sub_total_before_tax,
            true
        );

        row.find('.purchase_product_unit_tax_text').text(
            __currency_trans_from_en(tax, false, true)
        );
        __write_number(row.find('input.purchase_product_unit_tax'), tax, true);

        //row.find('.purchase_product_unit_tax_text').text( tax );
        __write_number(row.find('input.purchase_unit_cost_after_tax'), purchase_after_tax, true);
        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);

        update_inline_profit_percentage(row);
        update_table_total();
        update_grand_total();
    });

    $(document).on('change', 'select.purchase_line_tax_id', function () {
        var row = $(this).closest('tr');
        var purchase_before_tax = __read_number(row.find('.purchase_unit_cost'), true);
        var quantity = __read_number(row.find('input.purchase_quantity'), true);

        //Tax
        var tax_rate = parseFloat(
            $(this)
            .find(':selected')
            .data('tax_amount')
        );
        var tax = __calculate_amount('percentage', tax_rate, purchase_before_tax);

        //Purchase price
        var purchase_after_tax = purchase_before_tax + tax;
        var sub_total_after_tax = quantity * purchase_after_tax;

        row.find('.purchase_product_unit_tax_text').text(
            __currency_trans_from_en(tax, false, true)
        );
        __write_number(row.find('input.purchase_product_unit_tax'), tax, true);

        __write_number(row.find('input.purchase_unit_cost_after_tax'), purchase_after_tax, true);

        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);

        update_table_total();
        update_grand_total();
    });

    $(document).on('change', '.purchase_unit_cost_after_tax', function () {
        var row = $(this).closest('tr');
        var purchase_after_tax = __read_number($(this), true);
        var quantity = __read_number(row.find('input.purchase_quantity'), true);

        var sub_total_after_tax = purchase_after_tax * quantity;

        //Tax
        var tax_rate = parseFloat(
            row
            .find('select.purchase_line_tax_id')
            .find(':selected')
            .data('tax_amount')
        );
        var purchase_before_tax = __get_principle(purchase_after_tax, tax_rate);
        var sub_total_before_tax = quantity * purchase_before_tax;
        var tax = __calculate_amount('percentage', tax_rate, purchase_before_tax);

        //Update unit cost price before discount
        var discount_percent = __read_number(row.find('input.inline_discounts'), true);
        var purchase_before_discount = __get_principle(purchase_before_tax, discount_percent, true);
        __write_number(
            row.find('input.purchase_unit_cost_without_discount'),
            purchase_before_discount,
            true
        );

        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(sub_total_after_tax, false, true)
        );
        __write_number(row.find('input.row_subtotal_after_tax_hidden'), sub_total_after_tax, true);

        __write_number(row.find('.purchase_unit_cost'), purchase_before_tax, true);

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(sub_total_before_tax, false, true)
        );
        __write_number(
            row.find('input.row_subtotal_before_tax_hidden'),
            sub_total_before_tax,
            true
        );

        row.find('.purchase_product_unit_tax_text').text(__currency_trans_from_en(tax, true, true));
        __write_number(row.find('input.purchase_product_unit_tax'), tax);

        update_table_total();
        update_grand_total();
    });

    $('#tax_id, #discount_type, #discount_amount, input#shipping_charges').change(function () {
        update_grand_total();
    });

    //Purchase table
    purchase_table = $('#purchase_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/purchases',
            data: function (d) {
                if ($('#purchase_list_filter_location_id').length) {
                    d.location_id = $('#purchase_list_filter_location_id').val();
                }
                if ($('#purchase_list_filter_supplier_id').length) {
                    d.supplier_id = $('#purchase_list_filter_supplier_id').val();
                }
                if ($('#purchase_list_filter_payment_status').length) {
                    d.payment_status = $('#purchase_list_filter_payment_status').val();
                }
                if ($('#purchase_list_filter_status').length) {
                    d.status = $('#purchase_list_filter_status').val();
                }

                var start = '';
                var end = '';
                if ($('#purchase_list_filter_date_range').val()) {
                    start = $('input#purchase_list_filter_date_range')
                        .data('daterangepicker')
                        .startDate.format('YYYY-MM-DD');
                    end = $('input#purchase_list_filter_date_range')
                        .data('daterangepicker')
                        .endDate.format('YYYY-MM-DD');
                }
                d.start_date = start;
                d.end_date = end;

                d = __datatable_ajax_callback(d);
            },
        },
        aaSorting: [
            [1, 'desc']
        ],
        columns: [{
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'transaction_date',
                name: 'transaction_date'
            },
            {
                data: 'ref_no',
                name: 'ref_no'
            },
            {
                data: 'location_name',
                name: 'BS.name'
            },
            {
                data: 'name',
                name: 'contacts.name'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'payment_status',
                name: 'payment_status'
            },
            {
                data: 'final_total',
                name: 'final_total'
            },
            {
                data: 'payment_due',
                name: 'payment_due',
                orderable: false,
                searchable: false
            },
            {
                data: 'added_by',
                name: 'u.first_name'
            },
            {
                data: 'fiscal',
                name: 'fiscal'
            },
        ],
        fnDrawCallback: function (oSettings) {
            var total_purchase = sum_table_col($('#purchase_table'), 'final_total');
            $('#footer_purchase_total').text(total_purchase);

            var total_due = sum_table_col($('#purchase_table'), 'payment_due');
            $('#footer_total_due').text(total_due);

            var total_purchase_return_due = sum_table_col($('#purchase_table'), 'purchase_return');
            $('#footer_total_purchase_return_due').text(total_purchase_return_due);

            $('#footer_status_count').html(__sum_status_html($('#purchase_table'), 'status-label'));

            $('#footer_payment_status_count').html(
                __sum_status_html($('#purchase_table'), 'payment-status-label')
            );

            __currency_convert_recursively($('#purchase_table'));
        },
        createdRow: function (row, data, dataIndex) {
            $(row)
                .find('td:eq(5)')
                .attr('class', 'clickable_td');
        },
    });

    $(document).on(
        'change',
        '#purchase_list_filter_location_id, \
        #purchase_list_filter_supplier_id, #purchase_list_filter_payment_status,\
        #purchase_list_filter_status',
        function () {
            purchase_table.ajax.reload();
        }
    );

    update_table_sr_number();

    $(document).on('change', '.mfg_date', function () {
        var this_date = $(this).val();
        var this_moment = moment(this_date, moment_date_format);
        var expiry_period = parseFloat(
            $(this)
            .closest('td')
            .find('.row_product_expiry')
            .val()
        );
        var expiry_period_type = $(this)
            .closest('td')
            .find('.row_product_expiry_type')
            .val();
        if (this_date) {
            if (expiry_period && expiry_period_type) {
                exp_date = this_moment
                    .add(expiry_period, expiry_period_type)
                    .format(moment_date_format);
                $(this)
                    .closest('td')
                    .find('.exp_date')
                    .datepicker('update', exp_date);
            } else {
                $(this)
                    .closest('td')
                    .find('.exp_date')
                    .datepicker('update', '');
            }
        } else {
            $(this)
                .closest('td')
                .find('.exp_date')
                .datepicker('update', '');
        }
    });

    $('#purchase_entry_table tbody')
        .find('.expiry_datepicker')
        .each(function () {
            $(this).datepicker({
                autoclose: true,
                format: datepicker_date_format,
            });
        });

    $(document).on('change', '.profit_percent', function () {
        var row = $(this).closest('tr');
        var profit_percent = __read_number($(this), true);

        var purchase_unit_cost = __read_number(row.find('input.purchase_unit_cost_after_tax'), true);
        var default_sell_price =
            parseFloat(purchase_unit_cost) +
            __calculate_amount('percentage', profit_percent, purchase_unit_cost);
        var exchange_rate = $('input#exchange_rate').val();
        __write_number(
            row.find('input.default_sell_price'),
            default_sell_price * exchange_rate,
            true
        );
    });

    $(document).on('change', '.default_sell_price', function () {
        var row = $(this).closest('tr');
        update_inline_profit_percentage(row);
    });

    $('table#purchase_table tbody').on('click', 'a.delete-purchase', function (e) {
        e.preventDefault();
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                var href = $(this).attr('href');
                $.ajax({
                    method: 'DELETE',
                    url: href,
                    dataType: 'json',
                    success: function (result) {
                        if (result.success == true) {
                            toastr.success(result.msg);
                            purchase_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    $('table#purchase_entry_table').on('change', 'select.sub_unit', function () {
        var tr = $(this).closest('tr');
        var base_unit_cost = tr.find('input.base_unit_cost').val();
        var base_unit_selling_price = tr.find('input.base_unit_selling_price').val();

        var multiplier = parseFloat(
            $(this)
            .find(':selected')
            .data('multiplier')
        );

        var unit_sp = base_unit_selling_price * multiplier;
        var unit_cost = base_unit_cost * multiplier;

        var sp_element = tr.find('input.default_sell_price');
        __write_number(sp_element, unit_sp);

        var cp_element = tr.find('input.purchase_unit_cost_without_discount');
        __write_number(cp_element, unit_cost);
        cp_element.change();
    });
    toggle_search();
});

function get_purchase_entry_row(product_id, variation_id) {
    if (product_id) {
        var row_count = $('#row_count').val();
        var location_id = $('#location_id').val();
        $.ajax({
            method: 'POST',
            url: '/purchases/get_purchase_entry_row',
            dataType: 'html',
            data: {
                product_id: product_id,
                row_count: row_count,
                variation_id: variation_id,
                location_id: location_id
            },
            success: function (result) {

                $(result)
                    .find('.purchase_quantity')
                    .each(function () {
                        row = $(this).closest('tr');

                        $('#purchase_entry_table tbody').append(
                            update_purchase_entry_row_values(row)
                        );
                        update_row_price_for_exchange_rate(row);

                        update_inline_profit_percentage(row);

                        update_table_total();
                        update_grand_total();
                        update_table_sr_number();

                        //Check if multipler is present then multiply it when a new row is added.
                        if (__getUnitMultiplier(row) > 1) {
                            row.find('select.sub_unit').trigger('change');
                        }
                    });
                if ($(result).find('.purchase_quantity').length) {
                    $('#row_count').val(
                        $(result).find('.purchase_quantity').length + parseInt(row_count)
                    );
                }
            },
        });
    }
}

function update_purchase_entry_row_values(row) {
    if (typeof row != 'undefined') {
        var quantity = __read_number(row.find('.purchase_quantity'), true);
        var unit_cost_price = __read_number(row.find('.purchase_unit_cost'), true);
        var row_subtotal_before_tax = quantity * unit_cost_price;

        var tax_rate = parseFloat(
            $('option:selected', row.find('.purchase_line_tax_id')).attr('data-tax_amount')
        );

        var unit_product_tax = __calculate_amount('percentage', tax_rate, unit_cost_price);

        var unit_cost_price_after_tax = unit_cost_price + unit_product_tax;
        var row_subtotal_after_tax = quantity * unit_cost_price_after_tax;

        row.find('.row_subtotal_before_tax').text(
            __currency_trans_from_en(row_subtotal_before_tax, false, true)
        );
        __write_number(row.find('.row_subtotal_before_tax_hidden'), row_subtotal_before_tax, true);
        __write_number(row.find('.purchase_product_unit_tax'), unit_product_tax, true);
        row.find('.purchase_product_unit_tax_text').text(
            __currency_trans_from_en(unit_product_tax, false, true)
        );
        row.find('.purchase_unit_cost_after_tax').text(
            __currency_trans_from_en(unit_cost_price_after_tax, true)
        );
        row.find('.row_subtotal_after_tax').text(
            __currency_trans_from_en(row_subtotal_after_tax, false, true)
        );
        __write_number(row.find('.row_subtotal_after_tax_hidden'), row_subtotal_after_tax, true);

        row.find('.expiry_datepicker').each(function () {
            $(this).datepicker({
                autoclose: true,
                format: datepicker_date_format,
            });
        });
        return row;
    }
}

function update_row_price_for_exchange_rate(row) {
    var exchange_rate = $('input#exchange_rate').val();

    if (exchange_rate == 1) {
        return true;
    }

    var purchase_unit_cost_without_discount =
        __read_number(row.find('.purchase_unit_cost_without_discount'), true) / exchange_rate;
    __write_number(
        row.find('.purchase_unit_cost_without_discount'),
        purchase_unit_cost_without_discount,
        true
    );

    var purchase_unit_cost = __read_number(row.find('.purchase_unit_cost'), true) / exchange_rate;
    __write_number(row.find('.purchase_unit_cost'), purchase_unit_cost, true);

    var row_subtotal_before_tax_hidden =
        __read_number(row.find('.row_subtotal_before_tax_hidden'), true) / exchange_rate;
    row.find('.row_subtotal_before_tax').text(
        __currency_trans_from_en(row_subtotal_before_tax_hidden, false, true)
    );
    __write_number(
        row.find('input.row_subtotal_before_tax_hidden'),
        row_subtotal_before_tax_hidden,
        true
    );

    var purchase_product_unit_tax =
        __read_number(row.find('.purchase_product_unit_tax'), true) / exchange_rate;
    __write_number(row.find('input.purchase_product_unit_tax'), purchase_product_unit_tax, true);
    row.find('.purchase_product_unit_tax_text').text(
        __currency_trans_from_en(purchase_product_unit_tax, false, true)
    );

    var purchase_unit_cost_after_tax =
        __read_number(row.find('.purchase_unit_cost_after_tax'), true) / exchange_rate;
    __write_number(
        row.find('input.purchase_unit_cost_after_tax'),
        purchase_unit_cost_after_tax,
        true
    );

    var row_subtotal_after_tax_hidden =
        __read_number(row.find('.row_subtotal_after_tax_hidden'), true) / exchange_rate;
    __write_number(
        row.find('input.row_subtotal_after_tax_hidden'),
        row_subtotal_after_tax_hidden,
        true
    );
    row.find('.row_subtotal_after_tax').text(
        __currency_trans_from_en(row_subtotal_after_tax_hidden, false, true)
    );
}

function iraqi_dinnar_selling_price_adjustment(row) {
    var default_sell_price = __read_number(row.find('input.default_sell_price'), true);

    //Adjsustment
    var remaining = default_sell_price % 250;
    if (remaining >= 125) {
        default_sell_price += 250 - remaining;
    } else {
        default_sell_price -= remaining;
    }

    __write_number(row.find('input.default_sell_price'), default_sell_price, true);

    update_inline_profit_percentage(row);
}

function update_inline_profit_percentage(row) {
    //Update Profit percentage
    var default_sell_price = __read_number(row.find('input.default_sell_price'), true);
    var exchange_rate = $('input#exchange_rate').val();
    default_sell_price_in_base_currency = default_sell_price / parseFloat(exchange_rate);

    var purchase_after_tax = __read_number(row.find('input.purchase_unit_cost_after_tax'), true);
    var profit_percent = __get_rate(purchase_after_tax, default_sell_price_in_base_currency);
    __write_number(row.find('input.profit_percent'), profit_percent, true);
}

function update_table_total() {
    var total_quantity = 0;
    var total_st_before_tax = 0;
    var total_subtotal = 0;

    $('#purchase_entry_table tbody')
        .find('tr')
        .each(function () {

            total_quantity += __read_number($(this).find('.purchase_quantity'), true);
            total_st_before_tax += __read_number(
                $(this).find('.row_subtotal_before_tax_hidden'),
                true
            );

            console.log(total_st_before_tax)
            total_subtotal += __read_number($(this).find('.row_subtotal_after_tax_hidden'), true);
            // total_subtotal += total_st_before_tax
            // alert(total_subtotal)
        });

    $('#total_quantity').text(__number_f(total_quantity, false));
    $('#total_st_before_tax').text(__currency_trans_from_en(total_st_before_tax, true, true));
    __write_number($('input#st_before_tax_input'), total_st_before_tax, true);

    $('#total_subtotal').text(__currency_trans_from_en(total_subtotal, true, true));
    __write_number($('input#total_subtotal_input'), total_st_before_tax, true);
}

function update_grand_total() {
    var st_before_tax = __read_number($('input#st_before_tax_input'), true);
    var total_subtotal = __read_number($('input#total_subtotal_input'), true);

    //Calculate Discount
    var discount_type = $('select#discount_type').val();
    var discount_amount = __read_number($('input#discount_amount'), true);
    var discount = __calculate_amount(discount_type, discount_amount, total_subtotal);
    $('#discount_calculated_amount').text(__currency_trans_from_en(discount, true, true));

    //Calculate Tax
    var tax_rate = parseFloat($('option:selected', $('#tax_id')).data('tax_amount'));
    var tax = __calculate_amount('percentage', tax_rate, total_subtotal - discount);
    __write_number($('input#tax_amount'), tax);
    $('#tax_calculated_amount').text(__currency_trans_from_en(tax, true, true));

    //Calculate shipping
    var shipping_charges = __read_number($('input#shipping_charges'), true);

    //Calculate Final total
    grand_total = total_subtotal - discount + tax + shipping_charges;

    __write_number($('input#grand_total_hidden'), grand_total, true);

    var payment = __read_number($('input.payment-amount'), true);

    var due = grand_total - payment;
    // __write_number($('input.payment-amount'), grand_total, true);

    $('#grand_total').text(__currency_trans_from_en(grand_total, true, true));

    $('#payment_due').text(__currency_trans_from_en(due, true, true));

    //__currency_convert_recursively($(document));
}
$(document).on('change', 'input.payment-amount', function () {
    var payment = __read_number($(this), true);
    var grand_total = __read_number($('input#grand_total_hidden'), true);
    var bal = grand_total - payment;
    $('#payment_due').text(__currency_trans_from_en(bal, true, true));
});

function update_table_sr_number() {
    var sr_number = 1;
    $('table#purchase_entry_table tbody')
        .find('.sr_number')
        .each(function () {
            $(this).text(sr_number);
            sr_number++;
        });
}

$(document).on('click', 'button#submit_purchase_form', function (e) {
    e.preventDefault();

    //Check if product is present or not.
    if ($('table#purchase_entry_table tbody tr').length <= 0) {
        toastr.warning(LANG.no_products_added);
        $('input#search_product').select();
        return false;
    }

    $('form#add_purchase_form').validate({
        rules: {
            ref_no: {
                remote: {
                    url: '/purchases/check_ref_number',
                    type: 'post',
                    data: {
                        ref_no: function () {
                            return $('#ref_no').val();
                        },
                        contact_id: function () {
                            return $('#supplier_id').val();
                        },
                        purchase_id: function () {
                            if ($('#purchase_id').length > 0) {
                                return $('#purchase_id').val();
                            } else {
                                return '';
                            }
                        },
                    },
                },
            },
        },
        messages: {
            ref_no: {
                remote: LANG.ref_no_already_exists,
            },
        },
    });

    if ($('form#add_purchase_form').valid()) {
        $('#faturas').val(JSON.stringify(faturas))
        $('form#add_purchase_form').submit();
    }
});

function toggle_search() {
    if ($('#location_id').val()) {
        $('#search_product').removeAttr('disabled');
        $('#search_product').focus();
    } else {
        $('#search_product').attr('disabled', true);
    }
}

$(document).on('change', '#location_id', function () {
    toggle_search();
    $('#purchase_entry_table tbody').html('');
    update_table_total();
    update_grand_total();
    update_table_sr_number();
});

$(document).on('shown.bs.modal', '.quick_add_product_modal', function () {
    var selected_location = $('#location_id').val();
    if (selected_location) {
        $('.quick_add_product_modal').find('#product_locations').val([selected_location]).trigger("change");
    }
});

function intervaloClick(id) {
    $('#intervalo_' + id).removeAttr('disabled')
}

function diasClick(id) {
    $('#intervalo_' + id).val('')
    $('#intervalo_' + id).attr('disabled', true)
}

//para boleto
var FATURA = []
$('#gerar-fatura').click(() => {
    $('.payment-vencimento').removeAttr('required')
    let row_index = $('#payment_row_index').val() - 1;
    let qtdParcelas = $('#qtd_parcelas_' + row_index).val()
    let intervalo = $('#intervalo_' + row_index).val()
    let dataBase = $('#data_base_' + row_index).val()
    let porDias = $('#boleto_check_dias_' + row_index).is(':checked')
    let porIntervalo = $('#boleto_check_intervalo_' + row_index).is(':checked')

    console.log(row_index)
    console.log(qtdParcelas)
    if (qtdParcelas == "") {
        swal("Erro", "Informe a quantidade de parcelas", "error")
    } else if (porDias && dataBase == "") {
        swal("Erro", "Informe uma data base", "error")
    } else if (porIntervalo && dataBase == "") {
        swal("Erro", "Informe uma data base", "error")
    } else if (porIntervalo && intervalo == "") {
        swal("Erro", "Informe uma intervalo", "error")
    } else if (porIntervalo && intervalo == "") {
        swal("Erro", "Informe uma data base", "error")
    } else {
        //inserir
        if (porDias) {
            gerarPorDias(qtdParcelas, dataBase, (res) => {
                FATURA = res
                gerarHtmlTabela(res)
                $('#json_boleto').val(JSON.stringify(FATURA))
            })
        } else {
            gerarPorIntervalo(qtdParcelas, dataBase, intervalo, (res) => {
                FATURA = res
                gerarHtmlTabela(res)
                $('#json_boleto').val(JSON.stringify(FATURA))
            })
        }
    }
})

function gerarHtmlTabela() {
    let html = ''
    FATURA.map((f) => {
        html += '<tr>'
        html += '<td>' + f.vencimento + '</td>'
        html += '<td>' + f.doc + '</td>'
        html += '<td>' + formatReal(f.valor) + '</td>'
        html += '<td><button onclick="editLineBoleto(' + f.id + ')" type="button" class="btn btn-info">'
        html += '<i class="fa fa-edit"></i>'
        html += '</button></td>'
        html += '</tr>'
    })
    $('#tbl_fatura tbody').html(html)
}

function gerarPorDias(qtdParcelas, dataBase, call) {
    let map = [];
    let dia = dataBase.substring(0, 2)
    let mes = dataBase.substring(3, 5)
    let ano = dataBase.substring(6, 10)
    let total_payable = __read_number($('input#final_total_input'));

    let valorDaParcela = total_payable / qtdParcelas;
    valorDaParcela = valorDaParcela.toFixed(2)
    valorDaParcela = parseFloat(valorDaParcela)

    let somaParcelas = 0;
    let fatura = [];
    for (let i = 0; i < qtdParcelas; i++) {

        mes = parseInt(mes)
        if (mes == 12) {
            mes = 1;
            ano++;
        } else mes++;

        mes = mes < 10 ? "0" + mes : mes
        let novaData = dia + "/" + mes + "/" + ano

        if (i < qtdParcelas - 1) {
            let js = {
                id: i,
                vencimento: novaData,
                valor: valorDaParcela,
                doc: '000/' + (i + 1)
            }
            fatura.push(js)
        } else {
            let v = total_payable - somaParcelas
            v = v.toFixed(2)
            v = parseFloat(v)
            let js = {
                id: i,
                vencimento: novaData,
                valor: v,
                doc: '000/' + (i + 1)
            }
            fatura.push(js)
        }

        somaParcelas += valorDaParcela


    }
    call(fatura)
}

function gerarPorIntervalo(qtdParcelas, dataBase, intervalo, call) {
    let map = [];
    let dia = dataBase.substring(0, 2)
    let mes = dataBase.substring(3, 5)
    let ano = dataBase.substring(6, 10)
    let total_payable = __read_number($('input#final_total_input'));

    let valorDaParcela = total_payable / qtdParcelas;
    valorDaParcela = valorDaParcela.toFixed(2)
    valorDaParcela = parseFloat(valorDaParcela)

    let somaParcelas = 0;
    let fatura = [];
    for (let i = 0; i < qtdParcelas; i++) {
        console.log(ano + '-' + mes + '-' + dia + 'T00:00:00')
        let dt = new Date(ano + '-' + mes + '-' + dia + 'T00:00:00');
        dt.addDias(parseInt(intervalo))

        let diaN = dt.getDate() < 10 ? "0" + dt.getDate() : dt.getDate()
        let mesN = dt.getMonth() + 1
        mesN = mesN < 10 ? "0" + mesN : mesN

        dia = diaN
        mes = mesN
        let novaData = diaN + '/' + mesN + '/' + dt.getFullYear();
        console.log(novaData)

        if (i < qtdParcelas - 1) {
            let js = {
                id: i,
                vencimento: novaData,
                valor: valorDaParcela,
                doc: '000/' + (i + 1)
            }
            fatura.push(js)
        } else {
            let v = total_payable - somaParcelas
            v = v.toFixed(2)
            v = parseFloat(v)
            let js = {
                id: i,
                vencimento: novaData,
                valor: v,
                doc: '000/' + (i + 1)
            }
            fatura.push(js)
        }

        somaParcelas += valorDaParcela


    }
    call(fatura)
}

Date.prototype.addDias = function (dias) {
    this.setDate(this.getDate() + dias)
};

function intervaloClick(id) {
    $('#intervalo_' + id).removeAttr('disabled')
}

function diasClick(id) {
    $('#intervalo_' + id).val('')
    $('#intervalo_' + id).attr('disabled', true)
}

function formatReal(v) {
    return v.toLocaleString('pt-br', {
        style: 'currency',
        currency: 'BRL'
    });
}

function editLineBoleto(id) {
    let b = FATURA.filter((f) => {
        if (id == f.id) return f
    })
    b = b[0]
    $('#vencimento_boleto').val(b.vencimento)
    $('#valor_boleto').val(b.valor)
    $('#boleto_doc').val(b.doc)
    $('#id_doc').val(b.id)
    $('#modal_edit_line_boleto').modal('show')

}

$('#btn-save-line-bleto').click(() => {
    let id = $('#id_doc').val();
    let doc = $('#boleto_doc').val();
    let vencimento = $('#vencimento_boleto').val();
    let valor = $('#valor_boleto').val();

    valor = valor.replace(",", ".")
    valor = parseFloat(valor)

    for (let i = 0; i < FATURA.length; i++) {
        if (FATURA[i].id == id) {
            FATURA[i].vencimento = vencimento
            FATURA[i].valor = valor;
            FATURA[i].doc = doc;
        }
    }

    $('#json_boleto').val(JSON.stringify(FATURA))
    gerarHtmlTabela()
    $('#modal_edit_line_boleto').modal('hide')
    $('#modal_payment').css('overflow-y', 'auto')

})

$('#close-modal-line-boleto').click(() => {
    $('#modal_edit_line_boleto').modal('hide')
    $('#modal_payment').css('overflow-y', 'auto')

})

var faturas = []

$('#btn-add-fatura').click(() => {
    let vencimento = $('#vencimento').val()
    let valor = $('#valor_parcela').val().replace(",", ".")
    let forma_pagamento = $('#forma_pagamento').val()
    console.log(valor)
    console.log(forma_pagamento)
    if (grand_total > 0) {
        valor = parseFloat(valor)
        if (vencimento && valor && forma_pagamento) {
            somaFatura((total) => {
                console.log(total)
                if (total + valor > grand_total) {
                    swal("Alerta", "Valor ultrapassaou!", "warning")
                } else {
                    let fat = {
                        rand: Math.floor(Math.random() * 10000),
                        vencimento: vencimento,
                        valor: valor,
                        forma_pagamento: forma_pagamento
                    }
                    faturas.push(fat)
                    montaTabelaFatura()
                }
            })
        } else {
            swal("Alerta", "Informe os campos corretamente", "warning")
        }
    } else {
        swal("Alerta", "Adicione produtos a compra!", "warning")
    }
})

function somaFatura(call) {
    let total = 0
    faturas.map((rs) => {
        total += rs.valor
    })
    call(parseFloat(total))
}

function montaTabelaFatura() {
    let html = ''
    faturas.map((rs) => {
        html += '<tr>'
        html += '<td>' + rs.vencimento + '</td>'
        html += '<td>' + rs.valor.toFixed(2).replace(".", ",") + '</td>'
        html += '<td>' + getFormaPagamaento(rs.forma_pagamento) + '</td>'
        html += '<td><button type="button" onclick="removeFat(' + rs.rand + ')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>'
        html += '</tr>'
    })

    $('#tbl-fatura tbody').html(html)
}

function getFormaPagamaento(forma_pagamento) {
    if (forma_pagamento == 'cash')
        return "Dinheiro"
    else if (forma_pagamento == 'card')
        return "Cartão de crédito"
    else if (forma_pagamento == 'debit')
        return "Cartão de débito"
    else if (forma_pagamento == 'cheque')
        return "Cheque"
    else if (forma_pagamento == 'bank_transfer')
        return "Transferência bancária"
    else if (forma_pagamento == 'boleto')
        return "Boleto"
    else if (forma_pagamento == 'pix')
        return "Pix"
    else
        return "Outros"
}

function removeFat(rand) {
    let temp = faturas.filter((x) => {
        return x.rand != rand
    })
    faturas = temp
    setTimeout(() => {
        montaTabelaFatura()
    }, 300)
}

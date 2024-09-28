<script type="text/javascript">
    $(document).ready(function() {


        function getTaxonomiesIndexPage() {
            var data = {
                category_type: $('#category_type').val()
            };
            $.ajax({
                method: "GET",
                dataType: "html",
                url: '/taxonomies-ajax-index-page',
                data: data,
                async: false,
                success: function(result) {
                    $('.taxonomy_body').html(result);
                }
            });
        }

        function initializeTaxonomyDataTable() {
            //Category table
            if ($('#category_table').length) {
                var category_type = $('#category_type').val();
                category_table = $('#category_table').DataTable({
                    processing: true,
                    serverSide: true,
                    dom: 'tip',
                    ajax: '/taxonomies?type=' + category_type,
                    columns: [{
                            data: 'image',
                            name: 'image'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    initComplete: function() {
                        $('.dataTables_paginate').css('margin-top', '15px');
                        var api = this.api();
                        var filterableColumns = [ 1, 2];
                        $('#category_table thead').append('<tr class="filter-row"></tr>');
                        api.columns().every(function(index) {
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
                                    .on('keyup change', function() {
                                        if (column.search() !== this.value) {
                                            console.log(this.value);
                                            column.search(this.value).draw();
                                        }
                                    });
                            }
                        });
                    }
                });
            }
        }

        @if (empty(request()->get('type')))
            getTaxonomiesIndexPage();
        @endif

        initializeTaxonomyDataTable();
    });
    // $(document).on('submit', 'form#category_add_form', function(e) {
    //     e.preventDefault();
    //     $(this)
    //     .find('button[type="submit"]')
    //     .attr('disabled', true);
    //     var data = $(this).serialize();

    //     $.ajax({
    //         method: 'POST',
    //         url: $(this).attr('action'),
    //         dataType: 'json',
    //         data: data,
    //         success: function(result) {
    //             if (result.success === true) {
    //                 $('div.category_modal').modal('hide');
    //                 toastr.success(result.msg);
    //                 category_table.ajax.reload();
    //             } else {
    //                 toastr.error(result.msg);
    //             }
    //         },
    //     });
    // });
    $(document).on('click', 'button.edit_category_button', function() {
        $('div.category_modal').load($(this).data('href'), function() {
            $(this).modal('show');

            // $('form#category_edit_form').submit(function(e) {
            //     e.preventDefault();
            //     $(this)
            //     .find('button[type="submit"]')
            //     .attr('disabled', true);
            //     var data = $(this).serialize();

            //     $.ajax({
            //         method: 'POST',
            //         url: $(this).attr('action'),
            //         dataType: 'json',
            //         data: data,
            //         success: function(result) {
            //             if (result.success === true) {
            //                 $('div.category_modal').modal('hide');
            //                 toastr.success(result.msg);
            //                 category_table.ajax.reload();
            //             } else {
            //                 toastr.error(result.msg);
            //             }
            //         },
            //     });
            // });
        });
    });

    $(document).on('click', 'button.delete_category_button', function() {
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
                    success: function(result) {
                        if (result.success === true) {
                            toastr.success(result.msg);
                            category_table.ajax.reload();
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    function md(img) {
        setTimeout(() => {
            var img_fileinput_setting = {
                showUpload: false,
                showPreview: true,
                browseLabel: LANG.file_browse_label,
                removeLabel: LANG.remove,
                initialPreview: '/uploads/img/categorias/' + img,
                initialPreviewAsData: true,
                previewSettings: {
                    image: {
                        width: '150px',
                        height: '150px',
                        'max-width': '100%',
                        'max-height': '100%'
                    },
                },
            };
            $('#upload_image').fileinput(img_fileinput_setting);

        }, 1500)
    }

    $('.btn-modal').click(() => {

        setTimeout(() => {
            var img_fileinput_setting = {
                showUpload: false,
                showPreview: true,
                browseLabel: LANG.file_browse_label,
                removeLabel: LANG.remove,
                previewSettings: {
                    image: {
                        width: '150px',
                        height: '150px',
                        'max-width': '100%',
                        'max-height': '100%'
                    },
                },
            };
            $('#upload_image').fileinput(img_fileinput_setting);

        }, 1500)
    })
</script>

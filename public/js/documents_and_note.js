//This file contains all code related to document & note
function getRubros() {
    var employee_id = $('#employee_id').val();
    $.ajax({
        method: "GET",
        dataType: "html",
        url: '/get-rubros-employee-page',
        async: false,
        data: {'employee_id' : employee_id},
        success: function(result){
            $('.document_note_body').html(result);
        }
    });
}

$(document).on('click', '.docs_and_notes_btn', function() {
    var url  = $(this).data('href');
    $.ajax({
        method: "GET",
        dataType: "html",
        url: url,
        success: function(result){
            $('.docus_note_modal').html(result).modal("show");
        }
    });
});

// initialize ck editor & dropzone on docs & notes model open
var dropzoneForDocsAndNotes = {};
$(document).on('shown.bs.modal','.docus_note_modal', function (e) {
    tinymce.init({
        selector: 'textarea#docs_note_description',
    });
    $('form#docus_notes_form').validate();
    initialize_dropzone_for_docus_n_notes();
});

// function initializing dropzone for docs & notes
function initialize_dropzone_for_docus_n_notes() {
    var file_names = [];

    if (dropzoneForDocsAndNotes.length > 0) {
        Dropzone.forElement("div#docusUpload").destroy();
    }

    dropzoneForDocsAndNotes = $("div#docusUpload").dropzone({
        url: '/post-document-upload',
        paramName: 'file',
        uploadMultiple: true,
        autoProcessQueue: true,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(file, response) {
            if (response.success) {
                toastr.success(response.msg);
                file_names.push(response.file_name);
                $('input#docus_notes_media').val(file_names);
            } else {
                toastr.error(response.msg);
            }
        },
    });
}

//form submittion of docs & notes form
$(document).on('submit', 'form#rubros_employee_form', function(e){
    e.preventDefault();
    var url = $('form#rubros_employee_form').attr('action');
    var method = $('form#rubros_employee_form').attr('method');
    var data = $('form#rubros_employee_form').serialize();
   
    $.ajax({
        method: method,
        dataType: "json",
        url: url,
        data:data,
        success: function(result){
            if (result.success) {
                $('.docus_note_modal').modal('hide');
                toastr.success(result.msg);
                documents_and_notes_data_table.ajax.reload();
            } else {
                toastr.error(result.msg);
            }
        }
    });
});

// on close of docs & notes form destroy dropzone
$(document).on('hide.bs.modal','.docus_note_modal', function (e) {
    tinymce.remove("textarea#docs_note_description");
    if (dropzoneForDocsAndNotes.length > 0) {
        Dropzone.forElement("div#docusUpload").destroy();
        dropzoneForDocsAndNotes = {};
    }
});

// delete a docs & note
$(document).on('click', '#delete_docus_note', function(e) {
    e.preventDefault();
    var url = $(this).data('href');
    swal({
        title: "Deseas eliminar este rubro?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((confirmed) => {
        if (confirmed) {
            $.ajax({
                method:'post',
                dataType: 'json',
                url: url,
                success: function(result){
                    if (result.success) {
                        toastr.success(result.msg);
                        documents_and_notes_data_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });
});

// view docs & note
$(document).on('click', '.view_a_docs_note', function() {
    var url  = $(this).data('href');
    $.ajax({
        method: "GET",
        dataType: "html",
        url: url,
        success: function(result){
            $('.view_modal').html(result).modal("show");
        }
    });
});

function initializeDocumentAndNoteDataTable() {

    documents_and_notes_data_table = $('#documents_and_notes_table').DataTable({
            processing: true,
            serverSide: true,
            ajax:{
                url: '/rubros-employee',
                data: function(d) {
                    d.employee_id = $('#employee_id').val();
                }
            },
            columnDefs: [
                {
                    targets: [0, 2, 3],
                    orderable: false,
                    searchable: false,
                },
            ],
            aaSorting: [[3, 'asc']],
            columns: [
                { data: 'action', name: 'action' },
                { data: 'tipo', name: 'tipo' },
                { data: 'name', name: 'name' },
                { data: 'valor', name: 'valor' },
                { data: 'status', name: 'status' }
            ]
        });
}
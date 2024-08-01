<div class="table-responsive">

    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-primary docs_and_notes_btn pull-right"
            data-href="{{ action('DocumentAndNoteController@create', ['employee_id' => $employee_id]) }}">
            @lang('messages.add')&nbsp;
            <i class="fa fa-plus"></i>
        </button>
    </div> <br><br>
    <table class="table table-bordered table-striped" style="width: 100%;" id="documents_and_notes_table">
        <thead>
            <tr>
                <th>@lang('messages.action')</th>
                <th>@lang('Tipo')</th>
                <th>@lang('Rubro')</th>
                <th>@lang('Valor')</th>
                <th>@lang('Estado')</th>
            </tr>
        </thead>
    </table>
</div>
<div class="modal fade docus_note_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>

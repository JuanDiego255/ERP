<div class="table-responsive">

    <div class="pull-right">
        <button type="button" class="btn btn-sm btn-primary docs_and_notes_btn pull-right"
            data-href="<?php echo e(action('DocumentAndNoteController@create', ['employee_id' => $employee_id]), false); ?>">
            <?php echo app('translator')->get('messages.add'); ?>&nbsp;
            <i class="fa fa-plus"></i>
        </button>
    </div> <br><br>
    <table class="table table-bordered table-striped" style="width: 100%;" id="documents_and_notes_table">
        <thead>
            <tr>
                <th><?php echo app('translator')->get('messages.action'); ?></th>
                <th><?php echo app('translator')->get('Tipo'); ?></th>
                <th><?php echo app('translator')->get('Rubro'); ?></th>
                <th><?php echo app('translator')->get('Valor'); ?></th>
                <th><?php echo app('translator')->get('Estado'); ?></th>
            </tr>
        </thead>
    </table>
</div>
<div class="modal fade docus_note_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
<?php /**PATH C:\xampp\htdocs\StoreWeb\resources\views/admin/rubros/tab_rubros/index.blade.php ENDPATH**/ ?>
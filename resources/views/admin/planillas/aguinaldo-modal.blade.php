<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Aguinaldo</h4>
        </div>

        <div class="modal-body">
            @component('components.widget', ['title' => __('Complete la información para calcular el aguinaldo')])
            @endcomponent
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary calcular" id="calcular">@lang('Calcular')</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

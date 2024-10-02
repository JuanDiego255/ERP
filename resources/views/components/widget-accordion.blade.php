<div class="box @if (!empty($class)) {{ $class }} @else box-primary @endif" id="accordion">
    <div class="box-header with-border" style="cursor: pointer;">
        @if (empty($header))
            @if (!empty($title) || !empty($tool))
                {!! $icon ?? '' !!}
                <h3 class="box-title"><a data-toggle="collapse" data-parent="#accordion"
                        href="#collapseFilter{{ $id ?? 'default' }}">
                        <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        {{ $title ?? '' }}
                    </a></h3>
                {!! $tool ?? '' !!}
            @endif
        @else
            {!! $header !!}
        @endif
    </div>
    <div id="collapseFilter{{ $id ?? 'default' }}"
        class="panel-collapse active collapse @if (!empty($id) && $id === "accordionPagos") in @endif" aria-expanded="true">
        <div class="box-body">
            {{ $slot }}
        </div>
    </div>
</div>

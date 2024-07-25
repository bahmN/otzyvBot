@if ($data->hasPages())

<div class="paginator">
    @if ($data->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $data->previousPageUrl() }}&chat_id={{$chatId}}&tab_numb={{$tab}}">
        < </a>
            @endif

            <?php
            // config
            $link_limit = 6; // maximum number of links (a little bit inaccurate, but will be ok for now)
            ?>

            @if ($data->lastPage() > 1)
            @for ($i = 1; $i <= $data->lastPage(); $i++)
                <?php
                $half_total_links = floor($link_limit / 2);
                $from = $data->currentPage() - $half_total_links;
                $to = $data->currentPage() + $half_total_links;
                if ($data->currentPage() < $half_total_links) {
                    $to += $half_total_links - $data->currentPage();
                }
                if ($data->lastPage() - $data->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($data->lastPage() - $data->currentPage()) - 1;
                }
                ?>
                @if ($from < $i && $i < $to) <a href="{{ $data->url($i) }}&chat_id={{$chatId}}&tab_numb={{$tab}}" class="{{ ($data->currentPage() == $i) ? 'cursor__active' : 'cursor' }}">{{ $i }}
    </a>
    @endif
    @endfor
    @endif

    {{-- Next Page Link --}}
    @if ($data->hasMorePages())

    <a class="cursor" href="{{ $data->nextPageUrl() }}&chat_id={{$chatId}}&tab_numb={{$tab}}">></a>

    @else
    <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
        <span aria-hidden="true">></span>
    </div>
    @endif
</div>

@endif
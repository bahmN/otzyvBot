@if ($reviews2->hasPages())

<div class="paginator">
    @if ($reviews2->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $reviews2->previousPageUrl() }}&chat_id={{$chatId}}&tab_numb=2">
        < </a>
            @endif

            <?php
            // config
            $link_limit = 6; // maximum number of links (a little bit inaccurate, but will be ok for now)
            ?>

            @if ($reviews2->lastPage() > 1)
            @for ($i = 1; $i <= $reviews2->lastPage(); $i++)
                <?php
                $half_total_links = floor($link_limit / 2);
                $from = $reviews2->currentPage() - $half_total_links;
                $to = $reviews2->currentPage() + $half_total_links;
                if ($reviews2->currentPage() < $half_total_links) {
                    $to += $half_total_links - $reviews2->currentPage();
                }
                if ($reviews2->lastPage() - $reviews2->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($reviews2->lastPage() - $reviews2->currentPage()) - 1;
                }
                ?>
                @if ($from < $i && $i < $to) <a href="{{ $reviews2->url($i) }}&chat_id={{$chatId}}&tab_numb=2" class="{{ ($reviews2->currentPage() == $i) ? 'cursor__active' : 'cursor' }}">{{ $i }}
    </a>
    @endif
    @endfor
    @endif

    {{-- Next Page Link --}}
    @if ($reviews2->hasMorePages())

    <a class="cursor" href="{{ $reviews2->nextPageUrl() }}&chat_id={{$chatId}}&chat_id={{$chatId}}&tab_numb=2">></a>

    @else
    <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
        <span aria-hidden="true">></span>
    </div>
    @endif
</div>
@endif
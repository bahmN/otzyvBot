@if ($reviews->hasPages())

<div class="paginator">
    @if ($reviews->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $reviews->previousPageUrl() }}&chat_id={{$chatId}}">
        < </a>
            @endif

            {{-- Next Page Link --}}
            @if ($reviews->hasMorePages())

            <a class="cursor" href="{{ $reviews->nextPageUrl() }}&chat_id={{$chatId}}">></a>

            @else
            <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">></span>
            </div>
            @endif
</div>

@endif
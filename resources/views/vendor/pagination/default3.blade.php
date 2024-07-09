@if ($users2->hasPages())

<div class="paginator">
    @if ($users2->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $users2->previousPageUrl() }}&chat_id={{$chatId}}">
        < </a>
            @endif

            {{-- Next Page Link --}}
            @if ($users2->hasMorePages())

            <a class="cursor" href="{{ $users2->nextPageUrl() }}&chat_id={{$chatId}}">></a>

            @else
            <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">></span>
            </div>
            @endif
</div>
@else

@endif
@if ($users->hasPages())

<div class="paginator">
    @if ($users->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $users->previousPageUrl() }}&chat_id={{$chatId}}">
        < </a>
            @endif

            {{-- Next Page Link --}}
            @if ($users->hasMorePages())

            <a class="cursor" href="{{ $users->nextPageUrl() }}&chat_id={{$chatId}}">></a>

            @else
            <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">></span>
            </div>
            @endif
</div>
@else

@endif
@if ($reviews->hasPages())

<div class="paginator">
    @if ($reviews->onFirstPage())
    <div class="cursor" aria-disabled="true">
        <span aria-hidden="true">
            < </span>
    </div>
    @else
    <a class="cursor" href="{{ $reviews->previousPageUrl() }}">
        < </a>
            @endif



            {{-- Next Page Link --}}
            @if ($reviews->hasMorePages())

            <a class="cursor" href="{{ $reviews->nextPageUrl() }}">></a>

            @else
            <div class="cursor" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true">></span>
            </div>
            @endif
</div>

@endif
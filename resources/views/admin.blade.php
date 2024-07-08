@extends('main')

@section('content')
<section class="admin">

    <h1>Модерация отзывов</h1>
    @foreach($reviews as $review)
    <form class="admin__container" method="post">
        @csrf
        <input type="hidden" name="id" value="{{$review->id}}">
        <input type="hidden" name="chat_id" value="{{$review->chat_id}}">
        <h2>Имя: <span>{{$review->blogger_name}}</span></h2>
        @foreach($users as $user)
        @if($user->chat_id == $review->chat_id)
        <h2>Кто написал: <span>{{$user->name}}</span></h2>
        @endif
        @endforeach
        <h2>Отзыв: <span>{{$review->text_review}}</span></h2>
        <hr>
        <div class="buttons__section">
            <button id="reject" formaction="/admin/reject">Отклонить</button>
            <button id="approve" formaction="/admin/approve">Одобрить</button>
        </div>
    </form>
    @endforeach
    @include('vendor.pagination.default')
</section>
@endsection
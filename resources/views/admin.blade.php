@extends('main')
@section('content')

@if($isAdmin)
<div class="loader">
    <div class="loader__content"></div>
</div>
<section class="admin">
    <div class="tabs">
        @if($tabNumb==1)
        <input class="input" name="tabs" type="radio" id="tab-1" checked />
        @else
        <input class="input" name="tabs" type="radio" id="tab-1" />
        @endif
        <label class="label" for="tab-1">Модерация отзывов</label>
        <div class="panel">
            @foreach($reviews as $review)
            <form class="admin__container" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$review->id}}">
                <input type="hidden" name="chat_id" value="{{$chatId}}">
                <h2>Имя: <span>{{$review->blogger_name}}</span></h2>
                @foreach($users as $user)
                @if($user->chat_id == $review->chat_id)
                <h2>Кто написал: <a href="https://{{$user->link}}">{{$user->name}}</a></h2>
                @endif
                @endforeach
                <h2>Отзыв: <span>{{$review->text_review}}</span></h2>
                <h2>Статус модерации: <span>Не промодерирован </span></h2>
                <input type="hidden" name="id_photo" value="{{$review->id_photo}}">
                <button id="screenBtn" class="screenBtn" formaction="/admin/getScreenshot">Просмотреть скриншот с отзывом</button>
                <hr>
                <div class="buttons__section">
                    <button id="reject" formaction="/admin/reject">Отклонить</button>
                    <button id="approve" formaction="/admin/approve">Одобрить</button>
                </div>
            </form>
            @endforeach
            @include('vendor.pagination.default')
        </div>
        @if($tabNumb==2)
        <input class="input" name="tabs" type="radio" id="tab-2" checked />
        @else
        <input class="input" name="tabs" type="radio" id="tab-2" />
        @endif
        <label class="label" for="tab-2">Все отзывы</label>
        <div class="panel">
            @foreach($reviews2 as $review)
            <form class="admin__container" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$review->id}}">
                <input type="hidden" name="chat_id" value="{{$chatId}}">
                <h2>Имя: <span>{{$review->blogger_name}}</span></h2>
                @foreach($users as $user)
                @if($review->chat_id == $user->chat_id)
                <h2>Кто написал: <a href="https://{{$user->link}}">{{$user->name}}</a></h2>
                @endif
                @endforeach
                <h2>Отзыв: <span>{{$review->text_review}}</span></h2>
                <h2>Статус модерации: <span>@if($review->is_moderated == 1)Опубликован @else Удален @endif </span></h2>
                <input type="hidden" name="id_photo" value="{{$review->id_photo}}">
                <button id="screenBtn" class="screenBtn" formaction="/admin/getScreenshot">Просмотреть скриншот с отзывом</button>
                <hr>
                @if($review->is_moderated != 2)
                <div class="buttons__section">
                    <button id="reject" formaction="/admin/reject">Удалить</button>
                </div>
                @endif
            </form>
            @endforeach
            @include('vendor.pagination.default2')
        </div>
        @if($tabNumb==3)
        <input class="input" name="tabs" type="radio" id="tab-3" checked />
        @else
        <input class="input" name="tabs" type="radio" id="tab-3" />
        @endif
        <label class="label" for="tab-3">Пользователи</label>
        <div class="panel">
            @foreach($users2 as $user)
            <form class="admin__container" method="post">
                <h2>Имя: <a href="https://{{$user->link}}">{{$user->name}}</a></h2>
                <h2>Доход: <span>{{$user->income}}</span></h2>
                <h2>На сколько закупаем рекламу: <span>{{$user->cost}}</span></h2>
                <h2>Готов потратить на рекламу: <span>{{$user->deposit}}</span></h2>
                <h2>Платформа покупки рекламы: <span>{{$user->platform_name}}</span></h2>
                <h2>Количество баллов: <span>{{$user->amount_bonus}}</span></h2>
            </form>
            @endforeach
            @include('vendor.pagination.default3')
        </div>
    </div>
</section>
@else
<h1>403 Forbidden...</h1>
@endif
@endsection
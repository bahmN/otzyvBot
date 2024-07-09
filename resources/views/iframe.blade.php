@extends('main')
@section('content')

<body class="iframe_body">
    <section class=" iframe">
        <a href='/admin?chat_id={{$chat_id}}'>Назад</a>
        <iframe src="{{$link}}"></iframe>
    </section>
</body>
@endsection
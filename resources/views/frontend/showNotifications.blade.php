@extends('frontend.layout')

@section('main')

    <div class="breadCrumbBg contacts">
        <div class="container">
            <div class="row">
                <ul class="breadCrumbs col-xs-12">
                    <li><a href="/">Главная</a></li>
                    <li><a href="/{{ $page->slug }}">{{ $page->name }}</a></li>
                </ul>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="pers_area not col-xs-12">
                <div class="row">

                @if (Auth::check())
                    <form action="/notifications" method="post" class="notificationForm col-lg-4">
                        {!! csrf_field() !!}
                            <div class="formRow">
                                <label for="date"> Дата получения линз </label><br>
                                <input type="date" name="delivery_date" id="date" required>
                            </div>

                            <div class="formRow">
                                <input name="repeat" type="checkbox" id="repeat">
                                <label class="repeat" for="repeat"> Повторять напоминание </label>
                            </div>

                            <div class="formRow">
                                <label for="interval"> Интервал дней между напоминаниями(дней)</label><br>
                                <input type="number" min="1" name="interval" id="interval" placeholder="Интервал дней между напоминаниями" required>
                            </div>

                            <input type="submit" value="Создать напоминание">
                    </form>

                    <div class="title col-xs-12">Существующие напоминания</div>


                    <div class="notList col-xs-12">

                        @if(count($notification) > 0)
                            <table>
                                <tr class="thead">
                                    <th>Дата напоминания</th>
                                    <th class="hidden-xs">Повторяемое</th>
                                    <th>Повторять через</th>
                                    <th></th>
                                </tr>

                                @foreach($notification as $item)
                                    <? $delivery_date = explode(' ', $item->delivery_date); ?>

                                    <tr class="tbody">
                                        <td>{{ $delivery_date[0] }}</td>
                                        <td class="hidden-xs">{{ $item->repeat == 1 ? 'Да' : 'Нет' }}</td>
                                        <td>Через каждые {{ $item->interval }} дней</td>
                                        <td><div data-id="{{ $item->id }}" class="del"></div></td>
                                    </tr>
                               @endforeach

                            </table>
                        @else
                            Пусто
                        @endif
                    </div>

                @endif
                </div>
        </div>
    </div>
    <!-- wrapper end -->

@endsection

@section('scripts')
    <script>

        $('.del').click(function(){
            var pointer = $(this);
            var id = pointer.data('id');
            $.post( '/notifications', { _token: '{{ Session::token() }}', id: id },
                function (data) {
                    pointer.parent().parent('.tbody').fadeOut(function () {
                       $(this).remove();
                    });
                });
        });


    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="/frontend/css/contacts.css">
    <link rel="stylesheet" href="/frontend/css/user.css">
@endsection

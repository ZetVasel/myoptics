@extends('admin.layout')

@section('main')

    <h1><a href="/master/products_management" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1>

    @if(Session::has('message'))
        <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
    @endif

    {!! Form::open(['class' => 'features_add', 'action' => 'Admin\AdminCharlistController@postAdd']) !!}
    <div class="col-sm-6">
        <div class="input-group">
            <span class="input-group-addon"></span>
            {!! Form::text('name', '', array('class'=>'form-control', 'placeholder' => 'Введите название параметра') ) !!}
        </div>
    </div>
    <div class="col-sm-2">{!! Form::submit('Добавить', ['class' => 'btn btn-success']) !!}</div>
    {!! Form::close() !!}


    @if( count($charlist)>0 )
        {!! Form::open(['class'=>'features']) !!}
        <table class="table">
            <tr>
                <th style="width: 50%;">Название</th>
                <th style="text-align: right;">Управление</th>
            </tr>
            @foreach ($charlist as $post)
                <tr>
                    <td style="vertical-align: middle">
                        <input name="check[]" value="{{ $post->id }}" type="checkbox"><a class="black_link" href="/master/charlist_option_variants/show/{{ $post->id }}">{{{ $post->name }}}</a>
                    </td>
                    <td style="text-align: right;">
                        <div class="btn-group">
                            <a title="Редактировать" href="/master/charlist/edit/{{ $post->id }}" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span></a>
                            <button title="Удалить" type="button" class="delete btn btn-danger" data-id="{{ $post->id }}"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="select_form">
            <label id="check_all" class="link">Выбрать все</label>
            <select name="action" class="form-control">
                <option value="delete">удалить</option>
            </select>
            <button type="submit" style="margin-left: 20px;" class="btn btn-success">Применить</button>
        </div>
        {!! Form::close() !!}

        <!-- navigation //-->
        {!! $charlist->render() !!}

    @else
        <div class="alert alert-warning" role="alert">
            Нет записей
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $(function() {

            // удаление записи
            $('.delete').click( function() {
                $('input[type="checkbox"][name*="check"]').prop('checked', false);
                $(this).closest("tr").find('input[type="checkbox"][name*="check"]').prop('checked', true);
                $(this).closest("form").find('select[name="action"] option[value=delete]').prop('selected', true);
                $(this).closest("form").submit();
            });

            // удаление записей
            $("form.features").submit(function() {
                if($('select[name="action"]').val()=='delete' && !confirm('Подтвердите удаление'))
                    return false;
            });

            // выделить все
            $("#check_all").on( 'click', function() {
                $('input[type="checkbox"][name*="check"]').prop('checked', $('input[type="checkbox"][name*="check"]:not(:checked)').length>0 );
            });
        })
    </script>
@endsection

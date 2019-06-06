@extends('admin.layout')

@section('main')

    <h1><a href="/master/{{ Request::segment(2) }}" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>

    @if(Session::has('message'))
        <div class="alert alert-success" role="alert">{{ Session::get('message') }}</div>
    @endif

    @include('errors.formErrors')

    {!! Form::model( $post, array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}

    <div class="form-group">
        {!! Form::label('name', 'Название', array('class'=>'col-sm-2 control-label') ) !!}
        <div class="col-sm-10">
            {!! Form::text('name',  $post->name, array('class'=>'form-control') ) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
    </div>


    {!! Form::close() !!}
@stop
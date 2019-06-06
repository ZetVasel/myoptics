@extends('admin.layout')

@section('main')

    <h1><a href="/master/pointers" class="glyphicon glyphicon-circle-arrow-left"></a>{{ $title }}</h1><br>

    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">{{ Session::get('success') }}</div>
    @elseif(Session::has('error'))
        <div class="alert alert-danger" role="alert">{{ Session::get('error') }}</div>
    @endif

    @include('errors.formErrors')

    {!! Form::model( $post, array( 'class' => 'form-horizontal', 'role' => 'form', 'files' => true) ) !!}

    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_a" data-toggle="tab">Запись</a></li>
    </ul>
    <div class="tab-content">

        <div class="tab-pane active" id="tab_a">
            <div class="form-group">&nbsp;</div>

            <div class="tab-content">
                <div class="tab-pane active" id="tab_ua">
                    <div class="form-group">
                        {!! Form::label('address', 'Адрес', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('address',  $post->address, array('class'=>'form-control') ) !!}
                        </div>
                    </div>
                    <div class="form-group" >
                        {!! Form::label('latitude', 'Широта(например, 50.4145125)', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('latitude',  $post->latitude, array('class'=>'form-control') ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('longitude', 'Долгота(например, 30.5290105)', array('class'=>'col-sm-2 control-label') ) !!}
                        <div class="col-sm-10">
                            {!! Form::text('longitude',  $post->longitude, array('class'=>'form-control') ) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div><!-- tab content -->

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Сохранить</button>
        </div>
    </div>

    {!! Form::close() !!}
@stop

@section('scripts')

@endsection
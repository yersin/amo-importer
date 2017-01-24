@extends("layouts.admin")
@section("content")
    <h1>Редактировать деятельность</h1>

    {!! Form::model($activity, ["style" => "width:40%"]) !!}
        @include("admin.activities._form")
    {!! Form::close() !!}
@stop
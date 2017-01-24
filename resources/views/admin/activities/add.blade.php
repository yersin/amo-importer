@extends("layouts.admin")
@section("content")
    <h1>Добавить деятельность</h1>
    {!! Form::open(["style"=> "width:40%;"]) !!}
        @include("admin.activities._form")
    {!! Form::close() !!}
@stop
@extends("layouts.admin")
@section("content")
    <h1>Добавить конфигурации для Amocrm</h1>
    {!! Form::open(["style"=> "width:40%;"]) !!}
        @include("admin.amo._form")
    {!! Form::close() !!}
@stop
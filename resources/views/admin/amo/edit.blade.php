@extends("layouts.admin")
@section("content")
    <h1>Редактировать конфигурации Amocrm</h1>

    {!! Form::model($amo_config, ["style" => "width:40%"]) !!}
        @include("admin.amo._form")
    {!! Form::close() !!}
@stop
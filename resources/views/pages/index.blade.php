@extends("layouts.admin")
@section("content")
    @if(session("message"))
        <div class="alert alert-{{ session("message")->status }}">
            {{ session("message")->text }}
        </div>
    @endif
    <h1> Интегрировано : <span style="color:green">{{ $integrated }}</span></h1>
    <h1> Неинтегрированных фирм : <span style="color:red">{{ $not_integrated }}</span></h1>
    {!! Form::open(["style" => "width:40%;"]) !!}

        <div class="form-group">
            <label>Интегрировать (общее количество)</label>
            {!! Form::number('total', null, ["class" => "form-control"]) !!}
        </div>

        <div class="form-group">
            <label>Отправлять по (количество)</label>
            {!! Form::number('chunk', null, ["class" => "form-control"]) !!}
        </div>
        <input type="submit" class="btn btn-primary" value="Сохранить">
    {!! Form::close() !!}
@stop
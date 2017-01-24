@extends("layouts.admin")
@section("content")
    {!! Form::open(["style" => "width:40%;"]) !!}

        <div class="form-group">
            <label>Название компании</label>
            {!! Form::text('name', null, ["class" => "form-control", "required"]) !!}
        </div>

        <div class="form-group">
            <label>Адрес</label>
            {!! Form::text('address', null, ["class" => "form-control", "required"]) !!}
        </div>
        <div class="form-group">
            <label>Телефон</label>
            {!! Form::text('phone', null, ["class" => "form-control", "required"]) !!}
        </div>

        <div class="form-group">
            <label>EMAIl</label>
            {!! Form::text('email', null, ["class" => "form-control", "required"]) !!}
        </div>

        <div class="form-group">
            <label>Категория</label>
            {!! Form::text('category', null, ["class" => "form-control", "required"]) !!}
        </div>
        <div class="form-group">
            <label>Подкатегория</label>
            {!! Form::text('sub_category', null, ["class" => "form-control", "required"]) !!}
        </div>

        <input type="submit" class="btn btn-primary" value="Сохранить">
    {!! Form::close() !!}
@stop
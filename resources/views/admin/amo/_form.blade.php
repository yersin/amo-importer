
<div class="form-group">
    <label>Название </label>
    {!! Form::text('name', null, ["class" => "form-control", "required"]) !!}
</div>
<div class="form-group">
    <label>Поддомен</label>
    {!! Form::text('subdomain', null, ["class" => "form-control", "required"]) !!}
</div>
<div class="form-group">
    <label>Логин</label>
    {!! Form::text('login', null, ["class" => "form-control", "required"]) !!}
</div>
<div class="form-group">
    <label>HASH</label>
    {!! Form::text('hash', null, ["class" => "form-control", "required"]) !!}
</div>

<input type="submit" class="btn btn-primary" value="Сохранить">
<a href="/admin/amo-configs" class="btn btn-danger">Отмена</a>
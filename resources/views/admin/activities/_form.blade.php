
<div class="form-group">
    <label>Название деятельнсти</label>
    {!! Form::text('name', null, ["class" => "form-control", "required"]) !!}
</div>

<input type="submit" class="btn btn-primary" value="Сохранить">
<a href="/admin/activities" class="btn btn-danger">Отмена</a>
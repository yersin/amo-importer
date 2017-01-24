@extends("layouts.admin")
@section("content")
    <div class="container-fluid">
        <a href="/admin/activities/add" class="btn btn-primary">Добавить</a>
        <hr>
        @if(session("message"))
            <div class="alert alert-{{ session("message")->status }}">
                {{ session("message")->text }}
            </div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Название деятельности</th>
                <th>Действия</th>
            </tr>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->id }}</td>
                    <td>{{ $activity->name }}</td>
                    <td>
                        <a href="/admin/activities/edit/{{ $activity->id }}" class="btn btn-primary">Изменить</a>
                        <a href="/admin/activities/remove/{{ $activity->id }}" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
@extends("layouts.admin")
@section("content")
    <div class="container-fluid">
        <a href="/admin/amo-configs/add" class="btn btn-primary">Добавить</a>
        <hr>
        @if(session("message"))
            <div class="alert alert-{{ session("message")->status }}">
                {{ session("message")->text }}
            </div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <th>Название </th>
                <th>Поддомен</th>
                <th>Логин</th>
                <th>HASH</th>
                <th>Действия</th>
            </tr>
            @foreach($amo_configs as $amo_config)
                <tr>
                    <td>{{ $amo_config->id }}</td>
                    <td>{{ $amo_config->name }}</td>
                    <td>{{ $amo_config->subdomain }}</td>
                    <td>{{ $amo_config->login }}</td>
                    <td>{{ $amo_config->hash }}</td>
                    <td>
                        <a href="/admin/amo-configs/edit/{{ $amo_config->id }}" class="btn btn-primary">Изменить</a>
                        <a href="/admin/amo-configs/remove/{{ $amo_config->id }}" class="btn btn-danger">Удалить</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
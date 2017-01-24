@extends("layouts.admin")
@section("content")
    <div>
        <div class="alert alert-success" style="display:none;">
            Рубрика успешно изменена
        </div>
    </div>
    <table class="table table-bordered table-striped table-hover">
        <tr>
            <th>#</th>
            <th>Категория</th>
            <th>Рубрика</th>
            <th>Деятельность</th>
        </tr>
        <tr>
        @foreach($rubrics as $key => $rubric)
            <tr data-id="{{ $rubric->rubric_id }}">
                <td>{{ $key+1 }}</td>
                <td>{{ $rubric->title }}</td>
                <td>{{ $rubric->groupTitle }}</td>
                <td>
                    {!! Form::select('activity_id', $acivities, $rubric->activity_id, ["class" => "activity form-control"]) !!}
                </td>
            </tr>
            @endforeach
    </table>
    <hr>
    {!! $rubrics->render() !!}
    <script>
        $(".activity").on("change", function () {
            var activity_id = $(this).val();
            var id = $(this).closest("tr").data("id");
            var params = {id:id, activity_id: activity_id, _token: "{{ csrf_token() }}"};
            changeRubric(params);
        });

        function changeRubric( params){
            $.ajax({
                url: "/admin",
                method: 'POST',
                data: params,
                success: function(data){
                    generate('success', "Рубрика успешно изменена");
                },
                error: function(data){
                    generate('error', "Произошла непредвиденная ошибка");
                },
            });
        }

        function generate(type, text) {
            var n = noty({
                text        : text,
                type        : type,
                dismissQueue: true,
                timeout     : 500,
                closeWith   : ['click'],
                layout      : 'topCenter',
                maxVisible  : 10
            });
        }
    </script>
@stop
@extends("layouts.admin")
@section("content")
    <div class="alert alert-success" style="display:none;">
        Рубрика успешно изменена
    </div>
    <input type="text" class="form-control " style="width:20%;" placeholder="🔍 Поиск" id="search" data-type="activities">
    <hr>
    <div class="rubric-table">
        @include("admin.inc.rubric_search_table", ["type" => "activities"])
    </div>
    <hr>
    {!! $rubrics->render() !!}
    <script>
        $("body").on("change", ".activity", function () {
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
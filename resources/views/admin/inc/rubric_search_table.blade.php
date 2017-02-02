<table class="table table-bordered table-striped table-hover">
    <tr>
        <th>#</th>
        <th>Категория</th>
        <th>Рубрика</th>
        @if($type == "activities")
            <th>Деятельность</th>
        @else
            <th>Нужные/Ненужные</th>
        @endif
    </tr>
    <tr>
    @foreach($rubrics as $key => $rubric)
        <tr data-id="{{ $rubric->rubric_id }}">
            <td>{{ $key+1 }}</td>
            <td>{{ $rubric->title }}</td>
            <td>{{ $rubric->groupTitle }}</td>
            <td>
                @if($type == "activities")
                    {!! Form::select('activity_id', $activities, $rubric->activity_id, ["class" => "activity form-control"]) !!}
                @else
                    <input type="checkbox" class="rubric"  {{ $rubric->notNeed == \App\FirmRubric::NEED ? "checked" : "" }} >
                @endif
            </td>
        </tr>
    @endforeach
</table>
{!! form_open($form, $attr) !!}

{!! form_messages($form) !!}

@foreach($form->getSections() as $id => $section)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ $section['label'] }}</h3>
        </div>

        <div class="panel-body">
            @foreach($form->getSectionFields($id) as $field)
                {!! form_row($field) !!}
            @endforeach
        </div>
    </div>
@endforeach

@foreach($form->getFieldsWithoutSection() as $field)
    {!! form_row($field) !!}
@endforeach

{!! form_submit_button($form) !!}

{!! form_close($form) !!}

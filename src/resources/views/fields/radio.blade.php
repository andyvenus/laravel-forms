@if(!empty($options['choices']))
    @foreach($options['choices'] as $choice_value => $choice_name)
        <label><input type="radio" name="{{ $name }}" value="{{ $choice_value }}" id="{{ $name }}_{{ $choice_value }}" @if(isset($value) && $choice_value == $value)checked="checked"@endif {!! form_attributes($attr, 'radio') !!}/> &nbsp;{{ $choice_name }}</label>&nbsp;
    @endforeach
@else
    No radio field choices for field '{{ $name }}'
@endif

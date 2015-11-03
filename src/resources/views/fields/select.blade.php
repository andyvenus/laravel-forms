<select name="{{ $name }}" {!! form_attributes($attr, 'select') !!}>
    @if(isset($attr['data-placeholder']))<option></option>@endif

    @foreach($options['choices'] as $choice_value => $choice_name)

        @if(is_array($choice_name))
            <optgroup label="{{ $choice_value }}">
                @foreach($choice_name as $inner_choice_value => $inner_choice_name)
                    <option value="{{ $inner_choice_value }}" @if(isset($value) && ($inner_choice_value == $value or (is_array($value) and in_array($inner_choice_value, $value))))selected="selected"@endif @if (isset($options['disabled_choices']) && in_array($choice_value, $options['disabled_choices']))disabled @endif>{{ $inner_choice_name }}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $choice_value }}" @if(isset($value) && (!is_array($value) && (strval($choice_value) === strval($value)) or (is_array($value) and in_array($choice_value, $value))))selected="selected"@endif @if (isset($options['disabled_choices']) && in_array($choice_value, $options['disabled_choices']))disabled @endif>
                {{ $choice_name }}
            </option>
        @endif

    @endforeach
</select>

<label>
    <input type="checkbox" name="{{ $name }}" @if(isset($value)) value="{{ $value }}" @endif @if($options['checked'] == true) checked="checked" @endif {!! form_attributes($attr, 'checkbox') !!} />
    {{ $options['label'] or '' }}
    {!! $options['html_label'] or '' !!}
</label>

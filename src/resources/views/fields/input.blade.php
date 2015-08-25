<input type="{{ $type or 'text' }}" name="{{ $name }}" @if(isset($value) && !is_array($value))value="{{ $value }}"@endif {!! form_attributes($attr, $type) !!}  />

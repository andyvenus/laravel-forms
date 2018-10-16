<input type="{{ $type ?? 'text' }}" name="{{ $name }}" @if(isset($value) && !is_array($value) && $type != 'password')value="{{ $value }}"@endif {!! form_attributes($attr, $type) !!}  />

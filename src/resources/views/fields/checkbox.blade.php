<label><input type="checkbox" name="{{ $name }}" @if(isset($value)) value="{{ $value }}" @endif @if($options['checked'] == true) checked="checked" @endif /> {{ $options['label'] or '' }}</label>

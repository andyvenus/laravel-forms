<div class="form-group @if(isset($field['has_error']) && $field['has_error'] == true)has-error @endif">
    <div class="control-label">
        {!! form_label($field, $attr) !!}
    </div>

    {!! form_field($field, $attr) !!}

    <p class="help-block">@if(isset($field['options']['help'])) {!! $field['options']['help'] !!} @endif</p>
</div>

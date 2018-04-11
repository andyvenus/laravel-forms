<div class="form-group @if(isset($field['has_error']) && $field['has_error'] == true)has-error @endif {{ isset($field['options']['row_class']) ? $field['options']['row_class'] : '' }}">
    @if (form_label($field))
        <div class="control-label">
            {!! form_label($field) !!}
        </div>
    @endif

    {!! form_field($field, $attr) !!}

    <p class="help-block">@if(isset($field['options']['help'])) {!! $field['options']['help'] !!} @endif</p>
</div>

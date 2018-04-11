<div class="form-row {{ isset($field['options']['row_class']) ? $field['options']['row_class'] : '' }}">
    {!! form_field($field) !!}

    <p class="help-block">@if(isset($field['options']['help'])) {!! $field['options']['help'] !!} @endif</p>
</div>

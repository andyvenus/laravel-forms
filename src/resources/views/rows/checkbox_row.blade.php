<div class="form_row">
    {!! form_field($field) !!}

    <p class="help-block">@if(isset($field['options']['help'])) {!! $field['options']['help'] !!} @endif</p>
</div>

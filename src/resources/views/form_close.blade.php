@if ($form->getParams()['method'] !== 'GET')
    {!! csrf_field() !!}
@endif

</form>

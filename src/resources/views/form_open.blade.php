<form enctype="{{ $form->getParams()['encoding'] }}" @if ($form->getParams()['action'])action="{{ $form->getParams()['action'] }}"@endif
@if($form->getParams()['name'])name="{{ $form->getParams()['name'] }}"@endif method="{{ $form->getParams()['method'] }}" {!! form_attributes($attr) !!}>

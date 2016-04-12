<?php

function form(\AV\Form\FormViewInterface $formView, $attributes = [])
{
    return view('forms::full_form')->with('form', $formView)->with('attr', $attributes)->render();
}

function form_sectioned(\AV\Form\FormViewInterface $formView, $attributes = [])
{
    return view('forms::section_form')->with('form', $formView)->with('attr', $attributes)->render();
}

function form_open(\AV\Form\FormViewInterface $form, $attributes = [])
{
    return view('forms::form_open')->with('form', $form)->with('attr', $attributes);
}

function form_close(\AV\Form\FormViewInterface $form, $attributes = [])
{
    return view('forms::form_close')->with('form', $form)->with('attr', $attributes);
}

function form_row(array $fieldData, $attributes = [])
{
    if (isset($fieldData['options']['row_template'])) {
        $template = $fieldData['options']['row_template'];
    }
    else {
        if ($fieldData['type'] == 'hidden' || $fieldData['type'] == 'collection') {
            $templateName = 'hidden_row';
        } elseif ($fieldData['type'] == 'checkbox') {
            $templateName = 'checkbox_row';
        } else {
            $templateName = 'form_label_row';
        }

        $template = 'forms::rows.'.$templateName;
    }


    return view($template)->with('field', $fieldData)->with('attr', $attributes)->render();
}

function form_rows(\AV\Form\FormViewInterface $form, $attributes = [])
{
    $html = '';
    foreach ($form->getFields() as $name => $fieldData) {
        $html .= form_row($fieldData, isset($attributes[$name]) ? $attributes[$name] : []);
    }

    return $html;
}

function form_label(array $fieldData)
{
    return view('forms::label', $fieldData)->render();
}

function label_for_attribute($options)
{
    if(!empty($options) && !empty($options['attr']) && !empty($options['attr']['id'])) { 
        $id = $options['attr']['id'];
        return "for='".$id."'";
    }
    return "";
}

function form_field(array $fieldData, $attributes = [])
{
    if (isset($fieldData['options']['attr']) && is_array($fieldData['options']['attr'])) {
        $fieldData['attr'] = array_merge($fieldData['options']['attr'], $attributes);
    } else {
        $fieldData['attr'] = $attributes;
    }

    $templateName = 'forms::fields.'.$fieldData['type'];

    if (isset($fieldData['options']['field_template'])) {
        $templateName = $fieldData['options']['field_template'];
    }

    try {
        $field = view($templateName, $fieldData)->render();
    } catch (\InvalidArgumentException $e) {
        $field = view('forms::fields.input', $fieldData)->render();
    }

    return $field;
}

function form_submit_button(\AV\Form\FormViewInterface $form, $label = null)
{
    $label = $label ?: $form->getSubmitButtonLabel();

    return view('forms::submit_button')->with('label', $label)->render();
}

function form_messages(\AV\Form\FormViewInterface $form)
{
    return view('forms::messages')->with('form', $form)->render();
}

function form_attributes($attr, $type = null)
{
    $existingClasses = isset($attr['class']) ? $attr['class'] : '';

    // todo: remove hard-coded bootstrap styles
    if (in_array($type, ['input', 'textarea', 'text', 'password', 'text_button', 'select'])) {
        $attr['class'] = 'form-control '.$existingClasses;
    }
    elseif ($type == 'button') {
        $attr['class'] = 'btn btn-default ' . $existingClasses;
    }
    elseif ($type == 'submit') {
        $attr['class'] = 'btn btn-primary ' . $existingClasses;
    }

    $attrString = '';
    foreach ($attr as $name => $value) {
        $attrString .= ' '.$name.'="'.$value.'"';
    }

    return $attrString;
}

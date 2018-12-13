<?php

namespace AV\LaravelForm\ValidatorExtension;

use AV\Form\FormError;
use AV\Form\FormHandler;
use AV\Form\ValidatorExtension\ValidatorExtensionInterface;

class LaravelValidatorExtension implements ValidatorExtensionInterface
{
    /**
     * @var FormHandler
     */
    private $formHandler;

    /**
     * @var bool
     */
    private $isValid = true;

    /**
     * @var \Illuminate\Validation\Validator
     */
    private $validator;

    /**
     * Sets the form handler
     *
     * @param \AV\Form\FormHandler $formHandler
     * @return mixed
     */
    public function setFormHandler(FormHandler $formHandler)
    {
        $this->formHandler = $formHandler;
    }

    /**
     * Validate the form
     *
     * @param null $scope
     * @param null $options
     * @return mixed
     * @throws \Exception
     */
    private function validate()
    {
        if (isset($this->validator)) {
            throw new \Exception('Cannot validate the same form twice, use the existing result');
        }

        $fieldRules = $this->getFieldValidationRules($this->formHandler->getFormBlueprint()->getAll());

        $validationRules = $fieldRules['rules'];
        $validationAttributeNames = $fieldRules['names'];

        // Get validation rules from any assigned entities (models)
        foreach ($this->formHandler->getEntities() as $entity) {
            if (is_callable([$entity['entity'], 'getValidationRules'])) {

                try {
                    $entityRules = $entity['entity']->getValidationRules();
                } catch (\BadMethodCallException $e) {
                    $entityRules = [];
                }

                foreach ($entityRules as $field => $entityRule) {
                    // If we already have rules for that parameter, concatenate them
                    if (isset($validationRules[$field])) {
                        $validationRules[$field] .= '|'.$entityRule;
                    }
                    // Rules for a parameter that doesn't have any yet
                    else {
                        $validationRules[$field] = $entityRule;
                    }
                }
            }
        }

        $this->validator = \Validator::make($this->formHandler->getData(), $validationRules, $fieldRules['messages']);

        $this->validator->setAttributeNames($validationAttributeNames);

        $this->isValid = $this->validator->passes();
    }

    /**
     * Recursively read in the validation rules from the form fields and convert the field names to dot notation
     *
     * @param $fields
     * @return array
     */
    private function getFieldValidationRules($fields)
    {
        $results = ['rules' => [], 'names' => [], 'messages' => []];

        foreach ($fields as $field) {
            if (isset($field['options']['validation'])) {
                if (!isset($field['original_name'])) {
                    $paramName = $field['name'];
                } else {
                    $paramName = $this->fieldNameToDot($field['original_name']);
                }

                $results['rules'][$paramName] = $field['options']['validation'];

                if (isset($field['options']['label'])) {
                    $results['names'][$paramName] = $field['options']['label'];
                }

                if (isset($field['options']['validation_messages'])) {
                    foreach ($field['options']['validation_messages'] as $validation => $message) {
                        $results['messages']["$paramName.$validation"] = $message;
                    }
                }
            }

            if (isset($field['fields'])) {
                $results = array_merge_recursive($results, $this->getFieldValidationRules($field['fields']));
            }
        }

        return $results;
    }

    /**
     * Convert a form field name to dot notation for the Laravel validator
     *
     * @param $fieldName
     * @return string
     */
    private function fieldNameToDot($fieldName)
    {
        $fieldName = trim(str_replace(['][', '[', ']'], '.', $fieldName), '.');

        return $fieldName;
    }

    /**
     * Check if the form is valid or not
     *
     * @param $scope
     * @param $options
     * @return mixed
     */
    public function isValid($scope, $options)
    {
        if (!isset($this->validator)) {
            $this->validate();
        }

        return $this->isValid;
    }

    /**
     * Get errors, should return an array of FormError objects
     *
     * @return \AV\Form\FormError[]
     */
    public function getErrors()
    {
        if (!isset($this->validator)) {
            return [];
        }

        $errors = [];

        foreach($this->validator->errors()->getMessages() as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errors[] = new FormError($field, $error);
            }
        }

        return $errors;
    }

    /**
     * Check if a field has any errors
     *
     * @param $field
     * @return mixed
     */
    public function fieldHasError($field)
    {
        if (!isset($this->validator)) {
            $this->validate();
        }

        return isset($this->validator->errors()->getMessages()[$field]);
    }
}

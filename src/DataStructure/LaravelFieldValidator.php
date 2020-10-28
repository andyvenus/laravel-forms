<?php

namespace AV\LaravelForm\DataStructure;

use AV\Form\DataStructure\Field;
use AV\Form\Validator\FieldValidationResult;
use AV\Form\Validator\ValidationError;
use AV\Form\Validator\ValidationRuleResult;
use AV\Form\Validator\ValidatorInterface;
use Illuminate\Validation\Factory as ValidationFactory;

class LaravelFieldValidator implements ValidatorInterface
{
    private ValidationFactory $validationFactory;

    public function __construct(ValidationFactory $validationFactory)
    {
        $this->validationFactory = $validationFactory;
    }

    public function validateField(Field $field, $value): FieldValidationResult
    {
        $errors = [];

        foreach ($field->getValidationRules() as $rule) {
            if (!$rule->supportsValidator($this)) {
                continue;
            }

            $ruleResult = $this->validate($rule, $field, $value);

            $errors += $ruleResult->getErrors();
        }

        return new FieldValidationResult($errors);
    }

    public function validate(LaravelRule $rule, Field $field, $value)
    {
        $validator = $this->validationFactory->make(
            [$field->getName() => $value],
            [$field->getName() => $rule->getRules()],
            $rule->getErrorMessages()
        );

        $validator->setAttributeNames([$field->getName() => $field->getLabel() ?: $field->getName()]);

        $isValid = $validator->passes();

        if ($isValid) {
            return new ValidationRuleResult(true);
        }

        $fieldErrors = [];
        foreach($validator->errors()->getMessages() as $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $errors[] = new ValidationError($error, $field);
            }
        }

        return new ValidationRuleResult(false, $fieldErrors);
    }
}

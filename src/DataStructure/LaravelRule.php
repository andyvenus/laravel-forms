<?php

namespace AV\LaravelForm\DataStructure;

use AV\Form\DataStructure\Field;
use AV\Form\Validator\ValidationRuleInterface;
use AV\Form\Validator\ValidationRuleResult;
use AV\Form\Validator\ValidatorInterface;

class LaravelRule implements ValidationRuleInterface
{
    private Field $field;

    private string $rules;

    private array $errorMessages;

    public function __construct(string $rules, array $errorMessages = [])
    {
        $this->rules = $rules;
        $this->errorMessages = $errorMessages;
    }

    public function setField(Field $field): void
    {
        $this->field = $field;
    }

    public function supportsValidator(ValidatorInterface $validator): bool
    {
        return $validator instanceof LaravelFieldValidator;
    }

    /**
     * @return string
     */
    public function getRules(): string
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getErrorMessages(): array
    {
        return $this->errorMessages;
    }
}

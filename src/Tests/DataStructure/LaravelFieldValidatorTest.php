<?php

namespace AV\LaravelForm\Tests\DataStructure;

use AV\Form\DataStructure\Field;
use AV\LaravelForm\DataStructure\LaravelFieldValidator;
use AV\LaravelForm\DataStructure\LaravelRule;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use PHPUnit\Framework\TestCase;

class LaravelFieldValidatorTest extends TestCase
{
    public function testValidateField()
    {
        $translatorLoader = new ArrayLoader();
        $translatorLoader->addMessages('en', 'validation', ['email' => 'Wrong email :attribute']);

        $validationFactory = new Factory(new Translator($translatorLoader, 'en'));

        $fieldValidator = new LaravelFieldValidator($validationFactory);

        $field = new Field('string', 'test');
        $field->label('Email');
        $rule = new LaravelRule('email');
        $field->validationRule($rule);

        $result = $fieldValidator->validate($rule, $field, 'notanemail');

        $this->assertFalse($result->isValid());
        $this->assertSame(["Wrong email Email"], $result->getErrors());

        $result = $fieldValidator->validate($rule, $field, 'andyvenus@forms.test');

        $this->assertTrue($result->isValid());
        $this->assertEmpty($result->getErrors());
    }
}

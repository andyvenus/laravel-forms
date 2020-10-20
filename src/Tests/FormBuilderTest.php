<?php

namespace AV\LaravelForm\Tests;

use AV\Form\FormBlueprint;
use AV\Form\FormHandler;
use AV\Form\FormHandlerFactory;
use AV\Form\RequestHandler\SymfonyRequestHandler;
use AV\Form\Transformer\TransformerManager;
use AV\LaravelForm\EntityProcessor\EloquentEntityProcessor;
use AV\LaravelForm\FormBuilder;
use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{
    public function testBuild()
    {
        $factory = new FormHandlerFactory(
            new SymfonyRequestHandler(),
            new EloquentEntityProcessor(),
            new TransformerManager()
        );

        $builder = new FormBuilder($factory);

        $blueprint = new FormBlueprint();

        $form = $builder->build($blueprint);

        $this->assertInstanceOf(FormHandler::class, $form);
    }
}

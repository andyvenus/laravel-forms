<?php
/**
 * User: Andy
 * Date: 01/07/15
 * Time: 10:51
 */

namespace AV\LaravelForm;

use AV\Form\Type\StrictSelectType;
use AV\Form\Type\TypeHandler;
use Illuminate\Support\ServiceProvider;

class LaravelFormProvider extends ServiceProvider
{
    public function register() {}

    /**
     *  Wire-up form classes for injection
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'forms');

        $this->app->singleton('AV\LaravelForm\FormBuilder', 'AV\LaravelForm\FormBuilder');

        $this->app->bind(
            'AV\Form\RequestHandler\RequestHandlerInterface',
            'AV\Form\RequestHandler\SymfonyRequestHandler'
        );

        $this->app->bind(
            'AV\Form\EntityProcessor\EntityProcessorInterface',
            'AV\LaravelForm\EntityProcessor\EloquentEntityProcessor'
        );

        $this->app->bind(
            'AV\Form\RestoreDataHandler\RestoreDataHandlerInterface',
            'AV\LaravelForm\RestoreDataHandler\LaravelRestoreDataHandler'
        );

        $this->app->singleton('AV\Form\Type\TypeHandler', function($app) {
            return new TypeHandler(['select' => new StrictSelectType()]);
        });
    }
}

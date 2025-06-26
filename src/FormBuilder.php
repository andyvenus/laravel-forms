<?php
/**
 * User: Andy
 * Date: 03/09/2014
 * Time: 16:02
 */

namespace AV\LaravelForm;

use AV\Form\FormBlueprint;
use AV\Form\FormHandlerFactory;
use AV\Form\FormView;
use AV\LaravelForm\ValidatorExtension\LaravelValidatorExtension;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Build forms for Laravel
 *
 * Class FormBuilder
 * @package AV\LaravelForm
 */
class FormBuilder
{
    protected $formHandlerFactory;

    protected $translator;

    protected $eventDispatcher;

    public function __construct(FormHandlerFactory $formHandlerFactory)
    {
        $this->formHandlerFactory = $formHandlerFactory;
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function setTranslator(LegacyTranslatorInterface|TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param \AV\Form\FormBlueprint $form
     * @param null $request
     * @param mixed $entities
     * @param null $formView
     * @return \AV\Form\FormHandler
     */
    public function build(FormBlueprint $form, $request = null, $entities = array(), $formView = null)
    {
        if (!$formView) {
            $formView = new FormView();
            if (isset($this->translator)) {
                $formView->setTranslator($this->translator);
            }
        }

        $validatorExtension = new LaravelValidatorExtension();

        $formHandler = $this->formHandlerFactory->buildForm($form, $formView, $validatorExtension);

        if (!is_array($entities)) {
            $entities = array($entities);
        }
        foreach ($entities as $entity) {
            $formHandler->bindEntity($entity);
        }

        if ($request) {
            $formHandler->handleRequest($request);
        }

        return $formHandler;
    }
}

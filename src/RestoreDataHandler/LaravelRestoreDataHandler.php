<?php

namespace AV\LaravelForm\RestoreDataHandler;

use AV\Form\FormHandler;
use AV\Form\RestoreDataHandler\RestoreDataHandlerInterface;
use Illuminate\Http\Request;

class LaravelRestoreDataHandler implements RestoreDataHandlerInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param FormHandler $formHandler
     * @param $request
     * @return array|null
     */
    public function restoreData(FormHandler $formHandler, $request)
    {
        $formName = $this->getFormName($formHandler);

        $restoredErrors = $request->session()->get('form_errors_'.$formName);

        if (!empty($restoredErrors) && is_array($restoredErrors)) {
            $formHandler->addCustomErrors($restoredErrors);
        }

        return $request->session()->get('form_data_'.$formName, null);
    }

    /**
     * @param FormHandler $formHandler
     * @param array $data
     * @param $request
     */
    public function setRestorableData(FormHandler $formHandler, array $data, $request)
    {
        $formName = $this->getFormName($formHandler);

        $this->stripObjects($data);

        $request->session()->flash('form_data_'.$formName, $data);
    }

    /**
     * @param FormHandler $formHandler
     * @param $errors \AV\Form\FormError[]
     */
    public function setRestorableErrors(FormHandler $formHandler, array $errors)
    {
        $formName = $this->getFormName($formHandler);

        $this->request->session()->flash('form_errors_'.$formName, $errors);
    }

    /**
     * Cancel restoring the data. Called when the view is rendered.
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function cancelRestore(FormHandler $formHandler)
    {
        $formName = $this->getFormName($formHandler);

        $this->request->session()->forget('form_data_'.$formName);
        $this->request->session()->forget('form_errors_'.$formName);
        $this->request->session()->forget('form_valid_'.$formName);
    }

    /**
     * If called, the form was valid
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function setValid(FormHandler $formHandler)
    {
        $formName = $this->getFormName($formHandler);

        $this->request->session()->flash('form_valid_'.$formName, 1);
    }

    /**
     * Check if the form was valid
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function wasValid(FormHandler $formHandler)
    {
        $formName = $this->getFormName($formHandler);

        return $this->request->session()->has('form_valid_'.$formName);
    }

    /**
     * Get a unique form name by combining the class name and the form name
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    private function getFormName(FormHandler $formHandler)
    {
        $formName = $formHandler->getFormBlueprint()->getName();

        return str_replace("\\", '', get_class($formHandler->getFormBlueprint()).'::'.$formName);
    }

    /**
     * Remove objects from the data, like file uploads
     *
     * @param $array
     */
    private function stripObjects(&$array)
    {
        foreach ($array as $key => $value) {
            if (is_object($value) && !$value) {
                unset($array[$key]);
            } elseif (is_array($value)) {
                $this->stripObjects($array[$key]);
            }
        }
    }
}

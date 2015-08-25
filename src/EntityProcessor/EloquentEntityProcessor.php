<?php
/**
 * User: Andy
 * Date: 01/07/15
 * Time: 11:24
 */

namespace AV\LaravelForm\EntityProcessor;

use AV\Form\EntityProcessor\EntityProcessorInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentEntityProcessor implements EntityProcessorInterface
{

    /**
     * Save an array of form data to an entity
     *
     * @param $entity
     * @param $formData
     * @param null $limitFields
     * @return mixed
     * @throws \Exception
     */
    public function saveToEntity($entity, $formData, $limitFields = null)
    {
        if (!$entity instanceof Model) {
            throw new \Exception('An entity passed to form that is not an Eloquent model');
        }

        $fillable = $entity->getFillable();

        foreach($formData as $field => $value) {
            if (($limitFields === null || in_array($field, $limitFields)) && in_array($field, $fillable)) {
                $entity->$field = $value;
            }
        }
    }

    /**
     * Get an array of data from an entity
     *
     * @param $entity
     * @param array $formData
     * @param null $limitFields
     * @return mixed
     * @throws \Exception
     */
    public function getFromEntity($entity, array $formData, $limitFields = null)
    {
        if (!$entity instanceof Model) {
            throw new \Exception('An entity passed to form that is not an Eloquent model');
        }

        $fillable = $entity->getFillable();

        $extractedData = [];

        foreach($formData as $field) {
            if (($limitFields === null || in_array($field, $limitFields)) && in_array($field, $fillable) && ($value = $entity->$field) !== null) {
                $extractedData[$field] = $value;
            }
        }

        return $extractedData;
    }
}

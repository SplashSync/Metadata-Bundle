<?php

namespace Splash\Metadata\Services;

use Splash\Metadata\Mapping\FieldMetadata;
use Splash\OpenApi\Fields\Descriptor;

class PropertyReader
{
    public function __construct(
        protected readonly MetadataCollector $fieldsProcessor,
        protected readonly PropertyAccessor $propertyAccessor,
    ) {
    }


    /**
     * Get an Object Field Data
     *
     * @param class-string $model   Target Model
     * @param object       $object  Object to Update
     * @param string       $fieldId Field Identifier / Name
     *
     * @throws Exception
     *
     * @return null|array<string, null|array<string, null|array|scalar>|object|scalar>|object|scalar
     */
    public function get(FieldMetadata $metadata, object $object)
    {


//        //====================================================================//
//        // Detect SubResource Fields Types
//        $prefix = Descriptor::getSubResourcePrefix($fieldId);
//        if (!$prefix) {
            //====================================================================//
            // Read Simple Fields Types
            return $this->getSimpleData($metadata, $object);
//        }
//        //====================================================================//
//        // Load SubResource Object
//        $subResourceModel = Descriptor::getSubResourceModel($model, $prefix);
//        $subResource = self::getRawData($object, $prefix);
//        if (!$subResourceModel || !$subResource || !($subResource instanceof $subResourceModel)) {
//            return null;
//        }
//
//        return self::getSimpleData(
//            $subResourceModel,
//            $subResource,
//            (string) Descriptor::getSubResourceField($fieldId)
//        );
    }

//    /**
//     * Collect Required Fields
//     *
//     * @param AbstractVisitor $visitor
//     * @param object          $inputs
//     *
//     * @throws Exception
//     *
//     * @return null|object
//     */
//    public static function getRequiredFields(AbstractVisitor $visitor, object $inputs): ?object
//    {
//        $newObject = array();
//        //====================================================================//
//        // Walk on required Field Ids
//        foreach (Descriptor::getRequiredFields($visitor->getModel()) as $fieldId) {
//            //====================================================================//
//            // Ensure Field is Available
//            if (!self::exists($inputs, $fieldId)) {
//                Splash::log()->err("ErrLocalFieldMissing", __CLASS__, __FUNCTION__, $fieldId);
//
//                return null;
//            }
//            $newObject[$fieldId] = self::get($visitor->getModel(), $inputs, $fieldId);
//        }
//
//        return $visitor->getHydrator()->hydrate($newObject, $visitor->getModel());
//    }
//
//    /**
//     * Get an Object List Field Data
//     *
//     * @param class-string $model   Target Model
//     * @param object       $object
//     * @param string       $listId
//     * @param string       $fieldId
//     *
//     * @throws Exception
//     *
//     * @return array
//     */
//    public static function getListData(string $model, object $object, string $listId, string $fieldId): array
//    {
//        $results = array();
//        //====================================================================//
//        // Load Raw List Data
//        $rawData = self::getRawData($object, $listId);
//        if (!is_iterable($rawData)) {
//            return $results;
//        }
//        //====================================================================//
//        // Walk on Raw List Data
//        foreach ($rawData as $index => $item) {
//            if (!is_object($item) || !self::exists($item, $fieldId)) {
//                $results[$index] = null;
//
//                continue;
//            }
//            $results[$index] = self::getSimpleData($model, $item, $fieldId);
//        }
//
//        return $results;
//    }



    /**
     * Get a Simple Object Field Data
     */
    private function getSimpleData(FieldMetadata $metadata, object $object): array|float|bool|int|string|null
    {
        //====================================================================//
        // Read Property from Object
        $propertyValue = $this->propertyAccessor->getProperty($object, $metadata);
        //====================================================================//
        // Read Field Value Using Data Transformer
        if ($dataTransformer = $metadata->getDataTransformer()) {
            return $dataTransformer->transform($propertyValue);
        }

        return $propertyValue;
    }

    /**
     * Extract Property from An Object
     *
     * @param object $object
     * @param string $fieldId
     *
     * @return null|mixed
     */
    private static function getProperty(object $object, string $fieldId): mixed
    {
        foreach (array('get', 'is', 'has') as $prefix) {
            $method = $prefix.ucfirst($fieldId);
            if (method_exists($object, $method)) {
                return $object->{$method}();
            }
        }

        return $object->{$fieldId} ?? null;
    }
}
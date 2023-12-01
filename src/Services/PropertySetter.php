<?php

namespace Splash\Metadata\Services;

use Doctrine\ORM\EntityManagerInterface;
use Splash\Metadata\Mapping\FieldMetadata;
use Splash\OpenApi\Fields\Descriptor;
use Splash\OpenApi\Fields\Getter;
use Splash\OpenApi\Visitor\AbstractVisitor;

class PropertySetter
{
    public function __construct(
        protected readonly MetadataCollector $fieldsProcessor,
        protected readonly PropertyAccessor $propertyAccessor,
    ) {
    }

    /**
     * Set an Object Field Data using Metadata Parser
     */
    public function set(FieldMetadata $metadata, object &$object, $fieldData): ?bool
    {
        //====================================================================//
        // Safety Check
        if (empty($metadata->write)) {
            return null;
        }


//        //====================================================================//
//        // Detect SubResource Fields Types
//        $prefix = Descriptor::getSubResourcePrefix($fieldId);
//        if (!$prefix) {
            //====================================================================//
            // Write Simple Fields Types
            return $this->setSimpleData($metadata, $object, $fieldData);
//        }
//        //====================================================================//
//        // Load SubResource Object
//        $subResourceClass = Descriptor::getSubResourceModel($model, $prefix);
//        if (!$subResourceClass) {
//            throw new Exception("Unable to identify SubResource Class");
//        }
//        $subResource = Getter::getRawData($object, $prefix);
//        //====================================================================//
//        // Create SubResource Object
//        if (!$subResource) {
//            $subResource = new $subResourceClass();
//        }
//        //====================================================================//
//        // Write Data to SubResource Object
//        $result = self::setSimpleData(
//            $subResourceClass,
//            $subResource,
//            (string) Descriptor::getSubResourceField($fieldId),
//            $fieldData
//        );
//        //====================================================================//
//        // Update SubResource on Object
//        if ($result) {
//            self::setRawData($model, $object, $prefix, $subResource);
//        }
//
//        return $result;
    }

    /**
     * Set an Object Field Data
     */
    private function setSimpleData(FieldMetadata $metadata, object &$object, $fieldData): ?bool
    {
        //====================================================================//
        // Normalize Field Value Using Data Transformer
        if ($dataTransformer = $metadata->getDataTransformer()) {
            $fieldData = $dataTransformer->reverseTransform($fieldData);
        }
        //====================================================================//
        // Compare Values
        $isSame = $dataTransformer
            ? $dataTransformer->isSame($this->propertyAccessor->getProperty($object, $metadata), $fieldData)
            : ($this->propertyAccessor->getProperty($object, $metadata) == $fieldData)
        ;
        if ($isSame) {
            return false;
        }
        //====================================================================//
        // Write Object Property
        return $this->propertyAccessor->setProperty($object, $metadata, $fieldData);
    }
}
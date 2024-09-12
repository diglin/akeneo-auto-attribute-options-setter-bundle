<?php

namespace Niji\AutoAttributeOptionsSetterBundle\Component\Catalog\Updater\Setter;

use Akeneo\Pim\Enrichment\Component\Product\Model\EntityWithValuesInterface;
use Akeneo\Pim\Structure\Bundle\Doctrine\ORM\Repository\AttributeOptionRepository;
use Akeneo\Pim\Enrichment\Component\Product\Updater\Setter\AttributeSetter as BaseMultiSelectAttributeSetter;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;

class UnrestrictedMultiSelectAttributeSetter extends BaseMultiSelectAttributeSetter
{
    public function __construct(
        protected                             $entityWithValuesBuilder,
        protected                             $supportedTypes,
        private AttributeOptionRepository     $attrOptionRepository,
        private UnrestrictedCreateOptionValue $unrestrictedCreateOptionValue
    ) {
        parent::__construct($entityWithValuesBuilder, $supportedTypes);
    }

    public function setAttributeData(
        EntityWithValuesInterface $entityWithValues,
        AttributeInterface        $attribute,
                                  $data,
        array                     $options = []
    ): void {
        if ($data !== null) {
            $data = preg_replace('/[^a-zA-Z0-9\']/', '_', $data);
            $this->checkOption($attribute, $data);
        }

        parent::setAttributeData($entityWithValues, $attribute, $data, $options);
    }

    protected function checkOption(AttributeInterface $attribute, $datas): void
    {
        if ($datas === null) {
            return;
        }

        foreach ($datas as $data){
            $identifier = $attribute->getCode() . '.' . $data;
            $optionExists = $this->attrOptionRepository->optionExists($identifier);

            if(!$optionExists){
                $this->unrestrictedCreateOptionValue->createOptionValue($attribute->getCode(), $data);
            }
        }
    }
}

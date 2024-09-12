<?php

namespace Niji\AutoAttributeOptionsSetterBundle\Component\Catalog\Updater\Setter;

use Akeneo\Pim\Structure\Component\Updater\AttributeOptionUpdater;
use Akeneo\Tool\Bundle\StorageUtilsBundle\Doctrine\Common\Detacher\ObjectDetacher;
use Akeneo\Tool\Bundle\StorageUtilsBundle\Doctrine\Common\Saver\BaseSaver;
use Akeneo\Tool\Component\StorageUtils\Factory\SimpleFactory;

class UnrestrictedCreateOptionValue
{
    public function __construct(
        private SimpleFactory          $optionValueFactory,
        private AttributeOptionUpdater $attributeOptionUpdater,
        private BaseSaver              $baseSaver,
        private ObjectDetacher         $objectDetacher
    ) {

    }

    public function createOptionValue($codeAttribute, $code): void
    {
        $attributeOptionValue = $this->optionValueFactory->create();

        //todo: make the label locales dynamic by detecting the enabled locales
        //todo: make the sort_order dynamic by getting the highest sort_order available for this attribute
        $tab = [
            'attribute' => $codeAttribute,
            'code' => $code,
            'sort_order' => 2,
            'labels' => [
                'de_CH' => $code,
                'fr_CH' => $code,
                'it_CH' => $code,
                'en_GB' => $code,
                'en_US' => $code,
            ]
        ];

        $this->attributeOptionUpdater->update($attributeOptionValue, $tab, []);
        $this->baseSaver->save($attributeOptionValue, []);

        $this->objectDetacher->detach($attributeOptionValue);
    }
}

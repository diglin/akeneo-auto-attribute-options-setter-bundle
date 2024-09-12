<?php

namespace Niji\AutoAttributeOptionsSetterBundle\EventListener;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class ClassMetadataListener
{
    /**
     * Run when Doctrine ORM metadata is loaded.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $eventArgs->getClassMetadata();

        $entityName = $classMetadata->getName();
        //if ($entityName === 'Pim\Bundle\CatalogBundle\Entity\AttributeOption') {
        if ($entityName === 'Akeneo\Pim\Structure\Component\Model\AttributeOption') {
            $classMetadata->customRepositoryClassName = 'Niji\AutoAttributeOptionsSetterBundle\Doctrine\ORM\Repository\AttributeOptionRepository';
        }
    }
}

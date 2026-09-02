<?php

namespace VladX\PagesBundle\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use VladX\PagesBundle\Attributes\Nestable;

#[AutoconfigureTag('doctrine.event_listener', ['event' => 'loadClassMetadata'])]
class ClassMetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $metadata = $eventArgs->getClassMetadata();
        $reflectionClass = $metadata->getReflectionClass();

        if (!$reflectionClass) {
            return;
        }

        $attributes = $reflectionClass->getAttributes(Nestable::class);
        if (empty($attributes)) {
            return;
        }

        if (!$metadata->hasAssociation('parent')) {
            $metadata->mapManyToOne([
                'fieldName' => 'parent',
                'targetEntity' => $metadata->getName(),
                'inversedBy'   => 'children',
                'joinColumns'  => [
                    [
                        'name'                 => 'parent_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'SET NULL',
                    ]
                ]
            ]);
        }
        if (!$metadata->hasAssociation('children')) {
            $metadata->mapOneToMany([
                'fieldName'    => 'children',
                'targetEntity' => $metadata->getName(),
                'mappedBy'     => 'parent',
                'cascade'      => ['persist'],
            ]);
        }
    }
}

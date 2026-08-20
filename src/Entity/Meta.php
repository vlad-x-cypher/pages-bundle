<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\ORM\Mapping\Embeddable;
use Vich\UploaderBundle\Mapping\Attribute\Uploadable;

#[Embeddable]
#[Uploadable]
class Meta implements MetaInterface
{
    use MetaFieldsTrait;
}

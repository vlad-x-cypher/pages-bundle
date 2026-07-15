<?php

namespace VladX\PagesBundle\Entity;

class MetaProperty
{
    public string $property = '';
    public string $content = '';

    public function __toString(): string
    {
        return $this->property . ':' . $this->content;
    }
}

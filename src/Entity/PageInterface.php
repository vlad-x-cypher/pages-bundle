<?php

namespace VladX\PagesBundle\Entity;

interface PageInterface
{
    public function getTitle(): ?string;
    public function getMeta(): MetaInterface;
}

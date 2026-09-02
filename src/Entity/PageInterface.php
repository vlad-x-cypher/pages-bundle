<?php

namespace VladX\PagesBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface PageInterface
{
    public function getTitle(): ?string;
    public function getSlug(): ?string;
    public function computeFullSlug(): void;
    public function cascadeFullSlug(): void;
    public function getParent(): ?self;
    public function getChildren(): Collection;
    public function getFullSlug(): ?string;
    public function getMeta(): MetaInterface;
    public function getTemplate(): ?string;
    public function getTemplateData(): ?array;
    public function getSitemapConfig(): ?array;
}

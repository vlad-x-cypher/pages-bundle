<?php

namespace VladX\PagesBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;

interface MetaInterface
{
    public function getMetaTitle(): ?string;
    public function getMetaDescription(): ?string;
    public function getMetaKeywords(): ?string;
    public function getOgTitle(): ?string;
    public function setOgImageFile(?File $f): static;
    public function getOgImageFile(): ?File;
    public function getOgImage(): ?EmbeddedFile;
    public function getOgDescription(): ?string;
    public function getMetaUpdatedAt(): ?\DateTimeImmutable;
    /** @param array<int, array{property: string, content: string}> $arr */
    public function setMetaProperties(array $arr = []): static;
    /** @return array<int, array{property: string, content: string}> */
    public function getMetaProperties(): array;

}

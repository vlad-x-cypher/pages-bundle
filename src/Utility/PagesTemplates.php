<?php

namespace VladX\PagesBundle\Utility;

/*
 * @template T of array<string,array{path:string,form:?string}>
 */
class PagesTemplates
{
    /**
     * @param T $templates
     */
    public function __construct(private array $templates)
    {
    }

    public function templatesAsChoices(): array
    {
        return array_combine(
            array_keys($this->templates),
            array_map(fn ($item) => $item['path'], $this->templates)
        );
    }

    public function getTemplatePath(string $name): ?string
    {
        if (array_key_exists($name, $this->templates)) {
            return $this->templates[$name]['path'];
        }
        return null;
    }

    public function getTemplateFormTypeByPath(string $path): ?string
    {
        $item = array_find($this->getTemplates(), fn ($itm) => $itm['path'] == $path);
        if (!$item) {
            return null;
        }

        return $item['form'] ?? null;

    }

    public function getTemplateFormType(string $name): ?string
    {
        if (array_key_exists($name, $this->templates)) {
            return $this->templates[$name]['form'] ?? null;
        }

        return null;
    }

    /**
     * @return T
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }
}

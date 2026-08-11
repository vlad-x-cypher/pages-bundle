<?php

namespace VladX\PagesBundle\Dto;

class TemplateDto
{
    public ?string $template = null;
    public ?array $templateData = [];

    public function __construct(?string $template = "", ?array $data = [])
    {
        $this->template = $template;
        $this->templateData = $data;
    }
}

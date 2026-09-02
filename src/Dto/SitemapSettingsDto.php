<?php

namespace VladX\PagesBundle\Dto;

use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSettingsDto
{
    public bool $isEnabled = true;
    public string $changeFrequency = UrlConcrete::CHANGEFREQ_DAILY;
    public float $priority = 1.0;

    public static function fromArray(?array $arr = null): self
    {
        $s = new self();
        if (isset($arr['isEnabled'])) {
            $s->isEnabled = $arr['isEnabled'];
        }
        if (isset($arr['changeFrequency'])) {
            $s->changeFrequency = $arr['changeFrequency'];
        }
        if (isset($arr['priority'])) {
            $s->priority = $arr['priority'];
        }
        return $s;
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'isEnabled' => $this->isEnabled,
            'changeFrequency' => $this->changeFrequency,
            'priority' => $this->priority,
        ];
    }
}

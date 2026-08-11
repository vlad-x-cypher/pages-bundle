<?php

namespace VladX\PagesBundle\Utility;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use VladX\PagesBundle\Entity\PageInterface;

class PageHelper
{
    public function __construct(
        private readonly UploaderHelper $uploaderHelper
    ) {
    }

    /**
     * @return array<string,array<string,mixed>>
     */
    public function preparePageMeta(PageInterface $page): array
    {
        return [
            'meta' => [
                'title' => $page->getMeta()->getMetaTitle() ?: $page->getTitle(),
                'description' => $page->getMeta()->getMetaDescription(),
                'properties' => array_merge([
                    'og:title' => $page->getMeta()->getOgTitle() ?: ($page->getMeta()->getMetaTitle() ?: $page->getTitle()),
                    'og:description' => $page->getMeta()->getOgDescription() ?: $page->getMeta()->getMetaDescription(),
                    'og:type' => 'website',
                    'og:image' => $this->uploaderHelper->asset($page->getMeta())
                ], array_combine(
                    array_map(callback: fn (array $item) => $item['property'], array: $page->getMeta()->getMetaProperties()),
                    array_map(callback: fn (array $item) => $item['content'], array: $page->getMeta()->getMetaProperties()),
                )),
            ],
        ];
    }
}

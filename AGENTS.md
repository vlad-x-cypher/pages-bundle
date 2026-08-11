# vlad-x/pages-bundle

Symfony bundle providing reusable Page + Meta (SEO/OpenGraph) entities, Vich uploader image field, and EasyAdmin 5 field helpers.

## Key facts

- **Namespace:** `VladX\PagesBundle`, PSR-4 mapped to `src/`
- **DI extension:** `PagesExtension` also prepends Twig namespace (`@vxpgs`) and Vich `metaimage` mapping.
- **No `declare(strict_types=1)`** anywhere in the codebase.

## Entities

- `Page` — mapped superclass (extend in your app). Fields: `title`, embedded `Meta`, Vich `metaOgImageFile`.
- `Meta` — embeddable. Fields: `metaTitle`, `metaDescription`, `metaKeywords`, `ogTitle`, `ogDescription`, `ogImage`, `metaProperties` (JSON array of `{property, content}` pairs). Vich image upload on `ogImageFile`.
- `MetaProperty` — plain DTO (no Doctrine mapping).
- Use `PageInterface` / `MetaInterface` for type safety.

## Twig

- `@vxpgs/meta_fields.html.twig` — renders `<meta>` tags from a `meta` object with `title`, `description`, `properties`.
- `@vxpgs/admin/vich_image.html.twig` — EasyAdmin field template for Vich images.

## Commands

- **Install:** `composer install`
- **No tests, no linter, no type checker, no CI** — none exist in this package.

## Vich uploader config (auto-prepended)

- Mapping: `metaimage`
- URI prefix: `/images/meta`
- Upload destination: `%kernel.project_dir%/public/images/meta`
- Namer: `SmartUniqueNamer`
- Validation: max 5M, JPEG/PNG only (on `Meta` embeddable)

## Page templates

For each page entity we can assign a template through bundle configuration.

Example of configuration `config/packages/pages.yaml`
```yaml
pages:
    templates:
        Homepage: # Template name 
            path: page/index.html.twig # Template path to twig template 
            form: App\Form\HomepageType # Custom form for templateData. Optional field
```

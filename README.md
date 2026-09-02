# vlad-x/pages-bundle

Symfony bundle providing reusable **Page** and **Meta** (SEO / OpenGraph) entities, a Vich Uploader image field, and EasyAdmin 5 field/CRUD helpers.

## Features

- `Page` mapped superclass — title, slug, parent/child hierarchy with automatic `fullSlug` computation, and an embedded `Meta`.
- `Meta` embeddable — `metaTitle`, `metaDescription`, `metaKeywords`, `ogTitle`, `ogDescription`, `ogImage`, and arbitrary `metaProperties` (`{property, content}` pairs).
- Vich Uploader integration — OpenGraph image upload with validation (max 5M, JPEG/PNG) and `SmartUniqueNamer`.
- EasyAdmin 5 helpers — reusable `PageCrudController`, `MetaFields` (SEO tab), and `VichImageField`.
- Template assignment — pages can be rendered through configurable templates with optional custom form types for template data.

## Installation

```bash
composer require vlad-x/pages-bundle
```

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    VladX\PagesBundle\PagesBundle::class => ['all' => true],
];
```

## Configuration

```yaml
# config/packages/pages.yaml
pages:
    templates:
        Homepage:             # Template name
            path: page/index.html.twig   # Twig template path
            form: App\Form\HomepageType  # Optional custom form for templateData
```

## Usage

### 1. Create your page entity

Extend the `Page` mapped superclass:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use VladX\PagesBundle\Entity\Page;

#[ORM\Entity]
class HomePage extends Page
{
}
```

### 2. Override mapped superclass fields

Since `Page` is a mapped superclass, you can override the mapping of its fields
in your own entity with Doctrine's `AttributeOverrides`. This lets you adjust
column definitions (length, nullability, uniqueness, ...) without touching the
bundle.

For example, make the inherited `slug` field longer and non-unique:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use VladX\PagesBundle\Entity\Page;

#[ORM\Entity]
#[ORM\AttributeOverrides([
    new ORM\AttributeOverride(
        name: 'slug',
        column: new ORM\Column(length: 500, unique: false),
    ),
])]
class HomePage extends Page
{
}
```

For more info, check out the official [Doctrine documentation](https://www.doctrine-project.org/projects/doctrine-orm/en/3.6/tutorials/override-field-association-mappings-in-subclasses.html)

#### 2.1 Self Nesting

`VladX\PagesBundle\Entity\Page` mapped supperclass contains unmapped parent/children fields. To enable entity self nesting use `VladX\PagesBundle\Attributes\Nestable` Attribute. For example:

```php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use VladX\PagesBundle\Entity\Page;
use VladX\PagesBundle\Attributes\Nestable;

#[ORM\Entity]
#[Nestable]
class NestablePage extends Page
{
}
```

Create migration and apply it:
```php
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

### 3. Create the EasyAdmin CRUD controller

Extend the abstract `PageCrudController` — it wires the general fields, the template tab, and the SEO tab:

```php
namespace App\Controller\Admin;

use App\Entity\HomePage;
use VladX\PagesBundle\Admin\PageCrudController;

class HomePageCrudController extends PageCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomePage::class;
    }
}
```

You can add custom fields via `addGeneralFields()`:

```php
public function configureFields(string $pageName): iterable
{
    $this->addGeneralFields(TextField::new('customField'));
    yield from parent::configureFields($pageName);
}
```

### 4. Render the page

In your template, use `PageHelper` to build the meta data and the configured template:

```php
use VladX\PagesBundle\Utility\PagesTemplates;

class PageController extends AbstractController
{
    public function show(HomePage $page, PageHelper $pageHelper, PagesTemplates $templates): Response
    {
        return $this->render(
            $templates->getTemplatePath($page->getTemplate()) ?? 'default/index.html.twig',
            [
                'page' => $page,
                'meta' => $pageHelper->preparePageMeta($page)['meta'],
            ]
        );
    }
}
```

Render the SEO meta tags in your base layout:

```twig
{# templates/base.html.twig #}
<html>
<head>
   {% include "@Pages/meta_fields.html.twig" with { meta: meta|default(null) } %}
</head>
<body>
    {% block body %}{% endblock %}
</body>
</html>
```

## Twig templates

- `@Pages/meta_fields.html.twig` — renders `<meta>` tags from a `meta` object with `title`, `description` and `properties`.
- `@Pages/admin/vich_image.html.twig` — EasyAdmin field template for Vich images.

## License

MIT — see [LICENSE](LICENSE).

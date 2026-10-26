# nyumbani/docs

The docs engine of the Nyumbani packages: a page shell, interactive components and live demos, served only in the `local` environment. It brings its own styles and scripts, so it works in any Laravel app, and it is what shows the guides of `nyumbani/essentials`, `nyumbani/ui`, `nyumbani/daraja`, `nyumbani/messaging` and `nyumbani/dt-tables`.

## Requirements

- PHP 8.3 or newer and Laravel 12 or 13

## Install

It is always a **dev dependency**: the docs are for developers, and every package boots without it.

1. Require it with `--dev`:

```
composer require --dev nyumbani/docs
```

2. Open the docs of any Nyumbani package you have installed, with `APP_ENV=local`:

| Package | Docs address |
| --- | --- |
| `nyumbani/essentials` | `/nyumbani-essentials/docs` |
| `nyumbani/ui` | `/nyumbani-ui/docs` |
| `nyumbani/daraja` | `/nyumbani-daraja/docs` |
| `nyumbani/messaging` | `/nyumbani-messaging/docs` |
| `nyumbani/dt-tables` | `/nyumbani-dt-tables/docs` |

Outside the `local` environment every docs address answers 404, and with `composer install --no-dev` the app boots with no docs at all. There is nothing to publish or configure.

## Adding the docs of a package

A package registers its pages when the engine is installed:

```php
use Nyumbani\Docs\Docs;
use Nyumbani\Docs\DocsRegistry;

if (class_exists(DocsRegistry::class)) {
    app(DocsRegistry::class)->register(new Docs('nyumbani-acme', 'Acme', [
        ['title' => 'Getting started', 'pages' => ['introduction' => 'Introduction']],
    ], 'acme::docs.pages'));
}
```

After adding or changing a docs view in any package, run `npm run build` in this folder and commit `resources/dist`: the stylesheet is built from the docs views of every package.

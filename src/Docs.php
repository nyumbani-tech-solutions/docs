<?php

namespace Nyumbani\Docs;

use Illuminate\Support\Collection;

/**
 * The docs of one Nyumbani package: its pages, grouped by the concept they explain, and where they are served
 *
 * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
 */
class Docs
{
    /**
     * The interactive demos by name, each answering what a reader typed with the rows to show
     *
     * @var array<string, callable>
     */
    protected array $demos = [];

    /**
     * Create the docs of a package
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $name,
        protected array $sections,
        protected string $views,
        public readonly string $home = 'introduction',
        public ?string $head = null,
        public ?string $foot = null,
        public array $htmlAttributes = [],
    ) {}

    /**
     * Load the app's own head and footer markup around the docs, for pages whose live examples need the app's styles and scripts
     *
     * The attributes go on the html tag, for scripts that read them. Without it the docs bring their own styles and scripts, so a package works in any Laravel app.
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function withAppAssets(string $head, string $foot, array $htmlAttributes = []): static
    {
        //  Keep the views
        $this->head = $head;
        $this->foot = $foot;
        $this->htmlAttributes = $htmlAttributes;

        //  Return the docs
        return $this;
    }

    /**
     * Add an interactive demo, which receives the typed values and returns the rows to show
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function demo(string $name, callable $callback): static
    {
        //  Keep the demo
        $this->demos[$name] = $callback;

        //  Return the docs
        return $this;
    }

    /**
     * Determine whether the package has a demo with the name
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function hasDemo(string $name): bool
    {
        return array_key_exists($name, $this->demos);
    }

    /**
     * Run a demo with the values a reader typed
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function runDemo(string $name, array $input): array
    {
        return ($this->demos[$name])($input);
    }

    /**
     * Build one row of a demo's answer, marked as passing or failing when it is a check
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public static function row(string $label, mixed $value, ?bool $passes = null): array
    {
        return [
            'label' => $label,
            'value' => is_bool($value) ? ($value ? 'Yes' : 'No') : (string) ($value ?? 'null'),
            'state' => $passes === null ? null : ($passes ? 'pass' : 'fail'),
        ];
    }

    /**
     * Get the url of a built file or image of the docs, which changes when the file does so a browser never keeps an old copy
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public static function asset(string $file): string
    {
        //  Find the file where the build and the images are kept
        $path = collect(['dist', 'images'])
            ->map(fn (string $folder) => __DIR__."/../resources/{$folder}/{$file}")
            ->first(fn (string $candidate) => is_file($candidate));

        return route('docs.asset', ['file' => $file]).($path ? '?v='.filemtime($path) : '');
    }

    /**
     * Get the path the docs are served from
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function path(): string
    {
        return "{$this->slug}/docs";
    }

    /**
     * Get the url of a page, or of the home page when none is given
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function url(?string $page = null): string
    {
        return route('docs.page', ['package' => $this->slug, 'page' => $page === $this->home ? null : $page]);
    }

    /**
     * Get the name of the view of a page
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function view(string $page): string
    {
        return "{$this->views}.{$page}";
    }

    /**
     * Get the sections of the sidebar, each with its pages as slug => title
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function sections(): Collection
    {
        return collect($this->sections);
    }

    /**
     * Get every page as slug => title, in reading order
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function pages(): Collection
    {
        return $this->sections()->flatMap(fn (array $section) => $section['pages']);
    }

    /**
     * Get the pages before and after a page, as slug => title
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function neighbours(string $slug): array
    {
        //  Find where the page sits in the reading order
        $slugs = $this->pages()->keys();
        $position = $slugs->search($slug);

        //  Pick the pages either side of it
        $previous = $slugs->get($position - 1);
        $next = $slugs->get($position + 1);

        return [
            'previous' => $position > 0 && $previous ? [$previous => $this->pages()[$previous]] : null,
            'next' => $next ? [$next => $this->pages()[$next]] : null,
        ];
    }
}

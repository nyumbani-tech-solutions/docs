<?php

namespace Nyumbani\Docs\Views\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Nyumbani\Docs\DocsRegistry;

class PackageLink extends Component
{
    /**
     * Create the link to a page of another package's docs, which is plain text while that package is not installed
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function __construct(
        protected DocsRegistry $registry,
        public string $package,
        public ?string $page = null,
        public ?string $anchor = null,
    ) {}

    /**
     * Get the address of the page, or null when the package registered no docs
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function url(): ?string
    {
        //  Find the docs of the package
        $docs = $this->registry->find($this->package);

        //  Add the section to the page address
        return $docs ? $docs->url($this->page).($this->anchor ? "#{$this->anchor}" : '') : null;
    }

    /**
     * Get the view that draws the link
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function render(): View
    {
        return view('docs::components.package-link');
    }
}

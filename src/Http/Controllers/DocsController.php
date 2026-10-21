<?php

namespace Nyumbani\Docs\Http\Controllers;

use Illuminate\Contracts\View\View;
use Nyumbani\Docs\DocsRegistry;

class DocsController
{
    /**
     * Show a page of the docs of a package
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function __invoke(DocsRegistry $registry, string $package, ?string $page = null): View
    {
        //  Only the packages that registered docs have them
        $docs = $registry->find($package);

        //  Abort if the package does not have docs
        abort_unless($docs, 404);

        //  Open on the home page, and only show the pages in the sidebar
        $page ??= $docs->home;

        //  Abort if the page does not exist
        abort_unless($docs->pages()->has($page), 404);

        //  Show the page
        return view($docs->view($page), [
            'docs' => $docs,
            'packages' => $registry->all(),
            'current' => $page,
            'neighbours' => $docs->neighbours($page),
        ]);
    }
}

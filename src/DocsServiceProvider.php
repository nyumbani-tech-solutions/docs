<?php

namespace Nyumbani\Docs;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class DocsServiceProvider extends ServiceProvider
{
    /**
     * Keep the docs of every package in one place.
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function register(): void
    {
        $this->app->singleton(DocsRegistry::class);
    }

    /**
     * Load the routes, the views and the components of the docs.
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function boot(): void
    {
        //  Load the routes
        $this->loadRoutesFrom(docs_path('routes/web.php'));

        //  Load views
        $this->loadViewsFrom(docs_path('resources/views'), 'docs');

        //  Load components
        Blade::anonymousComponentPath(docs_path('resources/views/components'), 'docs');

        //  Register components
        Blade::componentNamespace('Nyumbani\\Docs\\Views\\Components', 'docs');
    }
}

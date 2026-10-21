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
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        //  Load the layout and the components
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docs');
        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'docs');
        Blade::componentNamespace('Nyumbani\\Docs\\Views\\Components', 'docs');
    }
}

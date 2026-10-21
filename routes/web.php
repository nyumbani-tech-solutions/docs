<?php

use Illuminate\Support\Facades\Route;
use Nyumbani\Docs\Http\Controllers\DocsAssetController;
use Nyumbani\Docs\Http\Controllers\DocsController;
use Nyumbani\Docs\Http\Controllers\DocsDemoController;
use Nyumbani\Docs\Http\Middleware\EnsureDocsAreAvailable;

//  Serve the styles, scripts and images of the docs, which need no session
Route::get('nyumbani-docs/assets/{file}', DocsAssetController::class)
    ->where('file', '[A-Za-z0-9._-]+')
    ->middleware(EnsureDocsAreAvailable::class)
    ->name('docs.asset');

//  Show the docs of every Nyumbani package to developers, in the local environment, at /nyumbani-{package}/docs
Route::get('{package}/docs/{page?}', DocsController::class)
    ->where('package', 'nyumbani-[a-z0-9-]+')
    ->where('page', '[a-z-]+')
    ->middleware(['web', EnsureDocsAreAvailable::class])
    ->name('docs.page');

//  Run the interactive demos of the docs, which are available wherever the docs are
Route::post('{package}/docs/demo/{demo}', DocsDemoController::class)
    ->where('package', 'nyumbani-[a-z0-9-]+')
    ->where('demo', '[a-z0-9-]+')
    ->middleware(['web', EnsureDocsAreAvailable::class])
    ->name('docs.demo');

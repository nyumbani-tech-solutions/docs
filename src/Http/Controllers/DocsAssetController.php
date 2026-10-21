<?php

namespace Nyumbani\Docs\Http\Controllers;

use Nyumbani\Docs\Docs;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocsAssetController
{
    /**
     * Serve a built file of the docs, or an image they show, by name
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    public function __invoke(string $file): BinaryFileResponse
    {
        //  Look for the file where the build and the images are kept
        $path = Docs::assetPath($file);

        //  Only the files the package ships can be served
        abort_unless($path, 404);

        //  Name the type by its extension, since the server may guess a script is text
        return response()->file($path, [
            'Content-Type' => match (pathinfo($path, PATHINFO_EXTENSION)) {
                'js' => 'text/javascript',
                'css' => 'text/css',
                'png' => 'image/png',
                default => mime_content_type($path),
            },
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}

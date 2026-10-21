<?php

if (! function_exists('docs_path')) {
    /**
     * Get the path to a file or folder inside the docs package.
     *
     * @author Alvin G. Kaburu <geekaburu@nyumbanitech.co.ke>
     */
    function docs_path(string $path = ''): string
    {
        return dirname(__DIR__, 2).($path ? DIRECTORY_SEPARATOR.ltrim($path, '/\\') : '');
    }
}

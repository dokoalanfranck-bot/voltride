<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StorageController extends Controller
{
    /**
     * Serve image files from storage without requiring symlink
     */
    public function getImage($path): Response|BinaryFileResponse
    {
        $fullPath = 'public/' . $path;

        // Security check: prevent directory traversal
        if (str_contains($path, '..') || str_contains($path, '//')) {
            return response('Unauthorized', 403);
        }

        // Check if file exists in storage
        if (!Storage::disk('local')->exists($fullPath)) {
            return response('Not Found', 404);
        }

        $file = Storage::disk('local')->path($fullPath);

        // Return file response
        return response()->file($file, [
            'Content-Type' => mime_content_type($file),
        ]);
    }

    /**
     * Serve logo file
     */
    public function getLogo($filename): Response|BinaryFileResponse
    {
        return $this->getImage('logos/' . $filename);
    }
}

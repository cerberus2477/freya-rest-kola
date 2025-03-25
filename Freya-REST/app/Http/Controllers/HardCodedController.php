<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HardCodedController extends Controller
{
    public function documentation()
{
    $filename = 'FreyasGardenDocumentation.docx';
    $relativePath = "documentation/{$filename}";
    
    if (!Storage::disk('public')->exists($relativePath)) {
        return response()->json([
            'message' => 'Documentation file not found',
            'path' => $relativePath
        ], 404);
    }

    return response()->json([
        'download_url' => asset("storage/documentation/{$filename}"),
        'message' => 'Documentation available at the provided URL'
    ]);
}

public function placeholders()
{
    $directoryPath = 'placeholders'; // Relative to public disk
    $disk = Storage::disk('public');
    
    if (!$disk->exists($directoryPath)) {
        return response()->json([
            'message' => 'Placeholders directory not found',
            'path' => $directoryPath
        ], 404);
    }

    $files = $disk->files($directoryPath);
    $fileUrls = [];

    foreach ($files as $file) {
        // Remove any 'public/' prefix if present
        $cleanPath = str_replace('public/', '', $file);
        $fileUrls[] = asset("storage/{$cleanPath}");
    }

    return response()->json([
        'files' => $fileUrls,
        'count' => count($fileUrls),
        'message' => 'Placeholder images available'
    ]);
}
}

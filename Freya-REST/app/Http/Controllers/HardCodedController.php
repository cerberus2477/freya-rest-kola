<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HardCodedController extends BaseController
{
    public function getDocumentation()
{
    $filename = 'FreyasGardenDocumentation.docx';
    $relativePath = "documentation/{$filename}";
    
    if (!Storage::disk('public')->exists($relativePath)) {
        return $this->jsonResponse(404,
            'Documentation file not found',
            $relativePath);
    }

    return $this->jsonResponse(200,
        'Documentation available at the provided URL',
        asset("storage/documentation/{$filename}"));
}

public function getPlaceholders(string $directoryPath)
{
    $disk = Storage::disk('public');
    
    if (!$disk->exists($directoryPath)) {
        return $this->jsonResponse(404,
            'Placeholders directory not found',
            $directoryPath);
    }

    $files = $disk->files($directoryPath);
    $fileUrls = [];

    foreach ($files as $file) {
        // Remove any 'public/' prefix if present
        $cleanPath = str_replace('public/', '', $file);
        $fileUrls[] = asset("storage/{$cleanPath}");
    }

    return $this->jsonResponse(200,
    'Placeholders available at the provided URLs',
    [
        $directoryPath => $fileUrls,
        'count' => count($fileUrls)
    ]);    
}
}

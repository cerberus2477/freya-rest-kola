<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HardCodedController extends Controller
{
    public function documentation()
    {
        $filePath = 'app/public/public/documentation/FreyasDocu.docx'; //TODO: remove gitignore from the folder

        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'Documentation not found'], 404);
        }

        $content = Storage::get($filePath);
        return response()->json(['documentation' => $content], 200);
    }

    public function placeholders()
    {
        $directoryPath = 'app/public/public/placeholders/'; //TODO: remove gitignore from the folder

        if (!Storage::exists($directoryPath)) {
            return response()->json(['message' => 'Placeholders not found'], 404);
        }

        $files = Storage::files($directoryPath);
        $fileUrls = [];

        foreach ($files as $file) {
            $fileUrls[] = Storage::url($file);
        }

        return response()->json(['files' => $fileUrls], 200);
    }
}

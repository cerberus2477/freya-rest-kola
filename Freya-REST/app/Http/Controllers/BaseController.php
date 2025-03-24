<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

abstract class BaseController extends Controller
{
    // message e.g "Articles retrieved successfully"
    protected function jsonResponse(int $status, string $message, $data = []): JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data instanceof LengthAwarePaginator ? $data->items() : $data,
        ];

        if ($data instanceof LengthAwarePaginator) {
            $response['pagination'] = [
                'total' => $data->total(),
                'page' => $data->currentPage(),
                'pageSize' => $data->perPage(),
                'totalPages' => $data->lastPage(),
            ];
        }

        return response()->json($response, $status);
    }

    
    //used in listings, articles, potentially profile
    //functions to be used in delete, update, create having to do with image handling
    //TODO: articleben nem medianal hivjak a cucclit
    protected function deleteMediaFromApi($model, $folder)
    {
        // Decode media JSON
        $previousImages = json_decode($model->media, true);
        
        foreach ($previousImages as $fileName) {
            $filePath = storage_path("{$folder}/" . $fileName);
            if (file_exists($filePath)) {
                // Delete the file from storage
                unlink($filePath);
            }
        }
    }
    

    protected function storeRequestImagesInApi($request, $folder)
    {
        $manager = new ImageManager(new Driver());
        $imagePaths = [];
        
        foreach ($request->file('media') as $image) {
            // Resize and compress
            $imageInstance = $manager->read($image->getRealPath());
            $imageInstance->scaleDown(1920, 1080);
            $encodedImage = $imageInstance->toWebp(80);
            
            // Generate a unique filename and store image in "storage/app/public/{folder}"
            $filename = $folder . '_' . uniqid() . '.webp';
            $path = "{$folder}/" . $filename;
            Storage::disk('public')->put($path, $encodedImage);
            
            // Store only the filename (not the full URL)
            $imagePaths[] = $filename;
        }
        
        return $imagePaths;
    }

}
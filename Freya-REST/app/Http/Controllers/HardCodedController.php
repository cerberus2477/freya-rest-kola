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

//documentation

/**
 * @api {get} /documentation Get Documentation File
 * @apiName GetDocumentation
 * @apiGroup HardCoded
 * @apiDescription Retrieve the documentation file for Freya's Garden.
 *
 * @apiSuccess {Number} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {String} data URL to the documentation file.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "Documentation available at the provided URL",
 *   "data": "http://localhost/storage/documentation/FreyasGardenDocumentation.docx"
 * }
 *
 * @apiError {Number} status HTTP status code.
 * @apiError {String} message Error message.
 * @apiError {String} data Path to the missing documentation file.
 *
 * @apiErrorExample {json} Error Response:
 * HTTP/1.1 404 Not Found
 * {
 *   "status": 404,
 *   "message": "Documentation file not found",
 *   "data": "documentation/FreyasGardenDocumentation.docx"
 * }
 */

/**
 * @api {get} /images/:folder Get Placeholder Images
 * @apiName GetPlaceholders
 * @apiGroup HardCoded
 * @apiDescription Retrieve all placeholder images from a specified folder.
 *
 * @apiParam {String="placeholders","profilePictures","notFoundImage"} folder The folder name to retrieve images from.
 *
 * @apiSuccess {Number} status HTTP status code.
 * @apiSuccess {String} message Success message.
 * @apiSuccess {Object} data Object containing the folder name, list of image URLs, and the count of images.
 * @apiSuccess {String[]} data.<folder> List of image URLs.
 * @apiSuccess {Number} data.count Total number of images in the folder.
 *
 * @apiSuccessExample {json} Success Response:
 * HTTP/1.1 200 OK
 * {
 *   "status": 200,
 *   "message": "Placeholders available at the provided URLs",
 *   "data": {
 *     "placeholders": [
 *       "http://localhost/storage/placeholders/image1.png",
 *       "http://localhost/storage/placeholders/image2.png"
 *     ],
 *     "count": 2
 *   }
 * }
 *
 * @apiError {Number} status HTTP status code.
 * @apiError {String} message Error message.
 * @apiError {String} data Path to the missing folder.
 *
 * @apiErrorExample {json} Error Response:
 * HTTP/1.1 404 Not Found
 * {
 *   "status": 404,
 *   "message": "Placeholders directory not found",
 *   "data": "placeholders"
 * }
 */

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Exception;


class StorageHelper
{
    // Deletes media files from storage folder.
    public static function deleteMedia($filenames, string $folder): void
    {
        foreach ($filenames as $filename) {
            $filePath = "public/{$folder}/{$filename}";
            Storage::disk('local')->delete($filePath);
        }
    }


    // Stores request images, compresses them, and saves them as WebP.
    public static function storeRequestImages($request, string $folder): array
    {
        $manager = new ImageManager(new Driver());
        $imagePaths = [];

        foreach ($request->file('media') as $image) {
            $imageInstance = $manager->read($image->getRealPath());
            $imageInstance->scaleDown(1920, 1080);
            $encodedImage = $imageInstance->toWebp(80);

            $filename = self::storeImageWithUniqueName($folder, $encodedImage);
            $imagePaths[] = $filename;
        }

        return $imagePaths;
    }


    // Returns a placeholder image path (random if filename is not provided).
    public static function getPlaceholderImage(?string $filename = null): string
    {
        $placeholders = Storage::disk('public')->files('profilePictures');

        if (empty($placeholders)) {
            throw new Exception("No placeholder images found in 'storage/app/public/profilePictures'.");
        }
        return $filename ? "profilePictures/{$filename}" : $placeholders[array_rand($placeholders)];
    }


    // Generates between $min and $max random images and return the array of paths
    public static function generateImagesToFolder(string $folder, int $min = 1, int $max = 10): array
    {
        Storage::disk('public')->makeDirectory($folder);

        $numImages = rand($min, $max);
        $imagePaths = [];

        for ($i = 0; $i < $numImages; $i++) {
            $image = imagecreatetruecolor(640, 480);
            $bgColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
            imagefill($image, 0, 0, $bgColor);

            ob_start();
            imagewebp($image, null, 80);
            $imageData = ob_get_clean();

            imagedestroy($image);

            $imagePaths[] = self::storeImageWithUniqueName($folder, $imageData);
        }

        return $imagePaths;
    }


    // Stores an image with a unique name and returns the path.
    public static function storeImageWithUniqueName(string $folder, string $imageData): string
    {
        $filename = "{$folder}_" . Str::uuid() . '.webp';
        $path = "{$folder}/{$filename}";

        Storage::disk('public')->put($path, $imageData);

        return $path;
    }
}

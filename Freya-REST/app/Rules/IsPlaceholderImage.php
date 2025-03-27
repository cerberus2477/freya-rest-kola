<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Storage;

class IsPlaceholderImage implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Get all files from the placeholderImages directory
        $placeholderImages = scandir(public_path('placeholders'));
        
        // Filter out . and .. directories and get just the filenames
        $validImages = array_filter($placeholderImages, function ($file) {
            return !in_array($file, ['.', '..']) && is_file(public_path('placeholders/' . $file));
        });

        // Check if the given value matches any of the placeholder images
        if (!in_array($value, $validImages)) {
            $fail('The selected image is not a valid image.');
        }
    }
}

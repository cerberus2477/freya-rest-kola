<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\UserPlant;

class notUsedByOtherListing implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->isUsedByOtherListing($value)) {
            $fail('This plant is already associated with another listing.');
        }
    }

    /**
     * Check if the plant is used by another listing
     */
    protected function isUsedByOtherListing(int $plantId): bool
    {
        // Using the UserPlant model's relationship
        $userPlant = UserPlant::find($plantId);

        if (!$userPlant) {
            return false;
        }

        $existingListing = $userPlant->listing;

        // If no listing exists, it's available
        if (!$existingListing) {
            return false;
        }

        // If we're editing a listing, check if it's the same one
        if ($listingId = request()->route('listing')) {
            return $existingListing->id != $listingId;
        }

        return true;
    }
}

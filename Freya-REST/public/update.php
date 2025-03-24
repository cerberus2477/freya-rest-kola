<?php

public function update(ListingRequest $request, $id)
{
    $manager = new ImageManager(new Driver());

    // Find listing by ID
    $listing = Listing::find($id);
    if (!$listing) {
        return $this->jsonResponse(404, 'Listing not found');
    }

    $user = $request->user();

    // Check permissions
    // if (!$user->tokenCan('admin') && $user->id != $listing->userPlant->user->id) {
    if(!$user->abilities('admin') && $user->$id != $listing->userPlant()->user()->id){
        return $this->jsonResponse(403, "You don't have permission to modify this listing");
    }

    if ($request->hasFile('media')) 
    {
        // Delete previous images
        $previousImages = json_decode($listing->media, true) ?? [];
        foreach ($previousImages as $file)
        {
            $filePath = storage_path('app/public/listings/' . $file);
            if (file_exists($filePath))
            {
                // Delete the file from storage
                unlink($filePath);
            }
        }

        // Handle new image uploads
        $imagePaths = [];
        foreach ($request->file('media') as $image)
        {
            // Resize and compress image
            $imageInstance = $manager->read($image->getRealPath());
            $imageInstance->scaleDown(1920, 1080);
            $encodedImage = $imageInstance->toWebp(80);

            // Generate a unique filename
            $filename = 'listing_' . uniqid() . '.webp';
            $path = 'public/listings/' . $filename;

            // Store the image
            Storage::disk('public')->put($path, $encodedImage);

            // Store only the filename
            $imagePaths[] = $filename;
        }
    }

    // Update listing with new data
    $data = array_merge($request->validated(), ['media' => json_encode($imagePaths)]);
    $listing->update($data);

    return $this->jsonResponse(201, 'Listing updated successfully', $listing);
}

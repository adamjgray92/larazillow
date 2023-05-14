<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealtorListingImageController extends Controller
{
    public function create(Listing $listing)
    {
        // Loads all the related images
        // Relationship found on the Listing model
        $listing->load(['images']);

        return inertia('Realtor/ListingImage/Create', ['listing' => $listing]);
    }

    public function store(Listing $listing, Request $request)
    {
        if ($request->hasFile('images')) {
            $request->validate([
                'images.*' => 'mimes:jpg,png,jpeg|max:5000'
            ], [
                'images.*.mimes' => 'The file should be in one of the formats: jpg, png, jpeg'
            ]);
            
            foreach ($request->file('images') as $file) {
                // public disc, check filesystem.php
                $path = $file->store('images', 'public');

                $listing->images()->save(new ListingImage([
                    'filename' => $path
                ]));
            }
        }

        return redirect()->back()->with('success', 'Images uploaded');
    }

    public function destroy($listing, ListingImage $image)
    {
        Storage::disk('public')->delete($image->filename);
        $image->delete();
        
        return redirect()->back()->with('success', 'Image was deleted!');
    }
}
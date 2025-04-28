<?php

namespace App\Http\Controllers;

use App\Models\ImageStyle;
use App\Models\Category;
use App\Models\Tag;
use App\Http\Resources\ImageStyleResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('gallery', [
            'imageStyles' => ImageStyle::with(['category', 'tags'])
                ->latest()
                ->paginate(12),
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    public function mobile()
    {
        return view('mobile-gallery', [
            'imageStyles' => ImageStyle::with(['category', 'tags'])
                ->latest()
                ->paginate(8),
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    public function show($id)
    {
        try {
            $imageStyle = ImageStyle::with(['category', 'tags'])->findOrFail($id);
            
            Log::info('Image data:', [
                'id' => $imageStyle->id,
                'title' => $imageStyle->title,
                'image' => $imageStyle->image,
                'storage_path' => Storage::disk('public')->path($imageStyle->image),
                'exists' => Storage::disk('public')->exists($imageStyle->image)
            ]);

            // Periksa apakah file gambar ada
            if (!Storage::disk('public')->exists($imageStyle->image)) {
                Log::error('Image file not found:', [
                    'path' => $imageStyle->image,
                    'id' => $id,
                    'storage_path' => Storage::disk('public')->path($imageStyle->image)
                ]);
                return response()->json(['error' => 'Image file not found'], 404);
            }

            $resource = new ImageStyleResource($imageStyle);
            Log::info('API Response:', $resource->toArray(request()));
            return $resource;
        } catch (\Exception $e) {
            Log::error('Error in GalleryController@show:', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return response()->json(['error' => 'Image not found'], 404);
        }
    }

    public function apiList(Request $request)
    {
        $query = ImageStyle::with(['category', 'tags']);
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->tags) {
            $tagIds = explode(',', $request->tags);
            $query->whereHas('tags', function($q) use ($tagIds) {
                $q->whereIn('tags.id', $tagIds);
            });
        }
        $perPage = 8;
        $imageStyles = $query->latest()->paginate($perPage);
        // Return resource agar relasi tetap konsisten
        return ImageStyleResource::collection($imageStyles);
    }
}
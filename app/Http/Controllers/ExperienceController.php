<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        //*Validate the request data
        $request->validate([
            'title' => 'required',
            'department' => 'required',
            'your_experience' => 'required',
        ]);

        //*Save the blog post
        $experience = new Experience();
        $experience->user_id = auth()->user()->id;
        $experience->title = $request->title;
        $experience->department = $request->department;
        $experience->featured_image_url = $request->featured_image_url ?? null;
        $experience->your_experience = $request->your_experience ?? null;
        $experience->is_published = '0';
        $experience->slug = $this->generateUniqueSlug($request->title);
        $experience->save();

        return response()->json([
            'message' => 'experience post created successfully',
            'experience' => $experience,
        ], 201);
    }

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = 0;

        while (Experience::where('slug', $slug)->exists()) {
            $count++;
            $slug = Str::slug($title) . '-' . $count;
        }

        return $slug;
    }

    public function updatePublishStatus(Request $request, $id)
    {
        $request->validate([
            'is_published' => 'required|boolean',
        ]);

        $experience = Experience::findOrFail($id);
        $experience->is_published = $request->is_published;
        $experience->save();

        return response()->json([
            'message' => 'Publish status updated successfully',
            'experience' => $experience,
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'title' => 'required',
            'department' => 'required',
            'your_experience' => 'required',
        ]);
        $experience = Experience::findOrFail($id);

        // If title changed, regenerate slug
        if ($request->filled('title') && $experience->title !== $request->title) {
            $experience->slug = $this->generateUniqueSlug($request->title);
            $experience->title = $request->title;
        }
        $experience->title = $request->title ?? $experience->title;
        $experience->department = $request->department ?? $experience->department;
        $experience->featured_image_url = $request->featured_image_url ?? $experience->featured_image_url;
        $experience->your_experience = $request->your_experience ?? $experience->your_experience;
        $experience->is_published = $request->is_published ?? 0;
        $experience->save();

        return response()->json([
            'message' => 'Experience Updated Successfully',
            'experience' => $experience
        ]);
    }

    public function delete($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return response()->json([
            'message' => 'Experience Deleted Successfully',
        ]);
    }


}

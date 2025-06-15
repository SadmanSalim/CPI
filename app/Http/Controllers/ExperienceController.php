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


}

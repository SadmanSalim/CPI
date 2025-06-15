<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'featured_image_url' => 'nullable|url',
        //     'content' => 'required|string',
        // ]);

        // Create a new blog post
        $blog = new Blog();
        $blog->user_id = auth()->user()->id; // Assuming the user is authenticated
        $blog->title = $request->title;
        $blog->slug = $this->generateUniqueSlug($request->title);
        $blog->category = $request->category;
        $blog->department = $request->department;
        $blog->featured_image_url = $request->featured_image_url;
        $blog->content = $request->content;
        $blog->is_published = false; // Default to not published
        $blog->save();

        return response()->json([
            'message' => 'Blog post created successfully',
            'blog' => $blog,
        ], 201);

    }

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = 0;

        while (Blog::where('slug', $slug)->exists()) {
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
    
        $blog = Blog::findOrFail($id);
        $blog->is_published = $request->is_published;
        $blog->save();
    
        return response()->json([
            'message' => 'Publish status updated successfully',
            'blog' => $blog,
        ]);
        }
}

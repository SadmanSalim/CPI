<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'featured_image_url' => 'nullable|url',
            'content' => 'required|string',
        ]);

        // Create a new blog post
        $blog = new Blog();
        $blog->user_id = auth()->id(); // current logged-in user
        $blog->title = $request->title;
        $blog->slug = $this->generateUniqueSlug($request->title);
        $blog->category = $request->category;
        $blog->department = $request->department;
        $blog->featured_image_url = $request->featured_image_url;
        $blog->content = $request->content;
        $blog->is_published = false; // default unpublished
        $blog->save();

        return response()->json([
            'message' => 'Blog post created successfully',
            'blog' => $blog,
        ], 201);
    }

    protected function generateUniqueSlug($title)
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        while (Blog::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function updatePublishStatus(Request $request, $id)
    {
        $request->validate([
            'is_published' => 'required|boolean',
        ]);

        $blog = Blog::findOrFail($id);
        $blog->is_published = $request->boolean('is_published');
        $blog->save();

        return response()->json([
            'message' => 'Publish status updated successfully',
            'blog' => $blog,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'featured_image_url' => 'nullable|url',
            'content' => 'required|string',
        ]);

        $blog = Blog::findOrFail($id);

        // If title changed, regenerate slug
        if ($request->filled('title') && $blog->title !== $request->title) {
            $blog->slug = $this->generateUniqueSlug($request->title);
            $blog->title = $request->title;
        }
        

        $blog->title = $request->title ?? $blog->title;
        $blog->category = $request->category ?? $blog->category;
        $blog->department = $request->department ?? $blog->department;
        $blog->featured_image_url = $request->featured_image_url ?? $blog->featured_image_url;
        $blog->content = $request->content ?? $blog->content;
        $blog->is_published = $request->has('is_published') ? $request->boolean('is_published') : false;
        $blog->save();

        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog,
        ]);
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return response()->json([
            'message' => 'Blog deleted successfully',
        ]);
    }
}

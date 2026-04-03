<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('search')) {
            $query->where('heading', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('entries', 10));

        return view('admin.knowledge-hub.blogs.blogs', [
            'mode'  => 'index',
            'blogs' => $blogs,
        ]);
    }

    public function create()
    {
        // Load all blogs for the "Related Blog" dropdown
        $allBlogs = Blog::orderBy('heading')->get(['_id', 'heading']);

        return view('admin.knowledge-hub.blogs.blogs', [
            'mode'     => 'create',
            'record'   => null,
            'allBlogs' => $allBlogs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading'         => 'required|string|max:500',
            'alt_tag'         => 'nullable|string|max:255',
            'slug'            => 'nullable|string|max:255',
            'related_blog_id' => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'description' => 'nullable|string',
            'blog_comments' => 'nullable|string',
        ]);

        $data = [
            'heading'         => $request->heading,
            'alt_tag'         => $request->alt_tag,
            'slug'            => $request->slug
                                    ? Str::slug($request->slug)
                                    : Str::slug($request->heading),
            'related_blog_id' => $request->related_blog_id,
             'description'     => $request->description,
             'blog_comments'   => $request->blog_comments,
        ];

        // Store related blog title for easy display
        if ($request->related_blog_id) {
            $related = Blog::find($request->related_blog_id);
            $data['related_blog_title'] = $related?->heading;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.knowledge-hub.blogs.index')
                         ->with('success', 'Blog post created successfully.');
    }

    public function edit($id)
    {
        $record   = Blog::findOrFail($id);
        $allBlogs = Blog::where('_id', '!=', $id)
                        ->orderBy('heading')
                        ->get(['_id', 'heading']);

        return view('admin.knowledge-hub.blogs.blogs', [
            'mode'     => 'edit',
            'record'   => $record,
            'allBlogs' => $allBlogs,
        ]);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'heading'         => 'required|string|max:500',
            'alt_tag'         => 'nullable|string|max:255',
            'slug'            => 'nullable|string|max:255',
            'related_blog_id' => 'nullable|string',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'description' => 'nullable|string',
            'blog_comments' => 'nullable|string',
            
        ]);

        $data = [
            'heading'         => $request->heading,
            'alt_tag'         => $request->alt_tag,
            'slug'            => $request->slug
                                    ? Str::slug($request->slug)
                                    : Str::slug($request->heading),
            'related_blog_id' => $request->related_blog_id,
             'description'     => $request->description,
             'blog_comments'   => $request->blog_comments,
        ];

        if ($request->related_blog_id) {
            $related = Blog::find($request->related_blog_id);
            $data['related_blog_title'] = $related?->heading;
        } else {
            $data['related_blog_title'] = null;
        }

        if ($request->hasFile('image')) {
            if ($blog->image) Storage::disk('public')->delete($blog->image);
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.knowledge-hub.blogs.index')
                         ->with('success', 'Blog post updated.');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image) Storage::disk('public')->delete($blog->image);
        $blog->delete();

        return redirect()->route('admin.knowledge-hub.blogs.index')
                         ->with('success', 'Blog post deleted.');
    }
}
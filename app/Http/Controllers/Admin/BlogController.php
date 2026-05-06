<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\TranslationService;

class BlogController extends Controller
{
    public function __construct(protected TranslationService $translator) {}

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
            'description'     => 'nullable|string',
        ]);

        $data = [
            'heading'         => $request->heading,
            'alt_tag'         => $request->alt_tag,
            'slug'            => $request->slug
                                    ? Str::slug($request->slug)
                                    : Str::slug($request->heading),
            'related_blog_id' => $request->related_blog_id,
            'description'     => $request->description,
            'blog_comments'   => new \MongoDB\Model\BSONArray([]),
        ];

        if ($request->related_blog_id) {
            $related = Blog::find($request->related_blog_id);
            $data['related_blog_title'] = $related?->heading;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blogs', 'public');
        }

        $data = $this->attachTranslations($data, new Blog());

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
            'description'     => 'nullable|string',
        ]);

        $data = [
            'heading'         => $request->heading,
            'alt_tag'         => $request->alt_tag,
            'slug'            => $request->slug
                                    ? Str::slug($request->slug)
                                    : Str::slug($request->heading),
            'related_blog_id' => $request->related_blog_id,
            'description'     => $request->description,
            // blog_comments intentionally excluded — managed by its own methods
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

        $data = $this->attachTranslations($data, new Blog());

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

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $blog = Blog::findOrFail($id);

        $now = now()->toDateTimeString();

        $languages = array_keys(config('languages.available'));
$translations = [];
foreach ($languages as $locale) {
    if ($locale === 'en') continue;
    $translations[$locale] = $this->translator->translateText($request->comment, $locale, 'en');
}

$newComment = [
    'comment'    => $request->comment,
    'user_id'    => (string) auth()->id(),
    'created_at' => $now,
    'updated_at' => $now,
    'translations' => $translations,
];

        $comments   = $blog->blog_comments ?? [];
        $comments[] = $newComment;

        $blog->update(['blog_comments' => array_values($comments)]);

        return redirect()->back()->with('success', 'Comment added.');
    }

    public function updateComment(Request $request, $blogId, $commentIndex)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        $blog     = Blog::findOrFail($blogId);
        $comments = $blog->blog_comments ?? [];

        if (!isset($comments[$commentIndex])) {
            abort(404, 'Comment not found.');
        }

        $languages = array_keys(config('languages.available'));
$translations = [];
foreach ($languages as $locale) {
    if ($locale === 'en') continue;
    $translations[$locale] = $this->translator->translateText($request->comment, $locale, 'en');
}

$comments[$commentIndex]['comment']      = $request->comment;
$comments[$commentIndex]['translations'] = $translations;
$comments[$commentIndex]['updated_at']   = now()->toDateTimeString();

        $blog->update(['blog_comments' => array_values($comments)]);

        return redirect()->back()->with('success', 'Comment updated.');
    }

    public function deleteComment(Request $request, $blogId, $commentIndex)
    {
        $blog     = Blog::findOrFail($blogId);
        $comments = $blog->blog_comments ?? [];

        array_splice($comments, $commentIndex, 1);

        $blog->update(['blog_comments' => array_values($comments)]);

        return redirect()->back()->with('success', 'Comment deleted.');
    }

    private function attachTranslations(array $data, $modelInstance): array
    {
        $languages    = array_keys(config('languages.available'));
        $translatable = $modelInstance->translatable ?? [];

        foreach ($languages as $locale) {
            if ($locale === 'en') continue;

            $translated = [];
            foreach ($translatable as $field) {
                if (!empty($data[$field])) {
                    $translated[$field] = $this->translator->translateText(
                        $data[$field], $locale, 'en'
                    );
                }
            }

            if (!empty($translated)) {
                $data[$locale] = $translated;
            }
        }

        return $data;
    }
}
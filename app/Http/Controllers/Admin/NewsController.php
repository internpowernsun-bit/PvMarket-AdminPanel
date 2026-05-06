<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Services\TranslationService;

class NewsController extends Controller
{
    public function __construct(protected TranslationService $translator) {}
    public function index(Request $request)
    {
        $query = News::query();

        if ($request->filled('search')) {
            $query->where('heading', 'like', '%' . $request->search . '%');
        }

        $news = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('entries', 10));

        return view('admin.knowledge-hub.news.news', [
            'mode' => 'index',
            'news' => $news,
        ]);
    }

    public function create()
    {
        return view('admin.knowledge-hub.news.news', ['mode' => 'create']);
    }

    public function store(Request $request)
{
    $request->validate([
        'heading' => 'required|string|max:255',
        'slug'    => 'nullable|string|max:255',
        'content' => 'nullable|string',
        'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        'alt_tag' => 'nullable|string|max:255',
    ]);

    $data = [
        'heading' => $request->heading,
        'slug'    => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->heading),
        'content' => $request->content,
        'alt_tag' => $request->alt_tag,
    ];

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('news', 'public');
    }

    $data = $this->attachTranslations($data, new News()); // ← News not Event

    News::create($data);

    return redirect()->route('admin.knowledge-hub.news.index')
                     ->with('success', 'News article created successfully.');
}

    public function edit($id)
    {
        $record = News::findOrFail($id);
        return view('admin.knowledge-hub.news.news', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'heading' => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'alt_tag' => 'nullable|string|max:255',
        ]);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : Str::slug($request->heading);

        $data = [
            'heading' => $request->heading,
            'slug'    => $slug,
            'content' => $request->content,
            'alt_tag' => $request->alt_tag,
        ];

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $data = $this->attachTranslations($data, new News());

        $news->update($data);

        return redirect()->route('admin.knowledge-hub.news.index')
                         ->with('success', 'News article updated successfully.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        $news->delete();

        return redirect()->route('admin.knowledge-hub.news.index')
                         ->with('success', 'News article deleted.');
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
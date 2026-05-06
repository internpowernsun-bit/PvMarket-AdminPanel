<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubMenu;
use App\Models\MainMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\TranslationService;

class SubMenuController extends Controller
{
    public function __construct(protected TranslationService $translator) {}
    public function index(Request $request)
    {
        $query = SubMenu::query();

        if ($request->filled('search')) {
            $query->where('sub_category_name', 'like', '%' . $request->search . '%');
        }

        $subMenus = $query->orderBy('created_at', 'desc')
                          ->paginate($request->get('entries', 10));

        return view('admin.setup.sub-menu.sub-menu', [
            'mode'     => 'index',
            'subMenus' => $subMenus,
        ]);
    }

    public function create()
    {
        $mainMenus = MainMenu::orderBy('category_name')->get();

        return view('admin.setup.sub-menu.sub-menu', [
            'mode'      => 'create',
            'record'    => null,
            'mainMenus' => $mainMenus,
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'category_id'     => 'required|string',
        'items'           => 'required|array|min:1',
        'items.*.name'    => 'required|string|max:255',
        'items.*.icon'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'items.*.alt_tag' => 'nullable|string|max:255',
    ]);

    foreach ($request->items as $index => $item) {

        $iconData = [
            'size'          => 0,
            'uploaded_at'   => now()->toISOString(),
            'filename'      => '',
            'original_name' => '',
            'path'          => '',
            'url'           => '',
            'mime_type'     => '',
        ];

        if ($request->hasFile("items.{$index}.icon")) {
            $file     = $request->file("items.{$index}.icon");
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('uploads/sub-categories', $filename, 'public');

            $iconData = [
                'size'          => $file->getSize(),
                'uploaded_at'   => now()->toISOString(),
                'filename'      => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path'          => $path,
                'url'           => $path,
                'mime_type'     => $file->getMimeType(),
            ];
        }

        $data = [
            'sub_category_name'       => $item['name'],
            'category_id'             => $request->category_id,
            'slug'                    => Str::slug($item['name']),
            'sub_category_icon_image' => $iconData,
            'category_name'           => MainMenu::find($request->category_id)?->category_name ?? '',
            'icon_alt_tag'            => $item['alt_tag'] ?? Str::slug($item['name']),
            'is_hold'                 => false,
            'stock_value'             => false,
            'pallet_applicable'       => isset($item['pallet']) ? true : false,
            'container_applicable'    => isset($item['container']) ? true : false,
            'created_by'              => (string) auth()->id(),
        ];

        $data = $this->attachTranslations($data, new SubMenu());
        SubMenu::create($data);
    }

    return redirect()->route('admin.setup.sub-menus.index')
                     ->with('success', count($request->items) . ' sub category(s) created successfully.');
}

    public function edit($id)
    {
        $record    = SubMenu::findOrFail($id);
        $mainMenus = MainMenu::orderBy('category_name')->get();

        return view('admin.setup.sub-menu.sub-menu', [
            'mode'      => 'edit',
            'record'    => $record,
            'mainMenus' => $mainMenus,
        ]);
    }

    public function update(Request $request, $id)
{
    $subMenu = SubMenu::findOrFail($id);

    $request->validate([
        'sub_category_name' => 'required|string|max:255',
        'category_id'       => 'required|string',
        'slug'              => 'nullable|string|max:255',
        'alt_tag'           => 'nullable|string|max:255',
        'icon'              => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
    ]);

    $iconData = $subMenu->sub_category_icon_image ?? [
    'size'          => 0,
    'uploaded_at'   => now()->toISOString(),
    'filename'      => '',
    'original_name' => '',
    'path'          => '',
    'url'           => '',
    'mime_type'     => '',
];

if ($request->hasFile('icon')) {
    $oldPath = is_array($iconData) ? ($iconData['path'] ?? '') : '';
    if ($oldPath) Storage::disk('public')->delete($oldPath);

    $file     = $request->file('icon');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path     = $file->storeAs('uploads/sub-categories', $filename, 'public');

    $iconData = [
        'size'          => $file->getSize(),
        'uploaded_at'   => now()->toISOString(),
        'filename'      => $filename,
        'original_name' => $file->getClientOriginalName(),
        'path'          => $path,
        'url'           => $path,
        'mime_type'     => $file->getMimeType(),
    ];
}

    $data = [
        'sub_category_name'       => $request->sub_category_name,
        'category_id'             => $request->category_id,
        'slug'                    => $request->slug ?: Str::slug($request->sub_category_name),
        'sub_category_icon_image' => $iconData,
        'category_name'           => MainMenu::find($request->category_id)?->category_name ?? '',
        'icon_alt_tag'            => $request->alt_tag ?? Str::slug($request->sub_category_name),
        'pallet_applicable'       => $request->has('pallet_applicable'),    
        'container_applicable'    => $request->has('container_applicable'),
    ];

    $data = $this->attachTranslations($data, $subMenu);
    $subMenu->update($data);

    return redirect()->route('admin.setup.sub-menus.index')
                     ->with('success', 'Sub category updated successfully.');
}

    public function toggleStatus($id)
{
    $subMenu = SubMenu::findOrFail($id);
    $subMenu->update(['is_hold' => !$subMenu->is_hold]);

    return back()->with('success', 'Status updated.');
}

    public function toggleStock($id)
    {
        $subMenu = SubMenu::findOrFail($id);
        $subMenu->update(['stock_value' => !$subMenu->stock_value]);

        return back()->with('success', 'Stock value updated.');
    }

    public function destroy($id)
{
    $subMenu = SubMenu::findOrFail($id);
    $iconPath = is_array($subMenu->sub_category_icon_image)
    ? ($subMenu->sub_category_icon_image['path'] ?? '')
    : '';
if ($iconPath) Storage::disk('public')->delete($iconPath);
    $subMenu->delete();

    return redirect()->route('admin.setup.sub-menus.index')
                     ->with('success', 'Sub category deleted.');
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
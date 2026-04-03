<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MainMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MainMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = MainMenu::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('created_at', 'desc')
                       ->paginate($request->get('entries', 10));

        return view('admin.setup.main-menu.main-menu', [
            'mode'  => 'index',
            'menus' => $menus,
        ]);
    }

    public function create()
    {
        return view('admin.setup.main-menu.main-menu', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
{
    $request->validate([
        'items'             => 'required|array|min:1',
        'items.*.name'      => 'required|string|max:255',
        'items.*.icon'      => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'items.*.alt_tag'   => 'nullable|string|max:255',
    ]);

    foreach ($request->items as $index => $item) {
        $data = [
            'name'      => $item['name'],
            'alt_tag'   => $item['alt_tag'] ?? null,
            'is_active' => true,
        ];

        if ($request->hasFile("items.{$index}.icon")) {
            $data['icon'] = $request->file("items.{$index}.icon")
                                    ->store('main-menus', 'public');
        }

        MainMenu::create($data);
    }

    return redirect()->route('admin.setup.main-menus.index')
                     ->with('success', count($request->items) . ' menu(s) created successfully.');
}
    public function edit($id)
    {
        $record = MainMenu::findOrFail($id);

        return view('admin.setup.main-menu.main-menu', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $menu = MainMenu::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'icon'              => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'alt_tag'           => 'nullable|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'short_description' => 'nullable|string',
            'content'           => 'nullable|string',
        ]);

        $data = [
            'name'              => $request->name,
            'alt_tag'           => $request->alt_tag,
            'slug'              => $request->slug ?? Str::slug($request->name),
            'meta_title'        => $request->meta_title,
            'meta_description'  => $request->meta_description,
            'short_description' => $request->short_description,
            'content'           => $request->content,
        ];

        if ($request->hasFile('icon')) {
            if ($menu->icon) Storage::disk('public')->delete($menu->icon);
            $data['icon'] = $request->file('icon')->store('main-menus', 'public');
        }

        if ($request->hasFile('meta_image')) {
            if ($menu->meta_image) Storage::disk('public')->delete($menu->meta_image);
            $data['meta_image'] = $request->file('meta_image')->store('main-menus/meta', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.setup.main-menus.index')
                         ->with('success', 'Main menu updated successfully.');
    }

    public function toggleStatus($id)
    {
        $menu = MainMenu::findOrFail($id);
        $menu->update(['is_active' => !$menu->is_active]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy($id)
    {
        $menu = MainMenu::findOrFail($id);
        if ($menu->icon) Storage::disk('public')->delete($menu->icon);
        if ($menu->meta_image) Storage::disk('public')->delete($menu->meta_image);
        $menu->delete();

        return redirect()->route('admin.setup.main-menus.index')
                         ->with('success', 'Main menu deleted.');
    }
}
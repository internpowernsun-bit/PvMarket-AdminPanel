<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubMenu;
use App\Models\MainMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SubMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = SubMenu::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
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
        $mainMenus = MainMenu::orderBy('name')->get();

        return view('admin.setup.sub-menu.sub-menu', [
            'mode'      => 'create',
            'record'    => null,
            'mainMenus' => $mainMenus,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_menu_id'    => 'required|string',
            'items'           => 'required|array|min:1',
            'items.*.name'    => 'required|string|max:255',
        ]);

        $mainMenu = MainMenu::findOrFail($request->main_menu_id);

        foreach ($request->items as $item) {
            SubMenu::create([
                'name'                 => $item['name'],
                'main_menu_id'         => $request->main_menu_id,
                'main_menu_name'       => $mainMenu->name,
                'pallet_applicable'    => isset($item['pallet']) ? true : false,
                'container_applicable' => isset($item['container']) ? true : false,
                'slug'                 => Str::slug($item['name']),
                'is_active'            => true,
                'stock_value'          => false,
            ]);
        }

        return redirect()->route('admin.setup.sub-menus.index')
                         ->with('success', count($request->items) . ' sub menu(s) created successfully.');
    }

    public function edit($id)
    {
        $record    = SubMenu::findOrFail($id);
        $mainMenus = MainMenu::orderBy('name')->get();

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
            'name'              => 'required|string|max:255',
            'main_menu_id'      => 'required|string',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'short_description' => 'nullable|string',
            'content'           => 'nullable|string',
            'slug'              => 'nullable|string|max:255',
        ]);

        $mainMenu = MainMenu::findOrFail($request->main_menu_id);

        $data = [
            'name'                 => $request->name,
            'main_menu_id'         => $request->main_menu_id,
            'main_menu_name'       => $mainMenu->name,
            'pallet_applicable'    => $request->has('pallet_applicable'),
            'container_applicable' => $request->has('container_applicable'),
            'slug'                 => $request->slug ?: Str::slug($request->name),
            'meta_title'           => $request->meta_title,
            'meta_description'     => $request->meta_description,
            'short_description'    => $request->short_description,
            'content'              => $request->content,
        ];

        if ($request->hasFile('meta_image')) {
            if ($subMenu->meta_image) Storage::disk('public')->delete($subMenu->meta_image);
            $data['meta_image'] = $request->file('meta_image')->store('sub-menus/meta', 'public');
        }

        $subMenu->update($data);

        return redirect()->route('admin.setup.sub-menus.index')
                         ->with('success', 'Sub menu updated successfully.');
    }

    public function toggleStatus($id)
    {
        $subMenu = SubMenu::findOrFail($id);
        $subMenu->update(['is_active' => !$subMenu->is_active]);

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
        if ($subMenu->meta_image) Storage::disk('public')->delete($subMenu->meta_image);
        $subMenu->delete();

        return redirect()->route('admin.setup.sub-menus.index')
                         ->with('success', 'Sub menu deleted.');
    }

    
}
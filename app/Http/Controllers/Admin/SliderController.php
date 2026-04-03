<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends ResourceController
{
    protected string $model   = Slider::class;
    protected string $view    = 'admin.setup.sliders.sliders';
    protected string $route   = 'admin.setup.sliders';

    protected array $rules = [
        'name'          => 'required|string|max:255',
        'alt_tag'       => 'nullable|string|max:255',
        'redirect_link' => 'nullable|url',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ];

    protected array $fields = ['name', 'alt_tag', 'redirect_link'];

    public function store(Request $request)
    {
        $request->validate([
            'sliders'                 => 'required|array|min:1',
            'sliders.*.name'          => 'required|string|max:255',
            'sliders.*.redirect_link' => 'nullable|url',
            'sliders.*.alt_tag'       => 'nullable|string|max:255',
            'sliders.*.image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $sortStart = Slider::count();

        foreach ($request->sliders as $i => $data) {
            $row = [
                'name'          => $data['name'],
                'alt_tag'       => $data['alt_tag'] ?? null,
                'redirect_link' => $data['redirect_link'] ?? null,
                'is_active'     => true,
                'sort_order'    => $sortStart + $i + 1,
            ];
            if ($request->hasFile("sliders.{$i}.image")) {
                $row['image'] = $request->file("sliders.{$i}.image")->store('sliders', 'public');
            }
            Slider::create($row);
        }

        return redirect()->route('admin.setup.sliders.index')
                         ->with('success', 'Sliders saved.');
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $request->validate($this->rules);

        $data = $request->only(['name', 'alt_tag', 'redirect_link']);

        if ($request->hasFile('image')) {
            if ($slider->image) Storage::disk('public')->delete($slider->image);
            $data['image'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.setup.sliders.index')
                         ->with('success', 'Slider updated.');
    }

    public function toggle($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update(['is_active' => !$slider->is_active]);
        return redirect()->route('admin.setup.sliders.index');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image) Storage::disk('public')->delete($slider->image);
        $slider->delete();
        return redirect()->route('admin.setup.sliders.index')
                         ->with('success', 'Slider deleted.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('order', []) as $pos => $id) {
            Slider::where('_id', $id)->update(['sort_order' => $pos + 1]);
        }
        return response()->json(['success' => true]);
    }
}
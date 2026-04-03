<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricePromotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PricePromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = PricePromotion::query();

        if ($request->filled('search')) {
            $query->where('heading', 'like', '%' . $request->search . '%');
        }

        $promotions = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('entries', 10));

        return view('admin.knowledge-hub.price-promotions.price-promotions', [
            'mode'       => 'index',
            'promotions' => $promotions,
        ]);
    }

    public function create()
    {
        return view('admin.knowledge-hub.price-promotions.price-promotions', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading'     => 'required|string|max:500',
            'event_place' => 'nullable|string|max:255',
            'event_date'  => 'nullable|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'heading'     => $request->heading,
            'event_place' => $request->event_place,
            'event_date'  => $request->event_date,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('price-promotions', 'public');
        }

        PricePromotion::create($data);

        return redirect()->route('admin.knowledge-hub.price-promotions.index')
                         ->with('success', 'Price promotion created successfully.');
    }

    public function edit($id)
    {
        $record = PricePromotion::findOrFail($id);

        return view('admin.knowledge-hub.price-promotions.price-promotions', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $promotion = PricePromotion::findOrFail($id);

        $request->validate([
            'heading'     => 'required|string|max:500',
            'event_place' => 'nullable|string|max:255',
            'event_date'  => 'nullable|date',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = [
            'heading'     => $request->heading,
            'event_place' => $request->event_place,
            'event_date'  => $request->event_date,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if ($promotion->image) Storage::disk('public')->delete($promotion->image);
            $data['image'] = $request->file('image')->store('price-promotions', 'public');
        }

        $promotion->update($data);

        return redirect()->route('admin.knowledge-hub.price-promotions.index')
                         ->with('success', 'Price promotion updated.');
    }

    public function destroy($id)
    {
        $promotion = PricePromotion::findOrFail($id);
        if ($promotion->image) Storage::disk('public')->delete($promotion->image);
        $promotion->delete();

        return redirect()->route('admin.knowledge-hub.price-promotions.index')
                         ->with('success', 'Price promotion deleted.');
    }
}
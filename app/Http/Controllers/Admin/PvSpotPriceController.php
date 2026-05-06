<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PvSpotPrice;
use Illuminate\Http\Request;
use App\Services\TranslationService;

class PvSpotPriceController extends Controller
{
    public function __construct(protected TranslationService $translator) {}
    public function index(Request $request)
    {
        $query = PvSpotPrice::query();

        if ($request->filled('search')) {
            $query->where('heading', 'like', '%' . $request->search . '%');
        }

        $spotPrices = $query->orderBy('created_at', 'desc')
                            ->paginate($request->get('entries', 10));

        return view('admin.knowledge-hub.pv-spot-price.pv-spot-price', [
            'mode'       => 'index',
            'spotPrices' => $spotPrices,
        ]);
    }

    public function create()
    {
        return view('admin.knowledge-hub.pv-spot-price.pv-spot-price', [
            'mode'   => 'create',
            'record' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading'     => 'required|string|max:255',
            'upload_date' => 'required|date',
            'items'       => 'nullable|array',
        ]);

        $items = [];
        if ($request->has('items')) {
            foreach ($request->items as $i => $row) {
                if (!empty($row['item'])) {
                    $items[] = [
                        'item'     => $row['item'],
                        'high'     => $row['high']     ?? null,
                        'low'      => $row['low']      ?? null,
                        'average'  => $row['average']  ?? null,
                        'change'   => $row['change']   ?? null,
                        'ordering' => $row['ordering'] ?? ($i + 1),
                    ];
                }
            }
        }

        PvSpotPrice::create([
            'heading'     => $request->heading,
            'upload_date' => $request->upload_date,
            'items'       => $items,
        ]);

        $data = $this->attachTranslations($data, new PvSpotPrice()); // ← ADD

    PvSpotPrice::create($data); 

        return redirect()->route('admin.knowledge-hub.pv-spot-price.index')
                         ->with('success', 'PV Spot Price created successfully.');
    }

    public function edit($id)
    {
        $record = PvSpotPrice::findOrFail($id);

        return view('admin.knowledge-hub.pv-spot-price.pv-spot-price', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
    {
        $spotPrice = PvSpotPrice::findOrFail($id);

        $request->validate([
            'heading'     => 'required|string|max:255',
            'upload_date' => 'required|date',
            'items'       => 'nullable|array',
        ]);

        $items = [];
        if ($request->has('items')) {
            foreach ($request->items as $i => $row) {
                if (!empty($row['item'])) {
                    $items[] = [
                        'item'     => $row['item'],
                        'high'     => $row['high']     ?? null,
                        'low'      => $row['low']      ?? null,
                        'average'  => $row['average']  ?? null,
                        'change'   => $row['change']   ?? null,
                        'ordering' => $row['ordering'] ?? ($i + 1),
                    ];
                }
            }
        }

        $spotPrice->update([
            'heading'     => $request->heading,
            'upload_date' => $request->upload_date,
            'items'       => $items,
        ]);

        $data = $this->attachTranslations($data, $spotPrice); 

        $spotPrice->update($data);

        return redirect()->route('admin.knowledge-hub.pv-spot-price.index')
                         ->with('success', 'PV Spot Price updated.');
    }

    public function destroy($id)
    {
        PvSpotPrice::findOrFail($id)->delete();

        return redirect()->route('admin.knowledge-hub.pv-spot-price.index')
                         ->with('success', 'PV Spot Price deleted.');
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
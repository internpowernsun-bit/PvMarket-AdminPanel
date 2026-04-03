<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $records = $query->orderBy('sort_order', 'asc')
                         ->orderBy('created_at', 'asc')
                         ->paginate($request->get('entries', 10));

        return view('admin.setup.countries.countries', [
            'mode'    => 'index',
            'records' => $records,
        ]);
    }

    public function create()
    {
        return view('admin.setup.countries.countries', ['mode' => 'create']);
    }

    public function store(Request $request)
{
     $request->validate([
        'countries'           => 'required|array|min:1',
        'countries.*.name'    => 'required|string|max:100',
        'countries.*.code'    => 'required|string|max:10',
        'countries.*.flag'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'countries.*.alt_tag' => 'nullable|string|max:100',
    ], [
        'countries.*.name.required' => 'Country name is required for all rows.',
        'countries.*.code.required' => 'Country code is required for all rows.',
    ]);

    foreach ($request->countries as $index => $item) {
        $flagPath = null;

        if ($request->hasFile("countries.$index.flag")) {
            // Upload to R2 instead of public disk
            $flagPath = $request->file("countries.$index.flag")
                                 ->storePublicly('flags', 'r2');
        }

        Country::create([
            'name'       => $item['name'],
            'code'       => $item['code'],
            'flag'       => $flagPath,
            'alt_tag'    => $item['alt_tag']  ?? null,
            'capital'    => $item['capital']  ?? null,
            'currency'   => $item['currency'] ?? null,
            'is_default' => false,
        ]);
    }

    return redirect()->route('admin.setup.countries.index')
                     ->with('success', count($request->countries) . ' country/countries added successfully.');
}

    public function edit($id)
    {
        $record = Country::findOrFail($id);
        return view('admin.setup.countries.countries', [
            'mode'   => 'edit',
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id)
{
    $country = Country::findOrFail($id);

    $request->validate([
        'name'    => 'required|string|max:100',
        'code'    => 'required|string|max:10',
        'flag'    => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        'alt_tag' => 'nullable|string|max:100',
    ]);

    $data = [
        'name'     => $request->name,
        'code'     => $request->code,
        'alt_tag'  => $request->alt_tag,
        'capital'  => $request->capital,
        'currency' => $request->currency,
    ];

    if ($request->hasFile('flag')) {
        // Delete old flag from R2
        if ($country->flag) {
            Storage::disk('r2')->delete($country->flag);
        }

        // Upload new flag to R2
        $data['flag'] = $request->file('flag')
                                 ->storePublicly('flags', 'r2');
    }

    $country->update($data);

    return redirect()->route('admin.setup.countries.index')
                     ->with('success', 'Country updated successfully.');
}

    public function destroy($id)
{
    $country = Country::findOrFail($id);

    if ($country->flag) {
        Storage::disk('r2')->delete($country->flag);  // was: Storage::disk('public')
    }

    $country->delete();

    return redirect()->route('admin.setup.countries.index')
                     ->with('success', 'Country deleted.');
}

    public function setDefault($id)
    {
        Country::where('is_default', true)->update(['is_default' => false]);
        Country::findOrFail($id)->update(['is_default' => true]);

        return redirect()->route('admin.setup.countries.index')
                         ->with('success', 'Default country updated.');
    }

    public function importFromApi()
{
\App\Jobs\ImportCountriesJob::dispatch();

    try {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => '4bf4e5168e6f4c4fd03f4c36fc7d94df43c713a9b6a69a9e9fec6cfbd19e7d10',
        ])->timeout(30)->get('https://api.countrystatecity.in/v1/countries');

        if (!$response->successful()) {
            return redirect()->route('admin.setup.countries.index')
                             ->with('error', 'Failed to fetch countries from API.');
        }

        $countries = $response->json();
        $count = 0;

        foreach ($countries as $item) {
            $iso2 = strtolower($item['iso2'] ?? '');
            $flagPath = null;

            // ── Download flag image and upload to R2 ──
            if ($iso2) {
                $flagPath = $this->downloadFlagToR2($iso2);
            }

            Country::updateOrCreate(
                ['name' => $item['name']],
                [
                    'code'     => $item['phonecode'] ?? null,
                    'iso2'     => $item['iso2']      ?? null,
                    'iso3'     => $item['iso3']      ?? null,
                    'capital'  => $item['capital']   ?? null,
                    'currency' => $item['currency']  ?? null,
                    'alt_tag'  => ($item['name'] ?? '') . ' Flag',
                    'flag'     => $flagPath,  // now stores R2 path, not iso2 code
                ]
            );

            $count++;
        }

        return redirect()->route('admin.setup.countries.index')
                     ->with('success', 'Import started! Countries will appear shortly in the background.');

    } catch (\Exception $e) {
        return redirect()->route('admin.setup.countries.index')
                         ->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

/**
 * Downloads a flag image from flagcdn.com and uploads it to R2.
 * Returns the R2 file path, or null on failure.
 */
private function downloadFlagToR2(string $iso2): ?string
{
    try {
        // flagcdn.com is a reliable free source — 64x48px PNG
        $imageUrl = "https://flagcdn.com/w80/{$iso2}.png";

        $imageResponse = Http::timeout(10)->get($imageUrl);

        if (!$imageResponse->successful()) {
            return null;
        }

        $imageContents = $imageResponse->body();
        $r2Path = "flags/{$iso2}.png";

        // Upload to R2 — 'public' visibility so it's accessible via URL
        Storage::disk('r2')->put($r2Path, $imageContents, 'public');

        return $r2Path;

    } catch (\Exception $e) {
        // Log but don't crash the whole import for one bad flag
        \Log::warning("Flag download failed for {$iso2}: " . $e->getMessage());
        return null;
    }
}
}
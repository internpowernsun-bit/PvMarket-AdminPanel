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
            'countries'                  => 'required|array|min:1',
            'countries.*.name'           => 'required|string|max:100',
            'countries.*.code'           => 'required|string|max:10',
            'countries.*.flag'           => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'countries.*.alt_tag'        => 'nullable|string|max:100',
            'countries.*.country_files'  => 'nullable|file|max:10240',
        ], [
            'countries.*.name.required' => 'Country name is required for all rows.',
            'countries.*.code.required' => 'Country code is required for all rows.',
        ]);

        foreach ($request->countries as $index => $item) {
            $flagPath = null;

            if ($request->hasFile("countries.$index.flag")) {
                $flagPath = $request->file("countries.$index.flag")
                                     ->storePublicly('flags', 'r2');
            }

            $countryFiles = $this->emptyFileData();

            if ($request->hasFile("countries.$index.country_files")) {
                $countryFiles = $this->buildFileData(
                    $request->file("countries.$index.country_files"),
                    'country-files'
                );
            }

            Country::create([
                'name'          => $item['name'],
                'code'          => $item['code'],
                'flag'          => $flagPath,
                'alt_tag'       => $item['alt_tag']  ?? null,
                'capital'       => $item['capital']  ?? null,
                'currency'      => $item['currency'] ?? null,
                'is_default'    => false,
                'country_files' => $countryFiles,
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
            'name'          => 'required|string|max:100',
            'code'          => 'required|string|max:10',
            'flag'          => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'alt_tag'       => 'nullable|string|max:100',
            'country_files' => 'nullable|file|max:10240',
        ]);

        $data = [
            'name'     => $request->name,
            'code'     => $request->code,
            'alt_tag'  => $request->alt_tag,
            'capital'  => $request->capital,
            'currency' => $request->currency,
        ];

        // Handle flag
        if ($request->hasFile('flag')) {
            if ($country->flag) {
                Storage::disk('r2')->delete($country->flag);
            }
            $data['flag'] = $request->file('flag')->storePublicly('flags', 'r2');
        }

        // Handle country_files
        $fileData = $country->country_files ?? $this->emptyFileData();

        if ($request->hasFile('country_files')) {
            // Delete old file if exists
            $oldPath = $country->country_files['path'] ?? null;
            if ($oldPath) Storage::disk('public')->delete($oldPath);

            $fileData = $this->buildFileData($request->file('country_files'), 'country-files');
        }

        $data['country_files'] = $fileData;

        $country->update($data);

        return redirect()->route('admin.setup.countries.index')
                         ->with('success', 'Country updated successfully.');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        if ($country->flag) {
            Storage::disk('r2')->delete($country->flag);
        }

        $oldPath = $country->country_files['path'] ?? null;
        if ($oldPath) Storage::disk('public')->delete($oldPath);

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

                if ($iso2) {
                    $flagPath = $this->downloadFlagToR2($iso2);
                }

                Country::updateOrCreate(
                    ['name' => $item['name']],
                    [
                        'code'          => $item['phonecode'] ?? null,
                        'iso2'          => $item['iso2']      ?? null,
                        'iso3'          => $item['iso3']      ?? null,
                        'capital'       => $item['capital']   ?? null,
                        'currency'      => $item['currency']  ?? null,
                        'alt_tag'       => ($item['name'] ?? '') . ' Flag',
                        'flag'          => $flagPath,
                        'country_files' => $this->emptyFileData(),
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

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function emptyFileData(): array
    {
        return [
            'size'          => 0,
            'uploaded_at'   => now()->toISOString(),
            'filename'      => '',
            'original_name' => '',
            'path'          => '',
            'url'           => '',
            'mime_type'     => '',
        ];
    }

    private function buildFileData($file, string $folder): array
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $path     = $file->storeAs($folder, $filename, 'public');

        return [
            'size'          => $file->getSize(),
            'uploaded_at'   => now()->toISOString(),
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path'          => $path,
            'url'           => $path,
            'mime_type'     => $file->getMimeType(),
        ];
    }

    private function downloadFlagToR2(string $iso2): ?string
    {
        try {
            $imageUrl      = "https://flagcdn.com/w80/{$iso2}.png";
            $imageResponse = Http::timeout(10)->get($imageUrl);

            if (!$imageResponse->successful()) return null;

            $r2Path = "flags/{$iso2}.png";
            Storage::disk('r2')->put($r2Path, $imageResponse->body(), 'public');

            return $r2Path;

        } catch (\Exception $e) {
            \Log::warning("Flag download failed for {$iso2}: " . $e->getMessage());
            return null;
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Models\PageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageSectionController extends Controller
{
    private array $pages = [
        'home'        => 'Homepage',
        'about'       => 'About Us',
        'contact'     => 'Contact Us',
        'terms'       => 'Terms & Conditions',
        'delivery'    => 'Delivery & Return Policy',
        'disclaimer'  => 'Disclaimer',
        'privacy'     => 'Privacy Policy',
        'faq'         => 'FAQ',
    ];

    public function index()
    {
        return view('admin.page-sections.index', [
            'pages' => $this->pages,
        ]);
    }

    public function edit(string $page)
    {
        abort_unless(array_key_exists($page, $this->pages), 404);

        $sections = PageSection::where('page', $page)
                               ->orderBy('order')
                               ->get();

        $setting = PageSetting::firstOrNew(['page' => $page]);

        return view('admin.page-sections.edit', [
            'page'      => $page,
            'pageLabel' => $this->pages[$page],
            'sections'  => $sections,
            'setting'   => $setting,
        ]);
    }

    public function update(Request $request, string $page)
    {
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionId => $data) {

                $section = PageSection::find($sectionId);
                if (!$section) continue;

                // ── Build extra, starting from what's already stored ──────────
                $extra = is_array($section->extra) ? $section->extra : [];

                if (isset($data['extra'])) {
                    $incoming = is_string($data['extra'])
                        ? json_decode($data['extra'], true)
                        : $data['extra'];

                    // ── Handle logo file uploads inside extra.items ───────────
                    if (isset($incoming['items']) && is_array($incoming['items'])) {
                        foreach ($incoming['items'] as $i => $item) {

                            // Check for a new uploaded file for this logo slot
                            $fileKey = "sections.{$sectionId}.extra.items.{$i}.logo_file";
                            if ($request->hasFile($fileKey)) {
                                // Delete old logo if one existed
                                $oldLogo = $extra['items'][$i]['logo'] ?? null;
                                if ($oldLogo) {
                                    Storage::disk('public')->delete($oldLogo);
                                }
                                // Store new file
                                $incoming['items'][$i]['logo'] = $request
                                    ->file($fileKey)
                                    ->store("logos/{$page}", 'public');
                            }

                            // Strip the transient logo_file key — we don't persist it
                            unset($incoming['items'][$i]['logo_file']);
                        }
                    }

                    $extra = array_merge($extra, $incoming);
                }

                // ── Build the rest of the update payload ─────────────────────
                $update = [
                    'title'       => $data['title']       ?? $section->title,
                    'subtitle'    => $data['subtitle']    ?? null,
                    'description' => $data['description'] ?? null,
                    'button_text' => $data['button_text'] ?? null,
                    'button_link' => $data['button_link'] ?? null,
                    'alt_tag'     => $data['alt_tag']     ?? null,
                    'is_active'   => isset($data['is_active']),
                    'extra'       => $extra,
                ];

                // ── Handle the single section-level image (non-logos) ─────────
                if ($request->hasFile("sections.{$sectionId}.image")) {
                    if ($section->image) {
                        Storage::disk('public')->delete($section->image);
                    }
                    $update['image'] = $request
                        ->file("sections.{$sectionId}.image")
                        ->store("sections/{$page}", 'public');
                }

                $section->update($update);
            }
        }

        // ── SEO settings ─────────────────────────────────────────────────────
        PageSetting::updateOrCreate(
            ['page' => $page],
            [
                'seo_title'       => $request->seo_title,
                'seo_description' => $request->seo_description,
                'seo_keywords'    => $request->seo_keywords,
                'is_published'    => $request->boolean('is_published'),
            ]
        );

        return redirect()->route('admin.page-sections.edit', $page)
                         ->with('success', $this->pages[$page] . ' updated successfully.');
    }
}
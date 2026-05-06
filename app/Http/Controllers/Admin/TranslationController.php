<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function __construct(private TranslationService $service)
    {}

    // ── Step 1: Just save language to session and return ─────────
    // The frontend then calls /translate-all once per model.
    // This way each request is small and never hits the 30s timeout.
    public function switchLanguage(Request $request)
    {
        $request->validate(['lang' => 'required|string|max:5']);

        $lang       = $request->lang;
        $validCodes = array_merge(['en'], array_keys(TranslationService::$languages));

        if (!in_array($lang, $validCodes)) {
            return response()->json(['success' => false, 'error' => 'Unknown language code.'], 422);
        }

        session(['admin_lang' => $lang]);

        return response()->json([
            'success' => true,
            'lang'    => $lang,
            'rtl'     => TranslationService::$languages[$lang]['rtl'] ?? false,
            // Tell frontend which models to translate (only if not English)
            'models'  => $lang !== 'en' ? array_keys(TranslationService::$modelMap) : [],
        ]);
    }

    // ── Step 2: Translate ONE model per request (called in a loop) ─
    // Each call handles one model → stays well under 30s
    public function translateAll(Request $request)
    {
        // Bump execution time for this request only
        set_time_limit(120);

        $request->validate([
            'model'       => 'required|string',
            'target_lang' => 'required|string',
        ]);

        if (!isset(TranslationService::$modelMap[$request->model])) {
            return response()->json(['success' => false, 'error' => 'Unknown model: ' . $request->model], 422);
        }

        try {
            $result = $this->service->translateAllRecords($request->model, $request->target_lang);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ── Translate a single record by ID ───────────────────────────
    public function translateRecord(Request $request)
    {
        set_time_limit(120);

        $request->validate([
            'model'       => 'required|string',
            'model_id'    => 'required|string',
            'target_lang' => 'required|string',
        ]);

        if (!isset(TranslationService::$modelMap[$request->model])) {
            return response()->json(['success' => false, 'error' => 'Unknown model.'], 422);
        }

        [$modelClass, $fields] = TranslationService::$modelMap[$request->model];
        $record = $modelClass::find($request->model_id);

        if (!$record) {
            return response()->json(['success' => false, 'error' => 'Record not found.'], 404);
        }

        try {
            $result = $this->service->translateAndSave($record, $fields, $request->target_lang);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ── Legacy: translate all models in one request ───────────────
    // Kept for compatibility but avoid using — use the loop instead
    public function translateAllModels(Request $request)
    {
        set_time_limit(300);

        $request->validate(['target_lang' => 'required|string']);

        $targetLang = $request->target_lang;
        $results    = [];

        foreach (TranslationService::$modelMap as $modelKey => [$modelClass, $fields]) {
            if (!class_exists($modelClass)) continue;
            try {
                $results[$modelKey] = $this->service->translateAllRecords($modelKey, $targetLang);
            } catch (\Exception $e) {
                $results[$modelKey] = ['success' => false, 'error' => $e->getMessage()];
            }
        }

        return response()->json([
            'success'    => true,
            'results'    => $results,
            'translated' => array_sum(array_column($results, 'translated')),
            'failed'     => array_sum(array_column($results, 'failed')),
        ]);
    }
}
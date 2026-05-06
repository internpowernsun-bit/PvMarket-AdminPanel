<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * The path to the languages config file.
     */
    protected string $configPath;

    public function __construct()
    {
        $this->configPath = config_path('languages.php');
    }

    /**
     * Display the language management page.
     */
    public function index()
    {
        $config    = config('languages');
        $available = $config['available'] ?? [];
        $rtl       = $config['rtl'] ?? [];
        $default   = $config['default'] ?? 'en';

        // Full ISO list for the "add language" dropdown
        $isoLanguages = $this->isoLanguages();

        return view('admin.setup.languages.index', compact('available', 'rtl', 'default', 'isoLanguages'));
    }

    /**
     * Add a language.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:2', 'regex:/^[a-z]{2}$/'],
            'name' => ['required', 'string', 'max:60'],
            'rtl'  => ['nullable', 'boolean'],
        ]);

        $config = config('languages');
        $code   = strtolower(trim($request->code));

        if (isset($config['available'][$code])) {
            return back()->with('error', "Language code '{$code}' already exists.");
        }

        $config['available'][$code] = trim($request->name);

        if ($request->boolean('rtl')) {
            $config['rtl'][] = $code;
            $config['rtl']   = array_unique($config['rtl']);
        }

        $this->writeConfig($config);

        return back()->with('success', "Language '{$request->name}' added successfully.");
    }

    /**
     * Update an existing language (name, RTL flag).
     */
    public function update(Request $request, string $code)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'rtl'  => ['nullable', 'boolean'],
        ]);

        $config = config('languages');

        if (!isset($config['available'][$code])) {
            return back()->with('error', "Language '{$code}' not found.");
        }

        $config['available'][$code] = trim($request->name);

        // Rebuild RTL list
        $rtl = array_diff($config['rtl'] ?? [], [$code]);
        if ($request->boolean('rtl')) {
            $rtl[] = $code;
        }
        $config['rtl'] = array_values(array_unique($rtl));

        $this->writeConfig($config);

        return back()->with('success', "Language '{$code}' updated.");
    }

    /**
     * Set the default language.
     */
    public function setDefault(Request $request)
    {
        $request->validate([
            'default' => ['required', 'string', 'size:2'],
        ]);

        $config = config('languages');
        $code   = strtolower(trim($request->default));

        if (!isset($config['available'][$code])) {
            return back()->with('error', "Language '{$code}' is not in the available list.");
        }

        $config['default'] = $code;
        $this->writeConfig($config);

        return back()->with('success', "Default language set to '{$code}'.");
    }

    /**
     * Remove a language.
     */
    public function destroy(string $code)
    {
        $config = config('languages');

        if ($config['default'] === $code) {
            return back()->with('error', 'Cannot remove the default language.');
        }

        unset($config['available'][$code]);
        $config['rtl'] = array_values(array_diff($config['rtl'] ?? [], [$code]));

        $this->writeConfig($config);

        return back()->with('success', "Language '{$code}' removed.");
    }

    // ─────────────────────────────────────────────
    //  Private helpers
    // ─────────────────────────────────────────────

    /**
     * Serialize the config array and write it to disk,
     * then clear the config cache so the app picks it up immediately.
     */
    private function writeConfig(array $config): void
    {
        // Normalise RTL to a plain list
        $config['rtl'] = array_values(array_unique($config['rtl'] ?? []));

        $php  = "<?php\n\nreturn [\n";
        $php .= "    'default' => '{$config['default']}',\n\n";
        $php .= "    'rtl' => " . $this->exportArray($config['rtl'], 1) . ",\n\n";
        $php .= "    'available' => [\n";
        foreach ($config['available'] as $code => $name) {
            $escapedName = str_replace("'", "\\'", $name);
            $php .= "        '{$code}' => '{$escapedName}',\n";
        }
        $php .= "    ],\n];\n";

        file_put_contents($this->configPath, $php);

        // Flush runtime config so we don't need to restart the server
        app('config')->set('languages', $config);

        // Optionally clear the file-based config cache (artisan config:cache)
        $cached = app()->getCachedConfigPath();
        if (file_exists($cached)) {
            @unlink($cached);
        }
    }

    /**
     * Render a simple PHP array for writing to the config file.
     */
    private function exportArray(array $arr, int $depth): string
    {
        if (empty($arr)) {
            return '[]';
        }

        $pad  = str_repeat('    ', $depth);
        $pad2 = str_repeat('    ', $depth + 1);
        $out  = "[\n";
        foreach ($arr as $value) {
            $out .= "{$pad2}'" . str_replace("'", "\\'", $value) . "',\n";
        }
        $out .= "{$pad}]";

        return $out;
    }

    /**
     * Common ISO 639-1 language list for the "add" dropdown.
     * Extend as needed.
     */
    private function isoLanguages(): array
    {
        return [
            'af' => 'Afrikaans',   'sq' => 'Albanian',    'am' => 'Amharic',
            'ar' => 'Arabic',      'hy' => 'Armenian',    'az' => 'Azerbaijani',
            'eu' => 'Basque',      'be' => 'Belarusian',  'bn' => 'Bengali',
            'bs' => 'Bosnian',     'bg' => 'Bulgarian',   'ca' => 'Catalan',
            'ceb'=> 'Cebuano',     'zh' => 'Chinese',     'co' => 'Corsican',
            'hr' => 'Croatian',    'cs' => 'Czech',       'da' => 'Danish',
            'nl' => 'Dutch',       'en' => 'English',     'eo' => 'Esperanto',
            'et' => 'Estonian',    'fi' => 'Finnish',     'fr' => 'French',
            'gl' => 'Galician',    'ka' => 'Georgian',    'de' => 'German',
            'el' => 'Greek',       'gu' => 'Gujarati',    'ht' => 'Haitian Creole',
            'ha' => 'Hausa',       'he' => 'Hebrew',      'hi' => 'Hindi',
            'hu' => 'Hungarian',   'is' => 'Icelandic',   'id' => 'Indonesian',
            'ga' => 'Irish',       'it' => 'Italian',     'ja' => 'Japanese',
            'kn' => 'Kannada',     'kk' => 'Kazakh',      'km' => 'Khmer',
            'ko' => 'Korean',      'ku' => 'Kurdish',     'ky' => 'Kyrgyz',
            'lo' => 'Lao',         'la' => 'Latin',       'lv' => 'Latvian',
            'lt' => 'Lithuanian',  'lb' => 'Luxembourgish','mk' => 'Macedonian',
            'mg' => 'Malagasy',    'ms' => 'Malay',       'ml' => 'Malayalam',
            'mt' => 'Maltese',     'mi' => 'Maori',       'mr' => 'Marathi',
            'mn' => 'Mongolian',   'my' => 'Myanmar',     'ne' => 'Nepali',
            'no' => 'Norwegian',   'ny' => 'Nyanja',      'or' => 'Odia',
            'ps' => 'Pashto',      'fa' => 'Persian',     'pl' => 'Polish',
            'pt' => 'Portuguese',  'pa' => 'Punjabi',     'ro' => 'Romanian',
            'ru' => 'Russian',     'sm' => 'Samoan',      'gd' => 'Scots Gaelic',
            'sr' => 'Serbian',     'st' => 'Sesotho',     'sn' => 'Shona',
            'sd' => 'Sindhi',      'si' => 'Sinhala',     'sk' => 'Slovak',
            'sl' => 'Slovenian',   'so' => 'Somali',      'es' => 'Spanish',
            'su' => 'Sundanese',   'sw' => 'Swahili',     'sv' => 'Swedish',
            'tg' => 'Tajik',       'ta' => 'Tamil',       'tt' => 'Tatar',
            'te' => 'Telugu',      'th' => 'Thai',        'tr' => 'Turkish',
            'tk' => 'Turkmen',     'uk' => 'Ukrainian',   'ur' => 'Urdu',
            'ug' => 'Uyghur',      'uz' => 'Uzbek',       'vi' => 'Vietnamese',
            'cy' => 'Welsh',       'xh' => 'Xhosa',       'yi' => 'Yiddish',
            'yo' => 'Yoruba',      'zu' => 'Zulu',
        ];
    }
}
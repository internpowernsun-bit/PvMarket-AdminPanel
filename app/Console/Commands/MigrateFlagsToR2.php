<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class MigrateFlagsToR2 extends Command
{
    protected $signature   = 'flags:migrate-to-r2';
    protected $description = 'Download existing flags from CDN and upload to Cloudflare R2';

    public function handle()
    {
        $countries = Country::whereNotNull('flag')->get();
        $this->info("Found {$countries->count()} countries to migrate...");
        $bar = $this->output->createProgressBar($countries->count());

        foreach ($countries as $country) {
            $iso2 = strtolower($country->flag);

            // Skip if already migrated (path contains a slash like "flags/ae.png")
            if (str_contains($country->flag, '/')) {
                $this->line(" Skipping {$country->name} — already migrated.");
                $bar->advance();
                continue;
            }

            try {
                $imageResponse = Http::timeout(10)->get("https://flagcdn.com/w80/{$iso2}.png");

                if ($imageResponse->successful()) {
                    $r2Path = "flags/{$iso2}.png";
                    Storage::disk('r2')->put($r2Path, $imageResponse->body(), 'public');
                    $country->update(['flag' => $r2Path]);
                } else {
                    $this->warn(" Could not download flag for {$country->name} ({$iso2})");
                }
            } catch (\Exception $e) {
                $this->warn(" Failed for {$country->name}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Migration complete!');
    }
}
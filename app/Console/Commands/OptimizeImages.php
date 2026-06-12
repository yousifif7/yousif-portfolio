<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--widths=280,360,480,720,960 : Comma-separated WebP widths to generate}';

    protected $description = 'Optimize uploaded images and generate WebP variants for faster delivery';

    public function handle(): int
    {
        $uploads = public_path('uploads');
        if (! is_dir($uploads)) {
            $this->warn('No uploads directory found.');

            return self::SUCCESS;
        }

        $widths = array_map('intval', explode(',', $this->option('widths')));
        $count = 0;

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploads));
        foreach ($iterator as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $ext = strtolower($file->getExtension());
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                continue;
            }

            $relative = 'uploads/'.str_replace('\\', '/', substr($file->getPathname(), strlen($uploads) + 1));
            if (str_contains($relative, '/_cache/')) {
                continue;
            }

            ImageOptimizer::optimizeOriginal($relative);
            foreach ($widths as $width) {
                ImageOptimizer::ensureVariant($relative, $width);
            }

            $count++;
            $this->line("Optimized: {$relative}");
        }

        $this->info("Done. Processed {$count} image(s).");

        return self::SUCCESS;
    }
}

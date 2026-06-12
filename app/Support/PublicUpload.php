<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PublicUpload
{
    public static function store(UploadedFile $file, string $folder): string
    {
        $folder = trim($folder, '/');
        $targetDir = public_path('uploads/'.$folder);

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = Str::random(40).'.'.strtolower($file->getClientOriginalExtension() ?: $file->extension());
        $file->move($targetDir, $filename);

        $path = 'uploads/'.$folder.'/'.$filename;

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            $maxWidth = str_contains($folder, 'covers') ? 1200 : (str_contains($folder, 'avatar') ? 800 : 1600);
            ImageOptimizer::optimizeOriginal($path, $maxWidth);
        }

        return $path;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, 'uploads/')) {
            $full = public_path($path);
            if (is_file($full)) {
                @unlink($full);
            }
            ImageOptimizer::deleteVariants($path);
        }
    }

    public static function url(?string $path, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if (str_starts_with($path, 'uploads/') || str_starts_with($path, 'brand/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }
}

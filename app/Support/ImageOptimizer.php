<?php

namespace App\Support;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageOptimizer
{
    private const CACHE_DIR = 'uploads/_cache';

    public static function isOptimizable(?string $path): bool
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return false;
        }

        $full = public_path($path);

        return is_file($full) && @getimagesize($full) !== false;
    }

    public static function optimizeOriginal(string $path, int $maxWidth = 1600, int $quality = 82): void
    {
        if (! self::isOptimizable($path)) {
            return;
        }

        try {
            $full = public_path($path);
            $image = self::manager()->read($full);

            if ($image->width() > $maxWidth) {
                $image->scale(width: $maxWidth);
                $image->save($full, quality: $quality);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public static function srcset(?string $path, array $widths = [480, 720]): ?string
    {
        if (! $path || ! self::isOptimizable($path)) {
            return null;
        }

        $parts = [];

        foreach ($widths as $width) {
            $variant = self::ensureVariant($path, $width);
            if ($variant) {
                $parts[] = asset($variant).' '.$width.'w';
            }
        }

        return $parts ? implode(', ', $parts) : null;
    }

    public static function url(?string $path, int $width = 0, ?string $fallback = null): ?string
    {
        if (! $path) {
            return $fallback;
        }

        if ($width > 0) {
            $variant = self::ensureVariant($path, $width);
            if ($variant) {
                return asset($variant);
            }
        }

        return PublicUpload::url($path, $fallback);
    }

    public static function ensureVariant(string $path, int $width, int $quality = 80): ?string
    {
        if (! self::isOptimizable($path)) {
            return null;
        }

        $variant = self::variantPath($path, $width);
        $variantFull = public_path($variant);

        if (is_file($variantFull)) {
            return $variant;
        }

        try {
            $dir = dirname($variantFull);
            if (! is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            $image = self::manager()->read(public_path($path));
            if ($image->width() > $width) {
                $image->scale(width: $width);
            }

            $image->toWebp($quality)->save($variantFull);

            return $variant;
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    public static function deleteVariants(?string $path): void
    {
        if (! $path || ! str_starts_with($path, 'uploads/')) {
            return;
        }

        $info = pathinfo($path);
        $base = public_path(self::CACHE_DIR);
        if (! is_dir($base)) {
            return;
        }

        foreach (scandir($base) as $widthDir) {
            if ($widthDir === '.' || $widthDir === '..') {
                continue;
            }

            $file = $base.'/'.$widthDir.'/'.$info['dirname'].'/'.$info['filename'].'.webp';
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    private static function variantPath(string $path, int $width): string
    {
        $info = pathinfo($path);

        return self::CACHE_DIR.'/'.$width.'/'.$info['dirname'].'/'.$info['filename'].'.webp';
    }

    private static function manager(): ImageManager
    {
        return new ImageManager(new Driver());
    }
}

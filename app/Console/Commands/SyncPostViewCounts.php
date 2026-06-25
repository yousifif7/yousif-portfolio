<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class SyncPostViewCounts extends Command
{
    protected $signature = 'posts:sync-view-counts';

    protected $description = 'Sync blog post view counters from the post_views log table';

    public function handle(): int
    {
        $posts = Post::withCount('viewLogs as logged_unique_views')
            ->get(['id', 'title', 'views', 'unique_views']);

        if ($posts->isEmpty()) {
            $this->warn('No posts found.');

            return self::SUCCESS;
        }

        $updated = 0;

        foreach ($posts as $post) {
            $resolvedUniqueViews = (int) $post->logged_unique_views;
            $resolvedViews = max((int) $post->views, $resolvedUniqueViews);

            if ((int) $post->unique_views === $resolvedUniqueViews && (int) $post->views === $resolvedViews) {
                continue;
            }

            $post->forceFill([
                'unique_views' => $resolvedUniqueViews,
                'views' => $resolvedViews,
            ])->saveQuietly();

            $updated++;

            $this->line(sprintf(
                'Synced post #%d "%s": views=%d, unique_views=%d',
                $post->id,
                $post->title,
                $resolvedViews,
                $resolvedUniqueViews
            ));
        }

        $this->info("Done. Synced {$updated} post(s).");
        $this->comment('Note: total views are preserved when higher because repeated non-unique visits are not reconstructable from post_views.');

        return self::SUCCESS;
    }
}

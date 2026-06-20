<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $siteName }} — Blog</title>
        <link>{{ route('blog.index') }}</link>
        <description>Thoughts, advice, and technical articles.</description>
        <language>en</language>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml"/>
        @foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('blog.show', $post) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $post) }}</guid>
            @if($post->published_at)
            <pubDate>{{ $post->published_at->toRfc2822String() }}</pubDate>
            @endif
            <description><![CDATA[{{ $post->excerpt }}]]></description>
        </item>
        @endforeach
    </channel>
</rss>

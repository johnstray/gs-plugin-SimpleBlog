<?php
/**
 * SimpleBlog Plugin for GetSimple CMS
 * A simple and easy to use blog/newsfeed system designed for GetSimple CMS
 *
 * @package: gs-SimpleBlog
 * @version: 4.0.0-alpha
 * @author: John Stray <getsimple@johnstray.com>
 */

# Prevent impropper loading of this file. Must be loaded via GetSimple's plugin interface
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); }


# -----
# Individual Sections
# -----

function show_blog_post( array $post, bool|null $excerpt = null ): void {}

function show_blog_category( array $category ): void {}

function show_blog_archive( array $archive ): void {}

function show_blog_tag( array $tag ): void {}

function show_blog_search_results( array $results ): void {}


# -----
# Group Sections
# -----

function show_blog_posts( array $posts ): void {}

function show_blog_categories( bool $echo = true, bool|null $show_count = null ): void {}

function show_blog_archives( string $basis = 'monthly', bool $echo = true, bool|null $show_count ): void {}

function show_blog_tags( bool $echo = true, bool|null $show_count = null ): void {}

function show_blog_recent_posts(
    int $limit = -1,
    bool $excerpts = false,
    int $excerpt_length = -1,
    bool $thumbnails = false,
    string $read_more = '',
    bool $echo = true
): void {}

# -----
# Backwards compatibility
# - with GetSimple Blog defined functions
# -----

function blog_display_posts(): void
{
    SimpleBlog_pageContentFilter( '', true );
}

function show_all_blog_posts(): void
{
    $SimpleBlog = new SimpleBlog;
    show_blog_posts( $SimpleBlog->getAllPosts() );
}

function search_posts( string $keyphrase = '', bool $echo = true )
{
    $SimpleBlog = new SimpleBlog();
    $searchResults = $SimpleBlog->searchPosts( $keyphrase, 'all' );

    if ( $echo )
    {
        show_blog_search_results( $searchResults );
    }

    return $searchResults;
}

# -----
# Return functions
# -----

function return_blog_posts(): array {}

function return_blog_post( string $slug, $excerpt = null ): array {}

function return_blog_categories(): array {}

function return_blog_category( string $category ): array {}

function return_blog_archives( string $basis = 'monthly' ): array {}

function return_blog_archive( string $archive ): array {}

function return_blog_tags(): array {}

function return_blog_tag( string $tag ): array {}

function return_blog_recent_posts(
    int $limit = -1,
    bool $excerpts = false,
    int $excerpt_length = -1,
    bool $thumbnails = false,
    string $read_more = ''
): array {}

function return_blog_search_results( string $keyphrase, array $filters = ['all'] ): array {}

function return_all_blog_posts(): array
{
    return return_blog_posts();
}
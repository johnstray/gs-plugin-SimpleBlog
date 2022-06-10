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
# Optional arguments array to influence the display of a post or list item
#
# $args = array(
#     'excerpts' => true (enable excerpts), false (full content) - uses configured setting if not given
#     'excerpt_length' => int(300) The length of the excerpt to show, uses configured setting if not given
#     'read_more' => string('Read More') If given, enables a 'Read more' link to be shown with the given string as label
#     'thumbnails' => true (adds a thumbnail img inside a <div> at start), false (no thumbnail), default is true
#     'exclude' => array() If given, an array of post data keys to exclude from showing
#
# $args = array(
#     'show_count' => true (shows # of posts in item), false (hides the post count), uses configured settting if not given
#     'show_description' => true (adds a description line to the list item), default is false not to add a description
#     'description_length' => int(50) The length of the description to show, default is 50 chars if not given
# -----


# -----
# Individual Sections
# -----

function show_blog_post( string $post, array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $html_content = $SimpleBlog->showPost( $post, $args );
    echo $html_content;
}

function show_blog_category( string $category, array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['category'] = $category;
    $html_content = $SimpleBlog->showGroup( 'category', $args );
    echo $html_content;
}

function show_blog_archive( string $archive, array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['archive'] = $archive;
    $html_content = $SimpleBlog->showGroup( 'archive', $args );
    echo $html_content;
}

function show_blog_tag( string $tag, array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['tag'] = $tag;
    $html_content = $SimpleBlog->showGroup( 'tag', $args );
    echo $html_content;
}


# -----
# Group Sections
# -----

function show_blog_posts( array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $html_content = $SimpleBlog->showGroup( 'posts', $args );
    echo $html_content;
}

function show_blog_categories( array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $html_content = $SimpleBlog->showList( 'categories', $args );
    echo $html_content;
}

function show_blog_archives( string $basis = 'monthly', array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['basis'] = $basis;
    $html_content = $SimpleBlog->showGroup( 'archives', $args );
    echo $html_content;
}

function show_blog_tags( array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $html_content = $SimpleBlog->showGroup( 'tags', $args );
    echo $html_content;
}

function show_blog_recent_posts( int $limit = -1, string $type = 'list',  array $args = [] ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['limit'] = $limit;

    switch ( $type )
    {
        case 'full':
            $html_content = $SimpleBlog->showGroup( 'recent', $args );
            break;

        default:
            $html_content = $SimpleBlog->showList( 'recent', $args );
    }

    echo $html_content;
}

function show_blog_search_results( string $keyphrase, array $filters = ['all'], array $args = [], string $type = 'full' ): void
{
    $SimpleBlog = new SimpleBlog_FrontEnd();
    $args['keyphrase'] = $keyphrase;
    $args['filters'] = $filters;
    switch ( $type )
    {
        case 'full':
            $html_content = $SimpleBlog->showGroup( 'results', $args );
            break;

        default:
            $html_content = $SimpleBlog->showList( 'results', $args );
    }

    echo $html_content;
}

function blog_display_posts(): void
{
    # Alias function - Backwards compatibility with GetSimple Blog
    SimpleBlog_pageContentFilter( '', true );
}

function show_all_blog_posts( array $args = [] ): void
{
    # Alias function - Backwards compatibility with GetSimple Blog
    show_blog_posts( $args );
}

# -----
# Return functions
# -----

function return_blog_posts(): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getAllPosts();
}

function return_blog_post( string $slug ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getPost( $slug );
}

function return_blog_categories(): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getAllCategories();
}

function return_blog_category( string $category ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getCategory( $category );
}

function return_blog_archives( string $basis = 'monthly' ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getAllArchives( $basis );
}

function return_blog_archive( string $archive ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getArchive( $archive );
}

function return_blog_tags(): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getAllTags();
}

function return_blog_tag( string $tag ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getTag( $tag );
}

function return_blog_recent_posts( int $limit = -1 ): array
{
    $SimpleBlog = new SimpleBlog();
    return $SimpleBlog->getRecentPosts();
}

function return_blog_search_results( string $keyphrase, array $filters = ['all'] ): array
{
    $SimpleBlog = new SimpleBlog();
    $results = $SimpleBlog->searchPosts( $keyphrase, $filters );
    $result_posts = array();

    foreach ( $results as $result )
    {
        $result_posts[] = $SimpleBlog->getPost( $result );
    }

    return $result_posts;
}

function return_all_blog_posts(): array
{
    # Alias function - Backwards compatibility with GetSimple Blog
    return return_blog_posts();
}

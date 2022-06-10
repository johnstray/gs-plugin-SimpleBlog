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

class SimpleBlog_FrontEnd extends SimpleBlog
{
    /**
     * Show a post
     * Generates the HTML content to show an individual post
     *
     * @since 1.0
     * @param string $post The slug of the post to display
     * @param array $args An optional array of arguments to influence the content
     * @return string A string representation of the generated HTML content
     */
    public function showPost( string $post, array $args = [] ): string {}

    /**
     * Show a group of posts
     * Generates the HTML content to show a group list of posts
     *
     * @since 1.0
     * @param string $type The type of content to show, one of ['posts', 'recent', 'category', 'archive', 'tag', 'results']
     * @param array $args An optional array of arguments to influence the content
     * @return string A string representation of the generated HTML content
     */
    public function showGroup( string $type = 'posts', array $args = [] ): string
    {
        // Generate page start

        // Generate post group listing

        // Generate page end - include pagination?
    }

    /**
     * Show unordered list
     * Generated a HTML unordered list of a group of items. Only the <li>'s are provided, template will need provede
     * the <ul> or <ol> to add this to.
     *
     * @since 1.0
     * @param string $type The type of content to show, one of ['recent', 'categories', 'archives', 'tags' 'results']
     * @param array $args An optional array of arguments to influence the content
     * @return string A string representation of the generated HTML content
     */
    public function showList( string $type = 'recent', array $args = [] ): string
    {
        // Generate an unordered list of items
    }

}

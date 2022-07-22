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
     * Show a post - @TODO: Write this function
     * Generates the HTML content to show an individual post
     *
     * @since 1.0
     * @param string $post The slug of the post to display
     * @param array $args An optional array of arguments to influence the content
     * @return string A string representation of the generated HTML content
     */
    public function showPost( string $post, array $args = [] ): string { return ''; }

    /**
     * Show a group of posts - @TODO: Write this function
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
        return '';
    }

    /**
     * Show unordered list - @TODO: Write this function
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
        return '';
    }

    /**
     * Get page title - @TODO: Write this function
     * Generates a title for the current blog page - used for page metadata
     *
     * @since 1.0
     * @param string $type - The type of title to show
     * @param string $slug - A slug for the current page related to the $type. When $type is set to results, $slug
     *                       needs to given as a 2 part string in the format of 'filter-keyphrase'
     * @return string The generated string of title text
     */
    // @TODO: Sanitise the $slug variable as $_GET data could be passed to it and flow to the page output
    //        creating a vector for a code injection attack.
    public function getPageTitle( string $type = 'post', string $slug = '' ): string { return ''; }

    /**
     * Get page long title - @TODO: Write this function
     * Generates a title for the current blog page - used for page metadata
     *
     * @since 1.0
     * @param string $type - The type of title to show
     * @param string $slug - A slug for the current page related to the $type. When $type is set to results, $slug
     *                       needs to given as a 2 part string in the format of 'filter-keyphrase'
     * @return string The generated string of long title text
     */
    // @TODO: Sanitise the $slug variable as $_GET data could be passed to it and flow to the page output
    //        creating a vector for a code injection attack.
    public function getPageTitleLong( string $type = 'post', string $slug = '' ): string { return ''; }

    /**
     * Get page description
     * Generates a description of the current blog page - used for page metadata
     *
     * @since 1.0
     * @param string $type - The type of description to show
     * @param string $slug - A slug for the current page related to the $type. When $type is set to results, $slug
     *                       needs to given as a 2 part string in the format of 'filter-keyphrase'
     * @return string The generated string of description text
     */
    public function getPageDescription( string $type = 'post', string $slug = '', bool $force = false ): string
    {
        // @TODO: Sanitise the $slug variable as $_GET data could be passed to it and flow to the page output
        //        creating a vector for a code injection attack.

        if ( empty($slug) ) { return ''; } // If empty slug, return empty description

        switch ( $type ) {
            case 'post':
                // Get data of the post
                $post = $this->getPost($slug);

                // Return an excerpt of its content
                return $this->generateExcerpt( $post['content'] );
                break;

            case 'category':
                // Check if enabled in settings or forced
                if ( $this->getSetting('categoriesdescshow') == 'yes' || $force === true )
                {
                    // Get description from settings and Category Name from file
                    $description = $this->getSetting('categoriesdesc');
                    $category = $this->getCategory($slug);

                    // Replace {category} with the actual category title
                    return str_replace( '{category}', $category['title'], $description );
                }
                break;

            case 'archive':
                // Check if enabled in settings or forced
                if ( $this->getSetting('archivesdescshow') == 'yes' || $force === true )
                {
                    // Get description from settings and Archive Name from file
                    $description = $this->getSetting('archivesdesc');
                    $archive = $this->getArchive($slug);

                    // Replace {archive} with the actual archive title
                    return str_replace( '{archive}', $archive['title'], $description );
                }
                break;

            case 'tag':
                // Check if enabled in settings or forced
                if ( $this->getSetting('tagsdescshow') == 'yes' || $force === true )
                {
                    // Get description from settings
                    $description = $this->getSetting('tagsdesc');

                    // Replace {tag} with the actual tag
                    return str_replace( '{category}', $slug, $description );
                }
                break;

            case 'results':
                // Check if enabled in settings or forced
                if ( $this->getSetting('searchdescshow') == 'yes' || $force === true )
                {
                    // Get description from settings and Category Name from file
                    $description = $this->getSetting('searchdesc');
                    $slug = explode('-', $slug);

                    // Replace {filter} with the filter extracted from slug
                    $description = str_replace( '{filter}', $slug[0], $description );
                    $description = str_replace( '{keyphrase}', $slug[1], $description );
                    return $description;
                }
                break;

            default: // Default is to return an empty string
                return '';
        }
    }

    // Get RSS Feed Link - @TODO: Write/Document this function
    public function getRssFeedLink(): string { return ''; }

}

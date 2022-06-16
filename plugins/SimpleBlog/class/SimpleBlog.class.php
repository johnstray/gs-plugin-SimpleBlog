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

class SimpleBlog
{
    /** @var array $default_settings Default configuration settting for the Blog. */
    private $default_settings = array(
        'blogurl'               => 'index',
        'prettyurls'            => 'no',
        'postsperpage'          => '10',
        'recentposts'           => '5',
        'postformat'            => 'excerpt',
        'excerptlength'         => '350',
        'postcounts'            => 'yes',
        'uploaderpath'          => 'blog/',
        'rsstitle'              => '',
        'rssdescription'        => '',
        'categoriesdesc'        => '',
        'categoriesdescshow'    => 'yes',
        'archivesdesc'          => '',
        'archivesdescshow'      => 'yes',
        'tagsdesc'              => '',
        'tagsdescshow'          => 'yes',
        'searchdesc'            => '',
        'searchdescshow'        => 'yes'
    );

    /** @var array $data_paths Array of paths to required directories. */
    public $data_paths = array(
        'basedata' => GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR,
        'posts' => SBLOGDATA . 'posts' . DIRECTORY_SEPARATOR,
        'cache' => GSCACHEPATH . SBLOG . DIRECTORY_SEPARATOR
    );

    /** @var array $data_files Array of paths to required files. */
    public $data_files = array(
        'settings' => GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR . 'settings.xml',
        'categories' => GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR . 'categories.xml'
    );

    /**
     * Class Constructor
     * Prepares the SimpleBlog class for use, making sure the settings are all good.
     *
     * @since 1.0
     * @return void
     */
    public function __construct()
    {
        # Check if required constants are defined, update the class variable defaults
        if ( defined('SBLOGPATH') ) { $this->data_paths['baseplugin'] == SBLOGPATH; }
        if ( defined('SBLOGDATA') ) { $this->data_paths['basedata'] == SBLOGDATA; }
        if ( defined('SBLOGPOSTSPATH') ) { $this->data_paths['posts'] == SBLOGPOSTSPATH; }
        if ( defined('SBLOGCACHEPATH') ) { $this->data_paths['cache'] == SBLOGCACHEPATH; }
        if ( defined('SBLOGTEMPLATES') ) { $this->data_paths['templates'] == SBLOGTEMPLATES; }
        if ( defined('SBLOGSETTINGS') ) { $this->data_files['settings'] == SBLOGSETTINGS; }
        if ( defined('SBLOGCATEGORIES') ) { $this->data_files['categories'] == SBLOGCATEGORIES; }

        # Check if required directories exist and are writeable, create them if not
        foreach ( $this->data_paths as $data_path )
        {
            if ( file_exists($data_path) )
            {
                if ( is_writeable($data_path) === false )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/DIRECTORY_NOT_WRITABLE') . $data_path, 'error', false );
                    SimpleBlog_debugLog( "Required directory is not writeable - is_writeable (false): " . $data_path, 'error' );
                    /** @TODO: Consider attempting to make this writeable before returning error - chmod? */
                }
            }
            else
            {
                if ( mkdir($data_path) === false )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/CANT_CREATE_DIRECTORY') . $data_path, 'error', false );
                    SimpleBlog_debugLog( "Couldn't create required directory - mkdir (false): " . $data_path, 'error' );
                }

                if ( getDef('GSDOCHMOD', true) !== false )
                {
                    if ( defined('GSCHMOD') )
                    {
                        /** @TODO: Handle this as error if it returns false */
                        @chmod( $data_path, defined('GSCHMOD') ? GSCHMOD : 0755 );
                    }
                }
            }
        }

        # Check if settings.xml file exists, create it if not
        if ( file_exists($this->data_files['settings']) === false )
        {
            if ( $this->saveSettings($this->default_settings) === false )
            {
                SimpleBlog_displayMessage( i18n_r(SBLOG . '/CREATE_SETTINGS_FAILED'), 'error', false );
                SimpleBlog_debugLog( "Couldn't create the settings file - saveSettings (false)", 'error' );
            }
        }
        else
        {
            $saved_settings = $this->getAllSettings();
            $update_settings = false;

            # Check for missing settings in file
            $missing_settings = array_diff_key( $this->default_settings, $saved_settings );
            if ( count($missing_settings) > 0 )
            {
                foreach ( $missing_settings as $missing_key => $missing_value )
                {
                    $saved_settings[$missing_key] = $missing_value;
                    SimpleBlog_debugLog( "Added missing setting: " . $missing_key . ' = ' . $missing_value, 'info' );
                    $update_settings = true;
                }
            }

            # Check for redundant settings in file
            foreach ( $saved_settings as $saved_key => $saved_value )
            {
                if ( array_key_exists( $saved_key, $this->default_settings ) === false )
                {
                    unset( $saved_settings[$saved_key] );
                    SimpleBlog_debugLog( "Removed redundant setting: " . $saved_key . ' = ' . $saved_value, 'info' );
                    $update_settings = true;
                }
            }

            # Update settings file if required
            if ( $update_settings === true )
            {
                if ( $this-saveSettings($saved_settings) )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/SETTINGS_UPDATE_OK'), 'info', false );
                    SimpleBlog_debugLog( "Successfully updated the settings file - saveSettings (true)", 'info' );
                }
                else
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/SETTINGS_UPDATE_FAILED'), 'error', false );
                    SimpleBlog_debugLog( "Failed to update the settings file - saveSettings (false)", 'error' );
                }
            }
        }

        # Check if categories.xml file exists, create it if not
        if ( file_exists($this->data_files['categories']) === false )
        {
            $categories_xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><categories/>');

            if ( XMLsave($categories_xml, $this->data_files['categories']) === false )
            {
                SimpleBlog_displayMessage( i18n_r(SBLOG . '/CREATE_CATEGORIES_FAILED'), 'error', false );
                SimpleBlog_debugLog( "Couldn't create the categories file - XMLsave (false)", 'error' );
            }
        }
    }


    # -----
    # Settings
    # -----

    /**
     * Get all Settings
     * Gets all the configuration settings from the settings.xml file and returns them as an array
     *
     * @since 1.0
     * @return array An array containg all settings as key=>value pairs
     */
    public function getAllSettings(): array
    {
        // Gets the array of settings
    }

    /**
     * Get setting value
     * Gets the currently configured value of a setting, default value if not set, empty if unknown
     *
     * @since 1.0
     * @param string $setting The setting key for the value required
     * @return string The value of the requested configuration setting
     */
    public function getSetting( string $setting ): string
    {
        $settings = $this->getAllSettings();

        if ( isset($settings[$setting]) )
        {
            return (string) $settings[ $setting ];
        }

        if ( isset($this->default_settings[$setting]) )
        {
            return (string) $this->default_settings[$setting];
        }

        return (string) '';
    }

    /**
     * Save settings
     * Saves an array of settings to the settings.xml file. Will validate settings first to ensure they are the correct
     * type of value, and drop anything that is not an expected setting. If an incomplete array is provided, we will
     * fill it with currently configured settings or default values if none are configured
     *
     * @since 1.0
     * @param array $settings - An array of settings, can be incomplete, will be filled by finction
     * @return bool True is saved successfully, False otherwise
     */
    public function saveSettings( array $settings ): bool
    {
        // Saves the settings to file
    }


    # -----
    # Posts
    # -----

    /**
     * Get all posts
     * Returns an associative array of posts with data, will have either an excerpt or the full content depending on
     * the configured setting for this. @NOTE: Maybe return both full and excerpt?
     *
     * @since 1.0
     * @return array An associative array of posts
     */
    public function getAllPosts(): array
    {
        // Returns an associative array of posts
    }

    /**
     * Get recent posts
     * Returns and associative array of the most recent posts with data. $limit determines how many posts to get, and
     * setting this to -1 will use the configured setting
     *
     * @since 1.0
     * @param int $limit How many recent posts to return, -1 uses configured setting
     * @return array An associative array of posts with data
     */
    public function getRecentPosts( int $limit = -1 ): array
    {
        // Returns an associative array of most recent posts, length limited by $limit
        // $limit = -1 will use configured setting
    }

    /**
     * Get individual post
     * Returns an array containing all the data for the post given by $slug
     *
     * @since 1.0
     * @param string $slug The slug for the post to get data for
     * @return array An array of data for the requested post
     */
    public function getPost( string $slug ): array
    {
        // Returns an array containing all data for the given post
    }

    /**
     * Save post
     * Saves a post to file with the given array of data, then updates caches and categories.xml as required. If the
     * post already exists, it will be updated with the new data. If the $force_new parameter is given, a new post will
     * be created instead of updating, and given a new slug with an incrementing number. Will then update caches.
     *
     * NOTE: 'updated' should be passed in the array for a post thats being updated. $force_new will override
     *       this behaviour and create a new post anyway.
     *
     * @since 1.0
     * @param array $post An array of data for the post
     * @param bool $force_new Forces saving a new post instead of updating an existing one
     * @return string The post slug that was actually saved, empty on failure
     */
    public function savePost( array $post, bool $force_new = false ): string
    {
        // Saves a post to file
    }

    /**
     * Delete post
     * Deletes a post, then updates caches as required
     *
     * @since 1.0
     * @param string $slug The slug of the post to delete
     * @return bool True on success or False otherwise
     */
    public function deletePost( string $slug ): bool
    {
        // Deletes a post
    }


    # -----
    # Categories
    # -----

    /**
     * Get all categories
     * Returns an associative array of all categories and data storred in the categories.xml file
     *
     * @since 1.0
     * @return array An associative array of categories with data
     */
    public function getAllCategories(): array
    {
        // Returns an associative array of categories, eg.:
        //  $categories = array(
        //      0 => array(
        //          'slug' => 'general-category',
        //          'title' => 'General Category',
        //          'description' => 'This is a description of this category',
        //          'post-count' => 22
        //      )
        //  )
    }

    /**
     * Get individual category
     * Gets a category with is data and all posts associated with the category
     *
     * @since 1.0
     * @param string $slug The slug for the category required
     * @return array An array of data for the category, including a subarray of associated posts
     */
    public function getCategory( string $slug ): array
    {
        // Returns an array with category details and its posts, eg.:
        //  $category = array(
        //      'slug' => 'general-category',
        //      'title' => 'General Category',
        //      'description' => 'This is a description of this category',
        //      'post-count' => 22,
        //      'posts' = array(
        //          0 => 'post-1-slug',
        //          1 => 'post-2-slug',
        //          ...
        //      )
        //  )
    }

    /**
     * Save category
     * Saves a category and its data to the categories.xml file. If the category already exists, it's data will be
     * updated with the new data. If the $force new parameter is given, a new entry will be created with an incrementing
     * number at the end of the slug instead of updating.
     *
     * NOTE: 'updated' should be passed in the array when a category is being updated. This behaviour will be
     *       overridden when the $force_new parameter is given.
     *
     * @since 1.0
     * @param array $category An array of category data to save
     * @param bool $force_new Forces creating a new category instead of updating
     * @return bool True on success or False otherwise
     */
    public function saveCategory( array $category ): bool
    {
        // Saves a category to the categories file
        // Updates details if category already exists
    }

    /**
     * Delete category
     * Removes a category entry from the categories.xml file, then removes the category from all attached posts
     *
     * @since 1.0
     * @param string $slug The $slug of the Category to remove
     * @return bool True on success or False otherwise
     */
    public function deleteCategory( string $slug ): bool
    {
        // Deletes a category from the categories file
        // Removes the category from all posts that are attached
    }


    # -----
    # Archives
    # -----

    /**
     * Get all archives
     * Returns an associative array of archives with its size depending on $basis. A description for the archive will
     * be generated based on the configure SEO Settings.
     *
     * @since 1.0
     * @param string $basis Determines the size of the archive, can be one of ['daily', 'weekly', 'monthly', 'yearly']
     *                      Defualts to 'monthly' for the basis
     * @return array An associative array of categories with data
     */
    public function getAllArchives( string $basis = 'monthly' ): array
    {
        // Returns an associative array of archives, eg.:
        //  $archives = array(
        //      0 => array(
        //          'slug' => '08-2021',
        //          'title' => 'August 2021',
        //          'description' => 'This description comes from SEO Settings {archive}',
        //          'basis' => 'monthly',
        //          'post-count' => 365
        //      )
        //  )
        // Size of archive is detemined by $basis: daily, weekly, monthly, yearly
        // Slug woud be either: '24-08-2021', 'w26-2021', '08-2021', '2021'
    }

    /**
     * Get individual archive
     * Returns an array of data of an archive. The size of the archive is determined from its basis which is determined]
     * from the slug given.
     *
     * @since 1.0
     * @param string $slug The slug of the archive to return
     * @return array An array of data for the archive, including any posts for the archive
     */
    public function getArchive( string $slug ): array
    {
        // Returns an array containg details about the archive and its posts
        // Archive basis is determined from the $slug <- Refer $this->getAllArchives()
        //  $archive = (
        //      'slug' => 'august-2021',
        //      'title' => 'August 2021',
        //      'description' => 'This description comes from SEO Settings {archive}',
        //      'basis' => 'monthly',
        //      'post-count' => 365,
        //      'posts' => array(
        //          0 => 'post-1-slug',
        //          1 => 'post-2-slug',
        //          ...
        //      )
        //  )
    }


    # -----
    # Tags
    # -----

    /**
     * Get all tags
     * Returns an associative array of tags. A description for the tag will be generated based on the configured
     * SEO Settings.
     *
     * @since 1.0
     * @return array An associative array of tags with data
     */
    public function getAllTags(): array
    {
        // Returns an associative array of tags, eg.:
        //  $tags = array(
        //      0 => array(
        //          'slug' => 'mytag',
        //          'description' => 'This description comes from SEO Settings {tag}',
        //          'post-count' => 365
        //      )
        //  )
    }

    /**
     * Get individual tag
     * Returns an array of data of a tag, including the posts containing the tag
     *
     * @since 1.0
     * @param string $slug The slug of the tag to return
     * @return array An array of data for the tag, including any posts for the tag
     */
    public function getTag( string $slug ): array
    {
        // Returns an array containg details about the tag and its posts
        //  $archive = (
        //      'slug' => 'mytag',
        //      'description' => 'This description comes from SEO Settings {tag}',
        //      'post-count' => 365,
        //      'posts' => array(
        //          0 => 'post-1-slug',
        //          1 => 'post-2-slug',
        //          ...
        //      )
        //  )
    }


    # -----
    # Utilities
    # -----

    /**
     * Generate slug
     * Generates a URL safe and transliterated slug based on the given string. Characters will be converted as needed.
     *
     * @since 1.0
     * @param string $string The string to convert into a slug
     * @return string A transliterated and URL safe slug
     */
    private function generateSlug( string $string ): string
    {
        // Generates a slug based on the given title
    }

    /**
     * Generate excerpt
     * Generates an excerpt of text from the given string of content. Will try to remove all non-text data such as HTML
     * markup. The length of the excerpt can either be specified or will default to what is configured with settings.
     *
     * @since 1.0
     * @param string $content The content to generate a slug from
     * @param int $length The length of the excerpt, -1 will result in using the configured setting
     * @return string The generated excerpt of the content
     */
    public function generateExcerpt( string $content, int $length = -1 ): string
    {
        // Generates an except from the given content, removing all markup.
        // Refer: inc/template_functions.php getExcerpt()
    }

    /**
     * Generate URL
     * Generates a URL to the blog or to the given section of the blog
     *
     * @since 1.0
     * @param string $type The section of the blog to return a URL for. If empty, will return a URL to the base page.
     *                     Can be one of ['post', 'category', 'archive', 'tag']
     * @return string A URL to the blog or specified section of the blog
     */
    public function generateUrl( string $type = '' ) : string
    {
        // Generates a URL to the blog item. Can optionally be passed a section for the url
    }

    /**
     * Search Posts
     * This is a basic search function. Returns an array of posts filtered by the given keyphrase and filter type.
     *
     * @since 1.0
     * @param string $keyphrase The string to search within the $filter for
     * @param array $filter An array of filters to use, a filter can be any key within a post, such as 'title' or 'all'
     * @return array An array of post slugs matching the keyphrase and filters
     */
    public function searchPosts( string $keyphrase, $filter = ['content','title'] ): array
    {
        // Filter through the posts looking for the keyphrase in the given filter(s)
    }


    # -----
    # Cacheing
    # -----

    /**
     * Cache insert
     * Inserts a string of content into the cache with the given identifier.
     *
     * @since 1.0
     * @param string $cache_id An identifier for the cache item
     * @param string $content A string of content to store in the cache
     * @return bool True if storred successfully, False otherwise
     */
    private function cachePut( string $cache_id, string $content ): bool
    {
        // Saves something to the cache.
    }

    /**
     * Cache Get
     * Retrieves an item from the cache.
     *
     * @since 1.0
     * @param string $cache_id The identifier for the item to get from the cache
     * @return string The string content of the item returned from the cache
     */
    private function cacheGet( string $cache_id ): string
    {
        // Gets something from the cache
    }

    /**
     * Rebuild cache
     * Loops over all posts and recreates all caches. Will also check posts against the categories.xml file ensuring
     * that all posts are attached to a category that actually exists, otherwise removes the category from the post.
     *
     * @since 1.0
     * @return bool Returns True if successful, False otherwise
     */
    public function rebuildCache(): bool
    {
        // Removes all cache content and rebuilds it by looping over post data
        // Will also recheck posts against categories.xml file
    }

}
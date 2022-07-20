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
    /** @var array $default_settings Default configuration settings for the Blog. */
    private $default_settings = array(
        [ 'displaypage', 'index', 'page' ],
        [ 'prettyurls', 'no', 'yesno' ],
        [ 'postsperpage', '10', 'number' ],
        [ 'recentposts', '5', 'number' ],
        [ 'postformat', 'excerpt', 'preval', 'excerpt,fulltext'],
        [ 'excerptlength', '350', 'number' ],
        [ 'postcounts', 'yes', 'yesno' ],
        [ 'uploaderpath', 'blog/', 'uploadpath' ],
        [ 'rsstitle', '', 'text' ],
        [ 'rssdescription', '', 'text' ],
        [ 'categoriesdesc', '', 'text' ],
        [ 'categoriesdescshow', 'yes', 'yesno' ],
        [ 'archivesdesc', '', 'text' ],
        [ 'archivesdescshow', 'yes', 'yesno' ],
        [ 'tagsdesc', '', 'text' ],
        [ 'tagsdescshow', 'yes', 'yesno' ],
        [ 'searchdesc', '', 'text' ],
        [ 'searchdescshow', 'yes', 'yesno' ]
    );

    /**
     * @var array $default_setting_values Default configuration settings
     * as single dimension array. Constructor will generate.
     */
    public $default_setting_values = array();

    /** @var array $data_paths Array of paths to required directories. */
    public $data_paths = array(
        'basedata' => GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR,
        'posts' => SBLOGDATA . 'posts' . DIRECTORY_SEPARATOR,
        'cache' => GSCACHEPATH . SBLOG . DIRECTORY_SEPARATOR,
        'backups' => GSBACKUPSPATH . SBLOG . DIRECTORY_SEPARATOR
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
        if ( defined('SBLOGDATA') ) { $this->data_paths['basedata'] = SBLOGDATA; }
        if ( defined('SBLOGPOSTSPATH') ) { $this->data_paths['posts'] = SBLOGPOSTSPATH; }
        if ( defined('SBLOGCACHEPATH') ) { $this->data_paths['cache'] = SBLOGCACHEPATH; }
        if ( defined('SBLOGSETTINGS') ) { $this->data_files['settings'] = SBLOGSETTINGS; }
        if ( defined('SBLOGCATEGORIES') ) { $this->data_files['categories'] = SBLOGCATEGORIES; }

        # Generate the $default_setting_values array from default settings
        foreach ( $this->default_settings as $default_setting )
        {
            $this->default_setting_values[$default_setting[0]] = $default_setting[1];
        }

        # Check if required directories exist and are writeable, create them if not
        foreach ( $this->data_paths as $data_path_key => $data_path )
        {
            if ( file_exists($data_path) )
            {
                if ( is_writeable($data_path) === false )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/DIRECTORY_NOT_WRITABLE') . $data_path, 'error', false );
                    SimpleBlog_debugLog( __METHOD__, "Required directory is not writeable - is_writeable (false): " . $data_path, 'error' );
                    /** @TODO: Consider attempting to make this writeable before returning error - chmod? */
                }
            }
            else
            {
                if ( @mkdir($data_path) === false )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/CANT_CREATE_DIRECTORY') . $data_path, 'error', false );
                    SimpleBlog_debugLog( __METHOD__, "Couldn't create required directory - mkdir (false): " . $data_path, 'error' );
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
            if ( $this->saveSettings($this->default_setting_values) === false )
            {
                SimpleBlog_displayMessage( i18n_r(SBLOG . '/CREATE_SETTINGS_FAILED'), 'error', false );
                SimpleBlog_debugLog( __METHOD__, "Couldn't create the settings file - saveSettings (false)", 'error' );
            }
        }
        else
        {
            $saved_settings = $this->getAllSettings();
            $update_settings = false;

            # Check for missing settings in file
            $missing_settings = array_diff_key( $this->default_setting_values, $saved_settings );
            if ( count($missing_settings) > 0 )
            {
                foreach ( $missing_settings as $missing_key => $missing_value )
                {
                    $saved_settings[$missing_key] = $missing_value;
                    SimpleBlog_debugLog( __METHOD__, "Added missing setting: " . $missing_key . ' = ' . $missing_value, 'info' );
                    $update_settings = true;
                }
            }

            # Check for redundant settings in file
            foreach ( $saved_settings as $saved_key => $saved_value )
            {
                if ( array_key_exists( $saved_key, $this->default_setting_values ) === false )
                {
                    unset( $saved_settings[$saved_key] );
                    SimpleBlog_debugLog( __METHOD__, "Removed redundant setting: " . $saved_key . ' = ' . $saved_value, 'info' );
                    $update_settings = true;
                }
            }

            # Update settings file if required
            if ( $update_settings === true )
            {
                if ( $this->saveSettings($saved_settings) )
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/SETTINGS_UPDATE_OK'), 'info', false );
                    SimpleBlog_debugLog( __METHOD__, "Successfully updated the settings file - saveSettings (true)", 'info' );
                }
                else
                {
                    SimpleBlog_displayMessage( i18n_r(SBLOG . '/SETTINGS_UPDATE_FAILED'), 'error', false );
                    SimpleBlog_debugLog( __METHOD__, "Failed to update the settings file - saveSettings (false)", 'error' );
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
                SimpleBlog_debugLog( __METHOD__, "Couldn't create the categories file - XMLsave (false)", 'error' );
            }
        }
    }


    # -----
    # Settings
    # -----

    /**
     * Get all Settings
     * Gets all the configuration settings from the settings.xml file and returns them as an array.
     *
     * @since 1.0
     * @return array An array containg all settings as key=>value pairs
     */
    public function getAllSettings(): array
    {
        # Pull in the settings file and decode the XML content to array
        foreach ( getXML($this->data_files['settings']) as $setting_key => $setting_value )
        {
            $settings_array[(string) $setting_key] = (string) $setting_value;
        }

        # Return the settings array, ensuring an array is returned
        return is_array($settings_array) ? $settings_array : array();
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

        # Check if setting is set, return it's value if it is
        if ( isset($settings[$setting]) )
        {
            return (string) $settings[ $setting ];
        }

        # Check if settings is available as a default, return default value if it is
        if ( isset($this->default_settings[$setting]) )
        {
            return (string) $this->default_settings[$setting];
        }

        # Setting is unknown, return an empty string
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
        $saving_settings = array();

        # Validate the array of settings
        foreach ( $settings as $setting_key => $setting_value )
        {
            # Make sure setting key is known, else we ignore it
            if ( array_key_exists($setting_key, $this->default_setting_values) )
            {
                # Make sure the setting value is valid for this key
                if ( $this->validateSetting($setting_key, $setting_value) )
                {
                    # All is good, add to settings to be saved
                    $saving_settings[$setting_key] = $setting_value;
                }
                else
                {
                    # Setting failed validation, ignore and save empty instead
                    $saving_settings[$setting_key] = '';
                }
            }
        }

        # Check for skipped settings
        $skipped_settings = array_diff_key( $this->default_setting_values, $saving_settings );
        if ( count($skipped_settings) > 0 )
        {
            foreach ( $skipped_settings as $skipped_key => $skipped_value )
            {
                if ( $this->getSetting($skipped_key) !== '' )
                {
                    $saving_settings[$skipped_key] = $this->getSetting($skipped_key);
                }
                else
                {
                    $saving_settings[$skipped_key] = $skipped_value;
                }
            }
        }

        # Convert settings array to XML data
        $settings_xml = new SimpleXMLExtended('<?xml version="1.0" encoding="utf-8"?><settings/>');
        foreach ( $saving_settings as $saving_key => $saving_value )
        {
            # Add setting key as a new XML node
            $settings_xml_node = $settings_xml->addChild($saving_key);

            # Add setting value to the above XML node
            $settings_xml_node->addCData($saving_value);
        }

        # Save settings array to file
        if ( XMLsave($settings_xml, $this->data_files['settings']) === false )
        {
            # Saving settings to file failed, spit-out some debugging info
            SimpleBlog_debugLog( __METHOD__, "Couldn't save settings to file - XMLsave (false)", 'error' );
            return false;
        }

        # Make a backup of the settings file
        if ( copy($this->data_files['settings'], $this->data_paths['backups'] . 'settings.bak.xml') === false )
        {
            SimpleBlog_displayMessage( i18n_r(SBLOG . '/UI_CANT_CREATE_SETTINGS_BACKUP') . $data_path, 'warn', false );
            SimpleBlog_debugLog( __METHOD__, "Couldn't create backup of settings - copy[cur,bak] (false)", 'warn' );
        }

        return true;
    }

    /**
     * Restore settings
     * Restores settings configuration to its previous state using the last backup that was saved.
     *
     * @since 1.0
     * @return bool True if restored successfully, False otherwise
     */
    function restoreSettings(): bool
    {
        // Move current to temp file
        if ( file_exists($this->data_files['settings']) )
        {
            if ( copy($this->data_files['settings'], $this->data_paths['backups'] . 'settings.tmp.xml') === false )
            {
                SimpleBlog_debugLog( __METHOD__, "Couldn't restore settings from backup - copy[cur,tmp] (false)", 'error' );
                return false;
            }
        }

        // Move backup to current
        if ( copy($this->data_paths['backups'] . 'settings.bak.xml', $this->data_files['settings']) === false )
        {
            SimpleBlog_debugLog( __METHOD__, "Couldn't restore settings from backup - copy[bak,cur] (false)", 'error' );
            return false;
        }

        // Move temp file to backup
        if ( file_exists($this->data_paths['backups'] . 'settings.tmp.xml') )
        {
            if ( copy($this->data_paths['backups'] . 'settings.tmp.xml', $this->data_paths['backups'] . 'settings.bak.xml') === false )
            {
                SimpleBlog_debugLog( __METHOD__, "Couldn't restore settings from backup - copy[tmp,bak] (false)", 'error' );
                return false;
            }
            // Remove the temp file
            if ( unlink($this->data_paths['backups'] . 'settings.tmp.xml' === false )
            {
                SimpleBlog_debugLog( __METHOD__, "Restored settings from backup, but temp file not removed - unlink (false)", 'warn' );
            }
        }

        return true;
    }

    /**
     * Reset settings to defaults
     * Resets settings configuration back to the default state by removing the current settings configuration file. When
     * the class is next instantiated, a default settings configuration file will be created.
     *
     * @since 1.0
     * @return bool True if restored successfully, False otherwise
     */
    function restoreSettings(): bool
    {
        // Delete the current setting file
        if ( unlink($this->data_paths['backups'] . 'settings.tmp.xml' === false )
        {
            SimpleBlog_debugLog( __METHOD__, "Failed to reset settings to default - unlink (false)", 'error' );
            return false;
        }

        return true;
    }


    # -----
    # Posts
    # -----

    /**
     * Get all posts
     * Returns an array of post slugs - getPost can be used in a loop over this array to get the details for all posts.
     *
     * @since 1.0
     * @return array An array of post slugs
     */
    public function getAllPosts(): array
    {
        # Get a list of files in the posts directory and trim the '.xml' from them.
        $post_files = getXmlFiles( $this->data_paths['posts'] );
        $post_slugs = array_map( function($str) { return substr($str, 0, -4); }, $post_files );

        return $post_slugs;
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
        return array();
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
        $post_data = array();

        if ( file_exists($this->data_paths['posts'] . $slug . '.xml') )
        {
            $post_xml = getXML( $this->data_paths['posts'] . $slug . '.xml' );

            foreach ( $post_xml as $post_key => $post_value )
            {
                $post_data[(string) $post_key] = (string) $post_value;
            }
        }

        // Returns an array containing all data for the given post - empty if file does not exist
        return $post_data;
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
        return '';
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
        return false;
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
        return array();
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
        return array();
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
        return false;
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
        return false;
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
        return array();
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
        return array();
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
        return array();
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
        return array();
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
        return '';
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
        return '';
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
    public function generateUrl( string $type = '' ): string
    {
        // Generates a URL to the blog item. Can optionally be passed a section for the url
        return '';
    }

    public function getPageTitle(): string { return ''; }

    public function getPageDescription(): string { return ''; }

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
        return array();
    }

    public function validateSetting( string $setting_key, string $setting_value ): bool
    {
        foreach ( $this->default_settings as $setting )
        {
            if ( $setting_key === $setting[0] )
            {
                switch ( $setting[2] )
                {
                    case 'yesno':
                        if ( $setting_value === 'yes' || $setting_value === 'no' ) { return true; }
                        break;

                    case 'number':
                        if ( ctype_digit($setting_value) ) { return true; }
                        break;

                    case 'text':
                        if ( ctype_print($setting_value) || $setting_value === "" ) { return true; }
                        break;

                    case 'preval':
                        $possible_values = explode(',', $setting[3]);
                        if ( in_array(strtolower($setting_value), $possible_values) ) { return true; }
                        break;

                    case 'uploadpath':
                        $upload_path = GSDATAUPLOADPATH . $setting_value;
                        if ( file_exists($upload_path) && is_writable($upload_path) ) { return true; }
                        break;

                    case 'page':
                        $available_pages = get_available_pages();
                        foreach ( $available_pages as $available_page )
                        {
                            if ( $setting_value === $available_page['slug'] ) { return true; }
                        }
                        break;
                }
            }
        }

var_dump('Failed: ' . $setting_key .' = '. $setting_value);
        return false;
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
        return false;
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
        return '';
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
        return false;
    }

}
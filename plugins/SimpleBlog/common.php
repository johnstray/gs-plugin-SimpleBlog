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

/**
 * Initialize the plugin
 * Sets up default variables, registers actions, filters, styles and scripts with the system, loads in the class files
 * and brings in the frontend function mapping.
 *
 * @since 1.0
 * @return void
 */
function SimpleBlog_init(): void
{
    # We need some globals
    GLOBAL $TEMPLATE, $SITEURL;

    # Define some required constants
    define( 'SBLOGVERS', '4.0.0-alpha' );
    define( 'SBLOGDATA', GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR );
    define( 'SBLOGSETTINGS', SBLOGDATA . 'settings.xml' );
    define( 'SBLOGCATEGORIES', SBLOGDATA . 'categories.xml' );
    define( 'SBLOGPOSTSPATH', SBLOGDATA . 'posts' . DIRECTORY_SEPARATOR );
    define( 'SBLOGCACHEPATH', GSCACHEPATH . SBLOG . DIRECTORY_SEPARATOR );

    # Make sure the path to the Theme template files is set
    if ( defined('SBLOGTEMPLATES') === false ) {
        define( 'SBLOGTEMPLATES', GSTHEMESPATH . $TEMPLATE . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR );
    }

    # Tab / Sidebar Actions
    add_action( 'settings-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_SETTINGS'), 'settings'] );
    add_action( 'support-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_BLOG_HELP'), 'help'] );
    add_action( 'theme-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_DEFAULT_LAYOUTS'), 'layouts'] );
    add_action( 'nav-tab', 'createNavTab', ['blog', SBLOG, i18n_r(SBLOG . '/UI_TAB_BUTTON'), 'manage'] );
    add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_MANAGE'), 'manage'] );
    add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_CATEGORIES'), 'categories'] );
    add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_SETTINGS'), 'settings'] );
    add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_HELP'), 'help'] );

    # Hooks and Filters
    add_action( 'index-pretemplate', 'SimpleBlog_setPageTitle' );       // Set page title
    add_action( 'index-pretemplate', 'SimpleBlog_setPageDescription' ); // Sets the page Meta Description
    add_action( 'theme-header', 'SimpleBlog_rssFeedLink' );             // Add RSS link to site header
    add_filter( 'content', 'SimpleBlog_pageContentFilter' );            // Replaces the page content with blog content

    # Register / Queue Stylesheets
    register_style( SBLOG . '_css', $SITEURL . '/plugins/' . SBLOG . '/includes/styles/admin_styles.css', '1.0', 'screen' );
    queue_style( SBLOG . '_css', GSBACK );

    # Register / Queue Scripts
    register_script( 'table_paging', $SITEURL . 'plugins/' . SBLOG . '/includes/scripts/paging.js', '1.0', false);
    register_script( 'image_upload', $SITEURL . 'plugins/' . SBLOG . '/includes/scripts/image_upload.js', '1.0.0', true);
    register_script( 'stupidTable', $SITEURL . 'plugins/' . SBLOG . '/includes/scripts/stupidTable.js', '1.0.2', false);
    queue_script('table_paging', GSBACK);
    queue_script('image_upload', GSBACK);
    queue_script('stupidTable', GSBACK);

    # Load in all the classes
    # - Ensuring the core SimpleBlog class is loaded before any others
    require_once( SBLOGPATH . 'class' . DIRECTORY_SEPARATOR . SBLOG . '.class.php' );
    $SimpleBlog_classFiles = glob( SBLOGPATH . 'class' . DIRECTORY_SEPARATOR . '*.class.php' );
    if ( $SimpleBlog_classFiles !== false )
    {
        foreach ( $SimpleBlog_classFiles as $SimpleBlog_classFile )
        {
            if ( $SimpleBlog_classFile !== SBLOGPATH . 'class' . DIRECTORY_SEPARATOR . SBLOG . '.class.php' )
            {
                require_once( $SimpleBlog_classFile );
            }
        }
    }

    # Function mapping for use within theme templates
    require_once( SBLOGPATH . 'frontEndFunctions.php' );
}

/**
 * Main - Admin Backend Director
 * Manages and directs what we are doing on the admin backend pages
 *
 * @since 1.0
 * @return void
 */
function SimpleBlog_main(): void
{
    # Instatiate the core class so that we can make use of it on each of these pages.
    $SimpleBlog = new SimpleBlog();

    if ( isset($_GET['categories']) )
    {
        require_once( SBLOGPATH . 'includes/html/categories.inc.php' );
    }
    elseif ( isset($_GET['settings']) )
    {
        switch ( $_GET['settings'] )
        {
            case 'seo':
                require_once( SBLOGPATH . 'includes/html/seo-settings.inc.php' );
                break;

            case 'save':
                if ( defined('GSNOCSRF') == false || GSNOCSRF == false )
                {
                    if ( check_nonce(($_POST['nonce'] ?: ''), SBLOG.'savesettings') == false )
                    {
                        die( 'CSRF detected!' );
                    }
                }
                if ( $SimpleBlog->saveSettings($_POST) )
                {
                    SimpleBlog_displayMessage( "Settings saved", 'info', true );
                }
                else
                {
                    SimpleBlog_displayMessage( "Settings not saved", 'error' );
                }
                require_once( SBLOGPATH . 'includes/html/blog-settings.inc.php' );
                break;

            case 'cancel':
                SimpleBlog_displayMessage( i18n_r(SBLOG . '/UI_SAVE_SETTINGS_CANCELED'), 'warn' );
                require_once( SBLOGPATH . 'includes/html/blog-settings.inc.php' );
                break;

            default:
                require_once( SBLOGPATH . 'includes/html/blog-settings.inc.php' );
        }
    }
    elseif ( isset($_GET['help']) )
    {
        require_once( SBLOGPATH . 'includes/html/help.inc.php' );
    }
    elseif ( isset($_GET['editor']) )
    {
        // Post editor
    }
    else
    {
        $categories = $SimpleBlog->getAllCategories();
        $posts = array();
        if ( isset($_GET['search']) && isset($_GET['filter']) )
        {
            if ( defined('GSNOCSRF') == false || GSNOCSRF == false )
            {
                if ( check_nonce(($_GET['nonce'] ?: ''), SBLOG.'filterposts') == false )
                {
                    die( 'CSRF detected!' );
                }
            }
            $posts = $SimpleBlog->searchPosts( $_GET['search'], $_GET['filter'] );
        }
        else
        {
            $posts = $SimpleBlog->getAllPosts();
        }

        if ( isset($_GET['delete']) )
        {
            if ( defined('GSNOCSRF') == false || GSNOCSRF == false )
            {
                if ( check_nonce(($_GET['nonce'] ?: ''), SBLOG.'deletepost') == false )
                {
                    die( 'CSRF detected!' );
                }
            }
            // Delete the post given in slug
        }

        require_once( SBLOGPATH . 'includes/html/manage-posts.inc.php' );
    }

    // Insert copyright footer to the bottom of the page
    echo "</div><div class=\"gs_simpleblog_ui_copyright-text\">SimpleBlog Plugin &copy; 2022 John Stray - Licensed under <a href=\"https://www.gnu.org/licenses/gpl-3.0.en.html\">GNU GPLv3</a>";
    echo "<div>If you like this plugin or have found it useful, please consider a <a href=\"https://paypal.me/JohnStray\">donation</a></div>";
}

/**
 * Set page title
 * Sets the title of the current page to the title of the current Blog page if one is showing. Will return unmodified
 * if the current page is not a Blog page.
 *
 * @since 1.0
 * @return string The (un)modified page title to show
 */
function SimpleBlog_setPageTitle(): string
{
    GLOBAL $title;
    $SimpleBlog = new SimpleBlog();
    $blogTitle = $SimpleBlog->getPageTitle();

    if ( empty($blogTitle) === false )
    {
        $title = $blogTitle;
    }

    return $title;
}

/**
 * Set page description
 * Sets the meta description of the current page to the description of the current Blog page if one is showing. Will
 * return unmodified if the current page is not a Blog page.
 *
 * @since 1.0
 * @return string The (un)modified page description to show
 */
function SimpleBlog_setPageDescription(): string
{
    GLOBAL $metad;
    $SimpleBlog = new SimpleBlog();
    $blogDescription = $SimpleBlog->getPageDescription();

    if ( empty($blogDescription) === false )
    {
        $metad = $blogDescription;
    }

    return $metad;
}

/**
 * Output RSS Feed Link
 * Spits out a link to the RSS feed and puts it in the <head> of the page. Requires theme to have 'get_header()'
 * function call in its templates
 *
 * @since 1.0
 * @param bool $echo True causes output of <link> tag, False returns just the urlencoded link
 * @return string A urlencoded link to the blog's RSS feed
 */
function SimpleBlog_rssFeedLink( bool $echo = true ): string
{
    // @TODO: Write this function to generate the link url and title
    $rss_feed_title = get_site_name(false) . i18n_r(SBLOG . '/RSS_FEED') . ': Rss Feed Title';
    $rss_feed_link = '';

    if ( $echo )
    {
        echo '<link rel="alternate" type="application/rss+xml" ' .
             'title="' . $rss_feed_title . '" ' .
             'href="' . $rss_feed_link . '" />';
    }

    return $rss_feed_link;
}

/**
 * Page content filter
 * Checks if we are on a blog page, and if true, will replace the contents of the page with the content from the
 * current blog page. Called via exec_filter('content'), but can be called directly to show the blog content manually.
 *
 * @since 1.0
 * @param string $content The content of the page to filter or replace
 * @param bool $forced If set to true, will replace content with default even if not blog page.
 *                     Useful if calling this function directly to show content manually
 * @return string The replaced or filtered content. Unmodified if not a blog page.
 */
function SimpleBlog_pageContentFilter( string $content = '', bool $forced = false ): string
{
    $SimpleBlog = new SimpleBlog();

    if ( get_page_slug(false) == $SimpleBlog->getSetting('blogurl') || $forced === true )
    {
        ob_start();

        switch( true )
        {
            case ( isset($_GET['post']) ):
                show_blog_post( $SimpleBlog->getPost($_GET['post']) );
                break;

            case ( isset($_GET['category']) ):
                show_blog_category( $SimpleBlog->getCategory($_GET['category']) );
                break;

            case ( isset($_GET['archive']) ):
                show_blog_archive( $SimpleBlog->getArchive($_GET['archive']) );
                break;

            case ( isset($_GET['tag']) ):
                show_blog_tag( $SimpleBlog->getTag($_GET['tag']) );
                break;

            case ( isset($_GET['search']) ):
                $filter = isset($_GET['filter']) ? array($_GET['filter']) : array('all');
                show_blog_search_results( $SimpleBlog->searchPosts($_GET['search'], $filter) );
                break;

            default:
                show_blog_posts( $SimpleBlog->getAllPosts() );
        }

        $content = ob_get_clean();
    }

    return $content;
}

/**
 * Display message
 * Function to display a message on the admin backend pages
 *
 * @since 1.0
 * @param string $message The message body to display
 * @param string $type The type of message to display, one of ['info', 'success', 'warn', 'error']
 * @return void
 */
function SimpleBlog_displayMessage( string $message, string $type = 'info', bool $close = true ): void
{
    if ( is_frontend() == false )
    {
        $removeit = (bool) $close ? ".removeit()" : "";
        $type = ucfirst( $type );
        if ( $close == false )
        {
            $message = $message . ' <a href="#" onclick="clearNotify();" style="float:right;">X</a>';
        }
        echo "<script>notify".$type."('".$message."')".$removeit.";</script>";
    }
}

/**
 * Debug Logging
 * Output debugging information to GetSimple's debug log when debugging enabled
 *
 * @since 1.0
 * @param string $message The text of the message to add to the log
 * @param string $type The type of message this is, could be 'ERROR', 'WARN', etc.
 * @return string The formatted message added to the debug log
 */
function SimpleBlog_debugLog( string $method, string $message, string $type = 'INFO' ): string
{
    if ( defined('GSDEBUG') && getDef('GSDEBUG', true) === true )
    {
        $debugMessage = "SimpleBlog Plugin (" . $method . ") [" . $type . "]: " . $message;
        debugLog( $debugMessage );
    }
    return $debugMessage || '';
}

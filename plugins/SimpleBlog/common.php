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

# Define some required constants
define( 'SBLOGDATA', GSDATAPATH . 'blog' . DIRECTORY_SEPARATOR );
define( 'SBLOGSETTINGS', SBLOGDATA . 'settings.xml' );
define( 'SBLOGCATEGORIES', SBLOGDATA . 'categories.xml' );
define( 'SBLOGPOSTSPATH', SBLOGDATA . 'posts' . DIRECTORY_SEPARATOR );
define( 'SBLOGCACHEPATH', GSCACHEPATH . SBLOG . DIRECTORY_SEPARATOR );

# Make sure the path to the Theme template files is set
if ( defined('SBLOGTEMPLATES') === false ) {
    define( 'SBLOGTEMPLATES', GSTHEMEPATH . $TEMPLATE . DIRECTORY_SEPARATOR . 'blog' . DIRECTORY_SEPARATOR );
}

# Tab / Sidebar Actions
add_action( 'nav-tab', 'createNavTab', ['blog', SBLOG, i18n_r(SBLOG . '/UI_TAB_BUTTON'), 'manage'] );
add_action( 'settings', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_SETTINGS'), 'settings'] );
add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_MANAGE'), 'manage'] );
add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_CATEGORIES'), 'categories'] );
add_action( 'blog-sidebar', 'createSideMenu', [SBLOG, i18n_r(SBLOG . '/UI_SIDEBAR_SETTINGS'), 'settings'] );

# Hooks and Filters
add_action( 'index-pretemplate', 'SimpleBlog_setPageTitle' );       // Set page title
add_action( 'index-pretemplate', 'SimpleBlog_setPageDescription' ); // Sets the page Meta Description
add_action( 'theme-header', 'SimpleBlog_rssFeedLink' );             // Add RSS link to site header

# Register / Queue Stylesheets
register_style( SBLOG . '_css', $SITEURL . '/plugins/' . SBLOG . '/styles/admin_styles.css', '1.0', 'screen' );
queue_style( SBLOG . '_css', GSBACK );

# Register / Queue Scripts
register_script( 'table_paging', $SITEURL . 'plugins/' . SBLOG . '/scripts/paging.js', '1.0', false);
register_script( 'image_upload', $SITEURL . 'plugins/' . SBLOG . '/scripts/image_upload.js', '1.0.0', true);
register_script( 'stupidTable', $SITEURL . 'plugins/' . SBLOG . '/scripts/stupidTable.js', '1.0.2', false);
queue_script('table_paging', GSBACK);
queue_script('image_upload', GSBACK);
queue_script('stupidTable', GSBACK);

# Function to replace page title
# - Replaces the page title to the current Blog page
function SimpleBlog_setPageTitle() : string {}

# Function to replace page description
# - Replaces the page description to the current Blog page
function SimpleBlog_setPageDescription() : string {}

# Function to generate the RSS Feed link
# - Generates an RSS Feed link to add to the theme header
function SimpleBlog_rssFeedLink() : string {} 

# Main controller function for the admin backend
function SimpleBlog_main() : void {}

# Functions for use within theme templates
require_once( SBLOGPATH . 'frontEndFunctions.php' );

# Main function for filtering the page content
# - This will capture the page content and replace it with the blog
function SimpleBlog_filter( string $content ) : string {}

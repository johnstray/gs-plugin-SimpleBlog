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

# Define the plugin identifier and base path
define( 'SBLOG', basename(__FILE__, ".php") );
define( 'SBLOGPATH', GSPLUGINPATH . DIRECTORY_SEPARATOR . SBLOG . DIRECTORY_SEPARATOR );

# Setup languages and language settings
i18n_merge( SBLOG ) || i18n_merge( SBLOG, "en_US" );

# Require the common file and initialize the plugin
require_once( SBLOGPATH . 'common.php' );
SimpleBlog_init();

# Register this plugin with the system
register_plugin(
    SBLOG,                                                      // Plugin Identifier
    i18n_r(SBLOG . '/PLUGIN_NAME'),                             // Plugin Name
    SBLOGVERS,                                                  // Plugin Version
    "John Stray",                                               // Author's Name
    i18n_r(SBLOG . '/AUTHOR_URL'),                              // Author URL
    i18n_r(SBLOG . '/PLUGIN_DESC'),                             // Plugin Description
    'blog',                                                     // Where the backend pages sit
    'SimpleBlog_main'                                           // Main backend controller function
);

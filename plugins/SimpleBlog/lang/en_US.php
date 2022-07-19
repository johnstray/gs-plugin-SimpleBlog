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

$i18n = array(
    
    # -----
    # General Info
    # -----

    'PLUGIN_NAME' => "SimpleBlog",
    'PLUGIN_DESC' => "A simple and easy to use blog/newsfeed system designed for GetSimple CMS",
    'AUTHOR_URL' => "https://johnstray.com/get-simple/plugin/gs-simpleblog/?lang=en_US",
    

    # -----
    # Tab / Sidebar Buttons
    # -----

    'UI_TAB_BUTTON' => "Blog",
    'UI_SIDEBAR_MANAGE' => "Manage Posts",
    'UI_SIDEBAR_CATEGORIES' => "Categories",
    'UI_SIDEBAR_SETTINGS' => "Blog Settings",
    'UI_SIDEBAR_HELP' => "Help Resources",
    'UI_SIDEBAR_BLOG_HELP' => "SimpleBlog Help Resources",
    'UI_SIDEBAR_DEFAULT_LAYOUTS' => "Blog Default Layouts",


    # -----
    # Settings UI
    # -----

    'UI_SETTINGS_PAGE_TITLE' => "Configuration Settings",
    'UI_SETTINGS_PAGE_INTRO' => "The blog can be configured using the below configuration settings. Some of these" .
                                "may influence how the display functions show things (More information on the Help " .
                                "Resources page). Each setting below has its own default value, and you also have the " .
                                "option to reset all.",
    'UI_MAIN_SETTINGS_BUTTON' => "Main Settings",
    'UI_MAIN_SETTINGS_BUTTON_HINT' => "Show the Main Settings page",
    'UI_SEO_SETTINGS_BUTTON' => "SEO Settings",
    'UI_SEO_SETTINGS_BUTTON_HINT' => "Show the SEO Settings page",
    'UI_REBUILD_CACHES_BUTTON' => "Rebuild Caches",
    'UI_REBUILD_CACHES_BUTTON_HINT' => "Rebuild all caches and verify post files",
    'UI_SETTINGS_DISPLAY_PAGE_LABEL' => "Page Used to Display Blog:",
    'UI_SETTINGS_DISPLAY_PAGE_HINT' => "The page that will be used by the blog to show it&apos;s content",
    'UI_SETTINGS_DISPLAY_PAGE_NONE' => "----- NONE (Blog disabled) -----",
    'UI_SETTINGS_POSTS_PER_PAGE_LABEL' => "Posts Per Page:",
    'UI_SETTINGS_POSTS_PER_PAGE_HINT' => "The number of posts to display per page",
    'UI_SETTINGS_POSTS_FORMAT_LABEL' => "Post Content Format:",
    'UI_SETTINGS_POSTS_FORMAT_HINT' => "Format of post content to show on list pages",
    'UI_SETTINGS_POSTS_FORMAT_FULL_CONTENT' => "Full Content",
    'UI_SETTINGS_POSTS_FORMAT_EXCERPT_ONLY' => "Excerpt Only",
    'UI_SETTINGS_EXCERPT_LENGTH_LABEL' => "Excerpt Length:",
    'UI_SETTINGS_EXCERPT_LENGTH_HINT' => "Number of characters shows for a post excerpt",
    'UI_SETTINGS_POSTS_COUNT_LABEL' => "Show Post Counts:",
    'UI_SETTINGS_POSTS_COUNT_HINT' => "Enable showing the number of posts in a category/archive/tag",
    'UI_SETTINGS_RECENT_POSTS_LABEL' => "Recent Posts Quantity:",
    'UI_SETTINGS_RECENT_POSTS_HINT' => "The quantity of recent posts to display",
    'UI_SETTINGS_RSS_FEED_TITLE_LABEL' => "RSS Feed Title:",
    'UI_SETTINGS_RSS_FEED_TITLE_HINT' => "The title of the RSS Feed for the blog",
    'UI_SETTINGS_RSS_FEED_DESC_LABEL' => "RSS Feed Description:",
    'UI_SETTINGS_RSS_FEED_DESC_HINT' => "A description of the RSS Feed for the blog",
    'UI_SETTINGS_UPLOADER_PATH_LABEL' => "Image Uploader Default Path:",
    'UI_SETTINGS_UPLOADER_PATH_HINT' => "Default path of the image uploader, relative to &apos;/data/uploads&apos;",

    'UI_SEO_SETTINGS_PAGE_TITLE' => "Search Engine Optimization Settings",
    'UI_SEO_SETTINGS_PAGE_INTRO' => "This page allows you to add a description to each of the function pages that " .
                                    "will then be used in the description hmtl meta tag. You can also optionally " .
                                    "enable the display of each description on their respective pages, so that they " .
                                    "can be used as the page's introduction.",
    'UI_SEO_CATEGORIES_DESC' => "Categories Description:",
    'UI_SEO_CATEGORIES_HINT' => "{category} = Name of the displayed Category",
    'UI_SEO_ARCHIVES_DESC' => "Archives Description:",
    'UI_SEO_ARCHIVES_HINT' => "{archive} = The titled basis of the Archive",
    'UI_SEO_TAGS_DESC' => "Tags Description:",
    'UI_SEO_TAGS_HINT' => "{tag} = Name of the displayed Tag",
    'UI_SEO_SEARCH_DESC' => "Search Results Description:",
    'UI_SEO_SEARCH_HINT' => "{keyphrase} = The search term, {filter} = The filter used",
    'UI_SEO_SHOW_ON_PAGE' => "Show on page?",


    # -----
    # Post Management UI
    # -----

    'UI_MANAGE_PAGE_TITLE' => "Manage Blog Posts",
    'UI_MANAGE_PAGE_INTRO' => "Edit existing posts or create new posts. The table below shows posts that currently exist.",
    'UI_NEW_POST_BUTTON' => "New Post",
    'UI_NEW_POST_BUTTON_HINT' => "Create a new post on the blog",
    'UI_FILTER_BOX_LABEL' => "Filter Posts",
    'UI_FILTER_BOX_HINT' => "Choose from the options below to filter the list of posts",
    'UI_POSTS_NO_POSTS' => "You haven&apos;t created any posts yet...",
    'UI_POSTS_NO_POSTS_HINT' => "Why not create a new post now?",
    'UI_POSTS_NO_RESULTS' => "There are no search results to show!",
    'UI_POSTS_NO_RESULTS_HINT' => "Try your search again with different parameters.",


    # -----
    # Category Management UI
    # -----

    'UI_CATEGORIES_PAGE_TITLE' => "Manage Blog Categories",
    'UI_CATEGORIES_PAGE_INTRO' => "",
    'UI_NEW_CATEGORY_BUTTON' => "New Category",
    'UI_NEW_CATEGORY_BUTTON_HINT' => "Create a new category for the blog",

    # -----
    # Layouts Management UI
    # -----

    'UI_LAYOUTS_PAGE_TITLE' => "Manage Blog Layout Templates",
    'UI_LAYOUTS_PAGE_INTRO' => "",


    # -----
    # Help Resources UI
    # -----

    'UI_HELP_PAGE_TITLE' => "SimpleBlog Help Resources",
    'UI_HELP_PAGE_INTRO' => "",


    # -----
    # General Strings
    # -----

    'RSS_FEED' => "RSS Feed",
    'DEFAULT' => "Default:",
    'CANCEL_CHANGES' => "Cancel Changes",
    'RESET_TO_DEFAULT' => "Reset to Default",
    'SETTINGS_LAST_SAVED_BY' => "Settings last saved by",
    'POST_TITLE' => "Post Title",
    'POST_AUTHOR' => "Post Author",
    'POST_CONTENT' => "Post Content",
    'CATEGORY_NAME' => "Category Name",
    'PUBLISHED_DATE' => "Published Date",
    'TAG' => "Tag",
    'ALL_FIELDS' => "All Fields",
    'SEARCH' => "Search",
    'CATEGORY' => "Category",
    'AUTHOR' => "Author",
    'PUBLISHED' => "Published",

    # -----
    # SimpleBlog Class
    # -----

    'DIRECTORY_NOT_WRITABLE' => "Required directory is not writeable: ",
    'CANT_CREATE_DIRECTORY' => "Could not create required directory: ",
    'CREATE_SETTINGS_FAILED' => "Could not create the settings file!",
    'SETTINGS_UPDATE_OK' => "Successfully updated the settings file",
    'SETTINGS_UPDATE_FAILED' => "Failed to update the settings file",
    'CREATE_CATEGORIES_FAILED' => "Could not create the categories file!",

);

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
if ( defined('IN_GS') === false ) { die( 'You cannot load this file directly!' ); } ?>

<h3 class="floated" style="float:left;"><?php i18n(SBLOG . '/UI_HELP_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">

    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(SBLOG . '/UI_HELP_PAGE_INTRO'); ?></p>
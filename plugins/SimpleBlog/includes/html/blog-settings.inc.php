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

<h3 class="floated" style="float:left;"><?php i18n(SBLOG . '/UI_SETTINGS_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo SBLOG; ?>&settings=seo" title="<?php i18n(SBLOG . '/UI_SEO_SETTINGS_BUTTON_HINT'); ?>"><?php i18n(SBLOG . '/UI_SEO_SETTINGS_BUTTON'); ?></a>
        <a href="load.php?id=<?php echo SBLOG; ?>&settings=rebuild-caches" title="<?php i18n(SBLOG . '/UI_REBUILD_CACHES_BUTTON_HINT'); ?>"><?php i18n(SBLOG . '/UI_REBUILD_CACHES_BUTTON'); ?></a>
    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(SBLOG . '/UI_SETTINGS_PAGE_INTRO'); ?></p>

<form class="largeform gs_simbleblog_ui_form" id="edit" action="load.php?id=<?php echo SBLOG; ?>&settings=save" method="post">

    <div class="leftsec">
        <label for="displaypage"><?php i18n(SBLOG . '/UI_SETTINGS_DISPLAY_PAGE_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_DISPLAY_PAGE_HINT'); ?></span>
        <select class="text" name="displaypage">
            <option value=""><?php i18n(SBLOG . '/UI_SETTINGS_DISPLAY_PAGE_NONE'); ?></option>
            <?php $available_pages = get_available_pages();
                foreach ( $available_pages as $available_page )
                {
                    $selected = ( $available_page['slug'] === $SimpleBlog->getSetting('displaypage') );
                    echo '<option value="' . $available_page['slug'] . '" ' . ($selected ? 'selected="selected"' : '') . '>';
                    echo $available_page['slug'] . ' - ' . $available_page['title'] . '</option>';
                }
            ?>
        </select>
    </div>

    <div class="rightsec">
        <label for="postsperpage"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_PER_PAGE_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_PER_PAGE_HINT'); ?></span>
        <input class="text" type="number" name="postsperpage"
            placeholder="<?php echo i18n_r(SBLOG . '/DEFAULT') . ' ' . $SimpleBlog->default_setting_values['postsperpage']; ?>"
            value="<?php echo $SimpleBlog->getSetting('postsperpage'); ?>" />
    </div>

    <div class="leftsec">
        <label for="postformat"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_FORMAT_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_FORMAT_HINT'); ?></span>
        <span class="radio">
            <input name="postformat" type="radio" value="fulltext" style="vertical-align: middle;"
                <?php if ( $SimpleBlog->getSetting('postformat') == 'fulltext' ) echo 'checked="checked"'; ?> />
            &nbsp; <?php i18n(SBLOG . '/UI_SETTINGS_POSTS_FORMAT_FULL_CONTENT'); ?>
        </span>
        <span class="radio">
            <input name="postformat" type="radio" value="excerpt" style="vertical-align: middle;"
                <?php if ( $SimpleBlog->getSetting('postformat') == 'excerpt' ) echo 'checked="checked"'; ?> />
            &nbsp; <?php i18n(SBLOG . '/UI_SETTINGS_POSTS_FORMAT_EXCERPT_ONLY'); ?>
        </span>
    </div>

    <div class="rightsec">
        <label for="excerptlength"><?php i18n(SBLOG . '/UI_SETTINGS_EXCERPT_LENGTH_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_EXCERPT_LENGTH_HINT'); ?></span>
        <input class="text" type="number" name="excerptlength"
            placeholder="<?php echo i18n_r(SBLOG . '/DEFAULT') . ' ' . $SimpleBlog->default_setting_values['excerptlength']; ?>"
            value="<?php echo $SimpleBlog->getSetting('excerptlength'); ?>" />
    </div>

    <div class="leftsec">
        <label for="postcounts"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_COUNT_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_POSTS_COUNT_HINT'); ?></span>
        <span class="radio">
            <input name="postcounts" type="radio" value="yes" style="vertical-align: middle;"
                <?php if ( $SimpleBlog->getSetting('postcounts') == 'yes' ) echo 'checked="checked"'; ?> />
            &nbsp; <?php i18n('YES'); ?>
        </span>
        <span class="radio">
            <input name="postcounts" type="radio" value="no" style="vertical-align: middle;"
                <?php if ( $SimpleBlog->getSetting('postcounts') == 'no' ) echo 'checked="checked"'; ?> />
            &nbsp; <?php i18n('NO'); ?>
        </span>
    </div>

    <div class="rightsec">
        <label for="recentposts"><?php i18n(SBLOG . '/UI_SETTINGS_RECENT_POSTS_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_RECENT_POSTS_HINT'); ?></span>
        <input class="text" type="number" name="recentposts"
            placeholder="<?php echo i18n_r(SBLOG . '/DEFAULT') . ' ' . $SimpleBlog->default_setting_values['recentposts']; ?>"
            value="<?php echo $SimpleBlog->getSetting('recentposts'); ?>" />
    </div>

    <div class="leftsec">
        <label for="uploaderpath"><?php i18n(SBLOG . '/UI_SETTINGS_UPLOADER_PATH_LABEL'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_UPLOADER_PATH_HINT'); ?></span>
        <input class="text" type="text" name="uploaderpath"
            placeholder="<?php echo i18n_r(SBLOG . '/DEFAULT') . ' ' . $SimpleBlog->default_setting_values['uploaderpath']; ?>"
            value="<?php echo $SimpleBlog->getSetting('uploaderpath'); ?>" />
    </div>

    <div class="clear"></div>

    <div class="widesec">
        <h3><?php i18n(SBLOG . '/RSS_FEED'); ?></h3>
        <div class="leftsec">
            <label for="rsstitle"><?php i18n(SBLOG . '/UI_SETTINGS_RSS_FEED_TITLE_LABEL'); ?></label>
            <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_RSS_FEED_TITLE_HINT'); ?></span>
            <input class="text" type="text" name="rsstitle"
                placeholder="<?php echo $SimpleBlog->default_setting_values['rsstitle']; ?>"
                value="<?php echo $SimpleBlog->getSetting('rsstitle'); ?>" />
        </div>
        <div class="rightsec">
            <label for="rssdescription"><?php i18n(SBLOG . '/UI_SETTINGS_RSS_FEED_DESC_LABEL'); ?></label>
            <span class="hint"><?php i18n(SBLOG . '/UI_SETTINGS_RSS_FEED_DESC_HINT'); ?></span>
            <input class="text" type="text" name="rssdescription"
                placeholder="<?php echo $SimpleBlog->default_setting_values['rssdescription']; ?>"
                value="<?php echo $SimpleBlog->getSetting('rssdescription'); ?>" />
        </div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <hr class="gs_simpleblog_ui_hline" />

    <div id="submit_line" style="text-align:center;">

		<span><input id="page_submit" class="submit" type="submit" name="submitted" value="<?php i18n('BTN_SAVESETTINGS'); ?>" /></span>

		<div id="dropdown">
			<h6 class="dropdownaction"><?php i18n('ADDITIONAL_ACTIONS'); ?></h6>
			<ul class="dropdownmenu">
				<li><a href="#"><?php i18n('SAVE_AND_CLOSE'); ?></a></li>
				<li><a href="load.php?id=<?php echo SBLOG; ?>&settings=cancel" ><?php i18n(SBLOG . '/CANCEL_CHANGES'); ?></a></li>
				<li class="alertme"><a href="load.php?id=<?php echo SBLOG; ?>&settings=reset-default" ><?php i18n(SBLOG . '/RESET_TO_DEFAULT'); ?></a></li>
			</ul>
		</div>

	</div>

	<p class="editfooter"><i class="far fa-fw fa-clock"></i>&nbsp;<?php i18n(SBLOG . '/SETTINGS_LAST_SAVED_BY'); ?> <em>Plugin Setup</em> <?php i18n('ON'); ?> <em><?php echo date('F jS, Y - g:i a', time()); ?></em></p>
</form>
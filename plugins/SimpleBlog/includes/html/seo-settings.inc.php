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

<h3 class="floated" style="float:left;"><?php i18n(SBLOG . '/UI_SEO_SETTINGS_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo SBLOG; ?>&settings" title="<?php i18n(SBLOG . '/UI_MAIN_SETTINGS_BUTTON_HINT'); ?>"><?php i18n(SBLOG . '/UI_MAIN_SETTINGS_BUTTON'); ?></a>
    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(SBLOG . '/UI_SEO_SETTINGS_PAGE_INTRO'); ?></p>

<form class="largeform gs_simbleblog_ui_form" id="edit" action="load.php?id=<?php echo SBLOG; ?>&settings=seo" method="post">
    <div class="leftsec">
        <label for="categoriesdesc"><?php i18n(SBLOG . '/UI_SEO_CATEGORIES_DESC'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SEO_CATEGORIES_HINT'); ?></span>
        <textarea class="text" name="categoriesdesc" style="height:55px;"></textarea>
        <label for="categoriesdescshow"><?php i18n(SBLOG . '/UI_SEO_SHOW_ON_PAGE'); ?> : </label>
        <span style="float:right;padding-right:10px;">
            <span style="padding-right:40px;">
                <input type="radio" name="categoriesdescshow" value="true" style="margin-right:5px;" />
                <?php i18n('YES'); ?>
            </span>
            <span style="margin-right:40px;">
                <input type="radio" name="categoriesdescshow" value="false" style="margin-right:5px;" />
                <?php i18n('NO'); ?>
            </span>
        </span>
    </div>

    <div class="rightsec">
        <label for="archivesdesc"><?php i18n(SBLOG . '/UI_SEO_ARCHIVES_DESC'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SEO_ARCHIVES_HINT'); ?></span>
        <textarea class="text" name="archivesdesc" style="height:55px;"></textarea>
        <label for="archivesdescshow"><?php i18n(SBLOG . '/UI_SEO_SHOW_ON_PAGE'); ?> : </label>
        <span style="float:right;padding-right:10px;">
            <span style="padding-right:40px;">
                <input type="radio" name="archivesdescshow" value="true" style="margin-right:5px;" />
                <?php i18n('YES'); ?>
            </span>
            <span style="margin-right:40px;">
                <input type="radio" name="archivesdescshow" value="false" style="margin-right:5px;" />
                <?php i18n('NO'); ?>
            </span>
        </span>
    </div>

    <div class="leftsec">
        <label for="tagsdesc"><?php i18n(SBLOG . '/UI_SEO_TAGS_DESC'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SEO_TAGS_HINT'); ?></span>
        <textarea class="text" name="tagsdesc" style="height:55px;"></textarea>
        <label for="tagsdescshow"><?php i18n(SBLOG . '/UI_SEO_SHOW_ON_PAGE'); ?> : </label>
        <span style="float:right;padding-right:10px;">
            <span style="padding-right:40px;">
                <input type="radio" name="tagsdescshow" value="true" style="margin-right:5px;" />
                <?php i18n('YES'); ?>
            </span>
            <span style="margin-right:40px;">
                <input type="radio" name="tagsdescshow" value="false" style="margin-right:5px;" />
                <?php i18n('NO'); ?>
            </span>
        </span>
    </div>

    <div class="rightsec">
        <label for="searchdesc"><?php i18n(SBLOG . '/UI_SEO_SEARCH_DESC'); ?></label>
        <span class="hint"><?php i18n(SBLOG . '/UI_SEO_SEARCH_HINT'); ?></span>
        <textarea class="text" name="searchdesc" style="height:55px;"></textarea>
        <label for="searchdescshow"><?php i18n(SBLOG . '/UI_SEO_SHOW_ON_PAGE'); ?> : </label>
        <span style="float:right;padding-right:10px;">
            <span style="padding-right:40px;">
                <input type="radio" name="searchdescshow" value="true" style="margin-right:5px;" />
                <?php i18n('YES'); ?>
            </span>
            <span style="margin-right:40px;">
                <input type="radio" name="searchdescshow" value="false" style="margin-right:5px;" />
                <?php i18n('NO'); ?>
            </span>
        </span>
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
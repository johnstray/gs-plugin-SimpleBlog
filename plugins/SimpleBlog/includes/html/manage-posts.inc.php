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

<h3 class="floated" style="float:left;"><?php i18n(SBLOG . '/UI_MANAGE_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="load.php?id=<?php echo SBLOG; ?>&new-post" title="<?php i18n(SBLOG . '/UI_NEW_POST_BUTTON_HINT'); ?>"><?php i18n(SBLOG . '/UI_NEW_POST_BUTTON'); ?></a>
        <?php if (count($posts) !== 0) { ?><a href="#" id="metadata_toggle">Filter Posts</a><?php } ?>
        <?php if (isset($_GET['search']) && isset($_GET['filter'])) { ?><a href="load.php?id=<?php echo SBLOG; ?>">Clear Filters</a><?php } ?>
    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(SBLOG . '/UI_MANAGE_PAGE_INTRO'); ?></p>

<div id="metadata_window" class="gs_simpleblog_ui_filter-box"<?php if (isset($_GET['search']) && isset($_GET['filter'])) {echo 'style="display:block !important;"'; } ?>>
    <p><strong><?php i18n(SBLOG . '/UI_FILTER_BOX_LABEL'); ?></strong> : <?php i18n(SBLOG . '/UI_FILTER_BOX_HINT'); ?></p>
    <form class="largeform" action="load.php" method="get">
        <input type="hidden" name="id" value="<?php echo SBLOG; ?>" />
        <select class="text" name="filter">
            <option value="title"<?php if (isset($_GET['filter']) && $_GET['filter'] == "title") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/POST_TITLE'); ?></option>
            <option value="content"<?php if (isset($_GET['filter']) && $_GET['filter'] == "content") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/POST_CONTENT'); ?></option>
            <option value="category"<?php if (isset($_GET['filter']) && $_GET['filter'] == "category") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/CATEGORY_NAME'); ?></option>
            <option value="author"<?php if (isset($_GET['filter']) && $_GET['filter'] == "author") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/POST_AUTHOR'); ?></option>
            <option value="date"<?php if (isset($_GET['filter']) && $_GET['filter'] == "date") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/PUBLISHED_DATE'); ?> [YYYY-MM-DD]</option>
            <option value="tags"<?php if (isset($_GET['filter']) && $_GET['filter'] == "tags") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/TAG'); ?></option>
            <option disabled>────────────────</option>
            <option value="all"<?php if (isset($_GET['filter']) && $_GET['filter'] == "all") {echo ' selected="selected"';} ?>><?php i18n(SBLOG . '/ALL_FIELDS'); ?></option>
        </select>
        <input class="text" type="text" name="search" value="<?php if (isset($_GET['search'])) {echo htmlentities($_GET['search'], ENT_QUOTES);} ?>" />
        <input class="submit" type="submit" name="" value="<?php i18n(SBLOG . '/SEARCH'); ?>" />
    </form>
    <div class="clear"></div>
</div>

<table class="edittable highlight paginate" id="gs_simbleblog_ui_posts-table">
    <tbody>

        <?php
            if ( count($posts) == 0 )
            {
                if ( isset($_GET['search']) && isset($_GET['filter']) )
                {
                    // There are no search results to show
                    echo '<div class="gs_simbleblog_ui_no-posts">';
                    echo '<h4>' . i18n_r(SBLOG . '/UI_POSTS_NO_RESULTS') . '</h4>';
                    echo '<p>' . i18n_r(SBLOG . '/UI_POSTS_NO_RESULTS_HINT') . '</p>';
                    echo '</div>';
                }
                else
                {
                    // There are no posts at all
                    echo '<div class="gs_simbleblog_ui_no-posts">';
                    echo '<h4>' . i18n_r(SBLOG . '/UI_POSTS_NO_POSTS') . '</h4>';
                    echo '<p><a href="#">' . i18n_r(SBLOG . '/UI_POSTS_NO_POSTS_HINT') . '</a></p>';
                    echo '</div>';
                }
            }
            else
            {
                foreach ( $posts as $post_slug ) {
                    $post = $SimpleBlog->getPost( $post_slug );
                    $post_ckey = array_search( $post['category'], array_column($categories, 'slug') );
                    ?>
                    <tr>
                        <td>
                            <img src="<?php if (isset($post['image']) && empty($post['image']) == false) { echo $post['image']; } else { echo '../plugins/'.SBLOG.'/includes/images/missing.png'; } ?>" />
                            <a title="<?php echo $post['title']; ?>" href="load.php?id=<?php echo SBLOG; ?>&editor=<?php echo $post_slug; ?>"><?php echo $post['title']; ?></a>
                            <div>
                                <div><?php i18n(SBLOG . '/CATEGORY'); ?>: <?php if ($post_ckey !== false) {echo $categories[$post_ckey]['title'];} else {echo '-----';} ?></div>
                                <div><?php i18n(SBLOG . '/AUTHOR'); ?>: <?php echo $post['author']; ?></div>
                                <div><?php i18n(SBLOG . '/PUBLISHED'); ?>: <?php echo date(i18n_r('DATE_AND_TIME_FORMAT'), (int) $post['date']); ?></div>
                                <div class="clear"></div>
                            </div>
                        </td>
                        <td class="secondarylink">
                            <a href="#" title="View post"><!-- @TODO: Generate the front-end link to view the post -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor">
                                    <path d="M279.6 160.4C282.4 160.1 285.2 160 288 160C341 160 384 202.1 384 256C384 309 341 352 288 352C234.1 352 192 309 192 256C192 253.2 192.1 250.4 192.4 247.6C201.7 252.1 212.5 256 224 256C259.3 256 288 227.3 288 192C288 180.5 284.1 169.7 279.6 160.4zM480.6 112.6C527.4 156 558.7 207.1 573.5 243.7C576.8 251.6 576.8 260.4 573.5 268.3C558.7 304 527.4 355.1 480.6 399.4C433.5 443.2 368.8 480 288 480C207.2 480 142.5 443.2 95.42 399.4C48.62 355.1 17.34 304 2.461 268.3C-.8205 260.4-.8205 251.6 2.461 243.7C17.34 207.1 48.62 156 95.42 112.6C142.5 68.84 207.2 32 288 32C368.8 32 433.5 68.84 480.6 112.6V112.6zM288 112C208.5 112 144 176.5 144 256C144 335.5 208.5 400 288 400C367.5 400 432 335.5 432 256C432 176.5 367.5 112 288 112z"/>
                                </svg>
                            </a>
                        </td>
                        <td class="delete">
                            <a class="delconfirm" href="load.php?id=<?php echo SBLOG; ?>&delete=<?php echo $post_slug; ?>" title="Delete: <?php echo $post['title']; ?>" >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" fill="currentColor">
                                    <path d="M418.8 104.2L404.6 32H304.1L304 159.1h60.77C381.1 140.7 399.1 121.8 418.8 104.2zM272.1 32.12H171.5L145.9 160.1h126.1L272.1 32.12zM461.3 104.2c18.25 16.25 35.51 33.62 51.14 51.49c5.751-5.623 11.38-11.12 17.38-16.37l21.26-18.98l21.25 18.98c1.125 .9997 2.125 2.124 3.126 3.124c-.125-.7498 .2501-1.5 0-2.249l-24-95.97c-1.625-7.123-8.127-12.12-15.38-12.12H437.2l12.25 61.5L461.3 104.2zM16 160.1l97.26-.0223l25.64-127.9h-98.89c-7.251 0-13.75 4.999-15.5 12.12L.5001 140.2C-2.001 150.3 5.626 160.1 16 160.1zM340.6 192.1L32.01 192.1l4.001 31.99L16 224.1C7.252 224.1 0 231.3 0 240.1V272c0 8.748 7.251 15.1 16 15.1l28.01 .0177l20 159.1L64.01 464C64.01 472.8 71.26 480 80.01 480h32.01c8.752 0 16-7.248 16-15.1v-15.1l208.8-.002c-30.13-33.74-48.73-77.85-48.73-126.3C288.1 285.8 307.9 238.8 340.6 192.1zM551.2 163.3c-14.88 13.25-28.38 27.12-40.26 41.12c-19.5-25.74-43.63-51.99-71.01-76.36c-70.14 62.73-120 144.2-120 193.6C319.1 409.1 391.6 480 479.1 480s160-70.87 160-158.3C640.1 285 602.1 209.4 551.2 163.3zM532.6 392.6c-14.75 10.62-32.88 16.1-52.51 16.1c-49.01 0-88.89-33.49-88.89-87.98c0-27.12 16.5-50.99 49.38-91.85c4.751 5.498 67.14 87.98 67.14 87.98l39.76-46.99c2.876 4.874 5.375 9.497 7.75 13.1C573.9 321.5 565.1 368.4 532.6 392.6z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            }
        ?>

    </tbody>
</table>

<?php if ( count($posts) > (int) $SimpleBlog->getSetting('postsperpage') ) { ?>
    <div id="gs_simbleblog_ui_pagination">

    </div>
<?php } ?>
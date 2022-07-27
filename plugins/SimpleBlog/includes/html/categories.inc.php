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

<h3 class="floated" style="float:left;"><?php i18n(SBLOG . '/UI_CATEGORIES_PAGE_TITLE'); ?></h3>
<div class="edit-nav">
    <p class="text 1">
        <a href="#" id="metadata_toggle" title="<?php i18n(SBLOG . '/UI_NEW_CATEGORY_BUTTON_HINT'); ?>"><?php i18n(SBLOG . '/UI_NEW_CATEGORY_BUTTON'); ?></a>
        Sort Categories:
        <span class="gs_simpleblog_ui_sort-button sort-az" data-sort="name"></span>
        <span class="gs_simpleblog_ui_sort-button sort-19" data-sort="posts"></span>
    </p>
    <div class="clear"></div>
</div>
<p class="text 2"><?php i18n(SBLOG . '/UI_CATEGORIES_PAGE_INTRO'); ?></p>

<div id="metadata_window" class="gs_simpleblog_ui_new-category">
    <form class="largeform" action="load.php?id=<?php echo SBLOG; ?>&categories=new" method="post">
        <input type="hidden" name="nonce" value="<?php echo get_nonce(SBLOG."filterposts"); ?>" />
        <p><strong>Add new category</strong> : Add new category hint</p>
        <fieldset>
            <div class="input-group">
                <label for="categoryname">Category Name:</label>
                <input class="text" type="text" name="categoryname" id="categoryname" value="" />
            </div>
            <div class="input-group">
                <label for="categorydesc">Category Description:</label>
                <textarea class="text" name="categorydesc" id="categorydesc" style="height:50px;"></textarea>
            </div>
        </fieldset>
        <input class="submit" type="submit" name="" value="Add Category" />
    </form>
</div>

<div id="gs_simpleblog_ui_categories_table">
    <table class="highlight">
        <tbody class="list">
            <tr>
                <td class="name">
                    General Information
                    <a href="load.php?id=<?php echo SBLOG; ?>&manage&filter=category&keyphrase=general-information" class="posts">26 posts</a>
                    <p>Afferrent, quo iucundius, id est nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia pacem animis afferat et eos quasi concordia quadam placet ac leniat.</p>
                </td>
                <td class="secondarylink edit">
                    <a href="#" title="Edit Category: General Information">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z"/>
                        </svg>
                    </a>
                </td>
                <td class="delete">
                    <a class="delconfirm" href="#" title="Delete category: General Information">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/>
                        </svg>
                    </a>
                </td>
            </tr>
            <tr class="editcat">
                <td class="editname">
                    <form class="largeform">
                        <input type="text" class="text" name="newname" value="Some awesome stuff" />
                        <textarea class="text" name="newdesc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magnam aliquam. This is about general things not related to anything specific</textarea>
                    </form>
                </td>
                <td class="secondarylink save">
                    <a href="#" title="Save changes to category">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M433.1 129.1l-83.9-83.9C342.3 38.32 327.1 32 316.1 32H64C28.65 32 0 60.65 0 96v320c0 35.35 28.65 64 64 64h320c35.35 0 64-28.65 64-64V163.9C448 152.9 441.7 137.7 433.1 129.1zM224 416c-35.34 0-64-28.66-64-64s28.66-64 64-64s64 28.66 64 64S259.3 416 224 416zM320 208C320 216.8 312.8 224 304 224h-224C71.16 224 64 216.8 64 208v-96C64 103.2 71.16 96 80 96h224C312.8 96 320 103.2 320 112V208z"/>
                        </svg>
                    </a>
                </td>
                <td class="delete">
                    <a class="delconfirm" href="#" title="Delete category: General Information">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/>
                        </svg>
                    </a>
                </td>
            </tr>
            <tr>
                <td class="name">
                    Another Category
                    <a href="load.php?id=<?php echo SBLOG; ?>&manage&filter=category&keyphrase=another-category" class="posts">98 posts</a>
                    <p>Afferrent, quo iucundius, id est nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia pacem animis afferat et eos quasi concordia quadam placet ac leniat.</p>
                </td>
                <td class="secondarylink edit">
                    <a href="#" title="Edit Category: Another Category">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M490.3 40.4C512.2 62.27 512.2 97.73 490.3 119.6L460.3 149.7L362.3 51.72L392.4 21.66C414.3-.2135 449.7-.2135 471.6 21.66L490.3 40.4zM172.4 241.7L339.7 74.34L437.7 172.3L270.3 339.6C264.2 345.8 256.7 350.4 248.4 353.2L159.6 382.8C150.1 385.6 141.5 383.4 135 376.1C128.6 370.5 126.4 361 129.2 352.4L158.8 263.6C161.6 255.3 166.2 247.8 172.4 241.7V241.7zM192 63.1C209.7 63.1 224 78.33 224 95.1C224 113.7 209.7 127.1 192 127.1H96C78.33 127.1 64 142.3 64 159.1V416C64 433.7 78.33 448 96 448H352C369.7 448 384 433.7 384 416V319.1C384 302.3 398.3 287.1 416 287.1C433.7 287.1 448 302.3 448 319.1V416C448 469 405 512 352 512H96C42.98 512 0 469 0 416V159.1C0 106.1 42.98 63.1 96 63.1H192z"/>
                        </svg>
                    </a>
                </td>
                <td class="delete">
                    <a class="delconfirm" href="#" title="Delete category: Another Category">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                            <!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                            <path d="M135.2 17.69C140.6 6.848 151.7 0 163.8 0H284.2C296.3 0 307.4 6.848 312.8 17.69L320 32H416C433.7 32 448 46.33 448 64C448 81.67 433.7 96 416 96H32C14.33 96 0 81.67 0 64C0 46.33 14.33 32 32 32H128L135.2 17.69zM31.1 128H416V448C416 483.3 387.3 512 352 512H95.1C60.65 512 31.1 483.3 31.1 448V128zM111.1 208V432C111.1 440.8 119.2 448 127.1 448C136.8 448 143.1 440.8 143.1 432V208C143.1 199.2 136.8 192 127.1 192C119.2 192 111.1 199.2 111.1 208zM207.1 208V432C207.1 440.8 215.2 448 223.1 448C232.8 448 240 440.8 240 432V208C240 199.2 232.8 192 223.1 192C215.2 192 207.1 199.2 207.1 208zM304 208V432C304 440.8 311.2 448 320 448C328.8 448 336 440.8 336 432V208C336 199.2 328.8 192 320 192C311.2 192 304 199.2 304 208z"/>
                        </svg>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- @TODO: Only show this if there is more than 1 page -->
    <ul class="pagination">

    </ul>
</div>

<script>
    window.addEventListener("DOMContentLoaded", function() {
        var categoryList = new List('gs_simpleblog_ui_categories_table', {
            valueNames: ['name', 'posts'],
            page: 10,
            pagination: true
        });

        let sortbuttons = document.getElementsByClassName('gs_simpleblog_ui_sort-button');
        for ( i = 0; i < sortbuttons.length; i++ ) {
            if ( sortbuttons[i].hasAttribute('data-sort') ) {
                if ( sortbuttons[i].hasAttribute('data-order') == false ) { sortbuttons[i].setAttribute('data-order', 'asc'); }
                sortbuttons[i].addEventListener("click", function(e) {
                    console.log('data-sort: ' + this.getAttribute('data-sort') + ' - data-order: ' + this.getAttribute('data-order'));
                    categoryList.sort( this.getAttribute('data-sort'), { order: this.getAttribute('data-order') } );
                    if ( this.getAttribute('data-order') == 'desc' ) { this.setAttribute('data-order', 'asc'); }
                    else { this.setAttribute('data-order', 'desc'); }
                }, false);
            }
        }
    });
</script>
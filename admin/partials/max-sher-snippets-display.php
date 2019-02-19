<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/admin/partials
 */

    $table = new Max_Sher_Snippets_List_Table();
    ?>
    <div class="wrap">
        <h2>Max Sher Snippets List</h2>

        <div id="poststuff">
            <div id="list_table" class="metabox-holder columns-2">
                <div id="post-body-content">
                    <div class="meta-box-sortables ui-sortable">
                        <form method="post">
                            <input type="hidden" name="page" value="max-sher-snippets">
                            <?php
                            $table->prepare_items();
                            $table->display(); ?>
                        </form>
                    </div>
                </div>
            </div>
            <br class="clear">
        </div>
    </div>
<?php

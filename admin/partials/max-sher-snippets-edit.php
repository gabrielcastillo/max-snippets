<?php
global $wpdb;
$id = filter_input(INPUT_GET, 'snippet', FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT * FROM " . $wpdb->prefix . 'max_snippets';
$sql .= " WHERE id= " . $id;

$query = $wpdb->get_row($sql);


?>

<div class="wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'title') :?>
    <div class="notice notice-error is-dismissible">
        <p>(Snippet Title) is requried!</p>
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] == 'text') :?>
    <div class="notice notice-error is-dismissible">
        <p>(Snippet Text) is requried!</p>
    </div>
    <?php endif; ?>
    <h2><?php _e( 'Edit Snippet', $this->plugin_name ); ?></h2>
    <?php $form_input = get_transient('mss_form_data_' . get_current_user_id()); ?>
    <form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
        <?php wp_nonce_field( 'max-sher-snippets-edit', 'max-sher-snippets-edit-nonce' ); ?>
        <input type="hidden" name="action" value="max_sher_snippets_edit_snippet">
        <input type="hidden" name="snippet_id" value="<?php echo $query->id; ?>" />
        <table class="form-table">
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="snippet_title">
                        <?php _e( 'Snippet Title', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                    <input class="mss_input_title" name="snippet_title" type="text" id="snippet_title" value="<?php echo stripslashes($query->snippet_title); ?>" aria-required="true" required>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row">
                    <label for="snippet_text">
                        <?php _e( 'Snippet Text', $this->plugin_name ); ?>
                        <span class="description"><?php _e( '(required)', $this->plugin_name ); ?></span>
                    </label>
                </th>
                <td>
                    <?php $content = $query->snippet_text; ?>
                    <?php wp_editor( stripslashes($content), 'snippet_text', array('media_buttons' => false, 'required' => 'required')); ?> 
                </td>
            </tr>
        </table>
 
        <p class="submit">
            <input type="submit" name="edit-snippet" class="button button-primary"
                   value="<?php _e( 'Edit Snippet', $this->plugin_name ); ?>" >
        </p>
    </form>
 
</div>
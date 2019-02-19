<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/admin
 * @author     Gabriel Castillo <gabriel@stellervision.com>
 */
class Max_Sher_Snippets_Admin {

	private $table;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	public $list_table;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Max_Sher_Snippets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Max_Sher_Snippets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/max-sher-snippets-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Max_Sher_Snippets_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Max_Sher_Snippets_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/max-sher-snippets-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add Snippet Menu Pages
	 *
	 * @return void
	 */
	public function add_snippet_menu_page()
	{
	
		add_menu_page(
			__( 'Snippets', $this->plugin_name ),
			__( 'Snippets', $this->plugin_name ),
			'edit_posts',
			'max-sher-snippets',
			array( $this, 'max_sher_snippets_table_layout' ),
			'dashicons-welcome-write-blog',
			'26.1'
		);
	 
		add_submenu_page(
			'max-sher-snippets',
			__( 'Add new', $this->plugin_name ),
			__( 'Add new', $this->plugin_name ),
			'edit_posts',
			'max-sher-snippets-new',
			array( $this, 'render_snippet_add_new' )
		);
	}

	/**
	 * Show new snippet view
	 *
	 * @return void
	 */
	public function render_snippet_add_new()
	{
		require plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/max-sher-snippets-new.php';
	}

	/**
	 * Handle new snippets form input
	 *
	 * @return void
	 */
	public function handle_add_max_sher_snippet()
	{
		global $wpdb;

		$redirect = admin_url('admin.php?page=max-sher-snippets-new');

		if ( ! empty($_POST) && check_admin_referer('max-sher-snippets-add', 'max-sher-snippets-add-nonce') ) {
			
			$user_id = get_current_user_id();

			set_transient('mss_form_data_' . $user_id, array('title' => $_POST['snippet_title'], 'text' => $_POST['snippet_text']));

			if ( $_POST['snippet_title'] == '' ) {
				wp_redirect(add_query_arg(array('error' => 'title'), $redirect));
				die();
			}

			if ( $_POST['snippet_text'] == '' ) {
				wp_redirect(add_query_arg(array('error' => 'text'), $redirect));
				die();
			}
			$snippet_title = sanitize_text_field($_POST['snippet_title']);
			$snippet_text = sanitize_text_field($_POST['snippet_text']);

			delete_transient('mss_form_data_' . $user_id);

			$table_name = $wpdb->prefix . 'max_snippets';

			$wpdb->insert(
				$table_name,
				array(
					'snippet_title' => $snippet_title,
					'post_author' => $user_id,
					'snippet_text' => $snippet_text,
					'snippet_excerpt' => $this->excerpt($snippet_text),
					'created_at' => current_time('mysql'),
					'updated_at' => current_time('mysql')
				),
				array('%s', '%s', '%s', '%s')
			);
		}
		wp_redirect(admin_url('admin.php?page=max-sher-snippets'));
		die();
	}

	/**
	 * Handle edit function
	 *
	 * @return void
	 */
	public function handle_edit_max_sher_snippet()
	{
		global $wpdb;

		$redirect = admin_url('admin.php?page=max-sher-snippets');

		if ( ! empty($_POST) && check_admin_referer('max-sher-snippets-edit', 'max-sher-snippets-edit-nonce') ) {

			$user_id = get_current_user_id();

			set_transient('mss_form_data_' . $user_id, array('title' => $_POST['snippet_title'], 'text' => $_POST['snippet_text']));

			if ( $_POST['snippet_title'] == '' ) {
				wp_redirect(add_query_arg(array('error' => 'title'), $redirect));
				die();
			}

			if ( $_POST['snippet_text'] == '' ) {
				wp_redirect(add_query_arg(array('error' => 'text'), $redirect));
				die();
			}

			$snippet_title = sanitize_text_field($_POST['snippet_title']);
			$snippet_text = sanitize_text_field($_POST['snippet_text']);
			$snippet_id = filter_input(INPUT_POST, 'snippet_id', FILTER_SANITIZE_NUMBER_INT);
			delete_transient('mss_form_data_' . $user_id);

			$table_name = $wpdb->prefix . 'max_snippets';

			$wpdb->update(
				$table_name,
				array(
					'snippet_title' => $snippet_title,
					'post_author' => $user_id,
					'snippet_text' => $snippet_text,
					'snippet_excerpt' => $this->excerpt($snippet_text),
					'updated_at' => current_time('mysql')
				),
				array('id' => $snippet_id),
				array('%s', '%d', '%s', '%s'),
				array('%d')
			);
		}
		wp_redirect(admin_url('admin.php?page=max-sher-snippets'));
		die();
	}

	/**
	 * Snippets Table List
	 *
	 * @return void
	 */
	public function max_sher_snippets_table_layout()
	{
		$action = '';
		$id = null;

		if ( isset($_GET['action']) ) {
			$action = $_GET['action'];
		}

		if ( isset($_GET['snippet']) && is_numeric($_GET['snippet']) ) {
			$id = $_GET['snippet'];
		}

		// if ( $action === 'delete' ) {
		// 	$this->delete_snippet($_GET['snippet']);
		// 	ob_start();
		// 	header('Location: http://'. $_SERVER['HTTP_HOST'] . 'admin.php?page=max-sher-snippets');
		// 	ob_clean();
		// 	exit;
			
		// }

		if ( $action === 'edit' ) {
			require plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/max-sher-snippets-edit.php';
		} else {
			require plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/max-sher-snippets-display.php';
		}
	}

	/**
	 * Create excerpt of snippet
	 *
	 * @param string $text
	 * @param integer $length
	 * @param boolean $dots
	 * @return void
	 */
	private function excerpt($text, $length = 100, $dots = true) {
		$text = trim(preg_replace('#[\s\n\r\t]{2,}#', ' ', $text));
		$text_temp = $text;
		while (substr($text, $length, 1) != " ") { $length++; if ($length > strlen($text)) { break; } }
		$text = substr($text, 0, $length);
		return $text . ( ( $dots == true && $text != '' && strlen($text_temp) > $length ) ? '...' : ''); 
	}

	/**
	 * Add table styles
	 *
	 * @return void
	 */
	function admin_header() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if( 'max-sher-snippets' != $page )
		return;
		echo '<style type="text/css">';
		echo '.wp-list-table .column-id { width: 5%; }';
		echo '.wp-list-table .column-snippets_title { width: 35%; }';
		echo '.wp-list-table .column-snippets_excerpt { width: 35%; }';
		echo '.wp-list-table .column-post_author { width: 10%; }';
		echo '.wp-list-table .column-created_at { width: 10%;}';
		echo '</style>';
	}

	/**
	 * Delete Snippet
	 * @TODO: need to fix this situation
	 * @param integer $id
	 * @return void
	 */
	private function delete_snippet($id)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'max_snippets';

		$wpdb->delete($table_name, array('id' => $id));

		return true;
	}
}

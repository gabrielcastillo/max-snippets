<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/public
 * @author     Gabriel Castillo <gabriel@stellervision.com>
 */
class Max_Sher_Snippets_Public {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/max-sher-snippets-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/max-sher-snippets-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Register Snippet Widget
	 *
	 * @return void
	 */
	public function register_snippet_widget()
	{
		$plugin_widget = new Max_Sher_Snippets_Widget();
		register_widget('max_sher_snippets_widget');
	}

	/**
	 * Snippet Shortcode
	 *
	 * @return void
	 */
	public function max_sher_snippet_shortcode()
	{
		global $wpdb;

		$snippet_table = $wpdb->prefix . 'max_snippets';
		$users_table = $wpdb->prefix . 'users';

		$sql = "SELECT {$snippet_table}.snippet_title, {$snippet_table}.snippet_text, {$snippet_table}.snippet_excerpt FROM {$snippet_table} ORDER BY {$snippet_table}.created_at DESC";

		$records = $wpdb->get_results($sql, 'ARRAY_A');

		$html = '<ul class="mss_snippets">';
		foreach($records as $record) {
			$html .= '<li class="mss_snippet_wrapper">';
				$html .= '<span class="side-list-cat">'.stripslashes($record['snippet_title']).'</span>';
				$html .= '<div class="mss_snippet_excerpt">'.stripslashes($record['snippet_excerpt']).'</div>';
				$html .= '<div class="mss_snippet_full" style="display:none;">' . stripslashes(nl2br($record['snippet_text'])) . '</div>';
				$html .= '<a class="mss_snippet_btn" href="#">Read More</a>';
			$html .= '</li>';
		}
		$html .= '</ul>';

		return $html;
	}



}

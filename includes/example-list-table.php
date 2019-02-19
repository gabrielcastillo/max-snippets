<?php


 class Max_Sher_Snippets_List_Table extends WP_List_Table {

   static $instance;

   public function __construct()
   {
      parent::__construct(['singular' => __('Max Sher Snippet List'), 'plural' => __('Max Sher Snippets List'), 'ajax' => false]);
   }

   public static function get_snippets($per_page = 5, $page_number = 1)
   {  
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $sql = "SELECT * FROM " . $table_name;

      if ( !empty($_REQUEST['orderby'])) {
         $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
         $sql .=  ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : 'ASC';
      }

      $sql .= ' LIMIT ' . $per_page;

      $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

      $result = $wpdb->get_results( $sql, 'ARRAY_A' );

      return $result;
   }

   public static function delete_snippets( $id )
   {
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $wpdb->delete($table_name, ['ID' => $id], ['%d']);
   }

   public static function record_count()
   {
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $sql = "SELECT COUNT(*) FROM " . $table_name;

      return $wpdb->get_var($sql);
   }

   public function no_items()
   {
      _e('No Snippets Found');
   }

   public function column_name( $item )
   {
      $delete_nonce = wp_create_nonce( 'mss_delete_snippet' );

      $title = '<strong>' . $item['snippet_title'] . '</strong>';

      $actions = [
         'delete' => sprintf( '<a href="?page%s&action=%s&snippet=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint($item['ID']), $delete_nonce ),
      ];

      return $title . $this->row_actions($actions);
   }

   public function column_default($item, $column_name)
   {
      switch($column_name) {
         case 'id':
            return $item[$column_name];
         case 'post_author':
            return $item[$column_name];
         case 'snippet_title':
            return $item[$column_name];
         case 'snippet_text':
            return $item[$column_name];
         case 'created_at':
            return $item[$column_name];
         default:
            return print_r($item, true);
      }
   }

   public function columb_cb( $item )
   {
      return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']);
   }

   public function get_columns()
   {
      $columns = [
         'cb' => '<input type="checkbox" />',
         'snippet_title' => __('Snippet Title'),
         'snippet_text' => __('Snippet Text'),
         'post_author' => __('Created By'),
         'created_at' => __('Created At'),
      ];

      return $columns;
   }

   public function get_sortable_columns()
   {
      $sortable_columns = array('snippet_title' => array('snippet_title', true), 'created_at' => array('created_at', true));

      return $sortable_columns;
   }

   public function get_bulk_actions()
   {
      $actions = [
         'bulk-delete' => 'Delete'
      ];

      return $actions;
   }

   public function prepare_items()
   {
      $this->_column_headers = $this->get_columns();

      $this->process_bulk_action();

      $per_page = $this->get_items_per_page('snippets_per_page', 5);

      $current_page = $this->get_pagenum();

      $total_items = self::record_count();

      $this->set_pagination_args([
         'total_items' => $total_items,
         'per_page' => $per_page,
      ]);

      $this->items = self::get_snippets($per_page, $current_page);
   }

   public function process_bulk_action()
   {
      if ( 'delete' === $this->current_action() ) {
         $nonce = esc_attr( $_REQUEST['_wpnonce'] );

         if ( !wp_verify_nonce( $nonce, 'mss_delete_snippet') ) {
            die('No direct access allowed!');
         } else {
            self::delete_snippets( absint($_GET['snippet'] ) );

            wp_redirect(esc_url(add_query_arg()));
            die();
         }
      }

      if ( (isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')) {
         $delete_ids = esc_sql( $_POST['bulk-delete']);

         foreach ( $delete_ids as $id ) {
            self::delete_snippets($id);
         }

         wp_redirect(esc_url(add_query_arg()));
         die();
      }
   }

   public static function get_instance()
   {
      if ( ! isset( self::$instance ) ) {
         self::$instance = new self();
      }

      return self::$instance;
   }

 }

 
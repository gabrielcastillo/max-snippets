<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://gabrielcastillo.net
 * @since      1.0.0
 *
 * @package    Max_Sher_Snippets
 * @subpackage Max_Sher_Snippets/includes
 */

 class Max_Sher_Snippets_List_Table extends WP_List_Table {

   public $found_data = array();

   public function __construct()
   {
     parent::__construct(array('singular' => 'Snippets List', 'plural' => 'Snippets Lists', 'ajax' => false));
   
   }

   /**
    * Get Snippets From Database
    *
    * @param integer $per_page
    * @param integer $page_number
    * @return void
    */
   public static function get_snippets($per_page = 10, $page_number = 1)
   {  
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $sql = "SELECT * FROM " . $table_name;

      if ( !empty($_REQUEST['orderby'])) {
         $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
         $sql .=  ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : 'DESC';
      }

      $sql .= ' LIMIT ' . $per_page;

      $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

      $result = $wpdb->get_results( $sql, 'ARRAY_A' );

      return $result;
   }

   /**
    * Count number of snippets
    *
    * @return void
    */
   public static function record_count()
   {
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $sql = "SELECT COUNT(*) FROM " . $table_name;

      return $wpdb->get_var($sql);
   }

   /**
    * Get post author name
    *
    * @param integer $id
    * @return void
    */
   public function get_author_name($id)
   {
      global $wpdb;

      $table_name = $wpdb->prefix . 'users';

      $sql = "SELECT display_name FROM {$table_name} WHERE id = {$id}";

      return $wpdb->get_var($sql);

   }

   /**
    * Delete Snippet
    *
    * @param integer $id
    * @return void
    */
   public static function delete_snippets( $id )
   {
      global $wpdb;
      
      $table_name = $wpdb->prefix . 'max_snippets';

      $wpdb->delete($table_name, ['ID' => $id], ['%d']);
   }

   public function get_columns()
   {
      $columns = array(
         'cb' => '<input type="checkbox" />',
         'id' => 'ID',
         'snippet_title' => 'Title', 
         'snippet_excerpt' => 'Excerpt',
         'post_author' => 'Author',
         'created_at' => 'Created At',
      );

      return $columns;
   }

   public function prepare_items()
   {
      $columns = $this->get_columns();

      $hidden = array();

      $sortable = $this->get_sortable_columns();

      $perPage = 10;

      $currentPage = $this->get_pagenum();
      $this->process_bulk_action();
      
      $data = $this->get_snippets($perPage, $currentPage);

      usort( $data, array( &$this, 'sort_data' ) );

      $totalItems = $this->record_count();

      $this->set_pagination_args( array(
         'total_items' => $totalItems,
         'per_page'    => $perPage
      ) );

      $this->_column_headers = array($columns, $hidden, $sortable);

      $this->items = $data;
   }
   private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }

   public function column_default($item, $column_name)
   {
      switch($column_name) {
         case 'id':
            return $item[$column_name];
         case 'snippet_title':
            return $item[$column_name];
         case 'snippet_excerpt':
            return stripslashes($item[$column_name]);
         case 'post_author':
            return $this->get_author_name($item[$column_name]);
         case 'created_at':
            return sprintf('Published At: <br> <abbr title="%s">%s<abbr>', date('M d, Y - h:i:s A', strtotime($item[$column_name])), date('M d, Y', strtotime($item[$column_name])) );
         default:
            return print_r(stripslashes($item), true);
      }
   }

   public function get_sortable_columns()
   {
      $sortable_columns = array(
         'snippet_title' => array('snippet_title', false),
         'post_author' => array('post_author', false),
         'created_at' => array('created_at', true)
      );

      return $sortable_columns;
   }

   public function column_snippet_title($item)
   {
      $actions = array(
         'edit' => sprintf('<a href="?page=%s&action=%s&snippet=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']),
         'delete' => sprintf('<a href="?page=%s&action=%s&snippet=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
      );

      return sprintf('%1$s %2$s', stripslashes($item['snippet_title']), $this->row_actions($actions));
   }

   public function get_bulk_actions()
   {
      $actions = array(
         'delete' => 'Delete',
      );
      return $actions;
   }

   public function column_cb($item)
   {
      return sprintf('<input type="checkbox" name="snippet[]" value="%s" />', $item['id']);
   }

   function no_items() {
      _e( 'No Snippets found, dude.' );
    }

    public function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'max_snippets'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['snippet']) ? $_REQUEST['snippet'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

 }

 
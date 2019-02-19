<?php 


class Max_Sher_Snippets_Widget extends WP_Widget {

    private $plugin_name;

    public function __construct()
    {
        $this->plugin_name = 'max-sher-snippets';

        $widget_ops = array(
            'classname' => 'max_sher_snippest_widget',
            'description' => 'Custom snippets plugin'
        );

        parent::__construct( 'max_sher_snippets_widget', 'Snippets Widget', $widget_ops);
    }

    /**
     * Widget
     *
     * @return void
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if ( !empty($instance['title']) ) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        echo do_shortcode('[max_sher_snippets]');

        echo $args['after_widget'];   
    }

    /**
     * Create widget Form
     *
     * @param [type] $instance
     * @return void
     */
    public function form( $instance )
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html('Title', $this->plugin_name);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', $this->plugin_name); ?></label>

            <input 
            class="widefat" 
            id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
            name="<?php echo esc_attr($this->get_field_name('title')) ?>"
            type="text"
            value="<?php echo esc_attr($title); ?>">
        <?php
    }

    /**
     * Update widget options
     *
     * @param mixed $new_instance
     * @param mixed $old_instance
     * @return void
     */
    public function update( $new_instance, $old_instance )
    {
        $instance = array();

        $instance['title'] = ( !empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

}


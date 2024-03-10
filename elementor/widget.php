<?php

class Taspri_Elementor_Widgets
{
    protected static $instance = null;

    public static function get_instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    
    protected function __construct()
    {
        require_once 'check-page.php';


        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
    }

    public function register_widgets()
    {
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\All_Blog_Post());

    }
}

add_action('init', 'my_elementor_init');
//if (is_plugin_active('elementor/elementor.php')) {
//
//}
function my_elementor_init()
{
    Taspri_Elementor_Widgets::get_instance();
}

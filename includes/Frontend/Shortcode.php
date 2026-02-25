<?php 
namespace Wedevs\Academy\Frontend; // FIXED (small d)

class Shortcode{

    public function __construct() {
        add_shortcode('wedevs-academy', [$this, 'render_shortcode']);
    }

    public function render_shortcode($atts = [], $content = '') {
        wp_enqueue_script('academy-script');
        wp_enqueue_style('academy-style');
        return '<div class="academy-shortcode"> Hello from shortcode! </div>';
    }
}
<?php

function halloween_supports () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
}

function halloween_register_assets () {
    wp_register_style('boostrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', []);
    wp_enqueue_style('boostrap');
    wp_enqueue_script('boostrap');
    wp_register_style('main', get_theme_file_uri('/assets/css/main.css'));
    wp_enqueue_style('main');
}


function halloween_document_title_parts ($title) {
    unset($title['tagline']);
    return $title;
}
add_action('after_setup_theme', 'halloween_supports');
add_action('wp_enqueue_scripts', 'halloween_register_assets');
add_filter('document_title_parts', 'halloween_document_title_parts');

class my_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            // widget ID
            'my_widget',
            // widget name
            __('Mon premier widget halloween', ' my_widget_domain'),
            // widget description
            array('description' => __('Widget halloween', 'my_widget_domain'),)
        );
    }
    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        //if title is present
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        //output
        echo __('Bonjour, voici mon premier widget', 'my_widget_domain');
        echo $args['after_widget'];
    }
    public function form($instance)
    {
        if (isset($instance['title']))
            $title = $instance['title'];
        else
            $title = __('Default Title', 'my_widget_domain');
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
    <?php
    }
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
        }
}
function my_register_widget() {
    register_widget( 'my_widget' );
    }
    add_action( 'widgets_init', 'my_register_widget' );
    
    
add_action('after_setup_theme', 'halloween_setup');
function halloween_setup()
{
    load_theme_textdomain('halloween', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-header');
    add_theme_support('custom-background');
    add_theme_support('html5', array('search-form'));
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array('main-menu' => esc_html__('Main Menu', 'halloween')));
}
add_action('wp_enqueue_scripts', 'halloween_load_scripts');
function halloween_load_scripts()
{
    wp_enqueue_style('halloween-style', get_stylesheet_uri());
    wp_enqueue_script('jquery');
    wp_register_script('halloween-videos', get_template_directory_uri() . '/js/videos.js');
    wp_enqueue_script('halloween-videos');
    wp_add_inline_script('halloween-videos', 'jQuery(document).ready(function($){$("#wrapper").vids();});');
}
add_action('wp_footer', 'halloween_footer_scripts');
function halloween_footer_scripts()
{
    ?>
    <script>
        jQuery(document).ready(function($) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
                $("html").addClass("ios");
                $("html").addClass("mobile");
            }
            if (navigator.userAgent.search("MSIE") >= 0) {
                $("html").addClass("ie");
            } else if (navigator.userAgent.search("Chrome") >= 0) {
                $("html").addClass("chrome");
            } else if (navigator.userAgent.search("Firefox") >= 0) {
                $("html").addClass("firefox");
            } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
                $("html").addClass("safari");
            } else if (navigator.userAgent.search("Opera") >= 0) {
                $("html").addClass("opera");
            }
            $(":checkbox").on("click", function() {
                $(this).parent().toggleClass("checked");
            });
        });
    </script>
<?php
}
add_filter('document_title_separator', 'halloween_document_title_separator');
function halloween_document_title_separator($sep)
{
    $sep = '|';
    return $sep;
}
add_filter('the_title', 'halloween_title');
function halloween_title($title)
{
    if ($title == '') {
        return '...';
    } else {
        return $title;
    }
}
add_filter('the_content_more_link', 'halloween_read_more_link');
function halloween_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">...</a>';
    }
}
add_filter('excerpt_more', 'halloween_excerpt_read_more_link');
function halloween_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">...</a>';
    }
}
add_filter('intermediate_image_sizes_advanced', 'halloween_image_insert_override');
function halloween_image_insert_override($sizes)
{
    unset($sizes['medium_large']);
    return $sizes;
}
add_action('widgets_init', 'halloween_widgets_init');
function halloween_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar Widget Area', 'halloween'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('wp_head', 'halloween_pingback_header');
function halloween_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s" />' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('comment_form_before', 'halloween_enqueue_comment_reply_script');
function halloween_enqueue_comment_reply_script()
{
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
function halloween_custom_pings($comment)
{
?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>"><?php echo comment_author_link(); ?></li>
<?php
}
add_filter('get_comments_number', 'halloween_comment_count', 0);
function halloween_comment_count($count)
{
    if (!is_admin()) {
        global $id;
        $get_comments = get_comments('status=approve&post_id=' . $id);
        $comments_by_type = separate_comments($get_comments);
        return count($comments_by_type['comment']);
    } else {
        return $count;
    }
}


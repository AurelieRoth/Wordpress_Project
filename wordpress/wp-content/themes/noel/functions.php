<?php

function noel_supports () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    
}

function noel_register_assets () {
    wp_register_style('boostrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', []);
    wp_enqueue_style('boostrap');
    wp_enqueue_script('boostrap');
    wp_register_style('main', get_theme_file_uri('/assets/css/main.css'));
    wp_enqueue_style('main');
}


function noel_document_title_parts ($title) {
    unset($title['tagline']);
    return $title;
}
add_action('after_setup_theme', 'noel_supports');
add_action('wp_enqueue_scripts', 'noel_register_assets');
add_filter('document_title_parts', 'noel_document_title_parts');
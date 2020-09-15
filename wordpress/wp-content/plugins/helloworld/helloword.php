<?php
/**
 * @package Helloworld
 * @version 1.0.0
 */
/*
Plugin Name: Hello World ! 
Plugin URI: https://bertrandbourgy.com
Description: plugin pour dire bonjour
Author: Aurélie Roth 
Version: 1.0.0
Author URI: https://bertrandbourgy.com
*/

add_action('wp_header', 'say_hello');

function say_hello(){
    echo('<p>Hello World ! </p>');
}
?>
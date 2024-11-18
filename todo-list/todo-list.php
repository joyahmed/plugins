<?php
/*
Plugin Name: To-Do List
Description: A simple to-do list plugin.
Version: 1.0
Author: Joy
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'TODO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

// Include the main class
require_once TODO_PLUGIN_PATH . 'includes/class-todo-list.php';

// Register activation hook to create the database table
register_activation_hook( __FILE__, [ 'Todo_List', 'create_table' ] );

// Initialize the plugin
function todo_list_init() {
    $plugin = new Todo_List();
    $plugin->run();
}
add_action( 'plugins_loaded', 'todo_list_init' );

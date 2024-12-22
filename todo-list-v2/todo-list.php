<?php
/*
Plugin Name: To-Do List
Description: A feature-rich to-do list plugin for WordPress.
Version: 2.0
Author: Joy
*/

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'TODO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TODO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include core files
require_once TODO_PLUGIN_PATH . 'includes/class-database-handler.php';
require_once TODO_PLUGIN_PATH . 'includes/class-ajax-handler.php';
require_once TODO_PLUGIN_PATH . 'includes/class-task-manager.php';

// Initialize the plugin
function todo_list_init() {
    $db_handler = new Database_Handler();
    $db_handler->register();

    $ajax_handler = new AJAX_Handler();
    $ajax_handler->register();

    $task_manager = new Task_Manager();
    $task_manager->register();
}
add_action( 'plugins_loaded', 'todo_list_init' );

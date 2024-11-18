<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Todo_List {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_add_todo_item', [ $this, 'add_todo_item' ] );
        add_action( 'wp_ajax_edit_todo_item', [ $this, 'edit_todo_item' ] );
        add_action( 'wp_ajax_delete_todo_item', [ $this, 'delete_todo_item' ] );
    }

    public function run() {
        register_activation_hook( TODO_PLUGIN_PATH . 'todo-list.php', [ $this, 'create_table' ] );
    }

    public static function create_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'todo_items';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            task text NOT NULL,
            completed tinyint(1) DEFAULT 0 NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Debugging log
        if ( $wpdb->last_error ) {
            error_log( "Error creating table: " . $wpdb->last_error );
        } else {
            error_log( "Table created or already exists: $table_name" );
        }
    }



    public function add_admin_menu() {
        add_menu_page(
            'To-Do List',
            'To-Do List',
            'manage_options',
            'todo-list',
            [ $this, 'render_admin_page' ],
            'dashicons-list-view'
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'todo-style', plugins_url( '../css/style.css', __FILE__ ) );
        wp_enqueue_script( 'todo-script', plugins_url( '../js/script.js', __FILE__ ), [ 'jquery' ], null, true );

        wp_localize_script( 'todo-script', 'todo_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'todo_list_nonce' ),
        ] );
    }

    public function render_admin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'todo_items';
        $tasks = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );

        echo '<div class="wrap">';
        echo '<h1>To-Do List</h1>';
        echo '<form id="todo-form">';
        echo '<input type="text" id="todo-task" placeholder="Enter a new task" required>';
        echo '<button type="submit">Add Task</button>';
        echo '</form>';
        echo '<ul id="todo-list">';

        foreach ( $tasks as $task ) {
            echo '<li data-id="' . esc_attr( $task->id ) . '">';
            echo '<span class="task-text">' . esc_html( $task->task ) . '</span>';
            echo ' <button class="edit-task">Edit</button>';
            echo ' <button class="delete-task">Delete</button>';
            echo '</li>';
        }

        echo '</ul>';
        echo '</div>';
    }



    public function add_todo_item() {
      // Verify the nonce
      if ( ! check_ajax_referer( 'todo_list_nonce', 'nonce', false ) ) {
          wp_send_json_error( 'Invalid nonce.' );
          return;
      }

      global $wpdb;
      $table_name = $wpdb->prefix . 'todo_items';

      // Sanitize the task input
      $task = sanitize_text_field( $_POST['task'] );

      // Log the task value to check if it's being received
      error_log( 'Task received: ' . $task );

      // Insert into the database
      $result = $wpdb->insert(
          $table_name,
          [ 'task' => $task, 'completed' => 0 ],
          [ '%s', '%d' ]
      );

      // Log the database query result
      if ( $result === false ) {
          error_log( 'Database error: ' . $wpdb->last_error );
          wp_send_json_error( 'Database insertion failed: ' . $wpdb->last_error );
          return;
      }

      // Return the newly inserted task ID
      $task_id = $wpdb->insert_id;

      wp_send_json_success( [
          'id'   => $task_id,
          'task' => $task,
      ] );
  }

  public function edit_todo_item() {
    check_ajax_referer( 'todo_list_nonce', 'nonce' );

    global $wpdb;
    $table_name = $wpdb->prefix . 'todo_items';

    // Sanitize and validate inputs
    $task_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $new_task = isset($_POST['task']) ? sanitize_text_field($_POST['task']) : '';

    if (!$task_id || empty($new_task)) {
        wp_send_json_error('Invalid data.');
    }

    // Update task in database
    $result = $wpdb->update(
        $table_name,
        [ 'task' => $new_task ],
        [ 'id' => $task_id ],
        [ '%s' ],
        [ '%d' ]
    );

    if ($result === false) {
        wp_send_json_error('Failed to update task.');
    }

    wp_send_json_success('Task updated successfully.');
}

public function delete_todo_item() {
    // Verify the nonce
    if ( ! check_ajax_referer( 'todo_list_nonce', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Invalid nonce' ] );
        return;
    }

    // Get the task ID
    $task_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ( !$task_id ) {
        wp_send_json_error( [ 'message' => 'Invalid task ID' ] );
        return;
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'todo_items';

    // Delete the task
    $result = $wpdb->delete(
        $table_name,
        [ 'id' => $task_id ],
        [ '%d' ]
    );

    if ( $result === false ) {
        wp_send_json_error( [ 'message' => 'Failed to delete task.' ] );
    } else {
        wp_send_json_success( [ 'message' => 'Task deleted successfully.' ] );
    }
}



}

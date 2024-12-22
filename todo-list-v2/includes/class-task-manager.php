<?php
class Task_Manager {
    public function register() {
        add_action( 'admin_menu', [ $this, 'add_admin_page' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function add_admin_page() {
        add_menu_page(
            'To-Do List',
            'To-Do List',
            'manage_options',
            'todo-list',
            [ $this, 'render_admin_page' ],
            'dashicons-list-view',
            26
        );
    }

    public function enqueue_assets() {
        wp_enqueue_style( 'todo-styles', TODO_PLUGIN_URL . 'assets/css/styles.css', [], '1.0' );
        wp_enqueue_script( 'todo-scripts', TODO_PLUGIN_URL . 'assets/js/todo-scripts.js', [ 'jquery' ], '1.0', true );

        wp_localize_script( 'todo-scripts', 'todo_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'todo_list_nonce' ),
        ] );
    }

    public function render_admin_page() {
        echo '<div class="wrap">';
        echo '<h1>To-Do List</h1>';
        echo '<form id="todo-form">';
        echo '<input type="text" id="todo-task" placeholder="Enter a new task">';
        echo '<select id="todo-priority">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
              </select>';
        echo '<input type="date" id="todo-due-date">';
        echo '<button type="submit">Add Task</button>';
        echo '</form>';
        echo '<ul id="todo-list"></ul>';
        echo '</div>';
    }
}

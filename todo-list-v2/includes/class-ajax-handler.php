<?php
class AJAX_Handler {
    public function register() {
        add_action( 'wp_ajax_add_todo_item', [ $this, 'add_todo_item' ] );
        add_action( 'wp_ajax_edit_todo_item', [ $this, 'edit_todo_item' ] );
        add_action( 'wp_ajax_delete_todo_item', [ $this, 'delete_todo_item' ] );
    }

    public function add_todo_item() {
        global $wpdb;
        check_ajax_referer( 'todo_list_nonce', 'nonce' );

        $task = sanitize_text_field( $_POST['task'] );
        $priority = sanitize_text_field( $_POST['priority'] );
        $due_date = sanitize_text_field( $_POST['due_date'] );

        $table_name = $wpdb->prefix . 'todo_items';

        $wpdb->insert( $table_name, [
            'task' => $task,
            'priority' => $priority,
            'due_date' => $due_date,
        ] );

        if ( $wpdb->insert_id ) {
            wp_send_json_success( [
                'id' => $wpdb->insert_id,
                'task' => $task,
                'priority' => $priority,
                'due_date' => $due_date,
            ] );
        } else {
            wp_send_json_error( [ 'message' => 'Failed to add task.' ] );
        }
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

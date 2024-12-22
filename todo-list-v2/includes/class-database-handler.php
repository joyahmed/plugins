<?php
class Database_Handler {
    public function register() {
        register_activation_hook( __FILE__, [ $this, 'create_table' ] );
    }

    public function create_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'todo_items';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            task VARCHAR(255) NOT NULL,
            priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
            due_date DATE NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }
}

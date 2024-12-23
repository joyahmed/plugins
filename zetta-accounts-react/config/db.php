<?php

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// Hook the database table creation to the plugin activation event
register_activation_hook(plugin_dir_path(__DIR__) . 'zetta-accounts.php', 'zetta_accounts_create_table');

/**
 * Create the necessary database table for the Zetta Accounts plugin.
 *
 * @return void
 */
function zetta_accounts_create_table()
{
  global $wpdb;

  // Define the table name with the WordPress table prefix
  $table_name = $wpdb->prefix . 'zetta_transactions';
  $charset_collate = $wpdb->get_charset_collate();

  // SQL statement to create the transactions table
  $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        description TEXT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        type ENUM('income', 'expense') NOT NULL,
        created_at DATETIME NOT NULL
    ) $charset_collate;";

  // Include the WordPress upgrade functions to execute dbDelta
  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  dbDelta($sql);
}

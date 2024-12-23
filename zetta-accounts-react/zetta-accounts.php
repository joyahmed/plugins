<?php
/*
Plugin Name: Zetta Accounts React
Description: A WordPress plugin with React and Webpack integration.
Version: 1.0.0
Author: Zettabyte Technology
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

add_action('admin_menu', 'zetta_accounts_init_menu');

require_once plugin_dir_path(__FILE__) . 'includes/class-zetta-accounts-api.php';

require_once plugin_dir_path(__FILE__) . 'config/db.php';


function zetta_accounts_init_menu()
{
  // Main menu
  add_menu_page(
    __('Accounting', 'zetta_accounts'), // Page title
    __('Accounting', 'zetta_accounts'), // Menu title
    'manage_options',                   // Capability
    'zetta_accounts',                   // Menu slug
    'zetta_accounts_admin_page',        // Callback function
    'dashicons-chart-area',             // Icon
    2                                   // Position
  );

  // Submenu: Dashboard
  add_submenu_page(
    'zetta_accounts',                   // Parent slug
    __('Dashboard', 'zetta_accounts'),  // Page title
    __('Dashboard', 'zetta_accounts'),  // Menu title
    'manage_options',                   // Capability
    'zetta_accounts_dashboard',         // Menu slug
    'zetta_accounts_dashboard_page'     // Callback function
  );

  // Submenu: Transactions
  add_submenu_page(
    'zetta_accounts',
    __('Transactions', 'zetta_accounts'),
    __('Transactions', 'zetta_accounts'),
    'manage_options',
    'zetta_accounts_transactions',
    'zetta_accounts_transactions_page'
  );

  // Submenu: Reports
  add_submenu_page(
    'zetta_accounts',
    __('Reports', 'zetta_accounts'),
    __('Reports', 'zetta_accounts'),
    'manage_options',
    'zetta_accounts_reports',
    'zetta_accounts_reports_page'
  );



  // Submenu: Settings
  add_submenu_page(
    'zetta_accounts',
    __('Settings', 'zetta_accounts'),
    __('Settings', 'zetta_accounts'),
    'manage_options',
    'zetta_accounts_settings',
    'zetta_accounts_settings_page'
  );
}

/**
 * Render the Main Page (default React root).
 *
 * @return void
 */
function zetta_accounts_admin_page()
{
  echo '<div id="zetta-accounts-root" data-page="dashboard">Loading...</div>';
}


function zetta_accounts_dashboard_page()
{
  echo '<div id="zetta-accounts-root" data-page="dashboard">Loading Dashboard...</div>';
}


function zetta_accounts_reports_page()
{
  echo '<div id="zetta-accounts-root" data-page="reports">Loading Reports...</div>';
}
function zetta_accounts_transactions_page()
{
  echo '<div id="zetta-accounts-root" data-page="transactions">Loading Transactions...</div>';
}


function zetta_accounts_settings_page()
{
  echo '<div id="zetta-accounts-root" data-page="settings">Loading Settings...</div>';
}

add_action('admin_enqueue_scripts', 'zetta_accounts_admin_enqueue_scripts');

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function zetta_accounts_admin_enqueue_scripts($hook_suffix)
{
  // Ensure scripts are loaded only for plugin pages
  if (strpos($hook_suffix, 'zetta_accounts') === false) {
    return;
  }

  $asset_file = plugin_dir_path(__FILE__) . 'build/index.asset.php';


  if (!file_exists($asset_file)) {
    return;
  }

  $asset = include $asset_file;

  wp_enqueue_script(
    'zetta-accounts-script',
    plugins_url('build/index.js', __FILE__),
    $asset['dependencies'],
    $asset['version'],
    true
  );

  wp_enqueue_style(
    'zetta-accounts-tailwind',
    plugins_url('css/tailwind.min.css', __FILE__),
    [],
    filemtime(plugin_dir_path(__FILE__) . 'css/tailwind.min.css')
  );
}

/**
 * Add development-specific scripts (e.g., BrowserSync).
 */
add_action('admin_footer', function () {
  if (!defined('WP_ENV')) {
    define('WP_ENV', 'production');
  }

  if (WP_ENV === 'development') {
    echo '<script async src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>';
  }
});

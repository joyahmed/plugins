<?php

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class ZettaAccountsAPI
{
  public function __construct()
  {
    add_action('rest_api_init', [$this, 'register_routes']);
  }

  public function register_routes()
  {
    register_rest_route('zetta-accounts/v1', '/transactions', [
      'methods' => 'GET',
      'callback' => [$this, 'get_transactions'],
      'permission_callback' => '__return_true',
    ]);

    register_rest_route('zetta-accounts/v1', '/transactions', [
      'methods' => 'POST',
      'callback' => [$this, 'add_transaction'],
      'permission_callback' => '__return_true',
    ]);

    register_rest_route('zetta-accounts/v1', '/transactions/(?P<id>\d+)', [
      'methods' => 'PUT',
      'callback' => [$this, 'update_transaction'],
      'permission_callback' => '__return_true',
    ]);

    register_rest_route('zetta-accounts/v1', '/transactions/(?P<id>\d+)', [
      'methods' => 'DELETE',
      'callback' => [$this, 'delete_transaction'],
      'permission_callback' => '__return_true',
    ]);
  }

  public function get_transactions()
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zetta_transactions';
    $results = $wpdb->get_results("SELECT * FROM $table_name");
    return rest_ensure_response($results);
  }

  public function add_transaction(WP_REST_Request $request)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zetta_transactions';

    $data = [
      'description' => $request->get_param('description'),
      'amount' => $request->get_param('amount'),
      'type' => $request->get_param('type'),
      'created_at' => current_time('mysql'),
    ];

    $wpdb->insert($table_name, $data);

    return rest_ensure_response(['success' => true, 'id' => $wpdb->insert_id]);
  }

  public function update_transaction(WP_REST_Request $request)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zetta_transactions';

    $id = $request->get_param('id');
    $data = [
      'description' => $request->get_param('description'),
      'amount' => $request->get_param('amount'),
      'type' => $request->get_param('type'),
    ];

    $wpdb->update($table_name, $data, ['id' => $id]);

    return rest_ensure_response(['success' => true]);
  }

  public function delete_transaction(WP_REST_Request $request)
  {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zetta_transactions';

    $id = $request->get_param('id');

    $wpdb->delete($table_name, ['id' => $id]);

    return rest_ensure_response(['success' => true]);
  }
}

new ZettaAccountsAPI();

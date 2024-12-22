<?php
/*
Plugin Name: CORS Handler
Description: Adds CORS headers to allow requests from other domains.
Version: 1.0
Author: Your Name
*/

add_action('init', function () {
    // Handle preflight OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        exit;
    }
});

add_action('rest_api_init', function () {
    // Add CORS headers to REST API responses
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
});

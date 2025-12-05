<?php
/**
 * Local Development Override
 * This file provides local tenant configuration to bypass the master API
 */

// Set a default tenant configuration in session for localhost
if (!isset($_SESSION)) {
    session_start();
}

// Mock tenant credentials for local development
$isLocalhost = isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
$isLocalIP = isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if (($isLocalhost || $isLocalIP) && !isset($_SESSION['tenant_credentials'])) {
    $_SESSION['tenant_credentials'] = [
        'authParams' => [
            'mysql_host' => 'mysql',
            'mysql_dbname' => 'uctenant',
            'mysql_username' => 'root',
            'mysql_password' => 'Gv9Xr2mQpLz7KbYt',
            'mongodb_host' => 'tlai-mongo1:27017,tlai-mongo2:27017,tlai-mongo3:27017',
            'mongodb_database' => 'uctenant',
            'mongodb_username' => '',
            'mongodb_password' => '',
            'mongodb_replica_set' => 'rs0',
        ],
        'tenant_id' => 'local-dev-tenant',
        'tragofoneUsername' => '',
        'tragofonePassword' => '',
        'tragofone_status' => 0,
        'tragofone_username' => '',
        'tragofone_password' => '',
        'tenant_code' => 'LOCAL',
        'enable_sso' => 0,
        'SSO_provider' => '',
        'getLogo' => [
            'tenant_id' => 'local-dev-tenant',
            'logo' => '',
            'favicon_icon' => '',
        ],
    ];
    $_SESSION['tenant_config_expire_time'] = time() + 86400; // 24 hours
    $_SESSION['api_access_token'] = 'local-dev-token';
    $_SESSION['token_expire_time'] = time() + 86400;
}

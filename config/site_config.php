<?php
// Site-specific configuration values. Prefer environment variables in production.
$manager_from_env = getenv('MANAGER_REGISTRATION_CODE');
if ($manager_from_env && trim($manager_from_env) !== '') {
    define('MANAGER_REGISTRATION_CODE', $manager_from_env);
} else {
    // Default for local development; change in production or set env var
    define('MANAGER_REGISTRATION_CODE', 'MANAGER2025');
}

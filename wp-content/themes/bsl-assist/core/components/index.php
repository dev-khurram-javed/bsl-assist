<?php
require_once 'register-components.php';

// Load Components
$components_files = glob(APP_PATH . '/components/*/*.php');

if ($components_files) {
    foreach ($components_files as $file) require_once $file;
}
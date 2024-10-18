<?php
require_once 'register-post-types.php';

// Load Post Types
$post_type_files = glob(APP_PATH . '/post-types/*.php');

if ($post_type_files) {
    foreach ($post_type_files as $file) require_once $file;
}
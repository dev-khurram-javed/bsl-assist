<?php
require_once 'register-taxonomies.php';

// Load Taxonomies
$tax_files = glob(APP_PATH . '/taxonomies/*.php');

if ($tax_files) {
    foreach ($tax_files as $file) require_once $file;
}
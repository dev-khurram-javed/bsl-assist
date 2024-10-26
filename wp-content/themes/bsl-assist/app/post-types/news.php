<?php

wp_register_custom_post_type('News', 'text-page');
wp_register_custom_taxonomy('Categories', 'news', [], ['slug' => 'news-category']);
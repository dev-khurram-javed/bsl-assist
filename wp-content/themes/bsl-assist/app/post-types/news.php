<?php

wp_register_custom_post_type('News', 'text-page', [], ['slug' => 'bsl-news']);
wp_register_custom_taxonomy('Categories', 'bsl-news', [], ['slug' => 'news-category']);
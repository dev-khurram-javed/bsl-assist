<?php

get_header();

// Get the current taxonomy.
$term = get_queried_object();
$taxonomy = get_taxonomy($term->taxonomy);

$temp = wp_get_taxonomy_template($taxonomy->name);

if ($temp) {
    echo apply_filters('the_content', $temp->post_content);
}else {
    global $wp_query;
    $wp_query->set_404();
    // return;
}

get_footer();
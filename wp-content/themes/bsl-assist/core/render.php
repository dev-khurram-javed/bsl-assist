<?php
/**
 * Renders the current post content (if any).
 *
 * @param int|string|WP_Post|null $current_post
 *
 * @return void
 */
function get_body_content($current_post = null) {
    global $post;

    if (is_null($current_post)) {
        // No current post was specified, so we'll try to use the global $post.
        $current_post = $post;
    } elseif (is_string($current_post)) {
        if (str_starts_with($current_post, 'default:')) {
            // The current post is a default post defined in Site Options.
            $current_post = get_default_page(substr($current_post, 8));
        } else {
            // The current post is a post slug.
            $current_post = get_page_by_path($current_post);
        }
    } elseif (is_int($current_post)) {
        // The current post is a post ID.
        $current_post = get_post($current_post);
    }

    if (!$current_post instanceof WP_Post) {
        // The current post is not a valid post.
        return;
    } else {
        // The current post is a valid post.
        $post = $current_post;
    }

    // Render the current post.
    if ($current_post) {
        echo apply_filters('the_content', $current_post->post_content);
    }
}

function get_default_page($page) {
    $default_pages = get_field('default_pages', 'option') ?? [];
    $page_id = $default_pages[$page] ?? null;

    if (!$page_id) {
        return false;
    }

    return get_post($page_id) ?? false;
}
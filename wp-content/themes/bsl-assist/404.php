<?php
get_header();
// print_r();
echo apply_filters('the_content', get_field('default_pages', 'option')['404_(not_found)']->post_content);
echo '404';
get_footer();
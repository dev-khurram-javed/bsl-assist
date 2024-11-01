<?php 
wp_register_component('Pagination', function($data) {
    // Bail early if there are no Posts.
    if ($data['total_posts'] < 1) return;

    $posts_per_page = $data['posts_per_page'];

    // Calculate total pages
    $total_posts = $data['total_posts'];
    $total_pages = ceil($total_posts / $posts_per_page);

    if ($total_posts <= $posts_per_page) return;

    // Build the pagination
    $big = 999999999;
    $pagination = paginate_links( array(
        'base' => str_replace($big, '%#%', html_entity_decode(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $total_pages,
        'prev_text' => print_svg('arrow-slider', false),
        'next_text' => print_svg('arrow-slider', false),
        'mid_size' => 1,
        'start_size' => 0,
        'end_size' => 0
    ));
?>
    <div>
        <?php $pagination; ?>
    </div>

<?php
}, [
    'total_posts' => 0,
    'posts_per_page' => 3
]);
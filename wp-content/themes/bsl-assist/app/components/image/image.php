<?php

wp_register_component('Image', function($data) {
    
    $image_id = $data['ID'];
    $image_url = $data['url'];
    $image_alt = $data['alt'];
    $image_title = $data['label'];
    $max_size = $data['max_size'];
    $post_id = $data['post_id'];
    $lazyload = $data['lazyload'];
    $link = $data['link'];

    $is_external_image = false;

    // Bail early if there's no image ID, no custom URL and no post ID.
    if (!$image_id && !$image_url && !$post_id) return;

    if (!$image_id && $post_id) {
        // Get the image ID from the post thumbnail.
        $image_id = get_post_thumbnail_id($post_id);

        // Bail early if there's no image ID.
        if (!$image_id) return;
    } else if($image_url && !$image_id && !$post_id) {
        $is_external_image = true;
    }
?>
    <figure>
        <picture class="picture">
            <?php
                // Render the link.
                if ($link && !is_string($link)) {
                    // component('link', ['link' => $data->field('link')])->render();
                    echo '<a class="link" href="' . $link['url'] . '">' . $link['title'] . '</a>';
                }

                if ($is_external_image) {
                    // If it's an external image, use a simple <img> tag.
                    printf('<img src="%s" alt="%s" loading="lazy" class="img"/>', $image_url, $image_alt);
                } else {

                    // Render the image using wp_get_attachment_image.
                    echo wp_get_attachment_image(
                        $image_id,
                        $max_size,
                        false,
                        [
                            'class' => 'img',
                            'loading' => $lazyload ? 'lazy' : 'eager',
                            'alt' => $image_alt,
                            'title' => $image_title
                        ]
                    );
                }
            ?>
        </picture>
        <?php if (isset($data['caption']) && !empty($data['caption'])): ?>
            <figcaption class="caption"><?= $data['caption'] ?></figcaption>
        <?php endif; ?>
    </figure>
<?php
}, [
    'ID' => null,
    'url' => '',
    'alt' => '',
    'label' => false,
    'max_size' => 'full',
    'post_id' => false,
    'has_overlay' => false,
    'lazyload' => true,
    'link' => [],
]);
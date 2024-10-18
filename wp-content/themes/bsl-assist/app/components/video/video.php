<?php

wp_register_component('Video', function($data) {

    $video = $data['video'];
    if (!isset($video['file']['url']) && !isset($video['embed_url'])) return;

    // Prepare the video player.
    if ($video['type'] === 'file') {

        $video_attrs = [
            'controls' => true,
            'autoplay' => false,
            'loop' => false,
            'muted' => false,
            'playsinline' => false
        ];

        $video_attrs = wp_parse_args($data['attributes'], $video_attrs);

        foreach ($video_attrs as $key => $value) {
            if (!in_array($key, ['autoplay', 'controls', 'loop', 'muted', 'playsinline'])) {
                $_value = !is_bool($value) ? $value : var_export($value, true);
                $video_inline_attrs .= " $key=\"$_value\"";
            } else if ($value) {
                $video_inline_attrs .= " $key";
            }
        }

        $video_element = sprintf('<video %s><source src="%s" type="video/mp4"></video>', $video_inline_attrs, $video['file']['url']);
    } else {
        $video_element = $video['embed_url'];

        if (strpos($video_element, 'vimeo')) {
            preg_match('/src="(.+?)"/', $video_element, $matches);
            $src = $matches[1];

            // add extra params to iframe src
            $params = array(
                'background' => 1,
                'autoplay'   => 1,
                'loop'       => 1,
                'title'      => 0,
                'byline'     => 0,
                'portrait'   => 0,
                'mute'       => 1,
                'controls'   => 0,
                'quality'    => '1080p'
            );

            $new_src = add_query_arg($params, $src);

            $video_element = str_replace($src, $new_src, $video_element);
        }


        $video_element = str_replace(' src="', ' data-src="', $video_element);
    }
?>
    <div class="<?php if($data['cover_image']) echo 'has-cover'; ?>">
        <?php
            // Maybe render a cover image.
            if ($data['cover_image']) {
                $img = $data['cover_image'];
                $img['max_size'] = $data['image_size'];
                component('image', $img, 'js-cover-image');
            }
        ?>
        <?= $video_element; ?>
        <div class="play-button-wrapper">
            <button type="button" class="video-play-button">
                <span class="button-wrapper">
                    <span class="button-text-wrapper">
                        <span class="button-text">PLAY</span>
                        <?php print_svg('play'); ?>
                    </span>
                </span>
            </button>
        </div>
    </div>
<?php
}, [
    'attributes' => [],
    'lazyload' => true
]);
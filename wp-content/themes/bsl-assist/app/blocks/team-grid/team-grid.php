<?php 
    $render = function ($data) {
?>
    <div class="wrapper">
        <?php component('headline', get_field('heading')); ?>
        <?php if (get_field('team_members')) : ?>
            <div class="team-members">
                <?php 
                    foreach (get_field('team_members') as $key => $member) : 
                        $modal_id = 'team-modal-' . uniqid();
                ?>
                    <div class="team-card">
                        <div class="modal-wrapper js-modal-trigger" data-modal="<?= $modal_id ?>">
                            <?php component('image', $member['image'], 'appear--zoom-in', ['data-appear' => '20']); ?>
                            <div class="info">
                                <h3 class="name appear--fade-in-up" data-appear="20"><?= $member['name']; ?></h3>
                                <span class="title appear--fade-in-up" data-appear="20"><?= $member['title']; ?></span>
                            </div>
                        </div>
                        
                        <?php
                            $modal_content = '';
                            $modal_content .= component('image', $member['image'], '', [], false);
                            $modal_content .= '<div class="info">';
                            $modal_content .= '<button class="btn-close js-modal-close">' . print_svg('close', false) . '</button>';
                            $modal_content .= '<div class="top-content">';
                            $modal_content .= '<div class="title-area">';
                            $modal_content .= '<h3 class="name">' . $member['name'] . '</h3>';
                            $modal_content .= '<span class="title">' . $member['title'] . '</span>';
                            $modal_content .= '</div>';
                            $modal_content .= '<div class="info-area">';
                            $modal_content .= ($member['email']) ? '<a class="email" href="mailto:' . $member['email'] . '">' . $member['email'] . '</a>' : '';
                            $modal_content .= '</div>';
                            $modal_content .= '</div>';
                            $modal_content .= ($member['bio']) ? '<div class="bio">' . $member['bio'] . '</div>' : '';
                            $modal_content .= '</div>';
                            component('modal', [
                                'id' => $modal_id,
                                'content' => $modal_content,
                                'is_fullscreen' => false
                            ]) 
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
};

$fields = [
    wp_acf_field('Heading', 'headline'),
    wp_acf_field('Team Members', 'repeater', [
        'sub_fields' => [
            wp_acf_field('Image', 'image'),
            wp_acf_field('Name', 'text', [
                'required' => 1
            ]),
            wp_acf_field('Title', 'text', [
                'required' => 1
            ]),
            wp_acf_field('Email', 'email'),
            wp_acf_field('Bio', 'wysiwyg')
        ],
        'button_label' => 'Add New Member',
        'min' => 1,
    ])
];

wp_register_custom_block([
    'title' => 'Team Grid',
    'icon' => 'slides',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-normal spacing-bottom-normal'
]);
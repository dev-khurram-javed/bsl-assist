<?php
/* 
    How to use this: 
    Add a trigger element where you want to use this modal.
    Add a class 'js-modal-trigger' & data-modal on that modal.
    data-modal will be the same id use to call the component.
*/
wp_register_component('Modal', function($data) {
    if (!$data['content'] || !$data['id']) return;
?>
    <div class="js-modal <?= $data['style'] ?>" aria-modal="true" data-modal-id="<?= $data['id'] ?>">
        <div class="modal-backdrop js-modal-close"></div>

        <?php if ($data['close_btn']): ?>
            <button
                type="button"
                class="modal-close js-modal-close"
                title="Close Modal"
                aria-label="Close Modal">
                Close
            </button>
        <?php endif ?>

        <div class="modal-content-wrapper <?= $data['is_fullscreen'] ? 'is-fullscreen' : '' ?>">
            <div class="modal-content">
                <?= $data['content'] ?>
            </div>
        </div>
    </div>
<?php
}, [
    'id' => '',
    'close_btn' => true,
    'is_fullscreen' => true,
    'style' => 'default',
    'content' => ''
]);
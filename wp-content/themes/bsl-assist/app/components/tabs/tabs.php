<?php 
wp_register_component('Tabs', function($data) {
    // Bail early if there are no tabs.
    if (empty($data['tabs'])) return;

    // Generate a unique ID.
    $id = uniqid('tabs-');

    // Get the active index.
    $active_index = $data['active_index'];
    
?>
    <div data-interaction-type="<?= $data['interaction'] ? $data['interaction'] : 'click' ?>">
        <nav class="tab-list-wrapper">
            <ul
                role="tablist"
                class="tab-list"
                aria-label="<?= $data['title'] ?>">
                <?php foreach ($data['tabs'] as $i => $tab): ?>
                    <li
                        id="<?= "$id-tab-$i" ?>"
                        role="tab"
                        aria-controls="<?= "$id-panel-$i" ?>"
                        data-index="<?= $i ?>"
                        class="<?= $active_index === $i ? 'is-active' : '' ?> js-tab">
                        <?php
                            if (is_array($tab) && isset($tab['title'])) {
                                echo $tab['title'];
                            }
                        ?>
                    </li>
                <?php endforeach ?>
            </ul>
        </nav>

        <div class="tab-panels-wrapper js-tab-panels-wrapper">
            <?php foreach ($data['tabs'] as $i => $panel): ?>
                <div
                    id="<?= "$id-panel-$i" ?>"
                    role="tabpanel"
                    aria-labelledby="<?= "$id-tab-$i" ?>"
                    data-index="<?= $i ?>"
                    class="<?= $active_index === $i ? 'is-active' : '' ?> tab-panel js-tab-panel">
                    <?php 
                        echo $panel['panel'];
                    ?>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php
}, [
    'title' => 'Tabs',
    'tabs' => [],
    'active_index' => 0,
    'interaction' => 'click'
]);
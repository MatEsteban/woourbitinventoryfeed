<div class="wrap">
    <div class="bootstrap-wrapper">
        <h2><?php echo get_admin_page_title() ?></h2>

        <form action="options.php" method="POST" class="urbit_inventoryfeed_form">
            <?php
                settings_fields($option_group);

                /** @var UIF_Admin_Settings_Section $section */
                foreach ($sections as $section) {
                    do_settings_sections($section->getPageId());
                }

                submit_button();
            ?>
        </form>
    </div>
</div>

<style>
    .urbit_inventoryfeed_form select {
        min-width: 190px;
    }
</style>
<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>

    <form action="options.php" method="POST" class="urbit_productfeed_form">
        <?php
            settings_fields($option_group);

            /** @var UPF_Admin_Settings_Section $section */
            foreach ($sections as $section) {
                do_settings_sections($section->getPageId());
            }

            submit_button();
        ?>
    </form>
</div>

<style>
    .urbit_productfeed_form select {
        min-width: 190px;
    }
</style>
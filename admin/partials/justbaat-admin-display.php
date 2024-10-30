<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://justbaat.com
 * @since      1.0.0
 *
 * @package    Justbaat
 * @subpackage Justbaat/admin/partials
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php Justbaat_Admin_Layout::justbaat_start_page_layout() ?>
<?php Justbaat_Admin_Layout::justbaat_render_heading("Ad Refresher", "Refresh all ad slots on your website to enhance viewability and engagement.") ?>

<div class="jb-content-container">
    <h2>Feature Highlights</h2>
    <ul>
        <li><em>Enhances ad viewability and engagement.</em></li>
        <li><em>Potentially increases your ad revenue through smarter ad refresh strategies.</em></li>
    </ul>

    <div class="jb-form-container">
    <?php
        if (!isset($justbaat_ad_refresher_form_handler)) {
            throw new Exception('Form handler not provided');
        }

        $justbaat_ad_refresher_form_submitted = $justbaat_ad_refresher_form_handler->justbaat_process_form();

        if ($justbaat_ad_refresher_form_submitted) {
            justbaat_render_toast('Settings saved successfully.', 'success', 'bottom-right', 3000);
        }

        $justbaat_ad_refresher_form_handler->justbaat_render_form();
    ?>
    </div>
</div>

<?php Justbaat_Admin_Layout::justbaat_end_page_layout() ?>


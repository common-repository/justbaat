<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Justbaat_Admin_Layout {

    public static function justbaat_start_page_layout() {
        echo '<div class="justbaat-admin-page">';
    }

    public static function justbaat_end_page_layout() {
        echo '</div>'; // Close .justbaat-admin-page
    }

    public static function justbaat_render_heading($heading_text, $heading_desc) {
        echo '<div class="justbaat-admin-header">';

        echo '<h1>' . esc_html($heading_text) . '</h1>';

        if (!empty($heading_desc)) {
            echo '<p>' . esc_html($heading_desc) . '</p>';
        }

        echo '</div>';
    }
}

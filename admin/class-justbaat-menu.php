<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Justbaat_Menu {

    private $main_menu;
    private $sub_menu;
    private $adapter;

    public function __construct() {
        add_action('admin_menu', array($this, 'justbaat_add_menu'));
    }

    public function justbaat_add_menu() {
        $this->main_menu = [
            'page_title' => 'Justbaat',
            'menu_title' => 'Justbaat',
            'capability' => 'manage_options',
            'menu_slug' => 'justbaat',
            'callback' => array($this, 'justbaat_display_main_admin_page'),
            'icon_url' => plugins_url('assets/images/JB_20x20.png', __FILE__),
            'position' => 5
        ];

        add_menu_page(
            $this->main_menu['page_title'],
            $this->main_menu['menu_title'],
            $this->main_menu['capability'],
            $this->main_menu['menu_slug'],
            $this->main_menu['callback'],
            $this->main_menu['icon_url'],
            $this->main_menu['position']
        );
    }

    public function justbaat_add_sub_menu() {
        $this->sub_menu = [
            [
                'parent_slug' => 'justbaat',
                'page_title' => 'Popup Ads',
                'menu_title' => 'Popup Ads',
                'capability' => 'manage_options',
                'menu_slug' => 'popup-ads',
                'callback' => array($this, 'display_popup_ads_page')
            ],
            [
                'parent_slug' => 'justbaat',
                'page_title' => 'Rewarded Ads',
                'menu_title' => 'Rewarded Ads',
                'capability' => 'manage_options',
                'menu_slug' => 'rewarded-ads',
                'callback' => array($this, 'display_rewarded_ads_page')
            ],
        ];

        foreach ($this->sub_menu as $submenu) {
            add_submenu_page(
                $submenu['parent_slug'],
                $submenu['page_title'],
                $submenu['menu_title'],
                $submenu['capability'],
                $submenu['menu_slug'],
                $submenu['callback']
            );
        }
    }

    public function justbaat_display_main_admin_page() {
        $fields = [
            ['name' => 'script_enabled', 'type' => 'checkbox', 'label' => 'Enable']
        ];

        $justbaat_ad_refresher_form_handler = new Justbaat_Form_Handler('JUSTBAAT_AD_REFRESHER', $fields, 'Settings', ['scripts', "JUSTBAAT_AD_REFRESHER"]);

        $partial_file = plugin_dir_path(__FILE__) . './partials/justbaat-admin-display.php';

        if (file_exists($partial_file)) {
            include $partial_file;
        } else {
            echo '<div class="wrap"><p>Something went wrong!!!</p></div>';
        }
    }
}

<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Justbaat_Storage_Manager {
    private static $instance = null;
    private $namespace;

    public function __construct() {
        $this->namespace = JUSTBAAT_GLOBAL_NS;
    }

    public static function justbaat_get_instance() {
        if (self::$instance == null) {
            self::$instance = new Justbaat_Storage_Manager();
        }

        return self::$instance;
    }

    // public function get_options($option_key, $default = []) {
    //     $options = get_option($this->namespace, []);
    //     return isset($options[$option_key]) ? $options[$option_key] : $default;
    // }

    public function justbaat_get_options(array $keys, $default = null) {
        $options = get_option($this->namespace, []);
        $current_level = $options;
    
        foreach ($keys as $key) {
            if (isset($current_level[$key])) {
                $current_level = $current_level[$key];
            } else {
                return $default;
            }
        }
    
        return $current_level;
    }

    public function justbaat_get_all_options($default = []) {
        $options = get_option($this->namespace, []);
        return $options;
    }

    // public function update_options($option_key, $new_values) {
    //     $options = get_option($this->namespace, []);
    //     $options[$option_key] = $new_values;
    //     return update_option($this->namespace, $options);
    // }

    // public function update_script_option($script_key, $script_data) {
    //     $options = get_option($this->namespace, []);
    //     if (!isset($options['scripts'])) {
    //         $options['scripts'] = [];
    //     }

    //     $options['scripts'][$script_key] = $script_data;
    //     update_option($this->namespace, $options);
    // }

    public function justbaat_update_options(array $keys, $new_value) {
        $options = get_option($this->namespace, []);

        $current_level =& $options;

        foreach ($keys as $key) {
            if (!isset($current_level[$key])) {
                $current_level[$key] = [];
            }

            $current_level =& $current_level[$key];
        }

        $current_level = $new_value;

        return update_option($this->namespace, $options);
    }    

    public function justbaat_delete_option($option_key) {
        $options = get_option($this->namespace, []);
        if (isset($options[$option_key])) {
            unset($options[$option_key]);
            return update_option($this->namespace, $options);
        }
        return false;
    }

    public function justbaat_clear_options() {
        return delete_option($this->namespace);
    }
}


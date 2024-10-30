<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Justbaat_Form_Handler {
    private $option_name;
    private $fields;
    private $title;
    private $storage_manager;

    public function __construct($option_name, $fields, $title, $storage_key) {
        $this->option_name = sanitize_text_field($option_name);
        $this->fields = $fields;
        $this->title = sanitize_text_field($title);
        $this->storage_key = $storage_key;

        $this->storage_manager = Justbaat_Storage_Manager::justbaat_get_instance();
    }

    public function justbaat_render_form() {
        $settings = $this->storage_manager->justbaat_get_options($this->storage_key);
        ?>
    
        <h2><?php echo esc_html($this->title); ?></h2>
    
        <form method="post" action="">
            <?php
            $this->justbaat_render_fields($this->fields, $settings);
            wp_nonce_field($this->justbaat_action_name(), $this->justbaat_nonce_field_name());
            submit_button('Save');
            ?>
        </form>
        <?php
    }

    private function justbaat_render_fields($fields, $settings, $prefix = '') {
        foreach ($fields as $field) {
            $field_id = $prefix . sanitize_key($field['name']);
            $field_value = isset($settings[$field['name']]) ? sanitize_text_field($settings[$field['name']]) : '';
    
            if ($field['type'] === 'group') {
                echo '<fieldset class="jb-form-group">';
                echo '<legend>' . esc_html($field['label']) . '</legend>';
                $this->justbaat_render_fields($field['fields'], $field_value, $field_id . '_');
                echo '</fieldset>';
            } else {
                echo '<div class="jb-form-handler-field ' . esc_attr($field['type']) . '">';
                $this->justbaat_render_individual_field($field, $field_id, $field_value);
                echo '</div>';
            }
        }
    }

    private function justbaat_render_individual_field($field, $id, $value) {
        echo '<label for="' . esc_attr($field['name']) . '">' . esc_html($field['label']) . ':</label>';
    
        switch ($field['type']) {
            case 'checkbox':
                $checked = $value ? 'checked' : '';
                echo '<input type="checkbox" id="' . esc_attr($field['name']) . '" name="' . esc_attr($field['name']) . '" ' . esc_attr($checked) . '>';
                break;
            case 'radio':
                foreach ($field['options'] as $option_value => $option_label) {
                    $checked = ($value == $option_value) ? 'checked' : '';
                    echo "<input type='radio' id='" . esc_attr($field['name'] . '_' . $option_value) . "' name='" . esc_attr($field['name']) . "' value='" . esc_attr($option_value) . "' " . esc_attr($checked) . ">";
                    echo "<label for='" . esc_attr($field['name'] . '_' . $option_value) . "'>" . esc_html($option_label) . "</label>";
                }
                break;
            case 'select':
                echo "<select id='" . esc_attr($field['name']) . "' name='" . esc_attr($field['name']) . "'>";
                foreach ($field['options'] as $option_value => $option_label) {
                    $selected = ($value == $option_value) ? 'selected' : '';
                    echo "<option value='" . esc_attr($option_value) . "' " . esc_attr($selected) . ">" . esc_html($option_label) . "</option>";
                }
                echo "</select>";
                break;
            case 'textarea':
                echo "<textarea id='" . esc_attr($field['name']) . "' name='" . esc_attr($field['name']) . "'>" . esc_textarea(stripslashes($value)) . "</textarea>";
                break;
            default:
                echo '<input type="' . esc_attr($field['type']) . '" id="' . esc_attr($field['name']) . '" name="' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '">';
                break;
        }
    }
    

    public function justbaat_process_form() {
        $form_processed = false;

        if (isset($_POST[$this->justbaat_nonce_field_name()])) {
            $nonce = sanitize_text_field(wp_unslash($_POST[$this->justbaat_nonce_field_name()]));
            if (wp_verify_nonce($nonce, $this->justbaat_action_name())) {
                $form_processed = true;
                $data = $this->justbaat_process_fields($this->fields, $_POST);
                $this->storage_manager->justbaat_update_options($this->storage_key, $data);
                }
        }        

        return $form_processed;
    }

    private function justbaat_process_fields($fields, $post_data, $prefix = '') {
        $data = [];

        foreach ($fields as $field) {
            $field_name = $prefix . $field['name'];

            if ($field['type'] === 'group') {
                $data[$field['name']] = $this->justbaat_process_fields($field['fields'], $post_data, $field_name . '_');
            } else {
                $field_name = $field['name'];

                if (isset($post_data[$field_name])) {
                    switch ($field['type']) {
                        case 'checkbox':
                            $data[$field_name] = isset($post_data[$field_name]) ? '1' : '0';
                            break;
                        case 'radio':
                        case 'select':
                            $data[$field_name] = sanitize_text_field($post_data[$field_name]);
                            break;
                        case 'textarea':
                            if (isset($field['input_type']) && $field['input_type'] === 'js_code') {
                                $data[$field_name] = $post_data[$field_name];
                            } else {
                                $data[$field_name] = sanitize_textarea_field($post_data[$field_name]);
                            }

                            break;
                        default:
                            $data[$field_name] = sanitize_text_field($post_data[$field_name]);
                            break;
                    }
                }
            }
        }

        return $data;
    }

    private function justbaat_nonce_field_name() {
        return $this->option_name . '_nonce';
    }

    private function justbaat_action_name() {
        return $this->option_name . '_update';
    }
}

// Sample usage
/* 
$fields = [
    // Existing fields
    ['name' => 'ad_unit_id', 'type' => 'text', 'label' => 'Ad Unit ID'],
    ['name' => 'scroll_threshold', 'type' => 'text', 'label' => 'Appearance Scroll Threshold'],

    // Select field
    [
        'name' => 'select_example',
        'type' => 'select',
        'label' => 'Select Example',
        'options' => [
            'option1' => 'Option 1',
            'option2' => 'Option 2',
            'option3' => 'Option 3'
        ]
    ],

    // Radio field
    [
        'name' => 'radio_example',
        'type' => 'radio',
        'label' => 'Radio Example',
        'options' => [
            'radio1' => 'Radio Option 1',
            'radio2' => 'Radio Option 2',
            'radio3' => 'Radio Option 3'
        ]
    ],

    // Checkbox field
    ['name' => 'checkbox_example', 'type' => 'checkbox', 'label' => 'Checkbox Example'],
    'modalUI' => [
        'type' => 'group',
        'fields' => [
            [
                'name' => 'enabled',
                'type' => 'checkbox',
                'label' => 'Enabled'
            ],
            [
                'name' => 'imageUrl',
                'type' => 'text',
                'label' => 'Image URL'
            ],
            [
                'name' => 'title',
                'type' => 'text',
                'label' => 'Title'
            ]
        ]
    ],
];
*/
<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class Justbaat_Load_Js_Scripts {
    private $service_name = "scripts";

    public function __construct() {
        $this->justbaat_setup_scripts();
    }

    public function justbaat_setup_scripts() {
        $config_data = get_option(JUSTBAAT_GLOBAL_NS, []);
        $scripts = $config_data['scripts'] ?? null;

        if (!is_array($scripts)) {
            return;
        }

        foreach ($scripts as $key => $script) {
            if ($key != 'JUSTBAAT_AD_REFRESHER') {
                continue;
            }

            if (!$script['script_enabled']) {
                continue;
            }
            
            $script['id'] = 'justbaat-adr';
            $script['src'] = 'https://mcm.justbaat.org/scripts/jb-refresher.v1.min.js';

            $placement = $script['placement'] ?? 'wp_head';

            if (!in_array($placement, ['wp_head', 'wp_footer'])) {
                $placement = 'wp_head';
            }

            add_action($placement, function() use ($script) {
                echo '<script id="' . esc_attr($script['id']) . '" src="' . esc_url($script['src']) . '"';

                if (isset($script['attributes']) && is_array($script['attributes']) && count($script['attributes']) > 0) {
                    foreach ($script['attributes'] as $key => $value) {
                        if (!is_string($key) || !is_string($value)) {
                            continue;
                        }
    
                        echo ' ' . esc_attr($key) . '="' . esc_attr($value) . '"';
                    }
                }

                echo '></script>';
            });
        }
    }
}
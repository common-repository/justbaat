<?php

function justbaat_render_toast($message, $type = 'success') {
    $message = sanitize_text_field($message);
    
    $valid_types = ['success', 'error', 'info', 'warning'];
    
    if (!in_array($type, $valid_types)) {
        $type = 'success';
    }
    
    ?>
    <div class="notice notice-<?php echo esc_attr($type); ?> is-dismissible">
        <p><?php echo esc_html($message); ?></p>
    </div>
    <?php 
}



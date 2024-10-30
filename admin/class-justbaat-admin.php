<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Justbaat
 * @subpackage Justbaat/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Justbaat
 * @subpackage Justbaat/admin
 * @author     Your Name <email@example.com>
 */
class Justbaat_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $justbaat    The ID of this plugin.
	 */
	private $justbaat;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $justbaat       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $justbaat, $version ) {

		$this->justbaat = $justbaat;
		$this->version = $version;

		add_action('admin_notices', array($this, 'justbaat_render_modal_toast_example'));

		$this->justbaat_setup();

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Justbaat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Justbaat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->justbaat, plugin_dir_url( __FILE__ ) . 'css/justbaat-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->justbaat . '-form-handler', plugin_dir_url( __FILE__ ) . 'css/justbaat-admin-form-handler.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Justbaat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Justbaat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->justbaat, plugin_dir_url( __FILE__ ) . 'js/justbaat-admin.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->justbaat . '-toast', plugin_dir_url(__FILE__) . 'js/justbaat-toast.js', array('jquery'), $this->version, true );
	}

	public function justbaat_setup() {
		require_once plugin_dir_path(__FILE__) . './utils/justbaat-utils.php';
		require_once plugin_dir_path(__FILE__) . '../includes/class-justbaat-storage-manager.php';
		require_once plugin_dir_path(__FILE__) . './class-justbaat-form-handler.php';
		require_once plugin_dir_path(__FILE__) . './class-justbaat-admin-layout.php';

		require_once plugin_dir_path(__FILE__) . './class-justbaat-menu.php';
		new Justbaat_Menu();
	}

	public function render_modal_toast($message, $type = 'success', $position = 'bottom-right', $delay = 3000) {
        add_action('admin_footer', function() use ($message, $type, $position, $delay) {
            $toast_data = array(
                'message' => esc_js($message),
                'type' => esc_attr($type),
                'position' => esc_attr($position),
                'delay' => intval($delay),
            );

            error_log(print_r($toast_data, true)); // Debugging line to ensure data is correct
            wp_localize_script($this->justbaat, 'justbaatToastData', $toast_data);
        });
    }

	public function justbaat_render_modal_toast_example() {
        $this->render_modal_toast('Your settings have been saved.', 'success', 'bottom-right', 3000);
    }

}

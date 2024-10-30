<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Justbaat
 * @subpackage Justbaat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Justbaat
 * @subpackage Justbaat/public
 * @author     Your Name <email@example.com>
 */
class Justbaat_Public {

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
	 * @param      string    $justbaat       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $justbaat, $version ) {

		$this->justbaat = $justbaat;
		$this->version = $version;

		$this->justbaat_public_setup();
	}

	public function justbaat_public_setup() {
		require_once plugin_dir_path( __FILE__ ) . '/class-justbaat-load-js-scripts.php';

		$this->load_js_scipts = new Justbaat_Load_Js_Scripts();
	}
}

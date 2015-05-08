<?php
/**
 * Vertical Center
 *
 * @package 			Vertical_Center
 * @author				Braad Martin <wordpress@braadmartin.com>
 * @license 			GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: 		Vertical Center
 * Plugin URI: 			https://wordpress.org/plugins/vertical-center/
 * Description: 		Easily vertically center any element relative to its container. Fully responsive.
 * Version: 			1.0.3
 * Author:				Braad Martin
 * Author URI: 			http://braadmartin.com
 * License: 			GPL-2.0+
 * License URI: 		http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: 		vertical-center
 * Domain Path: 		/languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Initialize the plugin.
 *
 * @since  1.0.0
 */
function init_vertical_center_plugin() {

	Vertical_Center::get_instance();
}
init_vertical_center_plugin();

/**
 * The core plugin class.
 */
class Vertical_Center {

	/**
	 * Plugin slug.
	 *
	 * @since  1.0.0
	 *
	 * @var    string
	 */
	const SLUG = 'vertical-center';

	/**
	 * Plugin display name.
	 *
	 * @since  1.0.0
	 * @access  private
	 * @var    string
	 */
	private $plugin_display_name;

	/**
	 * The version of this plugin.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Options for this plugin.
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     array    $options    The options stored for this plugin.
	 */
	private $options;

	/**
	 * Slug for main options group.
	 *
	 * @since   1.0.0
	 * @access 	private
	 * @var     string    $this->options_group_slug    Slug for the main options group.
	 */
	private $options_group_slug;

	/**
	 * Instance of this class.
	 *
	 * @since  1.0.0
	 * @access  protected
	 * @var    object
	 */
	protected static $instance = false;

	/**
	 * Returns the instance of this class, and initializes the instance if it
	 * doesn't already exist.
	 *
	 * @since   1.0.0
	 *
	 * @return  Post_Carousel_Plugin  The Post Carousel object.
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
		  self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The Constructor.
	 *
	 * @since  1.0.0
	 * @access  private
	 */
	private function __construct() {

		// Set up the reference vars.
		$this->plugin_name = 'vertical-center';
		$this->plugin_display_name = __( 'Vertical Center', 'vertical-center' );
		$this->version = '1.0.3';
		$this->plugin_url = plugin_dir_url( __FILE__ );
		$this->options_group_slug = 'vertical-center-elements';

		// Load the plugin text domain.
		add_action( 'init', array( $this, 'load_text_domain' ) );

		// Enqueue the script for the vertical centering.
		// Load later than usual to try to run after JS from other plugins that may affect the layout.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts'), 30 );

		// Set up the admin settings page and the settings.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts') );

		// Add an action link to the settings page
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'action_links' ) );
	}

	/**
	 * Load plugin text domain.
	 *
	 * @since  1.0.0
	 */
	public function load_text_domain() {

		load_plugin_textdomain( self::SLUG, false, $this->plugin_url . 'languages' );
	}

	/**
	 * Enqueue the public JS.
	 *
	 * @since  1.0.0
	 */
	public function register_public_scripts() {

		// Get the options stored for this plugin.
		$this->options = get_option( $this->plugin_name );

		wp_enqueue_script(
			$this->plugin_name,
			$this->plugin_url . 'js/vertical-center-public.js',
			array( 'jquery' ),
			$this->version,
			true
		);

		wp_localize_script( $this->plugin_name, 'verticalCenterElements', $this->options );
	}

	/**
	 * Enqueue the admin CSS and JS.
	 *
	 * @since  1.0.0
	 */
	public function register_admin_scripts( $hook ) {

		if ( 'settings_page_vertical-center' === $hook ) {

			wp_enqueue_script(
				$this->plugin_name,
				$this->plugin_url . 'js/vertical-center-admin.js',
				array( 'jquery' ),
				$this->version,
				false
			);

			wp_enqueue_style(
				$this->plugin_name,
				$this->plugin_url . 'css/vertical-center-admin.css',
				$this->version
			);
		}
	}

	/**
	 * Add an action link to the settings page.
	 *
	 * @since  1.0.0
	 */
	public function action_links( $links ) {

	   $links[] = '<a href="'. get_admin_url( null, 'options-general.php?page=vertical-center' ) .'">Settings</a>';
	   
	   return $links;
	}

	/**
	 * Create the plugin settings page.
	 *
	 * @since  1.0.0
	 */
	public function add_settings_page() {

		// Get the options stored for this plugin.
		$this->options = get_option( $this->plugin_name );

		add_options_page(
			$this->plugin_display_name,
			$this->plugin_display_name,
			'manage_options',
			self::SLUG,
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Output the plugin settings page contents.
	 *
	 * @since  1.0.0
	 */
	public function create_admin_page() {
	?>
		<div class="wrap vertical-center-settings-page">
			<h2><?php echo $this->plugin_display_name; ?></h2>
			<form method="post" action="options.php">
				<?php
					settings_fields( $this->plugin_name );
					do_settings_sections( $this->plugin_name );
					$this->output_add_button();
					submit_button();
				?>
			</form>
		</div>
	<?php
	}

	/**
	 * Register the plugin settings.
	 *
	 * @since  1.0.0
	 */
	function register_settings() {

		register_setting(
			$this->plugin_name, // Option group
			$this->plugin_name, // Option name
			array( $this, 'validate' ) // Validate
		);

		add_settings_section(
			'vertical-center-settings', // ID
			__( '<br />Instructions', 'vertical-center' ), // Title
			array( $this, 'output_element_section_info' ), // Callback
			$this->plugin_name // Page
		);

		// Set number of fields to output (number of saved groups, or 1 if none are saved).
		$field_count = count( $this->options[ $this->options_group_slug ] ) ? count( $this->options[ $this->options_group_slug ] ) : 1;

		// Output correct number of fields.
		for ( $i = 1; $i <= $field_count; $i++ ) {

			add_settings_field(
				'vertical-center-element-' . $i, // ID
				__( 'Element ', 'vertical-center' ) . '<span class="index-number">' . $i . '<span>', // Title
				array( $this, 'output_elements_fields' ), // Callback
				$this->plugin_name, // Page
				'vertical-center-settings', // Section
				array( // Args
					'id' => $this->options_group_slug,
					'index' => $i,
				)
			);
		}
	}

	/**
	 * Validate the user input for the plugin settings.
	 *
	 * @since  1.0.0
	 */
	public function validate( $input ) {

		// Validate input fields.
		foreach( $input[ $this->options_group_slug ] as $key => $group ) {

			// Validate selector.
			$selector = $group['selector'];
			$input[ $this->options_group_slug ][ $key ]['selector'] = wp_strip_all_tags( $selector, true );

			// Validate offset.
			$offset = $group['offset'];
			$input[ $this->options_group_slug ][ $key ]['offset'] = intval( $offset );
		}

		return $input;
	}

	/**
	 * Output some usage instructions.
	 *
	 * @since  1.0.0
	 */
	public function output_element_section_info() {
		?>
		<p><b><?php _e( 'Selector:', 'vertical-center' ) ?></b> <?php _e( 'jQuery/CSS selector of the element you want to vertically center. Examples: <code>#target</code> or <code>.container .target</code>', 'vertical-center' ); ?></p>
		<p><b><?php _e( 'Offset:', 'vertical-center' ); ?></b> <?php _e( 'The number of pixels to offset the calculation by. Positive values will offset down and negative will offset up. Examples: <code>25</code> or <code>-10</code>', 'vertical-center' ); ?></p>
		<p><?php _e( 'The selected elements will vertically center relative to their parent elements, and will remain vertically centered as the screen size changes.', 'vertical-center' ); ?></p>
		<br />
		<?php
	}

	/**
	 * Output the selector fields.
	 *
	 * @since  1.0.0
	 */
	public function output_elements_fields( $args ) {

		// Selector input.
		$field_id = 'selector-' . $args['index'];
		printf(
			'<div class="vc-input-wrapper"><label for="%s">%s</label> <input type="text" id="%s" name="%s[%s][%s][%s]" data-index="%s" value="%s" placeholder="%s" /></div>',
			$field_id,
			__( 'Selector:', 'vertical-center' ),
			$field_id,
			$this->plugin_name,
			$args['id'],
			$args['index'],
			'selector',
			$args['index'],
			isset( $this->options[ $args['id'] ][ $args['index'] ]['selector'] ) ? esc_attr( $this->options[ $args['id'] ][ $args['index'] ]['selector'] ) : '',
			__( '#target', 'vertical-center' )
		);

		// Offset input.
		$field_id = 'offset-' . $args['index'];
		printf(
			'<div class="vc-input-wrapper"><label for="%s">%s</label> <input type="number" id="%s" name="%s[%s][%s][%s]" data-index="%s" value="%s" /> px</div>',
			$field_id,
			__( 'Offset:', 'vertical-center' ),
			$field_id,
			$this->plugin_name,
			$args['id'],
			$args['index'],
			'offset',
			$args['index'],
			isset( $this->options[ $args['id'] ][ $args['index'] ]['offset'] ) ? esc_attr( $this->options[ $args['id'] ][ $args['index'] ]['offset'] ) : '0'
		);

		// Remove button.
		printf(
			'<button type="button" class="button remove-group">%s</button>',
			__( 'Remove', 'vertical-center' )
		);
	}

	/**
	 * Output the button to add additional selector fields.
	 *
	 * @since  1.0.0
	 */
	public function output_add_button() {
		printf(
			'<button type="button" class="button add-group">%s</button>',
			__( '+ Add More', 'vertical-center' )
		);
	}

}

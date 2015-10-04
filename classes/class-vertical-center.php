<?php
/**
 * The Vertical Center class.
 *
 * @since  1.0.0
 */
class Vertical_Center {

	/**
	 * Plugin slug.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	const SLUG = 'vertical-center';

	/**
	 * Plugin display name.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $plugin_display_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $version;

	/**
	 * Options for this plugin.
	 *
	 * @since  1.0.0
	 * @var    array
	 */
	private $options;

	/**
	 * Slug for main options group.
	 *
	 * @since  1.0.0
	 * @var    string
	 */
	private $options_group_slug;

	/**
	 * The Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {

		// Set up the reference vars.
		$this->plugin_name         = self::SLUG;
		$this->plugin_display_name = __( 'Vertical Center', 'vertical-center' );
		$this->version             = VERTICAL_CENTER_VERSION;
		$this->plugin_url          = VERTICAL_CENTER_URL;
		$this->options_group_slug  = $this->plugin_name . '-elements';

		// Enqueue the script for the vertical centering.
		// Load later than usual to try to run after JS from other plugins that may affect the layout.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_public_scripts' ), 30 );

		// Set up the admin settings page and the settings.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Add an action link to the settings page
		add_filter( 'plugin_action_links_' . plugin_basename( VERTICAL_CENTER_PATH . $this->plugin_name . '.php' ), array( $this, 'action_links' ) );
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

		if ( $this->options ) {
			wp_localize_script( $this->plugin_name, 'verticalCenterElements', $this->options );
		}
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
				array(),
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

		$link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( get_admin_url( null, 'options-general.php?page=vertical-center' ) ),
			__( 'Settings', 'vertical-center' )
		);

		$links[] = $link;

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
			<h1><?php echo $this->plugin_display_name; ?></h1>
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
			$this->plugin_name,        // Option group
			$this->plugin_name,        // Option name
			array( $this, 'validate' ) // Validate
		);

		add_settings_section(
			$this->plugin_name . '-settings',              // ID
			__( '<br />Instructions', 'vertical-center' ), // Title
			array( $this, 'output_element_section_info' ), // Callback
			$this->plugin_name                             // Page
		);

		// Set number of fields to output (number of saved groups, or 1 if none are saved).
		$field_count = count( $this->options[ $this->options_group_slug ] ) ? count( $this->options[ $this->options_group_slug ] ) : 1;

		// Output correct number of fields.
		for ( $i = 1; $i <= $field_count; $i++ ) {

			add_settings_field(
				$this->plugin_name . '-element-' . $i,    // ID
				__( 'Element ', 'vertical-center' ) . '<span class="index-number">' . $i . '<span>', // Title
				array( $this, 'output_elements_fields' ), // Callback
				$this->plugin_name,                       // Page
				$this->plugin_name . '-settings',         // Section
				array(                                    // Args
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

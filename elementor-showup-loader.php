<?php
/**
 * Plugin Name: Elementor Showup Loader Widget
 * Description: Adds a configurable Showup loading animation widget to Elementor.
 * Version: 1.1.4
 * Author: Hassan
 * Text Domain: elementor-showup-loader
 * Requires Plugins: elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ESL_VERSION', '1.1.4' );
define( 'ESL_FILE', __FILE__ );
define( 'ESL_PATH', plugin_dir_path( __FILE__ ) );
define( 'ESL_URL', plugin_dir_url( __FILE__ ) );

/**
 * Register frontend assets once. Elementor loads them only when the widget
 * declares the matching style and script dependencies.
 */
function esl_register_assets() {
	wp_register_style(
		'esl-showup-loader',
		ESL_URL . 'assets/css/showup-loader.css',
		array(),
		ESL_VERSION
	);

	wp_register_script(
		'esl-gsap',
		'https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js',
		array(),
		'3.13.0',
		true
	);

	wp_register_script(
		'esl-showup-loader',
		ESL_URL . 'assets/js/showup-loader.js',
		array( 'esl-gsap', 'jquery', 'elementor-frontend' ),
		ESL_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'esl_register_assets' );
add_action( 'elementor/frontend/after_register_scripts', 'esl_register_assets' );
add_action( 'elementor/frontend/after_register_styles', 'esl_register_assets' );

/**
 * Register the widget after Elementor has initialized its widget manager.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widget manager.
 */
function esl_register_widget( $widgets_manager ) {
	require_once ESL_PATH . 'widgets/class-showup-loader-widget.php';
	$widgets_manager->register( new \ESL_Showup_Loader_Widget() );
}
add_action( 'elementor/widgets/register', 'esl_register_widget' );

/**
 * Show a clear admin notice when Elementor is unavailable.
 */
function esl_elementor_missing_notice() {
	if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning is-dismissible">
		<p>
			<?php
			echo esc_html__(
				'Elementor Showup Loader Widget requires Elementor to be installed and active.',
				'elementor-showup-loader'
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'esl_elementor_missing_notice' );

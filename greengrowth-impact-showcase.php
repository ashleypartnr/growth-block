<?php
/**
 * Plugin Name: GreenGrowth Impact Showcase
 * Plugin URI: https://greengrowth.org
 * Description: A high-performance, accessible project showcase block using the WordPress Interactivity API. Displays GreenGrowth's reforestation and sustainability projects in a filterable grid.
 * Version: 1.0.0
 * Requires at least: 6.9
 * Requires PHP: 7.4
 * Author: GreenGrowth
 * Author URI: https://greengrowth.org
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: greengrowth-impact-showcase
 * Domain Path: /languages
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' )) {
	exit;
}

// Define plugin constants.
define( 'GG_IMPACT_VERSION', '1.0.0' );
define( 'GG_IMPACT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GG_IMPACT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GG_IMPACT_PLUGIN_FILE', __FILE__ );

/**
 * Load plugin textdomain for translations.
 */
function gg_impact_load_textdomain() {
	load_plugin_textdomain(
		'greengrowth-impact-showcase',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'gg_impact_load_textdomain' );

/**
 * Include required files.
 */
require_once GG_IMPACT_PLUGIN_DIR . 'src/post-types/project.php';

/**
 * Register the Impact Showcase block.
 */
function gg_impact_register_blocks() {
	register_block_type( GG_IMPACT_PLUGIN_DIR . 'build' );
}
add_action( 'init', 'gg_impact_register_blocks' );

/**
 * Enqueue frontend script module.
 */
function gg_impact_enqueue_frontend_assets() {
	if ( is_admin() ) {
		return;
	}

	// Enqueue Interactivity API view script module
	// Use source file directly to preserve ES module imports
	if ( function_exists( 'wp_enqueue_script_module' ) ) {
		wp_enqueue_script_module(
			'greengrowth-impact-showcase-view',
			GG_IMPACT_PLUGIN_URL . 'src/view.js',
			array( '@wordpress/interactivity' ),
			GG_IMPACT_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'gg_impact_enqueue_frontend_assets' );


/**
 * Plugin activation hook.
 * Creates sample data and flushes rewrite rules.
 */
function gg_impact_showcase_activate() {
	// Register CPT and taxonomy.
	gg_register_project_post_type();
	gg_register_service_area_taxonomy();

	// Flush rewrite rules to enable custom post type URLs.
	flush_rewrite_rules();

	// Create sample data if no projects exist.
	$existing_projects = wp_count_posts( 'gg_project' );
	if ( $existing_projects->publish === 0 ) {
		gg_create_sample_projects();
	}
}
register_activation_hook( __FILE__, 'gg_impact_showcase_activate' );

/**
 * Plugin deactivation hook.
 * Flushes rewrite rules to clean up.
 */
function gg_impact_showcase_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'gg_impact_showcase_deactivate' );

/**
 * Fix existing projects that don't have featured images.
 * This function can be called manually or via WP-CLI to add images to existing projects.
 */
function gg_fix_project_images() {
	$projects = get_posts(
		array(
			'post_type'      => 'gg_project',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		)
	);

	$fixed_count = 0;

	foreach ( $projects as $project ) {
		// Skip if already has featured image.
		if ( has_post_thumbnail( $project->ID ) ) {
			continue;
		}

		// Check if has placeholder URL stored.
		$placeholder_url = get_post_meta( $project->ID, '_gg_placeholder_image', true );

		if ( $placeholder_url ) {
			// Download and attach the image.
			$attachment_id = gg_download_and_attach_image( $placeholder_url, $project->ID, $project->post_title );

			if ( $attachment_id && ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $project->ID, $attachment_id );
				$fixed_count++;
			}
		}
	}

	return $fixed_count;
}

/**
 * Handle the fix images action.
 */
function gg_handle_fix_images_action() {
	if ( ! isset( $_GET['gg_fix_images'] ) || ! isset( $_GET['_wpnonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'gg_fix_images' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$fixed_count = gg_fix_project_images();

	add_action(
		'admin_notices',
		function () use ( $fixed_count ) {
			echo '<div class="notice notice-success is-dismissible"><p>';
			printf(
				/* translators: %d: number of projects fixed */
				esc_html__( 'Successfully added images to %d projects!', 'greengrowth-impact-showcase' ),
				$fixed_count
			);
			echo '</p></div>';
		}
	);
}
add_action( 'admin_init', 'gg_handle_fix_images_action' );

/**
 * Show admin notice if projects are missing images.
 */
function gg_show_missing_images_notice() {
	$screen = get_current_screen();

	// Only show on relevant admin pages.
	if ( ! $screen || ! in_array( $screen->id, array( 'edit-gg_project', 'gg_project', 'dashboard' ), true ) ) {
		return;
	}

	// Check if any projects are missing images.
	$projects_without_images = get_posts(
		array(
			'post_type'      => 'gg_project',
			'posts_per_page' => 1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);

	if ( empty( $projects_without_images ) ) {
		return;
	}

	$fix_url = wp_nonce_url(
		add_query_arg( 'gg_fix_images', '1' ),
		'gg_fix_images'
	);

	?>
	<div class="notice notice-warning">
		<p>
			<strong><?php esc_html_e( 'Impact Showcase:', 'greengrowth-impact-showcase' ); ?></strong>
			<?php esc_html_e( 'Some projects are missing featured images.', 'greengrowth-impact-showcase' ); ?>
			<a href="<?php echo esc_url( $fix_url ); ?>" class="button button-primary" style="margin-left: 10px;">
				<?php esc_html_e( 'Add Images Now', 'greengrowth-impact-showcase' ); ?>
			</a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'gg_show_missing_images_notice' );

<?php
/**
 * Plugin Name: GreenGrowth Impact Showcase
 * Plugin URI: https://greengrowth.org
 * Description: A high-performance, accessible project showcase block using the WordPress Interactivity API. Displays GreenGrowth's reforestation and sustainability projects in a filterable grid.
 * Version: 1.1.0
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
define( 'GG_IMPACT_VERSION', '1.1.0' );
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
require_once GG_IMPACT_PLUGIN_DIR . 'src/includes/error-logger.php';
require_once GG_IMPACT_PLUGIN_DIR . 'src/includes/style-helpers.php';
require_once GG_IMPACT_PLUGIN_DIR . 'src/includes/class-projects-manager.php';
require_once GG_IMPACT_PLUGIN_DIR . 'src/post-types/project.php';

/**
 * Register the Impact Showcase block.
 */
function gg_impact_register_blocks() {
	register_block_type( GG_IMPACT_PLUGIN_DIR . 'build' );
}
add_action( 'init', 'gg_impact_register_blocks' );

/**
 * Note: Google Fonts removed for sustainability and performance.
 * The plugin now uses system font stacks with excellent cross-platform support.
 * This reduces external HTTP requests, improves carbon footprint, and enhances privacy.
 */

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
 * Sets up post types and shows welcome screen.
 */
function gg_impact_showcase_activate() {
	// Register CPT and taxonomy.
	gg_register_project_post_type();
	gg_register_service_area_taxonomy();

	// Flush rewrite rules to enable custom post type URLs.
	flush_rewrite_rules();

	// Set transient to show welcome screen on next admin page load.
	set_transient( 'gg_impact_show_welcome_screen', true, 60 );
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
 * Show welcome screen after plugin activation.
 */
function gg_impact_show_welcome_screen() {
	// Only show to users who can manage options.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if we should show the welcome screen.
	if ( ! get_transient( 'gg_impact_show_welcome_screen' ) ) {
		return;
	}

	// Delete the transient so it only shows once.
	delete_transient( 'gg_impact_show_welcome_screen' );

	// Check if user already has projects or dismissed the screen.
	if ( get_option( 'gg_impact_welcome_dismissed', false ) ) {
		return;
	}

	$existing_projects = wp_count_posts( 'gg_project' );
	if ( $existing_projects->publish > 0 ) {
		return;
	}

	// Show the welcome screen.
	?>
	<div class="notice notice-success is-dismissible gg-welcome-notice" style="position: relative; padding: 20px; border-left: 4px solid #2c7a4a;">
		<div style="display: flex; align-items: start; gap: 20px;">
			<div style="flex-shrink: 0;">
				<span class="dashicons dashicons-admin-site-alt3" style="font-size: 48px; width: 48px; height: 48px; color: #2c7a4a;"></span>
			</div>
			<div style="flex: 1;">
				<h2 style="margin: 0 0 10px 0; font-size: 20px;">
					<?php esc_html_e( 'Welcome to GreenGrowth Impact Showcase!', 'greengrowth-impact-showcase' ); ?>
				</h2>
				<p style="margin: 0 0 15px 0; font-size: 14px; line-height: 1.6;">
					<?php esc_html_e( 'Thank you for installing Impact Showcase. To help you get started quickly, we can create 30 sample projects across 3 categories (Reforestation, Carbon Capture, Sustainable Farming).', 'greengrowth-impact-showcase' ); ?>
				</p>
				<p style="margin: 0 0 20px 0; font-size: 13px; color: #666;">
					<?php esc_html_e( 'You can delete these sample projects anytime and add your own. Skip this step if you prefer to start with a clean slate.', 'greengrowth-impact-showcase' ); ?>
				</p>
				<div style="display: flex; gap: 10px; align-items: center;">
					<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'gg_install_sample_data', '1' ), 'gg_install_sample_data' ) ); ?>"
					   class="button button-primary button-large"
					   style="background: #2c7a4a; border-color: #2c7a4a; text-shadow: none; box-shadow: none;">
						<span class="dashicons dashicons-download" style="margin-top: 3px;"></span>
						<?php esc_html_e( 'Yes, Install Sample Data', 'greengrowth-impact-showcase' ); ?>
					</a>
					<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'gg_dismiss_welcome', '1' ), 'gg_dismiss_welcome' ) ); ?>"
					   class="button button-large">
						<?php esc_html_e( 'No Thanks, Start Fresh', 'greengrowth-impact-showcase' ); ?>
					</a>
					<span style="margin-left: 10px; font-size: 12px; color: #666;">
						<?php esc_html_e( 'You can always add sample data later from the Projects menu.', 'greengrowth-impact-showcase' ); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'admin_notices', 'gg_impact_show_welcome_screen' );

/**
 * Show success message after sample data installation.
 */
function gg_impact_show_success_message() {
	// Check if sample data was just created.
	if ( get_transient( 'gg_impact_sample_data_created' ) ) {
		delete_transient( 'gg_impact_sample_data_created' );

		// Count projects to show in message.
		$project_count = wp_count_posts( 'gg_project' );
		?>
		<div class="notice notice-success is-dismissible">
			<p>
				<span class="dashicons dashicons-yes-alt" style="color: #2c7a4a; margin-top: 3px;"></span>
				<strong><?php esc_html_e( 'Success!', 'greengrowth-impact-showcase' ); ?></strong>
				<?php
				printf(
					/* translators: %d: number of projects created */
					esc_html__( '%d sample projects have been created and are ready to use.', 'greengrowth-impact-showcase' ),
					(int) $project_count->publish
				);
				?>
			</p>
		</div>
		<?php
	}

	// Check if welcome was just dismissed.
	if ( get_transient( 'gg_impact_welcome_dismissed_msg' ) ) {
		delete_transient( 'gg_impact_welcome_dismissed_msg' );
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<?php esc_html_e( 'Welcome screen dismissed. You can start adding your own projects!', 'greengrowth-impact-showcase' ); ?>
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=gg_project' ) ); ?>" class="button button-small" style="margin-left: 10px;">
					<?php esc_html_e( 'Add Your First Project', 'greengrowth-impact-showcase' ); ?>
				</a>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'gg_impact_show_success_message' );

/**
 * Handle sample data installation from welcome screen.
 */
function gg_impact_handle_welcome_actions() {
	// Handle sample data installation.
	if ( isset( $_GET['gg_install_sample_data'] ) && isset( $_GET['_wpnonce'] ) ) {
		// Verify nonce.
		if ( ! wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'gg_install_sample_data' ) ) {
			gg_log_warning( 'Invalid nonce for sample data installation', 'security' );
			return;
		}

		// Check capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			gg_log_warning( 'Unauthorized user attempted sample data installation', 'security' );
			return;
		}

		// Mark welcome as dismissed.
		update_option( 'gg_impact_welcome_dismissed', true );

		// Ensure CPT and taxonomy are registered before creating projects.
		gg_register_project_post_type();
		gg_register_service_area_taxonomy();

		// Create sample projects.
		gg_log_info( 'Starting sample data installation from welcome screen', 'onboarding' );
		gg_create_sample_projects();

		// Count created projects to verify.
		$project_count = wp_count_posts( 'gg_project' );
		gg_log_info( "Sample data installation complete. Projects created: {$project_count->publish}", 'onboarding' );

		// Set transient for success message to show after redirect.
		set_transient( 'gg_impact_sample_data_created', true, 30 );

		// Redirect to projects page to show the new content.
		wp_safe_redirect( admin_url( 'edit.php?post_type=gg_project' ) );
		exit;
	}

	// Handle welcome dismissal.
	if ( isset( $_GET['gg_dismiss_welcome'] ) && isset( $_GET['_wpnonce'] ) ) {
		// Verify nonce.
		if ( ! wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'gg_dismiss_welcome' ) ) {
			gg_log_warning( 'Invalid nonce for welcome dismissal', 'security' );
			return;
		}

		// Check capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			gg_log_warning( 'Unauthorized user attempted welcome dismissal', 'security' );
			return;
		}

		// Mark as dismissed.
		update_option( 'gg_impact_welcome_dismissed', true );

		// Set transient for confirmation message.
		set_transient( 'gg_impact_welcome_dismissed_msg', true, 30 );

		// Redirect to projects page.
		wp_safe_redirect( admin_url( 'post-new.php?post_type=gg_project' ) );
		exit;
	}
}
add_action( 'admin_init', 'gg_impact_handle_welcome_actions' );

/**
 * Add "Install Sample Data" link to admin menu for users who dismissed welcome.
 */
function gg_impact_add_sample_data_submenu() {
	// Only show if no sample data exists and welcome was dismissed.
	$existing_projects = wp_count_posts( 'gg_project' );
	if ( $existing_projects->publish === 0 && get_option( 'gg_impact_welcome_dismissed', false ) ) {
		add_submenu_page(
			'edit.php?post_type=gg_project',
			__( 'Install Sample Data', 'greengrowth-impact-showcase' ),
			__( 'Install Sample Data', 'greengrowth-impact-showcase' ),
			'manage_options',
			'gg-install-sample-data',
			'gg_impact_render_sample_data_page'
		);
	}
}
add_action( 'admin_menu', 'gg_impact_add_sample_data_submenu' );

/**
 * Render sample data installation page.
 */
function gg_impact_render_sample_data_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Install Sample Data', 'greengrowth-impact-showcase' ); ?></h1>

		<div class="card" style="max-width: 800px; margin-top: 20px;">
			<h2><?php esc_html_e( 'Demo Content for Testing', 'greengrowth-impact-showcase' ); ?></h2>
			<p>
				<?php esc_html_e( 'Installing sample data will create 30 demonstration projects across 3 service areas:', 'greengrowth-impact-showcase' ); ?>
			</p>
			<ul style="margin-left: 20px; list-style: disc;">
				<li><strong><?php esc_html_e( 'Reforestation', 'greengrowth-impact-showcase' ); ?></strong> - <?php esc_html_e( '10 projects', 'greengrowth-impact-showcase' ); ?></li>
				<li><strong><?php esc_html_e( 'Carbon Capture', 'greengrowth-impact-showcase' ); ?></strong> - <?php esc_html_e( '10 projects', 'greengrowth-impact-showcase' ); ?></li>
				<li><strong><?php esc_html_e( 'Sustainable Farming', 'greengrowth-impact-showcase' ); ?></strong> - <?php esc_html_e( '10 projects', 'greengrowth-impact-showcase' ); ?></li>
			</ul>
			<p>
				<?php esc_html_e( 'Each project includes a title, description, featured image, and category assignment. This is helpful for testing the Impact Showcase block appearance and functionality.', 'greengrowth-impact-showcase' ); ?>
			</p>
			<p style="color: #d63638;">
				<span class="dashicons dashicons-warning" style="margin-top: 3px;"></span>
				<strong><?php esc_html_e( 'Note:', 'greengrowth-impact-showcase' ); ?></strong>
				<?php esc_html_e( 'Sample projects can be deleted individually or in bulk from the Projects page after installation.', 'greengrowth-impact-showcase' ); ?>
			</p>

			<p style="margin-top: 30px;">
				<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'gg_install_sample_data', '1' ), 'gg_install_sample_data' ) ); ?>"
				   class="button button-primary button-large">
					<span class="dashicons dashicons-download" style="margin-top: 3px;"></span>
					<?php esc_html_e( 'Install Sample Data Now', 'greengrowth-impact-showcase' ); ?>
				</a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=gg_project' ) ); ?>"
				   class="button button-large">
					<?php esc_html_e( 'Cancel', 'greengrowth-impact-showcase' ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
}

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
 * Handle the fix images action with rate limiting.
 */
function gg_handle_fix_images_action() {
	if ( ! isset( $_GET['gg_fix_images'] ) || ! isset( $_GET['_wpnonce'] ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( ! wp_verify_nonce( wp_unslash( $_GET['_wpnonce'] ), 'gg_fix_images' ) ) {
		gg_log_warning( 'Invalid nonce for fix images action', 'security' );
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		gg_log_warning( 'Unauthorized user attempted fix images action', 'security' );
		return;
	}

	// Rate limiting - prevent hammering the endpoint.
	$user_id       = get_current_user_id();
	$transient_key = "gg_fix_images_throttle_{$user_id}";

	if ( get_transient( $transient_key ) ) {
		add_action(
			'admin_notices',
			function () {
				echo '<div class="notice notice-error is-dismissible"><p>';
				esc_html_e( 'Please wait a minute before trying again.', 'greengrowth-impact-showcase' );
				echo '</p></div>';
			}
		);
		gg_log_warning( 'Rate limit triggered for fix images action', 'rate_limit' );
		return;
	}

	// Set rate limit transient (1 minute).
	set_transient( $transient_key, true, MINUTE_IN_SECONDS );

	try {
		$fixed_count = gg_fix_project_images();

		gg_log_info( "Fixed images for {$fixed_count} projects", 'fix_images' );

		add_action(
			'admin_notices',
			function () use ( $fixed_count ) {
				echo '<div class="notice notice-success is-dismissible"><p>';
				printf(
					/* translators: %d: number of projects fixed */
					esc_html__( 'Successfully added images to %d projects!', 'greengrowth-impact-showcase' ),
					(int) $fixed_count
				);
				echo '</p></div>';
			}
		);
	} catch ( Exception $e ) {
		gg_log_error( 'Failed to fix project images: ' . $e->getMessage(), 'fix_images' );

		add_action(
			'admin_notices',
			function () use ( $e ) {
				echo '<div class="notice notice-error is-dismissible"><p>';
				esc_html_e( 'An error occurred while fixing images. Please check the error log.', 'greengrowth-impact-showcase' );
				echo '</p></div>';
			}
		);
	}
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

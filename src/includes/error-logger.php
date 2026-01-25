<?php
/**
 * Error Logging Utility.
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Log an error message.
 *
 * @param string $message Error message.
 * @param string $context Context (e.g., 'activation', 'query', 'image_download').
 * @param mixed  $data    Additional data to log.
 */
function gg_log_error( $message, $context = 'general', $data = null ) {
	// Only log if WP_DEBUG is enabled.
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	$log_message = sprintf(
		'[GG Impact Showcase] [%s] %s',
		strtoupper( $context ),
		$message
	);

	if ( $data ) {
		$log_message .= ' | Data: ' . wp_json_encode( $data );
	}

	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( $log_message );

	// Store in transient for admin notices (optional).
	if ( is_admin() ) {
		$errors = get_transient( 'gg_recent_errors' );
		if ( ! is_array( $errors ) ) {
			$errors = array();
		}
		$errors[] = array(
			'message'   => $message,
			'context'   => $context,
			'data'      => $data,
			'timestamp' => time(),
		);

		// Keep only last 10 errors.
		$errors = array_slice( $errors, -10 );

		set_transient( 'gg_recent_errors', $errors, DAY_IN_SECONDS );
	}
}

/**
 * Log a warning message.
 *
 * @param string $message Warning message.
 * @param string $context Context.
 */
function gg_log_warning( $message, $context = 'general' ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}

	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( sprintf( '[GG Impact Showcase] [WARNING] [%s] %s', strtoupper( $context ), $message ) );
}

/**
 * Log an info message.
 *
 * @param string $message Info message.
 * @param string $context Context.
 */
function gg_log_info( $message, $context = 'general' ) {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG || ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
		return;
	}

	// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	error_log( sprintf( '[GG Impact Showcase] [INFO] [%s] %s', strtoupper( $context ), $message ) );
}

/**
 * Display admin notice for errors.
 */
function gg_display_error_notices() {
	$errors = get_transient( 'gg_recent_errors' );

	if ( ! $errors || ! is_array( $errors ) ) {
		return;
	}

	// Only show to administrators.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Show most recent error only.
	$latest_error = end( $errors );
	$age_seconds  = time() - $latest_error['timestamp'];

	// Only show errors from last hour.
	if ( $age_seconds > HOUR_IN_SECONDS ) {
		return;
	}

	?>
	<div class="notice notice-error is-dismissible">
		<p>
			<strong><?php esc_html_e( 'Impact Showcase Error:', 'greengrowth-impact-showcase' ); ?></strong>
			<?php echo esc_html( $latest_error['message'] ); ?>
		</p>
		<?php if ( isset( $latest_error['context'] ) ) : ?>
			<p>
				<em>
					<?php
					/* translators: %s: error context. */
					echo esc_html( sprintf( __( 'Context: %s', 'greengrowth-impact-showcase' ), $latest_error['context'] ) );
					?>
				</em>
			</p>
		<?php endif; ?>
	</div>
	<?php
}
add_action( 'admin_notices', 'gg_display_error_notices' );

/**
 * Clear error log.
 */
function gg_clear_error_log() {
	delete_transient( 'gg_recent_errors' );
}

/**
 * Get recent errors (for debugging).
 *
 * @return array Recent errors.
 */
function gg_get_recent_errors() {
	$errors = get_transient( 'gg_recent_errors' );
	if ( ! is_array( $errors ) ) {
		return array();
	}

	return $errors;
}

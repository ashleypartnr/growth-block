<?php
/**
 * Projects Manager helpers.
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the Projects Manager instance.
 *
 * @return GG_Projects_Manager
 */
function gg_get_projects_manager() {
	return GG_Projects_Manager::get_instance();
}

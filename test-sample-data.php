<?php
/**
 * Test Script for Sample Data Creation
 * 
 * To use: Visit this URL in your browser (replace with your site URL):
 * http://your-site.local/wp-content/plugins/greengrowth-impact-showcase/test-sample-data.php
 * 
 * Or run via WP-CLI:
 * wp eval-file wp-content/plugins/greengrowth-impact-showcase/test-sample-data.php
 */

// Load WordPress.
require_once dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/wp-load.php';

// Check permissions.
if ( ! current_user_can( 'manage_options' ) ) {
	die( 'ERROR: You must be an administrator to run this script.' );
}

echo "=== GreenGrowth Impact Showcase - Sample Data Test ===\n\n";

// Check if CPT is registered.
$post_types = get_post_types();
echo "1. Checking if 'gg_project' CPT is registered... ";
if ( in_array( 'gg_project', $post_types, true ) ) {
	echo "✓ YES\n";
} else {
	echo "✗ NO (This is the problem!)\n";
	echo "   Registering now...\n";
	if ( function_exists( 'gg_register_project_post_type' ) ) {
		gg_register_project_post_type();
		echo "   ✓ Registered\n";
	} else {
		echo "   ✗ Function not found\n";
	}
}

// Check if taxonomy is registered.
$taxonomies = get_taxonomies();
echo "2. Checking if 'gg_service_area' taxonomy is registered... ";
if ( in_array( 'gg_service_area', $taxonomies, true ) ) {
	echo "✓ YES\n";
} else {
	echo "✗ NO (This is the problem!)\n";
	echo "   Registering now...\n";
	if ( function_exists( 'gg_register_service_area_taxonomy' ) ) {
		gg_register_service_area_taxonomy();
		echo "   ✓ Registered\n";
	} else {
		echo "   ✗ Function not found\n";
	}
}

// Check current project count.
$existing = wp_count_posts( 'gg_project' );
echo "3. Current project count: {$existing->publish} published\n\n";

// Ask if we should create sample data.
if ( $existing->publish > 0 ) {
	echo "⚠️  WARNING: You already have {$existing->publish} projects.\n";
	echo "This script will create 30 MORE projects.\n\n";
}

echo "Creating sample projects...\n";

// Create sample projects.
if ( function_exists( 'gg_create_sample_projects' ) ) {
	gg_create_sample_projects();
	
	// Check new count.
	$new_count = wp_count_posts( 'gg_project' );
	echo "\n✓ Sample data creation complete!\n";
	echo "Total projects now: {$new_count->publish}\n\n";
	
	// List service areas.
	$terms = get_terms( array(
		'taxonomy' => 'gg_service_area',
		'hide_empty' => false,
	) );
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		echo "Service Areas created:\n";
		foreach ( $terms as $term ) {
			$count = $term->count;
			echo "  - {$term->name} ({$count} projects)\n";
		}
	}
	
	echo "\nView your projects: " . admin_url( 'edit.php?post_type=gg_project' ) . "\n";
} else {
	echo "✗ ERROR: gg_create_sample_projects() function not found!\n";
	echo "Make sure the plugin is activated.\n";
}

echo "\n=== Test Complete ===\n";

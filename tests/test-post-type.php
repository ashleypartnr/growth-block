<?php
/**
 * Tests for Project post type and taxonomy registration.
 *
 * @package GreenGrowth_Impact_Showcase
 */

/**
 * Test case for CPT and taxonomy registration.
 */
class Test_Post_Type extends WP_UnitTestCase {

	/**
	 * Test that the project post type is registered.
	 */
	public function test_project_post_type_registered() {
		$this->assertTrue( post_type_exists( 'gg_project' ), 'Project post type should be registered' );
	}

	/**
	 * Test that the service area taxonomy is registered.
	 */
	public function test_service_area_taxonomy_registered() {
		$this->assertTrue( taxonomy_exists( 'gg_service_area' ), 'Service area taxonomy should be registered' );
	}

	/**
	 * Test that service area taxonomy is associated with project post type.
	 */
	public function test_taxonomy_associated_with_post_type() {
		$taxonomies = get_object_taxonomies( 'gg_project' );

		$this->assertContains( 'gg_service_area', $taxonomies, 'Service area taxonomy should be associated with project post type' );
	}

	/**
	 * Test post type supports.
	 */
	public function test_post_type_supports() {
		$this->assertTrue( post_type_supports( 'gg_project', 'title' ) );
		$this->assertTrue( post_type_supports( 'gg_project', 'editor' ) );
		$this->assertTrue( post_type_supports( 'gg_project', 'excerpt' ) );
		$this->assertTrue( post_type_supports( 'gg_project', 'thumbnail' ) );
	}

	/**
	 * Test that REST API is enabled.
	 */
	public function test_rest_api_enabled() {
		$post_type = get_post_type_object( 'gg_project' );

		$this->assertTrue( $post_type->show_in_rest, 'Project post type should be available in REST API' );
		$this->assertEquals( 'projects', $post_type->rest_base );
	}

	/**
	 * Test taxonomy REST API.
	 */
	public function test_taxonomy_rest_api_enabled() {
		$taxonomy = get_taxonomy( 'gg_service_area' );

		$this->assertTrue( $taxonomy->show_in_rest, 'Service area taxonomy should be available in REST API' );
		$this->assertEquals( 'service-areas', $taxonomy->rest_base );
	}

	/**
	 * Test creating a project with service area.
	 */
	public function test_create_project_with_service_area() {
		// Create a service area term.
		$term = wp_insert_term( 'Test Area', 'gg_service_area' );

		$this->assertIsArray( $term );
		$this->assertArrayHasKey( 'term_id', $term );

		// Create a project.
		$post_id = $this->factory->post->create(
			array(
				'post_type'   => 'gg_project',
				'post_title'  => 'Test Project',
				'post_status' => 'publish',
			)
		);

		// Assign the term.
		wp_set_object_terms( $post_id, $term['term_id'], 'gg_service_area' );

		// Verify.
		$terms = get_the_terms( $post_id, 'gg_service_area' );

		$this->assertIsArray( $terms );
		$this->assertCount( 1, $terms );
		$this->assertEquals( 'Test Area', $terms[0]->name );
	}

	/**
	 * Test REST API custom fields.
	 */
	public function test_rest_api_custom_fields() {
		global $wp_rest_server;

		// Initialize REST server.
		$wp_rest_server = new WP_REST_Server();
		do_action( 'rest_api_init' );

		// Get REST schema.
		$request  = new WP_REST_Request( 'OPTIONS', '/wp/v2/projects' );
		$response = rest_get_server()->dispatch( $request );
		$data     = $response->get_data();

		$this->assertArrayHasKey( 'schema', $data );
		$this->assertArrayHasKey( 'properties', $data['schema'] );

		// Check for our custom fields.
		$this->assertArrayHasKey( 'featured_image_data', $data['schema']['properties'] );
		$this->assertArrayHasKey( 'service_areas_data', $data['schema']['properties'] );
	}
}

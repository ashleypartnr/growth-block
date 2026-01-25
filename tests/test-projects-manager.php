<?php
/**
 * Tests for GG_Projects_Manager class.
 *
 * @package GreenGrowth_Impact_Showcase
 */

/**
 * Sample test case for Projects Manager.
 */
class Test_Projects_Manager extends WP_UnitTestCase {

	/**
	 * Test that the Projects Manager singleton works correctly.
	 */
	public function test_singleton_instance() {
		$instance1 = gg_get_projects_manager();
		$instance2 = gg_get_projects_manager();

		$this->assertSame( $instance1, $instance2, 'Projects Manager should return the same instance' );
		$this->assertInstanceOf( 'GG_Projects_Manager', $instance1 );
	}

	/**
	 * Test that get_all_projects returns an array.
	 */
	public function test_get_all_projects_returns_array() {
		$manager = gg_get_projects_manager();
		$projects = $manager->get_all_projects();

		$this->assertIsArray( $projects );
	}

	/**
	 * Test that project data has required keys.
	 */
	public function test_project_data_structure() {
		// Create a test project.
		$post_id = $this->factory->post->create(
			array(
				'post_type'   => 'gg_project',
				'post_title'  => 'Test Project',
				'post_status' => 'publish',
			)
		);

		$manager = gg_get_projects_manager();

		// Invalidate cache to force fresh query.
		$manager->invalidate_cache();

		$projects = $manager->get_all_projects();

		$this->assertNotEmpty( $projects );

		$project = $projects[0];
		$this->assertArrayHasKey( 'id', $project );
		$this->assertArrayHasKey( 'title', $project );
		$this->assertArrayHasKey( 'excerpt', $project );
		$this->assertArrayHasKey( 'link', $project );
		$this->assertArrayHasKey( 'image', $project );
		$this->assertArrayHasKey( 'serviceAreas', $project );
	}

	/**
	 * Test that cache invalidation works.
	 */
	public function test_cache_invalidation() {
		$manager = gg_get_projects_manager();

		// Get projects to populate cache.
		$projects1 = $manager->get_all_projects();

		// Create a new project.
		$this->factory->post->create(
			array(
				'post_type'   => 'gg_project',
				'post_title'  => 'New Test Project',
				'post_status' => 'publish',
			)
		);

		// Manually invalidate cache (normally done by hook).
		$manager->invalidate_cache();

		// Get projects again.
		$projects2 = $manager->get_all_projects();

		// Should have one more project.
		$this->assertCount( count( $projects1 ) + 1, $projects2 );
	}

	/**
	 * Test projects count.
	 */
	public function test_get_projects_count() {
		// Create test projects.
		$this->factory->post->create_many(
			3,
			array(
				'post_type'   => 'gg_project',
				'post_status' => 'publish',
			)
		);

		$manager = gg_get_projects_manager();
		$manager->invalidate_cache();

		$count = $manager->get_projects_count();

		$this->assertEquals( 3, $count );
	}
}

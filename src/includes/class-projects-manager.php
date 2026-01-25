<?php
/**
 * Projects Manager - Singleton for project data management and caching
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton class for managing project data with caching.
 */
class GG_Projects_Manager {
	/**
	 * Singleton instance.
	 *
	 * @var GG_Projects_Manager|null
	 */
	private static $instance = null;

	/**
	 * Cached projects data.
	 *
	 * @var array|null
	 */
	private $projects = null;

	/**
	 * Cache key for projects.
	 *
	 * @var string
	 */
	private $cache_key = 'gg_all_projects_data';

	/**
	 * Cache group.
	 *
	 * @var string
	 */
	private $cache_group = 'gg_projects';

	/**
	 * Cache duration in seconds.
	 *
	 * @var int
	 */
	private $cache_duration = HOUR_IN_SECONDS;

	/**
	 * Private constructor to enforce singleton pattern.
	 */
	private function __construct() {
		// Hook into post save to invalidate cache.
		add_action( 'save_post_gg_project', array( $this, 'invalidate_cache' ) );
		add_action( 'delete_post', array( $this, 'on_delete_project' ), 10, 2 );
		add_action( 'edited_gg_service_area', array( $this, 'invalidate_cache' ) );
		add_action( 'created_gg_service_area', array( $this, 'invalidate_cache' ) );
		add_action( 'deleted_gg_service_area', array( $this, 'invalidate_cache' ) );
	}

	/**
	 * Get singleton instance.
	 *
	 * @return GG_Projects_Manager
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get all projects with caching.
	 *
	 * @return array Array of project data.
	 */
	public function get_all_projects() {
		// Return from memory if already loaded.
		if ( null !== $this->projects ) {
			return $this->projects;
		}

		// Try to get from cache.
		$cached = wp_cache_get( $this->cache_key, $this->cache_group );
		if ( false !== $cached && is_array( $cached ) ) {
			$this->projects = $cached;
			return $this->projects;
		}

		// Query projects.
		$this->projects = $this->query_projects();

		// Cache the results.
		wp_cache_set( $this->cache_key, $this->projects, $this->cache_group, $this->cache_duration );

		return $this->projects;
	}

	/**
	 * Query projects from database.
	 *
	 * @return array Array of project data.
	 */
	private function query_projects() {
		$query_args = array(
			'post_type'      => 'gg_project',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		/**
		 * Filter the query arguments for projects.
		 *
		 * @param array $query_args WP_Query arguments.
		 */
		$query_args = apply_filters( 'gg_projects_query_args', $query_args );

		$projects_query = new WP_Query( $query_args );
		$projects_data  = array();

		if ( $projects_query->have_posts() ) {
			while ( $projects_query->have_posts() ) {
				$projects_query->the_post();
				$post_id = get_the_ID();

				$project_data = $this->get_project_data( $post_id );

				/**
				 * Filter individual project data.
				 *
				 * @param array $project_data Project data array.
				 * @param int   $post_id      Post ID.
				 */
				$project_data = apply_filters( 'gg_project_data', $project_data, $post_id );

				$projects_data[] = $project_data;
			}
			wp_reset_postdata();
		}

		return $projects_data;
	}

	/**
	 * Get data for a single project.
	 *
	 * @param int $post_id Post ID.
	 * @return array Project data.
	 */
	private function get_project_data( $post_id ) {
		// Get featured image.
		$image_id  = get_post_thumbnail_id( $post_id );
		$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : '';
		$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : get_the_title();

		// If no featured image, check for placeholder.
		if ( ! $image_url ) {
			$image_url = get_post_meta( $post_id, '_gg_placeholder_image', true );
		}

		// Get service areas.
		$terms         = get_the_terms( $post_id, 'gg_service_area' );
		$service_areas = array();
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$service_areas[] = $term->slug;
			}
		}

		// Get excerpt or generate from content.
		$excerpt = get_the_excerpt();
		if ( empty( $excerpt ) ) {
			$excerpt = wp_trim_words( get_the_content(), 25, '...' );
		}

		return array(
			'id'           => $post_id,
			'title'        => get_the_title(),
			'excerpt'      => $excerpt,
			'link'         => get_permalink(),
			'image'        => array(
				'url' => $image_url ? $image_url : 'https://placehold.co/800x600/CCCCCC/666666/png?text=No+Image',
				'alt' => $image_alt ? $image_alt : get_the_title(),
			),
			'serviceAreas' => $service_areas,
		);
	}

	/**
	 * Invalidate cache.
	 */
	public function invalidate_cache() {
		wp_cache_delete( $this->cache_key, $this->cache_group );
		$this->projects = null;

		// Log cache invalidation for debugging.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'GG Impact Showcase: Cache invalidated' );
		}
	}

	/**
	 * Handle project deletion.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function on_delete_project( $post_id, $post ) {
		if ( 'gg_project' === $post->post_type ) {
			$this->invalidate_cache();
		}
	}

	/**
	 * Get projects count for monitoring.
	 *
	 * @return int Number of published projects.
	 */
	public function get_projects_count() {
		$projects = $this->get_all_projects();
		return count( $projects );
	}

	/**
	 * Clear all caches (useful for debugging).
	 */
	public function clear_all_caches() {
		wp_cache_flush();
		$this->projects = null;
	}
}

/**
 * Get the Projects Manager instance.
 *
 * @return GG_Projects_Manager
 */
function gg_get_projects_manager() {
	return GG_Projects_Manager::get_instance();
}

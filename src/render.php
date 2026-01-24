<?php
/**
 * Server-side rendering for the Impact Showcase block.
 *
 * @package GreenGrowth_Impact_Showcase
 *
 * @var array $attributes Block attributes.
 * @var string $content Block default content.
 * @var WP_Block $block Block instance.
 */

// Helper function to get color with support for transparent when explicitly cleared.
if ( ! function_exists( 'gg_get_color_value' ) ) {
	function gg_get_color_value( $attributes, $key, $default ) {
		if ( array_key_exists( $key, $attributes ) ) {
			// Key exists - use value or transparent if empty/null.
			return ! empty( $attributes[ $key ] ) ? $attributes[ $key ] : 'transparent';
		}
		// Key doesn't exist - use default for new blocks.
		return $default;
	}
}

// Extract block attributes with defaults.
$primary_color = gg_get_color_value( $attributes, 'primaryColor', '#1a1a1a' );
$accent_color = gg_get_color_value( $attributes, 'accentColor', '#d4af37' );
$background_color = gg_get_color_value( $attributes, 'backgroundColor', '#ffffff' );
$text_color = gg_get_color_value( $attributes, 'textColor', '#1a1a1a' );
$card_bg_color = gg_get_color_value( $attributes, 'cardBackgroundColor', '#ffffff' );
$title_font_size = isset( $attributes['titleFontSize'] ) ? $attributes['titleFontSize'] : 24;
$excerpt_font_size = isset( $attributes['excerptFontSize'] ) ? $attributes['excerptFontSize'] : 16;
$button_font_size = isset( $attributes['buttonFontSize'] ) ? $attributes['buttonFontSize'] : 14;
$card_border_radius = isset( $attributes['cardBorderRadius'] ) ? $attributes['cardBorderRadius'] : 0;
$card_gap = isset( $attributes['cardGap'] ) ? $attributes['cardGap'] : 32;
$shadow_intensity = isset( $attributes['shadowIntensity'] ) ? $attributes['shadowIntensity'] : 'medium';
$overlay_opacity = isset( $attributes['overlayOpacity'] ) ? $attributes['overlayOpacity'] : 0;
$button_style = isset( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'minimal';
$button_bg_color = gg_get_color_value( $attributes, 'buttonBackgroundColor', '#ffffff' );
$button_text_color = gg_get_color_value( $attributes, 'buttonTextColor', '#1a1a1a' );
$button_border_color = gg_get_color_value( $attributes, 'buttonBorderColor', '#e0e0e0' );
$button_active_bg = gg_get_color_value( $attributes, 'buttonActiveBackgroundColor', '#1a1a1a' );
$button_active_text = gg_get_color_value( $attributes, 'buttonActiveTextColor', '#ffffff' );
$button_active_border = gg_get_color_value( $attributes, 'buttonActiveBorderColor', '#1a1a1a' );
$button_border_radius = isset( $attributes['buttonBorderRadius'] ) ? $attributes['buttonBorderRadius'] : 0;
$button_border_width = isset( $attributes['buttonBorderWidth'] ) ? $attributes['buttonBorderWidth'] : 1;
$button_hover_bg = isset( $attributes['buttonHoverBackgroundColor'] ) ? $attributes['buttonHoverBackgroundColor'] : '#1a1a1a';
$button_hover_text = isset( $attributes['buttonHoverTextColor'] ) ? $attributes['buttonHoverTextColor'] : '#d4af37';
$button_hover_border = isset( $attributes['buttonHoverBorderColor'] ) ? $attributes['buttonHoverBorderColor'] : '#d4af37';
$mobile_button_text = gg_get_color_value( $attributes, 'mobileButtonTextColor', '#1b3a2f' );
$mobile_button_bg = gg_get_color_value( $attributes, 'mobileButtonBackgroundColor', '#f0f0f0' );
$mobile_button_active_text = gg_get_color_value( $attributes, 'mobileButtonActiveTextColor', '#ffffff' );
$mobile_button_active_bg = gg_get_color_value( $attributes, 'mobileButtonActiveBackgroundColor', '#1b3a2f' );
$mobile_button_hover_text = gg_get_color_value( $attributes, 'mobileButtonHoverTextColor', '#ffffff' );
$mobile_button_hover_bg = gg_get_color_value( $attributes, 'mobileButtonHoverBackgroundColor', '#c9a961' );
$show_explore_button = isset( $attributes['showExploreButton'] ) ? $attributes['showExploreButton'] : true;
$explore_button_text = isset( $attributes['exploreButtonText'] ) ? $attributes['exploreButtonText'] : 'Explore More';
$explore_button_bg = isset( $attributes['exploreButtonBackgroundColor'] ) ? $attributes['exploreButtonBackgroundColor'] : '#1a1a1a';
$explore_button_text_color = isset( $attributes['exploreButtonTextColor'] ) ? $attributes['exploreButtonTextColor'] : '#ffffff';
$explore_button_border = isset( $attributes['exploreButtonBorderColor'] ) ? $attributes['exploreButtonBorderColor'] : '#1a1a1a';
$explore_button_border_width = isset( $attributes['exploreButtonBorderWidth'] ) ? $attributes['exploreButtonBorderWidth'] : 0;
$explore_button_radius = isset( $attributes['exploreButtonBorderRadius'] ) ? $attributes['exploreButtonBorderRadius'] : 0;
$explore_button_hover_bg = isset( $attributes['exploreButtonHoverBackgroundColor'] ) ? $attributes['exploreButtonHoverBackgroundColor'] : '#d4af37';
$explore_button_hover_text = isset( $attributes['exploreButtonHoverTextColor'] ) ? $attributes['exploreButtonHoverTextColor'] : '#1a1a1a';
$posts_per_page = isset( $attributes['postsPerPage'] ) ? $attributes['postsPerPage'] : 3;

// Query all projects.
$projects_query = new WP_Query(
	array(
		'post_type'      => 'gg_project',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

// Prepare projects data for Interactivity API.
$projects_data = array();

if ( $projects_query->have_posts() ) {
	while ( $projects_query->have_posts() ) {
		$projects_query->the_post();
		$post_id = get_the_ID();

		// Get featured image.
		$image_id   = get_post_thumbnail_id( $post_id );
		$image_url  = $image_id ? wp_get_attachment_image_url( $image_id, 'medium_large' ) : '';
		$image_alt  = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : get_the_title();

		// If no featured image, check for placeholder.
		if ( ! $image_url ) {
			$image_url = get_post_meta( $post_id, '_gg_placeholder_image', true );
		}

		// Get service areas.
		$terms          = get_the_terms( $post_id, 'gg_service_area' );
		$service_areas  = array();
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

		$projects_data[] = array(
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
	wp_reset_postdata();
}

// Get service area terms for filter buttons.
$service_area_terms = get_terms(
	array(
		'taxonomy'   => 'gg_service_area',
		'hide_empty' => true,
	)
);

// Initial context for Interactivity API.
$displayed_projects = array_slice( $projects_data, 0, $posts_per_page );
$context = array(
	'selectedArea'      => 'all',
	'selectedAreaLabel' => __( 'All Projects', 'greengrowth-impact-showcase' ),
	'allProjects'       => $projects_data,
	'filteredProjects'  => $projects_data,
	'displayedProjects' => $displayed_projects,
	'postsPerPage'      => $posts_per_page,
	'currentOffset'     => $posts_per_page,
	'isLoading'         => false,
	'hasMore'           => count( $projects_data ) > $posts_per_page,
);

// Build inline styles from block attributes.
$inline_styles = sprintf(
	'--gg-primary-color: %s; --gg-accent-color: %s; --gg-background-color: %s; --gg-text-color: %s; --gg-card-bg-color: %s; --gg-title-size: %spx; --gg-excerpt-size: %spx; --gg-button-size: %spx; --gg-card-radius: %spx; --gg-card-gap: %spx; --gg-overlay-opacity: %s; --gg-button-bg: %s; --gg-button-text: %s; --gg-button-border: %s; --gg-button-active-bg: %s; --gg-button-active-text: %s; --gg-button-active-border: %s; --gg-button-radius: %spx; --gg-button-border-width: %spx; --gg-button-hover-bg: %s; --gg-button-hover-text: %s; --gg-button-hover-border: %s; --gg-mobile-button-text: %s; --gg-mobile-button-bg: %s; --gg-mobile-button-active-text: %s; --gg-mobile-button-active-bg: %s; --gg-mobile-button-hover-text: %s; --gg-mobile-button-hover-bg: %s; --gg-explore-btn-bg: %s; --gg-explore-btn-text: %s; --gg-explore-btn-border: %s; --gg-explore-btn-border-width: %spx; --gg-explore-btn-radius: %spx; --gg-explore-btn-hover-bg: %s; --gg-explore-btn-hover-text: %s;',
	esc_attr( $primary_color ),
	esc_attr( $accent_color ),
	esc_attr( $background_color ),
	esc_attr( $text_color ),
	esc_attr( $card_bg_color ),
	esc_attr( $title_font_size ),
	esc_attr( $excerpt_font_size ),
	esc_attr( $button_font_size ),
	esc_attr( $card_border_radius ),
	esc_attr( $card_gap ),
	esc_attr( $overlay_opacity / 100 ),
	esc_attr( $button_bg_color ),
	esc_attr( $button_text_color ),
	esc_attr( $button_border_color ),
	esc_attr( $button_active_bg ),
	esc_attr( $button_active_text ),
	esc_attr( $button_active_border ),
	esc_attr( $button_border_radius ),
	esc_attr( $button_border_width ),
	esc_attr( $button_hover_bg ),
	esc_attr( $button_hover_text ),
	esc_attr( $button_hover_border ),
	esc_attr( $mobile_button_text ),
	esc_attr( $mobile_button_bg ),
	esc_attr( $mobile_button_active_text ),
	esc_attr( $mobile_button_active_bg ),
	esc_attr( $mobile_button_hover_text ),
	esc_attr( $mobile_button_hover_bg ),
	esc_attr( $explore_button_bg ),
	esc_attr( $explore_button_text_color ),
	esc_attr( $explore_button_border ),
	esc_attr( $explore_button_border_width ),
	esc_attr( $explore_button_radius ),
	esc_attr( $explore_button_hover_bg ),
	esc_attr( $explore_button_hover_text )
);

// Wrapper classes and attributes.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                 => 'wp-block-greengrowth-impact-showcase',
	'data-wp-interactive'   => 'greengrowth-showcase',
	'data-wp-context'       => wp_json_encode( $context ),
	'data-show-explore-button' => $show_explore_button ? 'true' : 'false',
	'data-explore-button-text' => esc_attr( $explore_button_text ),
	'data-shadow'           => esc_attr( $shadow_intensity ),
	'style'                 => $inline_styles,
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>

	<nav class="gg-filter-buttons" role="group" aria-label="<?php esc_attr_e( 'Filter projects by service area', 'greengrowth-impact-showcase' ); ?>" data-button-style="<?php echo esc_attr( $button_style ); ?>" data-wp-init="callbacks.initStickyFilterBar">
		<button
			type="button"
			class="gg-filter-button active"
			data-wp-on--click="actions.filterByArea"
			data-wp-class--active="state.isActive"
			data-wp-bind--aria-pressed="state.isActive"
			data-wp-context='<?php echo wp_json_encode( array( 'buttonArea' => 'all' ) ); ?>'
			data-area="all"
			aria-pressed="true">
			<?php esc_html_e( 'All Projects', 'greengrowth-impact-showcase' ); ?>
		</button>

		<?php if ( ! empty( $service_area_terms ) && ! is_wp_error( $service_area_terms ) ) : ?>
			<?php foreach ( $service_area_terms as $term ) : ?>
				<button
					type="button"
					class="gg-filter-button"
					data-wp-on--click="actions.filterByArea"
					data-wp-class--active="state.isActive"
					data-wp-bind--aria-pressed="state.isActive"
					data-wp-context='<?php echo wp_json_encode( array( 'buttonArea' => $term->slug ) ); ?>'
					data-area="<?php echo esc_attr( $term->slug ); ?>"
					aria-pressed="false">
					<?php echo esc_html( $term->name ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>
	</nav>

	<div class="gg-projects-grid" data-wp-init="callbacks.initCardHeightNormalization">
		<?php if ( ! empty( $displayed_projects ) ) : ?>
			<?php foreach ( $displayed_projects as $project ) : ?>
				<article class="gg-project-card" data-wp-key="<?php echo esc_attr( $project['id'] ); ?>" data-service-areas="<?php echo esc_attr( implode( ',', $project['serviceAreas'] ) ); ?>">
					<a href="<?php echo esc_url( $project['link'] ); ?>" class="gg-project-card-link">
						<div class="gg-project-image">
							<?php if ( ! empty( $project['image']['url'] ) ) : ?>
								<img
									src="<?php echo esc_url( $project['image']['url'] ); ?>"
									alt="<?php echo esc_attr( $project['image']['alt'] ); ?>"
									loading="lazy"
									width="800"
									height="600" />
							<?php endif; ?>
						</div>
						<div class="gg-project-content">
							<h3 class="gg-project-title"><?php echo esc_html( $project['title'] ); ?></h3>
							<p class="gg-project-excerpt"><?php echo esc_html( $project['excerpt'] ); ?></p>
							<?php if ( $show_explore_button ) : ?>
								<span class="gg-explore-button">
									<?php echo esc_html( $explore_button_text ); ?>
									<svg class="gg-explore-icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<path d="M4 10H16M16 10L11 5M16 10L11 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</span>
							<?php endif; ?>
						</div>
					</a>
				</article>
			<?php endforeach; ?>
		<?php else : ?>
			<div class="gg-empty-state">
				<p><?php esc_html_e( 'No projects found.', 'greengrowth-impact-showcase' ); ?></p>
			</div>
		<?php endif; ?>
	</div>

	<!-- Sentinel element for infinite scroll -->
	<div class="gg-scroll-sentinel" data-wp-init="callbacks.initInfiniteScroll"></div>

	<!-- Loading indicator -->
	<div class="gg-loading-indicator" data-wp-class--visible="state.isLoading" style="display: none;">
		<span class="gg-spinner"></span>
		<p><?php esc_html_e( 'Loading more projects...', 'greengrowth-impact-showcase' ); ?></p>
	</div>

	<!-- Empty state (shown when filtering) -->
	<div class="gg-empty-state" data-wp-class--hidden="state.hasProjects" style="display: none;">
		<p><?php esc_html_e( 'No projects found in this category.', 'greengrowth-impact-showcase' ); ?></p>
	</div>

	<!-- Accessibility: Live region for screen readers -->
	<div class="sr-only" aria-live="polite" aria-atomic="true">
		<span data-wp-text="state.announcement"></span>
	</div>

</div>

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
	/**
	 * Get an attribute color value with a fallback.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $key        Attribute key.
	 * @param string $fallback   Default value when attribute is missing.
	 * @return string
	 */
	function gg_get_color_value( $attributes, $key, $fallback ) {
		if ( array_key_exists( $key, $attributes ) ) {
			// Key exists - use value or transparent if empty/null.
			return ! empty( $attributes[ $key ] ) ? $attributes[ $key ] : 'transparent';
		}
		// Key doesn't exist - use default for new blocks.
		return $fallback;
	}
}

// Extract block attributes with defaults.
$primary_color               = gg_get_color_value( $attributes, 'primaryColor', '#1a1a1a' );
$accent_color                = gg_get_color_value( $attributes, 'accentColor', '#d4af37' );
$background_color            = gg_get_color_value( $attributes, 'backgroundColor', '#ffffff' );
$text_color                  = gg_get_color_value( $attributes, 'textColor', '#1a1a1a' );
$card_bg_color               = gg_get_color_value( $attributes, 'cardBackgroundColor', '#ffffff' );
$title_font_size             = isset( $attributes['titleFontSize'] ) ? $attributes['titleFontSize'] : 24;
$excerpt_font_size           = isset( $attributes['excerptFontSize'] ) ? $attributes['excerptFontSize'] : 16;
$button_font_size            = isset( $attributes['buttonFontSize'] ) ? $attributes['buttonFontSize'] : 14;
$card_border_radius          = isset( $attributes['cardBorderRadius'] ) ? $attributes['cardBorderRadius'] : 0;
$card_gap                    = isset( $attributes['cardGap'] ) ? $attributes['cardGap'] : 32;
$shadow_intensity            = isset( $attributes['shadowIntensity'] ) ? $attributes['shadowIntensity'] : 'medium';
$overlay_opacity             = isset( $attributes['overlayOpacity'] ) ? $attributes['overlayOpacity'] : 0;
$button_style                = isset( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'minimal';
$button_bg_color             = gg_get_color_value( $attributes, 'buttonBackgroundColor', '#ffffff' );
$button_text_color           = gg_get_color_value( $attributes, 'buttonTextColor', '#1a1a1a' );
$button_border_color         = gg_get_color_value( $attributes, 'buttonBorderColor', '#e0e0e0' );
$button_active_bg            = gg_get_color_value( $attributes, 'buttonActiveBackgroundColor', '#1a1a1a' );
$button_active_text          = gg_get_color_value( $attributes, 'buttonActiveTextColor', '#ffffff' );
$button_active_border        = gg_get_color_value( $attributes, 'buttonActiveBorderColor', '#1a1a1a' );
$button_border_radius        = isset( $attributes['buttonBorderRadius'] ) ? $attributes['buttonBorderRadius'] : 0;
$button_border_width         = isset( $attributes['buttonBorderWidth'] ) ? $attributes['buttonBorderWidth'] : 1;
$button_hover_bg             = isset( $attributes['buttonHoverBackgroundColor'] ) ? $attributes['buttonHoverBackgroundColor'] : '#1a1a1a';
$button_hover_text           = isset( $attributes['buttonHoverTextColor'] ) ? $attributes['buttonHoverTextColor'] : '#d4af37';
$button_hover_border         = isset( $attributes['buttonHoverBorderColor'] ) ? $attributes['buttonHoverBorderColor'] : '#d4af37';
$mobile_button_text          = gg_get_color_value( $attributes, 'mobileButtonTextColor', '#1b3a2f' );
$mobile_button_bg            = gg_get_color_value( $attributes, 'mobileButtonBackgroundColor', '#f0f0f0' );
$mobile_button_active_text   = gg_get_color_value( $attributes, 'mobileButtonActiveTextColor', '#ffffff' );
$mobile_button_active_bg     = gg_get_color_value( $attributes, 'mobileButtonActiveBackgroundColor', '#1b3a2f' );
$mobile_button_hover_text    = gg_get_color_value( $attributes, 'mobileButtonHoverTextColor', '#ffffff' );
$mobile_button_hover_bg      = gg_get_color_value( $attributes, 'mobileButtonHoverBackgroundColor', '#c9a961' );
$show_explore_button         = isset( $attributes['showExploreButton'] ) ? $attributes['showExploreButton'] : true;
$explore_button_text         = isset( $attributes['exploreButtonText'] ) ? $attributes['exploreButtonText'] : 'Explore More';
$explore_button_bg           = isset( $attributes['exploreButtonBackgroundColor'] ) ? $attributes['exploreButtonBackgroundColor'] : '#1a1a1a';
$explore_button_text_color   = isset( $attributes['exploreButtonTextColor'] ) ? $attributes['exploreButtonTextColor'] : '#ffffff';
$explore_button_border       = isset( $attributes['exploreButtonBorderColor'] ) ? $attributes['exploreButtonBorderColor'] : '#1a1a1a';
$explore_button_border_width = isset( $attributes['exploreButtonBorderWidth'] ) ? $attributes['exploreButtonBorderWidth'] : 0;
$explore_button_radius       = isset( $attributes['exploreButtonBorderRadius'] ) ? $attributes['exploreButtonBorderRadius'] : 0;
$explore_button_hover_bg     = isset( $attributes['exploreButtonHoverBackgroundColor'] ) ? $attributes['exploreButtonHoverBackgroundColor'] : '#d4af37';
$explore_button_hover_text   = isset( $attributes['exploreButtonHoverTextColor'] ) ? $attributes['exploreButtonHoverTextColor'] : '#1a1a1a';
$posts_per_page              = isset( $attributes['postsPerPage'] ) ? $attributes['postsPerPage'] : 3;

/**
 * Filter the number of displayed projects.
 *
 * @param int $posts_per_page Number of projects to display initially.
 */
$posts_per_page = apply_filters( 'gg_displayed_projects_count', $posts_per_page );

/**
 * Action before rendering block.
 */
do_action( 'gg_before_render_block', $attributes, $context ?? array() );

// Get projects from manager (with caching).
$projects_manager = gg_get_projects_manager();
$projects_data    = $projects_manager->get_all_projects();

// Get service area terms for filter buttons.
$service_area_terms = get_terms(
	array(
		'taxonomy'   => 'gg_service_area',
		'hide_empty' => true,
	)
);

// Generate unique block ID for accessibility.
$block_id = 'gg-projects-grid-' . wp_unique_id();

// Initial context for Interactivity API.
$displayed_projects = array_slice( $projects_data, 0, $posts_per_page );
$context            = array(
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

// Build inline styles from block attributes (refactored for readability).
$inline_styles = gg_build_inline_styles( $attributes );

// Wrapper classes and attributes.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                    => 'wp-block-greengrowth-impact-showcase',
		'data-wp-interactive'      => 'greengrowth-showcase',
		'data-wp-context'          => wp_json_encode( $context ),
		'data-show-explore-button' => $show_explore_button ? 'true' : 'false',
		'data-explore-button-text' => esc_attr( $explore_button_text ),
		'data-shadow'              => esc_attr( $shadow_intensity ),
		'style'                    => $inline_styles,
	)
);
?>

<div <?php echo wp_kses_data( $wrapper_attributes ); ?>>

	<nav class="gg-filter-buttons" role="group" aria-label="<?php esc_attr_e( 'Filter projects by service area', 'greengrowth-impact-showcase' ); ?>" data-button-style="<?php echo esc_attr( $button_style ); ?>" data-wp-init="callbacks.initStickyFilterBar">
		<button
			type="button"
			class="gg-filter-button active"
			data-wp-on--click="actions.filterByArea"
			data-wp-class--active="state.isActive"
			data-wp-bind--aria-pressed="state.isActive"
			data-wp-context='<?php echo wp_json_encode( array( 'buttonArea' => 'all' ) ); ?>'
			data-area="all"
			aria-pressed="true"
			aria-controls="<?php echo esc_attr( $block_id ); ?>">
			<?php esc_html_e( 'All Projects', 'greengrowth-impact-showcase' ); ?>
		</button>

		<?php if ( ! empty( $service_area_terms ) && ! is_wp_error( $service_area_terms ) ) : ?>
			<?php foreach ( $service_area_terms as $service_term ) : ?>
				<button
					type="button"
					class="gg-filter-button"
					data-wp-on--click="actions.filterByArea"
					data-wp-class--active="state.isActive"
					data-wp-bind--aria-pressed="state.isActive"
					data-wp-context='<?php echo wp_json_encode( array( 'buttonArea' => $service_term->slug ) ); ?>'
					data-area="<?php echo esc_attr( $service_term->slug ); ?>"
					aria-pressed="false"
					aria-controls="<?php echo esc_attr( $block_id ); ?>">
					<?php echo esc_html( $service_term->name ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>
	</nav>

	<div id="<?php echo esc_attr( $block_id ); ?>" class="gg-projects-grid" data-wp-init="callbacks.initCardHeightNormalization">
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

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

// Extract block attributes with defaults.
$primary_color = isset( $attributes['primaryColor'] ) ? $attributes['primaryColor'] : '#1a1a1a';
$accent_color = isset( $attributes['accentColor'] ) ? $attributes['accentColor'] : '#d4af37';
$background_color = isset( $attributes['backgroundColor'] ) ? $attributes['backgroundColor'] : '#ffffff';
$text_color = isset( $attributes['textColor'] ) ? $attributes['textColor'] : '#1a1a1a';
$card_bg_color = isset( $attributes['cardBackgroundColor'] ) ? $attributes['cardBackgroundColor'] : '#ffffff';
$title_font_size = isset( $attributes['titleFontSize'] ) ? $attributes['titleFontSize'] : 24;
$excerpt_font_size = isset( $attributes['excerptFontSize'] ) ? $attributes['excerptFontSize'] : 16;
$button_font_size = isset( $attributes['buttonFontSize'] ) ? $attributes['buttonFontSize'] : 14;
$card_border_radius = isset( $attributes['cardBorderRadius'] ) ? $attributes['cardBorderRadius'] : 0;
$card_gap = isset( $attributes['cardGap'] ) ? $attributes['cardGap'] : 32;
$shadow_intensity = isset( $attributes['shadowIntensity'] ) ? $attributes['shadowIntensity'] : 'medium';
$overlay_opacity = isset( $attributes['overlayOpacity'] ) ? $attributes['overlayOpacity'] : 0;
$button_style = isset( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'minimal';
$button_bg_color = isset( $attributes['buttonBackgroundColor'] ) ? $attributes['buttonBackgroundColor'] : '#ffffff';
$button_text_color = isset( $attributes['buttonTextColor'] ) ? $attributes['buttonTextColor'] : '#1a1a1a';
$button_border_color = isset( $attributes['buttonBorderColor'] ) ? $attributes['buttonBorderColor'] : '#e0e0e0';
$button_active_bg = isset( $attributes['buttonActiveBackgroundColor'] ) ? $attributes['buttonActiveBackgroundColor'] : '#1a1a1a';
$button_active_text = isset( $attributes['buttonActiveTextColor'] ) ? $attributes['buttonActiveTextColor'] : '#ffffff';
$button_active_border = isset( $attributes['buttonActiveBorderColor'] ) ? $attributes['buttonActiveBorderColor'] : '#1a1a1a';
$button_border_radius = isset( $attributes['buttonBorderRadius'] ) ? $attributes['buttonBorderRadius'] : 0;
$button_border_width = isset( $attributes['buttonBorderWidth'] ) ? $attributes['buttonBorderWidth'] : 1;

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
$context = array(
	'selectedArea'      => 'all',
	'selectedAreaLabel' => __( 'All Projects', 'greengrowth-impact-showcase' ),
	'allProjects'       => $projects_data,
	'filteredProjects'  => $projects_data,
);

// Build inline styles from block attributes.
$inline_styles = sprintf(
	'--gg-primary-color: %s; --gg-accent-color: %s; --gg-background-color: %s; --gg-text-color: %s; --gg-card-bg-color: %s; --gg-title-size: %spx; --gg-excerpt-size: %spx; --gg-button-size: %spx; --gg-card-radius: %spx; --gg-card-gap: %spx; --gg-overlay-opacity: %s; --gg-button-bg: %s; --gg-button-text: %s; --gg-button-border: %s; --gg-button-active-bg: %s; --gg-button-active-text: %s; --gg-button-active-border: %s; --gg-button-radius: %spx; --gg-button-border-width: %spx;',
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
	esc_attr( $button_border_width )
);

// Wrapper classes and attributes.
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'class'                 => 'wp-block-greengrowth-impact-showcase',
		'data-wp-interactive'   => 'greengrowth-showcase',
		'data-wp-context'       => wp_json_encode( $context ),
		'data-shadow'           => esc_attr( $shadow_intensity ),
		'style'                 => $inline_styles,
	)
);
?>

<div <?php echo $wrapper_attributes; ?>>

	<nav class="gg-filter-buttons" role="group" aria-label="<?php esc_attr_e( 'Filter projects by service area', 'greengrowth-impact-showcase' ); ?>" data-button-style="<?php echo esc_attr( $button_style ); ?>">
		<button
			type="button"
			class="gg-filter-button"
			data-wp-on--click="actions.filterByArea"
			data-wp-class--active="state.isActive"
			data-wp-bind--aria-pressed="state.isActive"
			data-wp-context='<?php echo wp_json_encode( array( 'buttonArea' => 'all' ) ); ?>'
			data-area="all">
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
					data-area="<?php echo esc_attr( $term->slug ); ?>">
					<?php echo esc_html( $term->name ); ?>
				</button>
			<?php endforeach; ?>
		<?php endif; ?>
	</nav>

	<div class="gg-projects-grid">
		<?php if ( ! empty( $projects_data ) ) : ?>
			<?php foreach ( $projects_data as $project ) : ?>
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

	<!-- Empty state (shown when filtering) -->
	<div class="gg-empty-state" data-wp-class--hidden="state.hasProjects" style="display: none;">
		<p><?php esc_html_e( 'No projects found in this category.', 'greengrowth-impact-showcase' ); ?></p>
	</div>

	<!-- Accessibility: Live region for screen readers -->
	<div class="sr-only" aria-live="polite" aria-atomic="true">
		<span data-wp-text="state.announcement"></span>
	</div>

</div>

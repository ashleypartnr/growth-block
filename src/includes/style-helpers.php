<?php
/**
 * Style Helper Functions
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build inline styles from block attributes.
 *
 * @param array $attributes Block attributes.
 * @return string CSS inline styles.
 */
function gg_build_inline_styles( $attributes ) {
	$style_parts = array(
		gg_get_color_styles( $attributes ),
		gg_get_typography_styles( $attributes ),
		gg_get_layout_styles( $attributes ),
		gg_get_button_styles( $attributes ),
		gg_get_mobile_button_styles( $attributes ),
		gg_get_explore_button_styles( $attributes ),
	);

	return implode( ' ', array_filter( $style_parts ) );
}

/**
 * Get color styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS color variables.
 */
function gg_get_color_styles( $attributes ) {
	$primary_color    = gg_get_color_value( $attributes, 'primaryColor', '#1a1a1a' );
	$accent_color     = gg_get_color_value( $attributes, 'accentColor', '#d4af37' );
	$background_color = gg_get_color_value( $attributes, 'backgroundColor', '#ffffff' );
	$text_color       = gg_get_color_value( $attributes, 'textColor', '#1a1a1a' );
	$card_bg_color    = gg_get_color_value( $attributes, 'cardBackgroundColor', '#ffffff' );

	return sprintf(
		'--gg-primary-color: %s; --gg-accent-color: %s; --gg-background-color: %s; --gg-text-color: %s; --gg-card-bg-color: %s;',
		esc_attr( $primary_color ),
		esc_attr( $accent_color ),
		esc_attr( $background_color ),
		esc_attr( $text_color ),
		esc_attr( $card_bg_color )
	);
}

/**
 * Get typography styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS typography variables.
 */
function gg_get_typography_styles( $attributes ) {
	$title_font_size   = isset( $attributes['titleFontSize'] ) ? $attributes['titleFontSize'] : 24;
	$excerpt_font_size = isset( $attributes['excerptFontSize'] ) ? $attributes['excerptFontSize'] : 16;
	$button_font_size  = isset( $attributes['buttonFontSize'] ) ? $attributes['buttonFontSize'] : 14;

	return sprintf(
		'--gg-title-size: %spx; --gg-excerpt-size: %spx; --gg-button-size: %spx;',
		esc_attr( $title_font_size ),
		esc_attr( $excerpt_font_size ),
		esc_attr( $button_font_size )
	);
}

/**
 * Get layout styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS layout variables.
 */
function gg_get_layout_styles( $attributes ) {
	$card_border_radius = isset( $attributes['cardBorderRadius'] ) ? $attributes['cardBorderRadius'] : 0;
	$card_gap           = isset( $attributes['cardGap'] ) ? $attributes['cardGap'] : 32;
	$overlay_opacity    = isset( $attributes['overlayOpacity'] ) ? $attributes['overlayOpacity'] : 0;

	return sprintf(
		'--gg-card-radius: %spx; --gg-card-gap: %spx; --gg-overlay-opacity: %s;',
		esc_attr( $card_border_radius ),
		esc_attr( $card_gap ),
		esc_attr( $overlay_opacity / 100 )
	);
}

/**
 * Get button styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS button variables.
 */
function gg_get_button_styles( $attributes ) {
	$button_bg_color      = gg_get_color_value( $attributes, 'buttonBackgroundColor', '#ffffff' );
	$button_text_color    = gg_get_color_value( $attributes, 'buttonTextColor', '#1a1a1a' );
	$button_border_color  = gg_get_color_value( $attributes, 'buttonBorderColor', '#e0e0e0' );
	$button_active_bg     = gg_get_color_value( $attributes, 'buttonActiveBackgroundColor', '#1a1a1a' );
	$button_active_text   = gg_get_color_value( $attributes, 'buttonActiveTextColor', '#ffffff' );
	$button_active_border = gg_get_color_value( $attributes, 'buttonActiveBorderColor', '#1a1a1a' );
	$button_border_radius = isset( $attributes['buttonBorderRadius'] ) ? $attributes['buttonBorderRadius'] : 0;
	$button_border_width  = isset( $attributes['buttonBorderWidth'] ) ? $attributes['buttonBorderWidth'] : 1;
	$button_hover_bg      = isset( $attributes['buttonHoverBackgroundColor'] ) ? $attributes['buttonHoverBackgroundColor'] : '#1a1a1a';
	$button_hover_text    = isset( $attributes['buttonHoverTextColor'] ) ? $attributes['buttonHoverTextColor'] : '#d4af37';
	$button_hover_border  = isset( $attributes['buttonHoverBorderColor'] ) ? $attributes['buttonHoverBorderColor'] : '#d4af37';

	return sprintf(
		'--gg-button-bg: %s; --gg-button-text: %s; --gg-button-border: %s; --gg-button-active-bg: %s; --gg-button-active-text: %s; --gg-button-active-border: %s; --gg-button-radius: %spx; --gg-button-border-width: %spx; --gg-button-hover-bg: %s; --gg-button-hover-text: %s; --gg-button-hover-border: %s;',
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
		esc_attr( $button_hover_border )
	);
}

/**
 * Get mobile button styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS mobile button variables.
 */
function gg_get_mobile_button_styles( $attributes ) {
	$mobile_button_text        = gg_get_color_value( $attributes, 'mobileButtonTextColor', '#1b3a2f' );
	$mobile_button_bg          = gg_get_color_value( $attributes, 'mobileButtonBackgroundColor', '#f0f0f0' );
	$mobile_button_active_text = gg_get_color_value( $attributes, 'mobileButtonActiveTextColor', '#ffffff' );
	$mobile_button_active_bg   = gg_get_color_value( $attributes, 'mobileButtonActiveBackgroundColor', '#1b3a2f' );
	$mobile_button_hover_text  = gg_get_color_value( $attributes, 'mobileButtonHoverTextColor', '#ffffff' );
	$mobile_button_hover_bg    = gg_get_color_value( $attributes, 'mobileButtonHoverBackgroundColor', '#c9a961' );

	return sprintf(
		'--gg-mobile-button-text: %s; --gg-mobile-button-bg: %s; --gg-mobile-button-active-text: %s; --gg-mobile-button-active-bg: %s; --gg-mobile-button-hover-text: %s; --gg-mobile-button-hover-bg: %s;',
		esc_attr( $mobile_button_text ),
		esc_attr( $mobile_button_bg ),
		esc_attr( $mobile_button_active_text ),
		esc_attr( $mobile_button_active_bg ),
		esc_attr( $mobile_button_hover_text ),
		esc_attr( $mobile_button_hover_bg )
	);
}

/**
 * Get explore button styles.
 *
 * @param array $attributes Block attributes.
 * @return string CSS explore button variables.
 */
function gg_get_explore_button_styles( $attributes ) {
	$explore_button_bg           = isset( $attributes['exploreButtonBackgroundColor'] ) ? $attributes['exploreButtonBackgroundColor'] : '#1a1a1a';
	$explore_button_text_color   = isset( $attributes['exploreButtonTextColor'] ) ? $attributes['exploreButtonTextColor'] : '#ffffff';
	$explore_button_border       = isset( $attributes['exploreButtonBorderColor'] ) ? $attributes['exploreButtonBorderColor'] : '#1a1a1a';
	$explore_button_border_width = isset( $attributes['exploreButtonBorderWidth'] ) ? $attributes['exploreButtonBorderWidth'] : 0;
	$explore_button_radius       = isset( $attributes['exploreButtonBorderRadius'] ) ? $attributes['exploreButtonBorderRadius'] : 0;
	$explore_button_hover_bg     = isset( $attributes['exploreButtonHoverBackgroundColor'] ) ? $attributes['exploreButtonHoverBackgroundColor'] : '#d4af37';
	$explore_button_hover_text   = isset( $attributes['exploreButtonHoverTextColor'] ) ? $attributes['exploreButtonHoverTextColor'] : '#1a1a1a';

	return sprintf(
		'--gg-explore-btn-bg: %s; --gg-explore-btn-text: %s; --gg-explore-btn-border: %s; --gg-explore-btn-border-width: %spx; --gg-explore-btn-radius: %spx; --gg-explore-btn-hover-bg: %s; --gg-explore-btn-hover-text: %s;',
		esc_attr( $explore_button_bg ),
		esc_attr( $explore_button_text_color ),
		esc_attr( $explore_button_border ),
		esc_attr( $explore_button_border_width ),
		esc_attr( $explore_button_radius ),
		esc_attr( $explore_button_hover_bg ),
		esc_attr( $explore_button_hover_text )
	);
}

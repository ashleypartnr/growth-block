/**
 * WordPress dependencies
 */
import {
	useBlockProps,
	InspectorControls,
	ColorPalette,
} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import {
	Placeholder,
	Spinner,
	PanelBody,
	RangeControl,
	SelectControl,
	ToggleControl,
	TextControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import metadata from './block.json';

/**
 * Color presets
 */
const COLORS = [
	{ name: 'Transparent', color: 'transparent' },
	{ name: 'Midnight Black', color: '#1a1a1a' },
	{ name: 'Gold', color: '#d4af37' },
	{ name: 'Rose Gold', color: '#b76e79' },
	{ name: 'Champagne', color: '#f7e7ce' },
	{ name: 'Platinum', color: '#e5e4e2' },
	{ name: 'Deep Navy', color: '#001f3f' },
	{ name: 'Emerald', color: '#50c878' },
	{ name: 'Burgundy', color: '#800020' },
	{ name: 'Ivory', color: '#fffff0' },
	{ name: 'Charcoal', color: '#36454f' },
	{ name: 'Pearl White', color: '#faf0e6' },
	{ name: 'Bronze', color: '#cd7f32' },
];

const Heading              = ( { level = 4, className, children, ...props } ) => {
	const Tag              = `h${ level }`;
	const headingClassName = [ 'components-base-control__label', className ]
		.filter( Boolean )
		.join( ' ' );

	return (
		< Tag className = { headingClassName } { ...props } >
			{ children }
		< / Tag >
	);
};

/**
 * Edit component for the Impact Showcase block.
 *
 * @param {Object} props Block properties.
 * @return {JSX.Element} Edit component.
 */
export default function Edit( props ) {
	const { attributes, setAttributes } = props;
	const {
		primaryColor,
		accentColor,
		backgroundColor,
		textColor,
		cardBackgroundColor,
		titleFontSize,
		excerptFontSize,
		buttonFontSize,
		cardBorderRadius,
		cardGap,
		shadowIntensity,
		overlayOpacity,
		buttonStyle,
		buttonBackgroundColor,
		buttonTextColor,
		buttonBorderColor,
		buttonActiveBackgroundColor,
		buttonActiveTextColor,
		buttonActiveBorderColor,
		buttonBorderRadius,
		buttonBorderWidth,
		buttonHoverBackgroundColor,
		buttonHoverTextColor,
		buttonHoverBorderColor,
		mobileButtonTextColor,
		mobileButtonBackgroundColor,
		mobileButtonActiveTextColor,
		mobileButtonActiveBackgroundColor,
		mobileButtonHoverTextColor,
		mobileButtonHoverBackgroundColor,
		showExploreButton,
		exploreButtonText,
		exploreButtonBackgroundColor,
		exploreButtonTextColor,
		exploreButtonBorderColor,
		exploreButtonBorderWidth,
		exploreButtonBorderRadius,
		exploreButtonHoverBackgroundColor,
		exploreButtonHoverTextColor,
		postsPerPage,
	}                                   = attributes;

	const blockProps = useBlockProps(
		{
			'data-shadow': shadowIntensity,
			'data-button-style': buttonStyle,
			style: {
				'--gg-primary-color': primaryColor,
				'--gg-accent-color': accentColor,
				'--gg-background-color': backgroundColor,
				'--gg-text-color': textColor,
				'--gg-card-bg-color': cardBackgroundColor,
				'--gg-title-size': `${ titleFontSize }px`,
				'--gg-excerpt-size': `${ excerptFontSize }px`,
				'--gg-button-size': `${ buttonFontSize }px`,
				'--gg-card-radius': `${ cardBorderRadius }px`,
				'--gg-card-gap': `${ cardGap }px`,
				'--gg-overlay-opacity': overlayOpacity / 100,
				'--gg-button-bg': buttonBackgroundColor,
				'--gg-button-text': buttonTextColor,
				'--gg-button-border': buttonBorderColor,
				'--gg-button-active-bg': buttonActiveBackgroundColor,
				'--gg-button-active-text': buttonActiveTextColor,
				'--gg-button-active-border': buttonActiveBorderColor,
				'--gg-button-radius': `${ buttonBorderRadius }px`,
				'--gg-button-border-width': `${ buttonBorderWidth }px`,
				'--gg-button-hover-bg': buttonHoverBackgroundColor,
				'--gg-button-hover-text': buttonHoverTextColor,
				'--gg-button-hover-border': buttonHoverBorderColor,
				'--gg-mobile-button-text': mobileButtonTextColor,
				'--gg-mobile-button-bg': mobileButtonBackgroundColor,
				'--gg-mobile-button-active-text': mobileButtonActiveTextColor,
				'--gg-mobile-button-active-bg': mobileButtonActiveBackgroundColor,
				'--gg-mobile-button-hover-text': mobileButtonHoverTextColor,
				'--gg-mobile-button-hover-bg': mobileButtonHoverBackgroundColor,
				'--gg-explore-btn-bg': exploreButtonBackgroundColor,
				'--gg-explore-btn-text': exploreButtonTextColor,
				'--gg-explore-btn-border': exploreButtonBorderColor,
				'--gg-explore-btn-border-width': `${ exploreButtonBorderWidth }px`,
				'--gg-explore-btn-radius': `${ exploreButtonBorderRadius }px`,
				'--gg-explore-btn-hover-bg': exploreButtonHoverBackgroundColor,
				'--gg-explore-btn-hover-text': exploreButtonHoverTextColor,
			},
		}
	);

	return (
		< >
			< InspectorControls >
				{ /* General Colors */ }
				< PanelBody
					title           = { __( 'Colors', 'greengrowth-impact-showcase' ) }
					initialOpen     = { true }
				>
					< Heading level = { 4 } >
						{ __(
							'Background Color',
							'greengrowth-impact-showcase'
						) }
					< / Heading >
					< p className   = "components-base-control__help" >
						{ __(
							'Main background color',
							'greengrowth-impact-showcase'
						) }
					< / p >
					< ColorPalette
						colors      = { COLORS }
						value       = { backgroundColor }
						onChange    = { ( color ) =>
							setAttributes( { backgroundColor: color } )
						}
					/ >

					< Heading level = { 4 } >
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< p className   = "components-base-control__help" >
						{ __(
							'Main text color',
							'greengrowth-impact-showcase'
						) }
					< / p >
					< ColorPalette
						colors      = { COLORS }
						value       = { textColor }
						onChange    = { ( color ) =>
							setAttributes( { textColor: color } )
						}
					/ >

					< Heading level = { 4 } >
						{ __( 'Primary Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< p className   = "components-base-control__help" >
						{ __(
							'Brand color for accents',
							'greengrowth-impact-showcase'
						) }
					< / p >
					< ColorPalette
						colors      = { COLORS }
						value       = { primaryColor }
						onChange    = { ( color ) =>
							setAttributes( { primaryColor: color } )
						}
					/ >

					< Heading level = { 4 } >
						{ __( 'Accent Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< p className   = "components-base-control__help" >
						{ __(
							'Highlight color for hover states',
							'greengrowth-impact-showcase'
						) }
					< / p >
					< ColorPalette
						colors      = { COLORS }
						value       = { accentColor }
						onChange    = { ( color ) =>
							setAttributes( { accentColor: color } )
						}
					/ >
				< / PanelBody >

				{ /* Card Settings */ }
				< PanelBody
					title        = { __(
						'Card Settings',
						'greengrowth-impact-showcase'
					) }
					initialOpen  = { false }
				>
					< RangeControl
						label    = { __(
							'Posts Per Page',
							'greengrowth-impact-showcase'
						) }
						value    = { postsPerPage }
						onChange = { ( value ) =>
							setAttributes( { postsPerPage: value } )
						}
						min      = { 1 }
						max      = { 12 }
						step     = { 1 }
						help     = { __(
							'Number of projects to load initially and on each scroll',
							'greengrowth-impact-showcase'
						) }
					/ >

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __(
							'Card Background',
							'greengrowth-impact-showcase'
						) }
					< / Heading >
					< ColorPalette
						colors      = { COLORS }
						value       = { cardBackgroundColor }
						onChange    = { ( color ) =>
							setAttributes( { cardBackgroundColor: color } )
						}
					/ >

					< RangeControl
						label    = { __(
							'Card Border Radius',
							'greengrowth-impact-showcase'
						) }
						value    = { cardBorderRadius }
						onChange = { ( value ) =>
							setAttributes( { cardBorderRadius: value } )
						}
						min      = { 0 }
						max      = { 40 }
						step     = { 1 }
						help     = { __(
							'0 for sharp edges, 40 for rounded',
							'greengrowth-impact-showcase'
						) }
					/ >

					< RangeControl
						label    = { __(
							'Card Gap',
							'greengrowth-impact-showcase'
						) }
						value    = { cardGap }
						onChange = { ( value ) =>
							setAttributes( { cardGap: value } )
						}
						min      = { 16 }
						max      = { 80 }
						step     = { 4 }
						help     = { __(
							'Space between cards',
							'greengrowth-impact-showcase'
						) }
					/ >

					< SelectControl
						label    = { __(
							'Shadow Intensity',
							'greengrowth-impact-showcase'
						) }
						value    = { shadowIntensity }
						options  = { [
							{
								label: __(
									'None',
									'greengrowth-impact-showcase'
								),
							value: 'none',
							},
							{
								label: __(
									'Subtle',
									'greengrowth-impact-showcase'
								),
							value: 'subtle',
							},
							{
								label: __(
									'Medium',
									'greengrowth-impact-showcase'
								),
							value: 'medium',
							},
							{
								label: __(
									'Strong',
									'greengrowth-impact-showcase'
								),
							value: 'strong',
							},
							{
								label: __(
									'Dramatic',
									'greengrowth-impact-showcase'
								),
							value: 'dramatic',
							},
							] }
						onChange = { ( value ) =>
							setAttributes( { shadowIntensity: value } )
						}
						help     = { __(
							'Card shadow depth',
							'greengrowth-impact-showcase'
						) }
					/ >

					< RangeControl
						label    = { __(
							'Image Overlay Opacity',
							'greengrowth-impact-showcase'
						) }
						value    = { overlayOpacity }
						onChange = { ( value ) =>
							setAttributes( { overlayOpacity: value } )
						}
						min      = { 0 }
						max      = { 70 }
						step     = { 5 }
						help     = { __(
							'Dark overlay on images',
							'greengrowth-impact-showcase'
						) }
					/ >

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Explore Link', 'greengrowth-impact-showcase' ) }
					< / Heading >

					< ToggleControl
						label    = { __(
							'Show Explore Link',
							'greengrowth-impact-showcase'
						) }
						checked  = { showExploreButton }
						onChange = { ( value ) =>
							setAttributes( { showExploreButton: value } )
						}
						help     = { __(
							'Display a link with arrow icon on each card',
							'greengrowth-impact-showcase'
						) }
					/ >

					{ showExploreButton && (
						< >
							< TextControl
								label    = { __(
									'Link Text',
									'greengrowth-impact-showcase'
								) }
								value    = { exploreButtonText }
								onChange = { ( value ) =>
									setAttributes(
										{
											exploreButtonText: value,
										}
									)
								}
							/ >

							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Text Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { exploreButtonTextColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											exploreButtonTextColor: color,
										}
									)
								}
							/ >

							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Hover Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { exploreButtonHoverTextColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											exploreButtonHoverTextColor: color,
										}
									)
								}
							/ >
						< / >
					) }
				< / PanelBody >

				{ /* Typography */ }
				< PanelBody
					title        = { __( 'Typography', 'greengrowth-impact-showcase' ) }
					initialOpen  = { false }
				>
					< RangeControl
						label    = { __(
							'Title Font Size',
							'greengrowth-impact-showcase'
						) }
						value    = { titleFontSize }
						onChange = { ( value ) =>
							setAttributes( { titleFontSize: value } )
						}
						min      = { 16 }
						max      = { 48 }
						step     = { 1 }
						help     = { __(
							'Project card titles',
							'greengrowth-impact-showcase'
						) }
					/ >

					< RangeControl
						label    = { __(
							'Excerpt Font Size',
							'greengrowth-impact-showcase'
						) }
						value    = { excerptFontSize }
						onChange = { ( value ) =>
							setAttributes( { excerptFontSize: value } )
						}
						min      = { 12 }
						max      = { 24 }
						step     = { 1 }
						help     = { __(
							'Project descriptions',
							'greengrowth-impact-showcase'
						) }
					/ >

					< RangeControl
						label    = { __(
							'Button Font Size',
							'greengrowth-impact-showcase'
						) }
						value    = { buttonFontSize }
						onChange = { ( value ) =>
							setAttributes( { buttonFontSize: value } )
						}
						min      = { 11 }
						max      = { 20 }
						step     = { 1 }
						help     = { __(
							'Filter button text',
							'greengrowth-impact-showcase'
						) }
					/ >
				< / PanelBody >

				{ /* Filter Buttons */ }
				< PanelBody
					title        = { __(
						'Filter Buttons',
						'greengrowth-impact-showcase'
					) }
					initialOpen  = { false }
				>
					< SelectControl
						label    = { __(
							'Button Style',
							'greengrowth-impact-showcase'
						) }
						value    = { buttonStyle }
						options  = { [
							{
								label: __(
									'Text Only',
									'greengrowth-impact-showcase'
								),
							value: 'text',
							},
							{
								label: __(
									'Button',
									'greengrowth-impact-showcase'
								),
							value: 'button',
							},
							{
								label: __(
									'Underline',
									'greengrowth-impact-showcase'
								),
							value: 'underline',
							},
							] }
						onChange = { ( value ) =>
							setAttributes( { buttonStyle: value } )
						}
						help     = { __(
							'Visual style of filters',
							'greengrowth-impact-showcase'
						) }
					/ >

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< ColorPalette
						colors      = { COLORS }
						value       = { buttonTextColor }
						onChange    = { ( color ) =>
							setAttributes( { buttonTextColor: color } )
						}
					/ >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 4 }
								style    = { { marginTop: '24px' } }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { buttonBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											buttonBackgroundColor: color,
										}
									)
								}
							/ >

							< RangeControl
								label    = { __(
									'Border Radius',
									'greengrowth-impact-showcase'
								) }
								value    = { buttonBorderRadius }
								onChange = { ( value ) =>
									setAttributes(
										{
											buttonBorderRadius: value,
										}
									)
								}
								min      = { 0 }
								max      = { 50 }
								step     = { 1 }
							/ >
						< / >
					) }

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Active State', 'greengrowth-impact-showcase' ) }
					< / Heading >

					< Heading
						level    = { 5 }
						style    = { { fontSize: '13px', marginTop: '16px' } }
					>
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< ColorPalette
						colors   = { COLORS }
						value    = { buttonActiveTextColor }
						onChange = { ( color ) =>
							setAttributes( { buttonActiveTextColor: color } )
						}
					/ >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { buttonActiveBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											buttonActiveBackgroundColor: color,
										}
									)
								}
							/ >
						< / >
					) }

					{ ( buttonStyle === 'text' ||
						buttonStyle === 'underline' ) && (
						< >
							< Heading
								level     = { 5 }
								style     = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Accent Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< p
								className = "components-base-control__help"
								style     = { {
									marginTop: '-8px',
									marginBottom: '8px',
									} }
							>
								{ __(
									'Underline color for selected filter',
									'greengrowth-impact-showcase'
								) }
							< / p >
							< ColorPalette
								colors    = { COLORS }
								value     = { buttonActiveBackgroundColor }
								onChange  = { ( color ) =>
									setAttributes(
										{
											buttonActiveBackgroundColor: color,
										}
									)
								}
							/ >
						< / >
					) }

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Hover State', 'greengrowth-impact-showcase' ) }
					< / Heading >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { buttonHoverBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											buttonHoverBackgroundColor: color,
										}
									)
								}
							/ >

							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Text Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { buttonHoverTextColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											buttonHoverTextColor: color,
										}
									)
								}
							/ >
						< / >
					) }

					{ ( buttonStyle === 'text' ||
						buttonStyle === 'underline' ) && (
						< >
							< Heading
								level     = { 5 }
								style     = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __( 'Color', 'greengrowth-impact-showcase' ) }
							< / Heading >
							< p
								className = "components-base-control__help"
								style     = { {
									marginTop: '-8px',
									marginBottom: '8px',
									} }
							>
								{ __(
									'Text and underline color',
									'greengrowth-impact-showcase'
								) }
							< / p >
							< ColorPalette
								colors    = { COLORS }
								value     = { buttonHoverTextColor }
								onChange  = { ( color ) =>
									setAttributes(
										{
											buttonHoverTextColor: color,
										}
									)
								}
							/ >
						< / >
					) }
				< / PanelBody >

				{ /* Mobile Filter Bar */ }
				< PanelBody
					title         = { __(
						'Mobile Filter Bar',
						'greengrowth-impact-showcase'
					) }
					initialOpen   = { false }
				>
					< p className = "components-base-control__help" >
						{ __(
							'Overrides filter button colors on screens 768px and under.',
							'greengrowth-impact-showcase'
						) }
					< / p >

					< Heading level = { 4 } >
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< ColorPalette
						colors      = { COLORS }
						value       = { mobileButtonTextColor }
						onChange    = { ( color ) =>
							setAttributes( { mobileButtonTextColor: color } )
						}
					/ >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 4 }
								style    = { { marginTop: '24px' } }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { mobileButtonBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											mobileButtonBackgroundColor: color,
										}
									)
								}
							/ >
						< / >
					) }

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Active State', 'greengrowth-impact-showcase' ) }
					< / Heading >

					< Heading
						level    = { 5 }
						style    = { { fontSize: '13px', marginTop: '16px' } }
					>
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					< / Heading >
					< ColorPalette
						colors   = { COLORS }
						value    = { mobileButtonActiveTextColor }
						onChange = { ( color ) =>
							setAttributes(
								{
									mobileButtonActiveTextColor: color,
								}
							)
						}
					/ >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { mobileButtonActiveBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											mobileButtonActiveBackgroundColor:
												color,
										}
									)
								}
							/ >
						< / >
					) }

					{ ( buttonStyle === 'text' ||
						buttonStyle === 'underline' ) && (
						< >
							< Heading
								level     = { 5 }
								style     = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Accent Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< p
								className = "components-base-control__help"
								style     = { {
									marginTop: '-8px',
									marginBottom: '8px',
									} }
							>
								{ __(
									'Underline color for selected filter',
									'greengrowth-impact-showcase'
								) }
							< / p >
							< ColorPalette
								colors    = { COLORS }
								value     = { mobileButtonActiveBackgroundColor }
								onChange  = { ( color ) =>
									setAttributes(
										{
											mobileButtonActiveBackgroundColor:
												color,
										}
									)
								}
							/ >
						< / >
					) }

					< hr
						style = { {
							margin: '24px 0',
							border: 'none',
							borderTop: '1px solid #ddd',
							} }
					/ >

					< Heading level = { 4 } >
						{ __( 'Hover State', 'greengrowth-impact-showcase' ) }
					< / Heading >

					{ buttonStyle === 'button' && (
						< >
							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Background Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { mobileButtonHoverBackgroundColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											mobileButtonHoverBackgroundColor: color,
										}
									)
								}
							/ >

							< Heading
								level    = { 5 }
								style    = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __(
									'Text Color',
									'greengrowth-impact-showcase'
								) }
							< / Heading >
							< ColorPalette
								colors   = { COLORS }
								value    = { mobileButtonHoverTextColor }
								onChange = { ( color ) =>
									setAttributes(
										{
											mobileButtonHoverTextColor: color,
										}
									)
								}
							/ >
						< / >
					) }

					{ ( buttonStyle === 'text' ||
						buttonStyle === 'underline' ) && (
						< >
							< Heading
								level     = { 5 }
								style     = { {
									fontSize: '13px',
									marginTop: '16px',
									} }
							>
								{ __( 'Color', 'greengrowth-impact-showcase' ) }
							< / Heading >
							< p
								className = "components-base-control__help"
								style     = { {
									marginTop: '-8px',
									marginBottom: '8px',
									} }
							>
								{ __(
									'Text and underline color',
									'greengrowth-impact-showcase'
								) }
							< / p >
							< ColorPalette
								colors    = { COLORS }
								value     = { mobileButtonHoverTextColor }
								onChange  = { ( color ) =>
									setAttributes(
										{
											mobileButtonHoverTextColor: color,
										}
									)
								}
							/ >
						< / >
					) }
				< / PanelBody >
			< / InspectorControls >

			< div { ...blockProps } >
				< ServerSideRender
					block                      = { metadata.name }
					attributes                 = { attributes }
					LoadingResponsePlaceholder = { () => (
						< Placeholder
							icon               = "admin-site-alt3"
							label              = { __(
								'Impact Showcase',
								'greengrowth-impact-showcase'
							) }
						>
							< Spinner / >
						< / Placeholder >
					) }
					ErrorResponsePlaceholder = { () => (
						< Placeholder
							icon             = "warning"
							label            = { __(
								'Impact Showcase',
								'greengrowth-impact-showcase'
							) }
						>
							< p >
								{ __(
									'Error loading projects. Please ensure the GreenGrowth plugin is properly configured.',
									'greengrowth-impact-showcase'
								) }
							< / p >
						< / Placeholder >
					) }
					EmptyResponsePlaceholder = { () => (
						< Placeholder
							icon             = "admin-site-alt3"
							label            = { __(
								'Impact Showcase',
								'greengrowth-impact-showcase'
							) }
						>
							< p >
								{ __(
									'No projects found. Add some projects to get started.',
									'greengrowth-impact-showcase'
								) }
							< / p >
						< / Placeholder >
					) }
				/ >
			< / div >
		< / >
	);
}

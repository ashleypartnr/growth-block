/**
 * WordPress dependencies
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import {
	Placeholder,
	Spinner,
	PanelBody,
	RangeControl,
	SelectControl,
	__experimentalHStack as HStack,
	__experimentalHeading as Heading,
} from '@wordpress/components';
import { ColorPalette } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import metadata from './block.json';

/**
 * Luxury color presets
 */
const COLORS = [
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
	} = attributes;

	const blockProps = useBlockProps( {
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
		},
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Color Settings', 'greengrowth-impact-showcase' ) }
					initialOpen={ true }
				>
					<Heading level={ 4 }>
						{ __( 'Primary Color', 'greengrowth-impact-showcase' ) }
					</Heading>
					<p className="components-base-control__help">
						{ __(
							'Main brand color for active buttons',
							'greengrowth-impact-showcase'
						) }
					</p>
					<ColorPalette
						colors={ COLORS }
						value={ primaryColor }
						onChange={ ( color ) => setAttributes( { primaryColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Accent Color', 'greengrowth-impact-showcase' ) }
					</Heading>
					<p className="components-base-control__help">
						{ __(
							'Luxury accent color for highlights',
							'greengrowth-impact-showcase'
						) }
					</p>
					<ColorPalette
						colors={ COLORS }
						value={ accentColor }
						onChange={ ( color ) => setAttributes( { accentColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Background Color', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ backgroundColor }
						onChange={ ( color ) => setAttributes( { backgroundColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Text Color', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ textColor }
						onChange={ ( color ) => setAttributes( { textColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Card Background', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ cardBackgroundColor }
						onChange={ ( color ) =>
							setAttributes( { cardBackgroundColor: color } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Typography', 'greengrowth-impact-showcase' ) }
					initialOpen={ false }
				>
					<RangeControl
						label={ __( 'Title Font Size', 'greengrowth-impact-showcase' ) }
						value={ titleFontSize }
						onChange={ ( value ) => setAttributes( { titleFontSize: value } ) }
						min={ 16 }
						max={ 48 }
						step={ 1 }
						help={ __( 'Size of project card titles', 'greengrowth-impact-showcase' ) }
					/>

					<RangeControl
						label={ __( 'Excerpt Font Size', 'greengrowth-impact-showcase' ) }
						value={ excerptFontSize }
						onChange={ ( value ) => setAttributes( { excerptFontSize: value } ) }
						min={ 12 }
						max={ 24 }
						step={ 1 }
						help={ __(
							'Size of project descriptions',
							'greengrowth-impact-showcase'
						) }
					/>

					<RangeControl
						label={ __( 'Button Font Size', 'greengrowth-impact-showcase' ) }
						value={ buttonFontSize }
						onChange={ ( value ) => setAttributes( { buttonFontSize: value } ) }
						min={ 11 }
						max={ 20 }
						step={ 1 }
						help={ __( 'Size of filter button text', 'greengrowth-impact-showcase' ) }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Layout & Spacing', 'greengrowth-impact-showcase' ) }
					initialOpen={ false }
				>
					<RangeControl
						label={ __( 'Card Border Radius', 'greengrowth-impact-showcase' ) }
						value={ cardBorderRadius }
						onChange={ ( value ) => setAttributes( { cardBorderRadius: value } ) }
						min={ 0 }
						max={ 40 }
						step={ 1 }
						help={ __(
							'0 for sharp edges, 40 for ultra rounded',
							'greengrowth-impact-showcase'
						) }
					/>

					<RangeControl
						label={ __( 'Card Gap', 'greengrowth-impact-showcase' ) }
						value={ cardGap }
						onChange={ ( value ) => setAttributes( { cardGap: value } ) }
						min={ 16 }
						max={ 80 }
						step={ 4 }
						help={ __( 'Space between project cards', 'greengrowth-impact-showcase' ) }
					/>

					<SelectControl
						label={ __( 'Shadow Intensity', 'greengrowth-impact-showcase' ) }
						value={ shadowIntensity }
						options={ [
							{ label: __( 'None', 'greengrowth-impact-showcase' ), value: 'none' },
							{
								label: __( 'Subtle', 'greengrowth-impact-showcase' ),
								value: 'subtle',
							},
							{
								label: __( 'Medium', 'greengrowth-impact-showcase' ),
								value: 'medium',
							},
							{
								label: __( 'Strong', 'greengrowth-impact-showcase' ),
								value: 'strong',
							},
							{
								label: __( 'Dramatic', 'greengrowth-impact-showcase' ),
								value: 'dramatic',
							},
						] }
						onChange={ ( value ) => setAttributes( { shadowIntensity: value } ) }
						help={ __(
							'Card shadow depth for luxury feel',
							'greengrowth-impact-showcase'
						) }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Advanced Style', 'greengrowth-impact-showcase' ) }
					initialOpen={ false }
				>
					<RangeControl
						label={ __( 'Image Overlay Opacity', 'greengrowth-impact-showcase' ) }
						value={ overlayOpacity }
						onChange={ ( value ) => setAttributes( { overlayOpacity: value } ) }
						min={ 0 }
						max={ 70 }
						step={ 5 }
						help={ __(
							'Dark overlay on card images for text contrast',
							'greengrowth-impact-showcase'
						) }
					/>

					<SelectControl
						label={ __( 'Filter Button Style', 'greengrowth-impact-showcase' ) }
						value={ buttonStyle }
						options={ [
							{
								label: __( 'Minimal', 'greengrowth-impact-showcase' ),
								value: 'minimal',
							},
							{
								label: __( 'Outlined', 'greengrowth-impact-showcase' ),
								value: 'outlined',
							},
							{ label: __( 'Filled', 'greengrowth-impact-showcase' ), value: 'filled' },
							{
								label: __( 'Underline', 'greengrowth-impact-showcase' ),
								value: 'underline',
							},
						] }
						onChange={ ( value ) => setAttributes( { buttonStyle: value } ) }
						help={ __(
							'Visual style of category filters',
							'greengrowth-impact-showcase'
						) }
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Button Colors', 'greengrowth-impact-showcase' ) }
					initialOpen={ false }
				>
					<Heading level={ 4 }>
						{ __( 'Button Background (Default)', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonBackgroundColor }
						onChange={ ( color ) => setAttributes( { buttonBackgroundColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Button Text (Default)', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonTextColor }
						onChange={ ( color ) => setAttributes( { buttonTextColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Button Border (Default)', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonBorderColor }
						onChange={ ( color ) => setAttributes( { buttonBorderColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Active Button Background', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonActiveBackgroundColor }
						onChange={ ( color ) => setAttributes( { buttonActiveBackgroundColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Active Button Text', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonActiveTextColor }
						onChange={ ( color ) => setAttributes( { buttonActiveTextColor: color } ) }
					/>

					<Heading level={ 4 }>
						{ __( 'Active Button Border', 'greengrowth-impact-showcase' ) }
					</Heading>
					<ColorPalette
						colors={ COLORS }
						value={ buttonActiveBorderColor }
						onChange={ ( color ) => setAttributes( { buttonActiveBorderColor: color } ) }
					/>

					<RangeControl
						label={ __( 'Button Border Radius', 'greengrowth-impact-showcase' ) }
						value={ buttonBorderRadius }
						onChange={ ( value ) => setAttributes( { buttonBorderRadius: value } ) }
						min={ 0 }
						max={ 50 }
						step={ 1 }
					/>

					<RangeControl
						label={ __( 'Button Border Width', 'greengrowth-impact-showcase' ) }
						value={ buttonBorderWidth }
						onChange={ ( value ) => setAttributes( { buttonBorderWidth: value } ) }
						min={ 0 }
						max={ 5 }
						step={ 1 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
					LoadingResponsePlaceholder={ () => (
						<Placeholder
							icon="admin-site-alt3"
							label={ __( 'Impact Showcase', 'greengrowth-impact-showcase' ) }
						>
							<Spinner />
						</Placeholder>
					) }
					ErrorResponsePlaceholder={ () => (
						<Placeholder
							icon="warning"
							label={ __( 'Impact Showcase', 'greengrowth-impact-showcase' ) }
						>
							<p>
								{ __(
									'Error loading projects. Please ensure the GreenGrowth plugin is properly configured.',
									'greengrowth-impact-showcase'
								) }
							</p>
						</Placeholder>
					) }
					EmptyResponsePlaceholder={ () => (
						<Placeholder
							icon="admin-site-alt3"
							label={ __( 'Impact Showcase', 'greengrowth-impact-showcase' ) }
						>
							<p>
								{ __(
									'No projects found. Add some projects to get started.',
									'greengrowth-impact-showcase'
								) }
							</p>
						</Placeholder>
					) }
				/>
			</div>
		</>
	);
}

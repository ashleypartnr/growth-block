/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import './style.scss';
import Edit from './edit';
import metadata from './block.json';

/**
 * Register the Impact Showcase block.
 */
registerBlockType( metadata.name, {
	...metadata,
	edit: Edit,
} );

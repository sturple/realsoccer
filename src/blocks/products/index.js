/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import edit from './edit';
import './index.scss';

registerBlockType( metadata, {
	edit: edit,
	save: () => null,
} );
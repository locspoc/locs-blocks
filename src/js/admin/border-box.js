import { TextControl, ColorPicker } from '@wordpress/components';

/**
 * Border Box Block
 */

wp.blocks.registerBlockType('locs-blocks/border-box', {
	title: 'My Cool Border Box',
	description: 'Adds a cool border to your box and specify the color.',
	icon: 'smiley',
	category: 'locs_blocks',
	attributes: {
		locs_blocks_content: { type: 'string' },
		locs_blocks_color: { type: 'string' },
	},
	edit: function (props) {
		function updateContent(value) {
			props.setAttributes({ locs_blocks_content: value });
		}

		function updateColor(value) {
			props.setAttributes({ locs_blocks_color: value.hex });
		}

		return (
			<div className="border-box-edit-block">
				<h3>Your Cool Border Box</h3>
				<TextControl
					className="border-box-content"
					value={props.attributes.locs_blocks_content}
					onChange={updateContent}
				/>
				<ColorPicker
					color={props.attributes.locs_blocks_color}
					onChangeComplete={updateColor}
				/>
			</div>
		);
	},
	save: function (props) {
		return null;
	},
});

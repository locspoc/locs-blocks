/**
 * Demo Block
 */

wp.blocks.registerBlockType('locs-blocks/demo', {
	title: 'Demo',
	description:
		'Demo block allows you to specify a sky and grass colour on the back end and display it on the front end',
	icon: 'smiley',
	category: 'locs_blocks',
	attributes: {
		skyColor: { type: 'string' },
		grassColor: { type: 'string' },
	},
	edit: function (props) {
		function updateSkyColor(event) {
			props.setAttributes({ skyColor: event.target.value });
		}

		function updateGrassColor(event) {
			props.setAttributes({ grassColor: event.target.value });
		}

		return (
			<div>
				<input
					type="text"
					placeholder="sky color"
					value={props.attributes.skyColor}
					onChange={updateSkyColor}
				/>
				<input
					type="text"
					placeholder="grass color"
					value={props.attributes.grassColor}
					onChange={updateGrassColor}
				/>
			</div>
		);
	},
	save: function (props) {
		return null;
	},
});

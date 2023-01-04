/**
 * Page Tree Block
 *
 * wp.data.select("core").getEntityRecords("postType", "page", {per_page: -1})
 *
 * TODO:
 */

import '../../scss/page-tree.scss';
import '../../scss/admin/page-tree.scss';
import { useSelect } from '@wordpress/data';
import React, { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import {
	TextControl,
	Flex,
	FlexBlock,
	FlexItem,
	Button,
	Icon,
	PanelBody,
	PanelRow,
	ColorPicker,
} from '@wordpress/components';

wp.blocks.registerBlockType('locs-blocks/page-tree', {
	title: 'Page Tree',
	description: 'Display selected parent page and all child pages',
	icon: 'smiley',
	category: 'locs_blocks',
	attributes: {
		pageTreeId: { type: 'string' },
	},
	edit: EditComponent,
	save: function (props) {
		return null;
	},
});

function EditComponent(props) {
	const [thePreview, setThePreview] = useState('');

	useEffect(() => {
		async function go() {
			const response = await apiFetch({
				path: `/pageTree/v1/getHTML?pageTreeId=${props.attributes.pageTreeId}`,
				method: 'GET',
			});
			setThePreview(response);
		}
		go();
	}, [props.attributes.pageTreeId]);

	const allPages = useSelect((select) => {
		return select('core').getEntityRecords('postType', 'page', {
			per_page: -1,
			orderby: 'title',
			order: 'asc',
		});
	});

	// console.log(allPages);

	if (allPages == undefined) return <p>Loading...</p>;

	return (
		<div className="page-tree-edit-block">
			<div className="page-tree-select-container">
				<label>Select parent page:</label>

				<select
					style={{ fontSize: '20px' }}
					onChange={(e) =>
						props.setAttributes({ pageTreeId: e.target.value })
					}
				>
					<option value="">Select a page</option>

					{allPages.map((page) => {
						return (
							<option
								value={page.id}
								selected={
									props.attributes.pageTreeId == page.id
								}
							>
								{page.title.rendered}
							</option>
						);
					})}
				</select>
			</div>

			<div dangerouslySetInnerHTML={{ __html: thePreview }}></div>
		</div>
	);
}

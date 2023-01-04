/**
 * Featured Page Block
 *
 * wp.data.select("core").getEntityRecords("postType", "page", {per_page: -1})
 *
 * TODO:
 * How to exclude preview when a page has not been selected?
 */

import '../../scss/featured-page.scss';
import { useSelect } from '@wordpress/data';
import { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

wp.blocks.registerBlockType('locs-blocks/featured-page', {
	title: 'Featured Page',
	description:
		'Feature another page from your website to improve internal linking',
	icon: 'smiley',
	category: 'locs_blocks',
	attributes: {
		featuredPageId: { type: 'string' },
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
				path: `/featuredPage/v1/getHTML?featuredPageId=${props.attributes.featuredPageId}`,
				method: 'GET',
			});
			setThePreview(response);
		}
		go();
	}, [props.attributes.featuredPageId]);

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
		<div className="featured-page-wrapper">
			<div className="featured-page-select-container">
				<select
					onChange={(e) =>
						props.setAttributes({ featuredPageId: e.target.value })
					}
				>
					<option value="">Select a page</option>
					{allPages.map((page) => {
						return (
							<option
								value={page.id}
								selected={
									props.attributes.featuredPageId == page.id
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

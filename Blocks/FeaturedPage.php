<?php

if (!defined('ABSPATH')) {
	exit; // disable direct access.
}

/**
 * Cache Busting Version Number
 */

$featuredPageAssets = include 'build/featured-page.asset.php'; // Build version number

class LocsBlocksFeaturedPage
{

	private static $Instance;
	private static $Name = 'locs-blocks/featured-page';

	/**
	 * PHP class constructor
	 */
	function __construct()
	{

		add_action('rest_api_init', [$this, 'featuredPageHTML']);
	}

	/**
	 * Register WP block type
	 */
	public static function registerBlock()
	{

		// die("border box");
		if (empty(self::$Instance)) {

			self::$Instance = new self();

			register_block_type(
				self::$Name,
				array(
					'render_callback' => array(self::$Instance, 'render'),
					'category' => 'locs_blocks',
				)
			);
		}
	}

	/**
	 * Featured Page Preview
	 */
	function featuredPageHTML()
	{
		register_rest_route('featuredPage/v1', 'getHTML', array(
			'methods' => WP_REST_SERVER::READABLE,
			'callback' => [$this, 'getFeaturedPageHTML']
		));
	}

	function getFeaturedPageHTML($props)
	{

		// if ($props['featuredPageId'] != undefined ) {

		return locs_blocks::render('featured-page', $props);

		// } else {

		// return NULL;

		// }

	}

	/**
	 * Render template frontend
	 */
	public function render($props)
	{

		if ($props['featuredPageId']) {

			if (!is_admin()) {

				// Add scripts and styles
				wp_enqueue_script('locs-blocks-featured-page', Locs_Blocks::pluginUrl() . 'build/featured-page.js', array('wp-blocks', 'wp-element', 'wp-editor'), $featuredPageAssets['version'], true);
				wp_enqueue_style('locs-blocks-featured-page', Locs_Blocks::pluginUrl() . 'build/featured-page.css', [], $featuredPageAssets['version']);

				// return '<div class="featured-page-callout">Hello</div>';

				return Locs_Blocks::render('featured-page', $props);
			}
		} else {

			return NULL;
		}
	}
}

<?php

if (!defined('ABSPATH')) {
	exit; // disable direct access.
}

/**
 * Cache Busting Version Number
 */

$demoAssets = include 'build/demo.asset.php'; // Build version number

class LocsBlocksDemo
{

	private static $Instance;
	private static $Name = 'locs-blocks/demo';

	/**
	 * Register WP block type
	 */
	public static function registerBlock()
	{
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

	public function render($props)
	{

		if (!is_admin()) {

			// Add scripts and styles
			wp_enqueue_script('locs-blocks-demo', plugin_dir_url(__FILE__) . 'build/demo.js', array(), $demoAssets['version'], true);
			wp_enqueue_style('locs-blocks-demo', plugin_dir_url(__FILE__) . 'build/demo.css', [], $demoAssets['version']);
		}

		return locs_blocks::render('demo', $props);
	}
}
